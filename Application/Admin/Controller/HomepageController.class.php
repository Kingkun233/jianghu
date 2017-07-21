<?php
namespace Admin\Controller;

use Think\Controller;

class HomepageController extends Controller
{

    public function index()
    {
        $Homepage = D('homepage');
        $count = $Homepage->count();
        $Page = new \Think\Page($count, 10);
        $pageshow = page($Page);
        $list = $Homepage->limit(1)
            ->order('time desc')
            ->select();
        $this->assign('homepage', $list);
        $this->assign('page', $pageshow);
        $this->display();
    }

    public function add()
    {
        $this->display();
    }

    public function doadd()
    {
        $Homepage = D('homepage');
        $add['url'] = imageUpload()['url'][0];
        $add['time'] = date('Y-m-d H:i:s');
        $flag = $Homepage->add($add);
        if ($flag) {
            $this->success("版本添加成功", U('homepage/index'));
        } else {
            $this->error("版本添加失败");
        }
    }
}