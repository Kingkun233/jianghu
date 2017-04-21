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
        $post = touristApiPreTreat($type);
        $Intro = D('introduce');
        $User = D('user');
        $Domain = D('introduce_domain');
        $Introduce_controller = A("Home/Introduce");
        $key = $post['key'];
        $from = $post['from'];
        $length = 10;
        // 判断有没有传user_id
        $user_id = $post['user_id'];
        if (! $user_id) {
            $user_id = 0;
        }
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
//         // 关键字是商户名
//         $where['business_name'] = array(
//             'like',
//             '%' . $key . '%'
//         );
        $where['_logic'] = 'or';
        $map['_complex'] = $where;
        // 要求原创
        $map['isforward'] = array(
            "exp",
            "is NULL"
        );
        //要求三度以上
        $map['alldegree'] = array(
            "gt",
            0
        );
        // 搜索相应原创推荐的id
        $introduce_ids = $Intro->where($map)
            ->order("time desc")
            ->limit($from, $length)
            ->field('id')
            ->select();
        $introduces = array();
        foreach ($introduce_ids as $k => $v) {
            $introduces[] = $Introduce_controller->getIntroContent($v['id'], $user_id);
        }
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
        $post = touristApiPreTreat($type);
        $Busi = D('business');
        $key = $post['key'];
        $where['name'] = array(
            'like',
            "%" . $key . "%"
        );
        $business = $Busi->where($where)
            ->field("id,name,star,logourl")
            ->order("star desc")
            ->select();
        $this->ajaxReturn(responseMsg(0, $type, $business));
    }

    /**
     * 通过领域，或用户名搜索用户
     */
    public function searchUser()
    {
        $type = 802;
        $post = touristApiPreTreat($type);
        $User = D('user');
        $key = $post['key'];
        $where['u.username'] = array(
            'like',
            "%" . $key . "%"
        );
        // $where['d.domain'] = array(
        // 'like',
        // "%" . $key . "%"
        // );
//         $where['u.phonenum'] = array(
//             'like',
//             "%" . $key . "%"
//         );
//         $where['_logic'] = 'or';
        // 用外连接的时候注意null值的处理
        $msg = $User->where($where)
            ->table("jianghu_user u")
            ->join("left join jianghu_user_domain d on u.id=d.user_id")
            ->field("u.id,u.username,u.faceurl,u.allforward,u.allpraise,d.domain")
            ->group('u.id')
            ->select();
        // 如果domain为null的话改为“”
        foreach ($msg as $k => $v) {
            if (! $v["domain"]) {
                $msg[$k]["domain"] = "";
            }
        }
        $this->ajaxReturn(responseMsg(0, $type, $msg));
    }

    /**
     * 返回搜索标签
     */
    public function getTags()
    {
        $type = 803;
        $post = touristApiPreTreat($type);
        $Tag = D('tag');
        $tags = $Tag->select();
        $this->ajaxReturn(responseMsg(0, $type, $tags));
    }
}