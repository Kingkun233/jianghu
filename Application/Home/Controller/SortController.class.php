<?php
namespace Home\Controller;
use Think\Controller;
class SortController extends Controller{
    /**
     * 根据口碑给用户排序
     */
    public function sort(){
        $User=D('user');
        $Friend=D('friend');
        $user_id=I('userid');
         //得到朋友的id 二维数组
        $fids=$Friend->where(array('user_id'=>$user_id))->field('friend_id')->select();
         //转为一维数组
        foreach ($fids as $k=>$v){
            $fid[]=$v['friend_id'];
        }
//         dump($fid);die;
        //先将sort表全部排好序，再从里面找出好友
        $sortall=$User->order('praisenum desc')->select();
//         dump($sortall);die;
        $i=0;
//         dump($sortall);die;
        foreach ($sortall as $k=>$v){
            $show=null;
            //如果user_id在朋友id里
            if(in_array($v['id'], $fid)){
                $i=$i+1;
                $show['ranking']=$i;
                $show['username']=$v['username'];
                $show['faceurl']=$v['faceurl'];
                $show['praisenum']=$v['praisenum'];
                $showall[]=$show;
            }
        }
        dump($showall);die;
        $this->ajaxReturn($showall);
    }
}