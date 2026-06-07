<header class="header">
  <div class="header-container">
    <div class="header-logo">
      <img src="{{ asset('images/coachtech-logo.png') }}" alt="COACHTECH" />
    </div>

    <nav class="header-nav">
      @auth
        <ul class="nav-list">
          @if (auth()->user()->role === 'admin')
            <li><a href="{{ route('admin.attendance.list') }}">日次勤怠</a></li>
            <li><a href="{{ route('admin.staff.list') }}">スタッフ一覧</a></li>
            <li><a href="{{ route('admin.correction-request.list') }}">申請承認</a></li>
          @else
            <li><a href="{{ route('attendance.index') }}">打刻</a></li>
            <li><a href="{{ route('attendance.list') }}">勤怠一覧</a></li>
            <li><a href="{{ route('correction-request.list') }}">申請状況</a></li>
          @endif
        </ul>

        <div class="header-user">
          <span class="user-name">{{ auth()->user()->name }}</span>
          <form action="{{ route('logout') }}" method="POST" class="logout-form">
            @csrf
            <button type="submit" class="btn-logout">ログアウト</button>
          </form>
        </div>
      @endauth
    </nav>
  </div>
</header>
