<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>ECharts</title>
        <!-- 引入 echarts.js -->
        <script src="/company/51talk/6/echarts.min.js"></script>
    </head>
    <body>
        <!-- 为ECharts准备一个具备大小（宽高）的Dom -->
        <div id="main" style="width: 600px;height:400px;"></div>
        <script type="text/javascript">
            // 基于准备好的dom，初始化echarts实例
            var myChart = echarts.init(document.getElementById('main'));

            // 指定图表的配置项和数据
            option = {
                title: {
                    text: '老师积分趋势图',
                },
                tooltip: {
                    trigger: 'axis'
                },
//                legend: {
//                    data: ['意向', '预购', '成交']
//                },
                toolbox: {
                    show: true,
                    feature: {
//                        magicType: {show: true, type: ['stack', 'tiled']},
                        saveAsImage: {show: true}
                    }
                },
                xAxis: {
                    type: 'category',
                    name: '积分数',
                    boundaryGap: false,
                    data: [0, 10, 20, 30, 40, 50, 60]
                },
                yAxis: {
                    type: 'value',
                    name: '老师数',
                    data: [0, 10, 20, 30, 40, 50, 60]
                },
                series: [
                    {
                        name: '积分',
                        type: 'line',
                        smooth: true,
                        data: [50, 200, 300, 400, 500, 600, 700]
                    }
                ]
            };

            // 使用刚指定的配置项和数据显示图表。
            myChart.setOption(option);
        </script>
    </body>
</html>