<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Insert title here</title>

</head>
<body>
	<h3>添加新版本</h3>
	<form action="<?php echo U('update/doadd');?>" method="post"
		enctype="multipart/form-data" />
	<table width="70%" border="1" cellpadding="5" cellspacing="0"
		bgcolor="#cccccc">
		<tr>
			<td align="right">版本号</td>
			<td><input type="text" name="version" placeholder="请输入版本号" /></td>
		</tr>
		<tr>
			<td align="right">版本描述</td>
			<td><input type="text" name="description" placeholder="请输入版本描述" /></td>
		</tr>
		<tr>
			<td align="right">请上传最新版apk</td>
			<td><input type="file" name="upload" /></td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" value="添加版本" /></td>
		</tr>

	</table>
	</form>
</body>
</html>