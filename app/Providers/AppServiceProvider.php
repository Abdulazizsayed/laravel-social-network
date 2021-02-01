<?php

namespace App\Providers;

use App\Comment;
use App\Like;
use App\Observers\CommentObserver;
use App\Observers\LikeObserver;
use App\Observers\ReplyObserver;
use App\Reply;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Comment::observe(CommentObserver::class);
        Like::observe(LikeObserver::class);
        Reply::observe(ReplyObserver::class);
        view()->composer(["home", 'users.profile', 'users.editProfile', 'users.messages'], "App\Http\ViewComposers\NavbarViewComposer");
    }
}
