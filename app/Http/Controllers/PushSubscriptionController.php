<?php

namespace App\Http\Controllers;

use App\Models\PushSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PushSubscriptionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'endpoint' => 'required|string',
            'keys.p256dh' => 'required|string',
            'keys.auth' => 'required|string',
            'contentEncoding' => 'nullable|string',
        ]);

        $endpoint = $request->endpoint;

        PushSubscription::updateOrCreate(
            [
                'endpoint_hash' => hash('sha256', $endpoint),
            ],
            [
                'user_id' => Auth::id(),
                'endpoint' => $endpoint,
                'public_key' => $request->input('keys.p256dh'),
                'auth_token' => $request->input('keys.auth'),
                'content_encoding' => $request->input('contentEncoding', 'aes128gcm'),
            ]
        );

        return response()->json([
            'success' => true,
        ]);
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'endpoint' => 'required|string',
        ]);

        PushSubscription::where('user_id', Auth::id())
            ->where('endpoint_hash', hash('sha256', $request->endpoint))
            ->delete();

        return response()->json([
            'success' => true,
        ]);
    }
}