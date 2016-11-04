<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Insert title here</title>
<link rel="stylesheet" href="/jianghu/Public/css/backstage.css">
</head>
<body>
	<h3>商户详情</h3>

	<table bgcolor="#ddd" class="table">
		<?php if(is_array($rows)): foreach($rows as $key=>$vo): ?><tr>
			<td align="right">商户名字</td>
			<td><?php echo ($vo["name"]); ?></td>
		</tr>
		<tr>
			<td align="right">商户地址</td>
			<td><?php echo ($vo["addr"]); ?></td>
		</tr>
		<tr>
			<td align="right">商户介绍</td>
			<td><?php echo ($vo["discription"]); ?></td>
		</tr>
		<tr>
			<td align="right">商户状态</td>
			<td><?php echo ($vo["state"]); ?></td>
		</tr>
		<tr>
			<td align="right">加入江湖的日期</td>
			<td><?php echo ($vo["joindate"]); ?></td>
		</tr>
		<tr>
			<td align="right">联系电话</td>
			<td><?php echo ($vo["phone"]); ?></td>
		</tr>
		<tr>
			<td align="right">推荐人</td>
			<td><?php echo ($username); ?></td>
		</tr>

		<tr>
			<td align="right">logo</td>
			<td><img src="<?php echo ($vo["logourl"]); ?>" height='60px' padding="2px">&nbsp
			</td>
		</tr>
		<tr>
			<td align="right">星级</td>
			<td><?php echo ($vo["star"]); ?>&nbsp</td>
		</tr><?php endforeach; endif; ?>
	</table>

	<br>

	<a href="<?php echo ($backurl); ?>" class='btn'>返回</a>
</body>
</html>