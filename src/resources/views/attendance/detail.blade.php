@extends('layouts.app')

@section('title', '勤怠詳細 - COACHTECH 勤怠管理システム')

@section('page-css')
  <link rel="stylesheet" href="{{ asset('css/attendance-detail.css') }}" />
@endsection

@section('content')
  <div class="detail-container">
    <h1 class="page-title">勤怠詳細</h1>

    <div class="detail-card">
      <div class="detail-info">
        <p class="detail-label">日付</p>
        <p class="detail-value">{{ $attendance->date->format('Y年m月d日') }}</p>
      </div>

      <div class="detail-info">
        <p class="detail-label">出勤時刻</p>
        <p class="detail-value">{{ $attendance->clock_in_time?->format('H:i') ?? '-' }}</p>
      </div>

      <div class="detail-info">
        <p class="detail-label">退勤時刻</p>
        <p class="detail-value">{{ $attendance->clock_out_time?->format('H:i') ?? '-' }}</p>
      </div>

      <div class="detail-info">
        <p class="detail-label">休憩時間</p>
        <p class="detail-value">{{ $attendance->break_minutes ?? 0 }}分</p>
      </div>

      <div class="detail-info">
        <p class="detail-label">備考</p>
        <p class="detail-value">{{ $attendance->remarks ?? '-' }}</p>
      </div>
    </div>

    @if (!$hasPendingRequest)
      <div class="correction-form-section">
        <h2 class="section-title">修正申請</h2>

        <form action="{{ route('correction-request.store') }}" method="POST" class="correction-form">
          @csrf
          <input type="hidden" name="attendance_id" value="{{ $attendance->id }}" />

          <div class="form-group">
            <label for="requested-clock-in" class="form-label">修正出勤時刻</label>
            <input
              type="datetime-local"
              id="requested-clock-in"
              name="requested_clock_in_time"
              class="form-input @error('requested_clock_in_time') form-input--error @enderror"
              value="{{ old('requested_clock_in_time') }}"
            />
            @error('requested_clock_in_time')
              <span class="form-error">{{ $message }}</span>
            @enderror
          </div>

          <div class="form-group">
            <label for="requested-clock-out" class="form-label">修正退勤時刻</label>
            <input
              type="datetime-local"
              id="requested-clock-out"
              name="requested_clock_out_time"
              class="form-input @error('requested_clock_out_time') form-input--error @enderror"
              value="{{ old('requested_clock_out_time') }}"
            />
            @error('requested_clock_out_time')
              <span class="form-error">{{ $message }}</span>
            @enderror
          </div>

          <div class="form-group">
            <label for="requested-break" class="form-label">修正休憩時間（分）</label>
            <input
              type="number"
              id="requested-break"
              name="requested_break_minutes"
              class="form-input @error('requested_break_minutes') form-input--error @enderror"
              value="{{ old('requested_break_minutes') }}"
              min="0"
            />
            @error('requested_break_minutes')
              <span class="form-error">{{ $message }}</span>
            @enderror
          </div>

          <div class="form-group">
            <label for="requested-remarks" class="form-label">修正理由</label>
            <textarea
              id="requested-remarks"
              name="requested_remarks"
              class="form-textarea @error('requested_remarks') form-input--error @enderror"
              rows="4"
              required
            >{{ old('requested_remarks') }}</textarea>
            @error('requested_remarks')
              <span class="form-error">{{ $message }}</span>
            @enderror
          </div>

          <button type="submit" class="btn btn-primary">申請する</button>
        </form>
      </div>
    @else
      <div class="alert alert-info">
        <p>この勤怠の修正申請は現在承認待ちです。承認されるまで新たな申請はできません。</p>
      </div>
    @endif

    <div class="page-actions">
      <a href="{{ route('attendance.list') }}" class="btn btn-secondary">一覧に戻る</a>
    </div>
  </div>
@endsection
