@props([
  'sm' => "12",
  'md' => "12",
  'lg' => "12",
  'xl' => "12",
])

<div
  {{ $attributes->merge([
    'class' => collect(["col-sm-$sm", "col-md-$md", "col-lg-$lg", "col-xl-$xl"])->implode(' '),
  ]) }}>
  {{ $slot }}
</div>
