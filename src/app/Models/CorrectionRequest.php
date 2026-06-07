<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorrectionRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_id',
        'user_id',
        'status',
        'requested_clock_in_time',
        'requested_clock_out_time',
        'requested_break_minutes',
        'requested_remarks',
        'remarks',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'requested_clock_in_time'  => 'datetime',
        'requested_clock_out_time' => 'datetime',
        'approved_at'              => 'datetime',
    ];

    // リレーション
    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function correctionRequestBreaks()
    {
        return $this->hasMany(CorrectionRequestBreak::class);
    }

    // 承認待ちかどうかを判定
    public function isPending()
    {
        return $this->status === 'pending';
    }

}
