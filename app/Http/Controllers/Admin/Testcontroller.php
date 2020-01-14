<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Tools\Wechat;

class Testcontroller extends Controller
{
    //创建菜单
    public function createMenu()
    {
        $access_token=Wechat::getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$access_token;
        $menu = [
            "button"    => [
                [
                    "type"  => "view",
                    "name"  => "签到",
                    "key"   => "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxfa46b45d559fcbbe&redirect_uri=http%3A%2F%2F1906chenenpeng.comcto.com%2Fauth&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect"
                ],
                [
                    "name"  => "相机",
                    "sub_button"    => [
                        [
                            "type"  => "scancode_push",
                            "name"  => "扫一扫",
                            "key"   => "scan111"
                        ],
                        [
                            "type"  => "pic_sysphoto",
                            "name"  => "拍照",
                            "key"   => "photo111"
                        ]
                    ]
                ],
            ]
        ];
        $this->curlPost($url,$menu);
    }
    function curlPost($url,$menu)
    {
        $ch = curl_init();
        $data_string = json_encode($menu,JSON_UNESCAPED_UNICODE);     //要发送的数据
        // 设置参数
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //post数据
        curl_setopt($ch, CURLOPT_POST, 1);
        //post的变量
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        //加入以下设置
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($data_string))
        );
        $output = curl_exec($ch);
        curl_close($ch);
        //打印获得的数据
        $arr = json_decode($output,true);
        echo '<pre>';print_r($arr);echo '</pre>';
    }
}
