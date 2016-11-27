<?php
namespace Admin\Controller;

use Think\Controller;

class BusinessController extends Controller
{
    /**
     * 管理员登录检查
     */
    public function __construct(){
        parent::__construct();
        checkAdminLogin();
    }

    /**
     * 商户列表
     */
    public function businessList()
    {
        $Busi = D('business');
        $sql = I('where');
        $cate = I('cate');
        $searchkey = I('searchkey');
        // 将+还原为空格
//         dump($sql);die;
        if($sql){
            $where = str_replace('+', " ", $sql)."AND state=0";
        }else{
            $where="state=0";
        }
        // 得到页数
        $count = $Busi->table('jianghu_business b')
            ->join('left join jianghu_user u on b.user_id=u.id')
            ->where($where)
            ->field('b.*,u.username')
            ->count();
        // 得到记录
        $Page = new \Think\Page($count, 10);
        $pageshow = page($Page);
        $list = $Busi->table('jianghu_business b')
            ->join('left join jianghu_user u on b.user_id=u.id')
            ->where($where)
            ->field('b.*,u.username')
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->order('joindate desc')
            ->select();
        // 状态显示
        foreach ($list as $k => $v) {
            
            if ($v['state']) {
                // 注意！！想在foreach中改变二维数组的值要用“绝对路径”$list[$k][...]，不能用$v['...']
                $list[$k]['state'] = "未审核";
            } else {
                $list[$k]['state'] = "已审核";
            }
        }
        // 保持搜索条件
        $this->assign('cate', $cate);
        $this->assign('searchkey', $searchkey);
        $this->assign('business', $list);
        $this->assign('page', $pageshow);
        $this->display();
    }

    /**
     * 商户详情
     */
    public function showdetail()
    {
        $Busi = D('business');
        $User = D(user);
        $id = I('id');
        $rows = $Busi->where(array(
            'id' => $id
        ))->select();
        $user_id = $Busi->where(array(
            'id' => $id
        ))->getField('user_id');
        $username = $User->where(array(
            'id' => $user_id
        ))->getField('username');
        $backurl = empty($_SERVER['HTTP_REFERER']) && strstr($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']) ? '#' : $_SERVER['HTTP_REFERER'];
        if($rows[0]['state']){
            $rows[0]['state']="未审核";
        }else{
            $rows[0]['state']="已审核";
        }
        $this->assign('username', $username);
        $this->assign('rows', $rows);
        $this->assign('backurl', $backurl);
        $this->display('');
    }

    /**
     * 编辑商户页面
     */
    public function edit()
    {
        $Busi = D('business');
        $User = D(user);
        $id = I('id');
        $rows = $Busi->where(array(
            'id' => $id
        ))->select();
        $user_id = $Busi->where(array(
            'id' => $id
        ))->getField('user_id');
        $username = $User->where(array(
            'id' => $user_id
        ))->getField('username');
        $backurl = empty($_SERVER['HTTP_REFERER']) && strstr($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']) ? '#' : $_SERVER['HTTP_REFERER'];
        $this->assign('username', $username);
        $this->assign('rows', $rows);
        $this->assign('backurl', $backurl);
        $this->display();
    }

    /**
     * 编辑商户逻辑
     */
    public function doedit()
    {
        $Busi = D('business');
        $logo = imageUpload();
        $Busi->create();
        $Busi->save();
        $id = I('id');
        // dump($id);die;
        if ($logo) {
            $Busi->where(array(
                'id' => $id
            ))->save(array(
                'logopath' => $logo['path'][0]
            ));
            $Busi->where(array(
                'id' => $id
            ))->save(array(
                'logourl' => $logo['url'][0]
            ));
        }
        $this->success('修改成功', U('business/businessList'));
    }

    /**
     * 添加商户
     */
    public function add()
    {
        $this->display();
    }

    /**
     * 添加商户逻辑
     */
    public function doadd()
    {
        $Busi = D('business');
        $logo = imageUpload();
        $data=$Busi->create();
        $data['joindate']=date('Y-m-d');
        $id = $Busi->add($data);
        $Busi->where(array(
            'id' => $id
        ))->save(array(
            'logopath' => $logo['path'][0]
        ));
        $Busi->where(array(
            'id' => $id
        ))->save(array(
            'logourl' => $logo['url'][0]
        ));
        $this->success('添加成功', U('business/businessList'));
    }
    /**
     * 未审核商户列表
     */
    public function uncheckList(){
       $Busi=D('business');
       $business=$Busi->where(array('state'=>1))->select();
       // 状态显示
       foreach ($business  as $k => $v) {
       
           if ($v['state']) {
               // 注意！！想在foreach中改变二维数组的值要用“绝对路径”$list[$k][...]，不能用$v['...']
               $business[$k]['state'] = "未审核";
           } else {
               $business[$k]['state'] = "已审核";
           }
       }
       $this->assign('business',$business);
       $this->display();
    }
    /**
     * 审核通过
     */
    public function check(){
        $Busi=D('business');
        $id=I('id');
        $Busi->where(array('id'=>$id))->save(array('state'=>0));
        $this->success('审核成功',U('business/uncheckList'));
    }
}