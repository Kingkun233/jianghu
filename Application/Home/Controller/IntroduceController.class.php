<?php
namespace Home\Controller;

use Think\Controller;

class IntroduceController extends Controller
{

    /**
     * 添加推荐
     */
    public function add()
    {
        $type=201;
        loginPermitApiPreTreat($type);
        $Domain=D('introduce_domain');
        $User = D('user');
        $Intro = D('introduce');
        $Image = D('introduce_images');
        $Forward=D('forward');
        $user_id = I('user_id');
        //$add2为邻域表的数据
        $add2['name']=I('domain');
        $add['text'] = I('text');
        // 得到user_id
        $add['user_id'] = $user_id;
        $add['time'] = date("Y-m-d H:i:s");
        //推荐度数为1
        $add['degree']=1;
        // 插入推荐表，获取推荐id
        $img['intro_id'] = $Intro->add($add);
        $add2['introduce_id']=$img['intro_id'];
        if (! $img['intro_id']) {
            // 数据插入失败
            $this->ajaxReturn(responseMsg(1, $type));
        }
        //若不存在，插入邻域表
        $Domain->add($add2);
        // 上传图片
        $imageinfo = imageUpload();
        $img['imageurl'] = $imageinfo['url'];
        $img['imagepath'] = $imageinfo['path'];
        foreach ($img['imageurl'] as $k => $v) {
            $add1['introduce_id'] = $img['intro_id'];
            $add1['imageurl'] = $v;
            $add1['imagepath'] = $img['imagepath'][$k];
            $flag2 = $Image->add($add1); // 插入图片到数据库
            if (! $flag2) {
                $this->ajaxReturn(responseMsg(1, $type));// 数据插入失败
            }
        }
        //自己转发自己
        $add3['user_id']=$add['user_id'];
        $add3['introduce_id']=$add2['introduce_id'];
        $add3['original_id']=$add2['introduce_id'];
        $add3['time']=date("Y-m-d H:i:s");
        $add3['owner_id']=$add['user_id'];
        $add3['degree']=1;
        $Forward->add($add3);
        $this->ajaxReturn(responseMsg(0, $type));// 推荐插入成功
    }

    /**
     * 删除推荐
     */
    public function del()
    {
        if (! checkUserLogin()) {
            // 用户未登陆
            $this->ajaxReturn(2);
        }
        $Intro = D('introduce');
        $Image = D('introduce_images');
        $intro_id = I('id');
        $Domain=D('introduce_domain');
        $where['introduce_id'] = $intro_id;
        if (! imageDel($Image, $where, 'imagepath')) {
            // 图片删除失败
            $this->ajaxReturn(1); 
        }
        //删除该推荐的领域
        $flag3=$Domain->where(array('introduce_id'=>$intro_id))->delete();
        $flag1 = $Image->where(array(
            'introduce_id' => $intro_id
        ))->delete();
        $flag2 = $Intro->where(array(
            'id' => $intro_id
        ))->delete();
        if (! ($flag1 && $flag2&&$flag3)) {
            // 推荐删除失败
            $this->ajaxReturn(1); 
        }
        // 推荐删除成功
        $this->ajaxReturn(0); 
    }

