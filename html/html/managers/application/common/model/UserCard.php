<?php

namespace app\common\model;

use think\Model;

/**
 * 会员模型
 */
class UserCard Extends Model
{

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = false;
    // 定义时间戳字段名
    protected $createtime = 'createtime';
    protected $updatetime = 'updatetime';

    public function cardinfos(){
        return $this->hasMany('Card', 'id', 'cardnum', [], 'LEFT');
    }
}