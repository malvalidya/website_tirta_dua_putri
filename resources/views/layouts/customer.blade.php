<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<title>@yield('title', $profil->nama_usaha)</title>
<link rel="stylesheet" href="{{ asset('css/customer.css') }}">
@stack('head')
<script>(function(){try{var t=localStorage.getItem('tdp_theme')||(matchMedia('(prefers-color-scheme: dark)').matches?'dark':'light');document.documentElement.dataset.theme=t}catch(e){}})()</script>
</head>
<body>
@include('partials.customer.navbar')
<div class="wrap">
  @yield('content')
  @include('partials.customer.footer')
</div>
@include('partials.customer.modal')
@include('partials.customer.chatbot')
@php
$promoJs = $promo ? ['judul' => $promo->judul, 'diskon' => $promo->diskon_teks, 'kode' => $promo->kode_promo, 'sampai' => optional($promo->selesai)->translatedFormat('d F Y')] : null;
@endphp
<script>
window.TDP = {
  produk: @json($produkJs),
  wa: @json($profil->waNomor()),
  namaUsaha: @json($profil->nama_usaha),
  jam: @json($profil->jam_operasional),
  alamatDepo: @json($profil->alamat),
  promo: @json($promoJs),
  ongkir: @json((int) config('depo.ongkir', 5000)),
  pesanUrl: @json(route('pesan.simpan')),
  csrf: @json(csrf_token()),
  pusat: @json(config('depo.pusat', [-7.9666, 112.6326]))
};
</script>
@stack('scripts')
<script src="{{ asset('js/theme.js') }}"></script>
<script src="{{ asset('js/customer.js') }}"></script>
<script src="{{ asset('js/chatbot.js') }}"></script>
</body>
</html>
