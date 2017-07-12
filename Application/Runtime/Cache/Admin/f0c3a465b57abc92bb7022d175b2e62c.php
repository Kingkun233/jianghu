<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>-.-</title>
<link rel="stylesheet" href="/jianghu/Public/css/backstage.css">
</head>

<body>
	<br>
	<h3>&nbsp用户反馈列表</h3>
	<div class="details">
		<!--表格-->
		<table class="table" cellspacing="0" cellpadding="0">
			<thead>
				<tr>
					<th>编号</th>
					<th>用户id</th>
					<th>用户名称</th>
					<th>反馈时间</th>
					<th>内容</th>
					<th>状态</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
				<?php if(is_array($feedbacks)): foreach($feedbacks as $key=>$vo): ?><tr>
					<!--这里的id和for里面的c1 需要循环出来-->
					<td><?php echo ($vo["id"]); ?></td>
					<td><?php echo ($vo["user_id"]); ?></td>
					<td><?php echo ($vo["username"]); ?></td>
					<td><?php echo ($vo["time"]); ?></td>
					<td><?php echo ($vo["text"]); ?></td>
					<td><?php echo ($vo["state"]); ?></td>
					<td align="center"><input type="button"
						value="处理完成" class="btn" onclick="handle(<?php echo ($vo["id"]); ?>)"></td>
				</tr><?php endforeach; endif; ?>

			</tbody>
		</table>
		<div class="pagination"><?php echo ($page); ?></div>
	</div>
</body>
<script type="text/javascript">
	function handle(id){
		window.location="<?php echo U('user/handle');?>"+'?id='+id;	
	}
</script>
</html>