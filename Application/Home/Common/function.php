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
