<?php

namespace App\Observers;

use App\Events\MessageSent;
use App\Message;
use App\Notification;

class MessageObserver
{
    /**
     * Handle the message "created" event.
     *
     * @param  \App\Message  $message
     * @return void
     */
    public function created(Message $message)
    {
        $image = $message->image ? $message->image->file_name : '';
        event(new MessageSent([
            'message_id' => $message->id,
            'content' => $message->content,
            'image' => $image,
            'sender_id' => auth()->user()->id,
            'receiver_id' => $message->receiver_id,
        ]));

        Notification::create([
            'content' => auth()->user()->name . ' send you a message',
            'from_id' => auth()->user()->id,
            'to_id' => $message->receiver->id,
            'link' => 'http://127.0.0.1:8000/users/messages' . $message->sender->id
        ]);
    }

    /**
     * Handle the message "updated" event.
     *
     * @param  \App\Message  $message
     * @return void
     */
    public function updated(Message $message)
    {
        //
    }

    /**
     * Handle the message "deleted" event.
     *
     * @param  \App\Message  $message
     * @return void
     */
    public function deleted(Message $message)
    {
        //
    }

    /**
     * Handle the message "restored" event.
     *
     * @param  \App\Message  $message
     * @return void
     */
    public function restored(Message $message)
    {
        //
    }

    /**
     * Handle the message "force deleted" event.
     *
     * @param  \App\Message  $message
     * @return void
     */
    public function forceDeleted(Message $message)
    {
        //
    }
}
