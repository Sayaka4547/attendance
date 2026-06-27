@extends('layouts.admin')

@section('title', '修正申請承認 - COACHTECH 勤怠管理システム')

@section('css')
  <link rel="stylesheet" href="{{ asset('css/admin-correction-request.css') }}" />
@endsection

@section('content')
  <div class="admin-request-container">
    <h1 class="list-title">勤怠詳細</h1>

    <div class="detail-card">
      <div class="detail-row">
        <div class="detail-label">名前</div>
        <div class="detail-value">{{ $correctionRequest->attendance->user->name }}</div>
      </div>

      <div class="detail-row">
        <div class="detail-label">日付</div>
        <div class="detail-value">
          <span>{{ $correctionRequest->attendance->date->format('Y年') }}</span>
          <span class="date-day">{{ $correctionRequest->attendance->date->format('n月j日') }}</span>
        </div>
      </div>

      <div class="detail-row">
        <div class="detail-label">出勤・退勤</div>
        <div class="detail-value time-range">
          <span>{{ $correctionRequest->requested_clock_in_time?->format('H:i') }}</span>
          <span class="tilde">〜</span>
          <span>{{ $correctionRequest->requested_clock_out_time?->format('H:i') }}</span>
        </div>
      </div>

      @foreach ($correctionRequest->correctionRequestBreaks as $index => $break)
        <div class="detail-row">
          <div class="detail-label">休憩{{ $index === 0 ? '' : $index + 1 }}</div>
          <div class="detail-value time-range">
            <span>{{ $break->start_time ? \Carbon\Carbon::parse($break->start_time)->format('H:i') : '' }}</span>
            <span class="tilde">〜</span>
            <span>{{ $break->end_time ? \Carbon\Carbon::parse($break->end_time)->format('H:i') : '' }}</span>
          </div>
        </div>
      @endforeach

      @if ($correctionRequest->correctionRequestBreaks->isEmpty())
        <div class="detail-row">
          <div class="detail-label">休憩</div>
          <div class="detail-value time-range">
            <span></span>
            <span class="tilde">〜</span>
            <span></span>
          </div>
        </div>
      @endif

      <div class="detail-row">
        <div class="detail-label">備考</div>
        <div class="detail-value">{{ $correctionRequest->requested_remarks }}</div>
      </div>
    </div>

    
    <div class="form-footer">
    @if ($correctionRequest->status === 'pending')
    <form action="{{ route('admin.correction-request.approve.update', $correctionRequest->id) }}" method="POST">
      @csrf
      <button type="submit" class="btn-submit">承認</button>
    </form>
    @else
    <button type="button" class="btn-approved" disabled>承認済み</button>
    @endif
    </div>

  </div>

@endsection

