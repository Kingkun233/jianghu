<?php
namespace Home\Controller;

use Think\Controller;

class IntroduceController extends Controller
{
    /**
     * 上传图片数组得到图片数组
     */
    public function getImageUrl(){
        $type=211;
        if($type!=I('type')){
            $this->ajaxReturn(responseMsg(5, $type));
        }
        $image = imageUpload();
        //         dump($image);die;
        $msg['imageurl'] = $image['url'];
        if($image){
            $this->ajaxReturn(responseMsg(0, $type,$msg));
        }else{
            $this->ajaxReturn(responseMsg(1, $type));
        }
    }
    /**
     * 添加推荐
     */
    public function add()
    {
        $type = 201;
        loginPermitApiPreTreat($type);
        $IntroDomain = D('introduce_domain');
        $Domain = D('domain');
        $User = D('user');
        $Intro = D('introduce');
        $Image = D('introduce_images');
        $Forward = D('forward');
        $Friend = D('friend');
        $Busi=D('business');
        $user_id = I('user_id');
        // $add2为邻域表的数据
        $domain_id = I('domain_id');
        $add2['domain'] = $Domain->where(array(
            'id' => $domain_id
        ))->getField("name");
        $business_id=I('business_id');
        $add['business_id']=$business_id;
        $add['business_name']=$Busi->where(array('id'=>$business_id))->getField('name');
        $add['business_addr']=I('business_addr');
        $add['text'] = I('text');
        // 得到user_id
        $add['user_id'] = $user_id;
        $add['time'] = date("Y-m-d H:i:s");
        // 推荐度数为1
        $add['degree'] = 1;
        // 插入推荐表，获取推荐id
        $img['intro_id'] = $Intro->add($add);
        $add2['introduce_id'] = $img['intro_id'];
        if (! $img['intro_id']) {
            // 数据插入失败
            $this->ajaxReturn(responseMsg(1, $type));
        }
        // 若不存在，插入邻域表
        $IntroDomain->add($add2);
        // 添加图片url
        foreach (I('imageurl') as $k => $v) {
            $add1['introduce_id'] = $img['intro_id'];
            $add1['imageurl'] = $v;
            $add1['imagepath'] = ".".strstr($v, "/Uploads");
            $flag2 = $Image->add($add1); // 插入图片到数据库
            if (! $flag2) {
                $this->ajaxReturn(responseMsg(1, $type)); // 数据插入失败
            }
        }
        
        // 自己转发自己
        $add3['user_id'] = $add['user_id'];
        $add3['introduce_id'] = $add2['introduce_id'];
        $add3['original_id'] = $add2['introduce_id'];
        $add3['time'] = date("Y-m-d H:i:s");
        $add3['owner_id'] = $add['user_id'];
        $add3['degree'] = 1;
        $Forward->add($add3);
        // 该用户的朋友的未读推荐数加一
        $friends2 = $Friend->where(array(
            'user_id' => $user_id
        ))->select();
        foreach ($friends2 as $k => $v) {
            $friends[] = $v['friend_id'];
        }
        $where_friend['id'] = array(
            "IN",
            $friends
        );
        $User->where($where_friend)->setInc("unreadnum");
        // 用户口碑加一
        $User->where(array(
            'id' => $user_id
        ))->setInc('praisenum');
        // 推荐插入成功
        $this->ajaxReturn(responseMsg(0, $type));
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
        $IntroDomain = D('introduce_domain');
        $where['introduce_id'] = $intro_id;
        if (! imageDel($Image, $where, 'imagepath')) {
            // 图片删除失败
            $this->ajaxReturn(1);
        }
        // 删除该推荐的领域
        $flag3 = $IntroDomain->where(array(
            'introduce_id' => $intro_id
        ))->delete();
        $flag1 = $Image->where(array(
            'introduce_id' => $intro_id
        ))->delete();
        $flag2 = $Intro->where(array(
            'id' => $intro_id
        ))->delete();
        if (! ($flag1 && $flag2 && $flag3)) {
            // 推荐删除失败
            $this->ajaxReturn(1);
        }
        // 推荐删除成功
        $this->ajaxReturn(0);
    }

