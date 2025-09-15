@php

  $routes = [
    'admin' => 'admin.dashboard',
    'company' => 'company.dashboard',
    'employee' => 'employee.dashboard',
  ];

  $guard = collect(array_keys($routes))->first(fn($g) => auth($g)->check());
  $route = $guard ? route($routes[$guard]) : '#';
  
@endphp

<ol class="breadcrumb align-items-center fs-12">
  <li class="breadcrumb-item">
    <a href="{{ $route }}">داشبورد</a>
  </li>
  {{ $slot }}
</ol>
