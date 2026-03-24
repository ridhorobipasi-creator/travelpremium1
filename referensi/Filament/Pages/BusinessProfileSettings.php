<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Schemas\Components as Schemas;

class BusinessProfileSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static \UnitEnum|string|null $navigationGroup = 'Sistem & Pengaturan';

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $title = 'Profil Bisnis';

    protected string $view = 'filament.pages.business-profile-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'site_name' => Setting::get('site_name', 'NorthSumateraTrip'),
            'whatsapp_number' => Setting::get('whatsapp_number', '6281298622143'),
            'site_email' => Setting::get('site_email', 'hello@northsumateratrip.com'),
            'site_address' => Setting::get('site_address', 'Medan, Sumatera Utara, Indonesia'),
            'working_hours' => Setting::get('working_hours', '08:00 - 17:00'),
            'timezone' => Setting::get('timezone', 'Asia/Jakarta'),
            'facebook_url' => Setting::get('facebook_url'),
            'instagram_url' => Setting::get('instagram_url'),
            'tiktok_url' => Setting::get('tiktok_url'),
            'youtube_url' => Setting::get('youtube_url'),
            'twitter_url' => Setting::get('twitter_url'),
            'site_logo' => Setting::get('site_logo'),
            'site_favicon' => Setting::get('site_favicon'),
            'default_hero_image' => Setting::get('default_hero_image'),
            'primary_color' => Setting::get('primary_color', '#1D4ED8'),
            'secondary_color' => Setting::get('secondary_color', '#10B981'),
            'meta_title' => Setting::get('meta_title', 'NorthSumateraTrip'),
            'meta_description' => Setting::get('meta_description'),
            'meta_keywords' => Setting::get('meta_keywords'),
            'google_analytics_id' => Setting::get('google_analytics_id'),
            'bank_name_1' => Setting::get('bank_name_1'),
            'bank_account_1' => Setting::get('bank_account_1'),
            'bank_holder_1' => Setting::get('bank_holder_1'),
            'bank_name_2' => Setting::get('bank_name_2'),
            'bank_account_2' => Setting::get('bank_account_2'),
            'bank_holder_2' => Setting::get('bank_holder_2'),
            'qris_image' => Setting::get('qris_image'),
            'mail_host' => Setting::get('mail_host', '127.0.0.1'),
            'mail_port' => Setting::get('mail_port', '2525'),
            'mail_username' => Setting::get('mail_username'),
            'mail_password' => Setting::get('mail_password'),
            'hero_badge_text' => Setting::get('hero_badge_text', 'Layanan Premium'),
            'hero_title' => Setting::get('hero_title', 'Jelajahi Keindahan Sumatera Utara'),
            'hero_subtitle' => Setting::get('hero_subtitle', 'Nikmati pengalaman wisata terbaik dengan layanan private trip eksklusif kami.'),
            'cta_title' => Setting::get('cta_title', 'Siap Memulai Petualangan?'),
            'cta_subtitle' => Setting::get('cta_subtitle', 'Hubungi kami sekarang untuk konsultasi perjalanan gratis.'),
            'cta_button_text' => Setting::get('cta_button_text', 'Hubungi Kami'),
            // Language & Currency
            'default_locale' => Setting::get('default_locale', 'id'),
            'exchange_rate_sgd' => Setting::get('exchange_rate_sgd', '0.000085'),
            'exchange_rate_myr' => Setting::get('exchange_rate_myr', '0.00029'),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Schemas\Tabs::make('Settings')
                    ->tabs([
                        Schemas\Tabs\Tab::make('Identitas & Kontak')
                            ->icon('heroicon-o-building-office')
                            ->schema([
                                Schemas\Grid::make(3)
                                    ->schema([
                                        Schemas\Section::make('Informasi Dasar')
                                            ->columnSpan(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('site_name')
                                                    ->label('Nama Website / Bisnis')
                                                    ->required()
                                                    ->helperText('Nama ini akan muncul di judul browser dan footer.'),
                                                Schemas\Grid::make(2)
                                                    ->schema([
                                                        Forms\Components\TextInput::make('whatsapp_number')
                                                            ->label('WhatsApp (Tanpa +)')
                                                            ->placeholder('628123456789')
                                                            ->required()
                                                            ->tel(),
                                                        Forms\Components\TextInput::make('site_email')
                                                            ->label('Email Publik')
                                                            ->required()
                                                            ->email(),
                                                    ]),
                                                Forms\Components\Textarea::make('site_address')
                                                    ->label('Alamat Lengkap')
                                                    ->rows(3),
                                            ]),
                                        Schemas\Section::make('Operasional')
                                            ->columnSpan(1)
                                            ->schema([
                                                Forms\Components\TextInput::make('working_hours')
                                                    ->label('Jam Operasional')
                                                    ->placeholder('Setiap Hari, 08:00 - 21:00'),
                                                Forms\Components\Select::make('timezone')
                                                    ->label('Zona Waktu')
                                                    ->options([
                                                        'Asia/Jakarta' => 'WIB (Jakarta)',
                                                        'Asia/Makassar' => 'WITA (Makassar)',
                                                        'Asia/Jayapura' => 'WIT (Jayapura)',
                                                    ])
                                                    ->default('Asia/Jakarta'),
                                            ]),
                                    ]),
                            ]),
                        
                        Schemas\Tabs\Tab::make('Visual & Branding')
                            ->icon('heroicon-o-swatch')
                            ->schema([
                                Schemas\Grid::make(2)
                                    ->schema([
                                        Schemas\Section::make('Aset Gambar')
                                            ->schema([
                                                Schemas\Grid::make(2)
                                                    ->schema([
                                                        Forms\Components\FileUpload::make('site_logo')
                                                            ->label('Logo Utama')
                                                            ->image()
                                                            ->disk('public')
                                                            ->directory('settings')
                                                            ->imagePreviewHeight('80'),
                                                        Forms\Components\FileUpload::make('site_favicon')
                                                            ->label('Favicon')
                                                            ->image()
                                                            ->disk('public')
                                                            ->directory('settings'),
                                                    ]),
                                                Forms\Components\FileUpload::make('default_hero_image')
                                                    ->label('Carousel / Banner Utama')
                                                    ->image()
                                                    ->multiple()
                                                    ->reorderable()
                                                    ->disk('public')
                                                    ->directory('settings')
                                                    ->maxSize(2048)
                                                    ->columnSpanFull(),
                                            ]),
                                        Schemas\Section::make('Skema Warna')
                                            ->schema([
                                                Forms\Components\ColorPicker::make('primary_color')
                                                    ->label('Warna Primer')
                                                    ->default('#1D4ED8'),
                                                Forms\Components\ColorPicker::make('secondary_color')
                                                    ->label('Warna Sekunder')
                                                    ->default('#10B981'),
                                                Forms\Components\Placeholder::make('tip')
                                                    ->content('Warna ini akan digunakan pada tombol dan elemen aksen website.'),
                                            ]),
                                    ]),
                            ]),

                        Schemas\Tabs\Tab::make('Konten Landing Page')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Schemas\Section::make('Hero Section (Bagian Atas)')
                                    ->schema([
                                        Forms\Components\TextInput::make('hero_badge_text')
                                            ->label('Badge Text')
                                            ->placeholder('E.g. #1 di Sumatera Utara'),
                                        Forms\Components\TextInput::make('hero_title')
                                            ->label('Headline Utama')
                                            ->required(),
                                        Forms\Components\Textarea::make('hero_subtitle')
                                            ->label('Sub-headline')
                                            ->rows(3),
                                    ]),
                                Schemas\Section::make('CTA Section (Bagian Bawah)')
                                    ->schema([
                                        Schemas\Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('cta_title')
                                                    ->label('Judul Ajakan'),
                                                Forms\Components\TextInput::make('cta_button_text')
                                                    ->label('Teks Tombol'),
                                            ]),
                                        Forms\Components\Textarea::make('cta_subtitle')
                                            ->label('Sub-judul Ajakan')
                                            ->rows(2),
                                    ]),
                            ]),
                        
                        Schemas\Tabs\Tab::make('Media Sosial')
                            ->icon('heroicon-o-share')
                            ->schema([
                                Schemas\Section::make('Tautan Sosial')
                                    ->description('Masukkan link profil lengkap Anda')
                                    ->schema([
                                        Forms\Components\TextInput::make('facebook_url')->label('Facebook')->url(),
                                        Forms\Components\TextInput::make('instagram_url')->label('Instagram')->url(),
                                        Forms\Components\TextInput::make('tiktok_url')->label('TikTok')->url(),
                                        Forms\Components\TextInput::make('youtube_url')->label('YouTube')->url(),
                                        Forms\Components\TextInput::make('twitter_url')->label('Twitter / X')->url(),
                                    ])->columns(2),
                            ]),

                        Schemas\Tabs\Tab::make('Pembayaran')
                            ->icon('heroicon-o-credit-card')
                            ->schema([
                                Schemas\Section::make('Rekening Bank')
                                    ->schema([
                                        Schemas\Grid::make(3)
                                            ->schema([
                                                Forms\Components\TextInput::make('bank_name_1')->label('Bank 1'),
                                                Forms\Components\TextInput::make('bank_account_1')->label('No Rek 1'),
                                                Forms\Components\TextInput::make('bank_holder_1')->label('Atas Nama 1'),
                                                Forms\Components\TextInput::make('bank_name_2')->label('Bank 2'),
                                                Forms\Components\TextInput::make('bank_account_2')->label('No Rek 2'),
                                                Forms\Components\TextInput::make('bank_holder_2')->label('Atas Nama 2'),
                                            ]),
                                    ]),
                                Schemas\Section::make('QRIS')
                                    ->schema([
                                        Forms\Components\FileUpload::make('qris_image')
                                            ->label('Upload QRIS')
                                            ->image()
                                            ->disk('public')
                                            ->directory('payment'),
                                    ]),
                            ]),

                        Schemas\Tabs\Tab::make('Bahasa & Kurs')
                            ->icon('heroicon-o-language')
                            ->schema([
                                Schemas\Section::make('Bahasa Default & Terjemahan')
                                    ->description('Pilih bahasa default website dan aktifkan fitur multi-bahasa.')
                                    ->schema([
                                        Forms\Components\Select::make('default_locale')
                                            ->label('Bahasa Default')
                                            ->options([
                                                'id' => '🇮🇩 Bahasa Indonesia',
                                                'en' => '🇺🇸 English',
                                                'ms' => '🇲🇾 Bahasa Melayu',
                                            ])
                                            ->default('id')
                                            ->helperText('Bahasa yang digunakan secara default saat pengunjung pertama kali membuka website.'),
                                    ]),
                                Schemas\Section::make('Kurs Mata Uang')
                                    ->description('Atur kurs konversi dari IDR (Rupiah) ke mata uang lain. Digunakan untuk menampilkan harga dalam bahasa Inggris (SGD) dan Melayu (MYR).')
                                    ->schema([
                                        Schemas\Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('exchange_rate_sgd')
                                                    ->label('Kurs IDR → SGD (Dolar Singapura)')
                                                    ->numeric()
                                                    ->step(0.000001)
                                                    ->default('0.000085')
                                                    ->prefix('1 IDR =')
                                                    ->suffix('SGD')
                                                    ->helperText('Contoh: 0.000085 artinya 1 IDR = 0.000085 SGD (≈ Rp 11.800/SGD)'),
                                                Forms\Components\TextInput::make('exchange_rate_myr')
                                                    ->label('Kurs IDR → MYR (Ringgit Malaysia)')
                                                    ->numeric()
                                                    ->step(0.000001)
                                                    ->default('0.00029')
                                                    ->prefix('1 IDR =')
                                                    ->suffix('MYR')
                                                    ->helperText('Contoh: 0.00029 artinya 1 IDR = 0.00029 MYR (≈ Rp 3.400/MYR)'),
                                            ]),
                                        Schemas\Section::make('Panduan Kurs Saat Ini')
                                            ->description('Referensi kurs umum (update manual sesuai kurs terkini):')
                                            ->schema([
                                                Forms\Components\Placeholder::make('kurs_info')
                                                    ->label('')
                                                    ->content(new \Illuminate\Support\HtmlString('
                                                        <div class="grid grid-cols-2 gap-4 text-sm">
                                                            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-3">
                                                                <div class="font-bold text-blue-700 dark:text-blue-300">🇺🇸 IDR → SGD</div>
                                                                <div class="text-gray-600 dark:text-gray-400">Kurs saat ini: ~Rp 11.800/SGD</div>
                                                                <div class="text-xs text-gray-500">Masukkan: 0.000085</div>
                                                            </div>
                                                            <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-3">
                                                                <div class="font-bold text-red-700 dark:text-red-300">🇲🇾 IDR → MYR</div>
                                                                <div class="text-gray-600 dark:text-gray-400">Kurs saat ini: ~Rp 3.400/MYR</div>
                                                                <div class="text-xs text-gray-500">Masukkan: 0.00029</div>
                                                            </div>
                                                        </div>
                                                    ')),
                                            ])
                                            ->compact(),
                                    ]),
                            ]),

                        Schemas\Tabs\Tab::make('SEO & Advanced')
                            ->icon('heroicon-o-cog-6-tooth')
                            ->schema([
                                Schemas\Section::make('Meta Tags (SEO)')
                                    ->schema([
                                        Forms\Components\TextInput::make('meta_title')->label('Halaman Meta Title'),
                                        Forms\Components\Textarea::make('meta_description')->label('Meta Description')->rows(3),
                                        Forms\Components\TextInput::make('meta_keywords')->label('Keywords (koma)'),
                                    ]),
                                Schemas\Section::make('Analytics')
                                    ->schema([
                                        Forms\Components\TextInput::make('google_analytics_id')->label('Google Analytics ID'),
                                    ]),
                            ]),
                    ])->columnSpanFull(),
            ])
            ->statePath('data');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('view_site')
                ->label('Lihat Website')
                ->url(url('/'))
                ->openUrlInNewTab()
                ->color('gray')
                ->icon('heroicon-o-arrow-top-right-on-square'),
            Action::make('manage_settings')
                ->label('Advanced Settings')
                ->url(fn () => \App\Filament\Resources\SettingResource::getUrl('index'))
                ->icon('heroicon-o-cog-6-tooth')
                ->color('info'),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan Perubahan')
                ->submit('save')
                ->color('success')
                ->icon('heroicon-o-check-circle'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }

        Notification::make()
            ->title('Pengaturan berhasil disimpan')
            ->success()
            ->send();
            
        \Illuminate\Support\Facades\Cache::forget('site_settings');
        \Illuminate\Support\Facades\Cache::forget('app_settings');
    }
}
