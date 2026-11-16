<header class="top"><div class="wrap">
  <a class="brand" href="{{ route('home') }}"><span class="logo"><i class="fa-solid fa-droplet"></i></span><span class="brand-t">{{ $profil->nama_usaha }}</span></a>
  <nav aria-label="Menu utama">
    <a class="lnk {{ request()->routeIs('home') ? 'on' : '' }}" href="{{ route('home') }}" @if(request()->routeIs('home')) aria-current="page" @endif>Beranda</a>
    <a class="lnk {{ request()->routeIs('info') ? 'on' : '' }}" href="{{ route('info') }}" @if(request()->routeIs('info')) aria-current="page" @endif>Info usaha</a>
  </nav>
</div></header>
