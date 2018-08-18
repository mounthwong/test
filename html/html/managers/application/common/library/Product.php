<?php

namespace app\common\library;

use think\Hook;

/**
 * 邮箱验证码类
 */
class Product
{

    //获取产品的分类
    public static function getProductsCatagloy(){
      $result= \app\common\model\ProductCatagloy::order("weigh desc")->select();
      return $result;
    }

    //获取某一个分类下面的产品
    public static function getProductsByCataid($cataid){
      $result= \app\common\model\ProductCatagloy::with("productinfos")
            ->where(['id'=>$cataid])
            ->order("weigh desc")
            ->find();
      return $result;
    }

    //获取所有的产品
    public static function getAllCataAndProducts(){
      $result= \app\common\model\ProductCatagloy::with("productinfos")
            ->order("weigh desc")
            ->select();
      return $result;
    }


    //获取某一个产品
    public static function getProductById($productid){
      $result= \app\common\model\Product::with("productinfos")
            ->where(['id'=>$productid])
            ->find();
      return $result;
    }


}
