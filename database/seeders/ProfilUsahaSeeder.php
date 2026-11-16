<?php
namespace Database\Seeders;
use App\Models\ProfilUsaha;
use Illuminate\Database\Seeder;

class ProfilUsahaSeeder extends Seeder {
    public function run(): void {
        ProfilUsaha::firstOrCreate([], [
            'nama_usaha' => 'Depo Air Minum Isi Ulang Tirta Dua Putri',
            'deskripsi_singkat' => 'Depo air minum isi ulang usaha keluarga yang melayani isi ulang galon dan pengantaran ke rumah.',
            'alamat' => 'Malang, Jawa Timur',
            'jam_operasional' => 'Senin–Sabtu, 07.00–17.00',
            'nomor_wa' => '62812xxxxxxx', // GANTI dengan nomor WhatsApp pemilik
        ]);
    }
}
