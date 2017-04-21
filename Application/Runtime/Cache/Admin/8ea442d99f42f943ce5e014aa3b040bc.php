<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Insert title here</title>

</head>
<body>


	<h3>添加海报</h3>
	<form action="<?php echo U('poster/edit');?>" method="post"
		enctype="multipart/form-data" />

	<table width="70%" border="1" cellpadding="5" cellspacing="0"
		bgcolor="#cccccc">
		<?php if(is_array($rows)): foreach($rows as $key=>$vo): ?><input type="hidden" name="poster_id"value="<?php echo ($vo["id"]); ?>"></input>
		<tr>
			<td align="right">海报标题</td>
			<td><input type="text" name="title" value="<?php echo ($vo["title"]); ?>" /></td>
		</tr>
		<tr>
			<td align="right">海报图片</td>
			<td><img alt="#" src="<?php echo ($vo["posterurl"]); ?>"></td>
		</tr>
		<tr>
			<td align="right">阅读量</td>
			<td><?php echo ($vo["readnum"]); ?></td>
		</tr>
		<tr>
			<td align="right">海报秀米url</td>
			<td><input type="text" name="content_url" value="<?php echo ($vo["content_url"]); ?>" /></td>
		</tr><?php endforeach; endif; ?>
		
		<tr>
			<td colspan="2"><input type="submit" value="修改" /></td>
		</tr>
	</table>
	</form>
</body>
</html>