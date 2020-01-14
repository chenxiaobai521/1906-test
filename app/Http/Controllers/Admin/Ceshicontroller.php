<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Tools\Wechat;
use App\Model\Xinwen;
use App\Model\Channel;
use App\Model\WechatUser;
use App\Model\Media;

class Ceshicontroller extends Controller
{
    public function index()
    {
        //微信接入
//        $echosr = $_GET['echostr'];
//        echo $echosr;die;
        $xml=file_get_contents("php://input");//接受原始的xml或json数据
        file_put_contents("log.txt","\n".$xml."\n",FILE_APPEND);
        $xmlObj=simplexml_load_string($xml);
        //如果用户是关注
        if($xmlObj->MsgType=='event' && $xmlObj->Event=='subscribe'){
            //关注时，获取用户基本信息
            $data=Wechat::getUserInfoByOpenId($xmlObj->FromUserName);
            //获取渠道标识
            $identification=$data['qr_scene_str'];
            Channel::where(['identification'=>$identification])->increment('people');
            $nickname=$data['nickname'];
            //存入用户基本信息
            //判断用户基本信息表有没有数据（通过openid查询）
            $res=WechatUser::where(['openid'=>$data['openid']])->first();
            if(!empty($res)){
                WechatUser::where(['openid'=>$data['openid']])->update(['identification'=>$identification,'is_del'=>1]);
            }else{
                WechatUser::create(['openid'=>$data['openid'],'nickname'=>$nickname,'identification'=>$identification]);
            }
            if($data['sex']=='1'){
                $msg='欢迎'.$nickname.'先生关注本公众号';
            }elseif($data['sex']=='2'){
                $msg='欢迎'.$nickname.'女士关注本公众号';
            }else{
                $msg='欢迎'.$nickname.'关注本公众号';
            }
            Wechat::reponseText($xmlObj,$msg);
        }
        if($xmlObj->MsgType=='event' && $xmlObj->Event=='unsubscribe'){
            $res=Wechat::getUserInfoByOpenId($xmlObj->FromUserName);  //获取用户信息  调接口
            //获取渠道标识 根据openid
            $openid=$res['openid'];
            $identification=WechatUser::where(['openid'=>$openid])->get('identification')->first()->toArray();
            //根据渠道标识 关注人数递减
            Channel::where(['identification'=>$identification])->decrement('people');
            //条件没有删除 用户openid查询用户表
            $where=[
                ['is_del','=',1],
                ['openid','=',$openid]
            ];
            $res=WechatUser::where($where)->first()->toArray();
            //关注过将is_del改为2 删除信息
            if($res){
                WechatUser::where(['openid'=>$openid])->update(['is_del'=>2]);
            }
        }
        //如果用户发送的是文本消息
        if($xmlObj->MsgType == 'text'){
            $content=trim($xmlObj->Content);
            if($content == '最新新闻'||$content == '新闻'){
                $data=Xinwen::select('id','account','ll')->orderBy('time','desc')->limit(1)->get()->toArray();
                $id=$data[0]['id'];
                Xinwen::where('id',$id)->update(['ll'=>$data[0]['ll']+1]);
                $msg=$data[0]['account'];
                Wechat::reponseText($xmlObj,$msg);
            }elseif(mb_strpos($content,"新闻") !== false){
                $data=Xinwen::where('title','like',"%$content%")->get()->toArray();
                if(!empty($data)){
                    $id=$data[0]['id'];
                    $msg=$data[0]['account'];
                    Xinwen::where('id',$id)->update(['ll'=>$data[0]['ll']+1]);
                    Wechat::reponseText($xmlObj,$msg);
                }else{
                    Wechat::reponseText($xmlObj,'暂无相关新闻');
                }
            }elseif(mb_strpos($content, "天气") !== false){
                $city=rtrim($content, '天气');
                if(empty($city)){
                    $city="北京";
                }
                $url="http://api.k780.com/?app=weather.future&weaid=".$city."&appkey=47855&sign=32c031f89dce27a0dfd114a338719777&format=json";
                $data=file_get_contents($url);
                $data=json_decode($data, true);
                $msg='';
                foreach($data['result'] as $key => $value){
                    $msg.=$value['days']."".$value['week']."".$value['citynm']."".$value['temperature']."\n";
                }
                Wechat::reponseText($xmlObj, $msg);
            }
        }elseif($xmlObj->MsgType == 'image'){
            $data=Media::select('wechat_media_id')->orderBy(\DB::raw('RAND()'))->take(1)->first()->toArray();
            $media_id=$data['wechat_media_id'];
            Wechat::Image($xmlObj,$media_id);
        }
    }
}
