<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Insert title here</title>
</head>
<body>
<h3>修改搜索标签</h3>
<form action="<?php echo U('tag/doedit');?>" method="post">
<table width="70%" border="1" cellpadding="5" cellspacing="0" bgcolor="#cccccc">
	<tr>
		<td align="right">标签名称</td>
		<td><input type="text" name="name" value="<?php echo ($name); ?>"/></td>
	</tr>
	<input type="hidden" name="id" value="<?php echo ($id); ?>"/>
	<tr>
		<td colspan="2"><input type="submit"  value="修改标签"/></td>
	</tr>

</table>
</form>
</body>
</html>