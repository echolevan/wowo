<?php
function upload_img($file , $path){

    $url = \Illuminate\Support\Facades\Storage::put($path, $file);

//    $url = ftp_file($url,'image');

    if($url)
        return '/'.$url;
    return false;
}

function upload_plug($file , $path , $name){

    $url = \Illuminate\Support\Facades\Storage::putFileAs($path, $file, date('YmdHi')."_".$name);

//    $url = ftp_file($url,'addons' , $name);

    if($url)
        return '/'.$url;
    return false;
}

function upload_bm($file , $path){
    $url = \Illuminate\Support\Facades\Storage::put($path, $file);

//    $url = ftp_file($url,'market');

    if($url)
        return '/'.$url;
    return false;
}

function upload_avatar_img($file, $path, $size, $ext)
{

    $img = \Intervention\Image\Facades\Image::make($file)->resize($size[0], $size[1]);

    $new_path = $path."/".\Illuminate\Support\Facades\Auth::id().str_random(10).time().str_random(5).".".$ext;
    $img->save($new_path);

//    $url = ftp_file($new_path,'image');

    if($new_path)
        return '/'.$new_path;
    return false;
}

function ftp_file($path , $type ,$name = 0){
    $ftp = \Anchu\Ftp\Facades\Ftp::connection('xmr');

    $addons = $ftp->getDirListing('/'.$type);

    $new_dir = date('Y-m-d');

    if(!in_array($new_dir,$addons)){
        $ftp->makeDir('/'.$type.'/'.$new_dir);
    }

    $ext = explode(".",$path);
    $name = $name === 0 ? \Illuminate\Support\Facades\Auth::id().str_random(20).substr(time(),6).'.'.end($ext) : date('YmdHi').'_'.$name;

    $is_ftp = $ftp->uploadFile($path,'/'.$type.'/'.$new_dir.'/'.$name,FTP_BINARY);

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


function send_msg($code , $tel , $msg_template){
    $app_key = "24565350";
    $app_secret = "f7537d022a4a7435a3e8b4a6dde2be41";
    $request_paras = array(
        'ParamString' => '{"code":"'.$code.'"}',
        'RecNum' => (string)$tel,
        'SignName' =>'嘿市',
        'TemplateCode' => $msg_template
    );
    $request_host = "http://sms.market.alicloudapi.com";
    $request_uri = "/singleSendSms";
    $request_method = "GET";
    $info = "";
    $content = do_get($app_key, $app_secret, $request_host, $request_uri, $request_method, $request_paras, $info);
    return json_decode($content,true);
}



function do_get($app_key, $app_secret, $request_host, $request_uri, $request_method, $request_paras, &$info) {
    ksort($request_paras);
    $request_header_accept = "application/json;charset=utf-8";
    $content_type = "";
    $headers = array(
        'X-Ca-Key' => $app_key,
        'Accept' => $request_header_accept
    );
    ksort($headers);
    $header_str = "";
    $header_ignore_list = array('X-CA-SIGNATURE', 'X-CA-SIGNATURE-HEADERS', 'ACCEPT', 'CONTENT-MD5', 'CONTENT-TYPE', 'DATE');
    $sig_header = array();
    foreach($headers as $k => $v) {
        if(in_array(strtoupper($k), $header_ignore_list)) {
            continue;
        }
        $header_str .= $k . ':' . $v . "\n";
        array_push($sig_header, $k);
    }
    $url_str = $request_uri;
    $para_array = array();
    foreach($request_paras as $k => $v) {
        array_push($para_array, $k .'='. $v);
    }
    if(!empty($para_array)) {
        $url_str .= '?' . join('&', $para_array);
    }
    $content_md5 = "";
    $date = "";
    $sign_str = "";
    $sign_str .= $request_method ."\n";
    $sign_str .= $request_header_accept."\n";
    $sign_str .= $content_md5."\n";
    $sign_str .= "\n";
    $sign_str .= $date."\n";
    $sign_str .= $header_str;
    $sign_str .= $url_str;

    $sign = base64_encode(hash_hmac('sha256', $sign_str, $app_secret, true));
    $headers['X-Ca-Signature'] = $sign;
    $headers['X-Ca-Signature-Headers'] = join(',', $sig_header);
    $request_header = array();
    foreach($headers as $k => $v) {
        array_push($request_header, $k .': ' . $v);
    }

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $request_host . $url_str);
    //curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_VERBOSE, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $request_header);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $ret = curl_exec($ch);
    $info = curl_getinfo($ch);
    curl_close($ch);
    return $ret;
}

function del_cache(){
    \Illuminate\Support\Facades\Cache::forget('plug_index_wa');
    \Illuminate\Support\Facades\Cache::forget('plug_index_tmw');
    \Illuminate\Support\Facades\Cache::forget('plug_index_plug');
    \Illuminate\Support\Facades\Cache::forget('plug_index_recent_plugs');
    \Illuminate\Support\Facades\Cache::forget('plug_index_census');
    \Illuminate\Support\Facades\Cache::forget('plug_index_download_plugs_this_mouth');
    \Illuminate\Support\Facades\Cache::forget('plug_index_download_plugs');
    \Illuminate\Support\Facades\Cache::forget('plug_index_total_person');
}
