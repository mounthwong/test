<?php

namespace app\common\library;

use think\Hook;
use fast\Random;
/**
 * 邮箱验证码类
 */
class Order
{

    //获取用户的订单
    public static function getUserOrderList($username,$status,$offset,$num){
       if($status==0){
            $result = \app\common\model\ProductOrder::with("orderinfos,orderprocedures")
                ->where(['userid' => $username])
                ->order('updatetime', 'desc')
                ->limit($offset,$num)
                ->select();
            return $result?$result:null;
        }else{
            $result = \app\common\model\ProductOrder::with("orderinfos,orderprocedures")
                ->where(['userid' => $username,'status'=>$status])
                ->order('updatetime', 'desc')
                ->limit($offset,$num)
                ->select();
            return $result?$result:null;
        }
	
    }


    //生成订单
    public static function addUserOrder($username,$type,$amount,$productinfos,$phone,$nickname,$remark){
        $time = time();
        $orderid=md5($username.$time.mt_rand(10000,99999));
        $result = \app\common\model\ProductOrder::create(['status'=>1,'orderid'=>$orderid,'userid' => $username, 'type' => $type, 'amount' => $amount, 'createtime' => $time, 'updatetime' => $time, 'phone' => $phone, 'truename' => $nickname,'remark'=>$remark]);
        //经产品具体信息插入数据库
        foreach($productinfos as $key=>$value){
            \app\common\model\ProductOrderInfo::create(['addtime' => $time,'userid' => $username, 'orderid' => $orderid, 'productid' => $value["productid"], 'productinfoid' => $value["productinfoid"], 'name' => $value["name"], 'productname' => $value["productname"],'pic'=>$value["pic"], 'num' => $value["num"], 'price' => $value["price"]]);
        }
        if ($result)
        {
            $ret["orderid"]=$orderid;
            $ret["createtime"]=$time;
            return $ret;
        }else{
            return False;
        }
    }
    
    public static function getOrderInfo($orderid){
        $result = \app\common\model\ProductOrder::with("orderinfos")
                ->where(['orderid' => $orderid])
                ->find();
        return $result?$result:null;
    }

    //支付成功回掉地址
    public static function userPayOrder(){
        
    }
	 
    public static function changeOrderStatus($orderid,$status,$transaction_id,$type){
        $time = time();
        $result = \app\common\model\ProductOrder::where("orderid",$orderid)->update(['type'=>$type,'status'=>$status,'paytime'=>$time,'updatetime'=>$time,'payorderid'=>$transaction_id]);
        return $result;
    }

    public static function changeUserOrderStatus($orderid,$status){
        $time = time();
        $result = \app\common\model\ProductOrder::where("orderid",$orderid)->update(['status'=>$status,'updatetime'=>$time]);
        return $result;
    }
    /**
     * 获取最后一次邮箱发送的数据
     *
     * @param   int       $email   邮箱
     * @param   string    $event    事件
     * @return  Ems
     */
    public static function getArchivesList($username,$offset = 0,$num=10,$type=0)
    {
        if($type==0){
            $result = \app\common\model\UserArchives::with("userinfos")
                ->where(['userid' => $username])
                ->order('addtime', 'DESC')
                ->limit($offset,$num)
                ->select();
        }else{
            $result = \app\common\model\UserArchives::with("userinfos")
            ->where(['userid' => $username,'type'=>$type])
            ->order('addtime', 'DESC')
            ->limit($offset,$num)
            ->select();
        }
        return $result ? $result : NULL;
    }

    /**
     * 发送验证码
     *
     * @param   int       $email   邮箱
     * @param   int       $code     验证码,为空时将自动生成4位数字
     * @param   string    $event    事件
     * @return  boolean
     */
    public static function setUserArchives($username,$type = 0,$remark,$attachmentids)
    {
        $time = time();
        $result = \app\common\model\UserArchives::create(['userid' => $username, 'type' => $type, 'remark' => $remark, 'addtime' => $time, 'attachmentids' => $attachmentids]);
        if ($result)
        {
            return $result;
        }else{
            return False;
        }
    }

    //删除档案图片
    public static function delUserArchivesAttachment($id,$attachid){
        
        //删除附件表中的信息
        $result=\app\common\model\Attachment::where(["id"=>$attachid])->delete();
        if($result){
            if($id){
                $archivers = \app\common\model\UserArchives::where(['id' => $id])->find();
                $attachmentids=$archivers["attachmentids"];
                $arr=explode(",", $attachmentids);
                //删除键值是$attachid的
                foreach ($arr as $key=>$value)
                {
                  if ($value === $attachid)unset($arr[$key]);
                }
                //将数组转成字符串
                $attachmentids=implode(",", $arr);
                // 更改用户的name和email的值
                $data = array('attachmentids'=>$attachmentids);
                $result=\app\common\model\UserArchives::where('id',$id)->setField($data);
                if($result){
                    return $result;
                }else{
                    return False;
                }
            }else{
                return $result;
            }
        }else{
            return False;
        }
        
    }

    //删除档案信息
    public static function deleteUserArchives($id){
        $result=\app\common\model\UserArchives::where(["id"=>$id])->delete();
        if($result){
            return $result;
        }else{
            return False;
        }
    }

    //修改健康档案
    public static function updateUserArchives($id,$username,$type,$remark,$attachmentids){
        // 更改用户的name和email的值
        $data = array('attachmentids'=>$attachmentids,"userid"=>$username,"type"=>$type,"remark"=>$remark,"attachmentids"=>$attachmentids);
        $result=\app\common\model\UserArchives::where(['id' => $id])->setField($data);
        if($result){
            return $result;
        }else{
            return False;
        }
    }

}
