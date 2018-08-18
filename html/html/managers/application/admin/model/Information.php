<?php

namespace app\admin\model;

use think\Model;

class Information extends Model
{

    // 表名
    protected $name = 'information';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;
    // 定义时间戳字段名
    protected $createtime = 'createtime';
    protected $updatetime = 'updatetime';
    
}