    /**
     * 返回圈子的推荐
     */
    public function showFriendIntro()
    {
        $type = 202;
        loginPermitApiPreTreat($type);
        $User = D('user');
        $Friend = D('friend');
        $Intro = D('introduce');
        $Image = D('introduce_images');
        $IntroDomain = D('introduce_domain');
        $user_id = I('user_id');
        $from = I('from');
        $length = 10;
        $fid2 = $Friend->where(array(
            'user_id' => $user_id
        ))
            ->field('friend_id')
            ->select();
        // 得到好友id
        foreach ($fid2 as $k => $v) {
            $fid1[] = $v['friend_id'];
        }
        $where['user_id'] = array(
            'IN',
            $fid1
        );
        $contents = $Intro->where($where)
            ->order('time desc')
            ->limit($from, $length)
            ->select();
        foreach ($contents as $k => $v) {
            
            // 得到朋友名字
            $contents[$k]['friendname'] = $User->where(array(
                'id' => $v['user_id']
            ))->getField('username');
            // 整合朋友头像
            $contents[$k]['friendface'] = $User->where(array(
                'id' => $v['user_id']
            ))->getField('faceurl');
            // 整合推荐图片
            $contents[$k]['image'] = $Image->getIntroImg($v['id']);
            // 整合推荐领域
            $contents[$k]['domain'] = $IntroDomain->where(array(
                'introduce_id' => $v['id']
            ))->getField('domain');
            // 如果是转载
            if ($v['isforward']) {
                // 得到原创推荐记录
                $contents[$k]['isforward'] = $Intro->where(array(
                    'id' => $v['isforward']
                ))->find();
                $originaluser_id = $contents[$k]['isforward']['user_id'];
                // 得到原创者名字
                $contents[$k]['isforward']['username'] = $User->where(array(
                    'id' => $originaluser_id
                ))->getField('username');
                // 整合原创者头像
                $contents[$k]['isforward']['userface'] = $User->where(array(
                    'id' => $originaluser_id
                ))->getField('faceurl');
                // 整合推荐图片
                $contents[$k]['isforward']['image'] = $Image->getIntroImg($v['isforward']);
                // 整合推荐领域
                $contents[$k]['isforward']['domain'] = $IntroDomain->where(array(
                    'introduce_id' => $v['isforward']
                ))->getField('domain');
            }
        }
        //该用户的未读推荐数置零
        $User->where(array('id'=>$user_id))->save(array('unreadnum'=>0));
        // 分页返回
        $resp = responseMsg(0, 202, $contents);
        $resp['from'] = $from;
        $resp['length'] = $length;
        $this->ajaxReturn($resp);
    }

    /**
     * 查看用户自己的推荐
     */
    public function showUserIntro()
    {
        $type = 203;
        loginPermitApiPreTreat($type);
        $User = D('user');
        $Image = D('introduce_images');
        $Intro = D('introduce');
        $IntroDomain = D('introduce_domain');
        $user_id = I('user_id');
        $from = I('from');
        $length = 10;
        $contents = $Intro->where(array(
            'user_id' => $user_id
        ))
            ->order('time desc')
            ->select();
        foreach ($contents as $k => $v) {
            // 如果是转载
            if ($v['isforward']) {
                $contents[$k]['isforward'] = $Intro->where(array(
                    'id' => $v['isforward']
                ))->find();
                // 整合推荐图片
                $contents[$k]['isforward']['image'] = $Image->getIntroImg($v['isforward']);
                // 整合用户头像和名字
                $contents[$k]['isforward']['username'] = $User->where(array(
                    'id' => $contents[$k]['isforward']['user_id']
                ))->getField('username');
                $contents[$k]['isforward']['face'] = $User->where(array(
                    'id' => $contents[$k]['isforward']['user_id']
                ))->getField('faceurl');
                // 整合推荐领域
                $contents[$k]['isforward']['domain'] = $IntroDomain->where(array(
                    'introduce_id' => $v['isforward']
                ))->getField('domain');
            }
            // 整合推荐图片
            $contents[$k]['image'] = $Image->getIntroImg($v['id']);
            // 整合用户头像和名字
            $contents[$k]['username'] = $User->where(array(
                'id' => $user_id
            ))->getField('username');
            $contents[$k]['face'] = $User->where(array(
                'id' => $user_id
            ))->getField('faceurl');
            $contents[$k]['domain'] = $IntroDomain->where(array(
                'introduce_id' => $v['id']
            ))->getField('domain');
        }
        $resp = responseMsg(0, 203, $contents);
        $resp['from'] = $from;
        $resp['length'] = $length;
        $this->ajaxReturn($resp);
    }

    /**
     * 从圈子点赞推荐，不加口碑
     */
    public function addPraiseInQuanzi()
    {
        $type = 204;
        // 登录权限接口预处理，包括接口调用正确性，用户是否登录，用户是否被禁用
        loginPermitApiPreTreat($type);
        $User = D('user');
        $Intro = D('introduce');
        $Praise = D('praise');
        $Num = D("daily_num");
        $introduce_id = I('introduce_id');
        $user_id = I('user_id');
        // 通过推荐表获取所有者的id
        $owner_id = $Intro->where(array(
            'id' => $introduce_id
        ))->getField('user_id');
        // 得到原创推荐所有者id
        $original_intro_id = $Intro->where(array(
            'id' => $introduce_id
        ))->getField('isforward');
        $original_user_id = $Intro->where(array(
            'id' => $original_intro_id
        ))->getField('user_id');
        // 每日点赞人数加一
        $today = date("Y-m-d");
        $times = $Praise->where(array(
            "user_id" => $user_id
        ))
            ->field("time")
            ->select();
        $count = 0;
        foreach ($times as $k => $v) {
            // 如果该用户在这一天有点过赞，count=1
            if (isToday($v["time"])) {
                $count = 1;
                break;
            }
        }
        if (! $count) {
            $Num->where(array(
                "date" => $today
            ))->setInc("praisenum");
        }
        // $data1用于插入praise表
        $data1['introduce_id'] = $introduce_id;
        $data1['user_id'] = $user_id;
        $data1['time'] = date('Y-m-d H:i:s');
        $data1['owner_id'] = $owner_id;
        // 插入praise表
        $flag1 = $Praise->add($data1);
        // 该推荐所有者的总点赞数加一
        $User->where(array(
            'id' => $owner_id
        ))->setInc('allpraise');
        // 该推荐的原创用户的总点赞数加一
        $User->where(array(
            'id' => $original_user_id
        ))->setInc('allpraise');
        // 该推荐的praisenum++
        $flag2 = $Intro->where(array(
            'id' => $introduce_id
        ))->setInc('praisenum');
        // 原创推荐的priasenum++
        $Intro->where(array(
            'id' => $original_intro_id
        ))->setInc('praisenum');
        // $res=array($flag1,$flag2, $flag3,$flag4);
        // dump($res);die;
        if ($flag1 && $flag2) {
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
        // 获取点赞该推荐的用户id和点赞记录id
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
            $praiseinfo[$k]['username'] = $User->where(array(
                'id' => $v['user_id']
            ))->getField('username');
        }
        dump($praiseinfo);
        die();
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
        $User = D('user');
        $Praise = D('praise');
        $Intro = D('introduce');
        $praise_id = I('praise_id');
        // 获取推荐id
        $introduce_id = $Praise->where(array(
            'id' => $praise_id
        ))->getField('introduce_id');
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
        $type = 205;
        loginPermitApiPreTreat($type);
        $User = D('user');
        $Intro = D('introduce');
        $Forward = D('forward');
        $Friend = D("friend");
        $introduce_id = I('introduce_id');
        $user_id = I('user_id');
        // owner_id并不一定指原创的作者id 谁被转载了谁就owner
        // 原创的推荐id存在introduce表的isforward字段中
        $owner_id = $Intro->where(array(
            'id' => $introduce_id
        ))->getField('user_id');
        // 得到转采的推荐id 没有就返回0
        $isforward = $Intro->where(array(
            'id' => $introduce_id
        ))->getField('isforward');
        if ($isforward) {
            // 如果该推荐时转载的，original_id为原创推荐的id
            $original_id = $isforward;
        } else {
            // 若转载的就是原创推荐
            $original_id = $introduce_id;
        }
        // 转采表的数据
        $data1['user_id'] = $user_id;
        // 转载表的introduce_id为上一位转载的推荐的id，不是原创推荐的id
        $data1['introduce_id'] = $introduce_id;
        $data1['time'] = date('Y-m-d H:i:s');
        $data1['owner_id'] = $owner_id;
        $data1['original_id'] = $original_id;
        // 推荐表的数据
        $data2['time'] = $data1['time'];
        // isforward放的是原创推荐的id
        $data2['isforward'] = $original_id;
        $data2['text'] = I('comment');
        $data2['user_id'] = $data1['user_id'];
        // 度数逻辑
        // 获得被转发推荐的度数a
        $degree = $Intro->where(array(
            "id" => $introduce_id
        ))->getField("degree");
        // 获得被转发推荐的转载记录id
        $forward_id = $Intro->where(array(
            'id' => $introduce_id
        ))->getField('forward_id');
        // degree大于一的时候才需要判断，若degree转载的时候为一，就直接加一
        if ($degree > 1) {
            // 在forward表找到1.度数小于当前度数的2.和转发者是朋友的3.原创推荐id和forward表中的original_id一样的
            $where1['degree'] = array(
                "lt",
                $degree
            );
            $where1['original_id'] = array(
                "eq",
                $original_id
            );
            $friends = $Friend->where(array(
                "user_id" => $data1['user_id']
            ))
                ->field("friend_id")
                ->select();
            foreach ($friends as $k => $v) {
                $friend[] = $v["friend_id"];
            }
            $where1['user_id'] = array(
                "in",
                $friend
            );
            $rows = $Forward->where($where1)->select();
            if (! $rows) {
                // 如果没有找到
                // 度数为被转载推荐的度数加一
                $degree = $degree + 1;
                if ($degree >= 7) {
                    $degree = 7;
                }
                // 新增度数源（度数源是用户id，为了保存转载链，实现非原创推荐用户的口碑增加）
                $data1['original_id' . $degree] = $user_id;
                // 复制度数源
                // 在Intro表通过forward_id得到该转载推荐的转载链，从而复制度数源
                $original_ids = $Forward->where(array(
                    'id' => $forward_id
                ))->select();
                for ($i = 2; $i < $degree; $i ++) {
                    $data1['original_id' . $i] = $original_ids[0]['original_id' . $i];
                    // 给度数源用户加口碑
                    $User->where(array(
                        'id' => $original_ids[0]['original_id' . $i]
                    ))->setInc('praisenum', $degree - $i);
                }
                // 给原创推荐所有者增加口碑
                // 得到原创推荐所有者的id
                $original_intro_userid = $Intro->where(array(
                    'id' => $original_id
                ))->getField('user_id');
                switch ($degree) {
                    case 3:
                        $User->where(array(
                            'id' => $original_intro_userid
                        ))->setInc('praisenum', 1);
                        break;
                    case 4:
                        $User->where(array(
                            'id' => $original_intro_userid
                        ))->setInc('praisenum', 2);
                        break;
                    case 5:
                        $User->where(array(
                            'id' => $original_intro_userid
                        ))->setInc('praisenum', 4);
                        break;
                    case 6:
                        $User->where(array(
                            'id' => $original_intro_userid
                        ))->setInc('praisenum', 8);
                        break;
                    case 7:
                        $User->where(array(
                            'id' => $original_intro_userid
                        ))->setInc('praisenum', 16);
                        break;
                }
                $data1["degree"] = $degree;
                $data2["degree"] = $degree;
            } else {
                // 如果找到记录，度数为这些记录的最小的度数加一
                // 得到这些记录中最小的度数
                $min = 10000;
                foreach ($rows as $k => $v) {
                    if ($v['degree'] < $min) {
                        $min = $v['degree'];
                    }
                }
                foreach ($rows as $k => $v) {
                    if ($v['degree'] == $min) {
                        $forword = $rows[$k];
                    }
                }
                $min = $min + 1;
                // 新增度数源
                $data1['original_id' . $min] = $user_id;
                // 复制度数源
                $degreesum = 0;
                for ($i = 2; $i < $min; $i ++) {
                    $data1['original_id' . $i] = $forword['original_id' . $i];
                    // 给度数源用户加口碑
                    $User->where(array(
                        'id' => $forword['original_id' . $i]
                    ))->setInc('praisenum', $min - $i);
                }
                // 给原创推荐所有者增加口碑
                    // 得到原创推荐所有者的id
                $original_intro_userid = $Intro->where(array(
                    'id' => $original_id
                ))->getField('user_id');
                switch ($min) {
                    case 3:
                        $User->where(array(
                            'id' => $original_intro_userid
                        ))->setInc('praisenum', 1);
                        break;
                    case 4:
                        $User->where(array(
                            'id' => $original_intro_userid
                        ))->setInc('praisenum', 2);
                        break;
                    case 5:
                        $User->where(array(
                            'id' => $original_intro_userid
                        ))->setInc('praisenum', 4);
                        break;
                    case 6:
                        $User->where(array(
                            'id' => $original_intro_userid
                        ))->setInc('praisenum', 8);
                        break;
                    case 7:
                        $User->where(array(
                            'id' => $original_intro_userid
                        ))->setInc('praisenum', 16);
                        break;
                }
                $data2["degree"] = $min;
                $data1["degree"] = $min;
            }
        } else {
            $degree = $degree + 1;
            $data1['original_id2'] = $user_id;
            $data2["degree"] = $degree;
            $data1["degree"] = $degree;
        }
        // 判断是否自己转载自己,避免自己成为自己的度数源的逻辑错误
        $introduce_owner = $Forward->where(array(
            'id' => $forward_id
        ))->getField('user_id');
        if ($user_id != $introduce_owner) {
            // 如果转载的度数大于3，给原创推荐的alldegree增加该转载的度数
            if ($data1["degree"] >= 3) {
                $Intro->where(array(
                    'id' => $original_id
                ))->setInc('alldegree', $data1["degree"]);
            }
            // 插入转采表
            // dump($data1);die;
            $flag1 = $Forward->add($data1);
            // 插入推荐表
            // 保存该推荐的转载记录id
            $data2['forward_id'] = $flag1;
            $flag = $Intro->add($data2);
            // 该推荐表转采数加一
            $flag2 = $Intro->where(array(
                'id' => $data1['introduce_id']
            ))->setInc('forwardnum');
            // 原创推荐转载数加一
            $Intro->where(array(
                'id' => $original_id
            ))->setInc('praisenum');
        }
        // 该用户的朋友的未读推荐数加一
        $friends2 = $Friend->where(array(
            'user_id' => $user_id
        ))->select();
        foreach ($friends2 as $k => $v) {
            $friends[] = $v['friend_id'];
        }
        $where_friend['id'] = array(
            "IN",
            $friends
        );
        $User->where($where_friend)->setInc("unreadnum");
        // dump(array($flag,$flag1,$flag2,$flag3,$flag4));
        if ($flag && $flag1 && $flag2) {
            // 转采成功
            $this->ajaxReturn(responseMsg(0, $type));
        }
        // 转采失败
        $this->ajaxReturn(responseMsg(1, $type));
    }

