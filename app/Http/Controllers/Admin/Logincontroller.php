<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\User as user_model;

class Logincontroller extends Controller
{
    public function loginDo()
    {
        $data=request()->except('_token');
        $where=[];
        if(!empty($data['email'])){
            $where=[
                ['email','=',$data['email']],
            ];
        }
        $userInfo=user_model::where($where)->first();
        if(empty($userInfo)){
            echo "<script>alert('账号不正确');location.href='login';</script>";
        }else{
            $error_num=$userInfo['error_num'];
            $last_error_time=$userInfo['last_error_time'];
            $user_id=$userInfo['user_id'];
            $time=time();
            // dd($time-$last_error_time);
            if($userInfo['pwd']==$data['pwd']){
                // 如果密码正确
                // 判断 密码错误三次&&还在一小时之内
                if($error_num>=3&&($time-$last_error_time)<300){
                    // echo "提示 账号锁定中，请于***分钟后登录";exit;
                    $min=5-ceil(($time-$last_error_time)/60);
                    die ("<script>alert('账号已锁定，请于".$min."分钟后重新登陆');location.href='login';</script>");
                }
                //清零
                user_model::where("user_id",$user_id)->update(['error_num'=>0,'last_error_time'=>null]);
                return redirect('/');
            }else{
                //如果密码错误
                if($time-$last_error_time>=300){
                    //清零+错误次数为1
                    $res=user_model::where("user_id",$user_id)->update(['error_num'=>1,'last_error_time'=>$time]);
                    if($res){
                        echo "<script>alert('密码错误，您还有2次机会');location.href='login';</script>";
                    }
                }else{
                    if($error_num>=3){
                        $min=5-ceil(($time-$last_error_time)/60);
                        echo "<script>alert('账号已锁定，请于".$min."分钟后重新登陆');location.href='login';</script>";
                    }else{
                        //累计错误
                        $res=user_model::where("user_id",$user_id)->update(['error_num'=>$error_num+1,'last_error_time'=>$time]);
                        if($res){
                            echo "<script>alert('密码错误，您还有".(3-($error_num+1))."次机会');location.href='login';</script>";
                        }
                    }
                }
            }
        }
    }
}
