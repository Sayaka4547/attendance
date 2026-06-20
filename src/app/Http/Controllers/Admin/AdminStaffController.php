<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminStaffController extends Controller
{
    // スタッフ一覧
    public function index()
    {
        $staffs = User::where('role', 'user')->orderBy('id')->get();

        return view('admin.staff.list', compact('staffs'));
    }

    // スタッフ別勤怠一覧
    public function attendance(Request $request, $id)
    {
        $staff        = User::findOrFail($id);
        $month        = $request->query('month', Carbon::today()->format('Y-m'));
        $currentMonth = Carbon::parse($month . '-01')->startOfMonth();
        $previousMonth = $currentMonth->copy()->subMonth()->format('Y-m');
        $nextMonth     = $currentMonth->copy()->addMonth()->format('Y-m');

        $attendances = Attendance::where('user_id', $id)
            ->whereYear('date', $currentMonth->year)
            ->whereMonth('date', $currentMonth->month)
            ->orderBy('date')
            ->get();

        return view('admin.staff.attendance', compact(
            'staff',
            'attendances',
            'currentMonth',
            'previousMonth',
            'nextMonth'
        ));
    }

         // CSV出力
    public function csv(Request $request, $id)
    {
        $staff        = User::findOrFail($id);
        $month        = $request->query('month', Carbon::today()->format('Y-m'));
        $currentMonth = Carbon::parse($month . '-01');

        $attendances = Attendance::where('user_id', $id)
            ->whereYear('date', $currentMonth->year)
            ->whereMonth('date', $currentMonth->month)
            ->orderBy('date')
            ->get();

        $filename = $staff->name . '_' . $currentMonth->format('Y-m') . '.csv';

        // CSV文字列を組み立て
    $csvData = "\xEF\xBB\xBF"; // BOM（Excelで文字化けしないように）
    $csvData .= implode(',', ['名前', '日付', '出勤', '退勤', '休憩', '合計']) . "\n";

    foreach ($attendances as $a) {
        $breakTime = $a->break_minutes
            ? sprintf('%d:%02d', intdiv($a->break_minutes, 60), $a->break_minutes % 60)
            : '';

        $row = [
            $staff->name,
            $a->date->format('Y/m/d'),
            $a->clock_in_time?->format('H:i') ?? '',
            $a->clock_out_time?->format('H:i') ?? '',
            $breakTime,
            $a->working_hours ?? '',
        ];

        $csvData .= implode(',', $row) . "\n";
        }

        return response($csvData, 200, [
        'Content-Type'        => 'text/csv; charset=UTF-8',
        'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);

    }

}

