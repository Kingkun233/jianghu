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
        if (! checkUserLogin()) {
            // 用户未登陆
            $this->ajaxReturn(2);
        }
        $Domain=D('introduce_domain');
        $User = D('user');
        $Intro = D('introduce');
        $Image = D('introduce_images');
        $username = I('username');
        //$add2为邻域表的数据
        $add2['name']=I('domain');
        $add['text'] = I('text');
        // 得到user_id
        $add['user_id'] = getUidByUsername($username); 
        $add['time'] = date("Y-m-d H:i:s");
        // 获取推荐id
        $img['intro_id'] = $Intro->add($add);
        $add2['introduce_id']=$img['intro_id'];
        if (! $img['intro_id']) {
            // 数据插入失败
            $this->ajaxReturn(1); 
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
                $this->ajaxReturn(1); // 数据插入失败
            }
        }
        $this->ajaxReturn(0); // 推荐插入成功
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
        if (! checkUserLogin()) {
            // 用户未登陆
            $this->ajaxReturn(2);
        }
        $User = D('user');
        $Friend = D('friend');
        $Intro = D('introduce');
        $Image = D('introduce_images');
        $username = I('username');
        $user_id = getUidByUsername($username);
        $fid2 = $Friend->where(array(
            'user_id' => $user_id
        ))
            ->field('friend_id')
            ->select(); // 得到好友id,二维数组
        foreach ($fid2 as $k => $v) { // 二维转一维
            $fid1[] = $v['friend_id'];
        }
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
        dump($showall);
        die();
        $this->ajaxReturn($showall);
    }

    /**
     * 查看用户自己的推荐
     */
    public function showUserIntro()
    {
        if (! checkUserLogin()) {
            // 用户未登陆
            $this->ajaxReturn(2);
        }
        $User = D('user');
        $Image = D('introduce_images');
        $Intro = D('introduce');
        $username = I('username');
        $user_id = getUidByUsername($username);
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
        dump($showall);
        die();
        $this->ajaxReturn($showall);
    }

    /**
     * 点赞推荐
     */
    public function addPraise()
    {
        if (! checkUserLogin()) {
            // 用户未登陆
            $this->ajaxReturn(2);
        }
        $User = D('user');
        $Intro = D('introduce');
        $Praise = D('praise');
        $username = I('username');
        $introduce_id= I('introduce_id');
        // 通过推荐表获取所有者的id
        $owner_id= $Intro->where(array(
            'id' => $introduce_id
        ))->getField('user_id');
        // $data1用于插入praise表
        $data1['introduce_id'] =$introduce_id;
        $data1['user_id'] = getUidByUsername($username);
        $data1['time'] = date('Y-m-d H-i-s');
        $data1['owner_id']=$owner_id;
        // 插入praise表
        $flag1 = $Praise->add($data1);
        //该推荐的用户未读消息加一
        $userid=$Intro->where(array('id'=>$data1['introduce_id']))->getField('user_id');
        $flag4=$User->where(array('id'=>$userid))->setInc('unreadnum');
        // Introduce表的praisenum++
        $flag2 = $Intro->where(array(
            'id' => $introduce_id
        ))->setInc('praisenum');
        // 推荐所有者的口碑++
        $flag3 = $User->where(array('id'=>$owner_id))->setInc('praisenum');
        if ($flag1 && $flag2 && $flag3&&flag4) {
            // 点赞成功
            $this->ajaxReturn(0); 
        }
        // 数据插入失败
        $this->ajaxReturn(1); 
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
        if (! checkUserLogin()) {
            // 用户未登陆
            $this->ajaxReturn(2);
        }
        $User=D('user');
        $Intro = D('introduce');
        $Forward = D('forward');
        $introduce_id = I('introduce_id');
        //推荐所有者的未读消息加一
        $owner_id=$Intro->where(array('id'=>$introduce_id))->getField('user_id');
        $flag4=$User->where(array('id'=>$owner_id))->setInc('unreadnum');
        //得到转采的推荐id 没有就返回0
        $isforward = $Intro->where(array(
            'id' => $introduce_id
        ))->getField('isforward');
        if ($isforward) {
            $introduce_id = $isforward;
        }
        // 转采表的数据
        $data1['user_id'] = I('user_id');
        $data1['introduce_id'] = $introduce_id;
        $data1['time'] = date('Y-m-d H-i-s');
        $data1['owner_id']=$owner_id;
        // 推荐表的数据
        $data2['time'] = $data1['time'];
        $data2['isforward'] = $data1['introduce_id'];
        $data2['text'] = I('comment');
        $data2['user_id'] = $data1['user_id'];
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
        if ($flag && $flag1 && $flag2 && $flag3&&$flag4) {
            // 转采成功
            $this->ajaxReturn(0);
        }
        // 转采失败
        $this->ajaxReturn(1);
    }

    /**
     * 添加评论
     */
    public function addcomment()
    {}
}