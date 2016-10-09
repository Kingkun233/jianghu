<?php
namespace Admin\Controller;
use Think\Controller;
class UserController extends Controller{
    /**
     * 用户列表
     */
    public function index(){
    if(!checkAdminLogin()){
            $this->error("管理员未登录");
        }
        $user=D('user');
        $count=$user->count();
        $Page=new \Think\Page($count,3);
        $pageshow=page($Page);
        $list=$user
        ->limit($Page->firstRow . ',' . $Page->listRows)
        ->select();
        $this->assign('user',$list);
        $this->assign('page',$pageshow);
        $this->display();
    }
    /**
     * 删除用户
     */
    public function del(){
    if(!checkAdminLogin()){
            $this->error("管理员未登录");
        }
        $User=D('user');
        $id=I('id');
        $face=$User->where(array('id'=>$id))->getField('path');
        unlink($face);
        $flag=$User->where(array('id'=>$id))->delete();
        if ($flag){
            $this->success('删除成功',U('user/index'));
        }else{
            $this->error('删除失败');
        }
    }
    /**
     * 用户添加界面
     */
    public function add(){
    if(!checkAdminLogin()){
            $this->error("管理员未登录");
        }
        $this->display();
    }
    /**
     * 用户添加逻辑
     */
    public function doadd(){
    if(!checkAdminLogin()){
            $this->error("管理员未登录");
        }
        
        $User=D('user');
        $data['username']=I('username');
        if(checkUserExist($data['username'])){
            $this->error('用户已存在');
        }
        $data['password']=I('password');
        $data['email']=I('email');
        $image=imageUpload();
        $face=$image['url'];
        $path=$image['path'];
        $data['face']=$face[0];
        $data['path']=$path[0];
        $flag=$User->add($data);
        if ($flag){
            $this->success('添加成功',"user/index");
        }else {
            $this->error('添加失败');
        }
    }
}
