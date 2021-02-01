<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Complain extends Model
{
    protected $fillable = ['complainable_id', 'complainable_type', 'content', 'solved'];
    protected $table = 'complainable';

    /* Relationships */

    public function complainable()
    {
        return $this->morphTo('App\Complain');
    }

    public function image()
    {
        return $this->morphOne('App\Image', 'imageable');
    }
}
