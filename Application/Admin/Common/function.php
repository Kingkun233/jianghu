<?php
/**
 * 分页处理
* @param unknown $Page 传入Page实例
*/
function page($Page=null){
    $Page -> setConfig('header','共%TOTAL_ROW%条');
    $Page -> setConfig('first','首页');
    $Page -> setConfig('last','尾页');
    $Page -> setConfig('prev','上一页');
    $Page -> setConfig('next','下一页');
    $Page -> setConfig('link','indexpagenumb');//pagenumb 会替换成页码
    $Page -> setConfig('theme','%HEADER% %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');
    return $Page->show();
}

/**
 * 检查管理员是否登录
 * @param unknown $adminname
 * @return boolean
 */
function checkAdminLogin(){
    if(!session('name')){
        redirect(U('admin/login') ,2 ,'未登录' );
    }
}
/**
 * 检查登录状态
 * @param unknown $username
 * @return boolean
 */
function checkLogin($username){
    if(session($username)){
        return true;
    }else {
        return false;
    }
}
/**
 * 检查两人是否朋友关系
 * @param unknown $username
 * @param unknown $friendname
 * @return boolean
 */
function checkIsFriend($username,$friendname){
    $User=D('user');
    $Friend=D('friend');
    $flag1=$User->where(array('username'=>$username))->select();
    $flag2=$User->where(array('username'=>$friendname))->select();
    if(!empty($flag1)&&!empty($flag2)){
        $uid=$User->where(array('username'=>$username))->getField('id');
        $fid=$User->where(array('username'=>$friendname))->getField('id');
        $dbfid=$Friend->where(array('uid'=>$uid))->field('fid')->select();
        foreach ($dbfid as $k=>$v){
            if($fid==$v['fid']){
                return true;
            }
        }
        return false;

    }else {
        return false;//不存在此用户
    }
}


/**
 * 多维数组转一维
 * @param unknown $arr
 * @return boolean|unknown[]
 */
function arr_foreach ($arr)
{
    static $tmp=array();
    if (!is_array ($arr))
    {
        return false;
    }
    foreach ($arr as $val )
    {
        if (is_array ($val))
        {
            arr_foreach ($val);
        }
        else
        {
            $tmp[]=$val;
        }
    }
    return $tmp;
}