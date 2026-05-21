<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SmsLog;
use App\Services\SmsService;
use Illuminate\Http\Request;

class SmsController extends Controller
{
    public function index()
    {
        $logs = SmsLog::with('expediteur')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return inertia('Admin/Sms/Index', ['logs' => $logs]);
    }

    public function envoyer(Request $request, SmsService $sms)
    {
        $request->validate([
            'message' => 'required|string|max:160',
            'type'    => 'required|in:reunion,cotisation,autre',
            'reunion_id' => 'nullable|exists:reunions,id',
        ]);

        $result = $sms->envoyerATous(
            $request->message,
            $request->type,
            $request->reunion_id
        );

        return back()->with('sms_result', $result);
    }
}
