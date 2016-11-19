<?php
namespace Home\Controller;
use Think\Controller;
class UserController extends Controller
{

    /**
     * 注册接口 
     */
    public function join()
    {
        $type=100;
        if($type!=I("type")){
            $this->ajaxReturn(responseMsg(5,$type));//调用错误
        }
        $User = D('user');
        $Friend=D('friend');
        $Token=D('token');
        $LoginCount=D('login_count');
        $Joinnum=D("daily_num");
        $UserDomain=D('user_domain');
        $Domain=D('domain');
        $data['username'] = I('username');
        if (checkUserExist($data['username'])) {
            $this->ajaxReturn(responseMsg(6,$type)); // 用户已存在
        }
        
        $data['password'] = md5(I('password'));
        $data['phonenum'] = I('phonenum');
        $domain_ids=I('domain_id');
        if ('男' == I('sex')) {
            $data['sex'] = 0; // 男
        } else {
            $data['sex'] = 1; // 女
        }
        $data['addr'] = I('addr');
        $data['jointime'] = date('Y-m-d');
        //把数据插入用户表
        $flag1 = $User->add($data);
        $user_id=$flag1;
        //把数据插入领域表
        $add1['user_id']=$flag1;
        foreach ($domain_ids as $k=>$v){
            $domain_name=$Domain->where(array('id'=>$v['id']))->getField('name');
            if($v['id']){
                $domain_names[]=$domain_name;
                $add1['domain']=$domain_name;
                $UserDomain->add($add1);
            }
        }
        //自己和自己成为好友关系
            //flag1为用户id
        $add['user_id']=$flag1;
        $add['friend_id']=$add['user_id'];
        $flag2=$Friend->add($add);
        //每日注册量加一
        
        $today=date("Y-m-d");
        if($Joinnum->where(array("date"=>$today))->select()){
            $flag4=$Joinnum->where(array("date"=>$today))->setInc("joinnum");
        }else {
            $data1['date']=$today;
            $data1['joinnum']=1;
            $flag4=$Joinnum->add($data1);
        }
        
        //顺便登录
            //生成token
        $timestamp=time();
        $str1=array($user_id,$timestamp);
        $str=implode($str1);
        $add_token['token']=md5($str);
        $add_token['user_id']=$user_id;
        $add_token['time']=date("Y-m-d H:i:s");
        $Token->add($add_token);
            //每日登陆人数加一
        $today=date("Y-m-d");
        if($Joinnum->where(array("date"=>$today))->select()){
            $Joinnum->where(array("date"=>$today))->setInc("lognum");
        }else {
            $data1['date']=$today;
            $data1['lognum']=1;
            $Joinnum->add($data1);
        }
            //查看是不是留存用户，是的话keep++
        $jointime=$User->where(array("id"=>$user_id))->getField("jointime");
        $jointime=date("Y-m-d",strtotime($jointime));
        $yesterday=date("Y-m-d",strtotime("-1 day"));
        if($jointime==$yesterday){
            $Joinnum->where(array("date"=>$today))->setInc("keep");
        }
            //插入登陆统计表
        $add_lc['user_id']=$user_id;
        $add_lc['date']=date("Y-m-d");
        $LoginCount->add($add_lc);
            //登陆加一口碑
        $User->where(array('id'=>$user_id))->setInc('praisenum');
            //整合要返回的数据
        $msg=$User->where(array("id"=>$user_id))->find();
        $msg['token']=$add_token['token'];
        $msg['domain']=$domain_names;
        //判断上述操作是否成功
//         dump(array($flag1,$flag2,$flag3,$flag4));die;
        if ($flag1&&$flag2&&$flag4) {
            $this->ajaxReturn(responseMsg(0,$type,$msg)); // 注册成功
        } else {
//             $flag[]=[$flag1,$flag2,$flag3,$flag4];
//             dump($flag);die;
            $this->ajaxReturn(responseMsg(1,$type)); //注册失败
        }
    }

