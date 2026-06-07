@section('page-css')
  <link rel="stylesheet" href="{{ asset('css/auth.css') }}" />
@endsection

@section('content')
  <div class="auth-container">
    <div class="auth-box">
      <h1 class="auth-title">会員登録</h1>

      <form action="{{ route('register') }}" method="POST" class="auth-form">
        @csrf

        <div class="form-group">
          <label for="name" class="form-label">お名前</label>
          <input
            type="text"
            id="name"
            name="name"
            class="form-input @error('name') form-input--error @enderror"
            value="{{ old('name') }}"
            required
          />
          @error('name')
            <span class="form-error">{{ $message }}</span>
          @enderror
        </div>

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

        <div class="form-group">
          <label for="password-confirm" class="form-label">パスワード確認</label>
          <input
            type="password"
            id="password-confirm"
            name="password_confirmation"
            class="form-input @error('password_confirmation') form-input--error @enderror"
            required
          />
          @error('password_confirmation')
            <span class="form-error">{{ $message }}</span>
          @enderror
        </div>

        <button type="submit" class="btn btn-primary">登録する</button>
      </form>

      <p class="auth-link">
        すでにアカウントをお持ちですか？
        <a href="{{ route('login') }}">ログイン</a>
      </p>
    </div>
  </div>
@endsection
