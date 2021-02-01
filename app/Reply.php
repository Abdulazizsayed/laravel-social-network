<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    protected $fillable = ['content', 'comment_id', 'user_id'];

    /* Relationships */

    public function comment()
    {
        return $this->belongsTo('App\Comment');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function image()
    {
        return $this->morphOne('App\Image', 'imageable');
    }

    public function likes()
    {
        return $this->morphMany('App\Like', 'likeable');
    }
}
