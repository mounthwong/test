<?php

namespace app\admin\controller\general;

use app\common\controller\Backend;
use fast\Random;
use fast\Tree;
use think\Config;

/**
 * 管理员管理
 *
 * @icon fa fa-users
 * @remark 一个管理员可以有多个角色组,左侧的菜单根据管理员所拥有的权限进行生成
 */
class Product extends Backend
{

    protected $model = null;
    protected $productIds = [];
    protected $cardtypes = [];
    protected $list=[];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('CardProduct');
        $this->cardtypes=Config::get("card");

        $cardproducts=$this->model->getCardProductinfos();

        $list=array();

        foreach($this->cardtypes as $key=>$value){
            $list[$key-1]["id"]=$key;
            $list[$key-1]["name"]=$value;
            if(isset($cardproducts[$key])){
                $list[$key-1]["products"]=$cardproducts[$key]["productinfos"];
            }else{
                $list[$key-1]["products"]=array();
            }
        }

        $this->list=$list;
    }

    /**
     * 查看
     */
    public function index()
    {
        if ($this->request->isAjax())
        {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField'))
            {
                return $this->selectpage();
            }
            $list=$this->list;
            foreach ($list as $k => &$v)
            {
                $groups = $v['products'];
                $names=array();
                if(!empty($groups)){
                    foreach($groups as $key=>$value){
                        $names[]=$value["name"];
                    }
                }
                $v['products'] = implode(',', $names);
            }
            unset($v);
            $result = array("total" => count($list), "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 编辑
     */
    public function edit($ids = NULL)
    {
        $row=$this->list[$ids-1];

        if (!$row)
            $this->error(__('No Results were found'));
        if ($this->request->isPost())
        {
            $params = $this->request->post("group/a");
	     $this->model->where("cardtype",$ids)->delete();
            if ($params)
            {
                foreach($params as $key=>$value){
                    $param["cardtype"]=$ids;
                    $param["productid"]=$value;
                    $param["createtime"]=time();
                    $this->model->create($param);
                }
                $this->success();
            }
            $this->error();
        }
        $groupids = [];
        if(empty($row["products"])){

        }else{
            foreach ($row["products"] as $k => $v)
            {
                $groupids[] = $v['id'];
            }
        }
        $result=model("Product")->select();
        $groupName = [];
        foreach ($result as $k => $v)
        {
            $groupName[$v['id']] = $v['name'];
        }
        $this->view->assign("groupdata", $groupName);
        $this->view->assign("row", $row);
        $this->view->assign("ids",$ids);
        $this->view->assign("groupids", $groupids);
        return $this->view->fetch();
    }

}
