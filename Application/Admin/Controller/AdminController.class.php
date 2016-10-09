<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Page;

class AdminController extends Controller{
    /**
     * 登录页面
     */
    public function login(){
        $this->display();
    }
    /**
     * 登录逻辑
     */
    public function dologin(){
       $Admin=D('admin');
       $name=I('name');
       $password=I('password');
       $code=I('verify');
       if (!$name||!$password){
           $this->error('用户名或密码不能为空');
       }
       if(!$this->checkadminExist($name)){
           $this->error('用户不存在');
       }
       if(!$this->checkVerify($code)){
           $this->error('验证码错误');
       }
       $dbpassword=$Admin->where(array('name'=>$name))->getField('password');
       $adminid=$Admin->where(array('name'=>$name))->getField('id');
       if($password==$dbpassword){
           session('name',$name);
           session('adminid',$adminid);
           $this->success('登录成功',U('index/index'));
       }else {
           $this->error('密码错误');
       }
    }
    /**
     * 管理员列表
     */
    public function index(){
        if(!checkAdminLogin()){
            $this->error("管理员未登录");
        }
        $Admin=D('admin');
        $count=$Admin->count();
        $Page=new Page($count,3);
        $pageshow=page($Page);
        $list=$Admin
        ->limit($Page->firstRow . ',' . $Page->listRows)
        ->select();
        $this->assign('admin',$list);
        $this->assign('page',$pageshow);
        $this->display();
    }
   
    /**
     * 生成验证码
     */
    public function getVerify($id = 1) {
        $verify = new \Think\Verify ();
        $verify->entry ( $id );
    }
    /**
     * 添加管理员界面
     */
    public function add(){
        if(!checkAdminLogin()){
            $this->error("管理员未登录");
        }
        $this->display();
    }
    /**
     * 添加管理员逻辑
     */
    public function doadd(){
    if(!checkAdminLogin()){
            $this->error("管理员未登录");
        }
        $Admin=D('admin');
        $Admin->create();
        if($Admin->add()){
            $this->success('添加成功',U('admin/index'));
        }else {
            $this->error('添加失败');
        }
    }
    /**
     * 管理员修改界面
     */
    public function edit(){
    if(!checkAdminLogin()){
            $this->error("管理员未登录");
        }
        $Admin=D('admin');
        $id=I('id');
        $rows=$Admin->where(array('id'=>$id))->find();
        $this->assign('name',$rows['name']);
        $this->assign('password',$rows['password']);
        $this->assign('email',$rows['email']);
        $this->assign('id',$id);
        $this->display();
    }
    /**
     * 修改逻辑
     */
    public function doedit(){
    if(!checkAdminLogin()){
            $this->error("管理员未登录");
        }
        $Admin=D('admin');
        $Admin->create();
        if($Admin->save()){
            $this->success('修改成功',U('admin/index'));
        }else {
            $this->error('修改失败');
        }
    }
    /**
     * 删除管理员
     */
    public function del(){
    if(!checkAdminLogin()){
            $this->error("管理员未登录");
        }
        $Admin=D('admin');
        $id=I('id');
        $flag=$Admin->where(array('id'=>$id))->delete();
        if ($flag){
            $this->success('删除成功',U('admin/index'));
        }else {
            $this->error('删除失败');
        }
    }
    
    /**
     * 检查验证码
     * @param unknown $code 提交的验证码
     * @param number $id 验证码id
     */
    public function checkVerify($code, $id = 1){
        $verify = new \Think\Verify();
        return $verify->check($code, $id);}
        
    /**
     * 检查管理员是否存在
     * @param unknown $name 提交的管理员名
     */
    public function checkadminExist($name){
        $Admin=D('admin');
        $flag=$Admin->where(array('name'=>$name))->select();
        if ($flag){
            return true;
        }else{
            return false;
        }
    }
    /**
     * 登出
     */
    public function logout(){
        checkAdminLogin();
        session('[destroy]');
        $this->success('退出成功',U('admin/login'));
    }
}