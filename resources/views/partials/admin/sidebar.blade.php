<aside>
  <a class="brand" href="{{ route('admin.dashboard') }}" style="text-decoration:none;color:inherit"><i class="fa-solid fa-droplet"></i> Tirta Dua Putri</a>
  <nav>
    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'on' : '' }}"><i class="fa-solid fa-house"></i>Dashboard</a>
    <a href="{{ route('admin.pesanan.index') }}" class="{{ request()->routeIs('admin.pesanan.*') ? 'on' : '' }}"><i class="fa-solid fa-receipt"></i>Pesanan</a>
    <a href="{{ route('admin.produk.index') }}" class="{{ request()->routeIs('admin.produk.*') ? 'on' : '' }}"><i class="fa-solid fa-box-open"></i>Produk</a>
    <a href="{{ route('admin.profil.edit') }}" class="{{ request()->routeIs('admin.profil.*') ? 'on' : '' }}"><i class="fa-solid fa-store"></i>Profil usaha</a>
    <a href="{{ route('admin.akun.edit') }}" class="{{ request()->routeIs('admin.akun.*') ? 'on' : '' }}"><i class="fa-solid fa-user-gear"></i>Akun admin</a>
    <a href="{{ route('home') }}" target="_blank"><i class="fa-solid fa-arrow-up-right-from-square"></i>Lihat website</a>
  </nav>
</aside>
