<?php

namespace app\common\library;
use think\Config;

/**
 * 邮箱验证码类
 */
class Information
{

    /**
     * 验证码有效时长
     * @var int 
     */
    protected static $expire = 120;

    /**
     * 最大允许检测的次数
     * @var int 
     */
    protected static $maxCheckNums = 10;

    //获取资讯信息
    public static function getMessageList($offset=0,$num=10,$type=0,$childtype=1){
        if($type==0){//表示的是资讯
            $rs = \app\common\model\Information::where(["type"=>$type,"cataglory"=>$childtype])
                ->order('createtime', 'DESC')
                ->limit($offset,$num)
                ->select();
	     foreach($rs as $key=>$value){
                if($value["contenttype"]==0){
                    $rs[$key]["content"]=$value["content"];
                }else{
                    $rs[$key]["content"]=Config::get("host")."/managers/public/index.php/index/index/infos?id=".$value["id"];
                }
            }
            return $rs?$rs:null;
        }else{//表示的是自救
            $rs = \app\common\model\Information::where(["type"=>$type])
            ->order('createtime', 'DESC')
            ->limit($offset,$num)
            ->select();
	     foreach($rs as $key=>$value){
                if($value["contenttype"]==0){
                    $rs[$key]["content"]=$value["content"];
                }else{
                    $rs[$key]["content"]=Config::get("host")."/managers/public/index.php/index/index/infos?id=".$value["id"];
                }
            }
            return $rs?$rs:null;
        }
    }

    public static function getCataList(){
        return Config::get("infocataglory");
    }

    public static function searchMessage($apptype,$keyword,$offset,$num){
        $where['type'] = $apptype;
        $map['title'] = array('like','%'.$keyword.'%');
        $map['desp'] = array('like','%'.$keyword.'%');
        $rs = \app\common\model\Information::where('type='.$apptype.' AND (title like "%'.$keyword.'%" or desp like "%'.$keyword.'%")')
                ->order('createtime', 'DESC')
                ->limit($offset,$num)
                ->select();
        foreach($rs as $key=>$value){
            if($value["contenttype"]==0){
                $rs[$key]["content"]=$value["content"];
            }else{
                $rs[$key]["content"]=Config::get("host")."/managers/public/index.php/index/index/infos?id=".$value["id"];
            }
        }
        return $rs?$rs:null;
    }

    //获取某一条信息
    public static function getInfoById($id){
        $rs=\app\common\model\Information::where("id",$id)->find();
        return $rs?$rs:null;
    }
}
