<header class="header">
  <div class="header-inner">
      <div class="header-logo">
      <img src="{{ asset('images/coachtech-logo.png') }}" alt="COACHTECH" />
      </div>

    <nav class="header-nav">
      <a href="{{ route('admin.attendance.index') }}" class="nav-item">勤怠一覧</a>
      <a href="{{ route('admin.staff.index') }}" class="nav-item">スタッフ一覧</a>
      <a href="{{ route('admin.correction-request.index') }}" class="nav-item">申請一覧</a>
      <form action="{{ route('admin.logout') }}" method="POST" class="logout-form">
        @csrf
        <button type="submit" class="logout-btn">ログアウト</button>
      </form>
    </nav>
  </div>
</header>