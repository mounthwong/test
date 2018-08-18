<?php

namespace app\admin\controller\product;

use app\common\controller\Backend;
use fast\Random;
/**
 * 会员管理
 *
 * @icon fa fa-user
 */
class Product extends Backend
{

    protected $relationSearch = true;

    /**
     * User模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Product');
    }

    /**
     * 查看
     */
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax())
        {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField'))
            {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                    ->with('cataglory')
                    ->where($where)
                    ->order($sort, $order)
                    ->count();
            $list = $this->model
                    ->with('cataglory')
                    ->where($where)
                    ->order("weigh desc,id")
                    ->limit($offset, $limit)
                    ->select();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
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
        $this->view->assign('catalist', build_select('row[cataid]', \app\admin\model\ProductCatagloy::column('id,name'), $row['cataid'], ['class' => 'form-control selectpicker']));

        return parent::edit($ids);
    }

    //添加
    //
    //row[cataid]:2
    // row[pic]:/uploads/20180607/a2c2a2da95421dec5e53581bb431f3b3.png
    // row[title]:fasdfasda
    // row[desp]:<p>fasdfasdfasd</p>
    // row[issale]:0
    // row[isshow]:0
    // row[buynum]:12
    // row[cartnum]:12
    // row[collectnum]:12
    public function add()
    {
        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            if ($params)
            {
                $params["pic"]=$params["pic"];
                $params["weigh"]=1;
                $params["intro"]=preg_replace("/alt=\".*?\"/i","style='max-width:100%;'",$params["intro"]);
                $result = $this->model->save($params);
                //var_dump($result);exit;
                //规格的添加
                $data["productid"]=$this->model->id;
                $data["price"]=$params["discount"];
                $data["name"]="默认规格";
                $data["pic"]=$params["pic"];
                $data["store"]=99999;
                $data["defaultselect"]=1;
                $data["addtime"]=Date("Y-m-d H:i:s");
                model('ProductInfo')->save($data);
                if ($result === false)
                {
                    $this->error($this->model->getError());
                }
                $this->success();
            }
            $this->error();
        }
        $this->view->assign('catalist', build_select('row[cataid]', \app\admin\model\ProductCatagloy::column('id,name'), 0, ['class' => 'form-control selectpicker']));
        return $this->view->fetch();
    }


    /**
     * 编辑
     */
    public function card($ids = NULL)
    {
        $row = $this->model->get($ids);
        if (!$row)
            $this->error(__('No Results were found'));
        $this->view->assign('groupList', build_select('row[group_id]', \app\admin\model\UserGroup::column('id,name'), $row['group_id'], ['class' => 'form-control selectpicker']));
        return parent::edit($ids);
    }
    
    public function getCataglory(){
        $data=model("product_catagloy")->select();
        echo json_encode($data);
    }

    public function changeCataglory(){
        $cataid = $this->request->get("cataid/d");
        $id = $this->request->get("id/d");
        $this->model->where("id",$id)->update(["cataid"=>$cataid]);
    }


    public function getAllProduct(){
        
    }
}
