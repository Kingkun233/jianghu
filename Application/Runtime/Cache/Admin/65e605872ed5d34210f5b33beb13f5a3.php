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
                    <!--表格-->
                    <table class="table" cellspacing="0" cellpadding="0">
                        <thead>
                            <tr>
                                <th >编号</th>
                                <th >用户名称</th>
                                <th >性别</th>
                                <th >用户邮箱</th>
                                <th >用户手机</th>
                                <th >注册时间</th>
                                <th >头像</th>
                                <th >状态</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if(is_array($user)): foreach($user as $key=>$vo): ?><tr>
                                <!--这里的id和for里面的c1 需要循环出来-->
                                <td><input type="checkbox" id="c1" class="check"><label for="c1" class="label"><?php echo ($vo["id"]); ?></label></td>
                                <td><?php echo ($vo["username"]); ?></td>
                                <td><?php echo ($vo["sex"]); ?></td>
                                <td><?php echo ($vo["email"]); ?></td>
                                <td><?php echo ($vo["phonenum"]); ?></td>
                                <td><?php echo ($vo["jointime"]); ?></td>
                                <td><img height="30px" alt="" src="<?php echo ($vo["faceurl"]); ?>"></td>
                                <td></td>
                                <td align="center">
                                	<input type="button" value="禁用" class="btn"  onclick="ban(<?php echo ($vo["id"]); ?>)">
                                	<input type="button" value="解除禁用" class="btn"  onclick="unban(<?php echo ($vo["id"]); ?>)">
                                </td>
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