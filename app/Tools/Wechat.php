<?php

namespace App\Tools;

use Illuminate\Support\Facades\Cache;
use App\Tools\Curl;

class Wechat{
    const appId="wxfa46b45d559fcbbe";
    const appsecret="7d1e9134214ee8e36d195e30636fc4de";
    public static function reponseText($xmlObj, $msg)
    {
        echo "<xml>
              <ToUserName><![CDATA[".$xmlObj->FromUserName."]]></ToUserName>
              <FromUserName><![CDATA[".$xmlObj->ToUserName."]]></FromUserName>
              <CreateTime>".time()."</CreateTime>
              <MsgType><![CDATA[text]]></MsgType>
              <Content><![CDATA[".$msg."]]></Content>
              </xml>";die;
    }

    public static function getAccessToken(){
        //先判断缓存是否有数据
        $access_token=Cache::get('access_token');
        if(empty($access_token)){
            $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".Self::appId."&secret=".Self::appsecret."";
            $data=file_get_contents($url);
            $data=json_decode($data,true);
            $access_token=$data['access_token'];
            Cache::put('access_token',$access_token,1);
        }
        //没有数据再进去调微信接口获取=>存入缓存
        return $access_token;
    }
    public static function getUserInfoByOpenId($openid){
        $access_token=Self::getAccessToken();
        $url="https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";
        $data=file_get_contents($url);
        $data=json_decode($data,true);
        return $data;
    }
    public static function Image($xmlObj,$media_id){
        echo  "<xml>
            <ToUserName><![CDATA[".$xmlObj->FromUserName."]]></ToUserName>
            <FromUserName><![CDATA[".$xmlObj->ToUserName."]]></FromUserName>
            <CreateTime>".time()."</CreateTime>
            <MsgType><![CDATA[image]]></MsgType>
            <Image>
            <MediaId><![CDATA[".$media_id."]]></MediaId>
            </Image>
            </xml>";die;
    }
    //创建二维码ticket
    public static function getSticket($identification){
        $access_token=Self::getAccessToken();
        //地址
        $url="https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$access_token;
        //参数
        $postData='{"expire_seconds": 604800, "action_name": "QR_STR_SCENE", "action_info": {"scene": {"scene_str": "'.$identification.'"}}}';
        //请求方式
        $res=Curl::post($url,$postData);
        return $res;
    }
}
