<?php

function upload_img($file , $path){

    $url = \Illuminate\Support\Facades\Storage::put($path, $file);

    $url = ftp_file($url,'image');

    if($url)
        return $url;
    return false;
}

function upload_plug($file , $path){

    $url = \Illuminate\Support\Facades\Storage::put($path, $file);

    $url = ftp_file($url,'addons');

    if($url)
        return $url;
    return false;
}

function upload_avatar_img($file, $path, $size, $ext)
{

    $img = \Intervention\Image\Facades\Image::make($file)->resize($size[0], $size[1]);

    $new_path = $path."/".\Illuminate\Support\Facades\Auth::id().str_random(10).time().str_random(5).".".$ext;
    $img->save($new_path);

    $url = ftp_file($new_path,'image');

    if($url)
        return $url;
    return false;
}

function ftp_file($path , $type){
    $ftp = \Anchu\Ftp\Facades\Ftp::connection('xmr');

    $addons = $ftp->getDirListing('down.iwowcn.com/'.$type);

    $new_dir = date('Y-m-d');

    if(!in_array($new_dir,$addons)){
        $ftp->makeDir('down.iwowcn.com/'.$type.'/'.$new_dir);
    }

    $ext = explode(".",$path);
    $name = \Illuminate\Support\Facades\Auth::id().str_random(20).substr(time(),6).'.'.end($ext);

    $is_ftp = $ftp->uploadFile($path,'down.iwowcn.com/'.$type.'/'.$new_dir.'/'.$name,FTP_BINARY);

    if($is_ftp){
        unlink($path);
        return config('my.down_url').$type.'/'.$new_dir."/".$name;
    }
    return false;
}


function get_only_one_plug_id(){
    do{
        $plug_id = \Illuminate\Support\Facades\Auth::id().'_'.time().'_'.str_random(10);
    }while(\App\Plug::where('plug_id',$plug_id)->count() > 0);

    return  $plug_id;
}
