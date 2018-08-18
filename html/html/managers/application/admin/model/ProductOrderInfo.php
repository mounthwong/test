<?php

namespace app\admin\model;

use think\Model;

/**
 * 会员模型
 */
class ProductOrderInfo Extends Model
{

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = false;
    // 定义时间戳字段名
    protected $createtime = 'createtime';
    protected $updatetime = 'updatetime';

    public function productinfo()
    {
        return $this->belongsTo('Product', 'productid', 'id', [], 'LEFT')->setEagerlyType(0);
    }
    

}