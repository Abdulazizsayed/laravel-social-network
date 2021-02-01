<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['content', 'sender_id', 'receiver_id', 'seen'];

    /* Relationships */

    public function sender()
    {
        return $this->belongsTo('App\User', 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo('App\User', 'receiver_id');
    }

    public function image()
    {
        return $this->morphOne('App\Image', 'imageable');
    }
}
