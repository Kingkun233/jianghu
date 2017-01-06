<?php
namespace Home\Controller;
use Think\Controller;
class ReturnController extends Controller{
    /**
     * 用来返回需要登录权限的接口的预处理的返回信息
     */
    function returnMsg(){
        $re=(int)I('re');
        $type=I('type');
        $this->ajaxReturn(responseMsg($re,$type));
    }
}