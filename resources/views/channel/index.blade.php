@extends('layouts.layouts')
@section('title','渠道管理-展示')
@section('content')
    <h3>渠道管理-展示</h3>
    <table class="table table-bordered table-hover">
        <tr>
            <td>编号</td>
            <td>渠道名称</td>
            <td>渠道标识</td>
            <td>渠道二维码</td>
            <td>关注人数</td>
        </tr>
        @foreach($data as $v)
        <tr>
            <td>{{$v->id}}</td>
            <td>{{$v->name}}</td>
            <td>{{$v->identification}}</td>
            <td><img src="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket={{$v->ticket}}" width="100px"></td>
            <td>{{$v->people}}</td>
        </tr>
        @endforeach
    </table>
@endsection

