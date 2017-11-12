<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bm extends Model
{
    //
    protected $guarded = [];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class,'id','plug_id');
    }

    public function getCreatedAtAttribute($created_at)
    {
        return $this->created_at = date('Y-m-d',strtotime($created_at));
    }
}
