<footer class="foot" id="foot" data-n="0">
  <div class="foot-in">
    <p class="foot-copy">© {{ date('Y') }} {{ $profil->nama_usaha }}. Pesanan dikonfirmasi lewat WhatsApp.</p>
    <a class="foot-cart" href="{{ route('keranjang') }}">
      <span class="fc-ico"><i class="fa-solid fa-cart-shopping"></i><b id="cnt"></b></span>
      <span class="fc-txt"><small id="barN">Keranjang kosong</small><b id="barT">Belum ada produk</b></span>
      <span class="fc-go">Lihat keranjang</span>
    </a>
  </div>
</footer>
