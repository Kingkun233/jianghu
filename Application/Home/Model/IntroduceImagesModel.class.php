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
        ->field('imageurl,thumb_imageurl')
        ->select(); 
        // 转为一维数组
        $img=array();
        foreach ($image as $a => $b) { 
            $image[$a]["small_pic"]=$b["thumb_imageurl"];
            $image[$a]["big_pic"]=$b["imageurl"];
            unset($image[$a]["thumb_imageurl"]);
            unset($image[$a]["imageurl"]);
            $img[]=$image[$a];
        }
        return $img;
    }
    public function getIntroThumbImg($id){
        // 得到图片二维数组
        $image = $this->where(array(
            'introduce_id' => $id
        ))
        ->field('thumb_imageurl')
        ->select();
        // 转为一维数组
        foreach ($image as $a => $b) {
            $thumd_img[] = $b["thumb_imageurl"];
        }
        return $thumd_img;
    }
}