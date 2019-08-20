@php
$title = "Login"
@endphp

@extends('app')

@section('content')
<div class="container">
  <p>
    <a href="{{route('root')}}">戻る</a>
  </p>
  <h1>Login</h1>

  <div class="login-form form-wrapper">
    <form action="{{ route('auth.login') }}" method="POST">
      @csrf

      <label for="email">メールアドレス</label>
      <input type="email" class="form__input" required name="email" id="email">

      <label for="password">パスワード</label>
      <input type="password" class="form__input" required minlength="6" name="password">

      @if ($errors->any())
      <p>
        <strong>メールアドレスまたはパスワードが間違っています。</strong>
      </p>
      @endif

      <input type="submit" class="form__button" value="ログイン">
    </form>
  </div>
</div>
@endsection