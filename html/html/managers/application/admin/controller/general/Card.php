<?php

namespace app\admin\controller\general;

use app\common\controller\Backend;
use think\Config;

/**
 * 附件管理
 *
 * @icon fa fa-circle-o
 * @remark 主要用于管理上传到又拍云的数据或上传至本服务的上传数据
 */
class Card extends Backend
{

    protected $model = null;
    protected $cardtypes=[];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('card');
        $this->cardtypes=Config::get("card");
        $this->view->assign("cardtypes",$this->cardtypes);
        $this->view->assign("cardtype",1);
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
            if($this->request->request('addtabs')){
                return $this->import();
            }
            if ($this->request->request('keyField'))
            {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                    ->where($where)
                    ->order($sort, $order)
                    ->count();
            $list = $this->model
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();
            $cardtypes=Config::get("card");
            foreach($list as $key=>$value){
                $list[$key]["cardtype"]=$cardtypes[$value["cardtype"]];
            }
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }
    

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
                $params["createtime"]=time();
                $result = $this->model->save($params);
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

    //导入
    public function import(){
        $filename=$this->request->request("file");
        $filename="../public".$filename;
        //读取excel文件
        vendor('phpExcel.PHPExcel');
        $arr_excel = readExcel($filename);
        //A B C D E G 
        $arrcolumn = array('A', 'B', 'C', 'D');
        $i = 0;
        $iserr = false;
        foreach ($arr_excel as $row => $v) {
            if (empty($v["Column"]['A'])) {
                unset($arr_excel[$row]);
                continue;
            }
            /* 必填列判断 */
            foreach ($arrcolumn as $cv) {
                $v["iserr"] = false;
                if (empty($v["Column"][$cv])) {
                    $v["iserr"] = true;
                    $v["errmsg"] = '第' . $row . '行' . $cv . '列为空';
                    $arr_excel[$row] = $v;
                    $iserr = true;
                    break;
                }
            }
            if ($v["iserr"]) {
                continue;
            }
            $i++;
            $params["createtime"]=time();
            $params["cardnum"]=$v["Column"]['A'];
            $params["name"]=$v["Column"]['B'];
            $params["begintime"]=$v["Column"]['C'];
            $params["endtime"]=$v["Column"]['D'];
            $result = $this->model->save($params);
            if ($result === false)
            {
                $this->error($this->model->getError());
            }
        }
        $this->success();
    }

    public function config()
    {
        $siteList = [];
        //获取卡的类型信息
        $cardtypes=C("card");
        //获取卡绑定的产品的信息
        $productinfos=
        $groupList = ConfigModel::getGroupList();
        foreach ($groupList as $k => $v) {
            $siteList[$k]['name'] = $k;
            $siteList[$k]['title'] = $v;
            $siteList[$k]['list'] = [];
        }
        foreach ($this->model->all() as $k => $v) {
            if (!isset($siteList[$v['group']])) {
                continue;
            }
            $value = $v->toArray();
            $value['title'] = __($value['title']);
            if (in_array($value['type'], ['select', 'selects', 'checkbox', 'radio'])) {
                $value['value'] = explode(',', $value['value']);
            }
            $value['content'] = json_decode($value['content'], TRUE);
            $siteList[$v['group']]['list'][] = $value;
        }
        $index = 0;
        foreach ($siteList as $k => &$v) {
            $v['active'] = !$index ? true : false;
            $index++;
        }
        $this->view->assign('siteList', $siteList);
        $this->view->assign('typeList', ConfigModel::getTypeList());
        $this->view->assign('groupList', ConfigModel::getGroupList());
        return $this->view->fetch();
    }


}
