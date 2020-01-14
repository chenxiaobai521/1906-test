<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>新闻--展示页</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/admin/css/bootstrap.min.css">
    <script src="/admin/jquery/2.1.1/jquery.min.js"></script>
    <script src="/admin//js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
    <h2>新闻列表</h2>
    <a class="btn btn-default" href="{{url('xinwen/create')}}" role="button">添加</a>
    <form action="{{url('xinwen/index')}}" style="margin-top:10px">
        <input type="text" name="name" value="{{$data1['name']??''}}" placeholder="Name">
        <input type="text" name="title" value="{{$data1['title']??''}}" placeholder="Title">
        <input type="submit" value="搜索">
    </form>
    <table class="table table-hover" style="margin-top:10px">
        <thead>
        <tr>
            <th>标题</th>
            <th>内容</th>
            <th>作者</th>
            <th>时间</th>
            <th>访问量</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $v)
        <tr>
            <td>{{$v->title}}</td>
            <td>{{$v->name}}</td>
            <td>{{$v->account}}</td>
            <td>{{date('Y-m-d H:i:s',$v->time)}}</td>
            <td></td>
            <td>
                <a class="btn btn-default" href="{{url('xinwen/edit/'.$v->id)}}" role="button">编辑</a>&nbsp;&nbsp;
                <a class="btn btn-default" href="{{url('xinwen/destroy/'.$v->id)}}" role="button">删除</a>
            </td>
        </tr>
        @endforeach
        </tbody>
        <tr><td colspan="6">{{$data->appends($object)->links()}}</td></tr>
    </table>
</div>

</body>
</html>
