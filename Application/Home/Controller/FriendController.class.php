<?php
namespace Home\Controller;

use Think\Controller;

class FriendController extends Controller
{

    /**
     * 添加好友关系
     */
    public function add()
    {
        if (! checkUserLogin()) {
            // 用户未登陆
            $this->ajaxReturn(2);
        }
        $Friend = D('friend');
        $data1['user_id'] = I('user_id');
        $data1['friend_id'] = I('friend_id');
        $data2['user_id'] = $data1['friend_id'];
        $data2['friend_id'] = $data1['user_id'];
        $flag2 = $Friend->add($data1);
        $flag1 = $Friend->add($data2);
        if ($flag1 && $flag2) {
            $this->ajaxReturn(0); // 好友添加成功
        }
        $this->ajaxReturn(1); // 好友添加失败
    }

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
        loginPermitApiPreTreat($type);
        $User = D('user');
        $Request = D('friend_request');
        $user_id = I('user_id');
        $friend_id = I('friend_id');
        $add['date'] = date("Y-m-d");
        $add['user_id'] = $user_id;
        $add['friend_id'] = $friend_id;
        // 插入请求表
        $flag1 = $Request->add($add);
        // 被请求者未读消息加一
        $flag2 = $User->where(array(
            'id' => $friend_id
        ))->setInc('unreadnum');
        if ($flag1 && $flag2) {
            $this->ajaxReturn(responseMsg(0, $type));
        } else {
            $this->ajaxReturn(responseMsg(1, $type));
        }
    }

    /**
     * 查看好友请求
     */
    public function checkRequest()
    {
        $type = 301;
        loginPermitApiPreTreat($type);
        $User = D('user');
        $Request = D('friend_request');
        $user_id = I('user_id');
        //查表
        $msg=$Request->where(array(
            'friend_id' => $user_id
        ))
            ->table("jianghu_friend_request r")
            ->join("left join jianghu_user u on u.id=r.user_id")
            ->field("u.username,u.faceurl,r.*")
            ->order('date desc')
            ->select();
        $this->ajaxReturn(responseMsg(0, $type,$msg));
    }
    /**
     * 通过好友请求
     */
    public function accept(){
        $type=302;
        loginPermitApiPreTreat($type);
        $Request=D('friend_request');
        $Friend=D('friend');
        $user_id=I('user_id');
        $request_id=I('request_id');
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
}