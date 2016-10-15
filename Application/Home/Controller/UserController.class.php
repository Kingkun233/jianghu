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
//         dump(I());die;
        $User = D('user');
        $Friend=D('friend');
        $Domain=D('user_domain');
        $data['username'] = I('username');
        if (checkUserExist($data['username'])) {
            $this->ajaxReturn(4); // 用户已存在
        }
        $data['password'] = md5(I('password'));
        $data['phonenum'] = I('phonenum');
        $domains=I('domain');
        if ('男' == I('sex')) {
            $data['sex'] = 0; // 男
        } else {
            $data['sex'] = 1; // 女
        }
        $data['addr'] = I('addr');
        $data['jointime'] = date('Y-m-d');
        //把数据插入用户表
        $flag1 = $User->add($data);
        //把数据插入领域表
        $add1['user_id']=$flag1;
        foreach ($domains as $k=>$v){
            $add1['name']=$v;
            $flag3=$Domain->add($add1);
        }
        //自己和自己成为好友关系
            //flag1为用户id
        $add['user_id']=$flag1;
        $add['friend_id']=$add['user_id'];
        $flag2=$Friend->add($add);
        //每日注册量加一
        $Joinnum=D("daily_num");
        $today=date("Y-m-d");
        if($Joinnum->where(array("date"=>$today))->select()){
            $flag4=$Joinnum->where(array("date"=>$today))->setInc("joinnum");
        }else {
            $data1['date']=$today;
            $data1['joinnum']=1;
            $flag4=$Joinnum->add($data1);
        }
        //判断上述操作是否成功
        if ($flag1&&$flag2&&$flag3&&$flag4) {
            $this->ajaxReturn(0); // 注册成功
        } else {
//             $flag[]=[$flag1,$flag2,$flag3,$flag4];
//             dump($flag);
            $this->ajaxReturn(1); //注册失败
        }
    }

    /**
     * 登录接口
     */
    public function login()
    {
        $User = D('user');
        $Joinnum=D("daily_num");
        $data['username'] = I('username');
        $data['password'] = md5(I('password'));
        $flag = $User->where(array(
            'username' => $data['username']
        ))->select();
        if (! flag) {
            $this->ajaxReturn(2); // 用户不存在
        }
        //获取数据库密码
        $dbpassword = $User->where(array(
            'username' => $data['username']
        ))->getField('password');
        //和传过来的密码比较
        $userid = $User->where(array(
            'username' => $data['username']
        ))->getField('id');
        if ($dbpassword == $data['password']) {
            //匹配的话就存入session
            session('username', $data['username']);
            session('userid', $userid);
            //每日登陆人数加一
            $today=date("Y-m-d");
            if($Joinnum->where(array("date"=>$today))->select()){
                $Joinnum->where(array("date"=>$today))->setInc("lognum");
            }else {
                $data1['date']=$today;
                $data1['lognum']=1;
                $Joinnum->add($data1);
            }
            //查看是不是留存用户，是的话keep++
            $jointime=$User->where(array("id"=>$userid))->getField("jointime");
            $jointime=date("Y-m-d",strtotime($jointime));
            $yesterday=date("Y-m-d",strtotime("-1 day"));
            if($jointime==$yesterday){
                $Joinnum->where(array("date"=>$today))->setInc("keep");
            }
            $this->ajaxReturn(0); // 登录成功
        } else {
            $this->ajaxReturn(1); // 登录失败
        }
    }

    /**
     * 添加头像
     */
    public function addFace()
    {
        if(!checkUserLogin()){
            //用户未登陆
            $this->ajaxReturn(2);
        }
        $User = D('user');
        $data['username'] = I('username');
        $image = imageUpload();
//         dump($image);die;
        $add['faceurl'] = $image['url'];
        $add['facepath'] = $image['path'];
        foreach ($add as $k => $v) { // 将二维数组装换成一维
            $add1[$k] = $v[0];
        }
        $flag = $User->where(array(
            'username' => $data['username']
        ))->save($add1);

//         dump($add1);die;
        if (flag) {
            $this->ajaxReturn(0); // 头像添加成功
        } else {
            $this->ajaxReturn(1); // 头像添加失败
        }
    }

    /**
     * 更改头像
     */
    public function changeFace()
    {   
        if(!checkUserLogin()){
            //用户未登陆
            $this->ajaxReturn(2);
        }
        $User = D('user');
        $data['username'] = I('username');
        
        $image = imageUpload();
        $add['faceurl'] = $image['url'];
        $add['facepath'] = $image['path'];
        foreach ($add as $k => $v) { // 将二维数组装换成一维
            $add1[$k] = $v[0];
        }
        $where['username'] = $data['username'];
        imageDel($User, $where, "facepath");//删除旧图片
        $flag = $User->where(array(
            'username' => $data['username']
        ))->save($add1);
        if (flag) {
            $this->ajaxReturn(0); // 头像更改成功
        } else {
            $this->ajaxReturn(1); // 头像更改失败
        }
    }
}