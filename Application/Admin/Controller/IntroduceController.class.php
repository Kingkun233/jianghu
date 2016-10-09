<?php
namespace Admin\Controller;
use Think\Controller;
class IntroduceController extends Controller{
    public function index(){
    if(!checkAdminLogin()){
            $this->error("管理员未登录");
        }
        $Introduce=D('introduce');
        $Img=D('introduce_images');
        $count=$Introduce->count();
        $Page=new \Think\Page($count,3);
        $pageshow=page($Page);
//         $mids=$Introduce->field('id')->limit($Page->firstRow . ',' . $Page->listRows)->select();
//         foreach ($mids as $k=>$v){
//             $images=$Img->where(array('mid'=>$v['id']))->field('image')->select();
//         }
//         foreach ($images as $k=>$v){
//             $image[]=$v['image'];
//         }
        $list=$Introduce
        ->table('jianghu_introduce m')
        ->join('left join jianghu_user u on m.user_id=u.id')
        ->field('m.*,u.username')
        ->limit($Page->firstRow . ',' . $Page->listRows)
        ->select();
//         foreach ($list as $k=>$v){
//             $v['image']=$image[];
//         }
        $this->assign('introduce',$list);
        $this->assign('page',$pageshow);
        $this->display();
    }
    /**
     * 删除推送
     */
    public function del(){
    if(!checkAdminLogin()){
            $this->error("管理员未登录");
        }
        $Introduce=D('introduce');
        $Img=D('introduce_images');
        $id=I('id');
        $images=$Img->where(array('intoduce_id'=>$id))->select();
        foreach ($images as $k=>$v){
            unlink($v['path']);
        }
        $flag=$Introduce->where(array('id'=>$id))->delete();
        if ($flag){
            $this->success('删除成功',U('introduce/index'));
        }else {
            $this->error('删除失败');
        }
    }
    /**
     * 推送详情
     */
    public function showdetail(){
    if(!checkAdminLogin()){
            $this->error("管理员未登录");
        }
        $Introduce=D('introduce');
        $Img=D('introduce_images');
        $User=D('user');
        $id=I('id');
        $introduce=$Introduce->where(array('id'=>$id))->find();
        $uid=$Introduce->where(array('id'=>$id))->getField('user_id');
        $intro_id=$Introduce->where(array('id'=>$id))->getField('id');
        $images=$Img->where(array('introduce_id'=>$intro_id))->select();
        foreach ($images as $k=>$v){
            $image[]=$v['imageurl'];
        }
        $username=$User->where(array('id'=>$uid))->getField('username');
        $backurl = empty($_SERVER['HTTP_REFERER']) && strstr($_SERVER['HTTP_REFERER'],
         $_SERVER['HTTP_HOST']) ?  '#' : $_SERVER['HTTP_REFERER'];
        $this->assign('username',$username);
        $this->assign('content',$introduce['text']);
        $this->assign('image',$image);
        $this->assign('backurl', $backurl);
        $this->display();
    }
}
