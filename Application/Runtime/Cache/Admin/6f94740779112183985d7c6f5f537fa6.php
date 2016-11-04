<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Insert title here</title>
<link rel="stylesheet" href="/jianghu/Public/css/backstage.css">
</head>
<body>
	<br>
	<h3>&nbsp编辑商户信息</h3>
	<br>
	<form method="post" action="<?php echo U('business/doedit');?>"enctype="multipart/form-data">
	 <table bgcolor="#ddd" class="table">
		<?php if(is_array($rows)): foreach($rows as $key=>$vo): ?><tr>
			<td align="right">商户名字</td>
			<td><input name='name' type='text' value="<?php echo ($vo["name"]); ?>"></input></td>
		</tr>
		<tr>
			<td align="right">商户地址</td>
			<td><input name='addr' type='text' value="<?php echo ($vo["addr"]); ?>"></input></td>
		</tr>
		<tr>
			<td align="right">商户介绍</td>
			<td><input name='discription' type='text'
				value="<?php echo ($vo["discription"]); ?>"></input></td>
		</tr>
		<tr>
			<td align="right">加入江湖的日期</td>
			<td><?php echo ($vo["joindate"]); ?></td>
		</tr>
		<tr>
			<td align="right">联系电话</td>
			<td><input name='phone' type='text' value="<?php echo ($vo["phone"]); ?>"></input></td>
		</tr>
		<tr>
			<td align="right">推荐人</td>
			<td><?php echo ($username); ?></td>
		</tr>

		<tr>
			<td align="right">logo</td>
			<td><img src="<?php echo ($vo["logourl"]); ?>" height='60px' padding="2px">&nbsp
				<input name='logo' type='file'></input></td>
		</tr>
		<tr>
			<td align="right">星级</td>
			<td><input name='star' type='text' value="<?php echo ($vo["star"]); ?>"></input></td>
		</tr>
		<input type="hidden" name="id" value=<?php echo ($vo["id"]); ?>></input>
		<tr>
			<td colspan="2"><input type='submit' class='btn'></input></td>
		</tr><?php endforeach; endif; ?>
	</table>
	</form>
	<br>
	<a href="<?php echo ($backurl); ?>" class='btn'>返回</a>
</body>
</html>