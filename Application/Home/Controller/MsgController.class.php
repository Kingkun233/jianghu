<?php
namespace Home\Controller;
use Think\Controller;
class MsgController extends Controller{
    /**
     * 得到用户未读消息数
     */
    public function getUnreadNum(){
        $User=D('user');
        $where['id']=I('user_id');
        //得到用户未读消息数
        $nums=$User->where($where)->getField('unreadnum');
        if($nums){
            $this->ajaxReturn($nums);
        }
    }
    /**
     * 查看未读消息
     */
    public function checkMsg(){
        $User=D("user");
        $Praise=D('praise');
        $Forward=D('forward');
        $Intro=D('introduce');
        $user_id=I('userid');
        //得到点赞表里的内容
        $praisemsg=$Praise->where(array('owner_id'=>$user_id))->join("left join jianghu_user on jianghu_user.id=jianghu_praise.user_id")->field('jianghu_user.faceurl,jianghu_user.username,jianghu_praise.*')->order('time desc')->select();
        foreach ($praisemsg as $k=>$v){
            $msg['praise'][]=$v;
        }
        //得到推荐表里的内容
        $forwardmsg=$Forward->where(array('owner_id'=>$user_id))->join("left join jianghu_user on jianghu_user.id=jianghu_forward.user_id")->field('jianghu_user.faceurl,jianghu_user.username,jianghu_forward.*')->order('time desc')->select();
        foreach ($forwardmsg as $k=>$v){
            $msg['introduce'][]=$v;
        }
        //把unreadnum置零
        $data['unreadnum']=0;
        $flag=$User->where(array('id'=>$user_id))->save($data);
        dump($msg);die;
        $this->ajaxReturn($msg);
    }
}