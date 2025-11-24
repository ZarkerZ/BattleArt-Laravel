<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Challenge;
use App\Models\Comment;
use App\Models\Interpretation;
use App\Models\Rating;

class AdminController extends Controller
{
    // 1. DASHBOARD
    public function dashboard()
    {
        $admin_id = Auth::id();

        // Stats
        $total_users = User::count();
        $total_admins = User::where('user_type', 'admin')->count();
        $total_regular_users = $total_users - $total_admins;

        // Users List
        $users = User::where('user_id', '!=', $admin_id)
                     ->orderBy('joined_date', 'desc')
                     ->select('*')
                     ->get();

        // NEW: Fetch Pending Reports
        $reports = DB::table('reports as r')
            ->join('users as u', 'r.reporter_user_id', '=', 'u.user_id')
            ->select('r.*', 'u.user_userName as reporter_name', 'u.user_profile_pic')
            ->where('r.status', 'pending') // Only show pending reports on dashboard
            ->orderByDesc('r.created_at')
            ->get();

        return view('admin.dashboard', compact('users', 'reports', 'total_users', 'total_admins', 'total_regular_users'));
    }

    // 2. MANAGE USER DETAILS
    public function showUser($id)
    {
        $user = User::findOrFail($id);
        $admin_user_id = Auth::id();

        // Counts
        $artwork_count = Challenge::where('user_id', $id)->count();
        $interpretation_count = Interpretation::where('user_id', $id)->count();
        $comment_count = Comment::where('user_id', $id)->count();

        $challenge_likes = DB::table('likes')->where('user_id', $id)->count();
        $interp_likes = DB::table('interpretation_likes')->where('user_id', $id)->count();
        $like_count = $challenge_likes + $interp_likes;

        // Recent Activity
        $activities = DB::select("
            (SELECT 'artwork' as type, challenge_name as title, created_at as date FROM challenges WHERE user_id = ?)
            UNION
            (SELECT 'interpretation' as type, description as title, created_at as date FROM interpretations WHERE user_id = ?)
            UNION
            (SELECT 'comment' as type, comment_text as title, created_at as date FROM comments WHERE user_id = ?)
            ORDER BY date DESC
            LIMIT 10
        ", [$id, $id, $id]);

        return view('admin.users.show', compact('user', 'artwork_count', 'interpretation_count', 'comment_count', 'like_count', 'activities', 'admin_user_id'));
    }

    // 3. UPDATE USER STATUS (Ban/Unban/Archive)
    public function updateUserStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $action = $request->input('action');

        // Prevent altering your own account
        if ($user->user_id == Auth::id()) {
            return back()->with('error', 'You cannot alter your own account status.');
        }

        if ($action === 'ban') {
            $user->account_status = 'banned';
            $user->save();
            return back()->with('success', 'User has been banned.');
        }
        elseif ($action === 'unban' || $action === 'restore') {
            $user->account_status = 'active';
            $user->save();
            return back()->with('success', 'User account has been restored.');
        }
        elseif ($action === 'archive') {
            // LOGIC CHANGED: Update status instead of delete
            $user->account_status = 'archived';
            $user->save();

            // Optional: You might want to redirect to dashboard or stay on page
            return back()->with('success', 'User has been archived successfully.');
        }

        return back();
    }

    // 4. MANAGE ARTWORKS
    // 4. MANAGE ARTWORKS (Merged Page)
    public function manageArt()
    {
        // 1. Fetch Challenges
        $challenges = DB::table('challenges as c')
            ->join('users as u', 'c.user_id', '=', 'u.user_id')
            ->select(
                'c.*',
                'u.user_userName',
                'u.user_profile_pic',
                DB::raw('(SELECT COUNT(*) FROM likes WHERE challenge_id = c.challenge_id) as like_count'),
                DB::raw('(SELECT COUNT(*) FROM interpretations WHERE challenge_id = c.challenge_id) as interpretation_count')
            )
            ->orderByDesc('c.created_at')
            ->get();

        // 2. Fetch Interpretations (Added c.category)
        $interpretations = DB::table('interpretations as i')
            ->join('users as u', 'i.user_id', '=', 'u.user_id')
            ->join('challenges as c', 'i.challenge_id', '=', 'c.challenge_id')
            ->select(
                'i.*',
                'u.user_userName',
                'u.user_profile_pic',
                'c.challenge_name',
                'c.category', // <--- ADDED THIS so filtering works
                'c.original_art_filename as parent_art',
                DB::raw('(SELECT COUNT(*) FROM interpretation_likes WHERE interpretation_id = i.interpretation_id) as like_count')
            )
            ->orderByDesc('i.created_at')
            ->get();

        return view('admin.art.index', compact('challenges', 'interpretations'));
    }

