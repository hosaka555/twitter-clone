@php
$title = "新規登録"
@endphp

@extends('app')

@section('content')
<div class="container">
  <p>
    <a href="{{route('root')}}">戻る</a>
  </p>

  <h1>ユーザー登録</h1>

  <div class="register-form form-wrapper">
    <form action="{{ route('auth.register') }}" method="POST">
      @csrf

      <label for="account_id">アカウントID</label>
      <input type="name" class="form__input" required name="account_id">

      <label for="username">ユーザーネーム</label>
      <input type="username" class="form__input" required name="username" id="username">

      <label for="email">メールアドレス</label>
      <input type="email" class="form__input" required name="email" id="email">

      <label for="password">パスワード</label>
      <input type="password" class="form__input" required minlength="6" name="password">

      <label for="password_confirmation">パスワードの確認</label>
      <input type="password" class="form__input" required minlength="6" name="password_confirmation"
        id="password_confirmation">

      @if ($errors->any())
      <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
      </ul>
      @endif

      <input type="submit" class="form__button" value="新規登録">
    </form>

    <p>
      <a href="{{ route('auth.login') }}">すでにアカウントをお持ちの方はこちら</a>
    </p>
  </div>
</div>
@endsection