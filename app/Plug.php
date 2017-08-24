<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Plug extends Model
{
    //
    protected $guarded = [];
    protected $appends = ['type_name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * // 截图
     */
    public function thumbs()
    {
        return $this->hasMany(Thumb::class);
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * 一级标签
     */
    public function tag_one()
    {
        return $this->hasOne(Tag::class,'id','type_one');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * 2级标签
     */
    public function tag_two()
    {
        return $this->hasOne(Tag::class,'id','type_two');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * 作者信息
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * 点赞
     */
    function like_plug(){
        return $this->belongsToMany(User::class , 'like_plugs','plug_id','user_id')->withTimestamps();
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * 收藏
     */
    function collect_plug(){
        return $this->belongsToMany(User::class , 'collect_plugs','plug_id','user_id')->withTimestamps();
    }

    public function is_pay()
    {
        return $this->belongsTo(Order::class,'plug_id','plug_only_id');
    }

    public function historys()
    {
        return $this->hasMany(self::class,'plug_id','plug_id');
    }

    public function getTypeNameAttribute()
    {
        $type = $this->type;
        if($type == 1)
            $name = config('my.plug_name')[1];
        else if ($type == 2)
            $name = config('my.plug_name')[2];
        else
            $name = config('my.plug_name')[3];
        return $name;
    }

    public function getCreatedAtAttribute($created_at)
    {
        return $this->created_at = date('Y-m-d',strtotime($created_at));
    }
}