    public function archiveArt(Request $request)
    {
        $challenge = Challenge::findOrFail($request->art_id);
        $challenge->status = ($challenge->status === 'active') ? 'archived' : 'active';
        $challenge->save();
        return back()->with('success', $challenge->status === 'archived' ? 'Challenge archived.' : 'Challenge restored.');
    }
    public function archiveInterpretation(Request $request)
    {
        $interp = Interpretation::findOrFail($request->interpretation_id);
        $interp->status = ($interp->status === 'active') ? 'archived' : 'active';
        $interp->save();
        return back()->with('success', $interp->status === 'archived' ? 'Interpretation archived.' : 'Interpretation restored.');
    }
    // 5. MANAGE COMMENTS
    public function manageComments()
    {
        $comments = DB::table('comments as cm')
            ->join('users as u', 'cm.user_id', '=', 'u.user_id')
            ->join('challenges as c', 'cm.challenge_id', '=', 'c.challenge_id')
            ->select('cm.*', 'u.user_userName', 'c.challenge_name')
            ->orderByDesc('cm.created_at')
            ->get();

        return view('admin.comments.index', compact('comments'));
    }

    public function archiveComment(Request $request)
    {
        $comment = Comment::findOrFail($request->comment_id);

        // Toggle status
        $comment->status = ($comment->status === 'active') ? 'archived' : 'active';
        $comment->save();

        $message = $comment->status === 'archived' ? 'Comment archived.' : 'Comment restored.';
        return back()->with('success', $message);
    }

