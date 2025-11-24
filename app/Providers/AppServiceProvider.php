<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // View Composer for the Navbar
        // This runs ONLY when 'partials.navbar' is loaded
        View::composer('partials.navbar', function ($view) {
            $unread_count = 0;

            // Check if user is logged in
            if (Auth::check()) {
                $user = Auth::user();

                // Check if notifications are allowed
                if ($user->allow_notifications) {
                    // Count unread notifications using Query Builder
                    $unread_count = DB::table('notifications')
                        ->where('recipient_user_id', $user->user_id)
                        ->where('is_read', 0)
                        ->count();
                }
            }

            // Pass the variable to the view
            $view->with('unread_count', $unread_count);
        });
    }
}
