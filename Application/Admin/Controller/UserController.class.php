<?php
namespace Admin\Controller;
use Think\Controller;
class UserController extends Controller{
    /**
     * 用户列表
     */
    public function index(){
    checkAdminLogin();
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
    checkAdminLogin();
        $User=D('user');
        $id=I('id');
        $facepath=$User->where(array('id'=>$id))->getField('facepath');
        //删除照片
        unlink($facepath);
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
    checkAdminLogin();
        $this->display();
    }
    /**
     * 用户添加逻辑
     */
    public function doadd(){
    checkAdminLogin();
        
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
        $data['faceurl']=$face[0];
        $data['facepath']=$path[0];
        $flag=$User->add($data);
        if ($flag){
            $this->success('添加成功',U("user/index"));
        }else {
            $this->error('添加失败');
        }
    }
    /**
     * 查看每天数据
     */
    public function showDailyNum(){
        $Joinnum=D("daily_num");
        $num=$Joinnum->select();
        $last=0;
        foreach($num as $k=>$v){
            $show=array();
            $show['date']=$v['date'];
            $show['commentnum']=$v['commentnum'];
            $show['praisenum']=$v['praisenum'];
            $show['joinnum']=$v['joinnum'];
            $show['lognum']=$v['lognum'];
            $show['stay']=round($v['keep']/$last*100,2);
            $show1[]=$show;
            $last=$Joinnum->where(array("date"=>$v['date']))->getField("joinnum");
        }
//         dump($show);
        $this->assign("num",$show1);
        $this->display();
    }
    /**
     * 禁用用户（不能登录）
     */
    public function ban(){
        $User=D("User");
        
    }
}
