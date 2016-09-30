<?php
namespace Home\Model;
use Think\Model;
class IntroduceImagesModel extends Model{
    /**
     * 得到推荐的图片数组
     * @param unknown $id 推荐id
     * 返回该推荐的图片一维数组
     */
    public function getIntroImg($id){
        // 得到图片二维数组
        $image = $this->where(array(
            'introduce_id' => $id
        ))
        ->field('imageurl')
        ->select(); 
        // 转为一维数组
        foreach ($image as $a => $b) { 
            $img[] = $b["imageurl"];
        }
        return $img;
    }
}