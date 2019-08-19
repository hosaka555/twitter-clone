<!DOCTYPE html>
<html>

<head>
  <title>@isset($title){{ $title }} | @endisset Clone Twitter</title>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
  <!-- フラッシュメッセージ -->
  @if (session('flash_message'))
  <div class="flash_message">
    {{ session('flash_message') }}
  </div>
  @endif

  @yield('content')

  <script src="{{ asset('js/app.js') }}" defer></script>
</body>

</html>