<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Insert title here</title>
</head>
<body>
<h3>添加用户</h3>

<table width="70%" border="1" cellpadding="5" cellspacing="0" bgcolor="#cccccc">
	<tr>
		<td align="right">用户名</td>
		<td><input type="text" name="username" value="<?php echo ($username); ?>"/></td>
	</tr>
	<tr>
		<td align="right">推送内容</td>
		<td><textarea  name="content" /><?php echo ($content); ?></textarea></td>
	</tr>
	<tr>
		<td align="right">推送图片</td>
		<td>
			<?php if(is_array($image)): foreach($image as $key=>$vo): ?><img src="<?php echo ($vo); ?>" height='60px' padding="2px">&nbsp<?php endforeach; endif; ?>
		</td>
	</tr>
</table>
<a href="<?php echo ($backurl); ?>" class='btn'>返回</a>
</body>
</html>