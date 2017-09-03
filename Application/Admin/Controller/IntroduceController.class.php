<?php

namespace Admin\Controller;

use Think\Controller;

class IntroduceController extends Controller
{
    /**
     * 管理员登录检查
     */
    public function __construct()
    {
        parent::__construct();
        checkAdminLogin();
    }

    public function index()
    {
        $Introduce = D('introduce');
        $Img = D('introduce_images');
        $count = $Introduce->count();
        $Page = new \Think\Page($count, 10);
        $pageshow = page($Page);
        $where['u.username'] = array('like', '%d%');
        $list = $Introduce
            ->table('jianghu_introduce m')
            ->join('left join jianghu_user u on m.user_id=u.id')
            ->where($where)
            ->field('m.*,u.username')
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->select();
        $this->assign('introduce', $list);
        $this->assign('page', $pageshow);
        $this->display();
    }

    /**
     * 删除推送
     */
    public function del()
    {
        $Introduce = D('introduce');
        $Img = D('introduce_images');
        $id = I('id');
        $images = $Img->where(array('intoduce_id' => $id))->select();
        foreach ($images as $k => $v) {
            unlink($v['path']);
        }
        $flag = $Introduce->where(array('id' => $id))->delete();
        if ($flag) {
            $this->success('删除成功', U('introduce/index'));
        } else {
            $this->error('删除失败');
        }
    }

    /**
     * 推送详情
     */
    public function showdetail()
    {
        $Introduce = D('introduce');
        $Domain = D('introduce_domain');
        $Img = D('introduce_images');
        $User = D('user');
        $id = I('id');
//         dump($id);die;
        $introduce = $Introduce->where(array('id' => $id))->select();
        $domain_name = $Domain->where(array('introduce_id' => $id))->getField('domain');
        $uid = $Introduce->where(array('id' => $id))->getField('user_id');
        $intro_id = $Introduce->where(array('id' => $id))->getField('id');
        $images = $Img->where(array('introduce_id' => $intro_id))->select();
        foreach ($images as $k => $v) {
            $image[] = $v['imageurl'];
        }
        $username = $User->where(array('id' => $uid))->getField('username');
        $backurl = empty($_SERVER['HTTP_REFERER']) && strstr($_SERVER['HTTP_REFERER'],
            $_SERVER['HTTP_HOST']) ? '#' : $_SERVER['HTTP_REFERER'];
        foreach ($introduce as $k => $v) {
            $original_intro = $v['isforward'];
        }
        if ($original_intro) {
            $this->assign('original_intro', $original_intro);
        } else {
            $this->assign('msg', "这条是原创推荐");
        }
        $introduce[0]['domain'] = $domain_name;
//         dump($introduce);die;
        $this->assign('rows', $introduce);
        $this->assign('username', $username);
        $this->assign('image', $image);
        $this->assign('backurl', $backurl);
        $this->display();
    }

    /**
     * 显示推荐列表
     */
    public function showIntroduce()
    {
        $Introduce = D('introduce');
        $Img = D('introduce_images');
        $sql = I('where');
        $cate = I('cate');
        $searchkey = I('searchkey');
        //将+还原为空格
        $where = str_replace('+', " ", $sql);
        //得到页数
        $count = $Introduce
            ->table('jianghu_introduce m')
            ->join('left join jianghu_user u on m.user_id=u.id')
            ->where($where)
            ->field('m.*,u.username')
            ->count();
        //得到记录
        $Page = new \Think\Page($count, 10);
        $pageshow = page($Page);
        $list = $Introduce
            ->table('jianghu_introduce m')
            ->join('left join jianghu_user u on m.user_id=u.id')
            ->where($where)
            ->field('m.*,u.username')
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->order('time desc')
            ->select();
        //保持搜索条件
        $this->assign('cate', $cate);
        $this->assign('searchkey', $searchkey);
        $this->assign('introduce', $list);
        $this->assign('page', $pageshow);
        $this->display();
    }

    /**
     * 举报列表
     */
    public function reportedList()
    {
        $Report = D('introduce_report');
        $User = D('user');
        $Intro = D('introduce');
        $count = $Report->count();
        $Page = new \Think\Page($count, 10);
        $pageshow = page($Page);
        $list = $Report->limit($Page->firstRow . ',' . $Page->listRows)
            ->order("time desc")
            ->select();
        foreach ($list as $k => $v) {
            $list[$k]['username'] = $User->where(array(
                'id' => $v['user_id']
            ))->getField("username");
            $list[$k]['introduce_content'] = $Intro->where(array(
                'id' => $v['introduce_id']
            ))->getField("text");
            if ($list[$k]['state'] == 1) {
                $list[$k]['state'] = "已禁用推荐";
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
     * 禁用推荐
     */
    public function ban()
    {
        $Intro = D("introduce");
        $Report = D("introduce_report");
        $introduce_id = I("id");
        $flag = $Intro->where(array(
            "id" => $introduce_id
        ))->save(array(
            "isban" => 1
        ));
        // 关于该用户的所有举报全都解决
        $Report->where(array(
            'introduce_id' => $introduce_id
        ))->save(array(
            "state" => 1
        ));
        $this->success("禁用成功");
    }

    /**
     * 忽略举报
     */
    public function ignore()
    {
        $Report = D('introduce_report');
        $id = I('id');
        $Report->where(array(
            'id' => $id
        ))->save(["state"=>2]);
        $this->success("忽略成功");
    }
}
