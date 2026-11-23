@php $rp = fn($n) => 'Rp '.number_format($n, 0, ',', '.'); @endphp
<tr class="det" id="d-{{ $o->id_pesanan }}" hidden><td colspan="{{ $cols }}">
  <div class="meta">
    <div>No. HP<br><b>{{ $o->no_hp }}</b></div>
    <div>Pengambilan<br><b>{{ $o->pengambilan }}</b></div>
    @if($o->pengambilan === 'Diantar')
      <div>Alamat<br><b>{{ $o->alamat }}</b>@if($o->detail_alamat)<br>{{ $o->detail_alamat }}@endif
        @if($o->lat && $o->lng)<br><a href="https://maps.google.com/?q={{ $o->lat }},{{ $o->lng }}" target="_blank" rel="noopener">Buka titik di peta</a>@endif</div>
      @if($o->catatan)<div>Catatan kurir<br><b>{{ $o->catatan }}</b></div>@endif
    @elseif($o->alamat)
      <div>Alamat<br><b>{{ $o->alamat }}</b></div>
    @endif
  </div>
  <table class="sub"><thead><tr><th>Produk</th><th>Jumlah</th><th>Harga satuan</th><th>Subtotal</th></tr></thead><tbody>
    @foreach($o->detail as $d)<tr><td>{{ $d->produk->nama_produk }}</td><td>{{ $d->jumlah }}</td><td>{{ $rp($d->harga_satuan) }}</td><td>{{ $rp($d->subtotal) }}</td></tr>@endforeach
    @if($o->ongkir > 0)<tr><td colspan="3">Ongkos kirim</td><td>{{ $rp($o->ongkir) }}</td></tr>@endif
    <tr><td colspan="3"><b>Total</b></td><td><b>{{ $rp($o->total_harga) }}</b></td></tr>
  </tbody></table>
</td></tr>
