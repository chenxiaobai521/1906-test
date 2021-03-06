<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Tools\Wechat;
use App\Tools\Curl;
use App\Model\WechatUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class Wxcontroller extends Controller
{
    public function check()
    {
        //接收 微信推送的数据
        $data = file_get_contents("php://input");
        $log_str = date("Y-m-d H:i:s") . $data . "\n\n";
        file_put_contents('log.txt', $log_str, FILE_APPEND);
        $xmlObj = simplexml_load_string($data);
        $openid = $xmlObj->FromUserName;//取openid
        $msg_type = $xmlObj->MsgType;//消息类型
        $media_id = $xmlObj->MediaId;//MediaId
        if($msg_type == 'image'){
            // 下载图片
            $this->downloadImg($media_id);
        }elseif($msg_type == 'video'){
            //下载视频
            $this->downloadVideo($media_id);
        }
    }

    /**
     * 下载图片素材
     */
    protected function downloadImg($media_id)
    {
        $access_token = Wechat::getAccessToken();
        $url='https://api.weixin.qq.com/cgi-bin/media/get?access_token='.$access_token.'&media_id='.$media_id;
        //请求获取素材接口
        $img=file_get_contents($url);
        //保存图片
        file_put_contents("bbb.jpg",$img);
    }

    protected function downloadVideo($media_id)
    {
        $access_token = Wechat::getAccessToken();
        $url='https://api.weixin.qq.com/cgi-bin/media/get?access_token='.$access_token.'&media_id='.$media_id;
        //请求获取素材接口
        $img = file_get_contents($url);
        //保存视频
        $file_name=date("YmdHis").rand(1111, 9999).'.mp4';
        $res=file_put_contents($file_name,$img);
        var_dump($res);
    }

    /**
     * 根据Openid群发
     */
    public function sendAllByOpenId()
    {
        $users=WechatUser::select('openid')->get()->toArray();
        $openid_list = array_column($users,'openid');
        echo '<pre>';print_r($openid_list);echo'</pre>';
        // openid 列表  可以从数据库表获取
        $msg=date("Y-m-d H:i:s") ." 再发两条：马上放寒假了，不要忘记做作业!!";
        echo "消息： ".$msg;echo '</br>';
        $json_data = [
            "touser"    => $openid_list,
            "msgtype"   => "text",
            "text"      => [
                "content"   => $msg
            ]
        ];
        $access_token = Wechat::getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token='.$access_token;
        $response = Curl::post($url,$json_data);
        // 检查错误
        if($response['errcode']>0){
            echo '错误信息：'.$response['errmsg'];
        }else{
            echo "发送成功";
        }
    }

    public function test()
    {
        $redirect_uri = urlencode(env('WX_AUTH_REDIRECT_URI'));
        $url='https://open.weixin.qq.com/connect/oauth2/authorize?appid='.env('WX_APPID').'&redirect_uri='.$redirect_uri.'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
        echo $url;
    }

    /**
     * 接收网页授权code
     */
    public function auth()
    {
        // 接收 code
        $code = $_GET['code'];
        //换取access_token
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.env('WX_APPID').'&secret='.env('WX_APPSEC').'&code='.$code.'&grant_type=authorization_code';
        $json_data = file_get_contents($url);
        $arr = json_decode($json_data,true);
        echo '<pre>';print_r($arr);echo '</pre>';
        // 获取用户信息
        $url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$arr['access_token'].'&openid='.$arr['openid'].'&lang=zh_CN';
        $json_user_info = file_get_contents($url);
        $user_info_arr = json_decode($json_user_info,true);
        echo '<pre>';print_r($user_info_arr);echo '</pre>';

        //将用户信息保存至 Redis HASH中
        $key = 'h:user_info:'.$user_info_arr['openid'];
        Redis::hMset($key,$user_info_arr);
        //实现签到功能 记录用户签到
        $redis_key = 'checkin:'.date('Y-m-d');
        Redis::Zadd($redis_key,time(),$user_info_arr['openid']);  //将openid加入有序集合
        echo $user_info_arr['nickname']."签到成功"."签到时间：".date("Y-m-d H:i:s");
        echo '<hr>';
        $user_list = Redis::zrange($redis_key,0,-1);
        foreach($user_list as $k=>$v){
            $key = 'h:user_info:'.$v;
            $u = Redis::hGetAll($key);
            if(empty($u)){
                continue;
            }
            echo " <img src='".$u['headimgurl']."'> ";
        }
    }
}
