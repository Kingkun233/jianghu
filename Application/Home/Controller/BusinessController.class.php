<?php
namespace Home\Controller;
use Think\Controller;
class BusinessController extends Controller{
    /**
     * 添加商户
     */
    public function add(){
        $type=400;
        loginPermitApiPreTreat($type);
        $Busis=D('business');
        $User=D('user');
        $logo=imageUpload();
        $add['name']=I('name');
        $add['addr']=I('addr');
        $add['latitude']=I('latitude');
        $add['discription']=I('discription');
        $add['state']=1;
        $add['joindate']=date('Y-m-d');
        $add['logourl']=$logo['url'][0];
        $add['logopath']=$logo['path'][0];
        $add['phone']=I('phone');
        $add['user_id']=I('user_id');
        $Busis->add($add);
        $this->ajaxReturn(responseMsg(0, $type));
    }
    /**
     * 返回商户详细信息,顺便更新商户星级
     */
    public function showDetail() {
        $type=401;
        if($type!=I('type')){
            $this->ajaxReturn(responseMsg(5, $type));
        }
        $Busi=D('business');
        $Intro=D('introduce');
        $business_id=I('business_id');
        $msg=$Busi->where(array('id'=>$business_id))->find();
        //整合最高两个度数的个数
        $degrees2=$Intro->where(array('business_id'=>$business_id))->field("degree,alldegree")->select();
        $star=0;
        foreach($degrees2 as $k=>$v){
            $alldegree+=$v['alldegree'];
            $degrees[]=$v['degree'];
        }
        $countarray=array_count_values($degrees);
        foreach ($countarray as $k=>$v){
            if(count($countarray)<=2){
                break;
            }else{
                unset($countarray[$k]);
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
        $msg['degree']=$countarray;
        $msg['star']=$star;
        $this->ajaxReturn(responseMsg(0, $type,$msg));
    }
}