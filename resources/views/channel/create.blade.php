@extends('layouts.layouts')
@section('title','渠道管理-添加')
@section('content')
    <h3>渠道管理-添加</h3>
    <form action="{{url('/channel/store')}}" method="post">
        <div class="form-group">
            <label for="exampleInputName2">渠道名称</label>
            <input type="text" class="form-control" name="name" placeholder="Channel name">
        </div>
        <div class="form-group">
            <label for="exampleInputName2">渠道标识</label>
            <input type="text" class="form-control" name="identification" placeholder="Channel identification">
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
    </form>
@endsection
