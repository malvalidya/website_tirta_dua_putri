<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<title>@yield('title') – Panel Admin</title>
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
<div class="app">
  @include('partials.admin.sidebar')
  <main>
    @include('partials.admin.header')
    <section class="view on">
      @include('partials.admin.flash')
      @yield('content')
    </section>
  </main>
</div>
<script src="{{ asset('js/admin.js') }}"></script>
</body>
</html>
