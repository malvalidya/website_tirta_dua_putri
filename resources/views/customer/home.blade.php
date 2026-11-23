@extends('layouts.customer')
@section('content')
@include('partials.customer.hero')
<div class="sh" id="produk"><h2>Produk dan layanan</h2>
  <div class="filter" id="filter">
    <label class="psearch"><i class="fa-solid fa-magnifying-glass"></i><input type="search" id="cariProduk" placeholder="Cari produk" autocomplete="off" aria-label="Cari produk"></label>
    <button class="on" data-k="Semua">Semua</button>
    @foreach($kategori as $k)<button data-k="{{ $k }}">{{ $k }}</button>@endforeach
  </div>
</div>
<div class="list" id="list">
  @forelse($produk as $p)<x-product-card :p="$p" />@empty<p>Produk belum tersedia.</p>@endforelse
</div>
<p class="empty" id="kosong" hidden><b>Produk tidak ditemukan</b>Coba kata kunci atau kategori lain.</p>
<div class="sh"><h2>Tentang depo</h2></div>
<div class="two">
  <div class="card">@include('partials.customer.profil-detail')</div>
  @include('partials.customer.map')
</div>
@endsection
