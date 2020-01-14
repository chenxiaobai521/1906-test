<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Xinwen;

class Xinwencontroller extends Controller
{
    /**
     * Display a listing of the resource.
     *展示
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data1=request()->except('_token');
        $where=[];
        if(!empty($data1['name'])){
            $where=[
                ['name','=',$data1['name']]
            ];
        }
        if(!empty($data1['title'])){
            $where=[
                ['title','=',$data1['title']]
            ];
        }
        $object=request()->all();
        $data=Xinwen::where($where)->paginate('3');
        return view('admin.xinwen.index',['data'=>$data,'data1'=>$data1,'object'=>$object]);
    }

    /**
     * Show the form for creating a new resource.
     *添加页
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.xinwen.create');
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
        $data['time']=time();
        $res=Xinwen::create($data);
        if($res){
            return redirect('xinwen/index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *修改页
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data=Xinwen::where('id',$id)->first();
        return view('admin.xinwen.edit',['data'=>$data]);
    }

    /**
     * Update the specified resource in storage.
     *修改
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data=$request->except('_token');
        $data['time']=time();
        $res=Xinwen::where('id',$id)->update($data);
        if($res){
            return redirect('xinwen/index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *删除
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $res=Xinwen::where('id',$id)->delete();
        if($res){
            return redirect('xinwen/index');
        }
    }
}
