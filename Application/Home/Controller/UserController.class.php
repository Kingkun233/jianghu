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
        $User = D('user');
        $data['username'] = I('username');
        if (checkUserExist($data['username'])) {
            $this->ajaxReturn(4); // 用户已存在
        }
        $data['password'] = md5(I('password'));
        $data['phonenum'] = I('phonenum');
        if ('男' == I('sex')) {
            $data['sex'] = 0; // 男
        } else {
            $data['sex'] = 1; // 女
        }
        $data['addr'] = I('addr');
        $data['time'] = date('Y-m-d');
        $flag = $User->add($data);
        if ($flag) {
            $this->ajaxReturn(0); // 注册成功
        } else {
            $this->ajaxReturn(1); // 账户已存在
        }
    }

    /**
     * 登录接口
     */
    public function login()
    {
        $User = D('user');
        $data['username'] = I('username');
        $data['password'] = md5(I('password'));
        $flag = $User->where(array(
            'username' => $data['username']
        ))->select();
        if (! flag) {
            $this->ajaxReturn(2); // 用户不存在
        }
        $dbpassword = $User->where(array(
            'username' => $data['username']
        ))->getField('password');
        $userid = $User->where(array(
            'username' => $data['username']
        ))->getField('id');
        if ($dbpassword == $data['password']) {
            session('username', $data['username']);
            session('userid', $userid);
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
//         dump($image);
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