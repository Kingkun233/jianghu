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
 * 登录权限接口预处理，包括接口调用正确性，用户是否登录，用户是否被禁用，用户日登陆统计
 * @param unknown $token
 * @param unknown $apiType 
 * @param unknown $user_id
 * @return array post过来的参数数组
 */
function loginPermitApiPreTreat($apiType){
    //获取json数据并转化为数组
    $postjson=file_get_contents("php://input");
    $post=json_decode($postjson,true);
    $Token=D('token');
    $User=D('user');
    $LoginCount=D('login_count');
    $DailyNum=D('daily_num');
    $token=$post['token'];
    $user_id=$post['user_id'];
    $getType=$post['type'];
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
    //日登陆统计
    $today=date("Y-m-d");
    $where['user_id']=$user_id;
    $where['date']=$today;
    $flag1=$LoginCount->where($where)->find();
    $flag2=$DailyNum->where(array('date'=>$where['date']))->find();
    //查看是不是留存用户，是的话keep++
    $jointime=$User->where(array("id"=>$user_id))->getField("jointime");
    $jointime=date("Y-m-d",strtotime($jointime));
    $yesterday=date("Y-m-d",strtotime("-1 day"));
    if($jointime==$yesterday){
        $DailyNum->where(array("date"=>$today))->setInc("keep");
    }
    if(!$flag1){
        //插入登陆记录表
        $LoginCount->add($where);
        //口碑加一
        $User->where(array('id'=>$user_id))->setInc('praisenum');
        //每日登陆人数加一
        if($DailyNum->where(array("date"=>$today))->select()){
            $DailyNum->where(array("date"=>$today))->setInc("lognum");
        }else {
            $data1['date']=$today;
            $data1['lognum']=1;
            $DailyNum->add($data1);
        }
    }
    return $post;
}
/**
 * 游客接口预处理
 * @param unknown $apiType
 * @return mixed post过来的参数数组
 */
function touristApiPreTreat($apiType){
    //获取json数据并转化为数组
    $postjson=file_get_contents("php://input");
    $post=json_decode($postjson,true);
    //判断是否调用正确接口
    if($post['type']!=$apiType){
        redirect(U("return/returnMsg")."?re=5&type=".$apiType);
    }
    return $post;
}
/**
 * 把二维数组按照某个键排序
 * @param unknown $keyname 指定的键名
 * @param unknown $twoDimensionalArray 该二维数组
 * @param unknown $order 顺序 默认是降序 升序SORT_ASC
 */
function sortTwoDimensionalArrayByKey($keyname,$twoDimensionalArray,$order=SORT_DESC){
    foreach ($twoDimensionalArray as $k => $v) {
            $theKeyArray[] = $v[$keyname];
        }
//         sort($theKeyArray);
        array_multisort($theKeyArray, SORT_ASC, $twoDimensionalArray);
        return $twoDimensionalArray;
}
/**
 * 计算两点之间的距离
 *
 * @param unknown $lng1
 * @param unknown $lat1
 * @param unknown $lng2
 * @param unknown $lat2
 */
function getDistance($latitude1, $longitude1, $latitude2, $longitude2)
{
    $theta = $longitude1 - $longitude2;
    $miles = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
    $miles = acos($miles);
    $miles = rad2deg($miles);
    $miles = $miles * 60 * 1.1515;
    $feet = $miles * 5280;
    $yards = $feet / 3;
    $kilometers = $miles * 1.609344;
    $meters = $kilometers * 1000;
    return round($kilometers, 1);
}

/**检查是不是朋友
 * @param $user_id1
 * @param $user_id2
 */
function is_friend($user_id,$is_friend_id){
    $Friend=D('friend');
    $rows=$Friend->where(['user_id'=>$user_id,'friend_id'=>$is_friend_id])->select();
    if($rows){
        return true;
    }else{
        return false;
    }
}

