@extends('layouts.app')

@section('title', '勤怠詳細 - COACHTECH 勤怠管理システム')

@section('css')
  <link rel="stylesheet" href="{{ asset('css/attendance-detail.css') }}" />
@endsection

@section('content')
  <div class="attendance-detail-container">
    <h1 class="detail-title">勤怠詳細</h1>

    <div class="detail-card">
      <form id="correction-form" action="{{ route('attendance.requestCorrection', $attendance->id) }}" method="POST">
        @csrf

        {{-- 名前 --}}
        <div class="detail-row">
          <div class="detail-label">名前</div>
          <div class="detail-value">{{ $attendance->user->name }}</div>
        </div>

        {{-- 日付 --}}
        <div class="detail-row">
          <div class="detail-label">日付</div>
          <div class="detail-value detail-date">
            <span>{{ $attendance->date->format('Y年') }}</span>
            <span>{{ $attendance->date->format('n月j日') }}</span>
          </div>
        </div>

        {{-- 出勤・退勤 --}}
        <div class="detail-row">
          <div class="detail-label">出勤・退勤</div>
          <div class="detail-value detail-time-range">
            @if ($isPending)
              <span class="time-text">{{ $attendance->clock_in_time?->format('H:i') ?? '' }}</span>
              <span class="time-separator">〜</span>
              <span class="time-text">{{ $attendance->clock_out_time?->format('H:i') ?? '' }}</span>
            @else
              <input type="time" name="clock_in_time" class="time-input"
                value="{{ $attendance->clock_in_time?->format('H:i') ?? '' }}">
              <span class="time-separator">〜</span>
              <input type="time" name="clock_out_time" class="time-input"
                value="{{ $attendance->clock_out_time?->format('H:i') ?? '' }}">
            @endif
          </div>
        </div>

        {{-- 休憩（複数） --}}
        @foreach ($breaks as $index => $break)
          <div class="detail-row">
            <div class="detail-label">休憩{{ $index === 0 ? '' : $index + 1 }}</div>
            <div class="detail-value detail-time-range">
              @if ($isPending)
                <span class="time-text">{{ $break->start_time?->format('H:i') ?? '' }}</span>
                <span class="time-separator">〜</span>
                <span class="time-text">{{ $break->end_time?->format('H:i') ?? '' }}</span>
              @else
                <input type="time" name="breaks[{{ $index }}][start_time]" class="time-input"
                  value="{{ $break->start_time?->format('H:i') ?? '' }}">
                <span class="time-separator">〜</span>
                <input type="time" name="breaks[{{ $index }}][end_time]" class="time-input"
                  value="{{ $break->end_time?->format('H:i') ?? '' }}">
              @endif
            </div>
          </div>
        @endforeach

        {{-- 休憩が0件でも空欄行を1行表示 --}}
        @if ($breaks->isEmpty() && !$isPending)
          <div class="detail-row">
            <div class="detail-label">休憩</div>
            <div class="detail-value detail-time-range">
              <input type="time" name="breaks[0][start_time]" class="time-input" value="">
              <span class="time-separator">〜</span>
              <input type="time" name="breaks[0][end_time]" class="time-input" value="">
            </div>
          </div>
        @endif

        {{-- 追加の空白休憩行（デザインの「休憩2」空欄行） --}}
        @if (!$isPending)
          <div class="detail-row">
            <div class="detail-label">休憩{{ $breaks->count() + 1 }}</div>
            <div class="detail-value detail-time-range">
              <input type="time" name="breaks[{{ $breaks->count() }}][start_time]" class="time-input" value="">
              <span class="time-separator">〜</span>
              <input type="time" name="breaks[{{ $breaks->count() }}][end_time]" class="time-input" value="">
            </div>
          </div>
        @endif

        {{-- 備考 --}}
        <div class="detail-row">
          <div class="detail-label">備考</div>
          <div class="detail-value">
            @if ($isPending)
              <span class="remarks-text">{{ $attendance->remarks ?? '' }}</span>
            @else
              <textarea name="remarks" class="remarks-input">{{ $attendance->remarks ?? '' }}</textarea>
            @endif
          </div>
        </div>
      </form>
    </div>
   {{-- 承認待ちメッセージ or 修正ボタン --}}
    @if ($isPending)
      <div class="detail-footer">
        <p class="pending-message">*承認待ちのため修正はできません。</p>
      </div>
    @else
      <div class="detail-footer">
        <button type="submit" form="correction-form" class="btn-submit">修正</button>
      </div>
    @endif

  </div>
@endsection
