<?php
namespace Home\Controller;
use Think\Controller;
class DomainController extends Controller{
    /**
     * 返回热门邻域
     */
    public function returnDomain(){
        $type=500;
        $post=touristApiPreTreat($type);
        $Domain=D('domain');
        $msg=$Domain->select();
        $this->ajaxReturn(responseMsg(0, $type,$msg));
    }
}