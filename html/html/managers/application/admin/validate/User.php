<?php

namespace app\admin\validate;

use think\Validate;

class User extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'username' => 'require|max:50|unique:user',
        'nickname' => 'require',
        'password' => 'require',
        'mobile'    => 'require',
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
        'add'  => ['username', 'nickname', 'password','mobile'],
        'edit' => ['username', 'nickname','mobile'],
    ];
    
}
