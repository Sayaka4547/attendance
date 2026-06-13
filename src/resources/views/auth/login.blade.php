@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <h1 class="auth-title">ログイン</h1>

        <form action="{{ route('login') }}" method="POST" class="auth-form">
            @csrf

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

            @if ($errors->has('login'))
                <span class="error-message">{{ $errors->first('login') }}</span>
            @endif

            <button type="submit" class="auth-submit-btn">ログインする</button>
        </form>
    
        <a href="{{ route('register') }}" class="auth-link">会員登録はこちら</a>
    </div>
</div>
@endsection
