<?php

namespace App\Http\Controllers;

use App\Order;
use App\Plug;
use App\Recharge;
use App\Tag;
use App\Thumb;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PlugController extends Controller
{
    public function index (Request $request, $type)
    {
        $type = config('my.plug_type')[$type];

        if ($request->orderBy == 1) {
            $orderBy = 'created_at';
        } else if ($request->orderBy == 2) {
            $orderBy = 'download_num';
        } else {
            $orderBy = 'collect_num';
        }

        // search 条件
        $tag_active = isset($request->tag_active) ? $request->tag_active : 0;
        $tag_active_pid = isset($request->tag_active_pid) ? $request->tag_active_pid : 0;
        $limit = isset($request->page) ? ($request->page - 1) * 10 : 0;
        $keyWord = isset($request->keyword) ? $request->keyword : '';

        if ($tag_active_pid == 0) {
            // 查询type_one
            $where = "type_one = " . $tag_active;
        } else {
            $where = "type_two = " . $tag_active;
        }

        $plugs = Plug::where('type', $type)
            ->with('thumbs')
            ->when($tag_active != 0 || $tag_active_pid != 0, function ($query) use ($where, $tag_active, $tag_active_pid) {
                return $query->whereRaw($where);
            })
            ->when($keyWord != '', function ($query) use ($keyWord) {
                return $query->where('title', 'like', "%$keyWord%");
            })
            ->where('is_new',1)
            ->where([['status', 1], ['is_check', 1]])
            ->skip($limit)->take(10)
            ->orderBy($orderBy, 'desc')
            ->get();

        $count = Plug::where('type', $type)
            ->when($tag_active != 0 || $tag_active_pid != 0, function ($query) use ($where, $tag_active, $tag_active_pid) {
                return $query->whereRaw($where);
            })
            ->when($keyWord != '', function ($query) use ($keyWord) {
                return $query->where('title', 'like', "%$keyWord%");
            })
            ->where('is_new',1)
            ->where([['status', 1], ['is_check', 1]])
            ->count();

        return ['plugs' => $plugs, 'count' => $count];
    }


    /**
     * @param $type
     * @return array
     */
    public function plugRank ($type)
    {
        $type = $type == 'undefined' ? $type = 0 : config('my.plug_type')[$type];
        $rank_download = Plug::when($type > 0, function ($query) use ($type) {
            $query->where('type', $type);
        })->where([['status', 1], ['is_check', 1]])->skip(0)->take(5)->where('is_new',1)->orderBy('download_num', 'desc')->get();

        $rank_score = Plug::when($type > 0, function ($query) use ($type) {
            $query->where('type', $type);
        })->where([['status', 1], ['is_check', 1]])->skip(0)->take(5)->where('is_new',1)->orderBy('score', 'desc')->get();

        return ['rank_download' => $rank_download, 'rank_score' => $rank_score];
    }

    public function plugInfo ($id)
    {

        $plug = Plug::with('thumbs')
            ->with('tag_one')->with('tag_two')->with('user')
            ->with(['historys' => function ($query) {
                $query->select('id','plug_id','version','game_version','created_at')->latest();
            }])->with(['is_pay' => function ($query) {
                $query->where('orders.user_id', Auth::id());
            }])->where('id',$id)->first();
        $thumb_list = [];
        foreach ($plug->thumbs as $k => $v) {
            $thumb_list[$k]['src'] = $v->thumb;
            $thumb_list[$k]['w'] = $v->width;
            $thumb_list[$k]['h'] = $v->height;
        }

        $plug->like_plug = DB::table('like_plugs')->where('user_id',Auth::id())->where('plug_id',$plug->plug_id)->count();
        $plug->collect_plug = DB::table('collect_plugs')->where('user_id',Auth::id())->where('plug_id',$plug->plug_id)->count();

        // 更新说明
        $updated_info = Plug::where('plug_id', $plug->plug_id)->select('updated_info', 'created_at')->latest()->get();

        return ['plug' => $plug, 'thumb_list' => $thumb_list, 'updated_info' => $updated_info];
    }

    public function update_plugInfo ($id)
    {
        $plug = Plug::with('thumbs')
            ->with('tag_one')->with('tag_two')->with('user')->with(['like_plug' => function ($query) {
                $query->where('users.id', Auth::id());
            }])->with(['collect_plug' => function ($query) {
                $query->where('users.id', Auth::id());
            }])->with(['is_pay' => function ($query) {
                $query->where('orders.user_id', Auth::id());
            }])->find($id);

        if ($plug->user_id !== Auth::id()) {
            return ['sta' => 0];
        }

        $plug->is_free = $plug->is_free === 0 ? false : true;
        $plug->type = [$plug->type, $plug->type_one, $plug->type_two];
        return ['plug' => $plug];
    }

    /**
     * @param $id
     * 收藏id
     */
    public function collect_this ($id)
    {
        $user = User::find(Auth::id());

        $collect_plug = DB::table('collect_plugs')->where('user_id',Auth::id())->where('plug_id',$id)->count();

        if ($collect_plug > 0) {
            return ['sta' => 0, 'msg' => '你已经收藏过了'];
        }
        DB::beginTransaction();
        try {
            $user->collect_plug()->attach($id);
            $collect = Plug::where('plug_id',$id)->increment('like_num');
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ['sta' => 0, 'msg' => '收藏失败'];
        }


        if ($collect) {
            return ['sta' => 1, 'msg' => '收藏成功'];
        }
        return ['sta' => 0, 'msg' => '收藏失败'];

    }

    /**
     * @param $id
     * @return array
     * 点赞
     */
    public function like_this ($id)
    {
        $user = User::find(Auth::id());

        $like_plug = DB::table('like_plugs')->where('user_id',Auth::id())->where('plug_id',$id)->count();

        if ($like_plug > 0) {
            return ['sta' => 0, 'msg' => '你已经点赞过了'];
        }

        DB::beginTransaction();
        try {
            $user->like_plug()->attach($id);
            $collect = Plug::where('plug_id',$id)->increment('collect_num');
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ['sta' => 0, 'msg' => '点赞失败'];
        }


        if ($collect) {
            return ['sta' => 1, 'msg' => '点赞成功'];
        }
        return ['sta' => 0, 'msg' => '点赞失败'];
    }


    /**
     * @param $id
     * 下载插件
     */
    public function download ($id)
    {
        $plug = Plug::select('type', 'is_free', 'title', 'content', 'wwb', 'plug_id')->find($id);
        // 是否是免费的
        if ($plug->is_free == 0) {
            Plug::where('plug_id',$plug->plug_id)->increment('download_num');
            // 是否是免费的
            if ($plug->type == 1 || $plug->type == 2) {
                // wa twm  model
                return ['sta' => 1, 'type' => 1, 'info' => $plug];
            } else {
                // download
                return ['sta' => 1, 'type' => 2, 'info' => $plug];
            }

        } else {
            // 查看 orders 表是否存在 该订单 存在则不再收费 用唯一ID 去查询
            if (Order::where([['plug_only_id', $plug->plug_id], ['user_id', Auth::id()]])->count() > 0) {
                // 曾经购买过
                Plug::where('plug_id',$plug->plug_id)->increment('download_num');
                if ($plug->type == 1 || $plug->type == 2) {
                    // wa twm  model
                    return ['sta' => 1, 'type' => 1, 'info' => $plug];
                } else {
                    // download
                    return ['sta' => 1, 'type' => 2, 'info' => $plug];
                }
            }
            return ['sta' => 1, 'type' => 3, 'info' => $plug];
        }
    }

    public function to_pay (Request $request)
    {
        if (!Auth::check()) {
            return ['sta' => 0, 'msg' => '请先登录'];
        }

        $plug_id = $request->id;
        if (!$plug_id) {
            return ['sta' => 0, 'msg' => '插件ID不存在'];
        }

        $plug = Plug::where('id', $plug_id)->first();
        if (!$plug) {
            return ['sta' => 0, 'msg' => '插件不存在'];
        }

        //check user wwb
        $user = Auth::user();
        if ($user->wwb < $plug->wwb) {
            return ['sta' => 0, 'msg' => '窝窝币不足，请先充值'];
        }

        //pay
        DB::beginTransaction();
        try {
            Order::create([
                'plug_id' => $plug->id,
                'plug_only_id' => $plug->plug_id,
                'user_id' => $user->id,
                'wwb' => $plug->wwb,
                'status' => 1
            ]);

            User::where('id', $user->id)->update([
                'wwb' => $user->wwb - $plug->wwb
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ['sta' => 0, 'msg' => '支付失败'];
        }

        return ['sta' => 1, 'msg' => '支付成功'];

    }

    /**
     * @return array
     * 得到分类连动
     */
    public function plug_all_info ()
    {
        $tag = [[1, 1, 'WA'], [1, 2, 'TMW'], [2, 3, '插件']];

        $res = [];
        foreach ($tag as $k => $v) {
            $res[$k]['value'] = $v[1]; // type 1 WA 2 TMW 3 插件
            $res[$k]['label'] = $v[2];
            $res[$k]['children'] = Tag::with(['children' => function ($query) {
                $query->select(DB::raw('tags.id as value , tags.name as label ,  tags.pid , tags.id'));
            }])->select(DB::raw('tags.id as value , tags.name as label , tags.pid , tags.id'))->where('type', $v[0])->where('pid', 0)->where([['status', 1], ['is_check', 1], ['is_for_user', 1]])->get();
        }

        return $res;
    }

    /**
     * @param Request $request
     * @return array
     * 上传插件
     */
    public function upload_plug (Request $request, $plug_id = 0)
    {
        $req = collect($request->data);
        $plug = $req->except('uploadList', 'type', 'content', 'plug_url');
        $type = $req->only('type');
        $uploadList = $req->only('uploadList');

        // 开始写入值
        $Plug = new Plug;
        foreach ($plug as $k => $v) {
            $Plug->$k = $v;
        }
        $Plug->user_id = Auth::id();
        $Plug->plug_id = $plug_id === 0 ? get_only_one_plug_id() : $plug_id;
        $Plug->wwb = is_null($req['wwb']) ? 0 : $req['wwb'];
        $Plug->type = $type['type'][0];
        $Plug->type_one = $type['type'][1];
        $Plug->type_two = isset($type['type'][2]) ? $type['type'][2] : 0;
        $Plug->content = $type['type'][0] === 1 || $type['type'][0] === 2 ? $req['content'] : $req['plug_url'];  // 分字符串 跟下载链接

        DB::beginTransaction();
        try {
            if ($plug_id !== 0) {
                // 升级插件 历史插件 is_new  = 0
                Plug::where('plug_id', $plug_id)->update(['is_new' => 0]);
                $num = Plug::where('plug_id', $plug_id)->select('download_num','like_num','collect_num','score')->first();
                $Plug->download_num = $num->download_num;
                $Plug->like_num = $num->like_num;
                $Plug->collect_num = $num->collect_num;
                $Plug->score = $num->score;
            }
            $Plug->save();
            foreach ($uploadList['uploadList'] as $v) {
                Thumb::create([
                    'thumb' => $v['url'],
                    'plug_id' => $Plug->id,
                    'width' => $v['width'],
                    'height' => $v['height'],
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return ['sta' => 0, 'msg' => '上传失败'];
        }

        return ['sta' => 1, 'msg' => '上传成功'];
    }

    public function update_plug (Request $request, $id)
    {
        $req = collect($request->data);
        $plug = $req->except('uploadList', 'type', 'content', 'plug_url');
        $type = $req->only('type');
        $uploadList = $req->only('uploadList');
        // 开始写入值
        $Plug = Plug::find($id);

        if ($Plug->user_id !== Auth::id()) {
            return ['sta' => 0];
        }

        foreach ($plug as $k => $v) {
            $Plug->$k = $v;
        }
        $Plug->user_id = Auth::id();
        $Plug->wwb = is_null($req['wwb']) ? 0 : $req['wwb'];
        $Plug->type = $type['type'][0];
        $Plug->type_one = $type['type'][1];
        $Plug->type_two = isset($type['type'][2]) ? $type['type'][2] : 0;
        $Plug->content = $type['type'][0] === 1 || $type['type'][0] === 2 ? $req['content'] : $req['plug_url'];  // 分字符串 跟下载链接
        DB::beginTransaction();
        try {
            $Plug->save();
            //删除之前的图片
            Thumb::where('plug_id', $id)->delete();
            foreach ($uploadList['uploadList'] as $v) {
                Thumb::create([
                    'thumb' => $v['url'],
                    'plug_id' => $id,
                    'width' => $v['width'],
                    'height' => $v['height'],
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return ['sta' => 0, 'msg' => '编辑失败'];
        }

        return ['sta' => 1, 'msg' => '编辑成功'];
    }


    public function check_plug_id ($id)
    {
        $plug = Plug::where('plug_id', $id)->first();

        if ($plug && $plug->user_id === Auth::id())
            return ['sta' => 1];
        return ['sta' => 0];
    }
}
