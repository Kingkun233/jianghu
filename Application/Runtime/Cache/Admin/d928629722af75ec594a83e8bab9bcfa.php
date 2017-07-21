<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>-.-</title>
</script>
<link rel="stylesheet" href="/jianghu/Public/css/backstage.css">
</head>
<body>
	</br>
	<h3>&nbsp版本列表</h3>
	<div class="details">
		<div align="right">
			</br>
		</div>
		<!--表格-->
		<table class="table" cellspacing="0" cellpadding="0">
			<thead>
				<tr>
					<th>编号</th>
					<th>版本名</th>
					<th>更新描述</th>
					<th>时间</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
				<?php if(is_array($update)): foreach($update as $key=>$vo): ?><tr>
					<!--这里的id和for里面的c1 需要循环出来-->
					<td align="center"><?php echo ($vo["id"]); ?></td>
					<td align="center"><?php echo ($vo["version"]); ?></td>
					<td align="center"><?php echo ($vo["description"]); ?></td>
					<td align="center"><?php echo ($vo["time"]); ?></td>
					<td align="center">
						<input type="button" value="下载" class="btn"onclick="download(<?php echo ($vo["id"]); ?>)">
					</td>
				</tr><?php endforeach; endif; ?>
			</tbody>
		</table>
		<div class="pagination"><?php echo ($page); ?></div>
	</div>
</body>
<script type="text/javascript">
function download(id){
	window.location="<?php echo U('update/download');?>"+'?id='+id;
}
</script>
</html>