<?php

namespace App\Http\Controllers;

use App\Models\AdminMessage;
use App\Models\User;
use Illuminate\Http\Request;

class AdminMessageController extends Controller
{
    public function index(Request $request)
    {
        $this->ensurePermission($request, 'admins.message');

        $messages = AdminMessage::with(['sender', 'recipient'])
            ->where(function ($query) use ($request) {
                $query->where('sender_id', $request->user()->id)
                    ->orWhere('recipient_id', $request->user()->id)
                    ->orWhereNull('recipient_id');
            })
            ->latest()
            ->paginate(12);

        $admins = User::with('role')
            ->whereHas('role', fn ($query) => $query->whereIn('nom', ['Super Administrateur', 'Administrateur', 'Gerant']))
            ->orderBy('name')
            ->get();

        return view('store.admin.messages.index', compact('messages', 'admins'));
    }

    public function store(Request $request)
    {
        $this->ensurePermission($request, 'admins.message');

        $data = $request->validate([
            'recipient_id' => ['nullable', 'exists:users,id'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'min:5'],
        ]);

        AdminMessage::create([
            'sender_id' => $request->user()->id,
            'recipient_id' => $data['recipient_id'] ?? null,
            'subject' => $data['subject'],
            'message' => $data['message'],
        ]);

        $this->logAdminActivity($request, 'Message administrateur', 'user', $data['recipient_id'] ?? null, $data['subject']);

        return back()->with('success', 'Message envoye.');
    }
}
