<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>后台管理系统1.0</title>

    <link rel="stylesheet" type="text/css" href="/jianghu/Public/css/backstage.css" />
    <script type="text/javascript">

        //刷新上级页面
        function back() {
            window.mainFrame.history.back();
        }

        function forward() {
            window.mainFrame.history.forward();
        }

        function refresh() {
            window.mainFrame.location.reload();
        }
    </script>
    <!--小红点-->
    <style>
        .red-point {
            position: relative;
        }

        .red-point::before {
            content: " ";
            border: 3px solid red; /*设置红色*/
            border-radius: 3px; /*设置圆角*/
            position: absolute;
            z-index: 1000;
            right: 0%;
            margin-right: 0px;
            margin-top: 4px;
        }
    </style>
    <!--ajax-->
    <script src="http://libs.baidu.com/jquery/1.10.2/jquery.min.js">
    </script>
    <script type="text/javascript">
        function GetJsonData() {
            var json = {
                "type": 200
            };
            return json;
        }

        function check_unread() {
            $.ajax({
                type: 'post',
                url: 'http://121.42.203.85/jianghu/index.php/admin/user/check_unread',
                contentType: "application/json; charset=utf-8",
                data: JSON.stringify(GetJsonData()),
                dataType: "json",
                success: function (resp) {
                    var userRedPoint = $('#user-redpoint');
                    var feedbackRedPoint = $('#feedback-redpoint');
                    var reportRedPoint = $('#report-redpoint');
                    var businessRedPoint = $('#business-redpoint');
                    var businessCheckRedPoint = $('#business-check-redpoint');
                    var introduceRedPoint = $('#intro-redpoint');
                    var introduceReportRedPoint = $('#intro-report-redpoint');
                    if (resp['feedback']) {
                        feedbackRedPoint.hide();
                    }
                    if (resp['report']) {
                        reportRedPoint.hide();
                    }
                    if (resp['report'] && resp['feedback']) {
                        userRedPoint.hide();
                    }
                    if (resp['business']) {
                        businessRedPoint.hide();
                        businessCheckRedPoint.hide();
                    }
                    if(resp['introduce']){
                        introduceRedPoint.hide();
                        introduceReportRedPoint.hide();
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    console.log(XMLHttpRequest.status);
                    console.log(XMLHttpRequest.readyState);
                    console.log(errorThrown);
                }
            });
        }

        check_unread();
    </script>
</head>

<body>
<div class="head">

    <h3 class="head_text fl">后台管理系统1.0</h3>
</div>
<div class="operation_user clearfix">

    <div class="link fr">
        <b>欢迎您&nbsp<?php echo ($loginadmin); ?>
        </b>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo U('index/main');?>" class="icon icon_i"
                                       target="mainFrame">首页</a><span></span><a class="icon icon_j"
                                                                                onclick="back()">后退</a><span></span><a
            class="icon icon_t" onclick="forward()">前进</a><span></span><a class="icon icon_n" onclick="refresh()">刷新</a><span></span><a
            href="<?php echo U('admin/logout');?>" class="icon icon_e">退出</a>
    </div>
</div>
<div class="content clearfix">
    <div class="main">
        <!--右侧内容-->
        <div class="cont">
            <div class="title">后台管理</div>
            <!-- 嵌套网页开始 -->
            <iframe id="myframe" src="<?php echo U('index/main');?>" frameborder="0" name="mainFrame" width="100%"
                    height="522"></iframe>
            <!-- 嵌套网页结束 -->
        </div>
    </div>
    <!--左侧列表-->
    <div class="menu">
        <div class="cont">
            <div class="title">管理模块</div>
            <ul class="mList">
                <li>
                    <h3 onclick="show('menu1','change1')" id="change1"><span>+</span>搜索标签管理</h3>
                    <dl id="menu1" style="display:none;">
                        <dd><a href="<?php echo U('tag/add');?>" target="mainFrame">添加搜索标签</a></dd>
                        <dd><a href="<?php echo U('tag/index');?>" target="mainFrame">搜索标签列表</a></dd>
                    </dl>
                </li>

                <li>
                    <h3 onclick="show('menu3','change3')" id="change3"><span>+</span>推荐管理<span class="red-point"
                                                                                               display="block"
                                                                                               id="intro-redpoint">&nbsp</span></h3>
                    <dl id="menu3" style="display:none;">
                        <dd><a href="<?php echo U('introduce/showIntroduce');?>" target="mainFrame">推荐列表</a></dd>
                        <dd><a href="<?php echo U('introduce/reportedList');?>" target="mainFrame">举报列表<span class="red-point"
                                                                                                  display="block"
                                                                                                  id="intro-report-redpoint">&nbsp&nbsp&nbsp</span></a></dd>
                    </dl>
                </li>
                <li>
                    <h3 onclick="show('menu4','change4')" id="change4"><span>+</span>用户管理<span class="red-point"
                                                                                               display="block"
                                                                                               id="user-redpoint">&nbsp</span>
                    </h3>
                    <dl id="menu4" style="display:none;">
                        <dd><a href="<?php echo U('user/index');?>" target="mainFrame">用户列表</a></dd>
                        <dd><a href="<?php echo U('user/showDailyNum');?>" target="mainFrame">每日统计</a></dd>
                        <dd><a href="<?php echo U('user/feedbackList');?>" target="mainFrame">用户反馈<span class="red-point"
                                                                                             display="block"
                                                                                             id="feedback-redpoint">&nbsp&nbsp&nbsp</span></a>
                        </dd>
                        <dd><a href="<?php echo U('user/reportedList');?>" target="mainFrame">举报列表<span class="red-point"
                                                                                             display="block"
                                                                                             id="report-redpoint">&nbsp&nbsp&nbsp</span></a>
                        </dd>
                    </dl>
                </li>
                <li>
                    <h3 onclick="show('menu5','change5')" id="change5"><span class="menu5">+</span>管理员管理</h3>
                    <dl id="menu5" style="display:none;">
                        <dd><a href="<?php echo U('admin/add');?>" target="mainFrame">添加管理员</a></dd>
                        <dd><a href="<?php echo U('admin/index');?>" target="mainFrame">管理员列表</a></dd>
                    </dl>
                </li>
                <li>
                    <h3 onclick="show('menu6','change6')" id="change6"><span class="menu6">+</span>商户管理<span
                            class="red-point"
                            display="block" id="business-redpoint">&nbsp</span></h3>
                    <dl id="menu6" style="display:none;">
                        <dd><a href="<?php echo U('business/businessList');?>" target="mainFrame">商户列表</a></dd>
                        <dd><a href="<?php echo U('business/add');?>" target="mainFrame">添加商户</a></dd>
                        <dd><a href="<?php echo U('business/uncheckList');?>" target="mainFrame">待审核商户<span class="red-point"
                                                                                                 display="block"
                                                                                                 id="business-check-redpoint">&nbsp&nbsp&nbsp</span></a>
                        </dd>
                    </dl>
                </li>
                <li>
                    <h3 onclick="show('menu7','change7')" id="change7"><span>+</span>领域管理</h3>
                    <dl id="menu7" style="display:none;">
                        <dd><a href="<?php echo U('domain/add');?>" target="mainFrame">添加领域</a></dd>
                        <dd><a href="<?php echo U('domain/index');?>" target="mainFrame">领域列表</a></dd>
                    </dl>
                </li>
                <li>
                    <h3 onclick="show('menu8','change8')" id="change8"><span>+</span>海报管理</h3>
                    <dl id="menu8" style="display:none;">
                        <dd><a href="<?php echo U('poster/add');?>" target="mainFrame">添加海报</a></dd>
                        <dd><a href="<?php echo U('poster/index');?>" target="mainFrame">海报列表</a></dd>
                        <dd><a href="<?php echo U('poster/overtimeList');?>" target="mainFrame">已过期海报</a></dd>
                    </dl>
                </li>
                <li>
                    <h3 onclick="show('menu9','change9')" id="change9"><span>+</span>版本管理</h3>
                    <dl id="menu9" style="display:none;">
                        <dd><a href="<?php echo U('update/index');?>" target="mainFrame">版本列表</a></dd>
                        <dd><a href="<?php echo U('update/add');?>" target="mainFrame">添加新版本</a></dd>
                    </dl>
                </li>
                <li>
                    <h3 onclick="show('menu10','change10')" id="change10"><span>+</span>首页大图管理</h3>
                    <dl id="menu10" style="display:none;">
                        <dd><a href="<?php echo U('homepage/index');?>" target="mainFrame">首页大图列表</a></dd>
                        <dd><a href="<?php echo U('homepage/add');?>" target="mainFrame">添加新首页大图</a></dd>
                    </dl>
                </li>
            </ul>
        </div>
    </div>

</div>
<script type="text/javascript">
    function show(num, change) {
//        if(num)
        var menu = document.getElementById(num);
        var change = document.getElementById(change);
        var span = change.firstChild;
        if (span.innerHTML == "+") {
            span.innerHTML = "-";
        } else {
            span.innerHTML = "+";
        }
        if (menu.style.display == 'none') {
            menu.style.display = '';
        } else {
            menu.style.display = 'none';
        }
    }
</script>
</body>
</html>