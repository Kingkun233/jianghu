<?php
namespace Admin\Controller;

use Think\Controller;
use Think\Page;

class AdminController extends Controller
{

    /**
     * 登录页面
     */
    public function login()
    {
        $this->display();
    }

    /**
     * 登录逻辑
     */
    public function dologin()
    {
        $Admin = D('admin');
        $AdminToken = D('admin_token');
        $name = I('name');
        $password = I('password');
        $code = I('verify');
        if (! $name || ! $password) {
            $this->error('用户名或密码不能为空');
        }
        if (! $this->checkadminExist($name)) {
            $this->error('用户不存在');
        }
        if (! $this->checkVerify($code)) {
            $this->error('验证码错误');
        }
        $dbpassword = $Admin->where(array(
            'name' => $name
        ))->getField('password');
        $admin_id = $Admin->where(array(
            'name' => $name
        ))->getField('id');
        if ($password == $dbpassword) {
            // 如果勾选了自动登录，设置cookie
            if (I("autoFlag")) {
                $time = time();
                // 生成token
                $token = md5($name . $password . $time);
                // token存入数据库
                $add_token['token'] = $token;
                $add_token['time'] = date("Y-m-d H:i:s");
                $add_token['name'] = $name;
                $AdminToken->add($add_token);
                // 存入cookie
                cookie("name", $name, array(
                    'expire' => 60 * 60 * 24 * 7,
                    'prefix' => 'jianghu_'
                ));
                cookie("token", $token, array(
                    'expire' => 60 * 60 * 24 * 7,
                    'prefix' => 'jianghu_'
                ));
            }
            session('name', $name);
            $this->success('登录成功', U('index/index'));
        } else {
            $this->error('密码错误');
        }
    }

    /**
     * 管理员列表
     */
    public function index()
    {
        checkAdminLogin();
        checkIsSuperAdmin();
        $Admin = D('admin');
        $count = $Admin->count();
        $Page = new Page($count, 3);
        $pageshow = page($Page);
        $list = $Admin->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('admin', $list);
        $this->assign('page', $pageshow);
        $this->display();
    }

    /**
     * 生成验证码
     */
    public function getVerify($id = 1)
    {
        $verify = new \Think\Verify();
        $verify->entry($id);
    }

    /**
     * 添加管理员界面
     */
    public function add()
    {
        checkAdminLogin();
        checkIsSuperAdmin();
        $this->display();
    }

    /**
     * 添加管理员逻辑
     */
    public function doadd()
    {
        checkAdminLogin();
        $Admin = D('admin');
        $Admin->create();
        if ($Admin->add()) {
            $this->success('添加成功', U('admin/index'));
        } else {
            $this->error('添加失败');
        }
    }

    /**
     * 管理员修改界面
     */
    public function edit()
    {
        checkAdminLogin();
        checkIsSuperAdmin();
        $Admin = D('admin');
        $id = I('id');
        $rows = $Admin->where(array(
            'id' => $id
        ))->find();
        $this->assign('name', $rows['name']);
        $this->assign('password', $rows['password']);
        $this->assign('email', $rows['email']);
        $this->assign('id', $id);
        $this->display();
    }

    /**
     * 修改逻辑
     */
    public function doedit()
    {
        checkAdminLogin();
        $Admin = D('admin');
        $Admin->create();
        if ($Admin->save()) {
            $this->success('修改成功', U('admin/index'));
        } else {
            $this->error('修改失败');
        }
    }

    /**
     * 删除管理员
     */
    public function del()
    {
        checkAdminLogin();
        checkIsSuperAdmin();
        $Admin = D('admin');
        $id = I('id');
        $flag = $Admin->where(array(
            'id' => $id
        ))->delete();
        if ($flag) {
            $this->success('删除成功', U('admin/index'));
        } else {
            $this->error('删除失败');
        }
    }

    /**
     * 检查验证码
     * 
     * @param unknown $code
     *            提交的验证码
     * @param number $id
     *            验证码id
     */
    public function checkVerify($code, $id = 1)
    {
        $verify = new \Think\Verify();
        return $verify->check($code, $id);
    }

    /**
     * 检查管理员是否存在
     * 
     * @param unknown $name
     *            提交的管理员名
     */
    public function checkadminExist($name)
    {
        $Admin = D('admin');
        $flag = $Admin->where(array(
            'name' => $name
        ))->select();
        if ($flag) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 登出
     */
    public function logout()
    {
        session('adminid', null);
        session('name', null);
        $this->success('退出成功', U('admin/login'));
    }
}