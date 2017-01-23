<?php
namespace Home\Controller;

use Think\Controller;

class SortController extends Controller
{

    /**
     * 返回用户的排名，用户最高的两个度数，赞数和好友排名
     */
    public function mySort()
    {
        $type = 701;
        $post = loginPermitApiPreTreat($type);
        $User = D('user');
        $Forward = D('forward');
        $SortPraise = D('sort_praise');
        $Intro = D('introduce');
        $user_id = $post['user_id'];
        // 获得用户好友排名
        $Friend = D('friend');
        $Weekendreputate = D('weekend_reputation');
        // 得到朋友的id数组
        $fids = $Friend->where(array(
            'user_id' => $user_id
        ))
            ->field('friend_id')
            ->select();
        foreach ($fids as $k => $v) {
            $fid[] = $v['friend_id'];
        }
        if ($fid) {
            // 先将sort表全部排好序
            $where['id'] = array(
                'IN',
                $fid
            );
        } else {
            $where['id'] = - 1;
        }
        
        $sortall = $User->where($where)
            ->order('praisenum desc')
            ->select();
        $i = 1;
        $showall = null;
        $where_sort_praise['user_id'] = $user_id;
        foreach ($sortall as $k => $v) {
            $show = null;
            // 获得上周末的口碑统计
            $weekpraise = $Weekendreputate->where(array(
                'user_id' => $v['id']
            ))->getField('reputationnum');
            // 目前口碑减去上周末口碑得到这周新增的度数
            $weeknewpraise = $sortall[$k]['praisenum'] - $weekpraise;
            $show['user_id'] = $v['id'];
            $show['username'] = $v['username'];
            $show['faceurl'] = $v['faceurl'];
            $show['weeknewpraise'] = $weeknewpraise;
            $show['thumbnum']=$SortPraise->where(array('sort_user_id'=>$v['id']))->count();
            $where_sort_praise['sort_user_id'] = $v['id'];
            if ($SortPraise->where($where_sort_praise)->find()) {
                $show['ispraised'] = 1;
            } else {
                $show['ispraised'] = 0;
            }
            $showall[] = $show;
        }
        // 让记录按新增口碑数排序
        $weeknewpraisearray = array();
        foreach ($showall as $k => $v) {
            $weeknewpraisearray[] = $v['weeknewpraise'];
        }
        array_multisort($weeknewpraisearray, SORT_NUMERIC, SORT_DESC, $showall);
        // 加上rank
        foreach ($showall as $k => $v) {
            $showall[$k]['rank'] = $i;
            $i ++;
        }
        $friendsort = $showall;
        
        // 获得用户排名
        $userrank = 0;
        foreach ($showall as $k => $v) {
            if ($user_id == $friendsort[$k]['user_id']) {
                $userrank = $showall[$k]['rank'];
                $userWeekPraisenum=$showall[$k]['weeknewpraise'];
            }
        }
        
        // 获得最高两个度数的个数
        $where_intro['isforward'] = array("exp","is NULL");
        $where_intro['user_id'] = $user_id;
//         dump($user_id);die;
        $intro_ids2 = $Intro->where($where_intro)->select();
        foreach ($intro_ids2 as $k => $v) {
            $intro_ids[] = $v['id'];
        }
//         dump($intro_ids2);die;
        if ($intro_ids) {
            $where_forward['introduce_id'] = array(
                "IN",
                $intro_ids
            );
        } else {
            $where_forward['introduce_id'] = - 1;
        }
        
        $degrees = $Forward->field('degree')
            ->where($where_forward)
            ->select();
        
        // 整合数据
        $msg['degree'] = $this->getTwoTopDegree($degrees);
        $msg['allpraise'] = $User->where(array(
            'id' => $user_id
        ))->getField('allpraise');
        $msg['userrank'] = $userrank;
        $msg['userweeknewpraise']=$userWeekPraisenum;
        $msg['username'] = $User->where(array(
            'id' => $user_id
        ))->getField('username');
        $msg['userface'] = $User->where(array(
            'id' => $user_id
        ))->getField('faceurl');
        $msg['friendsort'] = $friendsort;
        $this->ajaxReturn(responseMsg(0, $type, $msg));
    }

