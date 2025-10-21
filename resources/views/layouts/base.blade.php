<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="_token" content="{{ csrf_token() }}" />
  <title>@yield('title', config('config.app_name'))</title>

  <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('/css/bootstrap.css') }}" />
  <link rel="stylesheet" href="{{ asset('/css/fontawesome.css') }}" />
  <link rel="stylesheet" href="{{ asset('/owl-carousel/assets/owl.carousel.css') }}" />
  <link rel="stylesheet" href="{{ asset('/css/styles.css?v=' . time()) }}" />
</head>

<body>
  <input type="hidden" id="baseUrl" value="{{ url('/') }}" />
  <script>const baseUrl = document.getElementById("baseUrl").value;</script>

  @yield('body')

  {{-- Core JS --}}
  <script src="{{ asset('/js/jquery.js') }}"></script>
  <script src="{{ asset('/js/popper.min.js') }}"></script>
  <script src="{{ asset('/js/bootstrap.js') }}"></script>
  <script src="{{ asset('/owl-carousel/owl.carousel.js') }}"></script>

  <script src="{{ asset('/js/react.development.js') }}"></script>
  <script src="{{ asset('/js/react-dom.development.js') }}"></script>
  <script src="{{ asset('/js/babel.min.js') }}"></script>
  <script src="{{ asset('/js/axios.min.js') }}"></script>
  <script src="{{ asset('/js/sweetalert2@11.js') }}"></script>
  <script src="{{ asset('/js/fontawesome.js') }}"></script>

  <script src="https://www.gstatic.com/firebasejs/10.12.0/firebase-app-compat.js"></script>
  <script src="https://www.gstatic.com/firebasejs/10.12.0/firebase-database-compat.js"></script>

  <script src="{{ asset('/js/script.js?v=' . time()) }}"></script>

  @yield('scripts')
</body>
</html>
