<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = ['file_name', 'imageable_id', 'imageable_type'];
    protected $table = 'imageable';

    /* Relationships */

    public function imageable()
    {
        return $this->morphTo('App\Image');
    }
}
