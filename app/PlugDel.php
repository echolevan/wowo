<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class PlugDel extends Model
{
    //
    protected $guarded = [];
    protected $table = 'plug_del';
    protected $appends = ['last_time'];


    public function getLastTimeAttribute()
    {
        return $this->last_time = 24 - Carbon::parse($this->created_at)->diffInHours(Carbon::now());
    }
}
