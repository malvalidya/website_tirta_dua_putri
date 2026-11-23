<div class="hero">
  <div>
    <h1>Air isi ulang bersih, praktis dipesan dari rumah.</h1>
    <p>Pilih produk, isi data, lalu kirim pesanan ke WhatsApp kami. Ambil di tempat atau diantar. Tanpa daftar akun.</p>
    <div class="cta"><a href="#produk"><button class="btn light" type="button">Pesan sekarang</button></a></div>
    <div class="chips">
      @if($profil->jam_operasional)<span class="chip"><i class="fa-solid fa-clock"></i>{{ $profil->jam_operasional }}</span>@endif
      @if($profil->alamat)<span class="chip"><i class="fa-solid fa-location-dot"></i>{{ \Illuminate\Support\Str::limit($profil->alamat, 40) }}</span>@endif
    </div>
  </div>
  <div class="hv {{ $promo ? 'has-promo' : '' }}"><div class="drop"></div>
    @if($promo)
      <div class="promo" id="promoLive">
        <span class="live"><i></i>{{ $promo->label }}</span>
        <h3>{{ $promo->judul }}</h3>
        @if($promo->diskon_teks)<div class="disc">{{ $promo->diskon_teks }}</div>@endif
        @if($promo->deskripsi)<p>{{ $promo->deskripsi }}</p>@endif
        @if($promo->kode_promo)<div class="kode">Kode: <b>{{ $promo->kode_promo }}</b></div>@endif
        @if($promo->selesai)<small>Berlaku sampai {{ $promo->selesai->translatedFormat('d F Y') }}</small>@endif
        <a class="btn light" href="#produk">{{ $promo->teks_tombol }}</a>
      </div>
    @else
      <div class="facts">
        <span class="fact"><i class="fa-solid fa-mobile-screen-button"></i>Tanpa akun</span>
        <span class="fact"><i class="fa-solid fa-money-bill-wave"></i>Tunai atau nontunai</span>
        <span class="fact"><i class="fa-solid fa-store"></i>Ambil atau diantar</span>
      </div>
    @endif
  </div>
</div>
