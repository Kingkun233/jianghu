<?php
namespace Admin\Controller;
use Think\Controller;

class IndexController extends Controller {
    /**
     * 主框架
     */
    public function index(){
    if(!checkAdminLogin()){
            $this->error("管理员未登录");
        }
        $loginadmin=session('name');
        $this->assign('loginadmin',$loginadmin);
        $this->display();
    }
    /**
     * mainframe主界面
     */
    public function main(){
    if(!checkAdminLogin()){
            $this->error("管理员未登录");
        }
        $this->display();
    }
}