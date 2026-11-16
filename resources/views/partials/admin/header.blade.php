<header>
  <h1>@yield('title')</h1>
  <div class="me" style="margin-left:auto">
    <span>{{ strtoupper(substr(auth('admin')->user()->email, 0, 1)) }}</span>
    <div>Admin<small>{{ auth('admin')->user()->email }}</small></div>
    <form method="POST" action="{{ route('admin.logout') }}">@csrf<button class="out" type="submit"><i class="fa-solid fa-right-from-bracket"></i> Keluar</button></form>
  </div>
</header>
