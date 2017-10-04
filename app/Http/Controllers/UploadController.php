<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    //

    /**
     * @param Request $request
     * @return array|string
     * 上传插件详情图片
     */
    public function upload_plug_info_img (Request $request)
    {
        if (!in_array($request->file('image')->getClientMimeType(), config('my.img_type'))) {
            return ['sta' => 0, 'msg' => '请上传PNG、GIF、JPG格式的图片'];
        }

        if ($request->file('image')->getSize() > 1024 * 1024 * 2) {
            return ['sta' => 0, 'msg' => '请上传小于2M的图片'];
        }

//        $ext = $request->file('image')->getClientOriginalExtension();
        $path = "image/";
        $url = upload_img($request->file('image'), $path);
        return ['sta' => 1, 'url' => $url];
    }

    /**
     * @param Request $request
     * @return array|string
     * 上传插件截图图片
     */
    public function upload_plug_screen_img (Request $request)
    {

        $size = getimagesize($request->file('file'));
        if (!in_array($request->file('file')->getClientMimeType(), config('my.img_type'))) {
            return ['sta' => 0, 'msg' => '请上传PNG、GIF、JPG格式的图片'];
        }
        if ($request->file('file')->getSize() > 1024 * 1024 * 2) {
            return ['sta' => 0, 'msg' => '请上传小于2M的图片'];
        }
//        $ext = $request->file('file')->getClientOriginalExtension();
        $path = "image/";
        $url = upload_img($request->file('file'), $path);
        return ['sta' => 1, 'url' => $url, 'width' => $size[0], 'height' => $size[1]];
    }

    public function upload_plug_info_plug (Request $request)
    {
        if (!in_array(strtolower($request->file('file')->getClientOriginalExtension()), config('my.upload_plug_type'))) {
            return ['sta' => 0, 'msg' => '请上传zip、rar、7z格式的文件'];
        }
        
//        if(isset($request->tag_one) && $request->tag_one === '整合界面'){
//            if ($request->file('file')->getSize() > 1024 * 1024*150) {
//                return ['sta' => 0, 'msg' => '请上传小于150M的文件'];
//            }
//        }else{
//            if ($request->file('file')->getSize() > 1024 * 1024 *10) {
//                return ['sta' => 0, 'msg' => '请上传小于10M的文件'];
//            }
//        }

        $path = "addons/";
        $url = upload_plug($request->file('file'), $path, $request->file('file')->getClientOriginalName());
        return ['sta' => 1, 'url' => $url];
    }

    public function upload_bm_plug (Request $request)
    {
        if (!in_array(strtolower($request->file('file')->getClientOriginalExtension()), config('my.upload_bm_type'))) {
            return ['sta' => 0, 'msg' => '请上传BT文件'];
        }

        if ($request->file('file')->getClientOriginalExtension() === 'torrent') {
            if ($request->file('file')->getSize() > 1024 * 1024 * 2) {
                return ['sta' => 0, 'msg' => '请上传小于2M的BT文件'];
            }
        } else {
            if ($request->file('file')->getSize() > 1024 * 1024) {
                return ['sta' => 0, 'msg' => '请上传小于1M的文件'];
            }
        }

        $path = "market/";
        $url = upload_bm($request->file('file'), $path);
        return ['sta' => 1, 'url' => $url];
    }
}
