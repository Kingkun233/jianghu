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
        $searchkey=I('searchkey');
        //得到页数
        $where['username']=array('like',"%".$searchkey."%");
        $count=$user->where($where)
        ->count();
        $Page=new \Think\Page($count,3);
        $pageshow=page($Page);
        $list=$user
        ->limit($Page->firstRow . ',' . $Page->listRows)
        ->where($where)
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
        checkAdminLogin();
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
     * 禁用用户
     */
    public function ban(){
        checkAdminLogin();
        $User=D("user");
        $user_id=I("id");
        $flag=$User->where(array("id"=>$user_id))->save(array("isban"=>'1'));
        if($flag){
            $this->success("禁用成功");
        }else{
            $this->error("禁用失败");
        }
    }
    /**
     * 解除禁用
     */
    public function unban(){
        checkAdminLogin();
        $User=D("user");
        $user_id=I("id");
        $flag=$User->where(array("id"=>$user_id))->save(array("isban"=>'0'));
        if($flag){
            $this->success("解除成功");
        }else{
            $this->error("解除失败");
        }
    }
    /**
     * 用户详情
     */
    public function userdetails(){
        checkAdminLogin();
        $User=D("user");
        $username=I("username");
        $rows=$User->where(array("username"=>$username))->select();
        $backurl = empty($_SERVER['HTTP_REFERER']) && strstr($_SERVER['HTTP_REFERER'],
         $_SERVER['HTTP_HOST']) ?  '#' : $_SERVER['HTTP_REFERER'];
        $this->assign("rows",$rows);
        $this->assign('backurl', $backurl);
        $this->display();
    }
    
}
