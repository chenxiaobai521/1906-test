<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>新闻--添加页</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/admin/css/bootstrap.min.css">
    <script src="/admin/jquery/2.1.1/jquery.min.js"></script>
    <script src="/admin//js/bootstrap.min.js"></script>
</head>
<body>

<form action="{{url('xinwen/store')}}" method="post" style="margin-left:100px;margin-top:50px">
    <div class="form-group">
        <label>标题</label>
        <input type="text" class="form-control" name="title">
    </div>
    <div class="form-group">
        <label>作者</label>
        <input type="text" class="form-control" name="name">
    </div>
    <div class="form-group">
        <label>内容</label>
        <textarea class="form-control" rows="3" name="account"></textarea>
    </div>
    <button type="submit" class="btn btn-default">添加</button>
</form>

</body>
</html>