    /**
     * 返回好友推荐内容
     */
    public function showFriendIntro()
    {
        $type=202;
        loginPermitApiPreTreat($type);
        $User = D('user');
        $Friend = D('friend');
        $Intro = D('introduce');
        $Image = D('introduce_images');
        $user_id = I('user_id');
        $fid2 = $Friend->where(array(
            'user_id' => $user_id
        ))
            ->field('friend_id')
            ->select(); // 得到好友id,二维数组
//             dump($fid2);die;
        foreach ($fid2 as $k => $v) { // 二维转一维
            $fid1[] = $v['friend_id'];
        }
//         dump($fid1);die;
        $contents = $Intro->order('time desc')->select();
        foreach ($contents as $k => $v) {
            if (in_array($v['user_id'], $fid1)) {
                $img = array(); // $img归零
                $show = array(); // $show置null
                $show['id'] = $v['id'];
                $show['friend_id'] = $v['user_id'];
                $show['friendname'] = $User->where(array(
                    'id' => $v['user_id']
                ))->getField('username'); // 得到朋友名字
                $show['text'] = $v['text']; // 文字内容
                $show['time'] = $v['time'];
                $show['praisenum'] = $v['praisenum'];
                $show['forwardnum'] = $v['forwardnum'];
                $show['image'] = $Image->getIntroImg($v['id']);
                // 如果是转载
                if ($v['isforward']) {
                    $show['isforward'] = $Intro->where(array(
                        'id' => $v['isforward']
                    ))->find();
                    $show['isforward']['image'] = $Image->getIntroImg($v['isforward']);
                }
                $showall[] = $show;
            }
        }
        $this->ajaxReturn(responseMsg(0, 202,$showall));
    }

    /**
     * 查看用户自己的推荐
     */
    public function showUserIntro()
    {
        $type=203;
        loginPermitApiPreTreat($type);
        $User = D('user');
        $Image = D('introduce_images');
        $Intro = D('introduce');
        $user_id = I('user_id');
        $contents = $Intro->order('time desc')->select();
        // dump($contents);die;
        foreach ($contents as $k => $v) {
            if ($v['user_id'] == $user_id) {
                $img = array(); // $img归零
                $show['id'] = $v['id'];
                $show['user_id'] = $v['user_id']; // 用户id
                                                  // 如果是转载
                if ($v['isforward']) {
                    $show['isforward'] = $Intro->where(array(
                        'id' => $v['isforward']
                    ))->find();
                    $show['isforward']['image'] = $Image->getIntroImg($v['isforward']);
                }
                $show['text'] = $v['text']; // 文字内容
                $show['time'] = $v['time']; // 推荐创建时间
                $show['praisenum'] = $v['praisenum'];
                $show['forwardnum'] = $v['forwardnum'];
                $show['image'] = $Image->getIntroImg($v['id']);
                $showall[] = $show;
            }
        }
        $this->ajaxReturn(responseMsg(0, 203,$showall));
    }

    /**
     * 点赞推荐
     */
    public function addPraise()
    {
        $type=204;
        //登录权限接口预处理，包括接口调用正确性，用户是否登录，用户是否被禁用
        loginPermitApiPreTreat($type);
        $User = D('user');
        $Intro = D('introduce');
        $Praise = D('praise');
        $Num=D("daily_num");
        $introduce_id= I('introduce_id');
        $user_id=I('user_id');
        // 通过推荐表获取所有者的id
        $owner_id= $Intro->where(array(
            'id' => $introduce_id
        ))->getField('user_id');
        //每日点赞人数加一
        $today=date("Y-m-d");
        $times=$Praise->where(array("user_id"=>$user_id))->field("time")->select();
        $count=0;
        foreach ($times as $k=>$v){
            //如果该用户在这一天有点过赞，count=1
            if(isToday($v["time"])){
                $count=1;break;
            }
        }
        if(!$count){
            $Num->where(array("date"=>$today))->setInc("praisenum");
        }
        // $data1用于插入praise表
        $data1['introduce_id'] =$introduce_id;
        $data1['user_id'] = $user_id;
        $data1['time'] = date('Y-m-d H:i:s');
        $data1['owner_id']=$owner_id;
        // 插入praise表
        $flag1 = $Praise->add($data1);
        //该推荐的用户未读消息加一
        $flag4=$User->where(array('id'=>$owner_id))->setInc('unreadnum');
        // Introduce表的praisenum++
        $flag2 = $Intro->where(array(
            'id' => $introduce_id
        ))->setInc('praisenum');
        // 推荐所有者的口碑++
        $flag3 = $User->where(array('id'=>$owner_id))->setInc('praisenum');
       
        if ($flag1 && $flag2 && $flag3&&$flag4) {
            // 点赞成功
            $this->ajaxReturn(responseMsg(0, $type)); 
        }
        // 数据插入失败
        $this->ajaxReturn(responseMsg(1, $type)); 
    }

