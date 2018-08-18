<?php

namespace app\common\library;
use think\Config;
/**
 * 会员接口
 */
class Card
{

    //获取这张卡再数据库中有没有
    public static function getCardInfo($cardnum){
        $result = \app\common\model\Card::where(['cardnum' => $cardnum])
                ->find();
        return $result?$result:null;
    }

    //用户添加卡
    public static function addUserCard($username,$cardnum){
        $time = time();
        $result = \app\common\model\UserCard::create(['userid' => $username, 'cardnum' => $cardnum, 'createtime' => $time]);
        if ($result)
        {
            return $result;
        }else{
            return False;
        }
    }

    //查询用户的卡
    public static  function getUserCardList($username){
        $result = collection(\app\common\model\UserCard::with("cardinfos")->where(['userid' => $username])->order('createtime', 'DESC')->select())->toArray();
        $cardtypes=Config::get("card");
        if(!empty($result)){
            foreach($result as $key=>$value){
                foreach($value["cardinfos"] as $k=>$v){
                    $result[$key]["cardinfos"][$k]["cardtype"]=$cardtypes[$v["cardtype"]];
                }
            }
        }
        return $result?$result:null;
    }

    //用户删除卡
    public static function delUserCard($username,$cardnum){
        $result=\app\common\model\UserCard::where(["cardnum"=>$cardnum,"userid"=>$username])->delete();
        return $result?$result:null;
    }

    public static function getCardProductByCardNum($cardnum){
        $result = \app\common\model\Card::where(['cardnum' => $cardnum])->find();
        $cardtype=$result["cardtype"];
        $cardtypes=Config::get("card");

        $cardproducts=\app\common\model\CardProduct::getCardProductinfos();

        $list=array();

        if(isset($cardproducts[$cardtype])){
            $list=$cardproducts[$cardtype]["productinfos"];
        }else{
            $list=array();
        }

        return $list?$list:null;
    }
}
