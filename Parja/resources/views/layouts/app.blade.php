<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('template/dist/assets/img/favicon.png') }}">

    <title>@yield('title', 'Parja') | {{ config('app.name', 'ePublic') }}</title>

    <link rel="stylesheet" href="{{ asset('template/dist/lib/remixicon/fonts/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('template/dist/assets/css/style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('SweetAlert/sweetalert2.min.css') }}">
    @stack('styles')
</head>

<body>

    @include('sweetalert::alert')

    @include('parja::layouts.sidebar')
    @include('parja::layouts.header')

    <div class="main main-app p-3 p-lg-4">
        @yield('content')
        @include('parja::layouts.footer')
    </div>

    <script src="{{ asset('template/dist/lib/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('template/dist/lib/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('template/dist/lib/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('template/dist/assets/js/script.js') }}"></script>
    <script src="{{ asset('SweetAlert/sweetalert2.all.min.js') }}"></script>

    @stack('scripts')
</body>
</html>
