<?php

namespace App\Http\Controllers;

use App\Models\CorrectionRequest;
use Illuminate\Support\Facades\Auth;

class CorrectionRequestController extends Controller
{
    // 申請一覧画面
    public function index()
    {
        $user = Auth::user();

        // 承認待ち
        $pendingRequests = CorrectionRequest::with('attendance')
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->latest()
            ->get();

        // 承認済み
        $approvedRequests = CorrectionRequest::with('attendance')
            ->where('user_id', $user->id)
            ->where('status', 'approved')
            ->latest()
            ->get();

        return view('correction-request.list', compact('pendingRequests', 'approvedRequests'));
    }

}
