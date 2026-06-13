<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\BreakTime;
use App\Models\CorrectionRequest;
use App\Models\CorrectionRequestBreak;
use App\Http\Requests\AttendanceCorrectionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
     // 勤怠打刻画面
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today();

        // 今日の勤怠レコードを取得（なければ null）
        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $today->toDateString())
            ->first();

        return view('attendance.index', compact('attendance', 'today'));
    }

    // 出勤打刻
    public function clockIn(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today();

        // 1日1回のみ（既に出勤レコードがある場合はスキップ）
        $exists = Attendance::where('user_id', $user->id)
            ->where('date', $today->toDateString())
            ->exists();

        if (!$exists) {
            Attendance::create([
                'user_id'       => $user->id,
                'date'          => $today->toDateString(),
                'clock_in_time' => Carbon::now(),
                'break_minutes' => 0,
                'status'        => 'working',
            ]);
        }

        return redirect()->route('attendance.index');
    }

    // 退勤打刻
    public function clockOut(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today();

        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $today->toDateString())
            ->first();

        if ($attendance && $attendance->status === 'working') {
            $attendance->update([
                'clock_out_time' => Carbon::now(),
                'status'         => 'finished',
            ]);
        }

        return redirect()->route('attendance.index');
    }

    // 休憩開始
    public function breakStart(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today();

        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $today->toDateString())
            ->first();

        if ($attendance && $attendance->status === 'working') {
            BreakTime::create([
                'attendance_id' => $attendance->id,
                'start_time'    => Carbon::now(),
            ]);

            $attendance->update(['status' => 'on_break']);
        }

        return redirect()->route('attendance.index');
    }

    // 休憩終了
    public function breakEnd(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today();

        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $today->toDateString())
            ->first();

        if ($attendance && $attendance->status === 'on_break') {
            // 終了時刻が未設定の最新の休憩レコードを更新
            $break = BreakTime::where('attendance_id', $attendance->id)
                ->whereNull('end_time')
                ->latest()
                ->first();

            if ($break) {
                $breakMinutes = Carbon::parse($break->start_time)
                    ->diffInMinutes(Carbon::now());

                $break->update(['end_time' => Carbon::now()]);

                // 累計休憩時間を更新
                $attendance->update([
                    'break_minutes' => $attendance->break_minutes + $breakMinutes,
                    'status'        => 'working',
                ]);
            }
        }

        return redirect()->route('attendance.index');
    }

    // 勤怠一覧画面
    public function list(Request $request)
    {
        $user = Auth::user();

        // クエリパラメータで月を切り替え（デフォルトは今月）
        $month = $request->query('month', Carbon::now()->format('Y-m'));
        $currentMonth = Carbon::parse($month)->startOfMonth();


        // 前月・翌月のY-m文字列を生成
        $previousMonth = $currentMonth->copy()->subMonth()->format('Y-m');
        $nextMonth     = $currentMonth->copy()->addMonth()->format('Y-m');

        $attendances = Attendance::where('user_id', $user->id)
            ->whereYear('date', $currentMonth->year)
            ->whereMonth('date', $currentMonth->month)
            ->orderBy('date')
            ->get();

        return view('attendance.list', compact('attendances', 'currentMonth', 'previousMonth', 'nextMonth'));
    }

    // 勤怠詳細画面
    public function detail($id)
    {
        $attendance = Attendance::with(['breaks', 'correctionRequests'])
            ->findOrFail($id);

        // 承認待ちの申請確認
        $hasPending = $attendance->hasPendingRequest();

        return view('attendance.detail', compact('attendance', 'hasPending'));
    }

    // 修正申請
    public function requestCorrection(AttendanceCorrectionRequest $request, $id)
    {
        $attendance = Attendance::findOrFail($id);

        // 修正申請レコードを作成
        $correctionRequest = CorrectionRequest::create([
            'attendance_id'            => $attendance->id,
            'user_id'                  => Auth::id(),
            'status'                   => 'pending',
            'requested_clock_in_time'  => $request->clock_in_time,
            'requested_clock_out_time' => $request->clock_out_time,
            'requested_remarks'        => $request->remarks,
        ]);

        // 修正申請の休憩レコードを作成
        if ($request->has('breaks')) {
            foreach ($request->breaks as $break) {
                if (!empty($break['start_time'])) {
                    CorrectionRequestBreak::create([
                        'correction_request_id' => $correctionRequest->id,
                        'start_time'            => $break['start_time'],
                        'end_time'              => $break['end_time'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('correction-request.index');
    }
}
