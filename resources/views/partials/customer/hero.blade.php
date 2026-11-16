<section class="hero">
  <div>
    <h1>Air isi ulang bersih, praktis dipesan dari rumah.</h1>
    <p>Pilih produk, isi data, lalu kirim pesanan ke WhatsApp kami. Ambil di tempat atau diantar. Tanpa daftar akun.</p>
    <a class="btn light" href="#produk">Pesan sekarang</a>
    <div class="chips">
      @if($profil->jam_operasional)<span class="chip"><i class="fa-solid fa-clock"></i>{{ $profil->jam_operasional }}</span>@endif
      @if($profil->alamat)<span class="chip"><i class="fa-solid fa-location-dot"></i>{{ \Illuminate\Support\Str::limit($profil->alamat, 40) }}</span>@endif
    </div>
  </div>
  <div class="hv" aria-hidden="true"><div class="drop"></div>
    <div class="facts">
      <span class="fact"><i class="fa-solid fa-mobile-screen-button"></i>Tanpa akun</span>
      <span class="fact"><i class="fa-solid fa-money-bill-wave"></i>Tunai atau nontunai</span>
      <span class="fact"><i class="fa-solid fa-store"></i>Ambil atau diantar</span>
    </div>
  </div>
</section>
