<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    protected $guarded = [];

    public function plug()
    {
        return $this->belongsTo(Plug::class,'plug_only_id','plug_id');
    }

    public function score()
    {
        return $this->hasMany(Score::class,'plug_id','plug_only_id');
    }
}
