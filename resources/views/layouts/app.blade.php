<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  
  <meta name="description" content="@yield('description', 'LaraBBS 9 愛好者社區')">

  <title>@yield('title', 'LaraBBS 9') - LaraBBS 9</title>

  <!-- Styles -->
  <!-- <link href="{{ mix('css/app.css') }}" rel="stylesheet"> -->
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <link rel="stylesheet" href="{{ asset('sudo-su/css/app.css') }}">
  @yield('styles')
</head>

<body>
  <div id="app" class="{{ route_class() }}-page">

    @include('layouts._header')

    <div class="container">

      @include('shared._messages')

      @yield('content')

    </div>

    @include('layouts._footer')
  </div>

  @if(app()->isLocal())
    @include('sudosu::user-selector')
  @endif

  <!-- Scripts -->
  <!-- <script src="{{ mix('js/app.js') }}"></script> -->
  <script src="{{ asset('js/app.js') }}"></script>
  @yield('scripts')
</body>

</html>