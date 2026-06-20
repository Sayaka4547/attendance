@extends('layouts.admin')

@section('title', '申請一覧 - COACHTECH 勤怠管理システム')

@section('css')
  <link rel="stylesheet" href="{{ asset('css/admin-correction-request.css') }}" />
@endsection

@section('content')
  <div class="admin-request-container">
    <h1 class="list-title">申請一覧</h1>

    <div class="tab-wrapper">
        <button class="tab-btn {{ $status === 'pending' ? 'active' : '' }}"
        onclick="location.href='{{ route('admin.correction-request.index', ['status' => 'pending']) }}'">
        承認待ち
      </button>
      <button class="tab-btn {{ $status === 'approved' ? 'active' : '' }}"
        onclick="location.href='{{ route('admin.correction-request.index', ['status' => 'approved']) }}'">
        承認済み 
      </button>
    </div>

    <div class="table-wrapper">
      <table class="data-table">
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
          @forelse ($requests as $request)
            <tr>
              <td>{{ $request->status === 'pending' ? '承認待ち' : '承認済み' }}</td>
              <td>{{ $request->attendance->user->name }}</td>
              <td>{{ $request->attendance->date->format('Y/m/d') }}</td>
              <td>{{ $request->reason }}</td>
              <td>{{ $request->created_at->format('Y/m/d') }}</td>
              <td>
                <a href="{{ route('admin.correction-request.approve', $request->id) }}" class="detail-link">詳細</a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="table-empty">申請はありません</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection
