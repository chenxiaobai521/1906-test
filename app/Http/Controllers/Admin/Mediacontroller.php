<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Tools\Wechat;
use App\Tools\Curl;
use App\Model\Media;

class Mediacontroller extends Controller
{
    public function create()
    {
        return view('media.create');
    }

    public function store(Request $request)
    {
        //接值
        $data=$request->input();
        $file=$request->file;
        //判断是否有文件
        if(!$request->hasFile('file')){
            exit('未获取到上传文件或上传过程出错');die;
        }
        //文件上传
        $ext=$file->getClientOriginalExtension();//得到文件的后缀名
        $filename=md5(uniqid()).".".$ext;

        $path = $request->file->storeAs('/images',$filename);
        //调接口
        $access_token=Wechat::getAccessToken();
        $url="https://api.weixin.qq.com/cgi-bin/media/upload?access_token=".$access_token."&type=".$data['media_format'];

        //curl发送文件先要通过CURLFile类处理
        $filePath=new \CURLFile(public_path()."/".$path);
        $postData=['media'=>$filePath];
        $res=Curl::post($url,$postData);
        $res=json_decode($res,true);
        if(isset($res['media_id'])){
            //微信返回的素材ID
            $media_id=$res['media_id'];
            $res=Media::create([
                'media_name'=>$data['media_name'],
                'media_format'=>$data['media_format'],
                'media_type'=>$data['media_type'],
                'media_url'=>$path,
                'wechat_media_id'=>$media_id,
                'add_time'=>time(),
            ]);
            if($res){
                return redirect('show');
            }
        }
    }

    public function show(Request $request)
    {
        $data=Media::get();
        return view('media.show',['data'=>$data]);
    }
}
