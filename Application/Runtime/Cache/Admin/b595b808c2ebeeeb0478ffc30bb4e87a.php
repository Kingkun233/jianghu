<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>江湖后台管理系统1.0</title>

<link rel="stylesheet" type="text/css" href="/jianghu/Public/css/backstage.css" />
</head>

<body>
    <div class="head">
            
            <h3 class="head_text fl">江湖后台管理系统1.0</h3>
    </div>
    <div class="operation_user clearfix">
       
        <div class="link fr">
            <b>欢迎您&nbsp<?php echo ($loginadmin); ?>
            </b>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo U('index/main');?>" class="icon icon_i"target="mainFrame">首页</a><span></span><a href="#" class="icon icon_j">前进</a><span></span><a href="#" class="icon icon_t">后退</a><span></span><a href="#" class="icon icon_n">刷新</a><span></span><a href="<?php echo U('admin/logout');?>" class="icon icon_e">退出</a>
        </div>
    </div>
    <div class="content clearfix">
        <div class="main">
            <!--右侧内容-->
            <div class="cont">
                <div class="title">后台管理</div>
      	 		<!-- 嵌套网页开始 -->         
                <iframe src="<?php echo U('index/main');?>"  frameborder="0" name="mainFrame" width="100%" height="522"></iframe>
                <!-- 嵌套网页结束 -->   
            </div>
        </div>
        <!--左侧列表-->
        <div class="menu">
            <div class="cont">
                <div class="title">管理员</div>
                <ul class="mList">
                    <li>
                        <h3 onclick="show('menu1','change1')" id="change1"><span >+</span>搜索标签管理</h3>
                        <dl id="menu1" style="display:none;">
                        	<dd><a href="<?php echo U('tag/add');?>" target="mainFrame">添加搜索标签</a></dd>
                            <dd><a href="<?php echo U('tag/index');?>" target="mainFrame">搜索标签列表</a></dd>
                        </dl>
                    </li>
                    
                    <li>
                        <h3 onclick="show('menu3','change3')" id="change3" ><span  >+</span>推荐管理</h3>
                        <dl id="menu3" style="display:none;">
                            <dd><a href="<?php echo U('introduce/showIntroduce');?>" target="mainFrame">推荐列表</a></dd>
                        </dl>
                    </li>
                    <li>
                        <h3 onclick="show('menu4','change4')" id="change4"><span >+</span>用户管理</h3>
                        <dl id="menu4" style="display:none;">
                            <dd><a href="<?php echo U('user/index');?>" target="mainFrame">用户列表</a></dd>
                            <dd><a href="<?php echo U('user/showDailyNum');?>" target="mainFrame">每日统计</a></dd>
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
                        <h3 onclick="show('menu6','change6')" id="change6"><span class="menu6">+</span>商户管理</h3>
                        <dl id="menu6" style="display:none;">
                        	<dd><a href="<?php echo U('business/businessList');?>" target="mainFrame">商户列表</a></dd>
                        	<dd><a href="<?php echo U('business/add');?>" target="mainFrame">添加商户</a></dd>
                        	<dd><a href="<?php echo U('business/uncheckList');?>" target="mainFrame">待审核商户</a></dd>
                        </dl>
                    </li>
                    <li>
                        <h3 onclick="show('menu7','change7')" id="change7"><span >+</span>领域管理</h3>
                        <dl id="menu7" style="display:none;">
                        	<dd><a href="<?php echo U('domain/add');?>" target="mainFrame">添加领域</a></dd>
                            <dd><a href="<?php echo U('domain/index');?>" target="mainFrame">领域列表</a></dd>
                        </dl>
                    </li>
                </ul>
            </div>
        </div>

    </div>
    <script type="text/javascript">
    	function show(num,change){
	    		var menu=document.getElementById(num);
	    		var change=document.getElementById(change);
	    		var span =change.firstChild;
	    		if(span.innerHTML=="+"){
	    			span.innerHTML="-";
	    		}else{
	    			span.innerHTML="+";
	    		}
    		   if(menu.style.display=='none'){
    	             menu.style.display='';
    		    }else{
    		         menu.style.display='none';
    		    }
        }
    </script>
</body>
</html>