<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['content', 'from_id', 'to_id'];

    /* Relationships */

    public function from()
    {
        return $this->belongsTo('App\User', 'from_id');
    }

    public function to()
    {
        return $this->belongsTo('App\User', 'to_id');
    }
}
