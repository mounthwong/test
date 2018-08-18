<?php

namespace app\api\controller;

use app\common\controller\Api;
use app\common\library\Information as Info;
use fast\Random;

/**
 * 会员接口
 */
class Information extends Api
{

    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';

    public function _initialize()
    {
        parent::_initialize();
    }

    //获取资讯信息
    public function getMessageList(){
        $offset = $this->request->request('offset',0);
        $num = $this->request->request('num',10);
        $apptype = $this->request->request('apptype',0);
        $childtype = $this->request->request('childtype',1);
        $ret = Info::getMessageList($offset,$num,$apptype,$childtype);
        $data = $ret;
        $this->success(__('Logged in successful'), $data);
    }

    public function searchMessage(){
        $offset = $this->request->request('offset',0);
        $num = $this->request->request('num',10);
        $apptype = $this->request->request('apptype',0);
        $keyword = $this->request->request('keyword',"");
        if(empty(trim($keyword))){
            $this->error(__('param is not empty'), array());
        }else{
            $ret = Info::searchMessage($apptype,$keyword,$offset,$num);
            $data = $ret;
            $this->success(__('information is successful'), $data);
        }
    }

    
    

    

}
