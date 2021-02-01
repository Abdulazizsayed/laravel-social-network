<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['content', 'user_id'];

    /* Relationships */

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment');
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
