<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Package;
use App\Models\City;
use App\Models\Province;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Admins & Staff
        User::create([
            'name' => 'Wonderful Toba Admin',
            'email' => 'admin@wonderfultoba.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Tour Staff',
            'email' => 'staff@tour.com',
            'password' => Hash::make('password123'),
            'role' => 'staff',
        ]);

        // 2. Create Master Data (Region)
        $sumut = Province::create(['name' => 'Sumatera Utara', 'scope' => 'tour']);
        $city = City::create([
            'province_id' => $sumut->id,
            'name' => 'Samosir',
            'scope' => 'tour'
        ]);

        // 3. Create Sample Packages (Tour)
        Package::create([
            'name' => 'Danau Toba Premium Tour 3D2N',
            'slug' => 'danau-toba-premium-tour-3d2n',
            'price' => 2500000,
            'price_category' => 'luxury',
            'city_id' => $city->id,
            'scope' => 'tour',
            'duration' => '3 Hari 2 Malam',
            'location' => 'Pulau Samosir',
            'image' => 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&w=1200',
            'description' => 'Eksplorasi keindahan Danau Toba dengan layanan VIP.',
        ]);

        // 4. Create Sample Packages (Outbound)
        Package::create([
            'name' => 'Corporate Team Building - Marianna Resort',
            'slug' => 'corporate-team-building-marianna',
            'price' => 1500000,
            'price_category' => 'luxury',
            'city_id' => $city->id,
            'scope' => 'outbound',
            'duration' => '2 Hari 1 Malam',
            'location' => 'Marianna Resort, Samosir',
            'image' => '/assets/images/2023/10/006.jpg',
            'description' => 'Paket peningkatan produktivitas tim korporat.',
        ]);
    }
}
