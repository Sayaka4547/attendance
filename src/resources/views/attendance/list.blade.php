@extends('layouts.app')

@section('title', '勤怠一覧 - COACHTECH 勤怠管理システム')

@section('css')
  <link rel="stylesheet" href="{{ asset('css/attendance-list.css') }}" />
@endsection

@section('content')
  <div class="attendance-list-container">
    <h1 class="list-title">勤怠一覧</h1>

    <div class="month-selector">
      <a href="{{ route('attendance.list', ['month' => $previousMonth]) }}" class="month-nav-btn month-nav-prev">
        &larr; 前月
      </a>
      <span class="current-month">
        <span class="calendar-icon">&#128197;</span>
        {{$currentMonth->format('Y/m') }}
      </span>
      <a href="{{ route('attendance.list', ['month' => $nextMonth]) }}" class="month-nav-btn month-nav-next">
        翌月 &rarr;
      </a>
    </div>

    <div class="table-wrapper">
      <table class="data-table">
        <thead>
          <tr>
            <th>日付</th>
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
              @php
              $weekdays = ['日', '月', '火', '水', '木', '金', '土'];
              $dayOfWeek = $weekdays[$attendance->date->dayOfWeek];
              @endphp
              <td>{{ $attendance->date->format('m/d') }}({{ $dayOfWeek}})</td>
              <td>{{ $attendance->clock_in_time?->format('H:i') ?? '' }}</td>
              <td>{{ $attendance->clock_out_time?->format('H:i') ?? '' }}</td>
              <td>{{ $attendance->break_minutes ? sprintf('%d:%02d', intdiv($attendance->break_minutes, 60), $attendance->break_minutes % 60) : '' }}</td>
              <td>{{ $attendance->working_hours ?? '' }}</td>
              <td>
                <a href="{{ route('attendance.detail', $attendance->id) }}" class="detail-link">
                  詳細
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="table-empty">勤怠データがありません</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection
