<?php

namespace app\admin\controller\product;

use app\common\controller\Backend;
use fast\Random;
/**
 * 会员管理
 *
 * @icon fa fa-user
 */
class Group extends Backend
{

    protected $relationSearch = true;

    /**
     * User模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('ProductCatagloy');
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
            $total = $this->model
                    ->where("isdel",0)
                    ->order("addtime desc")
                    ->count();
            $list = $this->model
                    ->where("isdel",0)
                    ->order("addtime desc")
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
                $params['salt'] = Random::alnum();
                $params['password'] = md5(md5($params['password']) . $params['salt']);
                $params['avatar'] = (isset($params['salt'])||empty($params['salt']))?'/assets/img/avatar.png':$params['avatar']; //设置新管理员默认头像。
                $params['jointime'] = time();
                $params['joinip'] = $_SERVER['REMOTE_ADDR'];
                $result = $this->model->validate('User.add')->save($params);
                if ($result === false)
                {
                    $this->error($this->model->getError());
                }
                $this->success();
            }
            $this->error();
        }
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

}
