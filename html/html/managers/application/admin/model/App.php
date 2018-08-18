<?php

namespace app\admin\model;

use think\Model;

class App extends Model
{

    // 表名
    protected $name = 'app';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;
    // 定义时间戳字段名
    protected $createtime = 'createtime';
    protected $updatetime = 'updatetime';
    
}
