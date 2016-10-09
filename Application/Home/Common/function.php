<?php
/**
 * 检查用户是否登录
 */
function checkUserLogin(){
    if(!session('username')){
       return false;//用户未登录
    }
    return true;
}
/**
 * 检查用户是否存在
 * @param unknown $username
 * @return boolean
 */
function checkUserExist($username){
    $User=D('user');
    $flag=$User->where(array('username'=>$username))->select();
    if($flag){
        return true;
    }else {
        return false;
    }
}
/**
 * 图片上传
 * @return 图片url和path的二维数组
 */
function imageUpload(){
    $upload = new \Think\Upload();// 实例化上传类
    $upload->maxSize   =     3145728 ;// 设置附件上传大小3M
    $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
    $upload->rootPath = './Uploads/';
    $upload->savePath  =     ""; // 设置附件上传目录
    $upload->saveName = array('uniqid','');
    // 上传文件
    $info   =   $upload->upload();
//     dump($upload->getError());die;
    foreach ($info as $k=>$v){
        $imageinfo['url'][]="http://localhost".__ROOT__.'/Uploads/'.date('Y-m-d')."/".$info[$k]['savename'];
        $imageinfo['path'][]='./Uploads/'.date('Y-m-d')."/".$info[$k]['savename'];
    }
    return $imageinfo;
}
/**
 * 删除图片
 * @param unknown $Album 操作的表对象
 * @param unknown $where 条件
 * @param unknown $fieldname 字段名
 * @return boolean 成功返回true,否则为false
 */
function imageDel($Album,$where,$fieldname){
    $images=$Album->where($where)->select();
//     dump($images);
    foreach ($images as $k=>$v){
//         var_dump($v[$fieldname]);
        $flag=unlink($v[$fieldname]);
        if(!$flag){
            return false;
        }
    }
    return true;
}
/**
 * 通过username得到uid
 * @param unknown $username
 * @return mixed|NULL|unknown|string[]|unknown[]|object
 */
function getUidByUsername($username){
    $User=D('user');
    $uid=$User->where(array('username'=>$username))->getField('id');
    return $uid;
}
/**
 * 通过uid得到username
 * @param unknown $uid
 * @return unknown
 */
function getUsernameByUId($uid){
    $User=D('user');
    $username=$User->where(array('id'=>$uid))->getField('username');
    return $username;
}