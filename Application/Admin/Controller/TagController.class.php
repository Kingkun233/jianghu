<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Page;
class TagController extends Controller{
    /**
     * 搜索标签列表
     */
    public function index(){
    if(!checkAdminLogin()){
            $this->error("管理员未登录");
        }
        $Tag=D('tag');
        $count=$Tag->count();
        $Page=new Page($count,3);
        $pageshow=page($Page);
        $list=$Tag->limit($Page->firstRow . ',' . $Page->listRows)
        ->select();
        $this->assign('tag',$list);
        $this->assign('page',$pageshow);
        $this->display();
    }
    /**
     * 添加标签页面
     */
    public function add(){
    if(!checkAdminLogin()){
            $this->error("管理员未登录");
        }
        $this->display();
    }
    /**
     * 添加标签逻辑
     */
    public function doadd(){
    if(!checkAdminLogin()){
            $this->error("管理员未登录");
        }
        $Tag=D('tag');
        $Tag->create();
        $flag=$Tag->add();
        if ($flag){
            $this->success('添加成功',U("tag/index"));
        }else {
            $this->error('添加失败');
        }
    }
    /**
     * 标签编辑界面
     */
    public function edit(){
    if(!checkAdminLogin()){
            $this->error("管理员未登录");
        }
        $Tag=D('tag');
        $id=I('id');
        $name=$Tag->where(array('id'=>$id))->getField('name');
        $this->assign('id',$id);
        $this->assign('name',$name);
        $this->display();
    }
    /**
     * 标签编辑逻辑
     */
    public function doedit(){
    if(!checkAdminLogin()){
            $this->error("管理员未登录");
        }
        $Tag=D('tag');
        $id=I('id');
        $data['name']=I('name');
        $flag=$Tag->where(array('id'=>$id))->save($data);
        if($flag){
            $this->success('修改成功',U('tag/index'));
        }else{
            $this->error('修改失败');
        }
    }
    /**
     * 删除标签
     */
    public function del(){
    if(!checkAdminLogin()){
            $this->error("管理员未登录");
        }
        $Tag=D('tag');
        $id=I('id');
        $flag=$Tag->where(array('id'=>$id))->delete();
        if ($flag){
            $this->success('删除成功',U('tag/index'));
        }else{
            $this->error('删除失败');
        }
    }
}