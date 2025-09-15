<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- <title>@yield('title', $siteTitle)</title> --}}
    <title>@yield('title')</title>

    @include('admin.includes.styles')
		@stack('scripts')
    @yield('styles')

</head>

<body class="app sidebar-mini">

	<div id="global-loader">
		<img src="{{ asset('assets/images/svgs/loader.svg') }}" alt="loader">
	</div>

	<div class="page">
		<div class="page-main">
			@include('admin.includes.sidebar')
			@include('admin.includes.header')
			<div class="app-content" style="padding-left: 20px">
				@yield('content')
			</div>
		</div>
	</div>
	<a href="#" id="back-to-top"><span class="feather feather-chevrons-up"></span></a>

	@include('admin.includes.scripts')

	@stack('scripts')
	@yield('scripts')
</body>

</html>
