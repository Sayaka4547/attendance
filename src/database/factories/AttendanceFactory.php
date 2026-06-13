<?php

namespace Database\Factories;

use App\Models\Attendance;
use App\Models\BreakTime;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class AttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = Attendance::class;

    public function definition()
    {
        $clockIn  = Carbon::today()->subDays(rand(1, 30))->setHour(rand(8, 10))->setMinute(rand(0, 59));
        $clockOut = (clone $clockIn)->addHours(rand(7, 9))->addMinutes(rand(0, 59));

        return [
            'user_id'        => 1,
            'date'           => $clockIn->toDateString(),
            'clock_in_time'  => $clockIn,
            'clock_out_time' => $clockOut,
            'break_minutes'  => 60,
            'status'         => 'off_work',
            'remarks'        => null,
        ];
    }
}