    // 6. ADMIN PROFILE
    public function profile()
    {
        $admin_id = Auth::id();
        $user = User::findOrFail($admin_id);

        $platform_stats = [
            'total_users' => User::count(),
            'total_artworks' => Challenge::count(),
            'total_comments' => Comment::count()
        ];

        $challenges_count = Challenge::where('user_id', $admin_id)->count();
        $interpretations_count = Interpretation::where('user_id', $admin_id)->count();
        $total_art_made = $challenges_count + $interpretations_count;

        $user_challenges = Challenge::where('user_id', $admin_id)->orderByDesc('created_at')->get();

        $user_history = DB::select("
            (SELECT 'created_challenge' as event_type, challenge_name as event_title, NULL as event_content, challenge_id, created_at as event_date FROM challenges WHERE user_id = ?)
            UNION
            (SELECT 'posted_comment', ch.challenge_name, co.comment_text, co.challenge_id, co.created_at FROM comments co JOIN challenges ch ON co.challenge_id = ch.challenge_id WHERE co.user_id = ?)
            UNION
            (SELECT 'created_interpretation', ch.challenge_name, i.description, i.challenge_id, i.created_at FROM interpretations i JOIN challenges ch ON i.challenge_id = ch.challenge_id WHERE i.user_id = ?)
            UNION
            (SELECT 'liked_challenge', ch.challenge_name, NULL, l.challenge_id, l.created_at FROM likes l JOIN challenges ch ON l.challenge_id = ch.challenge_id WHERE l.user_id = ?)
            UNION
            (SELECT 'liked_interpretation', u.user_userName, ch.challenge_name, ch.challenge_id, il.created_at FROM interpretation_likes il JOIN interpretations i ON il.interpretation_id = i.interpretation_id JOIN users u ON i.user_id = u.user_id JOIN challenges ch ON i.challenge_id = ch.challenge_id WHERE il.user_id = ?)
            ORDER BY event_date DESC LIMIT 10
        ", [$admin_id, $admin_id, $admin_id, $admin_id, $admin_id]);

        $comments_on_art = DB::table('comments as co')
            ->join('users as u', 'co.user_id', '=', 'u.user_id')
            ->join('challenges as ch', 'co.challenge_id', '=', 'ch.challenge_id')
            ->where('ch.user_id', $admin_id)
            ->where('co.user_id', '!=', $admin_id)
            ->select('co.*', 'u.user_userName', 'u.user_profile_pic', 'ch.challenge_name')
            ->orderByDesc('co.created_at')
            ->limit(10)
            ->get();

        $avg_rating = Rating::where('rated_user_id', $admin_id)->avg('rating_value') ?? 0;
        $rating_count = Rating::where('rated_user_id', $admin_id)->count();

        return view('admin.profile', compact(
            'user', 'platform_stats', 'challenges_count', 'interpretations_count',
            'total_art_made', 'user_challenges', 'user_history', 'comments_on_art',
            'avg_rating', 'rating_count'
        ));
    }

    // 7. ADMIN CREATE/EDIT (Stubs for completeness)
    public function create() { return view('admin.create'); }
    public function edit() { $user = Auth::user(); return view('admin.edit', compact('user')); }

    public function store(Request $request) {
        $request->validate([ 'userName' => 'required|unique:users,user_userName', 'email' => 'required|email|unique:users,user_email', 'password' => 'required|min:6' ]);
        User::create([ 'user_userName' => $request->userName, 'user_email' => $request->email, 'user_password' => Hash::make($request->password), 'user_type' => 'admin', 'user_firstName' => $request->firstName ?? 'Admin', 'user_lastName' => $request->lastName ?? 'User' ]);
        return redirect()->route('login')->with('success', 'Admin created');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // 1. Handle CROPPED Profile Picture (Base64)
        if ($request->filled('croppedImage')) {
            $data = $request->input('croppedImage');
            if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
                $data = substr($data, strpos($data, ',') + 1);
                $type = strtolower($type[1]);
                $data = base64_decode($data);

                if ($data) {
                    $filename = uniqid('profile_', true) . '.' . $type;
                    $path = public_path('assets/uploads/' . $filename);

                    // Ensure directory exists
                    if (!file_exists(public_path('assets/uploads'))) {
                        mkdir(public_path('assets/uploads'), 0755, true);
                    }

                    file_put_contents($path, $data);

                    // Delete old image
                    if ($user->user_profile_pic && $user->user_profile_pic !== 'default_avatar.png') {
                        $oldPath = public_path('assets/uploads/' . $user->user_profile_pic);
                        if (file_exists($oldPath)) @unlink($oldPath);
                    }

                    $user->user_profile_pic = $filename;
                }
            }
        }

        // 2. Handle CROPPED Banner Picture (Base64)
        if ($request->filled('croppedBanner')) {
            $data = $request->input('croppedBanner');
            if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
                $data = substr($data, strpos($data, ',') + 1);
                $type = strtolower($type[1]);
                $data = base64_decode($data);

                if ($data) {
                    $filename = uniqid('banner_', true) . '.' . $type;
                    $path = public_path('assets/uploads/' . $filename);

                    if (!file_exists(public_path('assets/uploads'))) {
                        mkdir(public_path('assets/uploads'), 0755, true);
                    }

                    file_put_contents($path, $data);
                    $user->user_banner_pic = $filename;
                }
            }
        }

        // 3. Update Text Fields
        // Note: The fancy view uses 'fullName' for the username input
        $user->user_userName = $request->fullName;
        $user->user_email = $request->user_email; // Ensure your view has this input or remove this line
        $user->user_bio = $request->userBio;

        // 4. Update Password if provided
        if ($request->filled('user_password')) {
            $user->user_password = Hash::make($request->user_password);
        }

        // 5. Update Toggles (Optional for admin, but good to have)
        $user->show_art = $request->has('toggleArt') ? 1 : 0;
        $user->show_history = $request->has('toggleHistory') ? 1 : 0;
        $user->show_comments = $request->has('toggleComments') ? 1 : 0;

        $user->save();



        return redirect()->route('admin.profile')->with('success', 'Profile updated successfully!');
    }
    public function manageReports()
    {
        // Fetch reports with the reporter's username
        $reports = DB::table('reports as r')
            ->join('users as u', 'r.reporter_user_id', '=', 'u.user_id')
            ->select('r.*', 'u.user_userName as reporter_name', 'u.user_profile_pic')
            ->orderByRaw("FIELD(r.status, 'pending', 'resolved', 'dismissed')") // Pending first
            ->orderByDesc('r.created_at')
            ->get();

        return view('admin.reports.index', compact('reports'));
    }

    public function updateReportStatus(Request $request)
    {
        $request->validate([
            'report_id' => 'required|integer',
            'status' => 'required|in:resolved,dismissed'
        ]);

        DB::table('reports')
            ->where('report_id', $request->report_id)
            ->update(['status' => $request->status]);

        return back()->with('success', 'Report marked as ' . $request->status);
    }
}
