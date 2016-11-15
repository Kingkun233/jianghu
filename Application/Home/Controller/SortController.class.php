<?php
namespace Home\Controller;
use Think\Controller;
class SortController extends Controller{
    /**
     * 根据口碑给用户排序
     */
    public function sortList(){
        $type=700;
        loginPermitApiPreTreat($type);
        $User=D('user');
        $Friend=D('friend');
        $user_id=I('user_id');
         //得到朋友的id 二维数组
        $fids=$Friend->where(array('user_id'=>$user_id))->field('friend_id')->select();
         //转为一维数组
        foreach ($fids as $k=>$v){
            $fid[]=$v['friend_id'];
        }
        //先将sort表全部排好序
        $where['id']=array('IN',$fid);
        $sortall=$User->where($where)->order('praisenum desc')->select();
        $i=0;
        foreach ($sortall as $k=>$v){
                $show=null;
            //如果user_id在朋友id里
                $i=$i+1;
                $show['user_id']=$user_id;
                $show['ranking']=$i;
                $show['username']=$v['username'];
                $show['faceurl']=$v['faceurl'];
                $show['praisenum']=$v['praisenum'];
                $showall[]=$show;
        }
        $this->ajaxReturn(responseMsg(0, $type,$showall));
    }
}