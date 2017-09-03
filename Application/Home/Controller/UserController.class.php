<?php

namespace Home\Controller;

use Think\Controller;
use Think\Model;

class UserController extends Controller
{

    /**
     * 注册接口
     */
    public function join()
    {
        $type = 100;
        $post = touristApiPreTreat($type);
        $model = new Model();
        //开启一个事务
        $model->startTrans();
        $User = D('user');
        $Friend = D('friend');
        $Token = D('token');
        $LoginCount = D('login_count');
        $Joinnum = D("daily_num");
        $UserDomain = D('user_domain');
        $Domain = D('domain');
        if (!$post['username']) {
            // 若username为空，则插入默认用户名
            $data['username'] = $this->getDefaultName();
        } else {
            $data['username'] = $post['username'];
        }
        if (checkUserExist($data['phonenum'])) {
            $this->ajaxReturn(responseMsg(6, $type)); // 用户已存在
        }
        // 默认头像
        $data['faceurl'] = $this->getDefaultFaceUrl();
        $data['password'] = $post['password'];
        $data['phonenum'] = $post['phonenum'];
//        if ($this->checkPhoneExist($data['phonenum'])) {
//            $this->ajaxReturn(responseMsg(7, $type, null)); // 电话已存在
//        }
        $domain_ids = $post['domain_id'];
        // dump($domain_ids);die;
        if ('男' == $post['sex']) {
            $data['sex'] = 0; // 男
        } else {
            $data['sex'] = 1; // 女
        }
        $data['addr'] = $post['addr'];
        $data['wechat_id'] = $post['wechat_id'];
        $data['qq_id'] = $post['qq_id'];
        $data['jointime'] = date('Y-m-d');
        // 把数据插入用户表
        $flag1 = $User->add($data);
        $user_id = $flag1;
        // 把数据插入领域表
        $add1['user_id'] = $flag1;
        foreach ($domain_ids as $k => $v) {
            $domain_name = $Domain->where(array(
                'id' => $v
            ))->getField('name');
            if ($v) {
                $domain_names[] = $domain_name;
                $add1['domain'] = $domain_name;
                $UserDomain->add($add1);
            }
        }
        // 自己和自己成为好友关系
        // flag1为用户id
        $add['user_id'] = $flag1;
        $add['friend_id'] = $add['user_id'];
        $flag2 = $Friend->add($add);
        // 每日注册量加一
        $today = date("Y-m-d");
        if ($Joinnum->where(array(
            "date" => $today
        ))->select()) {
            $flag4 = $Joinnum->where(array(
                "date" => $today
            ))->setInc("joinnum");
        } else {
            $data1['date'] = $today;
            $data1['joinnum'] = 1;
            $flag4 = $Joinnum->add($data1);
        }
        if (!($flag1 && $flag2 && $flag4)) {
            //事务回滚
            $model->rollback();
            $this->ajaxReturn(responseMsg(1, $type)); // 注册失败
        } else {
            //事务提交
            $model->commit();
        }
        // 顺便登录
        // post json请求
        $user_id = $flag1;
        $userinfo = $User->where(array(
            'id' => $user_id
        ))->find();
        $data = array(
            "phonenum" => $userinfo['phonenum'],
            "password" => $userinfo['password'],
            "type" => "101"
        );
        $data_string = json_encode($data);
        $url = "http://" . $_SERVER['SERVER_NAME'] . "/jianghu/index.php/home/user/login";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string)
        ));

        $result = curl_exec($ch);
        curl_close($ch);
        print_r($result);
    }

    /**
     * 登录接口
     */
    public function login()
    {
        $type = 101;
        $post = touristApiPreTreat($type);
        $User = D('user');
        $Joinnum = D("daily_num");
        $Token = D('token');
        $UserDomain = D('user_domain');
        $LoginCount = D('login_count');
        $data['phonenum'] = $post['phonenum'];
        $data['password'] = $post['password'];
        $push_regid = $post['push_regid'];
        $flag = $User->where(array(
            'phonenum' => $data['phonenum']
        ))->select();
        if (!flag) {
            $this->ajaxReturn(responseMsg(4, $type)); // 用户不存在
        }
        // 检查用户被禁用
        $user_id = $User->where(array(
            'phonenum' => $data['phonenum']
        ))->getField('id');
        if (isban($user_id)) {
            $this->ajaxReturn(responseMsg(3, $type)); // 用户被禁用
        }
        // 获取数据库密码
        $dbpassword = $User->where(array(
            'phonenum' => $data['phonenum']
        ))->getField('password');
        // 和传过来的密码比较
        if ($dbpassword == $data['password']) {
            //更新用户推送regid
            $User->where(array(
                'id' => $user_id
            ))->save(array('push_regid' => $push_regid));
            //返回用户信息
            $msg = $User->where(array(
                "id" => $user_id
            ))->find();
            // 匹配的话插入token表
            // 生成token
            $token = $this->getRongYunToken($user_id, $msg['faceurl'], $msg['username']);
            $add_token['token'] = $token['token'];
            $add_token['user_id'] = $user_id;
            $add_token['time'] = date("Y-m-d H:i:s");
            $Token->add($add_token);
            // 每日登陆人数加一
            $today = date("Y-m-d");
            if ($Joinnum->where(array(
                "date" => $today
            ))->select()) {
                $Joinnum->where(array(
                    "date" => $today
                ))->setInc("lognum");
            } else {
                $data1['date'] = $today;
                $data1['lognum'] = 1;
                $Joinnum->add($data1);
            }
            // 查看是不是留存用户，是的话keep++
            $jointime = $User->where(array(
                "id" => $user_id
            ))->getField("jointime");
            $jointime = date("Y-m-d", strtotime($jointime));
            $yesterday = date("Y-m-d", strtotime("-1 day"));
            if ($jointime == $yesterday) {
                $Joinnum->where(array(
                    "date" => $today
                ))->setInc("keep");
            }
            // 插入登陆统计表
            $add_lc['user_id'] = $user_id;
            $add_lc['date'] = date("Y-m-d");
            $LoginCount->add($add_lc);
            // 登陆加一口碑
            $User->where(array(
                'id' => $user_id
            ))->setInc('praisenum');
            // 整合要返回的数据
            // msg整合domain
            $domain_names2 = $UserDomain->where(array(
                'user_id' => $user_id
            ))->select();
            $domain_names = array();
            foreach ($domain_names2 as $k => $v) {
                $domain_names[] = $v['domain'];
            }
            $msg['domain'] = $domain_names;
            $resp = responseMsg(0, 101, $msg);
            $resp['token'] = $add_token['token'];
            $this->ajaxReturn($resp); // 登录成功
        } else {
            // dump($dbpassword);
            // dump($data['password']);
            $this->ajaxReturn(responseMsg(1, $type)); // 登录失败
        }
    }

    /**
     * 上传图片得到图片地址
     */
    public function getFaceUrl()
    {
        $type = 105;
        $image = imageUpload();
        // dump($image);die;
        $msg['faceurl'] = $image['url'][0];
        if ($image) {
            $this->ajaxReturn(responseMsg(0, $type, $msg));
        } else {
            $this->ajaxReturn(responseMsg(1, $type));
        }
    }

    /**
     * 添加或修改头像
     */
    public function addFace()
    {
        $type = 104;
        $post = loginPermitApiPreTreat($type);
        $User = D('user');
        $user_id = $post['user_id'];
        // 获得原头像
        $originface = $User->where(array(
            'id' => $user_id
        ))->getField('facepath');
        // 更改为新头像
        $add['faceurl'] = $post['faceurl'];
        $add['facepath'] = "." . strstr($add['faceurl'], "/Uploads");
        $flag = $User->where(array(
            'id' => $user_id
        ))->save($add);

        if (flag) {
            // 如果成功则刷新融云的个人信息
            $username = $User->where(array(
                'id' => $user_id
            ))->getField("username");
            $rm = $this->refreshRongYun($user_id, $add['faceurl'], $username);
            // 如果添加成功则删除原图片
            unlink($originface);
            $this->ajaxReturn(responseMsg(0, $type)); // 头像添加成功
        } else {
            $this->ajaxReturn(responseMsg(1, $type)); // 头像添加失败
        }
    }

    /**
     * 退出登录
     */
    public function outlogin()
    {
        $type = 102;
        // 检查调用是否正确
        $post = touristApiPreTreat($type);
        $Token = D("token");
        $User = D('user');
        $user_id = $post['user_id'];
        if ($Token->where(array(
            "user_id" => $user_id
        ))->save(array(
            "state" => 1
        ))) {
            //该用户的regid置空
            $User->where(array('id' => $user_id))->save(array('push_regid' => ''));
            $this->ajaxReturn(responseMsg(0, $type)); // 登出成功
        } else {
            $this->ajaxReturn(responseMsg(1, $type)); // 登出失败
        }
    }

    /**
     * 查看用户详细信息
     */
    public function checkUserDetail()
    {
        $type = 103;
        $post = touristApiPreTreat($type);
        $User = D('user');
        $Domain = D('user_domain');
        $user_id = $post['user_id'];
        $domain2 = $Domain->where(array(
            'user_id' => $user_id
        ))->select();
        $domain = array();
        foreach ($domain2 as $k => $v) {
            $domain[] = $v['domain'];
        }
        $msg = $User->where(array(
            'id' => $user_id
        ))->find();
        $msg['domain'] = $domain;
        unset($msg['unreadnum']);
        unset($msg['isban']);
        unset($msg['password']);
        unset($msg['temp_praisenum']);
        $this->ajaxReturn(responseMsg(0, $type, $msg));
    }

    /**
     * 获取融云token
     */
    public function getRongYunToken($user_id, $faceurl, $username)
    {
        // 参数初始化
        $nonce = mt_rand();

        $timeStamp = time();
        $appSec = "8u358zT5qaJX";
        $signature = sha1($appSec . $nonce . $timeStamp); // $appSec是平台分配
        $appKey = "4z3hlwrv4xj8t";
        $url = 'https://api.cn.rong.io/user/getToken.json';

        $postData = 'userId=' . $user_id . '&name=' . $username . '&portraitUri=' . $faceurl;

        $httpHeader = array(

            'App-Key:' . $appKey, // 平台分配

            'Nonce:' . $nonce, // 随机数

            'Timestamp:' . $timeStamp, // 时间戳

            'Signature:' . $signature, // 签名

            'Content-Type: application/x-www-form-urlencoded'
        );

        // 创建http header

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, 1);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_HEADER, false);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeader);

        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($ch);

        curl_close($ch);

        return json_decode($result, true);
    }

    /**
     * 刷新融云用户信息
     *
     * @param unknown $user_id
     * @param unknown $faceurl
     * @param unknown $username
     * @return mixed
     */
    public function refreshRongYun($user_id, $faceurl, $username)
    {
        // 参数初始化
        $nonce = mt_rand();

        $timeStamp = time();
        $appSec = "8u358zT5qaJX";
        $signature = sha1($appSec . $nonce . $timeStamp); // $appSec是平台分配
        $appKey = "4z3hlwrv4xj8t";
        $url = 'http://api.cn.ronghub.com/user/refresh.json';

        $postData = 'userId=' . $user_id . '&name=' . $username . '&portraitUri=' . $faceurl;

        $httpHeader = array(

            'App-Key:' . $appKey, // 平台分配

            'Nonce:' . $nonce, // 随机数

            'Timestamp:' . $timeStamp, // 时间戳

            'Signature:' . $signature, // 签名

            'Content-Type: application/x-www-form-urlencoded'
        );

        // 创建http header

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, 1);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_HEADER, false);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeader);

        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($ch);

        curl_close($ch);

        return json_decode($result, true);
    }

    /**
     * 修改用户名
     */
    public function changeUsername()
    {
        $type = 106;
        $post = loginPermitApiPreTreat($type);
        $User = D('user');
        $user_id = $post['user_id'];
        $data['username'] = $post['new_username'];
        // 刷新融云上的用户信息
        $faceurl = $User->where(array(
            "id" => $user_id
        ))->getField("faceurl");
        $this->refreshRongYun($user_id, $faceurl, $data['username']);
        $User->where(array(
            'id' => $user_id
        ))->save($data);
        $this->ajaxReturn(responseMsg(0, $type));
    }

    /**
     * 修改密码
     */
    public function changePassword()
    {
        $type = 107;
        $post = loginPermitApiPreTreat($type);
        $User = D('user');
        $user_id = $post['user_id'];
        $data['password'] = $post['new_password'];
        $User->where(array(
            'id' => $user_id
        ))->save($data);
        $this->ajaxReturn(responseMsg(0, $type));
    }

    /**
     * 修改个人简介
     */
    public function changeDescription()
    {
        $type = 108;
        $post = loginPermitApiPreTreat($type);
        $User = D('user');
        $user_id = $post['user_id'];
        $data['description'] = $post['new_description'];
        $User->where(array(
            'id' => $user_id
        ))->save($data);
        $this->ajaxReturn(responseMsg(0, $type));
    }

    /**
     * 提交反馈
     */
    public function feedBack()
    {
        $type = 109;
        $post = loginPermitApiPreTreat($type);
        $Feedback = D('feedback');
        $add['text'] = $post['text'];
        $add['user_id'] = $post['user_id'];
        $add['time'] = date("Y-m-d H:i:s");
        $Feedback->add($add);
        $this->ajaxReturn(responseMsg(0, $type));
    }

    /**
     * 收藏推荐
     */
    public function collect()
    {
        $type = 110;
        $post = loginPermitApiPreTreat($type);
        $Collection = D('collection');
        $add['introduce_id'] = $post['introduce_id'];
        $add['user_id'] = $post['user_id'];
        $add['time'] = date("Y-m-d H:i:s");
        $Collection->add($add);
        $this->ajaxReturn(responseMsg(0, $type));
    }

    /**
     * 删除收藏
     */
    public function delCollection()
    {
        $type = 114;
        $post = loginPermitApiPreTreat($type);
        $Collect = D('collection');
        $where_collect['user_id'] = $post['user_id'];
        $where_collect['introduce_id'] = $post['introduce_id'];
        $flag = $Collect->where($where_collect)->delete();
        if ($flag) {
            $this->ajaxReturn(responseMsg(0, $type));
        } else {
            $this->ajaxReturn(responseMsg(1, $type));
        }
    }

    /**
     * 举报用户
     */
    public function reportUser()
    {
        $type = 111;
        $post = loginPermitApiPreTreat($type);
        $Report = D('user_report');
        $add['user_id'] = $post['user_id'];
        $add['reported_id'] = $post['reported_id'];
        $add['text'] = $post['text'];
        $add['time'] = date("Y-m-d H:i:s");
        $flag = $Report->add($add);
        if ($flag) {
            $this->ajaxReturn(responseMsg(0, $type));
        } else {
            $this->ajaxReturn(responseMsg(1, $type));
        }
    }

    /**
     * 得到用户默认头像
     *
     * @return string 该头像的url
     */
    public function getDefaultFaceUrl()
    {
        return "http://121.42.203.85/jianghu/Public/images/default/userface.png";
    }

    /**
     * 得到默认用户名
     *
     * @return NULL|string 默认用户名
     */
    public function getDefaultName()
    {
        $User = D('user');
        $length = 9;
        $str = null;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol) - 1;
        for ($i = 0; $i < $length; $i++) {
            $str .= $strPol[rand(0, $max)]; // rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }
        // 检查数据库中有没有该名字的用户
        $flag = $User->where(array(
            "username" => $str
        ))->find();
        if ($flag) {
            // 如果有，递归
            $str = $this->getDefaultName();
        }
        return $str;
    }

    /**
     * 检查电话是否存在
     *
     * @param unknown $phonenum
     * @return boolean
     */
    function checkPhoneExist($phonenum)
    {
        $User = D('user');
        $flag = $User->where(array(
            'phonenum' => $phonenum
        ))->find();
        if ($flag) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 用QQ账户登录
     */
    public function loginByQQAccount()
    {
        $type = 112;
        $post = touristApiPreTreat($type);
        $User = D("user");
        $where['qq_id'] = $post['qq_id'];
        $userDetails = $User->where($where)->find();
        // 如果存在，就登录，不存在，就返回用户不存在4
        if ($userDetails) {
            // 顺便登录
            // post json请求
            $data = array(
                "phonenum" => $userDetails['phonenum'],
                "password" => $userDetails['password'],
                "type" => "101"
            );
            $data_string = json_encode($data);
            $url = "http://121.42.203.85/jianghu/index.php/home/user/login";
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string)
            ));

            $result = curl_exec($ch);
            curl_close($ch);
            print_r($result);
        } else {
            $this->ajaxReturn(responseMsg(4, $type));
        }
    }

    /**
     * 用微信账户登录
     */
    public function loginByWechatAccount()
    {
        $type = 113;
        $post = touristApiPreTreat($type);
        $User = D("user");
        $where['wechat_id'] = $post['wechat_id'];
        $userDetails = $User->where($where)->find();
        // 如果存在，就登录，不存在，就返回用户不存在4
        if ($userDetails) {
            // 顺便登录
            // post json请求
            $data = array(
                "phonenum" => $userDetails['phonenum'],
                "password" => $userDetails['password'],
                "type" => "101"
            );
            $data_string = json_encode($data);
            $url = "http://121.42.203.85/jianghu/index.php/home/user/login";
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string)
            ));

            $result = curl_exec($ch);
            curl_close($ch);
            print_r($result);
        } else {
            $this->ajaxReturn(responseMsg(4, $type));
        }
    }

    /**
     * 检查该手机号是否已存在
     */
    public function checkPhoneExisted()
    {
        $type = 115;
        $post = touristApiPreTreat($type);
        $User = D('user');
        $phonenum = $post['phonenum'];
        $where_phone['phonenum'] = $phonenum;
        $flag_phoneexist = $User->where($where_phone)->find();
        if (!$flag_phoneexist) {
            $this->ajaxReturn(responseMsg(0, $type));
        } else {
            $this->ajaxReturn(responseMsg(7, $type));
        }
    }

    /**
     * 忘记密码
     */
    public function forget_password()
    {
        $type = 116;
        $post = touristApiPreTreat($type);
        $User = D('user');
        $password = $post['password'];
        $phonenum = $post['phonenum'];
        $User->where(['phonenum' => $phonenum])->save(['password' => $password]);
        $this->ajaxReturn(responseMsg(0, $type));
    }
}