    /**
     * 登录接口
     */
    public function login()
    {
        $type=101;
        if($type!=I("type")){
            $this->ajaxReturn(responseMsg(5,$type));
        }
        $User = D('user');
        $Joinnum=D("daily_num");
        $Token=D('token');
        $UserDomain=D('user_domain');
        $LoginCount=D('login_count');
        $data['phonenum'] = I('phonenum');
        $data['password'] = md5(I('password'));
        $flag = $User->where(array(
            'phonenum' => $data['phonenum']
        ))->select();
        if (! flag) {
            $this->ajaxReturn(responseMsg(4,$type)); // 用户不存在
        }
        //检查用户被禁用
        $user_id = $User->where(array(
            'phonenum' => $data['phonenum']
        ))->getField('id');
        if (isban($user_id)) {
            $this->ajaxReturn(responseMsg(3,$type)); // 用户被禁用
        }
        //获取数据库密码
        $dbpassword = $User->where(array(
            'phonenum' => $data['phonenum']
        ))->getField('password');
        //和传过来的密码比较
        if ($dbpassword == $data['password']) {
            //匹配的话插入token表
                //生成token
            $timestamp=time();
            $str1=array($user_id,$timestamp);
            $str=implode($str1);
            $add_token['token']=md5($str);
            $add_token['user_id']=$user_id;
            $add_token['time']=date("Y-m-d H:i:s");
            $Token->add($add_token);
            //每日登陆人数加一
            $today=date("Y-m-d");
            if($Joinnum->where(array("date"=>$today))->select()){
                $Joinnum->where(array("date"=>$today))->setInc("lognum");
            }else {
                $data1['date']=$today;
                $data1['lognum']=1;
                $Joinnum->add($data1);
            }
            //查看是不是留存用户，是的话keep++
            $jointime=$User->where(array("id"=>$user_id))->getField("jointime");
            $jointime=date("Y-m-d",strtotime($jointime));
            $yesterday=date("Y-m-d",strtotime("-1 day"));
            if($jointime==$yesterday){
                $Joinnum->where(array("date"=>$today))->setInc("keep");
            }
            //插入登陆统计表
            $add_lc['user_id']=$user_id;
            $add_lc['date']=date("Y-m-d");
            $LoginCount->add($add_lc);
            //登陆加一口碑
            $User->where(array('id'=>$user_id))->setInc('praisenum');
            //整合要返回的数据
            $msg=$User->where(array("id"=>$user_id))->find();
            $msg['token']=$add_token['token'];
                //msg整合domain
            $domain_names2=$UserDomain->where(array('user_id'=>$user_id))->select();
            foreach ($domain_names2 as $k=>$v){
                $domain_names[]=$v['domain'];
            }
            $msg['domain']=$domain_names;
            $resp=responseMsg(0, 101, $msg);
            $this->ajaxReturn($resp); // 登录成功
        } else {
            $this->ajaxReturn(responseMsg(1)); // 登录失败
        }
    }

    /**
     * 添加头像
     */
    public function addFace()
    {
        if(!checkUserLogin()){
            //用户未登陆
            $this->ajaxReturn(2);
        }
        $User = D('user');
        $data['username'] = I('username');
        $image = imageUpload();
//         dump($image);die;
        $add['faceurl'] = $image['url'];
        $add['facepath'] = $image['path'];
        foreach ($add as $k => $v) { // 将二维数组装换成一维
            $add1[$k] = $v[0];
        }
        $flag = $User->where(array(
            'username' => $data['username']
        ))->save($add1);

//         dump($add1);die;
        if (flag) {
            $this->ajaxReturn(0); // 头像添加成功
        } else {
            $this->ajaxReturn(1); // 头像添加失败
        }
    }
    /**
     * 退出登录
     */
    public function outlogin(){
        $type=102;
        //检查调用是否正确
        if($type!=I("type")){
            $this->ajaxReturn(responseMsg(5,$type));
        }
        //检查登录状态
        if(!checkUserLogin(I("token"))){
            $this->ajaxReturn(responseMsg(2,$type));
        }
        $Token=D("token");
        $user_id=I("user_id");
        if($Token->where(array("user_id"=>$user_id))->save(array("state"=>1)))
        {
            $this->ajaxReturn(responseMsg(0, $type));//登出成功
        }else {
            $this->ajaxReturn(responseMsg(1, $type));//登出失败
        }
    }

    /**
     * 更改头像
     */
    public function changeFace()
    {   
        if(!checkUserLogin()){
            //用户未登陆
            $this->ajaxReturn(2);
        }
        $User = D('user');
        $data['username'] = I('username');
        
        $image = imageUpload();
        $add['faceurl'] = $image['url'];
        $add['facepath'] = $image['path'];
        foreach ($add as $k => $v) { // 将二维数组装换成一维
            $add1[$k] = $v[0];
        }
        $where['username'] = $data['username'];
        imageDel($User, $where, "facepath");//删除旧图片
        $flag = $User->where(array(
            'username' => $data['username']
        ))->save($add1);
        if (flag) {
            $this->ajaxReturn(0); // 头像更改成功
        } else {
            $this->ajaxReturn(1); // 头像更改失败
        }
    }
    /**
     * 查看用户详细信息
     */
    public function checkUserDetail(){
        $type=103;
        if($type!=I('type')){
            $this->ajaxReturn(responseMsg(5, $type));
        }
        $User=D('user');
        $Domain=D('user_domain');
        $user_id=I('user_id');
        $domain2=$Domain->where(array('user_id'=>$user_id))->select();
        foreach($domain2 as $k=>$v){
            $domain[]=$v['domain'];
        }
        $msg=$User->where(array('id'=>$user_id))->find();
        $msg['domain']=$domain;
        unset($msg['unreadnum']);
        unset($msg['isban']);
        unset($msg['password']);
        unset($msg['temp_praisenum']);
        $this->ajaxReturn(responseMsg(0, $type,$msg));
    }
}