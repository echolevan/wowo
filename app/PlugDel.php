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
        $s = Carbon::parse($this->created_at)->addDay()->diffInMinutes(Carbon::now());
        $h = floor($s/60);
        $m = $s - $h * 60;
        return $this->last_time = $h.'小时'.$m.'分钟';
    }
}
