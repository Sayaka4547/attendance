@extends('layouts.app')

@section('title', '打刻 - COACHTECH 勤怠管理システム')

@section('page-css')
  <link rel="stylesheet" href="{{ asset('css/attendance-index.css') }}" />
@endsection

@section('content')
  <div class="attendance-container">
    <div class="attendance-card">
      <h1 class="attendance-title">勤務状況</h1>

      <div class="attendance-date">
        <p class="date-label">{{ now()->format('Y年m月d日') }}</p>
        <p class="time-display" id="current-time">08:00</p>
      </div>

      <div class="attendance-status">
        <p class="status-label">ステータス</p>
        <p class="status-value">{{ $currentAttendance->status ?? '勤務外' }}</p>
      </div>

      <div class="attendance-actions">
        @if (!$currentAttendance || $currentAttendance->status === 'not_started')
          <form action="{{ route('attendance.clock-in') }}" method="POST" class="action-form">
            @csrf
            <button type="submit" class="btn btn-primary">出勤</button>
          </form>
        @endif

        @if ($currentAttendance && $currentAttendance->status === 'working')
          <form action="{{ route('attendance.break-start') }}" method="POST" class="action-form">
            @csrf
            <button type="submit" class="btn btn-secondary">休憩入</button>
          </form>

          <form action="{{ route('attendance.clock-out') }}" method="POST" class="action-form">
            @csrf
            <button type="submit" class="btn btn-primary">退勤</button>
          </form>
        @endif

        @if ($currentAttendance && $currentAttendance->status === 'on_break')
          <form action="{{ route('attendance.break-end') }}" method="POST" class="action-form">
            @csrf
            <button type="submit" class="btn btn-secondary">休憩戻</button>
          </form>

          <form action="{{ route('attendance.clock-out') }}" method="POST" class="action-form">
            @csrf
            <button type="submit" class="btn btn-primary">退勤</button>
          </form>
        @endif

        @if ($currentAttendance && $currentAttendance->status === 'finished')
          <p class="status-finished">本日の勤務は終了しました</p>
        @endif
      </div>

      <div class="attendance-links">
        <a href="{{ route('attendance.list') }}" class="link-button">勤怠一覧へ</a>
        <a href="{{ route('correction-request.list') }}" class="link-button">申請状況へ</a>
      </div>
    </div>
  </div>

  <script>
    function updateTime() {
      const now = new Date();
      const hours = String(now.getHours()).padStart(2, '0');
      const minutes = String(now.getMinutes()).padStart(2, '0');
      document.getElementById('current-time').textContent = `${hours}:${minutes}`;
    }

    updateTime();
    setInterval(updateTime, 1000);
  </script>
@endsection