    /**
     * 查看点赞了这条推荐的用户
     */
    public function showWhoPraise()
    {
        if (! checkUserLogin()) {
            // 用户未登陆
            $this->ajaxReturn(2);
        }
        $User = D('user');
        $Intro = D('introduce');
        $Praise = D('praise');
        $introduce_id = I('introduce_id');
        //获取点赞该推荐的用户id和点赞记录id
        $praiseinfo = $Praise->where(array(
            'introduce_id' => $introduce_id
        ))
            ->field('user_id,id,time')
            ->order('time desc')
            ->select();
        // 给praiseinfo添加上username和faceurl
        foreach ($praiseinfo as $k => $v) {
            $praiseinfo[$k]['faceurl'] = $User->where(array(
                'id' => $v['user_id']
            ))->getField('faceurl');
            $praiseinfo[$k]['username']=$User->where(array(
                'id' => $v['user_id']
            ))->getField('username');
        }
        dump($praiseinfo);die();
        $this->ajaxReturn($praiseinfo);
    }

    /**
     * 删除点赞
     */
    public function delPraise()
    {
        if (! checkUserLogin()) {
            // 用户未登陆
            $this->ajaxReturn(2);
        }
        $User=D('user');
        $Praise = D('praise');
        $Intro = D('introduce');
        $praise_id = I('praise_id');
        // 获取推荐id
        $introduce_id = $Praise->where(array(
            'id' => $praise_id
        ))->getField('introduce_id');
        //该推荐所有者的口碑减一
        $where['id']=$Intro->where(array('id'=>$introduce_id))->getField('user_id');
        $User->where($where)->setDec('praisenum');
        // 删除点赞表中的记录
        $flag1 = $Praise->where(array(
            'id' => $praise_id
        ))->delete();
        // 推荐的点赞数减一
        $flag2 = $Intro->where(array(
            'id' => $introduce_id
        ))->setDec('praisenum');
        // dump($flag2);
        if ($flag1 && $flag2) {
            $this->ajaxReturn(0); // 删除点赞成功
        }
        $this->ajaxReturn(1); // 删除点赞失败
    }

