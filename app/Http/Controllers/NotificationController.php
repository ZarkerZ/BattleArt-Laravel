<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $user_id = Auth::id();
        
        $notifications = DB::table('notifications as n')
            ->join('users as sender', 'n.sender_user_id', '=', 'sender.user_id')
            ->leftJoin('challenges as c', function($join) {
                $join->on('c.challenge_id', '=', 'n.target_id')
                     ->orOn('c.challenge_id', '=', 'n.target_parent_id');
            })
            ->where('n.recipient_user_id', $user_id)
            ->select('n.*', 'sender.user_userName as sender_name', 'c.challenge_name')
            ->orderBy('n.created_at', 'desc')
            ->get();

        return view('notifications.index', compact('notifications'));
    }

    public function markRead(Request $request, $id = null)
    {
        $user_id = Auth::id();

        if ($id) {
            // Mark single
            Notification::where('notification_id', $id)
                ->where('recipient_user_id', $user_id)
                ->update(['is_read' => 1]);
                
            if ($request->has('destination')) {
                return redirect($request->get('destination'));
            }
        } else {
            // Mark all
            Notification::where('recipient_user_id', $user_id)
                ->where('is_read', 0)
                ->update(['is_read' => 1]);
        }

        return redirect()->route('notifications.index');
    }

    public function delete(Request $request){
        $user_id = Auth::id();
        $action = $request->input('action');
        if ($action === 'all') {
        // Delete ALL
        DB::table('notifications')->where('recipient_user_id', $user_id)->delete();
    } 
    elseif ($action === 'single' && $request->has('notification_id')) {
        // Delete SINGLE (Security: Ensure it belongs to the user)
        DB::table('notifications')
            ->where('notification_id', $request->input('notification_id'))
            ->where('recipient_user_id', $user_id)
            ->delete();
    }
    return redirect()->route('notifications.index');
}
}