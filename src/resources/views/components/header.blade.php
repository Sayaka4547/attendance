<header class="header">
  <div class="header-inner">
    <div class="header-logo">
      <img src="{{ asset('images/coachtech-logo.png') }}" alt="COACHTECH" />
    </div>

    <nav class="header-nav">
      @auth
        @if (auth()->user()->role === 'admin')
          <a href="{{ route('admin.attendance.list') }}" class="nav-item">日次勤怠</a>
          <a href="{{ route('admin.staff.list') }}" class="nav-item">スタッフ一覧</a>
          <a href="{{ route('correction-request.index') }}" class="nav-item">申請承認</a>
        @else
          <a href="{{ route('attendance.index') }}" class="nav-item">勤怠</a>
          <a href="{{ route('attendance.list') }}" class="nav-item">勤怠一覧</a>
          <a href="{{ route('correction-request.index') }}" class="nav-item">申請状況</a>
        @endif
        <div class="header-user">
          <form action="{{ route('logout') }}" method="POST" class="logout-form">
            @csrf
            <button type="submit" class="logout-btn">ログアウト</button>
          </form>
        </div>
      @endauth
    </nav>
  </div>

</header>
