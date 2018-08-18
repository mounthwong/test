<?php

namespace app\admin\validate;

use think\Validate;

class Information extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'title' => 'require',
        'pic' => 'require',
        'desp' => 'require',
        'url'    => 'require',
    ];
    /**
     * 提示消息
     */
    protected $message = [
    ];
    /**
     * 验证场景
     */
    protected $scene = [
        'add'  => ['title', 'pic', 'desp','url'],
        'edit' => ['title', 'pic', 'desp','url'],
    ];
    
}
