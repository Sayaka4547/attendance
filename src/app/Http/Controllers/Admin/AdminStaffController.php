<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminStaffController extends Controller
{
    // スタッフ一覧画面
    public function index()
    {
        // 一般ユーザーのみ取得
        $users = User::where('role', 'user')->get();

        return view('admin.staff.list', compact('users'));
    }

    // スタッフ別月次勤怠一覧画面（PG11）
    public function attendance(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // クエリパラメータで月を切り替え（デフォルトは今月）
        $month = $request->query('month', Carbon::now()->format('Y-m'));
        $currentMonth = Carbon::parse($month);

        $attendances = Attendance::where('user_id', $user->id)
            ->whereYear('date', $currentMonth->year)
            ->whereMonth('date', $currentMonth->month)
            ->orderBy('date')
            ->get();

        return view('admin.staff.attendance', compact('user', 'attendances', 'currentMonth'));
    }

}
