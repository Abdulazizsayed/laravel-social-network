<?php

namespace App\Observers;

use App\Comment;
use App\Notification;
use App\Reply;

class ReplyObserver
{
    /**
     * Handle the reply "created" event.
     *
     * @param  \App\Reply  $reply
     * @return void
     */
    public function created(Reply $reply)
    {
        $commenter = Comment::findOrFail($reply->comment_id)->user;
        if ($commenter->id != auth()->user()->id) {
            Notification::create([
                'content' => auth()->user()->name . ' replied on your comment',
                'from_id' => auth()->user()->id,
                'to_id' => $commenter->id,
                'link' => 'http://127.0.0.1:8000/posts/' . $reply->comment->post->id . '#reply' . $reply->id
            ]);
        }
    }

    /**
     * Handle the reply "updated" event.
     *
     * @param  \App\Reply  $reply
     * @return void
     */
    public function updated(Reply $reply)
    {
        //
    }

    /**
     * Handle the reply "deleted" event.
     *
     * @param  \App\Reply  $reply
     * @return void
     */
    public function deleted(Reply $reply)
    {
        //
    }

    /**
     * Handle the reply "restored" event.
     *
     * @param  \App\Reply  $reply
     * @return void
     */
    public function restored(Reply $reply)
    {
        //
    }

    /**
     * Handle the reply "force deleted" event.
     *
     * @param  \App\Reply  $reply
     * @return void
     */
    public function forceDeleted(Reply $reply)
    {
        //
    }
}
