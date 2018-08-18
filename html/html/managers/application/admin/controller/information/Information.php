<?php

namespace app\admin\controller\information;

use app\common\controller\Backend;
use fast\Random;
use think\Config;
/**
 * 会员管理
 *
 * @icon fa fa-user
 */
class Information extends Backend
{

    protected $relationSearch = true;
    protected $infocataglory=[];

    /**
     * User模型对象
     */
    protected $model = null;
    

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Information');
        $this->infocataglory=Config::get("infocataglory");
        $this->view->assign("infocataglory",$this->infocataglory);
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
                    ->where($where)
                    ->order($sort, $order)
                    ->count();
            $list = $this->model
                    ->where($where)
                    ->order($sort, $order)
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
        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");

            if ($params)
            {
		if($params["type"]==0){
                    $pattern = '/<a href=".*?".*?>(.*?)<\/a>/i';
                    //使用preg_match()函数进行匹配
                    if(preg_match($pattern, $params["content"], $matches)) {          
                        $params["content"]=$matches[1];
                        $params["contenttype"]=0;
                        //$params["type"]=1;    //数组中第二个元素保存第一个子表达式
                    } else {
                    	if(substr($params["content"],0,3) == 'htt' || substr($params["content"],0,3) == 'Htt'){
                            $urlpattern='/(http|https):\/\/([\w\d\-_]+[\.\w\d\-_]+)[:\d+]?([\/]?[\w\/\.]+)/i';
                        if(preg_match($urlpattern,$params["content"])){
                            $params["contenttype"]=0;
                        }else{
                            $params["contenttype"]=1;
                        }  
                        }else{
                            $params["contenttype"]=1;
                        }
		    }
                }else{
		    $pattern = '/<a href=".*?".*?>(.*?)<\/a>/i';
                    if(preg_match($pattern, $params["content"], $matches)) {
                        if(strpos($matches[1],'http') !==false || strpos($matches[1],'https') !==false){
                            $params["content"]=$matches[1];
                            $params["contenttype"]=0;
                        }else{
		            if(substr($params["content"],0,3) == 'htt' || substr($params["content"],0,3) == 'Htt'){
                            $urlpattern='/(http|https):\/\/([\w\d\-_]+[\.\w\d\-_]+)[:\d+]?([\/]?[\w\/\.]+)/i';
                        if(preg_match($urlpattern,$params["content"])){
                            $params["contenttype"]=0;
                        }else{
                            $params["contenttype"]=1;
                        }  
                        }else{
                            $params["contenttype"]=1;
                        }
                        }      
                    } else {
                        $params["content"]=$params["content"];
			if(substr($params["content"],0,3) == 'htt' || substr($params["content"],0,3) == 'Htt'){
			    $urlpattern='/(http|https):\/\/([\w\d\-_]+[\.\w\d\-_]+)[:\d+]?([\/]?[\w\/\.]+)/i';
                        if(preg_match($urlpattern,$params["content"])){
                            $params["contenttype"]=0;
                        }else{
                            $params["contenttype"]=1;
                        }  
			}else{
                            $params["contenttype"]=1; 
			}
                    }
                }
		 $params["pic"]=str_replace("http://www.hnumg.cn/managers/public/","",$params["pic"]);
		$pattern = '/<img alt=.*? width="(.*?)" height="(.*?)" src=".*?">/i';
                $replacement = '${0}100%,${1}null';
                $params["content"]=preg_replace($pattern, $replacement, $params["content"]);
                $result=$this->model->save($params, ['id' => $ids]);
                if ($result === false)
                {
                    $this->error($this->model->getError());
                }
                $this->success();
            }
            $this->error();
        }
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
	        if($params["type"]==0){
                    $pattern = '/<a href=".*?".*?>(.*?)<\/a>/i';
                    //使用preg_match()函数进行匹配
                    if(preg_match($pattern, $params["content"], $matches)) {
                        $params["content"]=$matches[1];
                        $params["contenttype"]=0;
                        //$params["type"]=1;    //数组中第二个元素保存第一个子表达式
                    } else {
                        if(substr($params["content"],0,3) == 'htt' || substr($params["content"],0,3) == 'Htt'){
                            $urlpattern='/(http|https):\/\/([\w\d\-_]+[\.\w\d\-_]+)[:\d+]?([\/]?[\w\/\.]+)/i';
                        if(preg_match($urlpattern,$params["content"])){
                            $params["contenttype"]=0;
                        }else{
                            $params["contenttype"]=1;
                        }
                        }else{
                            $params["contenttype"]=1;
                        }
                    }
                }else{
                    $pattern = '/<a href=".*?".*?>(.*?)<\/a>/i';
                    if(preg_match($pattern, $params["content"], $matches)) {
                        if(strpos($matches[1],'http') !==false || strpos($matches[1],'https') !==false){
                            $params["content"]=$matches[1];
                            $params["contenttype"]=0;
                        }else{
                            if(substr($params["content"],0,3) == 'htt' || substr($params["content"],0,3) == 'Htt'){
                            $urlpattern='/(http|https):\/\/([\w\d\-_]+[\.\w\d\-_]+)[:\d+]?([\/]?[\w\/\.]+)/i';
                        if(preg_match($urlpattern,$params["content"])){
                            $params["contenttype"]=0;
                        }else{
                            $params["contenttype"]=1;
                        }
                        }else{
                            $params["contenttype"]=1;
                        }
                        }
                    } else {
                        $params["content"]=$params["content"];
                        if(substr($params["content"],0,3) == 'htt' || substr($params["content"],0,3) == 'Htt'){
                            $urlpattern='/(http|https):\/\/([\w\d\-_]+[\.\w\d\-_]+)[:\d+]?([\/]?[\w\/\.]+)/i';
                        if(preg_match($urlpattern,$params["content"])){
                            $params["contenttype"]=0;
                        }else{
                            $params["contenttype"]=1;
                        }
                        }else{
                            $params["contenttype"]=1;
                        }
                    }
                }
		 $params["pic"]=str_replace("http://www.hnumg.cn/managers/public/","",$params["pic"]);
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

}
