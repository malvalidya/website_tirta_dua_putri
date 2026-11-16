# Paket Laravel – Website Depo Tirta Dua Putri

Untuk **Laravel 11 atau 12**. Struktur folder sama dengan project Laravel, jadi tinggal di-copy/replace ke root project.

## Cara pasang
1. Backup project, lalu copy semua folder di sini ke root project (pilih *replace* jika ditanya).
   File yang menimpa bawaan Laravel: `bootstrap/app.php`, `config/auth.php`, `routes/web.php`, `app/Providers/AppServiceProvider.php`, `database/seeders/DatabaseSeeder.php`.
2. Buat database baru (mis. `tirta_dua_putri`) lalu isi `.env`:
   `DB_CONNECTION=mysql`, `DB_DATABASE=tirta_dua_putri`, `DB_USERNAME`, `DB_PASSWORD`.
3. Jalankan:
   ```
   php artisan migrate --seed
   php artisan serve
   ```
   Data contoh transaksi (opsional): `php artisan db:seed --class=PesananDemoSeeder`
4. Buka `/` untuk halaman pembeli dan `/admin/login` untuk admin.
   Login awal: `admin@tirtaduaputri.id` / `admin12345` (**ganti** lewat menu Akun admin).
5. Ganti `nomor_wa` lewat menu **Profil usaha** (format `62812…`).

## Struktur
- `database/migrations`: tabel `produk`, `pesanan`, `detail_pesanan`, `profil_usaha`, `admin` (sesuai ERD Gambar 3.3)
- `app/Models`: model tiap tabel (`Admin` memakai kolom `kata_sandi`)
- `app/Http/Controllers`: `HomeController` (pembeli) dan `Admin/*` (login, dashboard, produk, pesanan, profil, akun)
- `resources/views/layouts`: kerangka halaman (`customer`, `admin`, `auth`)
- `resources/views/partials`: navbar, footer, hero, sidebar, header, flash, dll
- `resources/views/components/product-card.blade.php`: kartu produk
- `public/css`, `public/js`: aset pembeli dan admin

## Catatan alur
- Keranjang pembeli disimpan di browser (localStorage); pesanan **tidak** masuk database saat konfirmasi WhatsApp (KF-08).
- Admin mencatat transaksi selesai di menu Pesanan > Catat transaksi; kode `TDP-0001` dst dibuat otomatis (KF-13) dan harga satuan disalin ke `detail_pesanan`.
- Rute `/admin/*` dilindungi guard `admin`; tamu dialihkan ke halaman login (TC-12).

## Pembaruan 2
- Pilihan **Ambil di tempat (gratis)** atau **Diantar (ongkir Rp 5.000)** di keranjang. Nilai ongkir diatur di `config/depo.php`.
- Alamat ala Grab: cari alamat, lokasi saya, pin di peta (Leaflet + OpenStreetMap, perlu internet), detail alamat, catatan kurir.
- Chatbot bantuan lokal (mengambang di semua halaman pembeli) dengan opsi chat admin lewat WhatsApp.
- Produk "Antar ke Rumah" diganti item tersembunyi **Ongkos Kirim** (kategori `Biaya`) agar admin tetap bisa mencatat ongkir di transaksi.
  Jika database sudah terisi, jalankan di MySQL:
  `UPDATE produk SET nama_produk='Ongkos Kirim', kategori='Biaya', deskripsi='Biaya pengantaran ke alamat pelanggan (hanya untuk pencatatan admin).', harga=5000 WHERE nama_produk='Antar ke Rumah';`

## Pembaruan 3 (tampilan & perbaikan bug)
- **Keranjang bawah = footer.** Bar keranjang mengambang diganti footer yang selalu menempel di bawah layar (jumlah item, total, tombol *Lihat keranjang*). Tombol keranjang di navbar atas dihapus. File `partials/customer/cartbar.blade.php` dihapus; footer lama digabung ke `partials/customer/footer.blade.php`.
- Navbar di HP kini tetap menampilkan menu *Beranda* dan *Info usaha* (sebelumnya hanya tombol keranjang yang tersisa).
- `public/css/customer.css` ditulis ulang dan dirapikan: latar gelembung memakai `transform` (lebih ringan di HP), tanpa `background-attachment: fixed` (bermasalah di iOS), `100dvh`, area aman (notch), font input 16px di HP agar tidak zoom sendiri, baris keranjang tidak lagi meluber di layar sempit, daftar saran alamat melayang di atas peta.
- HTML valid: tombol tidak lagi bersarang di dalam tautan (`<a><button>`), elemen `<main>`, atribut ARIA, kartu produk bisa dibuka dengan keyboard.
- `public/js/customer.js`: data keranjang rusak di localStorage tidak lagi membuat halaman error, jumlah dibatasi 1–99, keranjang sinkron antar tab, **nomor HP kini ikut terkirim di pesan WhatsApp**, pencarian alamat tidak lagi menampilkan hasil lama (race condition), peta tidak membajak scroll halaman, peringatan jika nomor WhatsApp depo belum diatur.
- `public/js/chatbot.js`: jawaban *Cara pesan* disesuaikan dengan footer keranjang, tumpang tindih kata kunci diperbaiki, keyboard HP tidak otomatis muncul saat chat dibuka.
- Admin: konfirmasi hapus produk aman untuk nama yang memuat tanda kutip, baris produk di *Catat transaksi* tidak hilang saat validasi gagal, pencarian pesanan tidak menganggap `%` dan `_` sebagai wildcard, halaman tidak error jika produk di riwayat sudah terhapus, tampilan admin lebih rapi di HP.
