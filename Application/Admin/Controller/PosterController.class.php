<?php
namespace Admin\Controller;

use Think\Controller;

class PosterController extends Controller
{

    /**
     * 添加海报页面
     */
    public function add()
    {
        $this->display();
    }

    /**
     * 添加海报逻辑
     */
    public function doadd()
    {
        $Poster = D('poster');
        $count = $Poster->where(array('state'=>0))->count();
        if ($count >= 5) {
            $this->error("最多只能有五张海报哦！", U('poster/index'));
        } else {
            $poster = imageUpload();
            $add['posterurl'] = $poster['url'][0];
            $add['posterpath'] = $poster['path'][0];
            $add['title'] = I('title');
            $add['content'] = I('content');
//             dump($add['content']);die;
            $add['time'] = date("Y-m-d H:i:s");
            $flag = $Poster->add($add);
            if ($flag) {
                $this->success("海报添加成功", U('poster/index'));
            } else {
                $this->error("海报添加失败");
            }
        }
    }

    /**
     * 海报列表
     */
    public function index()
    {
        $Poster = D('poster');
        $poster = $Poster->where(array("state"=>0))->order("time desc")->select();
        foreach ($poster as $k=>$v){
            if($poster[$k]["state"]){
                $poster[$k]["state"]="已过期";
            }else{
                $poster[$k]["state"]="正在运营";
            }
        }
        $this->assign('poster', $poster);
        $this->display();
    }
    /**
     * 海报已到期
     */
    public function overtime(){
        $Poster=D('poster');
        $poster_id=I('id');
        if($Poster->where(array('id'=>$poster_id))->save(array("state"=>1))){
            $this->success("操作成功",U('poster/index'));
        }else{
            $this->error("操作失败");
        }
    }
    /**
     * 海报列表
     */
    public function overtimeList()
    {
        $Poster = D('poster');
        $poster = $Poster->where(array("state"=>1))->order("time desc")->select();
        foreach ($poster as $k=>$v){
            if($poster[$k]["state"]){
                $poster[$k]["state"]="已过期";
            }else{
                $poster[$k]["state"]="正在运营";
            }
        }
        $this->assign('poster', $poster);
        $this->display();
    }
    /**
     * 已过期的海报再次操作
     */
    public function intime(){
    $Poster=D('poster');
        $poster_id=I('id');
        $count = $Poster->where(array('state'=>0))->count();
        if ($count >5) {
            $this->error("最多只能有五张海报哦！", U('poster/index'));
        } else {
            //更新发布日期
            $Poster->where(array('id'=>$poster_id))->save(array('time'=>date("Y-m-d H:i:s")));
            if($Poster->where(array('id'=>$poster_id))->save(array("state"=>0))){
                $this->success("操作成功",U('poster/index'));
            }else{
                $this->error("操作失败");
            }
        }
    }
    /**
     * 海报详情
     */
    public function detail(){
        $Poster=D('poster');
        $poster_id=I('id');
        $poster=$Poster->where(array('id'=>$poster_id))->select();
        $content=html_entity_decode($poster[0]['content']);
//         dump($content);die;
        $this->assign('content',$content);
        $this->assign('rows',$poster);
        $this->display();
    }
}