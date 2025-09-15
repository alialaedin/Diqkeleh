@props(['title' => null])

<div {{ $attributes->merge(['class' => 'card shadow']) }}>
  @if ($title)
    <div class="card-header border-0">
      <p class="card-title font-weight-bold">{{ $title }}</p>
      @isset($options)
        <div class="card-options">
          {{ $options }}
        </div>
      @endisset
    </div>
  @endif
  <div class="card-body">
    {{ $slot }}
  </div>
</div>
