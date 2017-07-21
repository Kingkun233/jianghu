<?php
namespace Admin\Controller;

use Think\Controller;

class UpdateController extends Controller
{

    public function index()
    {
        $Update = D('update');
        $count = $Update->count();
        $Page = new \Think\Page($count, 10);
        $pageshow = page($Page);
        $list = $Update->limit($Page->firstRow . ',' . $Page->listRows)->order('time desc')->select();
        $this->assign('update', $list);
        $this->assign('page', $pageshow);
        $this->display();
    }

    public function add()
    {
        $this->display();
    }

    /**
     * 添加新apk
     */
    public function doadd()
    {
        $Update = D('update');
        $add['description'] = I('description');
        $version = I('version');
        $apk_path = $this->ApkUpload($version);
        $add['url'] = $apk_path;
        $add['time'] = date("Y-m-d H:i:s");
        $add['version'] = $version;
        // 对文件进行md5并保存
        $file = str_replace("http://" . $_SERVER['SERVER_NAME'] . __ROOT__, '.', $apk_path);
        $add['md5'] = md5_file($file);
        // 文件大小也保存下来
        $add['filesize'] = filesize($file);
        $flag = $Update->add($add);
        if ($flag) {
            $this->success("版本添加成功", U('update/index'));
        } else {
            $this->error("版本添加失败");
        }
    }

    public function download()
    {
        $Update = D('update');
        $id = I('id');
        $apk_info = $Update->where(array(
            'id' => $id
        ))->find($id);
        $file = str_replace("http://" . $_SERVER['SERVER_NAME'] . __ROOT__, '.', $apk_info['url']);
        // 下载文件
        if (is_file($file)) {
            $length = filesize($file);
            $type = mime_content_type($file);
            $showname = ltrim(strrchr($file, '/'), '/');
            header("Content-Description: File Transfer");
            header('Content-type: ' . $type);
            header('Content-Length:' . $length);
            if (preg_match('/MSIE/', $_SERVER['HTTP_USER_AGENT'])) { // for IE
                header('Content-Disposition: attachment; filename="' . rawurlencode($showname) . '"');
            } else {
                header('Content-Disposition: attachment; filename="' . $showname . '"');
            }
            readfile($file);
        } else {
            echo '文件不存在!';
        }
    }

    /**
     * apk上传
     *
     * @return apk路径
     */
    private function ApkUpload($apkname)
    {
        $upload = new \Think\Upload(); // 实例化上传类
        $upload->maxSize = 104857600; // 设置附件上传大小100M
        $upload->exts = array(
            'apk'
        ); // 设置附件上传类型
        $upload->rootPath = './Uploads/';
        $upload->savePath = "apk/"; // 设置附件上传目录
        $upload->saveName = $apkname;
        $upload->autoSub = true;
        $upload->subName = array(
            'date',
            'Y-m-d'
        );
        // 上传文件
        $info = $upload->upload();
        if (! $info) {
            $this->error($upload->getError());
        } else {
            $url = "http://" . $_SERVER['SERVER_NAME'] . __ROOT__ . '/Uploads/apk/' . date('Y-m-d') . "/" . $apkname . '.apk';
            return $url;
        }
    }
}