    /**
     * 添加评论
     */
    public function addcomment()
    {
        $type = 206;
        loginPermitApiPreTreat($type);
        $Comment = D("comment");
        $Num = D("daily_num");
        $Intro = D("introduce");
        $User = D("user");
        $data['user_id'] = I("user_id");
        $data['introduce_id'] = I("introduce_id");
        $data['content'] = I("content");
        $data['owner_id'] = $Intro->where(array(
            'id' => $data['introduce_id']
        ))->getField("user_id");
        $data['time'] = date("Y-m-d H:i:s");
        // 每日评论人数加一
        $today = date("Y-m-d");
        $times = $Comment->where(array(
            "user_id" => $data['user_id']
        ))
            ->field("time")
            ->select();
        $count = 0;
        foreach ($times as $k => $v) {
            // 如果该用户在这一天有评论过，count=1
            if (isToday($v["time"])) {
                $count = 1;
                break;
            }
        }
        if (! $count) {
            $Num->where(array(
                "date" => $today
            ))->setInc("commentnum");
        }
        // 数据插入comment表
        $flag2 = $Comment->add($data);
        // 推荐评论数加一
        $Intro->where(array(
            'id' => $data['introduce_id']
        ))->setInc("commentnum");
        if ($flag2) {
            $this->ajaxReturn(responseMsg(0, $type));
        } else {
            $this->ajaxReturn(responseMsg(1, $type));
        }
    }

