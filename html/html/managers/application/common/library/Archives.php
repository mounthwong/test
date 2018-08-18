<?php

namespace app\common\library;

use think\Hook;

/**
 * 邮箱验证码类
 */
class Archives
{



    /**
     * 获取最后一次邮箱发送的数据
     *
     * @param   int       $email   邮箱
     * @param   string    $event    事件
     * @return  Ems
     */
    public static function getArchivesList($username,$offset = 0,$num=10,$type=0,$begintime,$endtime,$hospitalid)
    {
        $map['addtime']  = array('egt',$begintime);
        $map['addtime']  = array('elt',$endtime);
        $map["userid"]=$username;
        if(!empty($hospitalid)){
           $map["hospitalid"]=$hospitalid; 
        }
        if($type==0){

            $result = \app\common\model\UserArchives::with("userinfos,hospitalinfos")
                ->where($map)
                ->order('addtime', 'DESC')
                ->limit($offset,$num)
                ->select();
        }else{
            $map["type"]=$type;
            $result = \app\common\model\UserArchives::with("userinfos,hospitalinfos")
            ->where($map)
            ->order('addtime', 'DESC')
            ->limit($offset,$num)
            ->select();
        }
        //查看附件信息
        $ids=array();
        foreach($result as $key=>$value){
          if($value["attachmentids"]){
            $attment=explode(",",$value["attachmentids"]);
            $result[$key]["attachmentids"]=$attment;
          }
        }
        // $ids=array_unique($ids);
        // $attachments=\app\common\model\Attachment::all(implode(",",$ids));
        // $attarr=array();
        // foreach($attachments as $key=>$value){
        //   $attarr[$value->id]=$value->url;
        // }
        // foreach($result as $key=>$value){
        //   $pics=array();
        //   $idarr=explode(",",$value["attachmentids"]);
        //   foreach($idarr as $k=>$v){
        //     $pics[]=$attarr[$v];
        //   }
        //   $result[$key]["attachmentids"]=$pics;
        // }
        return $result ? $result : NULL;
    }


    public static function getUserArchivesCount($username){
        $result = \app\common\model\UserArchives::where(['userid' => $username])->group("type")->field("type,count(*) as count")->order("type")->select();
        $ret=array();
        foreach($result as $key=>$value){
            $temp=array();
            if($value["type"]==1){
                $temp["type"]=1;
                $temp["count"]=$value["count"];
            }else if($value["type"]==2){
                $temp["type"]=2;
                $temp["count"]=$value["count"];
            }
            array_push($ret,$temp);
        }
        $count=count($ret);
        //判断是不是两个
        if($count==0){
            $ret[0]["type"]=1;
            $ret[0]["count"]=0;
            $ret[1]["type"]=2;
            $ret[1]["count"]=0;
        }else if($count==1){
            if($ret[0]["type"]==1){
                $ret[0]["type"]=$ret[0]["type"];
                $ret[0]["count"]=$ret[0]["count"];
                $ret[1]["type"]=2;
                $ret[1]["count"]=0;
            }else if($ret[0]["type"]==2){
                $ret[0]["type"]=1;
                $ret[0]["count"]=0;
                $ret[1]["type"]=$ret[0]["type"];
                $ret[1]["count"]=$ret[0]["count"];
            }
        }else if($count==2){

        }
        return $ret;
    }

    /**
     * 发送验证码
     *
     * @param   int       $email   邮箱
     * @param   int       $code     验证码,为空时将自动生成4位数字
     * @param   string    $event    事件
     * @return  boolean
     */
    public static function setUserArchives($username,$type = 1,$remark,$hospitalid,$attachmentids,$addtime)
    {
        $time = time();
        $result = \app\common\model\UserArchives::create(['userid' => $username, 'type' => $type, 'hospitalid'=>$hospitalid,'remark' => $remark, 'addtime' => $addtime, 'attachmentids' => $attachmentids]);
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
    public static function updateUserArchives($id,$username,$type,$remark,$hospitalid,$attachmentids,$addtime){
        // 更改用户的name和email的值
        $data = array('attachmentids'=>$attachmentids,"userid"=>$username,"hospitalid"=>$hospitalid,'addtime'=>$addtime,"type"=>$type,"remark"=>$remark,"attachmentids"=>$attachmentids);
        $result=\app\common\model\UserArchives::where(['id' => $id])->setField($data);
        if($result){
            return $result;
        }else{
            return False;
        }
    }

    //获取某一个健康档案
    public static function getUserArchivesById($id){
        $result = \app\common\model\UserArchives::where(['id' => $id])->select();
        return $result;
    }

}
