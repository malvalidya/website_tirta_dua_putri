<header class="top"><div class="wrap">
  <a class="brand" href="{{ route('home') }}" style="text-decoration:none"><i class="fa-solid fa-droplet" style="color:#fff;font-size:16px"></i>{{ $profil->nama_usaha }}</a>
  <nav>
    <a class="lnk {{ request()->routeIs('home') ? 'on' : '' }}" href="{{ route('home') }}">Beranda</a>
    <a class="lnk {{ request()->routeIs('info') ? 'on' : '' }}" href="{{ route('info') }}">Info usaha</a>
    <button class="theme" id="themeBtn" type="button" aria-label="Ganti tema"><i class="fa-solid fa-moon"></i></button>
    <a class="cartbtn {{ request()->routeIs('keranjang') ? 'on' : '' }}" href="{{ route('keranjang') }}"><i class="fa-solid fa-cart-shopping"></i> Keranjang <b id="cnt" data-n="0"></b></a>
  </nav>
</div></header>
