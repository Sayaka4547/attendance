@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance-index.css') }}">
@endsection

@section('content')
<div class="attendance-board">
    <div class="board-date">{{ $today->format('Y年n月j日') }}</div>
    <div class="board-time" id="clock">00:00</div>

    <div class="stamp-actions">
        {{-- 出勤 --}}
        <form method="POST" action="{{ route('attendance.clockIn') }}">
            @csrf
            <button type="submit" class="stamp-btn btn-clock-in" @disabled($attendance !== null)>
                出勤
            </button>
        </form>

        {{-- 退勤 --}}
        <form method="POST" action="{{ route('attendance.clockOut') }}">
            @csrf
            <button type="submit" class="stamp-btn btn-clock-out" @disabled(!$attendance || $attendance->status !== 'working')>
                退勤
            </button>
        </form>

        {{-- 休憩入 --}}
        <form method="POST" action="{{ route('attendance.breakStart') }}">
            @csrf
            <button type="submit" class="stamp-btn btn-break-start" @disabled(!$attendance || $attendance->status !== 'working')>
                休憩入
            </button>
        </form>

        {{-- 休憩戻 --}}
        <form method="POST" action="{{ route('attendance.breakEnd') }}">
            @csrf
            <button type="submit" class="stamp-btn btn-break-end" @disabled(!$attendance || $attendance->status !== 'on_break')>
                休憩戻
            </button>
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
