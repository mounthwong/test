<?php

namespace app\admin\controller\order;

use app\common\controller\Backend;
use fast\Random;
use think\Cookie;
/**
 * 会员管理
 *
 * @icon fa fa-user
 */
class Process extends Backend
{

    protected $relationSearch = true;

    /**
     * User模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('ProductOrderProcedure');
    }

    public function detail($ids){
        //显示客户名称以及客户订购的产品
        $orderrs=model("ProductOrder")->where("id",$ids)->find();
        //显示订购的产品
        $productrs=\app\admin\model\ProductOrderInfo::with("productinfo")->where("orderid",$orderrs["orderid"])->find();
        $this->view->assign("order",$orderrs);
        $this->view->assign("product",$productrs);
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        $list = $this->model
                ->with('admininfo,touserinfo')
                ->where("orderid",$ids)
                ->select();
        $this->view->assign("list",$list);
        $this->view->assign("ids",$ids);
        return $this->view->fetch();
    }
    /**
     * 编辑
     */
    public function edit($ids = NULL)
    {
        $row = $this->model->get($ids);
        if (!$row)
            $this->error(__('No Results were found'));
        $this->view->assign('groupList', build_select('row[touserid]', \app\admin\model\Admin::column('username as id,nickname as name'), $row['touserid'], ['class' => 'form-control','disabled'=>true]));
        return parent::edit($ids);
    }

    //添加
    public function add()
    {
        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            if ($params)
            {
                $params['orderid'] = $params["orderid"];
                //查询这个订单
                $rs = model('ProductOrder')->where("id",$params["orderid"])->find();
                $params['fromuserid']=$rs["userid"];
                $params['adminid']=Cookie::get('username');
                $params['touserid'] = $params["touserid"];
                $params['begintime'] = $params["begintime"];
                $params['endtime'] = $params["endtime"];
                $params['status'] = $params['status'];
                $params['remark'] = $params['remark'];
                $params['addtime'] = Date("Y-m-d");
                $result = $this->model->save($params);
                if ($result === false)
                {
                    $this->error($this->model->getError());
                }
                $this->success();
            }
            $this->error();
        }
        $this->view->assign("ids",$this->request->get("ids",0));
        $this->view->assign('groupList', build_select('row[touserid]', \app\admin\model\Admin::column('username as id,nickname as name'), 0, ['class' => 'form-control']));
        return $this->view->fetch();
    }

}
