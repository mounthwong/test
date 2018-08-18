<?php

namespace app\admin\model;

use think\Cache;
use think\Model;

class UserCard extends Model
{

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = false;
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

    public function productinfos()
    {
        return $this->hasOne('product', 'id', 'productid');
    }
    
        //获取卡的信息
    public function card(){
        return $this->hasOne('Card', 'cardnum',"cardnum")->order("id desc");
    }
}
