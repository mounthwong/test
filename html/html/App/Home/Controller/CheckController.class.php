<?php
namespace Home\Controller;

use Think\Controller;

/**
 * 验证是否登录控制器类
 *
 * @author         gm
 * @since          1.0
 */
class CheckController extends Controller {

    public function _initialize() {
	     /* 如果没有登录，则跳转到登录页面 */
	    $adminuser=session('adminuser');
        if (empty($adminuser)) {
            $this->redirect('login/nolog');
        }
    }

}
