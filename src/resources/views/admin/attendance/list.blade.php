@extends('layouts.admin')

@section('title', '勤怠一覧 - COACHTECH 勤怠管理システム')

@section('css')
  <link rel="stylesheet" href="{{ asset('css/admin-attendance-list.css') }}" />
@endsection

@section('content')
  <div class="admin-attendance-container">
    <h1 class="list-title">{{ $currentDate->format('Y年n月j日') }}の勤怠</h1>

    <div class="date-selector">
      <a href="{{ route('admin.attendance.index', ['date' => $previousDate]) }}" class="date-nav-btn">
        &larr; 前日
      </a>
      <span class="current-date">
        <input type="date" 
        id="date-picker" 
        class="date-picker-input" 
        value="{{ $currentDate->format('Y-m-d') }}" 
        onchange="location.href='{{ url(request()->path()) }}?date=' + this.value">
        <label for="date-picker" class="calendar-icon-label">
          &#128197;
        </label>
        <span class="date-text">{{ $currentDate->format('Y/m/d') }}</span>
      </span>
      <a href="{{ route('admin.attendance.index', ['date' => $nextDate]) }}" class="date-nav-btn">
        翌日 &rarr;
      </a>
    </div>

    <div class="table-wrapper">
      <table class="data-table">
        <thead>
          <tr>
            <th>名前</th>
            <th>出勤</th>
            <th>退勤</th>
            <th>休憩</th>
            <th>合計</th>
            <th>詳細</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($attendances as $attendance)
            <tr>
              <td>{{ $attendance->user->name }}</td>
              <td>{{ $attendance->clock_in_time?->format('H:i') ?? '' }}</td>
              <td>{{ $attendance->clock_out_time?->format('H:i') ?? '' }}</td>
              <td>{{ $attendance->break_minutes ? sprintf('%d:%02d', intdiv($attendance->break_minutes, 60), $attendance->break_minutes % 60) : '' }}</td>
              <td>{{ $attendance->working_hours ?? '' }}</td>
              <td>
                <a href="{{ route('admin.attendance.detail', $attendance->id) }}" class="detail-link">詳細</a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="table-empty">この日の勤怠データはありません</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection
