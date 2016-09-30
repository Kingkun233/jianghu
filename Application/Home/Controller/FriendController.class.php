<?php
namespace Home\Controller;
use Think\Controller;
class FriendController extends Controller{
    /**
     * 添加好友关系
     */
    public function add(){
    if(!checkUserLogin()){
            //用户未登陆
            $this->ajaxReturn(2);
        }
        $Friend=D('friend');
        $data['user_id']=I('userid');
        $data['friend_id']=I('friendid');
        if(!$Friend->add($data)){
            $this->ajaxReturn(1);//好友添加失败
        }
        $this->ajaxReturn(0);//好友添加成功
    }
    /**
     * 删除好友
     */
    public function delete(){
        $Friend=D('friend');
        $user_id=I('userid');
        $friend_id=I('friendid');
        $where['user_id']=$user_id;
        $where['friend_id']=$friend_id;
        $flag=$Friend->where($where)->delete();
        if($flag){
            $this->ajaxReturn(0);//好友删除成功
        }else {
            $this->ajaxReturn(1);//好友删除失败
        }
    }
    
}