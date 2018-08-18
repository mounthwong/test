<?php

namespace app\admin\validate;

use think\Validate;

class App extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'name' => 'require',
        'apppath' => 'require',
        'changelog' => 'require',
        'version'    => 'require',
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
        'add'  => ['name', 'apppath', 'changelog','version'],
        'edit' => ['name', 'apppath', 'changelog','version'],
    ];
    
}
