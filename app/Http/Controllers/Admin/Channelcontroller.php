<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Channel;
use App\Tools\Curl;
use App\Tools\Wechat;

class Channelcontroller extends Controller
{
    /**
     * Display a listing of the resource.
     *展示
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data=Channel::get();
        return view('channel.index',['data'=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     *添加页
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('channel.create');
    }

    /**
     * Store a newly created resource in storage.
     *添加
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data=$request->except('_token');
        $identification=$data['identification'];
        $data1=Wechat::getSticket($identification);
        $data1=json_decode($data1,true);
        $res=Channel::create(['name'=>$data['name'],'identification'=>$identification,'ticket'=>$data1['ticket']]);
        if($res){
            return redirect('/channel/index');
        }
    }

    public function show(Request $request)
    {
        $data=Channel::get()->toArray();
        $xStr='';
        $yStr='';
        foreach($data as $key=>$value){
            $xStr.='"'.$value['name'].'",';
            $yStr.=$value['people'].',';
        }
        $xStr=rtrim($xStr,',');
        $yStr=rtrim($yStr,',');
        return view('channel.show',['xStr'=>$xStr,'yStr'=>$yStr]);
    }
}
