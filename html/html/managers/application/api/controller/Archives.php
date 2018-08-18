<?php

namespace app\api\controller;

use app\common\controller\Api;
use app\common\library\Archives as Arch;
use fast\Random;
use think\Validate;

/**
 * 会员接口
 */
class Archives extends Api
{

    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';

    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 用户档案
     *
     * @param string username 账号
     * @param string offset 起始
     * @param string num 每次获取的条数
     */
    public function getUserArchivesList(){
        $username=$this->request->request("username");
        $type=$this->request->request("type",0);
        $hospitalid=$this->request->request("hospitalid");
        $begintime=$this->request->request("begintime",time());
        $endtime=$this->request->request("endtime",time());
        $offset=$this->request->request("offset",0);
        $num=$this->request->request("num",10);
        //查询用户上传的附件信息
        $ret = Arch::getArchivesList($username,$offset,$num,$type,$begintime,$endtime,$hospitalid);
        $data = $ret;
        $this->success(__('Get userArchives successful'), $data);
    }

    //接口普查询用户当前的病历个个数
    public function getUserArchivesCount(){
        $username=$this->request->request("username");
        $ret = Arch::getUserArchivesCount($username);
        $this->success(__('Get userArchivesCount successful'), $ret);
    }

    

    /**
     * 用户档案
     *
     * @param string username 账号
     * @param string type 类型
     * @param string images 类型
     * @param string remark 备注信息
     */
    public function setUserArchives(){
        $username=$this->request->request("username");
        $type=$this->request->request("type");
        $remark=$this->request->request("remark");
        $addtime=$this->request->request("addtime");
        $hospitalid=$this->request->request("hospitalid");
        $attachmentids=$this->request->request("attachmentids");
        $ret = Arch::setUserArchives($username,$type,$remark,$hospitalid,$attachmentids,$addtime);
        if($ret){
            $data = $ret;
            $this->success(__('UserArchives set successful'), $data);
        }else{
            $data = ['result' => array()];
            $this->error(__('UserArchives set error'), $data);
        }
    }

    //删除健康档案的信息
    public function deleteUserArchivesAttachment(){
        $id=$this->request->request("archivesid");
        $attachid=$this->request->request("attachid");
        $ret = Arch::delUserArchivesAttachment($id,$attachid);
        if($ret){
            $data = $ret;
            $this->success(__('UserArchivesAttachment del successful'), $data);
        }else{
            $data = array();
            $this->error(__('UserArchivesAttachment del error'), $data);
        }
    }

    //删除健康档案
    public function deleteUserArchives(){
        $id=$this->request->request("archivesid");
        $ret = Arch::deleteUserArchives($id);
        if($ret){
            $data = $ret;
            $this->success(__('UserArchives del successful'), $data);
        }else{
            $data = array();
            $this->error(__('UserArchives del error'), $data);
        }
    }

    //修改健康档案
    public function editUserArchives(){
        $id=$this->request->request("archivesid");
        $username=$this->request->request("username");
        $type=$this->request->request("type");
        $hospitalid=$this->request->request("hospitalid");
        $addtime=$this->request->request("addtime");
        $remark=$this->request->request("remark");
        $attachmentids=$this->request->request("attachmentids");
        $ret = Arch::updateUserArchives($id,$username,$type,$remark,$hospitalid,$attachmentids,$addtime);
        if($ret){
            $data = $ret;
            $this->success(__('UserArchives edit successful'), $data);
        }else{
            $data = array();
            $this->error(__('UserArchives edit error'), $data);
        }
    }


    //获取某一个健康档案
    public function getUserArchivesById(){
        $id=$this->request->request("archivesid");
        $ret = Arch::getUserArchivesById($id);
        $this->success(__('get UserArchives successful'), $ret);
    }
}
