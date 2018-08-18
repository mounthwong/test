<?php

namespace app\admin\model;

use think\Model;

class CardProduct extends Model
{

    // 表名
    protected $name = 'card_product';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;
    

    public function productinfo()
    {
        return $this->hasMany('product', 'id', 'productid', [], 'LEFT');
    }

    //获取卡的详细信息
    public function getCardProductinfos(){
        $cardinfos = collection(self::with(['productinfo' => function($query){$query->field("id,name");}])->select())->toArray();
        $cardproductinfos = [];
        foreach ($cardinfos as $k => $v)
        {   
	    if(!empty($v["productinfo"])){
	       $cardproductinfos[$v["cardtype"]]["productinfos"][] = $v["productinfo"][0];
            }else{
               $cardproductinfos[$v["cardtype"]]["productinfos"][] = array();
            }
        }
        return $cardproductinfos;
    }

    

}
