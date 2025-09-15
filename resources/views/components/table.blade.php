@props([
  'pagination' => null
])

<div class="table-responsive">
  <div class="dataTables_wrapper dt-bootstrap4 no-footer">
    <div class="row">
      <table {{ $attributes->merge(['class' => 'table table-vcenter table-striped table-bordered text-nowrap text-center border-bottom table-sm fs-12']) }}>
        <thead>{{$thead}}</thead>
        <tbody>{{ $tbody }}</tbody>
      </table>
      @if ($pagination)
        {{ $pagination->onEachSide(0)->links('vendor.pagination.bootstrap-4') }}
      @endif 
      @isset($extraData)
        {{ $extraData }}
      @endisset
    </div>
  </div>
</div>