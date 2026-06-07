@extends('layouts.app')

@section('title', '勤怠一覧 - COACHTECH 勤怠管理システム')

@section('page-css')
  <link rel="stylesheet" href="{{ asset('css/attendance-list.css') }}" />
@endsection

@section('content')
  <div class="attendance-list-container">
    <h1 class="page-title">勤怠一覧</h1>

    <div class="month-navigation">
      <a href="{{ route('attendance.list', ['month' => $previousMonth]) }}" class="btn-nav">
        &lt; 前月
      </a>
      <span class="month-display">{{ $currentMonth->format('Y年m月') }}</span>
      <a href="{{ route('attendance.list', ['month' => $nextMonth]) }}" class="btn-nav">
        翌月 &gt;
      </a>
    </div>

    <div class="table-wrapper">
      <table class="attendance-table">
        <thead>
          <tr>
            <th>日付</th>
            <th>曜日</th>
            <th>出勤</th>
            <th>退勤</th>
            <th>休憩</th>
            <th>勤務時間</th>
            <th>ステータス</th>
            <th>操作</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($attendances as $attendance)
            <tr>
              <td>{{ $attendance->date->format('m/d') }}</td>
              <td>{{ $attendance->date->format('(D)') }}</td>
              <td>{{ $attendance->clock_in_time?->format('H:i') ?? '-' }}</td>
              <td>{{ $attendance->clock_out_time?->format('H:i') ?? '-' }}</td>
              <td>{{ $attendance->break_minutes ?? 0 }}分</td>
              <td>{{ $attendance->working_hours ?? '-' }}</td>
              <td>
                <span class="status-badge status-{{ $attendance->status }}">
                  {{ $attendance->status }}
                </span>
              </td>
              <td>
                <a href="{{ route('attendance.detail', $attendance->id) }}" class="btn-link">
                  詳細
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="table-empty">勤怠データがありません</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="page-actions">
      <a href="{{ route('attendance.index') }}" class="btn btn-secondary">戻る</a>
    </div>
  </div>
@endsection
