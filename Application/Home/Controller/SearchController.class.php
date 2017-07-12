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
        // 搜索类型（转采最多1，好友推荐2）
        $search_type = $post['search_type'];
        // 搜索的最大距离
        $max_distance = $post['max_distance'];
        $user_latitude=$post['user_latitude'];
        $user_longtitude=$post['user_longtitude'];
        $length = 500;
        // 判断有没有传user_id
        $user_id = $post['user_id'];
        if (! $user_id) {
            $user_id = 0;
        }
        if($key){
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
            $where['_logic'] = 'or';
            $map['_complex'] = $where;
        }
        // 要求原创
        $map['isforward'] = array(
            "exp",
            "is NULL"
        );
        if ($search_type == 1) {
            // 要求三度以上
            $map['alldegree'] = array(
                "gt",
                0
            );
            $order="alldegree desc";
        } else {
            if ($search_type == 2) {
                if($user_id){
                    // 要求是朋友
                    // 获取好友list
                    $Friend = D('friend');
                    $friends2 = $Friend->where(array(
                        'user_id' => $user_id
                    ))
                    ->field("friend_id")
                    ->select();
                    foreach ($friends2 as $k => $v) {
                        $friends[] = $v['friend_id'];
                    }
                    $map['user_id'] = array(
                        "IN",
                        $friends
                    );
                    $map['user_id'] = array(
                        "neq",
                        $user_id
                    );
                    $order="time desc";
                }else{
                    $this->ajaxReturn(responseMsg(2, $type));
                }
            }
        }
        // 搜索相应原创推荐的id
        $introduce_ids = $Intro->where($map)
            ->order($order)
            ->limit($from, $length)
            ->field('id')
            ->select();
        $introduces = array();
        foreach ($introduce_ids as $k => $v) {
            $intro_content = null;
            $intro_content = $Introduce_controller->getIntroContent($v['id'], $user_id);
            $introduces[] = $intro_content;
        }
        //距离筛选
        $returnIntros=array();
        if($max_distance!=-1){
            foreach ($introduces as $k => $v) {
                if ($introduces[$k]['business_latitude'] && $introduces[$k]['business_longtitude']) {
                    $distance = getDistance($introduces[$k]['business_latitude'], $introduces[$k]['business_longtitude'], $user_latitude, $user_longtitude);
                    if ($distance <= $max_distance) {
                        $introduces[$k]['distance'] = $distance;
                        $returnIntros[] = $introduces[$k];
                    }
                }
            }
        }else{
            $returnIntros=$introduces;
        }
        $resp = responseMsg(0, $type, $returnIntros);
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
//         dump($post);die;
        $User = D('user');
        $key = $post['key'];
        $where['u.username'] = array(
            'like',
            "%" . $key . "%"
        );
        $where['u.phonenum'] = array(
            'like',
            "%" . $key . "%"
        );
        $where['_logic'] = 'OR';
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