<?php

namespace App;

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
        return $this->belongsToMany(User::class , 'like_plugs')->withTimestamps();
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * 收藏
     */
    function collect_plug(){
        return $this->belongsToMany(User::class , 'collect_plugs')->withTimestamps();
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
}
