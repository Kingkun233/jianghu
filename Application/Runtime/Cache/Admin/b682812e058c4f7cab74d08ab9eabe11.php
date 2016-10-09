<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>-.-</title>
<link rel="stylesheet" href="/jianghuadmin/Public/css/backstage.css">
</head>

<body>
<div class="details">
                    <div class="details_operation clearfix">
                        <div class="bui_select">
                            <input type="button" value="添&nbsp;&nbsp;加" class="add"  onclick="addAdmin()">
                        </div>
                            
                    </div>
                    <!--表格-->
                    <table class="table" cellspacing="0" cellpadding="0">
                        <thead>
                            <tr>
                                <th width="15%">编号</th>
                                <th width="25%">管理员名称</th>
                                <th width="30%">管理员邮箱</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if(is_array($admin)): foreach($admin as $key=>$vo): ?><tr>
                                <!--这里的id和for里面的c1 需要循环出来-->
                                <td><input type="checkbox" id="c1" class="check"><label for="c1" class="label"><?php echo ($vo["id"]); ?></label></td>
                                <td><?php echo ($vo["name"]); ?></td>
                                <td><?php echo ($vo["email"]); ?></td>
                                <td align="center"><input type="button" value="修改" class="btn" onclick="editAdmin(<?php echo ($vo["id"]); ?>)"><input type="button" value="删除" class="btn"  onclick="delAdmin(<?php echo ($vo["id"]); ?>)"></td>
                            </tr><?php endforeach; endif; ?>
                         
                        </tbody>
                    </table>
                    <div class="pagination"><?php echo ($page); ?></div>
                </div>
</body>
<script type="text/javascript">

	function addAdmin(){
		window.location="<?php echo U('admin/add');?>";	
	}
	function editAdmin(id){
			window.location="<?php echo U('admin/edit');?>"+'?id='+id;
	}
	function delAdmin(id){
			if(window.confirm("您确定要删除吗？删除之后不可以恢复哦！！！")){
				window.location="<?php echo U('admin/del');?>"+'?id='+id;
			}
	}
</script>
</html>