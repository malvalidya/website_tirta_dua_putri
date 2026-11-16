<?php
namespace Database\Seeders;
use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder {
    public function run(): void {
        // GANTI kata sandi ini setelah login pertama (menu Akun admin).
        Admin::updateOrCreate(['email' => 'admin@tirtaduaputri.id'], ['kata_sandi' => 'admin12345']);
    }
}
