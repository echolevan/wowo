<?php

function upload_img($file , $path){

    $url = \Illuminate\Support\Facades\Storage::put($path, $file);

    return "http://wowo.dev/upload/".$url;
}