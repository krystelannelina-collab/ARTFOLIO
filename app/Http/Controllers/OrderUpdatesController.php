<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OrderUpdatesController extends Controller
{
    /**
     * Mark the user's order updates as seen, clearing the bell badge.
     */
    public function markSeen(Request $request): JsonResponse|RedirectResponse
    {
        $request->user()->forceFill(['order_updates_seen_at' => now()])->save();

        if ($request->expectsJson()) {
            return response()->json(['unseen' => 0]);
        }

        return back();
    }
}
