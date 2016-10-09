<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Insert title here</title>
</head>
<body>
<h3>添加用户</h3>
<form action="<?php echo U('user/doadd');?>" method="post" enctype="multipart/form-data" >
<table width="70%" border="1" cellpadding="5" cellspacing="0" bgcolor="#cccccc">
	<tr>
		<td align="right">用户名称</td>
		<td><input type="text" name="username" placeholder="请输入用户名称"/></td>
	</tr>
	<tr>
		<td align="right">用户密码</td>
		<td><input type="password" name="password" /></td>
	</tr>
	<tr>
		<td align="right">用户邮箱</td>
		<td><input type="text" name="email" placeholder="请输入用户邮箱"/></td>
	</tr>
	<tr>
		<td align="right">用户头像</td>
		<td><input type="file" name="photo" /></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit"  value="添加用户"/></td>
	</tr>

</table>
</form>
</body>
</html>