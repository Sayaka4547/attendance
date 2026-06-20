@extends('layouts.app')

@section('title', '申請状況 - COACHTECH 勤怠管理システム')

@section('css')
  <link rel="stylesheet" href="{{ asset('css/correction-request-list.css') }}" />
@endsection

@section('content')
  <div class="request-list-container">
    <h1 class="list-title">申請一覧</h1>

    <div class="tabs">
      <button class="tab-button tab-button--active" id="tab-pending" onclick="switchTab('pending')">
        承認待ち
      </button>
      <button class="tab-button" id="tab-approved" onclick="switchTab('approved')">
        承認済み
      </button>
    </div>

    {{-- 承認待ちテーブル --}}
    <div class="table-wrapper" id="panel-pending">
      <table class="request-table">
        <thead>
          <tr>
            <th>状態</th>
            <th>名前</th>
            <th>対象日時</th>
            <th>申請理由</th>
            <th>申請日時</th>
            <th>詳細</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($pendingRequests as $req)
            <tr>
              <td>承認待ち</td>
              <td>{{ Auth::user()->name }}</td>
              <td>{{ $req->attendance->date->format('Y/m/d') }}</td>
              <td>{{ $req->requested_remarks ?? '-' }}</td>
              <td>{{ $req->created_at->format('Y/m/d') }}</td>
              <td>
                <a href="{{ route('attendance.detail', $req->attendance_id) }}" class="detail-link">詳細</a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="table-empty">承認待ちの申請はありません</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- 承認済みテーブル --}}
    <div class="table-wrapper" id="panel-approved" style="display: none;">
      <table class="request-table">
        <thead>
          <tr>
            <th>状態</th>
            <th>名前</th>
            <th>対象日時</th>
            <th>申請理由</th>
            <th>申請日時</th>
            <th>詳細</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($approvedRequests as $req)
            <tr>
              <td>承認済み</td>
              <td>{{ Auth::user()->name }}</td>
              <td>{{ $req->attendance->date->format('Y/m/d') }}</td>
              <td>{{ $req->requested_remarks ?? '-' }}</td>
              <td>{{ $req->created_at->format('Y/m/d') }}</td>
              <td>
                <a href="{{ route('attendance.detail', $req->attendance_id) }}" class="detail-link">詳細</a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="table-empty">承認済みの申請はありません</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <script>
    function switchTab(status) {
      document.getElementById('panel-pending').style.display  = status === 'pending'  ? '' : 'none';
      document.getElementById('panel-approved').style.display = status === 'approved' ? '' : 'none';
      document.getElementById('tab-pending').classList.toggle('tab-button--active',  status === 'pending');
      document.getElementById('tab-approved').classList.toggle('tab-button--active', status === 'approved');
    }
  </script>
@endsection
