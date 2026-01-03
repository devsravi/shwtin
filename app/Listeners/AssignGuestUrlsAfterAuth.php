<?php

namespace App\Listeners;

use App\Jobs\AssignGuestShortUrlsToUser;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Session;

class AssignGuestUrlsAfterAuth
{
    /**
     * Handle the event.
     */
    public function handle(Login|Registered $event): void
    {
        $shortUrlKeys = Session::get('guest_short_url_keys', []);
        if (empty($shortUrlKeys)) {
            return;
        }
        // Assign URLs to user
        AssignGuestShortUrlsToUser::dispatch(
            userId: $event->user->id,
            shortUrlKeys: $shortUrlKeys
        );
        // Clear session after dispatch
        Session::forget('guest_short_url_keys');
    }
}
