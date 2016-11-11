<?php
namespace Home\Controller;
use Think\Controller;
class MsgController extends Controller{
    /**
     * 得到用户未读消息数(包括总未读数，转采，评论，赞，申请好友的未读消息数)
     */
    public function getUnreadNum(){
        $type=600;
        loginPermitApiPreTreat($type);
        $User=D('user');
        $Forward=D('forward');
        $Praise=D('praise');
        $Comment=D('comment');
        $Friend=D('friend_request');
        $user_id=I('user_id');
        $where['owner_id']=$user_id;
        $where['state']=0;
        //得到转采未读消息数
        $forwardmsgnum=$Forward->where($where)->count();
        //得到点赞未读消息数
        $praisemsgnum=$Praise->where($where)->count();
        //得到评论未读消息数
        $commentmsgnum=$Comment->where($where)->count();
        //得到好友申请未读消息
        $wherefriend['state']=0;
        $wherefriend['friend_id']=$user_id;
        $requestmsgnum=$Friend->where($wherefriend)->count();
        //得到用户总未读消息数
        $allmsgnum=$forwardmsgnum+$praisemsgnum+$requestmsgnum+$commentmsgnum;
        //整合返回数据
        $msg['allmsgnum']="".$allmsgnum;
        $msg['forwardmsgnum']=$forwardmsgnum;
        $msg['praisemsgnum']=$praisemsgnum;
        $msg['commentmsgnum']=$commentmsgnum;
        $msg['requestmsgnum']=$requestmsgnum;
        $this->ajaxReturn(responseMsg(0, $type,$msg));
    }
    /**
     * 查看点赞未读消息数
     */
    public function checkPraiseMsg(){
        $type=601;
        loginPermitApiPreTreat($type);
        $User=D('user');
        $Praise=D('praise');
        $user_id=I('user_id');
        //把自己给自己点赞的排除
        $where1['owner_id']=$user_id;
        $where1['user_id']=array('neq',$user_id);
        $msg=$Praise->where($where1)->order("time desc")->select();
        //整合头像和名字
        foreach ($msg as $k=>$v){
            $msg[$k]['name']=$User->where(array('id'=>$v['user_id']))->getField('username');
            $msg[$k]['face']=$User->where(array('id'=>$v['user_id']))->getField('faceurl');
            unset($msg[$k]['owner_id']);
        }
        //该用户点赞信息的state全部置一
        $where['owner_id']=$user_id;
        $where['state']=0;
        $Praise->where($where)->save(array('state'=>1));
        $this->ajaxReturn(responseMsg(0, $type,$msg));
    }
    /**
     * 查看转载未读消息数
     */
    public function checkForwardMsg(){
        $type=602;
        loginPermitApiPreTreat($type);
        $User=D('user');
        $Forward=D('forward');
        $user_id=I('user_id');
        //条件：不是自己转载自己,被转载推荐的owner是user_id
        $where1['user_id']=array('neq',$user_id);
        $where1['owner_id']=$user_id;
        $msg=$Forward->where($where1)->order("time desc")->select();
        //整合转载人的头像和名字
        foreach ($msg as $k=>$v){
            $msg[$k]['name']=$User->where(array('id'=>$v['user_id']))->getField('username');
            $msg[$k]['face']=$User->where(array('id'=>$v['user_id']))->getField('faceurl');
            unset($msg[$k]['original_id']);
            unset($msg[$k]['original_id2']);
            unset($msg[$k]['original_id3']);
            unset($msg[$k]['original_id4']);
            unset($msg[$k]['original_id5']);
            unset($msg[$k]['original_id6']);
            unset($msg[$k]['original_id7']);
            unset($msg[$k]['owner_id']);
            unset($msg[$k]['degree']);
        }
        //该用户点赞信息的state全部置一
        $where['owner_id']=$user_id;
        $where['state']=0;
        $Forward->where($where)->save(array('state'=>1));
        $this->ajaxReturn(responseMsg(0, $type,$msg));
    }
    /**
     * 查看评论未读消息
     */
    public function checkCommentMsg(){
        $type=603;
        loginPermitApiPreTreat($type);
        $User=D('user');
        $Comment=D('comment');
        $user_id=I('user_id');
        //把自己给自己评论的排除
        $where1['owner_id']=$user_id;
        $where1['user_id']=array('neq',$user_id);
        $msg=$Comment->where($where1)->order("time desc")->select();
        //整合头像和名字
        foreach ($msg as $k=>$v){
            $msg[$k]['name']=$User->where(array('id'=>$v['user_id']))->getField('username');
            $msg[$k]['face']=$User->where(array('id'=>$v['user_id']))->getField('faceurl');
            unset($msg[$k]['owner_id']);
        }
        //该用户点赞信息的state全部置一
        $where['owner_id']=$user_id;
        $where['state']=0;
        $Comment->where($where)->save(array('state'=>1));
        $this->ajaxReturn(responseMsg(0, $type,$msg));
    }
    /**
     * 查看评论未读消息
     */
    public function checkRequestMsg(){
        $type=604;
        loginPermitApiPreTreat($type);
        $User=D('user');
        $Request=D('friend_request');
        $user_id=I('user_id');
        $where1['friend_id']=$user_id;
        $msg=$Request->where($where1)->order("date desc")->select();
        //整合头像和名字
        foreach ($msg as $k=>$v){
            $msg[$k]['name']=$User->where(array('id'=>$v['user_id']))->getField('username');
            $msg[$k]['face']=$User->where(array('id'=>$v['user_id']))->getField('faceurl');
            unset($msg[$k]['friend_id']);
        }
        //该用户点赞信息的state全部置一
        $where['friend_id']=$user_id;
        $where['state']=0;
        $Request->where($where)->save(array('state'=>1));
        $this->ajaxReturn(responseMsg(0, $type,$msg));
    }
}