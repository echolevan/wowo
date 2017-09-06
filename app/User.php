<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $appends = ['year_created_at'];

    public function like_plug()
    {
        return $this->belongsToMany(Plug::class , 'like_plugs')->withTimestamps();
    }

    public function collect_plug()
    {
        return $this->belongsToMany(Plug::class , 'collect_plugs')->withTimestamps();
    }

    public function plugs()
    {
        return $this->hasMany(Plug::class);
    }

    public function getBirthplaceAttribute($birthplace)
    {
        if(is_array($birthplace)){
            return $this->birthplace = $birthplace;
        }else{
            return $this->birthplace = json_decode($birthplace,true);
        }
    }

    public function getHabitablyAttribute($habitably)
    {
        if(is_array($habitably)){
            return $this->habitably = $habitably;
        }else{
            return $this->habitably = json_decode($habitably,true);
        }
    }

    public function getYearCreatedAtAttribute($created_at)
    {
        return $this->attributes['year_created_at'] = date('Y-m-d',strtotime($this->created_at));
    }

}
