<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'COACHTECH 勤怠管理システム')</title>
    <link rel="stylesheet" href="{{ asset('css/common.css') }}" />
    @yield('page-css')
  </head>
  <body>
    @include('components.header')

    <main class="main-content">
      @if ($errors->any())
        <div class="alert alert-error">
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      @if (session('success'))
        <div class="alert alert-success">
          {{ session('success') }}
        </div>
      @endif

      @yield('content')
    </main>

    @include('components.footer')
  </body>
</html>
