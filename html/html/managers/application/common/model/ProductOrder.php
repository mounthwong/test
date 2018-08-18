<?php

namespace app\common\model;

use think\Model;

/**
 * 会员模型
 */
class ProductOrder Extends Model
{

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = false;
    // 定义时间戳字段名
    protected $createtime = 'createtime';
    protected $updatetime = 'updatetime';

    public function orderinfos(){
        return $this->hasMany('ProductOrderInfo', 'orderid', 'orderid', [], 'LEFT');
    }

    public function orderprocedures(){
    	return $this->hasMany('ProductOrderProcedure', 'orderid', 'orderid', [], 'LEFT');
    }

}