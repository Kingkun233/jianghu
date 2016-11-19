<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>-.-</title>
<link rel="stylesheet" href="/jianghu/Public/css/backstage.css">
</head>

<body>
	<div class="details">
		<div class="details_operation clearfix">
		</div>
		<!--表格-->
		<table class="table" cellspacing="0" cellpadding="0">
			<thead>
				<tr>
					<th>编号</th>
					<th>海报名称</th>
					<th>发布时间</th>
					<th>状态</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
				<?php if(is_array($poster)): foreach($poster as $key=>$vo): ?><tr>
					<!--这里的id和for里面的c1 需要循环出来-->
					<td><input type="checkbox" id="c1" class="check"><label
						for="c1" class="label"><?php echo ($vo["id"]); ?></label></td>
					<td><?php echo ($vo["title"]); ?></td>
					<td><?php echo ($vo["time"]); ?></td>
					<td><?php echo ($vo["state"]); ?></td>
					<td align="center">
						<input type="button" value="再次运营"
						class="btn" onclick="intime(<?php echo ($vo["id"]); ?>)">
						<input type="button" value="详情"
						class="btn" onclick="detail(<?php echo ($vo["id"]); ?>)">
					</td>
				</tr><?php endforeach; endif; ?>

			</tbody>
		</table>
		<div class="pagination"><?php echo ($page); ?></div>
	</div>
</body>
<script type="text/javascript">
	function intime(id){
		window.location="<?php echo U('poster/intime');?>"+'?id='+id;
	}
	function detail(id){
		window.location="<?php echo U('poster/detail');?>"+'?id='+id;
	}
</script>
</html>