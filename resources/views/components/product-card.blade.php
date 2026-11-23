@props(['p'])
<div class="card prod {{ $p->aktif ? '' : 'habis' }}" data-d="{{ $p->id_produk }}" data-kat="{{ $p->kategori }}" data-q="{{ \Illuminate\Support\Str::lower($p->nama_produk.' '.$p->deskripsi.' '.$p->kategori) }}">
  <div class="pic"><span class="kat">{{ $p->kategori }}</span>
    @if($p->foto_url)<img src="{{ $p->foto_url }}" alt="{{ $p->nama_produk }}">@else<i class="fa-solid {{ $p->ikon }}"></i>@endif
  </div>
  <div><b>{{ $p->nama_produk }}</b><small>{{ $p->deskripsi }}</small></div>
  <div class="bt"><b class="h">Rp {{ number_format($p->harga, 0, ',', '.') }}</b>
    <button class="plus" data-add="{{ $p->id_produk }}" aria-label="Tambah {{ $p->nama_produk }} ke keranjang" {{ $p->aktif ? '' : 'disabled' }}>+</button></div>
  @unless($p->aktif)<small>Sedang habis</small>@endunless
</div>
