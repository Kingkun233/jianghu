<?php
namespace Home\Controller;

use Think\Controller;

class UpdateController extends Controller
{

    /**
     * 获取最新apk
     */
    public function getNewestAkp()
    {
        $type = 1001;
        $post = touristApiPreTreat($type);
        $Update = D('update');
        $apk_infos = $Update->order('time desc')->select();
        if ($post['version'] != $apk_infos[0]['version']) {
            $msg = $apk_infos[0];
            $this->ajaxReturn(responseMsg(0, $type, $msg));
        } else {
            // $msg =json_encode(new class{});
            $this->ajaxReturn(responseMsg(0, $type, null));
        }
    }

    /**
     * 获取最新首页大图
     */
    public function getNewestHomepage()
    {
        $type = 1002;
        $post = touristApiPreTreat($type);
        $Homepage = D('homepage');
        $homepage_info = $Homepage->order('time desc')
            ->limit(1)
            ->select();
        if ($homepage_info) {
            $msg = $homepage_info[0];
            $this->ajaxReturn(responseMsg(0, $type, $msg));
        } else {
            $this->ajaxReturn(responseMsg(1, $type));
        }
    }
}