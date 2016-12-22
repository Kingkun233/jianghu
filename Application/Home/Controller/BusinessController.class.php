<?php
namespace Home\Controller;
use Think\Controller;
class BusinessController extends Controller{
    /**
     * 添加商户
     */
    public function add(){
        $type=400;
        $post=loginPermitApiPreTreat($type);
        $Busis=D('business');
        $User=D('user');
        $Domain=D('domain');
        $logo=imageUpload();
        $add['name']=$post['name'];
        $add['addr']=$post['addr'];
        $add['latitude']=$post['latitude'];
        $add['discription']=$post['discription'];
        $add['state']=1;
        $add['joindate']=date('Y-m-d');
        $add['logourl']=$logo['url'][0];
        $add['logopath']=$logo['path'][0];
        $add['phone']=$post['phone'];
        $add['user_id']=$post['user_id'];
        $domain_id=$post['domain_id'];
        $add['domain']=$Domain->where(array('id'=>$domain_id))->getField("name");
        $add['website']=$post['website'];
        $Busis->add($add);
        $this->ajaxReturn(responseMsg(0, $type));
    }
    /**
     * 返回商户详细信息,顺便更新商户星级
     */
    public function showDetail() {
        $type=401;
        $post=touristApiPreTreat($type);
        $Busi=D('business');
        $Intro=D('introduce');
        $business_id=$post['business_id'];
        $msg=$Busi->where(array('id'=>$business_id))->find();
        //整合最高两个度数的个数
        $transdegree=null;
        $degrees2=$Intro->where(array('business_id'=>$business_id))->field("degree,alldegree")->select();
        $star=0;
        foreach($degrees2 as $k=>$v){
            $alldegree+=$v['alldegree'];
            $degrees[]=$v['degree'];
        }
        $countdegree=array_count_values($degrees);
        foreach ($countdegree as $k=>$v){
            if(count($countdegree)<=2){
                break;
            }else{
                unset($countdegree[$k]);
            }
        }
        if(count($countdegree)==2){
            foreach ($countdegree as $k=>$v){
                $transdegree['second_degree']=$k;
                $transdegree['second_degree_num']=$countdegree[$k];
                unset($countdegree[$k]);
                break;
            }
            foreach ($countdegree as $k=>$v){
                $transdegree['highest_degree']=$k;
                $transdegree['highest_degree_num']=$countdegree[$k];
                unset($countdegree[$k]);
                break;
            }
        }else{
            foreach ($countdegree as $k=>$v){
                $transdegree['highest_degree']=$k;
                $transdegree['highest_degree_num']=$countdegree[$k];
                unset($countdegree[$k]);
                break;
            }
        }
        //更新star
        if($alldegree<=300){
            $star=3;
        }else if($alldegree<=500){
            $star=4;
        }else {
            $star=5;
        }
        $originstar=$Busi->where(array('id'=>$business_id))->getField('star');
        if($originstar<$star){
            $Busi->where(array('id'=>$business_id))->save(array('star'=>$star));
        }
        $msg['degree']=$transdegree;
        $msg['star']=$star;
        $this->ajaxReturn(responseMsg(0, $type,$msg));
    }
    /**
     * 添加商户评论
     */
    public function addBusinessComment(){
        $type=402;
        $post=loginPermitApiPreTreat($type);
        $BusiComment=D('business_comment');
        $user_id=$post['user_id'];
        $business_id=$post['business_id'];
        $comment=$post['comment'];
        $data=$BusiComment->create($post);
        $BusiComment->add($data);
        $this->ajaxReturn(responseMsg(0, $type));
    }
    /**
     * 返回商户评论
     */
    public function returnBusinessComment(){
        $type=403;
        $post=touristApiPreTreat($type);
        $BusiComment=D('business_comment');
        $User=D('user');
        $business_id=$post['business_id'];
        $comments=$BusiComment->where(array('business_id'=>$business_id))->select();
        foreach($comments as $k=>$v){
            $comments[$k]['username']=$User->where(array('id'=>$v['user_id']))->getField('username');
            $comments[$k]['faceurl']=$User->where(array('id'=>$v['user_id']))->getField('faceurl');
        }
        $this->ajaxReturn(responseMsg(0, $type,$comments));
    }
}