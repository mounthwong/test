<?php

namespace app\index\controller;

use app\common\controller\Frontend;
use app\common\library\Token;
use think\Jump;
use app\common\library\Information as Info;

class Index extends Frontend
{

    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';
    protected $layout = '';

    public function _initialize()
    {
        parent::_initialize();
    }

    public function index()
    {
        $this->redirect(url('admin/index/login'));
    }

    public function news()
    {
        $newslist = [];
        return jsonp(['newslist' => $newslist, 'new' => count($newslist), 'url' => 'https://www.fastadmin.net?ref=news']);
    }

    public function infos(){
        $id=$this->request->request('id');
        $rs=Info::getInfoById($id);
        $this->view->assign('title', $rs["title"]);
        $this->view->assign('content', $rs["content"]);
        return $this->view->fetch();
    }

}
