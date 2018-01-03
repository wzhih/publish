<?php
/**
 * Created by PhpStorm.
 * User: winton
 * Date: 2017/11/6
 * Time: 21:26
 */

namespace app\admin\controller;

use think\Db;

class Front extends Base
{
    public function getSwiper()
    {
        $data = Db::name('swiper')->select();

        $this->assign('data', $data);
        return $this->fetch();
    }

    public function setSwiper()
    {
        $data = input('post.');
        $photo_url = uploading('photo_url', 'static'. DS .'portal'. DS .'img'. DS .'swiper');

        $object = "swiper/" . basename($photo_url);
        $photo_url = upload_oss($object, $photo_url);

        if ($photo_url) {
            $data['photo_url'] = $photo_url;
        }

        $result = Db::name('swiper')->data($data)->update();
        if ($result) {
            $this->success('更新成功');
        }

        $this->error('更新失败');
    }
}