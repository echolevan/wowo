<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recharge extends Model
{
    //
    protected $guarded = [];
    protected $appends = ['year_created_at'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getYearCreatedAtAttribute()
    {
        return $this->attributes['year_created_at'] = date('Y-m-d',strtotime($this->created_at));
    }
}
