<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Insert title here</title>
</head>
<body>
	<h3>用户详情</h3>

	<table width="50%" border="1" cellpadding="5" cellspacing="0"
		bgcolor="#ddd">
		<?php if(is_array($rows)): foreach($rows as $key=>$vo): ?><tr>
			<td align="right">用户id</td>
			<td><?php echo ($vo["id"]); ?></td>
		</tr>
		<tr>
			<td align="right">用户名</td>
			<td><?php echo ($vo["username"]); ?></td>
		</tr>
		<tr>
			<td align="right">性别</td>
			<td><?php echo ($vo["sex"]); ?> </td>
		</tr>
		<tr>
			<td align="right">电话</td>
			<td><?php echo ($vo["phonenum"]); ?></td>
		</tr>
		<tr>
			<td align="right">地址</td>
			<td><?php echo ($vo["addr"]); ?></td>
		</tr>
		<tr>
			<td align="right">口碑</td>
			<td><?php echo ($vo["praisenum"]); ?> </td>
		</tr>
		<tr>
			<td align="right">生日</td>
			<td><?php echo ($vo["birthday"]); ?> </td>
		</tr>
		<tr>
			<td align="right">个人介绍</td>
			<td><?php echo ($description); ?></td>
		</tr>
		<tr>
			<td align="right">头像</td>
			<td><img alt="" src="<?php echo ($vo["faceurl"]); ?>"></td>
		</tr>
		<tr>
			<td align="right">注册时间</td>
			<td><?php echo ($vo["jointime"]); ?> </td>
		</tr>
		<tr>
			<td align="right">email</td>
			<td><?php echo ($vo["email"]); ?></td>
		</tr>
		<tr>
			<td colspan="2"><a href="<?php echo U('search/searchByUid');?>?user_id=<?php echo ($vo["id"]); ?>">查看该用户所有推荐</a></td>
		</tr><?php endforeach; endif; ?>
	</table>
	<a href="<?php echo ($backurl); ?>" class='btn'>返回</a>
</body>
</html>