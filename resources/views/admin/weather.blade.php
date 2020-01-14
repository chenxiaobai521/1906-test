@extends('layouts.layouts')
@section('title','主页')
@section('content')
<meta charset="utf-8">
<link rel="icon" href="https://jscdn.com.cn/highcharts/images/favicon.ico">
<meta name="viewport" content="width=device-width, initial-scale=1">

<script src="https://code.highcharts.com.cn/highcharts/highcharts.js"></script>
<script src="https://code.highcharts.com.cn/highcharts/highcharts-more.js"></script>
<script src="https://code.highcharts.com.cn/highcharts/modules/exporting.js"></script>
<script src="https://img.hcharts.cn/highcharts-plugins/highcharts-zh_CN.js"></script>

<form>
    <table>
        <h4>一周气温展示</h4>
        <input type="text" name="city" placeholder="请输入城市名称">
        <button type='button' id="dianji">搜索</button>
    </table>
</form>
<br><br><br>
<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
{{-- ajax --}}
<script>
    $(function(){
        $(document).on('click','#dianji',function(){
            var city =$('input[name="city"]').val();
            if(city==""){
                city="北京";
            }
            $.ajax({
                method:"get",
                data:{city:city},
                url:"{{url('weatherasd')}}",
                dataType:"json",
                success:function(res){
                    if(res.success==0){
                        alert('请输入存在的城市，默认是北京天气');
                        return;
                    }
                    suibianqide(res.result);
                }
            })
        })
        function suibianqide(res){
            var week=[];
            var temperature=[];
            $.each(res,function(i,v){
                week.push(v.days);
                var arr=[parseInt(v.temp_low),parseInt(v.temp_high)];
                temperature.push(arr);
            })
            var chart = Highcharts.chart('container', {
                chart: {
                    type: 'columnrange', // columnrange 依赖 highcharts-more.js
                    inverted: true
                },
                title: {
                    text: '一周气温统计图'
                },
                subtitle: {
                    text: res[0]['citynm']
                },
                xAxis: {
                    categories:  week
                },
                yAxis: {
                    title: {
                        text: '温度 ( °C )'
                    }
                },
                tooltip: {
                    valueSuffix: '°C'
                },
                plotOptions: {
                    columnrange: {
                        dataLabels: {
                            enabled: true,
                            formatter: function () {
                                return this.y + '°C';
                            }
                        }
                    }
                },
                legend: {
                    enabled: false
                },
                series: [{
                    name: '温度',
                    data: temperature
                }]
            });
        }
    })
</script>

<script src="/admin/jquery.js"></script>
<script>
    $.ajax({
        data:{city:'北京'},
        url:"{{url('weatherasd')}}",
        dataType:"json",
        success:function(res){
            suibianqide(res.result);
        }
    })

    function suibianqide(res){
        var week=[];
        var temperature=[];
        $.each(res,function(i,v){
            week.push(v.days);
            var arr=[parseInt(v.temp_low),parseInt(v.temp_high)];
            temperature.push(arr);
        })
        var chart = Highcharts.chart('container', {
            chart: {
                type: 'columnrange', // columnrange 依赖 highcharts-more.js
                inverted: true
            },
            title: {
                text: '一周气温统计图'
            },
            subtitle: {
                text: res[0]['citynm']
            },
            xAxis: {
                categories:  week
            },
            yAxis: {
                title: {
                    text: '温度 ( °C )'
                }
            },
            tooltip: {
                valueSuffix: '°C'
            },
            plotOptions: {
                columnrange: {
                    dataLabels: {
                        enabled: true,
                        formatter: function () {
                            return this.y + '°C';
                        }
                    }
                }
            },
            legend: {
                enabled: false
            },
            series: [{
                name: '温度',
                data: temperature
            }]
        });
    }
</script>
@endsection


