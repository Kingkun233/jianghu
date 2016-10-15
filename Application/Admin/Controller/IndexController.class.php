<?php
namespace Admin\Controller;
use Think\Controller;

class IndexController extends Controller {
    /**
     * 主框架
     */
    public function index(){
    checkAdminLogin();
        $loginadmin=session('name');
        $this->assign('loginadmin',$loginadmin);
        $this->display();
    }
    /**
     * mainframe主界面
     */
    public function main(){
    checkAdminLogin();
        $this->display();
    }
}