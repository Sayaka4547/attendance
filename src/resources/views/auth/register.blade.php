@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <h1 class="auth-title">会員登録</h1>

        <form action="{{ route('register') }}" method="POST" class="auth-form">
            @csrf

            <div class="form-group">
                <label for="name" class="form-label">お名前</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    class="form-input @error('name') is-error @enderror"
                    value="{{ old('name') }}"
                >
                @error('name')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">メールアドレス</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-input @error('email') is-error @enderror"
                    value="{{ old('email') }}"
                >
                @error('email')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">パスワード</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-input @error('password') is-error @enderror"
                >
                @error('password')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password-confirm" class="form-label">パスワード確認</label>
                <input
                    type="password"
                    id="password-confirm"
                    name="password_confirmation"
                    class="form-input"
                >
            </div>

            <button type="submit" class="auth-submit-btn">登録する</button>
        </form>

            <a href="{{ route('login') }}" class="auth-link">ログインはこちら</a>
    </div>
</div>
@endsection
