<?php
namespace Home\Controller;

use Think\Controller;

class BusinessController extends Controller
{

    /**
     * 添加商户
     */
    public function add()
    {
        $type = 400;
        $post = loginPermitApiPreTreat($type);
        $Busis = D('business');
        $User = D('user');
        $Domain = D('domain');
        $add['name'] = $post['name'];
        $add['addr'] = $post['addr'];
        $add['latitude'] = $post['latitude'];
        $add['discription'] = $post['discription'];
        $add['state'] = 1;
        $add['joindate'] = date('Y-m-d');
        $add['phone'] = $post['phone'];
        $add['user_id'] = $post['user_id'];
        $domain_id = $post['domain_id'];
        $add['domain'] = $Domain->where(array(
            'id' => $domain_id
        ))->getField("name");
        $add['website'] = $post['website'];
        $Busis->add($add);
        $this->ajaxReturn(responseMsg(0, $type));
    }

    /**
     * 返回商户详细信息,顺便更新商户星级
     */
    public function showDetail()
    {
        $type = 401;
        $post = touristApiPreTreat($type);
        $Busi = D('business');
        $Intro = D('introduce');
        $business_id = $post['business_id'];
        $msg = $Busi->where(array(
            'id' => $business_id
        ))->find();
        // 整合最高两个度数的个数
        $transdegree = null;
        $degrees2 = $Intro->where(array(
            'business_id' => $business_id
        ))
            ->field("degree,alldegree")
            ->select();
        $star = 0;
        foreach ($degrees2 as $k => $v) {
            $alldegree += $v['alldegree'];
            $degrees[] = $v['degree'];
        }
        // 更新star
        if ($alldegree <= 300) {
            $star = 3;
        } else 
            if ($alldegree <= 500) {
                $star = 4;
            } else {
                $star = 5;
            }
        $originstar = $Busi->where(array(
            'id' => $business_id
        ))->getField('star');
        if ($originstar < $star) {
            $Busi->where(array(
                'id' => $business_id
            ))->save(array(
                'star' => $star
            ));
        }
        $msg['degree'] = R("sort/getTwoTopDegree", array(
            $degrees2
        ));
        $msg['star'] = $star;
        $this->ajaxReturn(responseMsg(0, $type, $msg));
    }

    /**
     * 添加商户评论
     */
    public function addBusinessComment()
    {
        $type = 402;
        $post = loginPermitApiPreTreat($type);
        $BusiComment = D('business_comment');
        $user_id = $post['user_id'];
        $business_id = $post['business_id'];
        $comment = $post['comment'];
        $data = $BusiComment->create($post);
        $BusiComment->add($data);
        $this->ajaxReturn(responseMsg(0, $type));
    }

    /**
     * 返回商户评论
     */
    public function returnBusinessComment()
    {
        $type = 403;
        $post = touristApiPreTreat($type);
        $BusiComment = D('business_comment');
        $User = D('user');
        $business_id = $post['business_id'];
        $user_id=$post['user_id']?$post['user_id']:0;
        $where['business_id']=$business_id;
        //如果传了user_id的话，搜索条件添加“限定好友”
        if($user_id){
            //获取好友list
            $Friend=D('friend');
            $friends2=$Friend->where(array('user_id'=>$user_id))->field("friend_id")->select();
            foreach ($friends2 as $k=>$v){
                $friends[]=$v['friend_id'];
            }
            $where['user_id']=array("IN",$friends);
        }
        //返回评论
        $comments = $BusiComment->where($where
        )->select();
        foreach ($comments as $k => $v) {
            $comments[$k]['username'] = $User->where(array(
                'id' => $v['user_id']
            ))->getField('username');
            $comments[$k]['faceurl'] = $User->where(array(
                'id' => $v['user_id']
            ))->getField('faceurl');
        }
        $this->ajaxReturn(responseMsg(0, $type, $comments));
    }

    /**
     * 返回附近商户
     */
    public function getNearBusiness()
    {
        $type = 404;
        $post = touristApiPreTreat($type);
        $Business = D("business");
        $longtitude = $post['longtitude'];
        $latitude = $post['latitude'];
        //给定的距离范围
        $post_distance=$post['distance'];
        $where['name'] = array(
            "like",
            "%" . $post['key'] . "%"
        );
        $business_locations = $Business->where($where)->select();
        $returnBusiness = array();
        foreach ($business_locations as $k => $v) {
            if ($business_locations[$k]['latitude'] && $business_locations[$k]['longtitude']) {
                $distance = $this->getDistance($business_locations[$k]['latitude'], $business_locations[$k]['longtitude'], $latitude, $longtitude);
                if ($distance < $post_distance) {
                    $business_locations[$k]['distance'] = $distance;
                    unset($business_locations[$k]['state']);
                    unset($business_locations[$k]['user_id']);
                    $returnBusiness[] = $business_locations[$k];
                }
            }
        }
        // 按照距离排序
        $returnBusiness = sortTwoDimensionalArrayByKey("distance", $returnBusiness, SORT_ASC);
        $this->ajaxReturn(responseMsg(0, $type, $returnBusiness));
    }

    /**
     * 计算两点之间的距离
     *
     * @param unknown $lng1            
     * @param unknown $lat1            
     * @param unknown $lng2            
     * @param unknown $lat2            
     */
    function getDistance($latitude1, $longitude1, $latitude2, $longitude2)
    {
        $theta = $longitude1 - $longitude2;
        $miles = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $feet = $miles * 5280;
        $yards = $feet / 3;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;
        return round($kilometers, 1);
    }
}