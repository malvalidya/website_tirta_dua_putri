<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        $this->call([AdminSeeder::class, ProfilUsahaSeeder::class, ProdukSeeder::class]);
        // Data contoh untuk mengisi dashboard (opsional): php artisan db:seed --class=PesananDemoSeeder
    }
}
