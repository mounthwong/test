<?php

namespace app\admin\model;

use think\Model;

class ProductOrder extends Model
{

    // 表名
    protected $name = 'product_order';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    

    public function userinfo()
    {
        return $this->belongsTo('User', 'userid', 'username', [], 'LEFT')->setEagerlyType(0);
    }


    //订单信息
    public function orderinfo(){
        return $this->belongsTo('ProductOrderInfo', 'orderid', 'orderid', [], 'LEFT')->setEagerlyType(0);
    }

}
