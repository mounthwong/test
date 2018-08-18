<?php

namespace app\admin\model;

use think\Cache;
use think\Model;

class ProductOrderProcedure extends Model
{

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = false;
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

    public function admininfo()
    {
        return $this->belongsTo('Admin', 'adminid', 'username', [], 'LEFT');
    }

    public function touserinfo()
    {
        return $this->belongsTo('Admin', 'touserid', 'username', [], 'LEFT');
    }


    //订单信息
    public function orderinfo(){
    	return $this->belongsTo('ProductOrder', 'orderid', 'orderid');
    }
}
