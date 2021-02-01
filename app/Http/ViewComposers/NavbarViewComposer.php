<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;

class NavbarViewComposer
{
    public function compose(View $view)
    {
        $notifications = auth()->user()->notifications()->orderBy('created_at', 'desc')->paginate(15);
        $messages = auth()->user()->received()->orderBy('created_at', 'desc')->paginate(15);

        $view->with([
            'notSeenNotifications' => auth()->user()->notifications->where('seen', 0)->count(),
            'notSeenMessages' => auth()->user()->received->where('seen', 0)->count(),
            'notifications' => $notifications,
            'lastReceivedMessages' => $messages,
        ]);
    }
}
