@extends('layouts.admin')

@section('title', $staff->name . 'さんの勤怠 - COACHTECH 勤怠管理システム')

@section('css')
  <link rel="stylesheet" href="{{ asset('css/admin-staff.css') }}" />
@endsection

@section('content')
  <div class="admin-staff-container">
    <h1 class="list-title">{{ $staff->name }}さんの勤怠</h1>

    <div class="month-selector">
      <a href="{{ route('admin.staff.attendance', ['id' => $staff->id, 'month' => $previousMonth]) }}" class="month-nav-btn">
        &larr; 前月
      </a>
      <span class="current-month">
        <span class="calendar-icon">&#128197;</span>
        {{ $currentMonth->format('Y/m') }}
      </span>
      <a href="{{ route('admin.staff.attendance', ['id' => $staff->id, 'month' => $nextMonth]) }}" class="month-nav-btn">
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
            @php
              $weekdays = ['日', '月', '火', '水', '木', '金', '土'];
              $dayOfWeek = $weekdays[$attendance->date->dayOfWeek];
            @endphp
            <tr>
              <td>{{ $attendance->date->format('m/d') }}({{ $dayOfWeek }})</td>
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
              <td colspan="6" class="table-empty">この月の勤怠データはありません</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="csv-footer">
      <a href="{{ route('admin.staff.csv', ['id' => $staff->id, 'month' => $currentMonth->format('Y-m')]) }}" class="btn-csv">CSV出力</a>
    </div>
  </div>
@endsection