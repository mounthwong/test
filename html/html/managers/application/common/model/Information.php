<?php

namespace app\common\model;

use think\Model;

/**
 * 会员模型
 */
class Information Extends Model
{

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createtime = 'createtime';
    protected $updatetime = 'updatetime';
    

}