    /**
     * 根据口碑给用户排序
     */
    public function sortList()
    {
        $type = 700;
        $post = loginPermitApiPreTreat($type);
        $User = D('user');
        $Friend = D('friend');
        $Weekendreputate = D('weekend_reputation');
        $user_id = $post['user_id'];
        // 得到朋友的id 二维数组
        $fids = $Friend->where(array(
            'user_id' => $user_id
        ))
            ->field('friend_id')
            ->select();
        // 转为一维数组
        foreach ($fids as $k => $v) {
            $fid[] = $v['friend_id'];
        }
        // 先将sort表全部排好序
        $where['id'] = array(
            'IN',
            $fid
        );
        $sortall = $User->where($where)
            ->order('praisenum desc')
            ->select();
        $i = 1;
        $showall = null;
        foreach ($sortall as $k => $v) {
            $show = null;
            // 获得上周末的口碑统计
            $weekpraise = $Weekendreputate->where(array(
                'user_id' => $v['id']
            ))->getField('reputationnum');
            // 目前口碑减去上周末口碑得到这周新增的度数
            $weeknewpraise = $sortall[$k]['praisenum'] - $weekpraise;
            $show['user_id'] = $v['id'];
            $show['username'] = $v['username'];
            $show['faceurl'] = $v['faceurl'];
            $show['weeknewpraise'] = $weeknewpraise;
            $showall[] = $show;
        }
        // 让记录按新增口碑数排序
        // dump($showall);die;
        $weeknewpraisearray = array();
        foreach ($showall as $k => $v) {
            $weeknewpraisearray[] = $v['weeknewpraise'];
        }
        // rsort($weeknewpraisearray);
        // dump($weeknewpraisearray);die;
        array_multisort($weeknewpraisearray, SORT_NUMERIC, SORT_DESC, $showall);
        // 加上rank
        foreach ($showall as $k => $v) {
            $showall[$k]['rank'] = $i;
            $i ++;
        }
        $this->ajaxReturn(responseMsg(0, $type, $showall));
    }

    /**
     * 每周六晚上3点更新周末用户口碑表
     */
    public function weekendPraise()
    {
        $User = D('user');
        $Weekrepu = D('weekend_reputation');
        // 之前的state置1
        $Weekrepu->where(array(
            'state' => 0
        ))->save(array(
            'state' => 1
        ));
        // 删除state为1的记录
        $Weekrepu->where(array(
            'state' => 1
        ))->delete();
        // 更新数据表
        $rows = $User->field('id,praisenum')->select();
        foreach ($rows as $k => $v) {
            $add = null;
            $add['user_id'] = $v['id'];
            $add['reputationnum'] = $v['praisenum'];
            $add['date'] = date('Y-m-d');
            $Weekrepu->add($add);
        }
    }

    /**
     * 点赞该评论
     */
    public function addPraise()
    {
        $type = 702;
        $post = loginPermitApiPreTreat($type);
        $Praise = D('sort_praise');
        $add['user_id'] = $post['user_id'];
        $add['sort_user_id'] = $post['sort_user_id'];
        $add['date'] = date("Y-m-d");
        $Praise->add($add);
        $this->ajaxReturn(responseMsg(0, $type));
    }
    /**
     * 得到两个最高的度数
     * @param unknown $degrees 度数二维数组
     * @return number 最高两个度数
     */
    public function getTwoTopDegree($degrees){
        //dump($degrees);die;
        foreach ($degrees as $k => $v) {
            $degrees1[] = $v['degree'];
        }
        $countdegree = array_count_values($degrees1);
        // 把这个数组消减到只有最高两个度数
        foreach ($countdegree as $k => $v) {
            if (count($countdegree) <= 2) {
                break;
            } else {
                unset($countdegree[$k]);
            }
        }
        if (! $countdegree) {
            $transdegree['highest_degree'] = 0;
            $transdegree['highest_degree_num'] = 0;
            $transdegree['second_degree'] = 0;
            $transdegree['second_degree_num'] = 0;
        } else {
            if (count($countdegree) == 2) {
                foreach ($countdegree as $k => $v) {
                    $transdegree['second_degree'] = $k;
                    $transdegree['second_degree_num'] = $countdegree[$k];
                    unset($countdegree[$k]);
                    break;
                }
                foreach ($countdegree as $k => $v) {
                    $transdegree['highest_degree'] = $k;
                    $transdegree['highest_degree_num'] = $countdegree[$k];
                    unset($countdegree[$k]);
                    break;
                }
            } else {
                foreach ($countdegree as $k => $v) {
                    $transdegree['highest_degree'] = $k;
                    $transdegree['highest_degree_num'] = $countdegree[$k];
                    unset($countdegree[$k]);
                    break;
                }
                $transdegree['second_degree'] = 0;
                $transdegree['second_degree_num'] = 0;
            }
        }
        return $transdegree;
    }
}