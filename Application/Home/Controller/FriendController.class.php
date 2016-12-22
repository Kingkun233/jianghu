<?php
namespace Home\Controller;

use Think\Controller;

class FriendController extends Controller
{

    /**
     * 删除好友
     */
    public function delete()
    {
        $Friend = D('friend');
        $user_id = I('userid');
        $friend_id = I('friendid');
        $where['user_id'] = $user_id;
        $where['friend_id'] = $friend_id;
        $flag = $Friend->where($where)->delete();
        if ($flag) {
            $this->ajaxReturn(0); // 好友删除成功
        } else {
            $this->ajaxReturn(1); // 好友删除失败
        }
    }

    /**
     * 请求添加好友
     */
    public function friendRequest()
    {
        $type = 300;
        $post=loginPermitApiPreTreat($type);
        $User = D('user');
        $Request = D('friend_request');
        $user_id =$post['user_id'];
        $friend_id = $post['friend_id'];
        $add['date'] = date("Y-m-d");
        $add['user_id'] = $user_id;
        $add['friend_id'] = $friend_id;
        // 插入请求表
        $flag1 = $Request->add($add);
        
        if ($flag1 ) {
            $this->ajaxReturn(responseMsg(0, $type));
        } else {
            $this->ajaxReturn(responseMsg(1, $type));
        }
    }

    /**
     * 通过好友请求
     */
    public function accept(){
        $type=302;
        $post=loginPermitApiPreTreat($type);
        $Request=D('friend_request');
        $Friend=D('friend');
        $user_id=$post['user_id'];
        $request_id=$post['request_id'];
        //获得好友id(也就是发起请求的人的id)
        $friend_id=$Request->where(array('id'=>$request_id))->getField('user_id');
        //在好友表中添加好友关系
        $add['user_id']=$user_id;
        $add['friend_id']=$friend_id;
        $add1['user_id']=$friend_id;
        $add1['friend_id']=$user_id;
        $flag1=$Friend->add($add);
        $flag2=$Friend->add($add1);
        //在请求表中删除该记录
        $flag3=$Request->where(array('id'=>$request_id))->delete();
        if($flag1&&$flag2&&$flag3){
            $this->ajaxReturn(responseMsg(0, $type));
        }else{
            $this->ajaxReturn(responseMsg(1, $type));
        }
    }
    /**
     * 获取用户好友列表
     */
    public function getFriendList(){
        $type=301;
        $post=loginPermitApiPreTreat($type);
        $Friend=D('friend');
        $User=D("user");
        $user_id=$post['user_id'];
        $friends2=$Friend->where(array('user_id'=>$user_id))->field("friend_id")->select();
        foreach ($friends2 as $k=>$v){
            $friends[]=$v['friend_id'];
        }
        $where['id']=array("IN",$friends);
        $friends=$User->where($where)->field("id,username,faceurl,praisenum")->select();
        $this->ajaxReturn(responseMsg(0, $type,$friends));
    }    
}