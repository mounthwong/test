<?php

namespace app\api\controller;

use app\common\controller\Api;
use app\common\library\Product;
use fast\Random;

/**
 * 会员接口
 */
class Products extends Api
{

    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';

    public function _initialize()
    {
        parent::_initialize();
    }

    //获取分类以及产品
    public function getProductsCatagloy(){
      $result=Product::getProductsCatagloy();
      $this->success(__('get productscatagloy successful'), $result);
    }

    //获取某一个分类下面的产品
    public function getProductsByCataid(){
      $cataid=$this->request->request("cataid/d");
      $result=Product::getProductsByCataid($cataid);
      $this->success(__('get products successful'), $result);
    }

    //获取所有的产品
    public function getAllCataAndProducts(){
      $result=Product::getAllCataAndProducts();
      $this->success(__('get allproducts successful'), $result);
    }

    //获取某一个产品的信息
    public function getProductById(){
      $id=$this->request->request("id/d");
      $result=Product::getProductById($id);
      $this->success(__('get product successful'), $result);
    }
}
