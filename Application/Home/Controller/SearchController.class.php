<?php
namespace Home\Controller;

use Think\Controller;

class SearchController extends Controller
{

    /**
     * 根据领域或名字搜索推荐
     */
    public function search()
    {
        $Intro = D('introduce');
        $User = D('user');
        $Domain = D('introduce_domain');
        $key = I('key');
        //name为domain名字
        $where['name'] = array(
            'like',
            '%' . $key . '%'
        );
        //text为推荐文字内容
        $where['text'] = array(
            'like',
            '%' . $key . '%'
        );
        $where['_logic'] = 'or';
        $introduces = $Intro->where($where)
        //右链接
            ->join("right join jianghu_introduce_domain on jianghu_introduce_domain.introduce_id=jianghu_introduce.id")
            ->select();
        dump($introduces);
        // $this->ajaxReturn($introduces);
    }
}