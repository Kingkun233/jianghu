<?php
namespace Home\Controller;
use Think\Controller;
class PosterController extends Controller {
    /**
     * 返回海报
     */
    public function getPoster() {
        $type=900;
        if($type!=I('type')){
            loginPermitApiPreTreat(responseMsg(5, $type));
        }
        $Poster=D('poster');
        $msg=$Poster->where(array('state'=>0))->select();
        $this->ajaxReturn(responseMsg(0, $type,$msg));
    }
    /**
     * 阅读海报
     */
    public function readPoster(){
        $type=901;
        if($type!=I('type')){
            loginPermitApiPreTreat(responseMsg(5, $type));
        }
        $Poster=D('poster');
        $poster_id=I('poster_id');
        $flag=$Poster->where(array('id'=>$poster_id))->setInc('readnum');
        if($flag){
            $this->ajaxReturn(responseMsg(0, $type));
        }else{
            $this->ajaxReturn(responseMsg(1, $type));
        }
    }
}