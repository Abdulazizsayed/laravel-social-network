<?php

namespace App\Observers;

use App\Comment;
use App\Like;
use App\Notification;
use App\Post;
use App\Reply;

class LikeObserver
{
    /**
     * Handle the like "created" event.
     *
     * @param  \App\Like  $like
     * @return void
     */
    public function created(Like $like)
    {
        if ($like->user->id != auth()->user()->id) {
            if ($like->likeable_type == 'App\Post') {
                $poster = Post::findOrFail($like->likable_id);
                Notification::create([
                    'content' => auth()->user()->name . ' liked your post',
                    'from_id' => auth()->user()->id,
                    'to_id' => $poster->id
                ]);
            } elseif ($like->likeable_type == 'App\Comment') {
                $commenter = Comment::findOrFail($like->likable_id);
                Notification::create([
                    'content' => auth()->user()->name . ' liked your comment',
                    'from_id' => auth()->user()->id,
                    'to_id' => $commenter->id
                ]);
            } else {
                $replier = Reply::findOrFail($like->likable_id);
                Notification::create([
                    'content' => auth()->user()->name . ' liked your reply',
                    'from_id' => auth()->user()->id,
                    'to_id' => $replier->id
                ]);
            }
        }
    }

    /**
     * Handle the like "updated" event.
     *
     * @param  \App\Like  $like
     * @return void
     */
    public function updated(Like $like)
    {
        //
    }

    /**
     * Handle the like "deleted" event.
     *
     * @param  \App\Like  $like
     * @return void
     */
    public function deleted(Like $like)
    {
        //
    }

    /**
     * Handle the like "restored" event.
     *
     * @param  \App\Like  $like
     * @return void
     */
    public function restored(Like $like)
    {
        //
    }

    /**
     * Handle the like "force deleted" event.
     *
     * @param  \App\Like  $like
     * @return void
     */
    public function forceDeleted(Like $like)
    {
        //
    }
}
