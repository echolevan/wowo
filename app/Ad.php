<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    //
    protected $guarded = [];

    public function getUrlAttribute($url)
    {
        $url = json_decode($url);
        $n_url = [];
        foreach ($url as $k => $v){
            $n_url[$k]['url'] = $v;
        }
        return $this->url = $n_url;
    }
}
