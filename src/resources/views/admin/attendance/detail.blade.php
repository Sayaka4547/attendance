@extends('layouts.admin')

@section('title', '勤怠詳細 - COACHTECH 勤怠管理システム')

@section('css')
  <link rel="stylesheet" href="{{ asset('css/admin-attendance-detail.css') }}" />
@endsection

@section('content')
  <div class="admin-detail-container">
    <h1 class="list-title">勤怠詳細</h1>

    <form action="{{ route('admin.attendance.update', $attendance->id) }}" method="POST">
      @csrf

      <div class="detail-card">
        <div class="detail-row">
          <div class="detail-label">名前</div>
          <div class="detail-value">{{ $attendance->user->name }}</div>
        </div>

        <div class="detail-row">
          <div class="detail-label">日付</div>
          <div class="detail-value">
            <span>{{ $attendance->date->format('Y年') }}</span>
            <span class="date-day">{{ $attendance->date->format('n月j日') }}</span>
          </div>
        </div>

        <div class="detail-row">
          <div class="detail-label">出勤・退勤</div>
          <div class="detail-value time-range">
            <input type="text" name="clock_in_time" class="time-input"
              value="{{ old('clock_in_time', $attendance->clock_in_time?->format('H:i')) }}">
            <span class="tilde">〜</span>
            <input type="text" name="clock_out_time" class="time-input"
              value="{{ old('clock_out_time', $attendance->clock_out_time?->format('H:i')) }}">
          </div>
        </div>

        @php $breaks = $attendance->breaks; @endphp

        @for ($i = 0; $i < max(2, $breaks->count()); $i++)
          <div class="detail-row">
            <div class="detail-label">休憩{{ $i === 0 ? '' : $i + 1 }}</div>
            <div class="detail-value time-range">
              <input type="text" name="breaks[{{ $i }}][start_time]" class="time-input"
                value="{{ old('breaks.' . $i . '.start_time', isset($breaks[$i]) ? $breaks[$i]->start_time?->format('H:i') : '') }}">
              <span class="tilde">〜</span>
              <input type="text" name="breaks[{{ $i }}][end_time]" class="time-input"
                value="{{ old('breaks.' . $i . '.end_time', isset($breaks[$i]) ? $breaks[$i]->end_time?->format('H:i') : '') }}">
            </div>
          </div>
        @endfor

        <div class="detail-row">
          <div class="detail-label">備考</div>
          <div class="detail-value">
            <textarea name="note" class="note-input">{{ old('note', $attendance->note) }}</textarea>
          </div>
        </div>
      </div>

      @if ($errors->any())
        <div class="error-list">
          @foreach ($errors->all() as $error)
            <p class="error-text">{{ $error }}</p>
          @endforeach
        </div>
      @endif

      <div class="form-footer">
        <button type="submit" class="btn-submit">修正</button>
      </div>
    </form>
  </div>

@endsection