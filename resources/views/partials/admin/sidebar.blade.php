<aside>
  <a class="brand" href="{{ route('admin.dashboard') }}" style="text-decoration:none;color:inherit"><i class="fa-solid fa-droplet"></i> Tirta Dua Putri</a>
  <nav>
    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'on' : '' }}"><i class="fa-solid fa-house"></i>Dashboard</a>
    <a href="{{ route('admin.pesanan.index') }}" class="{{ request()->routeIs('admin.pesanan.index') ? 'on' : '' }}"><i class="fa-solid fa-inbox"></i>Belum fix @if(($jumlahBelumFix ?? 0) > 0)<span class="badge">{{ $jumlahBelumFix }}</span>@endif</a>
    <a href="{{ route('admin.pesanan.fix') }}" class="{{ request()->routeIs('admin.pesanan.fix', 'admin.pesanan.create') ? 'on' : '' }}"><i class="fa-solid fa-circle-check"></i>Pesanan fix</a>
    <a href="{{ route('admin.produk.index') }}" class="{{ request()->routeIs('admin.produk.*') ? 'on' : '' }}"><i class="fa-solid fa-box-open"></i>Produk</a>
    <a href="{{ route('admin.promo.index') }}" class="{{ request()->routeIs('admin.promo.*') ? 'on' : '' }}"><i class="fa-solid fa-bullhorn"></i>Promo</a>
    <a href="{{ route('admin.profil.edit') }}" class="{{ request()->routeIs('admin.profil.*') ? 'on' : '' }}"><i class="fa-solid fa-store"></i>Profil usaha</a>
    <a href="{{ route('admin.akun.edit') }}" class="{{ request()->routeIs('admin.akun.*') ? 'on' : '' }}"><i class="fa-solid fa-user-gear"></i>Akun admin</a>
    <a href="{{ route('home') }}" target="_blank"><i class="fa-solid fa-arrow-up-right-from-square"></i>Lihat website</a>
  </nav>
</aside>
