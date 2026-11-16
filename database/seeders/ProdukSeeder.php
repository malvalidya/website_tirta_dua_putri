<?php
namespace Database\Seeders;
use App\Models\Produk;
use Illuminate\Database\Seeder;

class ProdukSeeder extends Seeder {
    public function run(): void {
        // Harga contoh dari mockup, ganti sesuai daftar harga mitra
        foreach ([
            ['Isi Ulang Galon', 'Air isi ulang', 'Isi ulang air minum galon 19 liter.', 6000, 'Aktif'],
            ['Galon + Isi', 'Galon', 'Galon baru beserta isi air.', 45000, 'Aktif'],
            ['Ongkos Kirim', 'Biaya', 'Biaya pengantaran ke alamat pelanggan (hanya untuk pencatatan admin).', 5000, 'Aktif'],
            ['Paket Langganan', 'Paket', 'Isi ulang rutin 10 galon per bulan.', 55000, 'Aktif'],
            ['Tutup Galon', 'Galon', 'Tutup galon pengganti.', 3000, 'Habis'],
        ] as [$n, $k, $d, $h, $s]) {
            Produk::firstOrCreate(['nama_produk' => $n], ['kategori' => $k, 'deskripsi' => $d, 'harga' => $h, 'status_produk' => $s]);
        }
    }
}
