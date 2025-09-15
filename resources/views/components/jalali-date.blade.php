@props([
  'date',
  'format' => 'datetime', // time - date- datetimeو
  'separator' => '/'
])

@php
  $jalaliDate = verta($date);
@endphp

@switch($format)
  @case('time')
    @php($formatedDate = $jalaliDate->formatTime())
    @break
  @case('date')
    @php($formatedDate = $jalaliDate->format('Y' . $separator . 'm' . $separator . 'd'))
    @break
  @default
    @php($formatedDate = $jalaliDate->format('Y' . $separator . 'm' . $separator . 'd' . ' ' . 'H:i'))
    @break
@endswitch

<span>{{ $formatedDate }}</span>