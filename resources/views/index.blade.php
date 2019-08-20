@extends('app')

@section('content')
<div class="container">
  <h1>Twitter Clone</h1>
  <p>
    <a href="{{ route('auth.login') }}">Login</a>
  </p>
  <p>
    <a href="{{ route('auth.register') }}">Register</a>
  </p>
</div>
@endsection