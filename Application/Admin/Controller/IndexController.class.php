<?php
namespace Admin\Controller;
use Think\Controller;

class IndexController extends Controller {
    /**
     * 管理员登录检查
     */
    public function __construct(){
        parent::__construct();
        checkAdminLogin();
    }
    /**
     * 主框架
     */
    public function index(){
        $loginadmin=session('name');
        $this->assign('loginadmin',$loginadmin);
        $this->display();
    }
    /**
     * mainframe主界面
     */
    public function main(){
        $this->display();
    }
}