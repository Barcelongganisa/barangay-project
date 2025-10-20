<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Mail;
// use App\Mail\AccountApproved;
// use App\Mail\AccountDeclined;

class UserApprovalController extends Controller
{
    public function pendingUsers()
    {
        $pendingUsers = User::pending()->latest()->get();
        $approvedUsers = User::approved()->latest()->get();
        $declinedUsers = User::declined()->latest()->get();

        return view('admin.manage-requests', compact('pendingUsers', 'approvedUsers', 'declinedUsers'));
    }

    public function approveUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $user->update([
            'approval_status' => 'approved',
            'approved_at' => now(),
            'decline_reason' => null,
            'declined_at' => null,
        ]);

        // Send approval email
        // Mail::to($user->email)->send(new AccountApproved($user));

        return response()->json([
            'success' => true,
            'message' => 'User approved successfully!'
        ]);
    }

    public function declineUser(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        $user = User::findOrFail($id);
        
        $user->update([
            'approval_status' => 'declined',
            'decline_reason' => $request->reason,
            'declined_at' => now(),
            'approved_at' => null,
        ]);

        // Send decline email
        // Mail::to($user->email)->send(new AccountDeclined($user, $request->reason));

        return response()->json([
            'success' => true,
            'message' => 'User declined successfully!'
        ]);
    }

    public function getUserDetails($id)
    {
        $user = User::findOrFail($id);

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'approval_status' => $user->approval_status,
                'created_at' => $user->created_at,
                'approved_at' => $user->approved_at,
                'declined_at' => $user->declined_at,
                'decline_reason' => $user->decline_reason,
            ]
        ]);
    }
}