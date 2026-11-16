@if(session('sukses'))<div class="flash">{{ session('sukses') }}</div>@endif
@if(session('gagal'))<div class="flash err">{{ session('gagal') }}</div>@endif
@if($errors->any() && !$errors->has('email') && !request()->routeIs('admin.akun.*'))
  <div class="flash err">@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
@endif
