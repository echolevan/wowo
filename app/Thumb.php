<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thumb extends Model
{
    //
    protected $guarded = [];
    protected $appends = ['url'];

    public function getUrlAttribute()
    {
        return $this->url = $this->thumb;
    }

}
