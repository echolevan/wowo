<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    protected $guarded = [];

    protected $appends = ['year_created_at'];


    public function plug()
    {
        return $this->belongsTo(Plug::class,'plug_only_id','plug_id');
    }

    public function score()
    {
        return $this->hasMany(Score::class,'plug_id','plug_only_id');
    }

    public function getYearCreatedAtAttribute()
    {
        return $this->attributes['year_created_at'] = date('Y-m-d',strtotime($this->created_at));
    }
}
