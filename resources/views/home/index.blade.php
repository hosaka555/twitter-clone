<!DOCTYPE html>
<html>

<head>
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>Clone Twitter</title>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
  <div id="app"></div>
  <div id="token" style="display: none;">
    <!-- ここでSessionStoreからjwt-tokenの値を取得して表示 -->
    {{ Session::get('jwt-token') }}
  </div>
  <script src="{{ asset('js/app.js') }}" defer></script>
</body>

</html>