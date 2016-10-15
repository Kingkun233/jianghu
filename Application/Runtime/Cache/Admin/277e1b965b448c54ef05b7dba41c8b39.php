<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>-.-</title>
<link rel="stylesheet" href="/jianghu/Public/css/backstage.css">
</head>

<body>
<div class="details">
<div class="details_operation clearfix">
                        </div>
                        <h3>查看每日注册量</h3>
                        <br>
                    <!--表格-->
                    <table class="table" cellspacing="0" cellpadding="0">
                        <thead>
                            <tr>
                                <th >日期</th>
                                <th >注册人数</th>
                                <th >登陆人数</th>
                                <th >评论人数</th>
                                <th >点赞人数</th>
                                <th >留存率</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if(is_array($joinnum)): foreach($joinnum as $key=>$vo): ?><tr>
                                <td><?php echo ($vo["date"]); ?></td>
                                <td><?php echo ($vo["joinnum"]); ?></td>
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