<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\MessagesNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class MessagesNotificationsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): Response
    {
        MessagesNotification::create($request->toArray());

        $receiver = User::find($request->user_id);
        $sender = User::find($request->from);

        broadcast(new MessageSent($receiver, $sender, $request->message));

        return response()->noContent();
    }

    /**
     * Get the messages for the user along with messages count.
     */
    public function getUnreadMessages(Request $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        return response()->json($user);
    }

    /**
     * Send a test message (unauthenticated endpoint for testing)
     */
    public function sendTestMessage(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'from' => 'required|exists:users,id|different:user_id',
            'message' => 'required|string|min:1|max:500',
        ]);
        $receiver = User::findOrFail($validated['user_id']);
        $sender = User::findOrFail($validated['from']);

        broadcast(new MessageSent($receiver, $sender, $validated['message']));

        return response()->json([
            'message' => 'Test message sent successfully',
        ]);
    }
}
