@extends('layouts.layouts')
@section('title','素材管理-展示')
@section('content')
    <h3>素材管理-展示</h3>
    <table class="table table-bordered table-hover">
        <tr>
            <td>素材标题</td>
            <td>素材格式</td>
            <td>素材类型</td>
            <td>素材展示</td>
        </tr>
        @foreach($data as $v)
        <tr>
            <td>{{$v->media_name}}</td>
            <td>
                @if($v->media_format=='image')
                    图片
                @elseif($v->media_format=='voice')
                    语音
                @elseif($v->media_format=='video')
                    视频
                @endif
            </td>
            <td>
                @if($v->media_type=='1')
                    临时
                @elseif($v->media_type=='2')
                    永久
                @endif</td>
            <td>
                @if($v->media_format=='image')
                    <img src="{{$v->media_url}}" width="100px">
                @elseif($v->media_format=='voice')
                    <audio src="{{$v->media_url}}" controls="controls" width="200px"></audio>
                @elseif($v->media_format=='video')
                    <video src="{{$v->media_url}}" controls="controls" width="200px"></video>
                @endif
            </td>
        </tr>
        @endforeach
    </table>
@endsection

