<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Tools\Curl;

class Indexcontroller extends Controller
{
    /**
     * Display a listing of the resource.
     *首页
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.index');
    }

    /**
     * 天气图表
     * @return [type] [description]
     */
    public function weather()
    {
        return view('admin.weather');
    }

    /**
     * 获取天气数据
     * @return [type] [description]
     */
    public function weatherasd(){
        $city=request()->city;
        $url="http://api.k780.com/?app=weather.future&weaid=".$city."&&appkey=47855&sign=32c031f89dce27a0dfd114a338719777&format=json";
        $weather=Curl::Get($url);
        return $weather;
    }
}
