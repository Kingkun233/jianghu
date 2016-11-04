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
}