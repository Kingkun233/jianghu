<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Insert title here</title>

</head>
<body>
	<h3>添加海报</h3>
	<form action="<?php echo U('poster/doadd');?>" method="post"
		enctype="multipart/form-data" />
	<table width="70%" border="1" cellpadding="5" cellspacing="0"
		bgcolor="#cccccc">
		<tr>
			<td align="right">海报标题</td>
			<td><input type="text" name="title" placeholder="请输入海报名" /></td>
		</tr>
		<tr>
			<td align="right">海报图片</td>
			<td><input type="file" name="poster" /></td>
		</tr>
		<tr>
			<td align="right">海报内容</td>
			<td>
				<!-- 加载编辑器的容器 --> <script id="container" name="content"
					type="text/plain">
    					</script> 
    					<!-- 配置文件 -->
    					 <script type="text/javascript"
					src="../../../Public/ueditor/ueditor.config.js"></script> <!-- 编辑器源码文件 -->
				<script type="text/javascript"
					src="../../../Public/ueditor/ueditor.all.js"></script> <!-- 实例化编辑器 --> <script
					type="text/javascript">
						var ue = UE.getEditor('container');
					</script>
			</td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" value="添加海报" /></td>
		</tr>

	</table>
	</form>
</body>
</html>