@php
$title = "Login"
@endphp

@extends('app')

@section('content')
<div class="container">
  <h1>Login</h1>

  <div class="login-form form-wrapper">
    <form action="{{ route('auth.login') }}" method="POST">
      @csrf

      <input type="email" class="form__inputd" required name="email">

      <input type="password" class="form__input" required minlength="6" name="password">

      @if ($errors->has('email') || $errors->has('password'))
      <p>
        <strong>メールアドレスまたはパスワードが間違っています。</strong>
      </p>
      @endif

      <input type="submit" class="form__button" value="ログイン">
    </form>
  </div>
</div>
@endsection