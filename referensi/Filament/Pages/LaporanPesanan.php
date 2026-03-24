<?php

namespace App\Filament\Pages;

use App\Models\Order;
use App\Models\TripImport;
use BackedEnum;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use UnitEnum;

class LaporanPesanan extends Page implements HasForms
{
    use InteractsWithForms, WithFileUploads;

    protected string $view = 'filament.pages.laporan-pesanan';

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Laporan Pesanan';
    protected static \UnitEnum|string|null $navigationGroup = 'Pesanan & Jadwal';
    protected static ?int $navigationSort = 99;
    protected static ?string $title = 'Laporan Pesanan';

    public int $selectedYear;
    public ?int $selectedMonth = null;

    // Import form
    public ?string $importFile = null;
    public int $importBulan;
    public int $importTahun;
    public bool $showImportForm = false;

    public function mount(): void
    {
        $this->selectedYear  = (int) now()->format('Y');
        $this->selectedMonth = (int) now()->format('n');
        $this->importBulan   = (int) now()->format('n');
        $this->importTahun   = (int) now()->format('Y');
    }

    // ── Stats for selected period ───────────────────────────────────────────────

    public function getMonthlyStats(): array
    {
        $month = $this->selectedMonth ?? (int) now()->format('n');

        $q = Order::whereYear('created_at', $this->selectedYear)
            ->whereMonth('created_at', $month);

        // Also count trip imports
        $qi = TripImport::where('tahun', $this->selectedYear)
            ->where('bulan', $month);

        return [
            'total'           => $q->count(),
            'revenue'         => (clone $q)->where('status', '!=', 'cancelled')->sum('total_price'),
            'tour'            => (clone $q)->whereNotNull('product_id')->whereNull('vehicle_id')->whereNull('rental_package_id')->count(),
            'rental'          => (clone $q)->whereNotNull('rental_package_id')->count(),
            'car'             => (clone $q)->whereNotNull('vehicle_id')->count(),
            'pending'         => (clone $q)->where('status', 'pending')->count(),
            'confirmed'       => (clone $q)->where('status', 'confirmed')->count(),
            'completed'       => (clone $q)->where('status', 'completed')->count(),
            'cancelled'       => (clone $q)->where('status', 'cancelled')->count(),
            // CSV import stats
            'csv_total'       => $qi->count(),
            'csv_revenue'     => (clone $qi)->sum('harga'),
            'csv_paket_trip'  => (clone $qi)->where('layanan', 'like', '%Trip%')->count(),
            'csv_sewa_mobil'  => (clone $qi)->where('layanan', 'like', '%Mobil%')->count(),
        ];
    }

    public function getYearlyStats(): array
    {
        $q  = Order::whereYear('created_at', $this->selectedYear);
        $qi = TripImport::where('tahun', $this->selectedYear);

        return [
            'total'       => $q->count(),
            'revenue'     => (clone $q)->where('status', '!=', 'cancelled')->sum('total_price'),
            'tour'        => (clone $q)->whereNotNull('product_id')->whereNull('vehicle_id')->whereNull('rental_package_id')->count(),
            'rental'      => (clone $q)->whereNotNull('rental_package_id')->count(),
            'car'         => (clone $q)->whereNotNull('vehicle_id')->count(),
            // CSV
            'csv_total'   => $qi->count(),
            'csv_revenue' => (clone $qi)->sum('harga'),
        ];
    }

    // ── Monthly chart breakdown ─────────────────────────────────────────────────

    public function getMonthlyChartData(): array
    {
        $months = [];
        for ($m = 1; $m <= 12; $m++) {
            $count  = Order::whereYear('created_at', $this->selectedYear)
                ->whereMonth('created_at', $m)
                ->where('status', '!=', 'cancelled')
                ->count();
            $revenue = Order::whereYear('created_at', $this->selectedYear)
                ->whereMonth('created_at', $m)
                ->where('status', '!=', 'cancelled')
                ->sum('total_price');

            // CSV data
            $csvCount   = TripImport::where('tahun', $this->selectedYear)->where('bulan', $m)->count();
            $csvRevenue = TripImport::where('tahun', $this->selectedYear)->where('bulan', $m)->sum('harga');

            $months[] = [
                'month'       => Carbon::create($this->selectedYear, $m, 1)->locale('id')->monthName,
                'orders'      => $count,
                'revenue'     => $revenue,
                'csv_orders'  => $csvCount,
                'csv_revenue' => $csvRevenue,
                'total'       => $count + $csvCount,
            ];
        }
        return $months;
    }

    // ── Orders list for table a00k ──────────────────────────────────────────────────

    public function getOrdersData()
    {
        $q = Order::with(['product', 'vehicle', 'rentalPackage'])
            ->whereYear('created_at', $this->selectedYear);

        if ($this->selectedMonth) {
            $q->whereMonth('created_at', $this->selectedMonth);
        }

        return $q->orderByDesc('created_at')->get();
    }

