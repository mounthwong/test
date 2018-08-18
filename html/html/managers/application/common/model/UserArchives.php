<?php

namespace app\common\model;

use think\Cache;
use think\Model;

/**
 * 地区数据模型
 */
class UserArchives extends Model
{

    // 表名
    protected $name = 'user_archives';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    // 追加属性
    protected $append = [
    ];

    public function userinfos()
    {
        return $this->belongsTo('User', 'userid', 'username', [], 'LEFT')->setEagerlyType(0);
    }


    public function hospitalinfos()
    {
        return $this->hasOne('Hospital', 'id', 'hospitalid', [], 'LEFT');
    }

}
