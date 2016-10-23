<?php
namespace Admin\Controller;
use Think\Controller;
class SearchController extends Controller{
    /**
     * 搜索方式处理器
     */
    public function doaction(){
        $cate=I('cate');
        $searchkey=I('searchkey');
//         dump($searchkey);die;
        switch ($cate){
            case 1 : $this->redirect("search/searchByUsername",array('cate'=>$cate,"searchkey"=>$searchkey));
                            break;
            case 2 : $this->redirect("search/searchByIntroKey",array('cate'=>$cate,"searchkey"=>$searchkey));
                            break;
            case 3 : $this->redirect("search/searchByDegree",array('cate'=>$cate,"searchkey"=>$searchkey));
                            break;
            case 4 : $this->redirect("search/searchByTime",array('cate'=>$cate,"searchkey"=>$searchkey));
                            break;
            case 5 : $this->redirect("search/searchByDomain",array('cate'=>$cate,"searchkey"=>$searchkey));
                            break;
        }
    }
    /**
     * 通过用户名找到推荐
     */
    public function searchByUsername(){
        $Introduce=D("introduce");
        $User=D('user');
        $cate=I('cate');
//         dump($cate);die;
        $username=I('searchkey');
//         dump($username);die;
        $where['username']=array('like','%'.$username.'%');
        $user_ids=$User->where($where)->field('id')->select();
        foreach ($user_ids as $k=>$v){
            $uids[]=$v['id'];
        }
        //where的sql语句
        if($uids){
            $sql="u.id IN (".implode(',',$uids).")";
            //         dump($sql);die;
        }else {
            $sql="u.id=0";
        }
        $this->redirect("introduce/showIntroduce",array('where'=>$sql,'cate'=>$cate,'searchkey'=>$username));
    }
    /**
     * 通过id得到该用户所有的推荐
     */
    public function searchByUid(){
        $user_id=I('user_id');
        $sql="u.id=".$user_id;
        $this->redirect("introduce/showIntroduce",array('where'=>$sql));
    }
    /**
     * 通过推荐关键字搜索推荐
     */
    public function searchByIntroKey(){
        $Intro=D('introduce');
        $introkey=I('searchkey');
        $cate=I('cate');
        $where['text']=array("like","%".$introkey."%");
        $rows=$Intro->where($where)->select();
        foreach ($rows as $k=>$v){
            $intro_ids[]=$v['id'];
        }
        //where的sql语句
        if($intro_ids){
            $sql="m.id IN (".implode(',',$intro_ids).")";
            //         dump($sql);die;
        }else {
            $sql="m.id=0";
        }
        $this->redirect("introduce/showIntroduce",array('where'=>$sql,'cate'=>$cate,'searchkey'=>$introkey));
    }
    /**
     * 通过度数
     */
    public function searchByDegree(){
        $Intro=D('introduce');
        $cate=I('cate');
        $degree=I('searchkey');
        $rows=$Intro->where(array('degree'=>$degree))->select();
        foreach ($rows as $k=>$v){
            $intro_ids[]=$v['id'];
        }
        //where的sql语句
        if($intro_ids){
            $sql="m.id IN (".implode(',',$intro_ids).")";
            //         dump($sql);die;
        }else {
            $sql="m.id=0";
        }
        $this->redirect("introduce/showIntroduce",array('where'=>$sql,'cate'=>$cate,'searchkey'=>$degree));
    }
    /**
     * 通过time得到推荐
     */
    public function searchByTime(){
        $Intro=D('introduce');
        $cate=I('cate');
        $time=I('searchkey');
        $where['time']=array('like','%'.$time.'%');
        $rows=$Intro->where($where)->select();
        foreach ($rows as $k=>$v){
            $intro_ids[]=$v['id'];
        }
        //where的sql语句
        if($intro_ids){
            $sql="m.id IN (".implode(',',$intro_ids).")";
            //         dump($sql);die;
        }else {
            $sql="m.id=0";
        }
        $this->redirect("introduce/showIntroduce",array('where'=>$sql,'cate'=>$cate,'searchkey'=>$time));
    }
    /**
     * 通过领域寻找
     */
    public function searchByDomain(){
        $Intro=D('introduce');
        $Domain=D('introduce_domain');
        $cate=I('cate');
        $domain=I('searchkey');
        $where['name']=array('like','%'.$domain.'%');
        $rows=$Domain->where($where)->field('introduce_id')->select();
        foreach ($rows as $k=>$v){
            $intro_ids[]=$v['introduce_id'];
        }
        //where的sql语句
        if($intro_ids){
            $sql="m.id IN (".implode(',',$intro_ids).")";
            //         dump($sql);die;
        }else {
            $sql="m.id=0";
        }
        $this->redirect("introduce/showIntroduce",array('where'=>$sql,'cate'=>$cate,'searchkey'=>$domain));
    }
}