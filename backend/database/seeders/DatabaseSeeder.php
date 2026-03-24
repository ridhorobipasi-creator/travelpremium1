<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\Booking;
use App\Models\Car;
use App\Models\City;
use App\Models\Package;
use App\Models\Province;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Users ──────────────────────────────────────────────
        $admin = User::create([
            'name'     => 'Admin Wonderful Toba',
            'email'    => 'admin@wonderfultoba.id',
            'password' => Hash::make('admin123'),
            'role'     => 'admin',
        ]);

        $staff = User::create([
            'name'     => 'Staff Toba',
            'email'    => 'staff@wonderfultoba.id',
            'password' => Hash::make('staff123'),
            'role'     => 'staff',
        ]);

        $user = User::create([
            'name'     => 'Budi Traveler',
            'email'    => 'user@wonderfultoba.id',
            'password' => Hash::make('user123'),
            'role'     => 'user',
        ]);

        // ── Provinces & Cities ─────────────────────────────────
        $sumut = Province::create([
            'name'        => 'Sumatera Utara',
            'description' => 'Provinsi dengan keindahan alam luar biasa, rumah Danau Toba terbesar di Asia Tenggara.',
            'image'       => 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&q=80&w=1000',
        ]);

        $toba = City::create([
            'province_id' => $sumut->id,
            'name'        => 'Danau Toba',
            'description' => 'Danau vulkanik terbesar di dunia, destinasi wisata alam unggulan Sumatera Utara.',
            'image'       => 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&q=80&w=800',
        ]);

        $berastagi = City::create([
            'province_id' => $sumut->id,
            'name'        => 'Berastagi',
            'description' => 'Kota wisata sejuk di kaki Gunung Sinabung dan Sibayak dengan pemandangan indah.',
            'image'       => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&q=80&w=800',
        ]);

        $langkat = City::create([
            'province_id' => $sumut->id,
            'name'        => 'Bukit Lawang',
            'description' => 'Surga wisata alam dengan orangutan liar di tepi Sungai Bohorok.',
            'image'       => 'https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&q=80&w=800',
        ]);

        $tangkahan = City::create([
            'province_id' => $sumut->id,
            'name'        => 'Tangkahan',
            'description' => 'Destinasi ekowisata unik dengan gajah jinak dan hutan hujan tropis.',
            'image'       => 'https://images.unsplash.com/photo-1549366021-9f761d450615?auto=format&fit=crop&q=80&w=800',
        ]);

        // ── Packages ───────────────────────────────────────────
        $pkg1 = Package::create([
            'name'        => 'Pesona Danau Toba',
            'description' => 'Jelajahi keindahan Danau Toba, Pulau Samosir, dan budaya Batak yang kaya. Paket lengkap dengan penginapan tepi danau dan guide lokal berpengalaman.',
            'city_id'     => $toba->id,
            'price'       => 3500000,
            'duration'    => '4D 3N',
            'includes'    => ['Penginapan 3 malam', 'Makan 3x sehari', 'Guide lokal', 'Ferry ke Samosir', 'Transportasi darat'],
            'excludes'    => ['Tiket pesawat', 'Oleh-oleh', 'Pengeluaran pribadi'],
            'images'      => ['https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&q=80&w=1000'],
            'status'      => 'active',
        ]);

        $pkg2 = Package::create([
            'name'        => 'Bukit Lawang & Orangutan',
            'description' => 'Petualangan jungle trekking dan menyaksikan orangutan liar langsung di habitat aslinya. Pengalaman yang tidak akan terlupakan.',
            'city_id'     => $langkat->id,
            'price'       => 3200000,
            'duration'    => '4D 3N',
            'includes'    => ['Penginapan 3 malam', 'Makan 3x sehari', 'Guide jungle trek', 'Tiket masuk Taman Nasional', 'Transportasi dari Medan'],
            'excludes'    => ['Tiket pesawat ke Medan', 'Tips guide', 'Pengeluaran pribadi'],
            'images'      => ['https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&q=80&w=1000'],
            'status'      => 'active',
        ]);

        $pkg3 = Package::create([
            'name'        => 'Berastagi & Gunung Sinabung',
            'description' => 'Nikmati udara segar pegunungan, pemandangan Gunung Sinabung, dan pasar buah segar Berastagi.',
            'city_id'     => $berastagi->id,
            'price'       => 1900000,
            'duration'    => '2D 1N',
            'includes'    => ['Penginapan 1 malam', 'Makan 2x', 'Guide', 'Transport'],
            'excludes'    => ['Tiket pesawat', 'Pengeluaran pribadi'],
            'images'      => ['https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&q=80&w=1000'],
            'status'      => 'active',
        ]);

        Package::create([
            'name'        => 'Tangkahan Eco Adventure',
            'description' => 'Ekowisata premium dengan aktivitas gajah, jungle trek, dan river tubing di Tangkahan.',
            'city_id'     => $tangkahan->id,
            'price'       => 2800000,
            'duration'    => '3D 2N',
            'includes'    => ['Penginapan 2 malam', 'Makan 3x sehari', 'Aktivitas gajah', 'River tubing', 'Guide'],
            'excludes'    => ['Tiket pesawat', 'Pengeluaran pribadi'],
            'images'      => ['https://images.unsplash.com/photo-1549366021-9f761d450615?auto=format&fit=crop&q=80&w=1000'],
            'status'      => 'active',
        ]);

        // ── Cars ───────────────────────────────────────────────
        $car1 = Car::create([
            'name'          => 'Toyota Avanza',
            'type'          => 'MPV',
            'price_per_day' => 350000,
            'status'        => 'available',
            'image'         => 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&q=80&w=800',
            'seats'         => 7,
            'fuel'          => 'Bensin',
            'transmission'  => 'Manual',
        ]);

        Car::create([
            'name'          => 'Toyota Innova Reborn',
            'type'          => 'SUV',
            'price_per_day' => 550000,
            'status'        => 'available',
            'image'         => 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&q=80&w=800',
            'seats'         => 7,
            'fuel'          => 'Solar',
            'transmission'  => 'Manual',
        ]);

        Car::create([
            'name'          => 'Mitsubishi Pajero Sport',
            'type'          => 'SUV 4WD',
            'price_per_day' => 850000,
            'status'        => 'available',
            'image'         => 'https://images.unsplash.com/photo-1494976388531-d1058494cdd8?auto=format&fit=crop&q=80&w=800',
            'seats'         => 7,
            'fuel'          => 'Solar',
            'transmission'  => 'Otomatis',
        ]);

        Car::create([
            'name'          => 'Daihatsu Xenia',
            'type'          => 'MPV',
            'price_per_day' => 300000,
            'status'        => 'available',
            'image'         => 'https://images.unsplash.com/photo-1502877338535-766e1452684a?auto=format&fit=crop&q=80&w=800',
            'seats'         => 7,
            'fuel'          => 'Bensin',
            'transmission'  => 'Manual',
        ]);

        // ── Bookings ───────────────────────────────────────────
        Booking::create([
            'user_id'        => $user->id,
            'type'           => 'package',
            'item_id'        => $pkg1->id,
            'start_date'     => '2025-04-10',
            'end_date'       => '2025-04-13',
            'status'         => 'confirmed',
            'total_price'    => 3500000,
            'customer_name'  => 'Budi Traveler',
            'customer_phone' => '081234567890',
            'customer_email' => 'user@wonderfultoba.id',
        ]);

        Booking::create([
            'user_id'        => $user->id,
            'type'           => 'car',
            'item_id'        => $car1->id,
            'start_date'     => '2025-05-01',
            'end_date'       => '2025-05-03',
            'status'         => 'pending',
            'total_price'    => 700000,
            'customer_name'  => 'Budi Traveler',
            'customer_phone' => '081234567890',
            'customer_email' => 'user@wonderfultoba.id',
        ]);

        // ── Blog Posts ─────────────────────────────────────────
        BlogPost::create([
            'author_id' => $admin->id,
            'title'     => '10 Alasan Mengapa Danau Toba Wajib Dikunjungi',
            'content'   => 'Danau Toba adalah keajaiban alam yang luar biasa. Sebagai danau vulkanik terbesar di Asia Tenggara, Danau Toba menyimpan sejuta pesona yang sayang untuk dilewatkan...',
            'category'  => 'Destinasi',
            'tags'      => ['Danau Toba', 'Sumatera Utara', 'Wisata Alam'],
            'image'     => 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&q=80&w=1200',
        ]);

        BlogPost::create([
            'author_id' => $staff->id,
            'title'     => 'Tips Trekking Bukit Lawang untuk Pemula',
            'content'   => 'Bukit Lawang adalah salah satu destinasi trekking terbaik di Indonesia. Berikut tips penting yang harus Anda ketahui sebelum berangkat...',
            'category'  => 'Tips',
            'tags'      => ['Bukit Lawang', 'Trekking', 'Orangutan'],
            'image'     => 'https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&q=80&w=1200',
        ]);

        BlogPost::create([
            'author_id' => $admin->id,
            'title'     => 'Kuliner Khas Batak yang Harus Dicoba',
            'content'   => 'Sumatera Utara terkenal dengan kekayaan kulinernya yang unik dan lezat. Saksang, Arsik, dan Naniura adalah beberapa hidangan khas Batak yang wajib Anda cicipi...',
            'category'  => 'Kuliner',
            'tags'      => ['Kuliner Batak', 'Makanan Khas', 'Sumatera Utara'],
            'image'     => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&q=80&w=1200',
        ]);
    }
}