    /**
     * 转采推荐
     */
    public function forward()
    {
        $type=205;
        loginPermitApiPreTreat($type);
        $User=D('user');
        $Intro = D('introduce');
        $Forward = D('forward');
        $Friend=D("friend");
        $introduce_id = I('introduce_id');
        $user_id=I('user_id');
        //推荐所有者的未读消息加一
            //owner_id并不一定指原创的作者id 谁转载了谁就owner
            //原创的推荐id存在introduce表的isforward字段中
        $owner_id=$Intro->where(array('id'=>$introduce_id))->getField('user_id');
        $flag4=$User->where(array('id'=>$owner_id))->setInc('unreadnum');
        //得到转采的推荐id 没有就返回0
        $isforward = $Intro->where(array(
            'id' => $introduce_id
        ))->getField('isforward');
        if ($isforward) {
            //如果该推荐时转载的，introduce_id1为原创推荐的id
            $introduce_id1 = $isforward;
        }else{
            //若转载的就是原创推荐
            $introduce_id1=$introduce_id;
        }
        // 转采表的数据
        $data1['user_id'] = $user_id;
            //转载表的introduce_id为上一位转载的推荐的id，不是原创推荐的id
        $data1['introduce_id'] = $introduce_id;
        $data1['time'] = date('Y-m-d H:i:s');
        $data1['owner_id']=$owner_id;
        $data1['original_id']=$introduce_id1;
        // 推荐表的数据
        $data2['time'] = $data1['time'];
            //isforward放的是原创推荐的id
        $data2['isforward'] = $introduce_id1;
        $data2['text'] = I('comment');
        $data2['user_id'] = $data1['user_id'];
         //度数逻辑
             //获得被转发推荐的度数a
        $degree=$Intro->where(array("id"=>$introduce_id))->getField("degree");
            //degree大于一的时候才需要判断，若degree转载的时候为一，就直接加一
            if($degree>1){
                //在forward表找到1.度数小于当前度数的2.和转发者是朋友的3.原创推荐id和forward表中的original_id一样的
                $where1['degree']=array("lt",$degree);
                $where1['original_id']=array("eq",$introduce_id1);
                $friends=$Friend->where(array("user_id"=>$data1['user_id']))->field("friend_id")->select();
                foreach ($friends as $k=>$v){
                    $friend[]=$v["friend_id"];
                }
                $where1['user_id']=array("in",$friend);
                $rows=$Forward->where($where1)->select();
                if(!$rows){
                    //如果没有找到，则度数为被转载推荐的度数加一
                    $degree=$degree+1;
                    $data1["degree"]=$degree;
                    $data2["degree"]=$degree;
                }else{
                    //如果找到记录，度数为这些记录的最小的度数加一
                        //得到这些记录中最小的度数
                    $min=10000;
                    foreach ($rows as $k=>$v){
                        if($v['degree']<$min){
                            $min=$v['degree'];
                        }
                    }
                    $data2["degree"]=$min+1;
                    $data1["degree"]=$min+1;
                }
            }else{
                $degree=$degree+1;
                $data2["degree"]=$degree;
                $data1["degree"]=$degree;
            }
        // 插入推荐表
        $flag = $Intro->add($data2);
        // 插入转采表
        $flag1 = $Forward->add($data1);
        // 推荐表转采数加一
        $flag2 = $Intro->where(array(
            'id' => $data1['introduce_id']
        ))->setInc('forwardnum');
        //User表口碑加一
        $where['id']=$Intro->where(array('id'=>$introduce_id))->getField('user_id');
        $flag3=$User->where($where)->setInc('praisenum',3);
//         dump(array($flag,$flag1,$flag2,$flag3,$flag4));
        if ($flag && $flag1 && $flag2 && $flag3&&$flag4) {
            // 转采成功
            $this->ajaxReturn(responseMsg(0, $type));
        }
        // 转采失败
        $this->ajaxReturn(responseMsg(1, $type));
    }

    /**
     * 添加评论
     */
    public function addcomment(){
        $type=206;
        loginPermitApiPreTreat($type);
        $Comment=D("comment");
        $Num=D("daily_num");
        $Intro=D("introduce");
        $User=D("user");
        $data['user_id']=I("user_id");
        $data['introduce_id']=I("introduce_id");
        $data['content']=I("content");
        $data['time']=date("Y-m-d H:i:s");
        //推荐所有者未读消息加一
            //根据introduce_id获得owner_id
        $owner_id=$Intro->where(array("id"=>$data['introduce_id']))->getField("user_id");
        $flag1=$User->where(array("id"=>$owner_id))->setInc("unreadnum");
        //每日评论人数加一
        $today=date("Y-m-d");
        $times=$Comment->where(array("user_id"=>$data['user_id']))->field("time")->select();
        $count=0;
        foreach ($times as $k=>$v){
            //如果该用户在这一天有评论过，count=1
            if(isToday($v["time"])){
                $count=1;break;
            }
        }
        if(!$count){
            $Num->where(array("date"=>$today))->setInc("commentnum");
        }
        //数据插入comment表
        $flag2=$Comment->add($data);
        //推荐评论数加一
        $Intro->where(array('id'=>$data['introduce_id']))->setInc("commentnum");
        if($flag1&&$flag2){
            $this->ajaxReturn(responseMsg(0, $type));
        }else {
            $this->ajaxReturn(responseMsg(1, $type));
        }
    }
}