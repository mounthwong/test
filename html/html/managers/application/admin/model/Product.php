<?php

namespace app\admin\model;

use think\Model;

class Product extends Model
{

    // 表名
    protected $name = 'product';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    

    public function cataglory()
    {
        return $this->belongsTo('ProductCatagloy', 'cataid', 'id', [], 'LEFT')->setEagerlyType(0);
    }

}
