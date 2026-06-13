<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\BreakTime;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::where('role', 'user')->get();

        foreach ($users as $user) {
            // 過去30日分のダミー勤怠データを作成
            for ($i = 1; $i <= 30; $i++) {
                $date     = Carbon::today()->subDays($i);
                $clockIn  = (clone $date)->setHour(rand(8, 10))->setMinute(rand(0, 59));
                $clockOut = (clone $clockIn)->addHours(rand(7, 9))->addMinutes(rand(0, 59));

                $attendance = Attendance::create([
                    'user_id'        => $user->id,
                    'date'           => $date->toDateString(),
                    'clock_in_time'  => $clockIn,
                    'clock_out_time' => $clockOut,
                    'break_minutes'  => 60,
                    'status'         => 'off_work',
                    'remarks'        => null,
                ]);

                // 休憩データを1件作成
                BreakTime::create([
                    'attendance_id' => $attendance->id,
                    'start_time'    => (clone $clockIn)->addHours(4),
                    'end_time'      => (clone $clockIn)->addHours(5),
                ]);
            }
        }
    }
}
