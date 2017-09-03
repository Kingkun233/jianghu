<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>-.-</title>
<link rel="stylesheet" href="/jianghu/Public/css/backstage.css">
</head>

<body>
	<br>
	<h3>&nbsp举报列表</h3>
	<div class="details">
		<!--表格-->
		<table class="table" cellspacing="0" cellpadding="0">
			<thead>
				<tr>
					<th>编号</th>
					<th>举报者</th>
					<th>推荐id</th>
					<th>被举报推荐</th>
					<th>违规原因</th>
					<th>举报时间</th>
					<th>状态</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
				<?php if(is_array($report)): foreach($report as $key=>$vo): ?><tr>
					<!--这里的id和for里面的c1 需要循环出来-->
					<td><?php echo ($vo["id"]); ?></td>
					<td><?php echo ($vo["username"]); ?></td>
					<td><a href="<?php echo U('introduce/showdetail');?>?id=<?php echo ($vo["introduce_id"]); ?>"><?php echo ($vo["introduce_id"]); ?></a></td>
					<td><?php echo ($vo["introduce_content"]); ?></td>
					<td><?php echo ($vo["text"]); ?></td>
					<td><?php echo ($vo["time"]); ?></td>
					<td><?php echo ($vo["state"]); ?></td>
					<td align="center">
						<input type="button" value="禁用该推荐"class="btn" onclick="ban(<?php echo ($vo["introduce_id"]); ?>)"> 
						<input type="button"value="忽略该举报" class="btn" onclick="ignore(<?php echo ($vo["id"]); ?>)">
					</td>
				</tr><?php endforeach; endif; ?>
			</tbody>
		</table>
		<div class="pagination"><?php echo ($page); ?></div>
	</div>
</body>
<script type="text/javascript">
	
	function ignore(id){
		window.location="<?php echo U('introduce/ignore');?>"+'?id='+id;
	}
	function ban(id){
		window.location="<?php echo U('introduce/ban');?>"+'?id='+id;	
	}
</script>
</html>