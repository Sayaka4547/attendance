<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'clock_in_time',
        'clock_out_time',
        'break_minutes',
        'status',
        'remarks',
    ];

    protected $casts = [
        'date'           => 'date',
        'clock_in_time'  => 'datetime',
        'clock_out_time' => 'datetime',
    ];

    // リレーション
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function breaks()
    {
        return $this->hasMany(BreakTime::class);
    }

    public function correctionRequests()
    {
        return $this->hasMany(CorrectionRequest::class);
    }

    // 承認待ちの修正申請があるか確認
    public function hasPendingRequest()
    {
        return $this->correctionRequests()
            ->where('status', 'pending')
            ->exists();
    }

    // 勤務時間を「H:i」形式で返す
    public function getWorkingHoursAttribute(): string
    {
    if (!$this->clock_in_time || !$this->clock_out_time) {
        return '';
    }

    $totalMinutes = $this->clock_in_time->diffInMinutes($this->clock_out_time);
    $workMinutes  = $totalMinutes - ($this->break_minutes ?? 0);

    if ($workMinutes <= 0) {
        return '';
    }

    $hours   = intdiv($workMinutes, 60);
    $minutes = $workMinutes % 60;

    return sprintf('%d:%02d', $hours, $minutes);
    }

}
