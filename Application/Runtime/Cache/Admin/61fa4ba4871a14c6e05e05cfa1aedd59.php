<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Insert title here</title>
<link rel="stylesheet" href="/jianghu/Public/css/backstage.css">
</head>
<body>
<h3>推荐详情</h3>

<table  bgcolor="#ddd" class="table">
<?php if(is_array($rows)): foreach($rows as $key=>$vo): ?><tr>
		<td align="right">用户名</td>
		<td><a href="<?php echo U('user/userdetails');?>?username=<?php echo ($username); ?>"><?php echo ($username); ?></a></td>
	</tr>
	<tr>
		<td align="right">推送内容</td>
		<td><?php echo ($vo["text"]); ?></td>
	</tr>
	<tr>
		<td align="right">时间</td>
		<td><?php echo ($vo["time"]); ?></td>
	</tr>
	<tr>
		<td align="right">点赞数</td>
		<td><?php echo ($vo["praisenum"]); ?></td>
	</tr>
	<tr>
		<td align="right">踩数</td>
		<td><?php echo ($vo["opposenum"]); ?></td>
	</tr>
	<tr>
		<td align="right">转载数</td>
		<td><?php echo ($vo["forwardnum"]); ?></td>
	</tr>
	<tr>
		<td align="right">评论数</td>
		<td><?php echo ($vo["commentnum"]); ?></td>
	</tr>
	<tr>
		<td align="right">度数</td>
		<td><?php echo ($vo["degree"]); ?></td>
	</tr><?php endforeach; endif; ?>
	<tr>
		<td align="right">推送图片</td>
		<td>
			<?php if(is_array($image)): foreach($image as $key=>$vo): ?><img src="<?php echo ($vo); ?>" height='60px' padding="2px">&nbsp<?php endforeach; endif; ?>
		</td>
	</tr>
	<tr>
		<td align="right">推送领域</td>
		<td>
			<?php if(is_array($domains)): foreach($domains as $key=>$vo): echo ($vo["name"]); ?>&nbsp<?php endforeach; endif; ?>
		</td>
	</tr>
	<tr>
		<td align="right">查看原创推荐</td>
		<td><a href="<?php echo U('introduce/showdetail');?>?id=<?php echo ($original_intro); ?>"><?php echo ($original_intro); ?></a><?php echo ($msg); ?></td>
	</tr>
</table>
<br>
<a href="<?php echo ($backurl); ?>" class='btn'>返回</a>
</body>
</html>