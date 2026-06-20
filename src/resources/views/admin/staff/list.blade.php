@extends('layouts.admin')

@section('title', 'スタッフ一覧 - COACHTECH 勤怠管理システム')

@section('css')
  <link rel="stylesheet" href="{{ asset('css/admin-staff.css') }}" />
@endsection

@section('content')
  <div class="admin-staff-container">
    <h1 class="list-title">スタッフ一覧</h1>

    <div class="table-wrapper">
      <table class="data-table">
        <thead>
          <tr>
            <th>名前</th>
            <th>メールアドレス</th>
            <th>月次勤怠</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($staffs as $staff)
            <tr>
              <td>{{ $staff->name }}</td>
              <td>{{ $staff->email }}</td>
              <td>
                <a href="{{ route('admin.staff.attendance', $staff->id) }}" class="detail-link">詳細</a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="3" class="table-empty">スタッフが登録されていません</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection