<?php
namespace Home\Controller;

use Think\Controller;

class SearchController extends Controller
{

    /**
     * 根据领域或名字或商户名搜索推荐
     */
    public function searchIntro()
    {
        $type = 801;
        $post=touristApiPreTreat($type);
        $Intro = D('introduce');
        $User = D('user');
        $Domain = D('introduce_domain');
        $key = $post['key'];
        $from =$post['from'];
        $length = 10;
        // name为domain名字
        $where['d.domain'] = array(
            'like',
            '%' . $key . '%'
        );
        // text为推荐文字内容
        $where['i.text'] = array(
            'like',
            '%' . $key . '%'
        );
        // 关键字是商户名
        $where['i.business_name'] = array(
            'like',
            '%' . $key . '%'
        );
        $where['_logic'] = 'or';
        $introduces = $Intro->where($where)
            ->
        // 右链接
        table("jianghu_introduce i")->
        join("left join jianghu_introduce_domain d on d.introduce_id=i.id")
            ->order("time desc")
            ->limit($from, $length)
            ->select();
        $resp = responseMsg(0, $type, $introduces);
        $resp['from'] = $from;
        $resp['length'] = $length;
        $this->ajaxReturn($resp);
    }

    /**
     * 通过商户名搜索商户
     */
    public function searchBusiness()
    {
        $type = 800;
        $post=touristApiPreTreat($type);
        $Busi = D('business');
        $key = $post['key'];
        $where['name'] = array(
            'like',
            "%" . $key . "%"
        );
        $business = $Busi->where($where)
            ->field("id,name")
            ->select();
        $this->ajaxReturn(responseMsg(0, $type, $business));
    }

    /**
     * 通过领域，或用户名搜索用户
     */
    public function searchUser()
    {
        $type = 802;
        $post=touristApiPreTreat($type);
        $User = D('user');
        $key =$post['key'];
        $where['_logic'] = 'or';
        $where['u.username'] = array(
            'like',
            "%" . $key . "%"
        );
        $where['d.domain'] = array(
            'like',
            "%" . $key . "%"
        );
        $where['u.phonenum'] = array(
            'like',
            "%" . $key . "%"
        );
        $msg = $User->where($where)
            ->table("jianghu_user u")
            ->join("right join jianghu_user_domain d on u.id=d.user_id")
            ->field("u.id,u.username,u.faceurl,u.allforward,u.allpraise,d.domain")
            ->group('u.id')
            ->select();
        $this->ajaxReturn(responseMsg(0, $type, $msg));
    }
}