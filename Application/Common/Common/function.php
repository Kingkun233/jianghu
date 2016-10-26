<?php
/**
 * 封装返回数据
 * @param int $re 状态码
 * @param unknown $type 接口标示
 * @param unknown $msg 返回消息
 * @param unknown $token 可选
 * @return unknown[] 返回封装好的数组
 */
function responseMsg($re,$type,$msg=null){
    return array("re"=>$re,"type"=>$type,"msg"=>$msg);
}
/**
 * 检查用户登录
 * @param unknown $token
 * @return boolean
 */
function checkUserLogin($token,$user_id){
//     dump('halo');die;
   $Token=D("token");
   $where['token']=$token;
   $where['user_id']=$user_id;
   $where['state']=0;
   if($Token->where($where)->find()){
       return true;
   }
   return false;
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
/**
 * 判断是不是同一天
 * @param String $time “Y-m-d H:i:s”格式的时间
 * @return boolean
 */
function isToday($time){
    $time1=strtotime($time);
    if(date("Y-m-d")==date("Y-m-d",$time1)){
        return true;
    }else{
        return false;
    }
}