<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>-.-</title>
<link rel="stylesheet" href="/jianghu/Public/css/backstage.css">
</head>
<body>
</br>
<h3>&nbsp推荐列表</h3>
<div class="details">
<div align="right">
<form method="post" action="http://localhost/jianghu/index.php/admin/search/doaction">
    <select name="cate" class="btn">
        <option value="1">按用户名查找</option>
        <option value="2">按推荐关键字查找</option>
        <option value="3">按度数查找</option>
        <option value="4">按时间查找</option>
        <option value="5">按标签查找</option>
        <option value="6">按范围查找</option>
    </select>
    <input type=“text‘ name="searchkey" class="search" placeholder="请输入搜索条件"></input>
    <input class="btn" type="submit" />
</form>
</br>
</div>
                    <!--表格-->
                    <table class="table" cellspacing="0" cellpadding="0">
                        <thead>
                            <tr>
                                <th>编号</th>
                                <th>用户</th>
                                <th>推送内容</th>
                                <th>时间</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if(is_array($introduce)): foreach($introduce as $key=>$vo): ?><tr>
                                <!--这里的id和for里面的c1 需要循环出来-->
                                <td><input type="checkbox" id="c1" class="check"><label for="c1" class="label"><?php echo ($vo["id"]); ?></label></td>
                                <td><a href="<?php echo U('user/userdetails');?>?username=<?php echo ($vo["username"]); ?>"}><?php echo ($vo["username"]); ?></a></td>
                                <td><?php echo ($vo["text"]); ?></td>
                                <td><?php echo ($vo["time"]); ?></td>
                                <td align="center"><input type="button" value="详情" class="btn" onclick="showDetail(<?php echo ($vo["id"]); ?>)"><input type="button" value="删除" class="btn"  onclick="delMsg(<?php echo ($vo["id"]); ?>)">
                                	
                                </td>
                            </tr><?php endforeach; endif; ?>
                        </tbody>
                    </table>
                    <div class="pagination"><?php echo ($page); ?></div>
                </div>
</body>
<script type="text/javascript">
	function showDetail(id){
		window.location="<?php echo U('introduce/showdetail');?>"+'?id='+id;
	}
	function delMsg(id){
			if(window.confirm("您确定要删除吗？删除之后不可以恢复哦！！！")){
				window.location="<?php echo U('introduce/del');?>"+'?id='+id;
			}
	}
</script>
</html>