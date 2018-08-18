<?php

namespace app\common\library;

/**
 * 邮箱验证码类
 */
class Hospital
{

    /**
     * 验证码有效时长
     * @var int 
     */
    protected static $expire = 120;

    /**
     * 最大允许检测的次数
     * @var int 
     */
    protected static $maxCheckNums = 10;

    //获取资讯信息
    public static function getHospitalList(){
        $rs = \app\common\model\Hospital::
                order('addtime', 'DESC')
                ->select();
        return $rs ;
    }

}