    /**
     * 踩推荐
     */
    public function addOppose()
    {
        $type = 207;
        loginPermitApiPreTreat($type);
        $Intro = D('introduce');
        $User = D('user');
        $introduce_id = I('introduce_id');
        $user_id = $Intro->where(array(
            'id' => $introduce_id
        ))->getField('user_id');
        // 推荐的踩数加一
        $flag1 = $Intro->where(array(
            'id' => $introduce_id
        ))->setInc('opposenum');
        // 原创推荐的踩数加一
        $original_id = $Intro->where(array(
            'id' => $introduce_id
        ))->getField('isforward');
        $Intro->where(array(
            'id' => $original_id
        ))->setInc('opposenum');
        // 用户总踩量加一
        $flag2 = $User->where(array(
            'id' => $user_id
        ))->setInc('alloppose');
        // 原创用户总踩量加一
        $owner_id = $Intro->where(array(
            'id' => $original_id
        ))->getField('user_id');
        $User->where(array(
            'id' => $owner_id
        ))->setInc('alloppose');
        if ($flag1 && $flag2) {
            $this->ajaxReturn(responseMsg(0, $type));
        } else {
            $this->ajaxReturn(responseMsg(1, $type));
        }
    }

    /**
     * 从江湖中点赞非好友推荐，加0.1口碑
     */
    public function addPraiseInJianghu()
    {
        $type = 208;
        loginPermitApiPreTreat($type);
        $User = D('user');
        $Intro = D('introduce');
        $introduce_id = I('introduce_id');
        // 得到推荐所有人id
        $owner = $Intro->where(array(
            'id' => $introduce_id
        ))->getField('user_id');
        // 江湖点赞临时存放点加一
        $User->where(array(
            'id' => $owner
        ))->setInc('temp_praisenum');
        $temp_praisenum = $User->where(array(
            'id' => $owner
        ))->getField('temp_praisenum');
        // 判断是否点赞了十次，若被点赞了十次，则口碑加一
        if ($temp_praisenum == 10) {
            // 口碑加一
            $User->where(array(
                'id' => $owner
            ))->setInc('praisenum');
            // 点赞临时存放点清零
            $User->where(array(
                'id' => $owner
            ))->save(array(
                'temp_praisenum' => 0
            ));
        }
        // 该推荐点赞数加一
        $Intro->where(array(
            'id' => $introduce_id
        ))->setInc('praisenum');
        $this->ajaxReturn(responseMsg(0, $type));
    }

