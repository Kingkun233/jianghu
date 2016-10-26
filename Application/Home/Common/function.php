<?php
/**
 * 检查用户是否被禁用，在登录，添加推荐，转载的时候检查
 * @param unknown $user_id
 * @return boolean
 */
function isban($user_id){
    $User=D("user");
    $Token=D("token");
    if($User->where(array("id"=>$user_id))->getField("isban")){
        //若用户被禁用，该用户的token状态置一
        $Token->where(array("user_id"=>$user_id))->save(array("state"=>1));
        return true;
    }else{
        return false;
    }
}
/**
 * 登录权限接口预处理，包括接口调用正确性，用户是否登录，用户是否被禁用
 * @param unknown $token
 * @param unknown $apiType 
 * @param unknown $user_id
 */
function loginPermitApiPreTreat($apiType){
    $Token=D('token');
    $User=D('user');
    $token=I('token');
    $user_id=I('user_id');
    $getType=I('type');
    //判断是否调用正确的接口
    if($getType!=$apiType){
        redirect(U("return/returnMsg")."?re=5&type=".$apiType);
    }
    //判断是否登录
    $rows=checkUserLogin($token, $user_id);
    if(!$rows){
        redirect(U("return/returnMsg")."?re=2&type=".$apiType);
    }
    //判断用户是否被禁用
    if(isban($user_id)){
        redirect(U("return/returnMsg")."?re=3&type=".$apiType);
    }
}