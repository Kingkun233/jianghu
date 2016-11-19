<?php
namespace Home\Controller;
use Think\Controller;
class PosterController extends Controller {
    /**
     * 返回海报
     */
    public function getPoster() {
        $type=900;
        if($type!=900){
            loginPermitApiPreTreat(responseMsg(5, $type));
        }
        $Poster=D('poster');
        $msg=$Poster->where(array('state'=>0))->select();
        $this->ajaxReturn(responseMsg(0, $type,$msg));
    }
}