<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\DmConversation;
use App\Models\DmParticipant;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DirectMessageController extends Controller
{
    public function index(Request $request): View
    {
        return view('dms.index');
    }

    public function show(Request $request, DmConversation $conversation): View
    {
        $this->authorize('view', $conversation);

        $conversation->load([
            'dmParticipants:dm_participant_id,conversation_id,user_id',
            'dmParticipants.user:user_id,username,name',
        ]);

        return view('dms.show', compact('conversation'));
    }

    public function start(Request $request, User $user): RedirectResponse
    {
        $authUserId = $request->user()->user_id;
        $targetUserId = $user->user_id;

        // Find existing conversation between these two users
        $conversation = DmConversation::query()
            ->join('dm_participant as auth_participant', 'dm_conversation.conversation_id', '=', 'auth_participant.conversation_id')
            ->join('dm_participant as target_participant', 'dm_conversation.conversation_id', '=', 'target_participant.conversation_id')
            ->where('auth_participant.user_id', $authUserId)
            ->where('target_participant.user_id', $targetUserId)
            ->select('dm_conversation.*')
            ->withCount('dmParticipants')
            ->having('dm_participants_count', '=', 2)
            ->first();

        if (!$conversation) {
            $conversation = DmConversation::create([]);
            DmParticipant::create(['conversation_id' => $conversation->conversation_id, 'user_id' => $authUserId]);
            DmParticipant::create(['conversation_id' => $conversation->conversation_id, 'user_id' => $targetUserId]);
        }

        return redirect()->route('dms.show', $conversation);
    }
}
