@extends('layouts.layouts')
@section('title','素材管理-添加')
@section('content')
    <h3>素材管理-添加</h3>
    <form action="{{url('store')}}" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="exampleInputName2">素材名称</label>
            <input type="text" class="form-control" name="media_name">
        </div>
        <div class="form-group">
            <label for="exampleInputFile">素材文件</label>
            <input type="file" id="exampleInputFile" name="file">
        </div>
        <div class="form-group">
            <label for="exampleInputName2">素材类型</label>
            <select class="form-control" name="media_type">
                <option value="1">临时</option>
                <option value="2">永久</option>
            </select>
        </div>
        <div class="form-group">
            <label for="exampleInputName2">素材格式</label>
            <select class="form-control" name="media_format">
                <option value="image">图片（image）</option>
                <option value="voice">语音（voice）</option>
                <option value="video">视频（video）</option>
            </select>
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
    </form>
@endsection
