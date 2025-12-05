<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\User;
use App\Models\Category;
use Faker\Factory as Faker;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID'); // Pakai data orang Indonesia

        // Ambil ID User dan Kategori yang ada di database
        $userIds = User::pluck('id')->toArray();
        $categoryIds = Category::pluck('id')->toArray();

        // Pastikan ada user dan kategori dulu
        if (empty($userIds) || empty($categoryIds)) {
            $this->command->info('Harap jalankan UserSeeder dan CategorySeeder terlebih dahulu!');
            return;
        }

        // Daftar benda umum agar judul terlihat real
        $items = [
            'Dompet Kulit', 'Kunci Motor Honda', 'Tumbler Corkcicle', 
            'Laptop Asus ROG', 'iPhone 11', 'Jaket Hoodie', 
            'KTM Mahasiswa', 'Helm Bogo', 'Charger Laptop', 
            'Earphone Bluetooth', 'Buku Catatan', 'Payung Lipat'
        ];

        $colors = ['Hitam', 'Merah', 'Biru', 'Putih', 'Coklat', 'Abu-abu'];
        $locations = ['Kantin Pusat', 'Perpustakaan Lt 3', 'Gedung A R.302', 'Parkiran Motor', 'Masjid Kampus', 'Taman Depan'];

        // Generate 50 Data
        for ($i = 0; $i < 50; $i++) {
            
            $type = $faker->randomElement(['lost', 'found']);
            $itemName = $faker->randomElement($items);
            $color = $faker->randomElement($colors);
            
            // Logic Verifikasi: 
            // Lost = Selalu True. 
            // Found = Acak (80% True, 20% False buat simulasi butuh verifikasi admin)
            $isVerified = ($type == 'lost') ? true : $faker->boolean(80);

            Item::create([
                'user_id' => $faker->randomElement($userIds),
                'category_id' => $faker->randomElement($categoryIds),
                'type' => $type,
                'title' => "$itemName $color", // Contoh: Dompet Kulit Hitam
                'description' => "Telah $type $itemName berwarna $color. " . $faker->sentence(10),
                'date' => $faker->dateTimeBetween('-2 months', 'now'),
                'location' => $faker->randomElement($locations),
                'image_path' => null, // Kosongkan dulu atau isi path dummy jika punya
                'is_verified' => $isVerified,
                'status' => 'open',
                'created_at' => $faker->dateTimeBetween('-2 months', 'now'),
            ]);
        }
    }
}