    // ── Trip Import (CSV) data ─────────────────────────────────────────────────

    public function getTripImportData()
    {
        $q = TripImport::where('tahun', $this->selectedYear);

        if ($this->selectedMonth) {
            $q->where('bulan', $this->selectedMonth);
        }

        return $q->orderBy('tanggal')->get();
    }

    // ── Available years for filter ─────────────────────────────────────────────

    public function getAvailableYears(): array
    {
        $driver = \Illuminate\Support\Facades\DB::getDriverName();
        $query = Order::query();

        if ($driver === 'sqlite') {
            $query->selectRaw('strftime("%Y", created_at) as y');
        } else {
            $query->selectRaw('YEAR(created_at) as y');
        }

        $years = $query->distinct()->orderByDesc('y')->pluck('y')->toArray();

        // Also include years from trip_imports
        $csvYears = TripImport::distinct()->orderByDesc('tahun')->pluck('tahun')->toArray();
        $years = array_unique(array_merge($years, $csvYears));
        rsort($years);

        return empty($years) ? [(int) now()->year] : array_map('intval', $years);
    }

    // ── Handle CSV Upload ──────────────────────────────────────────────────────

    public function importCsv(): void
    {
        if (!$this->importFile) {
            Notification::make()
                ->title('Pilih file CSV terlebih dahulu')
                ->warning()
                ->send();
            return;
        }

        $path = Storage::disk('public')->path($this->importFile);
        if (!file_exists($path)) {
            Notification::make()->title('File tidak ditemukan')->danger()->send();
            return;
        }

        $filename = basename($this->importFile);
        $bulan    = $this->importBulan;
        $tahun    = $this->importTahun;

        // Delete old data for this month/year
        TripImport::where('bulan', $bulan)->where('tahun', $tahun)->delete();

        $handle   = fopen($path, 'r');
        $header   = null;
        $imported = 0;

        while (($row = fgetcsv($handle, 0, ',')) !== false) {
            if ($header === null) { $header = $row; continue; }

            $tanggal   = trim($row[0] ?? '');
            $pelanggan = trim($row[1] ?? '');
            if (empty($tanggal) && empty($pelanggan)) continue;

            $parsedDate = null;
            if (!empty($tanggal)) {
                try {
                    $parts = explode('/', $tanggal);
                    if (count($parts) === 3) {
                        $parsedDate = "{$parts[2]}-{$parts[1]}-{$parts[0]}";
                    }
                } catch (\Exception $e) {}
            }

            $harga     = (float) preg_replace('/[^0-9.]/', '', $row[15] ?? 0);
            $deposit   = (float) preg_replace('/[^0-9.]/', '', $row[16] ?? 0);
            $pelunasan = (float) preg_replace('/[^0-9.]/', '', $row[17] ?? 0);

            TripImport::create([
                'tanggal'        => $parsedDate,
                'nama_pelanggan' => $pelanggan ?: null,
                'status'         => trim($row[2] ?? '') ?: null,
                'nomor_hp'       => trim($row[3] ?? '') ?: null,
                'nama_driver'    => trim($row[4] ?? '') ?: null,
                'layanan'        => trim($row[5] ?? '') ?: null,
                'plat_mobil'     => trim($row[6] ?? '') ?: null,
                'jenis_mobil'    => trim($row[7] ?? '') ?: null,
                'drone'          => strtoupper(trim($row[8] ?? 'FALSE')) === 'TRUE',
                'jumlah_hari'    => (int) ($row[9] ?? 1) ?: 1,
                'penumpang'      => ($row[10] ?? null) !== null && $row[10] !== '' ? (int) $row[10] : null,
                'hotel_1'        => trim($row[11] ?? '') ?: null,
                'hotel_2'        => trim($row[12] ?? '') ?: null,
                'hotel_3'        => trim($row[13] ?? '') ?: null,
                'hotel_4'        => trim($row[14] ?? '') ?: null,
                'harga'          => $harga,
                'deposit'        => $deposit,
                'pelunasan'      => $pelunasan,
                'tiba'           => trim($row[18] ?? '') ?: null,
                'flight_balik'   => trim($row[19] ?? '') ?: null,
                'source_file'    => $filename,
                'bulan'          => $bulan,
                'tahun'          => $tahun,
            ]);
            $imported++;
        }

        fclose($handle);

        // Clean up temp file
        Storage::disk('public')->delete($this->importFile);
        $this->importFile    = null;
        $this->showImportForm = false;

        Notification::make()
            ->title("✅ Berhasil import {$imported} data trip")
            ->body("Data dari file {$filename} sudah masuk ke laporan bulan ini.")
            ->success()
            ->send();
    }
}
