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
        if ($type != I('type')) {
            $this->ajaxReturn(responseMsg(5, $type));
        }
        $Intro = D('introduce');
        $User = D('user');
        $Domain = D('introduce_domain');
        $key = I('key');
        $from = I('from');
        $length = 10;
        // name为domain名字
        $where['domain'] = array(
            'like',
            '%' . $key . '%'
        );
        // text为推荐文字内容
        $where['text'] = array(
            'like',
            '%' . $key . '%'
        );
        // 关键字是商户名
        $where['business_name'] = array(
            'like',
            '%' . $key . '%'
        );
        $where['_logic'] = 'or';
        $introduces = $Intro->where($where)
            ->
        // 右链接
        join("left join jianghu_introduce_domain on jianghu_introduce_domain.introduce_id=jianghu_introduce.id")
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
        if ($type != I('type')) {
            $this->ajaxReturn(responseMsg(5, $type));
        }
        $Busi = D('business');
        $key = I("key");
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
        if ($type != I('type')) {
            $this->ajaxReturn(responseMsg(5, $type));
        }
        $User = D('user');
        $key = I('key');
        $where['_logic'] = 'or';
        $where['u.username'] = array(
            'like',
            "%" . $key . "%"
        );
        $where['d.domain'] = array(
            'like',
            "%" . $key . "%"
        );
        $msg = $User->where($where)
            ->table("jianghu_user u")
            ->join("right join jianghu_user_domain d on u.id=d.user_id")
            ->field("u.id,u.username")
            ->group('u.id')
            ->select();
        $this->ajaxReturn(responseMsg(0, $type, $msg));
    }
}