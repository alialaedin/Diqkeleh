@if($errors->any())
  @php
    toastr()->error('خطا در انجام عملیات!');
  @endphp
  <div class="alert alert-danger" style="margin-top: 42px">
    @foreach($errors->all() as $e)
      <p class="mb-1">{{ $e }}</p>
    @endforeach
  </div>
@endif