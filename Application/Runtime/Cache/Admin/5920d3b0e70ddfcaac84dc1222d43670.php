<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>-.-</title>
<link rel="stylesheet" href="/jianghu/Public/css/backstage.css">
</head>

<body>
<br>
<h3>&nbsp每日统计</h3>
<div class="details">
                    <!--表格-->
                    <table class="table" cellspacing="0" cellpadding="0">
                        <thead>
                            <tr>
                                <th >日期</th>
                                <th >注册人数</th>
                                <th >登陆人数</th>
                                <th >点赞人数</th>
                                <th >评论人数</th>
                                <th >留存率</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if(is_array($num)): foreach($num as $key=>$vo): ?><tr>
                                <td><?php echo ($vo["date"]); ?></td>
                                <td><?php echo ($vo["joinnum"]); ?></td>
                                <td><?php echo ($vo["lognum"]); ?></td>
                                <td><?php echo ($vo["praisenum"]); ?></td>
                                <td><?php echo ($vo["commentnum"]); ?></td>
                                <td><?php echo ($vo["stay"]); ?>%</td>
                            </tr><?php endforeach; endif; ?>
                        </tbody>
                    </table>
                    <div class="pagination">
                    	<?php echo ($page); ?>
                    </div>
                </div>
</body>
<script type="text/javascript">
	
	function unban(id){
		window.location="<?php echo U('user/unban');?>"+'?id='+id;
	}
	function ban(id){
		window.location="<?php echo U('user/ban');?>"+'?id='+id;	
	}
</script>
</html>