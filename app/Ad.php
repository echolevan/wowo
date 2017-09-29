<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    //
    protected $guarded = [];
    protected $appends = ['last_time'];

    public function getUrlAttribute($url)
    {
        $url = json_decode($url);
        $n_url = [];
        foreach ($url as $k => $v){
            $n_url[$k]['url'] = $v;
        }
        return $this->url = $n_url;
    }

    public function getLastTimeAttribute()
    {
        if ($this->end_at > date('Y-m-d',time())){
            $s = Carbon::parse($this->end_at)->diffInDays(Carbon::now());
            return $this->last_time = ($s + 1).'天';
        }else{
            return $this->last_time = '0天';
        }
    }
}
