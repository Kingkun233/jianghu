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
</br>
</div>
                    <!--表格-->
                    <table class="table" cellspacing="0" cellpadding="0">
                        <thead>
                            <tr>
                                <th>编号</th>
                                <th>商户名</th>
                                <th>商户介绍</th>
                                <th>商户地址</th>
                                <th>商户星级</th>
                                <th>商户状态</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if(is_array($business)): foreach($business as $key=>$vo): ?><tr>
                                <!--这里的id和for里面的c1 需要循环出来-->
                                <td><input type="checkbox" id="c1" class="check"><label for="c1" class="label"><?php echo ($vo["id"]); ?></label></td>
                                <td><?php echo ($vo["name"]); ?></td>
                                <td><?php echo ($vo["discription"]); ?></td>
                                <td><?php echo ($vo["addr"]); ?></td>
                                <td><?php echo ($vo["star"]); ?></td>
                                <td><?php echo ($vo["state"]); ?></td>
                                <td align="center"><input type="button" value="详情" class="btn" onclick="showDetail(<?php echo ($vo["id"]); ?>)">
                                <input type="button" value="编辑" class="btn" onclick="edit(<?php echo ($vo["id"]); ?>)">
                                <input type="button" value="审核通过" class="btn" onclick="check(<?php echo ($vo["id"]); ?>)">
                                </td>
                            </tr><?php endforeach; endif; ?>
                        </tbody>
                    </table>
                    <div class="pagination"><?php echo ($page); ?></div>
                </div>
</body>
<script type="text/javascript">
	function showDetail(id){
		window.location="<?php echo U('business/showdetail');?>"+'?id='+id;
	}
	function edit(id){
		window.location="<?php echo U('business/edit');?>"+'?id='+id;
	}
	function check(id){
		window.location="<?php echo U('business/check');?>"+'?id='+id;
	}
</script>
</html>