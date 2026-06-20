<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CorrectionRequest;
use App\Models\Attendance;
use App\Models\BreakTime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AdminCorrectionRequestController extends Controller
{
    // 申請一覧画面（管理者）
    public function index(Request $request)
    {
        $date = $request->query('date', Carbon::today()->format('Y-m-d'));
        $currentDate = Carbon::parse($date);
        $status = $request->query('status', 'pending');

        // 承認待ち（全ユーザー分）
        $pendingRequests = CorrectionRequest::with(['user', 'attendance'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        // 承認済み（全ユーザー分）
        $approvedRequests = CorrectionRequest::with(['user', 'attendance'])
            ->where('status', 'approved')
            ->latest()
            ->get();
        
        // 画面では承認待ちと承認済みをタブで切り替えて表示する想定
        $requests = $status === 'approved' ? $approvedRequests : $pendingRequests;

        return view('admin.correction-request.list', compact('requests', 'status', 'pendingRequests', 'approvedRequests'));
    }

    // 修正申請承認画面`
    public function approve($attendance_correct_request_id)
    {
        $correctionRequest = CorrectionRequest::with([
            'attendance',
            'user',
            'correctionRequestBreaks',
        ])->findOrFail($attendance_correct_request_id);

        return view('admin.correction-request.approve', compact('correctionRequest'));
    }

    // 承認
    public function store($attendance_correct_request_id)
    {
        $correctionRequest = CorrectionRequest::with([
            'attendance',
            'correctionRequestBreaks',
        ])->findOrFail($attendance_correct_request_id);

        $attendance = $correctionRequest->attendance;

        // 勤怠本体に申請内容を反映
        $attendance->update([
            'clock_in_time'  => $correctionRequest->requested_clock_in_time,
            'clock_out_time' => $correctionRequest->requested_clock_out_time,
            'remarks'        => $correctionRequest->requested_remarks,
        ]);

        // 休憩レコードを申請内容で上書き
        $attendance->breaks()->delete();

        foreach ($correctionRequest->correctionRequestBreaks as $break) {
            BreakTime::create([
                'attendance_id' => $attendance->id,
                'start_time'    => $break->start_time,
                'end_time'      => $break->end_time,
            ]);
        }

        // 申請ステータスを承認済みに更新
        $correctionRequest->update([
            'status'      => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('admin.correction-request.index');
    }
}
