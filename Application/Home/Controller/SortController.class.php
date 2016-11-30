<?php
namespace Home\Controller;

use Think\Controller;

class SortController extends Controller
{

    /**
     * 根据口碑给用户排序
     */
    public function sortList()
    {
        $type = 700;
        loginPermitApiPreTreat($type);
        $User = D('user');
        $Friend = D('friend');
        $Weekendreputate=D('weekend_reputation');
        $user_id = I('user_id');
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
        $showall=null;
        foreach ($sortall as $k => $v) {
            $show = null;
            //获得上周末的口碑统计
            $weekpraise=$Weekendreputate->where(array('user_id'=>$v['id']))->getField('reputationnum');
            //目前口碑减去上周末口碑得到这周新增的度数
            $weeknewpraise=$sortall[$k]['praisenum']-$weekpraise;
            $show['user_id'] = $v['id'];
            $show['username'] = $v['username'];
            $show['faceurl'] = $v['faceurl'];
            $show['weeknewpraise'] = $weeknewpraise;
            $showall[] = $show;
        }
        //让记录按新增口碑数排序
//         dump($showall);die;
        $weeknewpraisearray=array();
        foreach ($showall as $k => $v){
            $weeknewpraisearray[]=$v['weeknewpraise'];
        }
//         rsort($weeknewpraisearray);
//         dump($weeknewpraisearray);die;
        array_multisort($weeknewpraisearray,SORT_NUMERIC, SORT_DESC,$showall);
        //加上rank
        foreach($showall as $k => $v){
            $showall[$k]['rank']=$i;
            $i++;
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
}