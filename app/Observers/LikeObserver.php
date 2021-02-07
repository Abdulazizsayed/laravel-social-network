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
                $post = Post::findOrFail($like->likable_id);
                Notification::create([
                    'content' => auth()->user()->name . ' liked your post',
                    'from_id' => auth()->user()->id,
                    'to_id' => $post->user->id,
                    'link' => 'http://127.0.0.1:8000/posts/' . $post->id
                ]);
            } elseif ($like->likeable_type == 'App\Comment') {
                $comment = Comment::findOrFail($like->likable_id);
                Notification::create([
                    'content' => auth()->user()->name . ' liked your comment',
                    'from_id' => auth()->user()->id,
                    'to_id' => $comment->user->id,
                    'link' => 'http://127.0.0.1:8000/posts/' . $comment->post->id . '#comment' . $comment->id
                ]);
            } else {
                $reply = Reply::findOrFail($like->likable_id);
                Notification::create([
                    'content' => auth()->user()->name . ' liked your reply',
                    'from_id' => auth()->user()->id,
                    'to_id' => $reply->user->id,
                    'link' => 'http://127.0.0.1:8000/posts/' . $reply->comment->post->id . '#reply' . $reply->id
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
