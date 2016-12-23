<?php
namespace Home\Controller;

use Think\Controller;

class UserController extends Controller
{

    /**
     * 注册接口
     */
    public function join()
    {
        $type = 100;
        $post = touristApiPreTreat($type);
        $User = D('user');
        $Friend = D('friend');
        $Token = D('token');
        $LoginCount = D('login_count');
        $Joinnum = D("daily_num");
        $UserDomain = D('user_domain');
        $Domain = D('domain');
        $data['username'] = $post['username'];
        if (checkUserExist($data['username'])) {
            $this->ajaxReturn(responseMsg(6, $type)); // 用户已存在
        }
        
        $data['password'] =$post['password'];
        $data['phonenum'] = $post['phonenum'];
        $domain_ids = $post['domain_id'];
        // dump($domain_ids);die;
        if ('男' == $post['sex']) {
            $data['sex'] = 0; // 男
        } else {
            $data['sex'] = 1; // 女
        }
        $data['addr'] = $post['addr'];
        $data['jointime'] = date('Y-m-d');
        // 把数据插入用户表
        $flag1 = $User->add($data);
        $user_id = $flag1;
        // 把数据插入领域表
        $add1['user_id'] = $flag1;
        foreach ($domain_ids as $k => $v) {
            $domain_name = $Domain->where(array(
                'id' => $v['id']
            ))->getField('name');
            if ($v['id']) {
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
        if (! ($flag1 && $flag2 && $flag4)) {
            $this->ajaxReturn(responseMsg(1, $type)); // 注册失败
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
            "type"=>"101"
        );
        $data_string = json_encode($data);
        $url="http://121.42.203.85/jianghu/index.php/home/user/login";
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
        $flag = $User->where(array(
            'phonenum' => $data['phonenum']
        ))->select();
        if (! flag) {
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
            foreach ($domain_names2 as $k => $v) {
                $domain_names[] = $v['domain'];
            }
            $msg['domain'] = $domain_names;
            $resp = responseMsg(0, 101, $msg);
            $resp['token'] = $add_token['token'];
            $this->ajaxReturn($resp); // 登录成功
        } else {
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
        // dump($add['facepath']);die;
        $flag = $User->where(array(
            'id' => $user_id
        ))->save($add);
        // dump($add1);die;
        if (flag) {
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
        $user_id = $post['user_id'];
        if ($Token->where(array(
            "user_id" => $user_id
        ))->save(array(
            "state" => 1
        ))) {
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

    public function test()
    {
        $postjson=file_get_contents("php://input");
        $post=json_decode($postjson,true);
        dump($postjson);
    }
}