@extends('layouts.app')

@section('title', '申請状況 - COACHTECH 勤怠管理システム')

@section('page-css')
  <link rel="stylesheet" href="{{ asset('css/correction-request-list.css') }}" />
@endsection

@section('content')
  <div class="request-list-container">
    <h1 class="page-title">申請状況</h1>

    <div class="tabs">
      <button
        class="tab-button @if ($status === 'pending') tab-button--active @endif"
        data-status="pending"
      >
        承認待ち
      </button>
      <button
        class="tab-button @if ($status === 'approved') tab-button--active @endif"
        data-status="approved"
      >
        承認済み
      </button>
    </div>

    <div class="table-wrapper">
      <table class="request-table">
        <thead>
          <tr>
            <th>申請日</th>
            <th>勤務日</th>
            <th>修正内容</th>
            <th>ステータス</th>
            <th>操作</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($requests as $request)
            <tr>
              <td>{{ $request->created_at->format('Y/m/d') }}</td>
              <td>{{ $request->attendance->date->format('Y/m/d') }}</td>
              <td>
                <ul class="modification-list">
                  @if ($request->requested_clock_in_time)
                    <li>出勤: {{ $request->requested_clock_in_time->format('H:i') }}</li>
                  @endif
                  @if ($request->requested_clock_out_time)
                    <li>退勤: {{ $request->requested_clock_out_time->format('H:i') }}</li>
                  @endif
                  @if ($request->requested_break_minutes)
                    <li>休憩: {{ $request->requested_break_minutes }}分</li>
                  @endif
                </ul>
              </td>
              <td>
                <span class="status-badge status-{{ $request->status }}">
                  {{ $request->status === 'pending' ? '承認待ち' : '承認済み' }}
                </span>
              </td>
              <td>
                <a href="{{ route('correction-request.show', $request->id) }}" class="btn-link">
                  詳細
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="table-empty">申請がありません</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="page-actions">
      <a href="{{ route('attendance.index') }}" class="btn btn-secondary">戻る</a>
    </div>
  </div>

  <script>
    document.querySelectorAll('.tab-button').forEach((button) => {
      button.addEventListener('click', function () {
        const status = this.dataset.status;
        window.location.href = `{{ route('correction-request.list') }}?status=${status}`;
      });
    });
  </script>
@endsection
