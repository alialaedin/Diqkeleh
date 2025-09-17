@props(['title', 'route' => null])

@if ($route)
    <li class="breadcrumb-item mt-0"><a href="{{ $route }}">{{ $title }}</a></li>
@else
    <li class="breadcrumb-item active mt-0">{{ $title }}</li>
@endif