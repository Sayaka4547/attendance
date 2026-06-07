<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\BreakTime;
use App\Http\Requests\AdminAttendanceCorrectionRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminAttendanceController extends Controller
{
    // 日次勤怠一覧画面
    public function index(Request $request)
    {
        // クエリパラメータで日付を切り替え（デフォルトは今日）
        $date = $request->query('date', Carbon::today()->toDateString());
        $currentDate = Carbon::parse($date);

        $attendances = Attendance::with('user')
            ->where('date', $currentDate->toDateString())
            ->get();

        return view('admin.attendance.list', compact('attendances', 'currentDate'));
    }

    // 勤怠詳細画面（管理者）
    public function detail($id)
    {
        $attendance = Attendance::with(['user', 'breaks', 'correctionRequests'])
            ->findOrFail($id);

        $hasPending = $attendance->hasPendingRequest();

        return view('admin.attendance.detail', compact('attendance', 'hasPending'));
    }

    // 管理者による勤怠直接修正
    public function update(AdminAttendanceCorrectionRequest $request, $id)
    {
        $attendance = Attendance::findOrFail($id);

        // 勤怠本体を更新
        $attendance->update([
            'clock_in_time'  => $request->clock_in_time,
            'clock_out_time' => $request->clock_out_time,
            'remarks'        => $request->remarks,
        ]);

        // 既存の休憩レコードを削除して再作成
        $attendance->breaks()->delete();

        if ($request->has('breaks')) {
            foreach ($request->breaks as $break) {
                if (!empty($break['start_time'])) {
                    BreakTime::create([
                        'attendance_id' => $attendance->id,
                        'start_time'    => $break['start_time'],
                        'end_time'      => $break['end_time'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('admin.attendance.detail', $id);
    }

}
