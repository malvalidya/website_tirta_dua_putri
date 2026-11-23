@extends('layouts.customer')
@section('title', 'Keranjang – '.$profil->nama_usaha)
@push('head')<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css">@endpush
@push('scripts')<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>@endpush
@section('content')
@include('partials.customer.back')
<div id="cartPage">
  <div class="steps" id="steps"><div>1. Keranjang</div><div>2. Pembayaran</div><div>3. Konfirmasi</div></div>

  <div class="card" id="s1">
    <h2>Keranjang pesanan</h2>
    <div id="items"></div>
    <div class="mode" id="modeBox">
      <label class="opt"><input type="radio" name="mode" value="ambil" checked><span><i class="fa-solid fa-store"></i><b>Ambil di tempat</b><small>Gratis, tanpa biaya tambahan</small></span></label>
      <label class="opt"><input type="radio" name="mode" value="antar"><span><i class="fa-solid fa-motorcycle"></i><b>Diantar ke alamat</b><small>Ongkos kirim Rp {{ number_format(config('depo.ongkir', 5000), 0, ',', '.') }}</small></span></label>
    </div>
    <div id="s1sum"></div>
    <div class="acts"><a href="{{ route('home') }}"><button class="btn ghost" type="button">Tambah produk</button></a><button class="btn" type="button" data-step="2">Lanjut ke pembayaran</button></div>
  </div>

  <div class="card" id="s2" hidden>
    <h2>Data pemesan</h2>
    <div class="fld" id="fNama"><label for="nama">Nama</label><input type="text" id="nama" placeholder="Nama lengkap" autocomplete="name"><div class="msg">Nama wajib diisi.</div></div>
    <div class="fld" id="fHp"><label for="hp">Nomor HP</label><input type="tel" id="hp" placeholder="08xxxxxxxxxx" autocomplete="tel"><div class="msg">Masukkan nomor HP yang valid, contoh 081234567890.</div></div>

    <div class="ambil" id="ambilInfo" hidden><i class="fa-solid fa-store"></i><div><b>Ambil di tempat</b><span>{{ $profil->alamat }}</span><small>{{ $profil->jam_operasional }}</small></div></div>

    <div id="alamatBox" hidden>
      <h2>Alamat pengantaran</h2>
      <div class="ga">
        <div class="ga-search"><i class="fa-solid fa-magnifying-glass"></i><input type="text" id="cari" placeholder="Cari jalan, gedung, atau area" autocomplete="off" aria-label="Cari alamat"><button type="button" class="ga-gps" id="lokasiku"><i class="fa-solid fa-location-crosshairs"></i> Lokasi saya</button></div>
        <ul class="ga-sug" id="sug" hidden></ul>
        <div class="ga-map" id="peta"></div>
        <div class="fld" id="fLokasi">
          <div class="ga-pick"><i class="fa-solid fa-location-dot"></i><div><b id="pickNama">Belum ada lokasi dipilih</b><small id="pickAlamat">Cari alamat, pakai lokasi saya, atau geser pin pada peta</small></div></div>
          <div class="msg">Pilih titik pengantaran di peta terlebih dahulu.</div>
        </div>
        <div class="fld" id="fDetail"><label for="detail">Detail alamat (nomor rumah, RT/RW, patokan)</label><input type="text" id="detail" placeholder="Contoh: Rumah pagar hijau, No. 12, RT 03/RW 02"><div class="msg">Isi detail alamat agar kurir mudah menemukan rumah Anda.</div></div>
        <div class="fld"><label for="catatan">Catatan untuk kurir (opsional)</label><input type="text" id="catatan" placeholder="Contoh: Titip di pos satpam"></div>
      </div>
    </div>

    <h2>Metode pembayaran</h2>
    <div class="pay">
      <label><input type="radio" name="bayar" value="Tunai" checked><span><b>Tunai</b><small>Bayar saat air diterima atau diambil</small></span></label>
      <label><input type="radio" name="bayar" value="Nontunai"><span><b>Nontunai</b><small>Transfer atau e-wallet, rincian dikirim lewat WhatsApp</small></span></label>
    </div>
    <div class="acts"><button class="btn ghost" type="button" data-step="1">Kembali</button><button class="btn" type="button" data-step="3">Periksa pesanan</button></div>
  </div>

  <input type="text" id="website" tabindex="-1" autocomplete="off" aria-hidden="true" style="position:absolute;left:-9999px;width:1px;height:1px;opacity:0">

  <div class="card" id="s3" hidden>
    <h2>Konfirmasi pesanan</h2>
    <div id="s3sum"></div>
    <h2 style="margin-top:18px">Pesan yang akan dikirim</h2>
    <div class="pre" id="pre"></div>
    <p class="note">Pesanan Anda dicatat sebagai menunggu konfirmasi, lalu WhatsApp terbuka dengan pesan yang sudah terisi. Tekan kirim di WhatsApp. Admin akan memverifikasi pesanan Anda sebelum diproses.</p>
    <div class="acts"><button class="btn ghost" type="button" data-step="2">Ubah data</button><button class="btn wa" type="button" data-kirim><i class="fa-brands fa-whatsapp"></i> Kirim lewat WhatsApp</button></div>
  </div>

  <div class="card" id="s4" hidden style="text-align:center;padding:30px 18px">
    <i class="fa-solid fa-circle-check" style="font-size:46px;color:var(--ok)"></i>
    <h2 style="margin-top:10px">Pesanan terkirim</h2>
    <p>Kode pesanan Anda: <b id="kodeOut"></b></p>
    <p class="note">Pesanan menunggu konfirmasi admin. Lanjutkan di WhatsApp dan simpan kode ini. Jika WhatsApp belum terbuka, tekan tombol di bawah.</p>
    <div class="acts" style="justify-content:center"><a class="btn wa" id="waAgain" target="_blank" rel="noopener"><i class="fa-brands fa-whatsapp"></i> Buka WhatsApp</a><a class="btn ghost" href="{{ route('home') }}">Kembali ke menu utama</a></div>
  </div>
</div>
@endsection
