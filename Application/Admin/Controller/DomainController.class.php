<?php
namespace Admin\Controller;
use Think\Controller;
class DomainController extends Controller{
    /**
     * 管理员登录检查
     */
    public function __construct(){
        parent::__construct();
        checkAdminLogin();
    }
    /**
     * 领域列表
     */
    public function index(){
        $Domain=D('domain');
        $count=$Domain->count();
        $Page=new \Think\Page($count,10);
        $pageshow=page($Page);
        $list=$Domain->limit($Page->firstRow . ',' . $Page->listRows)
        ->select();
        $this->assign('domain',$list);
        $this->assign('page',$pageshow);
        $this->display();
    }
    /**
     * 添加领域页面
     */
    public function add(){
        $this->display();
    }
    /**
     * 添加领域逻辑
     */
    public function doadd(){
        $Domain=D('domain');
        $add['name']=I('name');
        $Domain->add($add);
        $this->success('添加成功',U('domain/index'));
    }
    /**
     * 删除领域
     */
    public function del(){
        $Domain=D("domain");
        $id=I('id');
        $Domain->where(array('id'=>$id))->delete();
        $this->success('添加成功',U('domain/index'));
    }
}