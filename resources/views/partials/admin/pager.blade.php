@if($p->hasPages())
<div class="pager">
  <span>Halaman {{ $p->currentPage() }} dari {{ $p->lastPage() }}</span>
  <span>
    @if($p->previousPageUrl())<a class="btn" href="{{ $p->previousPageUrl() }}">Sebelumnya</a>@endif
    @if($p->nextPageUrl())<a class="btn" href="{{ $p->nextPageUrl() }}">Berikutnya</a>@endif
  </span>
</div>
@endif
