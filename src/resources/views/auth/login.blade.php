@extends('layouts.app')

@section('title', 'ログイン - COACHTECH 勤怠管理システム')

@section('page-css')
  <link rel="stylesheet" href="{{ asset('css/auth.css') }}" />
@endsection

@section('content')
  <div class="auth-container">
    <div class="auth-box">
      <h1 class="auth-title">ログイン</h1>

      <form action="{{ route('login') }}" method="POST" class="auth-form">
        @csrf

        <div class="form-group">
          <label for="email" class="form-label">メールアドレス</label>
          <input
            type="email"
            id="email"
            name="email"
            class="form-input @error('email') form-input--error @enderror"
            value="{{ old('email') }}"
            required
          />
          @error('email')
            <span class="form-error">{{ $message }}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="password" class="form-label">パスワード</label>
          <input
            type="password"
            id="password"
            name="password"
            class="form-input @error('password') form-input--error @enderror"
            required
          />
          @error('password')
            <span class="form-error">{{ $message }}</span>
          @enderror
        </div>

        @if ($errors->has('login'))
          <div class="form-error-message">
            {{ $errors->first('login') }}
          </div>
        @endif

        <button type="submit" class="btn btn-primary">ログインする</button>
      </form>

      <p class="auth-link">
        アカウントをお持ちでないですか？
        <a href="{{ route('register') }}">会員登録</a>
      </p>
    </div>
  </div>
@endsection
