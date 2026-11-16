<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="theme-color" content="#0a1f3d">
<meta name="description" content="{{ $profil->deskripsi_singkat ?? 'Pesan air isi ulang dan galon lewat WhatsApp. Ambil di tempat atau diantar.' }}">
<title>@yield('title', $profil->nama_usaha)</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/customer.css') }}">
@stack('head')
</head>
<body class="{{ request()->routeIs('keranjang') ? 'is-cart' : '' }}">
@include('partials.customer.navbar')
<main class="wrap">
  @yield('content')
</main>
@include('partials.customer.footer')
@include('partials.customer.modal')
@include('partials.customer.chatbot')
<script>
window.TDP = {
  produk: @json($produkJs),
  wa: @json($profil->waNomor()),
  namaUsaha: @json($profil->nama_usaha),
  jam: @json($profil->jam_operasional),
  alamatDepo: @json($profil->alamat),
  ongkir: @json((int) config('depo.ongkir', 5000)),
  pusat: @json(config('depo.pusat', [-7.9666, 112.6326])),
  homeUrl: @json(route('home'))
};
</script>
@stack('scripts')
<script src="{{ asset('js/customer.js') }}"></script>
<script src="{{ asset('js/chatbot.js') }}"></script>
</body>
</html>
