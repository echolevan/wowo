<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    protected $guarded = [];

    public function plug()
    {
        return $this->belongsTo(Plug::class);
    }
}
