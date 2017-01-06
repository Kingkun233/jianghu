<?php
namespace Admin\Controller;

use Think\Controller;

class UserController extends Controller
{

    /**
     * 管理员登录检查
     */
    public function __construct()
    {
        parent::__construct();
        checkAdminLogin();
    }

    /**
     * 用户列表
     */
    public function index()
    {
        $user = D('user');
        $searchkey = I('searchkey');
        // 得到页数
        $where['username'] = array(
            'like',
            "%" . $searchkey . "%"
        );
        $count = $user->where($where)->count();
        $Page = new \Think\Page($count, 10);
        $pageshow = page($Page);
        $list = $user->limit($Page->firstRow . ',' . $Page->listRows)
            ->where($where)
            ->select();
        foreach ($list as $k => $v) {
            if ($list[$k]['isban']) {
                $list[$k]['isban'] = "被禁用";
            } else {
                $list[$k]['isban'] = "正常";
            }
        }
        $this->assign('user', $list);
        $this->assign('page', $pageshow);
        $this->display();
    }

    /**
     * 删除用户
     */
    public function del()
    {
        $User = D('user');
        $id = I('id');
        $facepath = $User->where(array(
            'id' => $id
        ))->getField('facepath');
        // 删除照片
        unlink($facepath);
        $flag = $User->where(array(
            'id' => $id
        ))->delete();
        if ($flag) {
            $this->success('删除成功', U('user/index'));
        } else {
            $this->error('删除失败');
        }
    }

    /**
     * 用户添加界面
     */
    public function add()
    {
        $this->display();
    }

    /**
     * 用户添加逻辑
     */
    public function doadd()
    {
        $User = D('user');
        $data['username'] = I('username');
        if (checkUserExist($data['username'])) {
            $this->error('用户已存在');
        }
        $data['password'] = I('password');
        $data['email'] = I('email');
        $image = imageUpload();
        $face = $image['url'];
        $path = $image['path'];
        $data['faceurl'] = $face[0];
        $data['facepath'] = $path[0];
        $flag = $User->add($data);
        if ($flag) {
            $this->success('添加成功', U("user/index"));
        } else {
            $this->error('添加失败');
        }
    }

    /**
     * 查看每天数据
     */
    public function showDailyNum()
    {
        $Joinnum = D("daily_num");
        $count = $Joinnum->count();
        $Page = new \Think\Page($count, 10);
        $pageshow = page($Page);
        $num = $Joinnum->order('date desc')
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->select();
        $last = 0;
        foreach ($num as $k => $v) {
            $show = array();
            $show['date'] = $v['date'];
            $show['commentnum'] = $v['commentnum'];
            $show['praisenum'] = $v['praisenum'];
            $show['joinnum'] = $v['joinnum'];
            $show['lognum'] = $v['lognum'];
            $show['stay'] = round($v['keep'] / $last * 100, 2);
            $show1[] = $show;
            $last = $Joinnum->where(array(
                "date" => $v['date']
            ))->getField("joinnum");
        }
        // dump($show);
        $this->assign("num", $show1);
        $this->assign('page', $pageshow);
        $this->display();
    }

    /**
     * 禁用用户
     */
    public function ban()
    {
        $User = D("user");
        $Report = D("user_report");
        $user_id = I("id");
        $flag = $User->where(array(
            "id" => $user_id
        ))->save(array(
            "isban" => 1
        ));
        if ($flag) {
            // 关于该用户的所有举报全都解决
            $Report->where(array(
                'reported_id' => $user_id
            ))->save(array(
                "state" => 1
            ));
            $this->success("禁用成功");
        } else {
            $this->error("禁用失败");
        }
    }

    /**
     * 解除禁用
     */
    public function unban()
    {
        $User = D("user");
        $user_id = I("id");
        $flag = $User->where(array(
            "id" => $user_id
        ))->save(array(
            "isban" => '0'
        ));
        if ($flag) {
            $this->success("解除成功");
        } else {
            $this->error("解除失败");
        }
    }

    /**
     * 用户详情
     */
    public function userdetails()
    {
        $User = D("user");
        $username = I("username");
        $rows = $User->where(array(
            "username" => $username
        ))->select();
        foreach ($rows as $k=>$v){
            if($v['isban']){
                $rows[$k]['isban']="该用户已被禁用";
            }else{
                $rows[$k]['isban']="正常";
            }
        }
        $this->assign("rows", $rows);
        $this->display();
    }

    /**
     * 用户反馈列表
     */
    public function feedbackList()
    {
        $User = D('user');
        $FeedBack = D('feedback');
        $feedbacks = $FeedBack->order('time desc')->select();
        foreach ($feedbacks as $k => $v) {
            $feedbacks[$k]['username'] = $User->where(array(
                'id' => $v['user_id']
            ))->getField("username");
            if ($v['state']) {
                $feedbacks[$k]['state'] = "已处理";
            } else {
                $feedbacks[$k]['state'] = "未处理";
            }
        }
        $this->assign("feedbacks", $feedbacks);
        $this->display();
    }

    /**
     * 处理用户反馈
     */
    public function handle()
    {
        $FeedBack = D('feedback');
        $id = I('id');
        $FeedBack->where(array(
            'id' => $id
        ))->setInc("state");
        $this->success("处理成功", U('user/feedbackList'));
    }

    /**
     * 举报列表
     */
    public function reportedList()
    {
        $Report = D('user_report');
        $User = D('user');
        $count = $Report->count();
        $Page = new \Think\Page($count, 10);
        $pageshow = page($Page);
        $list = $Report->limit($Page->firstRow . ',' . $Page->listRows)
            ->order("time desc")
            ->select();
        foreach ($list as $k => $v) {
            $list[$k]['reportedname'] = $User->where(array(
                'id' => $v['reported_id']
            ))->getField("username");
            $list[$k]['username'] = $User->where(array(
                'id' => $v['user_id']
            ))->getField("username");
            if ($list[$k]['state'] == 1) {
                $list[$k]['state'] = "已禁用用户";
            } else 
                if ($list[$k]['state'] == 2) {
                    $list[$k]['state'] = "已忽略";
                } else {
                    $list[$k]['state'] = "未处理";
                }
        }
        $this->assign('report', $list);
        $this->assign('page', $pageshow);
        $this->display();
    }

    /**
     * 忽略举报
     */
    public function ignore()
    {
        $Report = D('user_report');
        $id = I('id');
        $Report->where(array(
            'id' => $id
        ))->setInc("state", 2);
        $this->success("忽略成功");
    }
}
