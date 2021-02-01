<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['content', 'post_id', 'user_id'];

    /* Relationships */

    public function post()
    {
        return $this->belongsTo('App\Post');
    }

    public function replies()
    {
        return $this->hasMany('App\Reply');
    }

    public function image()
    {
        return $this->morphOne('App\Image', 'imageable');
    }

    public function complains()
    {
        return $this->morphMany('App\Complain', 'complainable');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function likes()
    {
        return $this->morphMany('App\Like', 'likeable');
    }
}
