<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"/> 
        <title>小票打印</title>
        <script src='http://192.168.137.1:8000/CLodopfuncs.js'></script>
        <style type="text/css">
            *{padding:0;margin: 0;}
            h1{font-size: 20px;}
            h3{font-size: 16px;}
            .left{
                float: left;
            }
            .right{
                float:right;
            }
            .clearfix{
                clear: both;
            }
            ul{list-style: none;}
            .print_container{
                padding: 20px;
                width: 188px;
            }
            .section1{
            }
            .section2 label{
                display: block;
            }
            .section3 label{
                display: block;
            }
            .section4{
            }
            .section4 .total label{
                display: block;
            }
            .section4 .other_fee{
                border-bottom: 1px solid #DADADA;
            }
            .section5 label{
                display: block;
            }
        </style>
    </head>
    <body style="background-color:#fff;">
        <div class="print_container" id="form1">
            <h1>给顾客专用</h1>
            <span>**************************</span>
            <div class="section1">
                <h3>百度外卖</h3>
                <h3>在线支付预约</h3>
            </div>
            <span>**************************</span>
            <div class="section2">
                <label>期望送达时间：5678</label><br>
                <label>订单备注：1111</label>
            </div>
            <span>**************************</span>
            <div class="section3">
                <label>订单编号：567890</label><br>
                <label>下单时间：1111</label>
            </div>
            <span>**************************</span>
            <div class="section4">
                <div style="border-bottom: 1px solid #DADADA;">
                    <table style="width: 100%;">
                        <thead>
                            <tr>
                                <td width="60%">菜单名称</td>
                                <td width="20%">数量</td>
                                <td width="20%">金额</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>米饭</td>
                                <td>2</td>
                                <td>28.00</td>
                            </tr>
                            <tr>
                                <td>米饭</td>
                                <td>2</td>
                                <td>28.00</td>
                            </tr>
                            <tr>
                                <td>米饭</td>
                                <td>2</td>
                                <td>28.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="other_fee">
                    <div class="canh">
                        <label class="left">餐盒费</label>
                        <label class="right">0</label>
                        <div class="clearfix"></div>
                    </div>
                    <div class="peis">
                        <label class="left">配送费</label>
                        <label class="right">0</label>
                        <div class="clearfix"></div>
                    </div>
                    <div class="manj">
                        <label class="left">立减优惠</label>
                        <label class="right">0</label>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div class="total">
                    <label class="left">总计</label>
                    <label class="right">39</label>
                    <div class="clearfix"></div>
                </div>
                <div style="text-align: right;">
                    <label>顾客已付款</label>
                </div>
                <span>**************************</span>
            </div>
            <div class="section5">
                <label>姓名：小花</label><br>
                <label>地址：北京</label><br>
                <label>电话：67890</label>
            </div>    
            <span>**************************</span>
        </div>
        <script>
            if (LODOP.webskt && LODOP.webskt.readyState == 1) {
                //        alert(1);//准备好
            }

            setTimeout("prn1_print()", 1000);

            function prn1_preview() {
                if (LODOP.webskt && LODOP.webskt.readyState == 1) {
                } else {
                    setTimeout("prn1_preview()", 1000);
                }
                CreateOneFormPage();
                LODOP.PREVIEW();
            }
            function prn1_print() {
                CreateOneFormPage();
                LODOP.PRINT();
            }
            function prn1_printA() {
                CreateOneFormPage();
                LODOP.PRINTA();
            }
            function CreateOneFormPage() {
                LODOP.PRINT_INIT("打印控件功能演示_Lodop功能_表单一");
                LODOP.SET_PRINT_STYLE("FontSize", 18);
                LODOP.SET_PRINT_STYLE("Bold", 1);
                LODOP.ADD_PRINT_TEXT(50, 231, 260, 39, "打印页面部分内容");
                LODOP.ADD_PRINT_HTM(88, 200, 350, 600, document.getElementById("form1").innerHTML);
            }
            function prn2_preview() {
                CreateTwoFormPage();
                LODOP.PREVIEW();
            }
            function prn2_manage() {
                CreateTwoFormPage();
                LODOP.PRINT_SETUP();
            }
            function CreateTwoFormPage() {
                LODOP = getLodop();
                LODOP.PRINT_INIT("打印控件功能演示_Lodop功能_表单二");
                LODOP.ADD_PRINT_RECT(70, 27, 634, 242, 0, 1);
                LODOP.ADD_PRINT_TEXT(29, 236, 279, 38, "页面内容改变布局打印");
                LODOP.SET_PRINT_STYLEA(2, "FontSize", 18);
                LODOP.SET_PRINT_STYLEA(2, "Bold", 1);
                LODOP.ADD_PRINT_HTM(88, 40, 321, 185, document.getElementById("form1").innerHTML);
                LODOP.ADD_PRINT_HTM(87, 355, 285, 187, document.getElementById("form2").innerHTML);
                LODOP.ADD_PRINT_TEXT(319, 58, 500, 30, "注：其中《表单一》按显示大小，《表单二》在程序控制宽度(285px)内自适应调整");
            }
            function prn3_preview() {
                LODOP = getLodop();
                LODOP.PRINT_INIT("打印控件功能演示_Lodop功能_全页");
                LODOP.ADD_PRINT_HTM(0, 0, "100%", "100%", document.documentElement.innerHTML);
                LODOP.PREVIEW();
            }
        </script>
</html>
