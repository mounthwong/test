<?php

namespace app\api\controller;

use app\common\controller\Api;
use app\common\library\Card as userCard;
use fast\Random;

/**
 * 会员接口
 */
class Card extends Api
{

    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';

    public function _initialize()
    {
        parent::_initialize();
    }

    //获取这张卡数据库是否存在
    public function getCardInfo(){
        $cardnum=$this->request->request("cardnum");
        $result=userCard::getCardInfo($cardnum);
        $this->success(__('get userOrderList successful'), $result);
    }

    //用户添加卡
    public function addUserCard(){
        $username=$this->request->request("username");
        $cardnum=$this->request->request("cardnum");
        $result=userCard::addUserCard($username,$cardnum);
        $this->success(__('get userOrderList successful'), $result);
    }

    //查询用户的卡
    public  function getUserCardList(){
        $username=$this->request->request("username");
        $result=userCard::getUserCardList($username);
        $this->success(__('get userOrderList successful'), $result);

    }

    //用户删除卡
    public function delUserCard(){
        $username=$this->request->request("username");
        $cardnum=$this->request->request("cardnum");
        $result=userCard::delUserCard($username,$cardnum);
        $this->success(__('get userOrderList successful'), $result);
    }

    public function getCardProductByCardNum(){
        $cardnum=$this->request->request("cardnum");
        $result=userCard::getCardProductByCardNum($cardnum);
        $this->success(__('get userOrderList successful'), $result);
    }
}
