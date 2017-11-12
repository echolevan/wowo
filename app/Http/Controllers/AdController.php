<?php

namespace App\Http\Controllers;

use App\Ad;
use Illuminate\Http\Request;

class AdController extends Controller
{
    //

    public function create_info ()
    {
        $id = Ad::pluck('position');
        $ad_position = collect(config('my.ad_position'))->except($id->toArray());
        $ad_default_wh = collect(config('my.ad_default_wh'))->except($id->toArray());
        return ['ad_position' => $ad_position, 'ad_default_wh' => $ad_default_wh];
    }

    public function update_info ($id)
    {
        $id = Ad::where('id', '!=', $id)->pluck('position');
        $ad_position = collect(config('my.ad_position'))->except($id->toArray());
        $ad_default_wh = collect(config('my.ad_default_wh'))->except($id->toArray());
        return ['ad_position' => $ad_position, 'ad_default_wh' => $ad_default_wh];
    }

    public function create (Request $request)
    {
        foreach ($request->data['uploadList'] as $v) {
            $url[] = $v['url'];
        }
        $ad = Ad::create([
            'position' => $request->data['position'],
            'url' => json_encode($url),
            'width' => $request->data['width'],
            'height' => $request->data['height'],
            'is_show' => $request->data['is_show'],
            'link' => $request->data['link'],
            'end_at' => date('Y-m-d',strtotime($request->data['end_at'])),
            'info' => $request->data['info'],
            'email' => $request->data['email'],
        ]);

        if ($ad)
            return ['sta' => 1];
        return ['sta' => 0];
    }

    public function update (Request $request)
    {
        foreach ($request->data['uploadList'] as $v) {
            $url[] = $v['url'];
        }
        $ad = Ad::where('id', $request->data['id'])->update([
            'position' => $request->data['position'],
            'url' => json_encode($url),
            'width' => $request->data['width'],
            'height' => $request->data['height'],
            'is_show' => $request->data['is_show'],
            'link' => $request->data['link'],
            'end_at' => date('Y-m-d',strtotime($request->data['end_at'])),
            'info' => $request->data['info'],
            'email' => $request->data['email'],
        ]);

        if ($ad)
            return ['sta' => 1];
        return ['sta' => 0];
    }

    public function index ()
    {
        $ads = Ad::get();
        $ad_position = config('my.ad_position');
        foreach ($ads as $k => $v) {
            $ads[$k]->position_name = isset($ad_position[$v->position]) ? $ad_position[$v->position] : '';
        }

        return ['ads' => $ads];
    }

    public function change_status ($id, $v)
    {
        $tag = Ad::where('id', $id)->update([
            'is_show' => $v
        ]);
        if ($tag)
            return ['sta' => 1, 'msg' => '更新成功'];
        return ['sta' => 0, 'msg' => '更新失败'];
    }

    public function delete ($id)
    {
        $url = Ad::where('id', $id)->value('url');
        $thumb = [];
        foreach ($url as $v) {
            $thumb[] = $v['url'];
        }
        Ad::destroy($id);
        foreach ($thumb as $k => $v) {
             try{
                 unlink(substr($v,1));
             }catch(\Exception $e){
             }
        }
        return 1;
    }

    public function ads ()
    {
        $ads = Ad::where('is_show',1)->get();
        $n_ad = [];
        foreach ($ads as $k => $v){
            $n_ad[$v->position] = $v;
        }
        return ['sta' => 1, 'ads' => $n_ad];
    }

}
