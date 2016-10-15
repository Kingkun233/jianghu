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
                        <div class="bui_select">
                            <input type="button" value="添&nbsp;&nbsp;加" class="add"  onclick="addTag()">
                        </div>
                            
                    </div>
                    <!--表格-->
                    <table class="table" cellspacing="0" cellpadding="0">
                        <thead>
                            <tr>
                                <th>编号</th>
                                <th>搜索标签名称</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if(is_array($tag)): foreach($tag as $key=>$vo): ?><tr>
                                <!--这里的id和for里面的c1 需要循环出来-->
                                <td><input type="checkbox" id="c1" class="check"><label for="c1" class="label"><?php echo ($vo["id"]); ?></label></td>
                                <td><?php echo ($vo["name"]); ?></td>
                                <td align="center"><input type="button" value="修改" class="btn" onclick="editTag(<?php echo ($vo["id"]); ?>)"><input type="button" value="删除" class="btn"  onclick="delTag(<?php echo ($vo["id"]); ?>)"></td>
                            </tr><?php endforeach; endif; ?>
                         
                        </tbody>
                    </table>
                    <div class="pagination"><?php echo ($page); ?></div>
                </div>
</body>
<script type="text/javascript">

	function addTag(){
		window.location="<?php echo U('tag/add');?>";	
	}
	function editTag(id){
			window.location="<?php echo U('tag/edit');?>"+'?id='+id;
	}
	function delTag(id){
			if(window.confirm("您确定要删除吗？删除之后不可以恢复哦！！！")){
				window.location="<?php echo U('tag/del');?>"+'?id='+id;
			}
	}
</script>
</html>