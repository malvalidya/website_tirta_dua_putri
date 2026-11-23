<header>
  <h1>@yield('title')</h1>
  <div class="me" style="margin-left:auto">
    <button class="theme" id="themeBtn" type="button" aria-label="Ganti tema"><i class="fa-solid fa-moon"></i></button>
    <span>{{ strtoupper(substr(auth('admin')->user()->email, 0, 1)) }}</span>
    <div>Admin<small>{{ auth('admin')->user()->email }}</small></div>
    <form method="POST" action="{{ route('admin.logout') }}">@csrf<button class="out" type="submit"><i class="fa-solid fa-right-from-bracket"></i> Keluar</button></form>
  </div>
</header>
