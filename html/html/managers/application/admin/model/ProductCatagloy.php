<?php

namespace app\admin\model;

use think\Cache;
use think\Model;

class ProductCatagloy extends Model
{

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = false;
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';


}
