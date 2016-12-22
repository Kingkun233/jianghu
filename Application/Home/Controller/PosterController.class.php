<?php
namespace Home\Controller;
use Think\Controller;
class PosterController extends Controller {
    /**
     * 返回海报
     */
    public function getPoster() {
        $type=900;
        $post=touristApiPreTreat($type);
        if($post['user_id']){
            $post=null;
            $post=loginPermitApiPreTreat($type);
        }
        $Poster=D('poster');
        $PosterPraise=D('poster_praise');
        $user_id=$post['user_id'];
        $poster_id=$post['poster_id'];
        $msg=$Poster->where(array('state'=>0))->select();
        //整合是否已经点过赞
        $where['user_id']=$user_id;
//         dump($PosterPraise->where($where)->select());die;
        foreach ($msg as $k=>$v){
            $where['poster_id']=$v['id'];
            if($PosterPraise->where($where)->select()){
                $msg[$k]['ispraised']=1;
            }else{
                $msg[$k]['ispraised']=0;
            }
        }
        $this->ajaxReturn(responseMsg(0, $type,$msg));
    }
    /**
     * 阅读海报
     */
    public function readPoster(){
        $type=901;
        $post=touristApiPreTreat($type);
        $Poster=D('poster');
        $poster_id=$post['poster_id'];
        $flag=$Poster->where(array('id'=>$poster_id))->setInc('readnum');
        if($flag){
            $this->ajaxReturn(responseMsg(0, $type));
        }else{
            $this->ajaxReturn(responseMsg(1, $type));
        }
    }
    /**
     * 点赞海报
     */
    public function praisePoster(){
        $type=902;
        $post=loginPermitApiPreTreat($type);
        $Poster=D('poster');
        $PosterPraise=D('poster_praise');
        $poster_id=$post['poster_id'];
        //插入海报点赞表
        $add_posterpraise['user_id']=$post['user_id'];
        $add_posterpraise['poster_id']=$poster_id;
        $flag1=$PosterPraise->add($add_posterpraise);
        //海报点赞数加一
        $flag2=$Poster->where(array('id'=>$poster_id))->setInc("praisenum");
        $this->ajaxReturn(responseMsg(0, $type));
    }
}