<?php

namespace app\api\controller;

use app\common\controller\Api;
use app\common\library\Order as UserOrder;
use fast\Random;

/**
 * 会员接口
 */
class Order extends Api
{

    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';

    public function _initialize()
    {
        parent::_initialize();
    }

    //获取用户的订单
    public function getUserOrderList(){
        $username=$this->request->request("username");
        $status=$this->request->request("status",0);
        $offset=$this->request->request("offset",0);
        $num=$this->request->request("num",10);
        $result=UserOrder::getUserOrderList($username,$status,$offset,$num,$status);
        $this->success(__('get userOrderList successful'), $result);
    }

    //修改订单状态
    public function changeUserOrderStatus(){
      $orderid=$this->request->request("orderid/s");
      $status=$this->request->request("status/d");
      $result=UserOrder::changeUserOrderStatus($orderid,$status);
      $this->success(__('get userOrderList successful'), $result);
    }

    //用户下订单$username,$type,$amount,$productinfos
    public function addUserOrder(){
        $username=$this->request->request("username");
        $nickname=$this->request->request("truename");
        $productinfos=$this->request->request("productinfos");
        $amount=$this->request->request("amount");
        $phone=$this->request->request("phone");
        $remark=$this->request->request("remark");
        $source=$this->request->request("source/,0"); //0表示直接购买 1表示购物车购买
        $type=$this->request->request("type/d",0);
        //这个必须是json字符串
        $productinfos=json_decode($productinfos,true);
        //$type=$this->request->request("type");
        if($source===1){//删除购物车东西
          UserOrder::delShoppingCart($username,$productinfos);
          //生成订单
          $result=UserOrder::addUserOrder($username,$type,$amount,$productinfos,$phone,$nickname,$remark);
        }else{
          $result=UserOrder::addUserOrder($username,$type,$amount,$productinfos,$phone,$nickname,$remark);
        }
        $this->success(__('Add userOrder successful'), $result);
    }

    //获取用户的某一个订单
    public function getUserOrderById(){
        $orderid=$this->request->request("orderid");
        $result=UserOrder::getOrderInfo($orderid);
        $this->success(__('get userOrder successful'), $result);
    }

    //订单分派
    public function orderAssign(){
        $touser=$this->requests->request("touser");
        $orderid=$this->request->request("orderid");
        $begintime=$this->request->request("begintime");
        $endtime=$this->request->request("endtime");
        $adminid=$this->request->request("adminid");
        $remark=$this->request->request("remark");
        $result=UserOrder::assignOrder($orderid,$adminid,$touser,$begintime,$endtime,$remark);
        //需要在这里进行广播
        $this->success(__('assign userOrder successful'), $result);
    }

    //订单状态完成
    public function payUserOrder(){
      $orderid=$this->request->request("orderid");
      $payorderid=$this->request->request("payorderid");
      $result=UserOrder::userPayOrder($orderid,$payorderid);
      $this->success(__('pay userOrder successful'), $result);
    }

    //获取订单的流程
    public function getOrderProcedure(){
      $orderid=$this->request->request("orderid");
      $result=UserOrder::getOrderProcedure($orderid);
      $this->success(__('pay userOrder successful'), $result);
    }

    //员工修改订单的流程
    public function changeOrderProcedure(){
      $procedureid=$this->request->request("procedureid");
      $status=$this->request->request("status/d",0);
      $remark=$this->request->request("remark");
      $result=UserOrder::changeOrderProcedure($procedureid,$status,$remark);
      //修改完成之后需要给管理员进行广播
      $this->success(__('change userOrderProcedure successful'), $result);
    }

    public function addShoppingCart(){
      $username=$this->request->request("username");
      $productid=$this->request->request("productid");
      $result=UserOrder::addShoppingCart($username,$productid);
      //修改完成之后需要给管理员进行广播
      $this->success(__('add shoppingCart successful'), $result);
    }

    public function delShoppingCart(){
      $username=$this->request->request("username");
      $productinfos=$this->request->request("productinfos");
      $result=UserOrder::delShoppingCart($username,$productinfos);
      $this->success(__('delete shoppingCart successful'), $result);
    }

    
}