    /**
     * 返回江湖推荐
     */
    public function jianghuIntroduce()
    {
        $type = 209;
        // 判断有没有调用错接口
        if (! $type == I('type')) {
            $this->ajaxReturn(responseMsg(5, $type));
        }
        // 如果有用户登录，就按照domain找推荐，没有的话就不按domain找
        $user_id = I('user_id');
        $Intro = D('introduce');
        $User = D('user');
        $UserDomain = D('user_domain');
        $IntroImage = D('introduce_images');
        $from = I('from');
        $length = 10;
        // 要求原创
        $where['isforward'] = 0;
        // 如果有传user_id,则where有条件，否者where为空
        if ($user_id) {
            loginPermitApiPreTreat($type);
            // 得到该用户的domain
            $domain2 = $UserDomain->where(array(
                'user_id' => $user_id
            ))->select();
            foreach ($domain2 as $k => $v) {
                $domain[] = $v['domain'];
            }
            $where = array(
                "IN",
                $domain
            );
            $msg = $Intro->where($where)
                ->order('alldegree desc')
                ->limit($from, $length)
                ->select();
        } else {
            $msg = $Intro->order('alldegree desc')
                ->limit($from, $length)
                ->select();
        }
        foreach ($msg as $k => $v) {
            $msg[$k]['face'] = $User->where(array(
                'id' => $v['user_id']
            ))->getField('faceurl');
            $msg[$k]['username'] = $User->where(array(
                'id' => $v['user_id']
            ))->getField('username');
            $msg[$k]['image'] = $IntroImage->getIntroImg($v['id']);
        }
        $resp = responseMsg(0, $type, $msg);
        // 返回整合from，length
        $resp['from'] = $from;
        $resp['length'] = $length;
        $this->ajaxReturn($resp);
    }
    /**
     * 查看该推荐的一度好友的评论
     */
    public function checkComment(){
        $type=210;
        loginPermitApiPreTreat($type);
        $User=D('user');
        $Comment=D('comment');
        $Friend=D('friend');
        $user_id=I('user_id');
        $introduce_id=I('introduce_id');
        $friends2=$Friend->where(array('user_id'=>$user_id))->field("friend_id")->select();
        foreach ($friends2 as $k=>$v){
            $friends[]=$v['friend_id'];
        }
        $where['user_id']=array("IN",$friends);
        $where['introduce_id']=$introduce_id;
        $msg=$Comment->where($where)->order("time desc")->select();
        foreach ($msg as $k=>$v){
            $msg[$k]['username']=$User->where(array("id"=>$user_id))->getField('username');
            $msg[$k]['face']=$User->where(array("id"=>$user_id))->getField('faceurl');
            unset($msg[$k]['state']);   
            unset($msg[$k]['owner_id']);
        }
        $this->ajaxReturn(responseMsg(0, $type,$msg));
    }
}