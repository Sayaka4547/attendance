@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance-index.css') }}">
@endsection

@section('content')
<div class="attendance-board">
    <div class="stamp-status">
    @if (!$attendance)
        <span class="status-badge">勤務外</span>
    @elseif ($attendance->status === 'working')
        <span class="status-badge status-badge--working">出勤中</span>
    @elseif ($attendance->status === 'on_break')
        <span class="status-badge status-badge--break">休憩中</span>
    @else
        <span class="status-badge">退勤済</span>
    @endif
    </div>
    
    <div class="board-date">{{ $today->format('Y年n月j日') }}</div>
    <div class="board-time" id="clock">00:00</div>

    <div class="stamp-actions">
    @if (!$attendance)
        {{-- 勤務外：出勤ボタンのみ --}}
        <form method="POST" action="{{ route('attendance.clockIn') }}">
            @csrf
            <button type="submit" class="stamp-btn stamp-btn--primary">出勤</button>
        </form>

    @elseif ($attendance->status === 'working')
        {{-- 出勤中：退勤・休憩入ボタン --}}
        <form method="POST" action="{{ route('attendance.clockOut') }}">
            @csrf
            <button type="submit" class="stamp-btn stamp-btn--primary">退勤</button>
        </form>
        <form method="POST" action="{{ route('attendance.breakStart') }}">
            @csrf
            <button type="submit" class="stamp-btn stamp-btn--secondary">休憩入</button>
        </form>

    @elseif ($attendance->status === 'on_break')
        {{-- 休憩中：休憩戻ボタンのみ --}}
        <form method="POST" action="{{ route('attendance.breakEnd') }}">
            @csrf
            <button type="submit" class="stamp-btn stamp-btn--secondary">休憩戻</button>
        </form>

    @else
        {{-- 退勤後：メッセージのみ --}}
        <p class="stamp-done-text">お疲れ様でした。</p>

    @endif
        </form>
    </div>
</div>

<script>
    // リアルタイム時計の表示
    function updateClock() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        document.getElementById('clock').textContent = `${hours}:${minutes}`;
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>

@endsection
