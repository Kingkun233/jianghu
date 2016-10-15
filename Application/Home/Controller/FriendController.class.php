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
        $data1['user_id']=I('user_id');
        $data1['friend_id']=I('friend_id');
        $data2['user_id']=$data1['friend_id'];
        $data2['friend_id']=$data1['user_id'];
        $flag2=$Friend->add($data1);
        $flag1=$Friend->add($data2);
        if($flag1&&$flag2){
            $this->ajaxReturn(0);//好友添加成功
        }
        $this->ajaxReturn(1);//好友添加失败
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
    /**
     * showfriends
     */
    
}