<?php
namespace app\admin\controller\order;

use app\common\controller\Backend;
use fast\Random;
use think\Cookie;
use think\Loader;
use think\Config;
use app\common\library\Order as UserOrder;
/**
 * 会员管理
 *
 * @icon fa fa-user
 */
class Order extends Backend
{

    protected $relationSearch = true;

    /**
     * User模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('ProductOrder');
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
                    ->with('userinfo,orderinfo')
                    ->where($where)
                    ->order($sort, $order)
                    ->count();
            $list = $this->model
                    ->with('userinfo,orderinfo')
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();
            foreach ($list as $k => $v)
            {
                $v->hidden(['password', 'salt']);
            }
            $result = array("total" => $total, "rows" => $list);

            return json($result);
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

     /**
     * 详情
     */
    public function detail($ids)
    {
        $row = model('ProductOrderProcedure')->get(['orderid' => $ids]);
        if ($row){
            $this->view->assign("row", $row->toArray());
        }
        else{
            $this->view->assign("row", array());
        }
        
        return $this->view->fetch();
    }

    // 客户信息
    public function edit($ids = NULL){
        $row = $this->model->get($ids);
        if (!$row)
            $this->error(__('No Results were found'));
        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            if ($params)
            {
                try
                {
                    //是否采用模型验证
                    $result = $row->save($params);
                    $username=$params["adminid"];
                    $data["fromuserid"]=Cookie::get('username');
                    $data["touserid"]=$username;
                    $data["orderid"]=$row["orderid"];
                    $data["addtime"]=time();
                    $res=$productOrderProcedure=model("ProductOrderProcedure")->save($data);

                    if ($result !== false && $res !== false)
                    {
                        $this->success();
                    }
                    else
                    {
                        $this->error($row->getError());
                    }
                }
                catch (\think\exception\PDOException $e)
                {
                    $this->error($e->getMessage());
                }

                //再记录表中添加一条数据
                
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $this->view->assign("row", $row);
        //查询受理人
        $username=Cookie::get('username');
        $rs=model("Admin")->where("username",$username)->find();
        $truename=$rs["nickname"];
        $this->view->assign("truename",$truename);
        $this->view->assign('groupList', build_select('row[adminid]', \app\admin\model\Admin::column('username,nickname'), 0, ['class' => 'form-control selectpicker tds']));
        return $this->view->fetch();
    }
    
    public function custom($ids){
        //查询这个用户的账号
        $order=$this->model->where("id",$ids)->find();
        $user=\app\admin\model\User::where("username",$order["userid"])->find();
        $userid=$user["username"];
        $truename=$user["nickname"];
        //查询这个人的记录
        $groupdata["专家咨询"] = "专家咨询";
        $groupdata["报告解读"] = "报告解读";
        $groupdata["初次检查"] = "初次检查";
        $groupdata["复查"] = "复查";
        $groupdata["做手术"] = "做手术";
        $groupdata["办理住院"] = "办理住院";
        $groupdata["床位预约"] = "床位预约";
        $groupdata["床位协调"] = "床位协调";
        //查询这个人的记录
        //$order = $this->model->where(["userid"=>$userid])->where("overtime is null")->order("createtime desc")->find();
        $this->view->assign("groupdata", $groupdata);
        $this->view->assign("order",$order);
        // $this->view->assign("user",$user);
        $this->view->assign("truename",$truename);
        $this->view->assign('firstList', build_select('order[kfsendperson]', \app\admin\model\Admin::column('username,nickname'), empty($order)?0:$order["kfsendperson"], ['class' => 'form-control tds']));
        $this->view->assign('secondList', build_select('order[kfsendperson]', \app\admin\model\Admin::column('username,nickname'), empty($order)?0:$order["kfsendperson"], ['class' => 'form-control tds']));
        return $this->view->fetch("order");
    }

    public function exportWord(){
        $id = $this->request->get("id/d");
         //获取记录
         $record=model("UserRecords")->get($id);
         //订单客户信息信息
         $order = $this->model->where("orderid",$record["orderid"])->find();
         //获取客户信息
         $user=model("user")->where("username",$record["username"])->find();
        //写word
        echo '
        <html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:w="urn:schemas-microsoft-com:office:word" xmlns="http://www.w3.org/TR/REC-html40">
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <xml><w:WordDocument><w:View>Print</w:View></xml>
        </head>';
        echo '<body>
        <h1 style="text-align: center">个人信息表</h1>
        <h3>编号：000001</h3>
        <table class="table table-striped custom" border="1" cellpadding="3" cellspacing="0">
            <tbody>
                <tr>
                    <td><strong>姓名</strong></td><td><span class="info">'.$user["nickname"].'</span></td>
                    <td><strong>身份证号</strong></td><td><span class="info">'.$user["idcard"].'</span></td>
                    <td><strong>年龄</strong></td><td><span class="info">'.$user["age"].'</span> <strong>性别</strong></td><td><span class="info">'.$user["sex"].'</span></td>
                </tr>
                <tr>
                    <td><strong>手机号</strong></td><td><span class="info">'.$user["mobile"].'</span></td>
                    <td><strong>服务卡号</strong></td><td><span class="info">123456789</span></td>
                    <td><strong>生日</strong></td><td><span class="info">'.$user["birthday"].'</span></td>
                </tr>
                <tr>
                    <td><strong>客源</strong></td><td><span class="info">app订购</span></td>
                    <td><strong>三级机构</strong></td><td><span class="info">'.$user["city"].'</span></td>
                    <td><strong>四级机构</strong></td><td><span class="info">'.$user["region"].'</span></td>
                </tr>
            </tbody>
        </table>
        <table  border="1" cellpadding="3" cellspacing="0">
            <tbody>
            <tr>
                <td><strong>医生</strong></td>
                <td>
                    <div class="form-group">
                        '.$order["doctor"].'
                    </div>
                </td>
                <td><strong>科室</strong></td>
                <td>
                    <div class="form-group">
                        '.$order["section"].'
                    </div>
                </td>
            </tr>
            <tr>
                <td><strong>就诊总时间</strong></td>
                <td>
                    <div class="form-group">
                        '.$record["treatmenttimes"].'
                    </div>
                </td>
                <td><strong>医生看诊时长</strong></td>
                <td>
                    <div class="form-group">
                        '.$record["diagnosetimes"].'
                    </div>
                </td>
            </tr>
            <tr>
                <td><strong>患者是否同意下一步的检查</strong></td>
                <td colspan="3">
                    <div class="form-group">
                        '.$record["nextcheck"].'
                    </div>
                </td>
            </tr>
            <tr>
                <td rowspan="3"><strong>检查</strong></td>
                <td><strong>检查项目</strong></td>
                <td colspan="2">
                    <div class="form-group">
                        '.$record["checkproject"].'
                    </div>
                </td>
            </tr>
            <tr>
                <td><strong>取结果方式</strong></td>
                <td colspan="2">
                    <div class="form-group">
                        '.$record["checkresult"].'
                    </div>
                </td>
            </tr>
            <tr>
                <td><strong>备注</strong></td>
                <td colspan="2">
                    <div class="form-group">
                        '.$record["remark"].'
                    </div>
                </td>
            </tr>
            <tr>
                <td rowspan="2"><strong>看诊</strong></td>
                <td><strong>疾病诊断</strong></td>
                <td colspan="2">
                    <div class="form-group">
                        '.$record["diagnosis"].'
                    </div>
                </td>
            </tr>
            <tr>
                <td><strong>医生建议（治疗方案）</strong></td>
                <td colspan="2">
                    <div class="form-group">
                        '.$record["diagnoseadvice"].'
                    </div>
                </td>
            </tr>
            <tr>
                <td rowspan="3"><strong>取药</strong></td>
                <td><strong>药物名称及用法</strong></td>
                <td colspan="2">
                    <div class="form-group">
                        '.$record["medicines"].'
                    </div>
                </td>
            </tr>
            <tr>
                <td><strong>取药方式</strong></td>
                <td colspan="2">
                    <div class="form-group">
                        '.$record["medicineget"].'
                    </div>
                </td>
            </tr>
            <tr>
                <td><strong>注意事项</strong></td>
                <td colspan="2">
                    <div class="form-group">
                        '.$record["medicineadvice"].'
                    </div>
                </td>
            </tr>
            <tr>
                <td rowspan="2"><strong>住院</strong></td>
                <td><strong>患者意见</strong></td>
                <td colspan="2">
                    <div class="form-group">
                        '.$record["hospitaladvice"].'
                    </div>
                </td>
            </tr>
            <tr>
                <td><strong>住院号</strong></td>
                <td colspan="2">
                    <div class="form-group">
                       '.$record["hospitalnum"].'
                    </div>
                </td>
            </tr>
            <tr>
                <td ><strong>复查</strong></td>
                <td colspan="3">
                    <div class="form-group">
                       '.$record["nextcheck"].'
                    </div>
                </td>
            </tr>
            <tr>
                <td ><strong>备注</strong></td>
                <td colspan="3">
                    <div class="form-group">
                        '.$record["remark"].'
                    </div>
                </td>
            </tr>
            <tr>
                <td  rowspan="2"><strong>对医生评价</strong></td>
                <td><strong>陪诊人员</strong></td>
                <td colspan="2">
                    <div class="form-group">
                        '.$record["appraiseadmin"].'
                    </div>
                </td>
            </tr>
            <tr>
                <td><strong>患者及家属</strong></td>
                <td colspan="3">
                    <div class="form-group">
                        '.$record["appraisecustom"].'
                    </div>
                </td>
            </tr>
            <tr>
                <td><strong>陪诊人员</strong></td>
                <td colspan="3">
                    <div class="form-group">
                        '.$record["admins"].'
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
        </body>';
        ob_start(); //打开缓冲区
        header("Cache-Control: public");
        Header("Content-type: application/octet-stream");
        Header("Accept-Ranges: bytes");
        if (strpos($_SERVER["HTTP_USER_AGENT"],'MSIE')) {
        header('Content-Disposition: attachment; filename=test.doc');
        }else if (strpos($_SERVER["HTTP_USER_AGENT"],'Firefox')) {
        Header('Content-Disposition: attachment; filename=test.doc');
        } else {
        header('Content-Disposition: attachment; filename=test.doc');
        }
        header("Pragma:no-cache");
        header("Expires:0");
        ob_end_flush();//输出全部内容到浏览器
    }


    //客服接受订单查看订单
    public function order($ids){
        //查询这个用户的账号
        $user=\app\admin\model\User::where("id",$ids)->find();
        $userid=$user["username"];
        $truename=$user["nickname"];
        exit;
        $groupdata["专家咨询"] = "专家咨询";
        $groupdata["报告解读"] = "报告解读";
        $groupdata["初次检查"] = "初次检查";
        $groupdata["复查"] = "复查";
        $groupdata["做手术"] = "做手术";
        $groupdata["办理住院"] = "办理住院";
        $groupdata["床位预约"] = "床位预约";
        $groupdata["床位协调"] = "床位协调";
        //查询这个人的记录
        $order = $this->model->where(["userid"=>$userid])->where("overtime is null")->order("createtime desc")->find();
        $this->view->assign("groupdata", $groupdata);
        $this->view->assign("order",$order);
        $this->view->assign("user",$user);
        $this->view->assign("truename",$truename);
        exit;
        $this->view->assign('firstList', build_select('order[kfsendperson]', \app\admin\model\Admin::column('username,nickname'), empty($order)?0:$order["kfsendperson"], ['class' => 'form-control tds']));
        $this->view->assign('secondList', build_select('order[kfsendperson]', \app\admin\model\Admin::column('username,nickname'), empty($order)?0:$order["kfsendperson"], ['class' => 'form-control tds']));
        return $this->view->fetch();
    }

    public function exportComfirmExcel(){
        $id = $this->request->get("id/d");
        //订单客户信息信息
        $order = $this->model->where("id",$id)->find();
        $admin=model("Admin")->where("username",$order["kfsendperson"])->find();
        //获取客户信息
        //$user=model("")->where("username",$order["userid"])->find();
        Vendor("phpExcel.PHPExcel");
        //$xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称
        $fileName = $order["truename"]."确诊表".date('_YmdHis');//or $xlsTitle 文件名称可根据自己情况设定
        $cellNum = 4;

        $objPHPExcel = new \PHPExcel();
        $sheetcount=0;
        $objPHPExcel->getDefaultStyle()->getFont()->setName('宋体');
        $objPHPExcel->getDefaultStyle()->getFont()->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objPHPExcel->getActiveSheet(0)->getStyle('A1:D1')->getFont()->setBold(true);
        $styleThinBlackBorderOutline = array(
            'borders' => array(
                'allborders' => array( //设置全部边框
                    'style' => \PHPExcel_Style_Border::BORDER_THIN //粗的是thick
                ),
    
            ),
        );
        $objPHPExcel->getActiveSheet(0)->getStyle('A1:D21')->applyFromArray($styleThinBlackBorderOutline);
        
        $objPHPExcel->getActiveSheet(0)->getStyle('A1:D1')->getFont()->setBold(true)->setSize(16); //字体加粗 
        $objPHPExcel->getActiveSheet(0)->setCellValue('A1', "诊前信息确认表");
        $objPHPExcel->getActiveSheet(0)->mergeCells('A1:D1');
        $objPHPExcel->getActiveSheet(0)->getStyle('A1:D1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //$objPHPExcel->getActiveSheet(0)->getStyle('A1:D1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);
        $objPHPExcel->getActiveSheet(0)->setCellValue('A2', "姓名");
        $objPHPExcel->getActiveSheet(0)->getStyle('A2')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet(0)->setCellValue('B2', $order["truename"]);
        $objPHPExcel->getActiveSheet(0)->setCellValue('C2', "性别");
        $objPHPExcel->getActiveSheet(0)->getStyle('C2')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet(0)->setCellValue('D2', empty($order["gender"])?"":(($order["gender"]==1)?"男":"女"));
        $objPHPExcel->getActiveSheet(0)->setCellValue('A3', "手机号");
        $objPHPExcel->getActiveSheet(0)->getStyle('A3')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet(0)->setCellValue('B3', $order["phone"]);
        $objPHPExcel->getActiveSheet(0)->setCellValue('C3', "年龄");
        $objPHPExcel->getActiveSheet(0)->getStyle('C3')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet(0)->setCellValue('D3', $order["age"]);
        $objPHPExcel->getActiveSheet(0)->getStyle('D3:D3')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);
        $objPHPExcel->getActiveSheet(0)->setCellValue('A4', "身份证号");
        $objPHPExcel->getActiveSheet(0)->getStyle('A4')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet(0)->setCellValue('B4', $order["idcard"]);
        $objPHPExcel->getActiveSheet(0)->setCellValue('C4', "服务项目");
        $objPHPExcel->getActiveSheet(0)->getStyle('C4')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet(0)->setCellValue('D4', $order["customserver"]);
        $objPHPExcel->getActiveSheet(0)->setCellValue('A5', "客户现状");
        $objPHPExcel->getActiveSheet(0)->getStyle('A5')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet(0)->mergeCells('A5:A6');
        $objPHPExcel->getActiveSheet(0)->getStyle('B5:D6')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->setCellValue('B5', $order["customdetail"]);
        $objPHPExcel->getActiveSheet(0)->mergeCells('B5:D6');
        $objPHPExcel->getActiveSheet(0)->getStyle('B5:D6')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->setCellValue('A7', "具体服务需求");
        $objPHPExcel->getActiveSheet(0)->getStyle('A7')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet(0)->mergeCells('A7:A8');
        $objPHPExcel->getActiveSheet(0)->mergeCells('B7:D8');
        $objPHPExcel->getActiveSheet(0)->getStyle('B7:D8')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->setCellValue('A9', "医生选择");
        $objPHPExcel->getActiveSheet(0)->getStyle('A9')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet(0)->setCellValue('B9', ($order["appointdoctortype"]==0)?"医联推荐":"客户指定");
        $objPHPExcel->getActiveSheet(0)->setCellValue('C9', "预约就诊时间");
        $objPHPExcel->getActiveSheet(0)->getStyle('C9')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet(0)->setCellValue('D9', $order["appointtime"]);
        $objPHPExcel->getActiveSheet(0)->setCellValue('A10', "预约就诊医生");
        $objPHPExcel->getActiveSheet(0)->getStyle('A10')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet(0)->setCellValue('B10', $order["appointdoctor"]);
        $objPHPExcel->getActiveSheet(0)->setCellValue('C10', "预约就诊医院");
        $objPHPExcel->getActiveSheet(0)->getStyle('C10')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet(0)->setCellValue('D10', $order["appointhospital"]);
        $objPHPExcel->getActiveSheet(0)->setCellValue('A11', "预约就诊科室");
        $objPHPExcel->getActiveSheet(0)->getStyle('A11')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet(0)->setCellValue('B11', $order["appointsection"]);
        $objPHPExcel->getActiveSheet(0)->setCellValue('C11', "支付金额");
        $objPHPExcel->getActiveSheet(0)->getStyle('C11')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet(0)->setCellValue('D11', $order["amount"]);
        $objPHPExcel->getActiveSheet(0)->getStyle('D11:D11')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);
        $objPHPExcel->getActiveSheet(0)->setCellValue('A12', "接诊人员");
        $objPHPExcel->getActiveSheet(0)->getStyle('A12')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet(0)->setCellValue('B12', $admin["nickname"]);
        $objPHPExcel->getActiveSheet(0)->setCellValue('C12', "填写日期");
        $objPHPExcel->getActiveSheet(0)->getStyle('C12')->getFont()->setBold(true);
        //时间戳转日期

        $objPHPExcel->getActiveSheet(0)->setCellValue('D12', date('Y-m-d H:i:s', $order["appointsendtime"]));
        $objPHPExcel->getActiveSheet(0)->setCellValue('A13', "客户的要求补充");
        $objPHPExcel->getActiveSheet(0)->getStyle('A13')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet(0)->mergeCells('A13:A15');
        $objPHPExcel->getActiveSheet(0)->mergeCells('B13:D15');
        $objPHPExcel->getActiveSheet(0)->setCellValue('A16', "温馨提醒：\r\n1、以上预约信息已得到客户同意。\r\n2、诊前注意事项已由接诊人提前提醒客户（如：携带历史检查报告、空腹等）。\r\n3、接诊人员仅提供就医服务，与客户在医院产生的治疗行为和结果无关。\r\n4、医院人多拥挤，请注意妥善保管贵重物品（如手机、钱包、身份证等）。");
        $objPHPExcel->getActiveSheet(0)->getRowDimension(16)->setRowHeight(50);
        $objPHPExcel->getActiveSheet(0)->mergeCells('A16:D18');
        $objPHPExcel->getActiveSheet(0)->setCellValue('A19', "是否同意建立健康档案:");
        $objPHPExcel->getActiveSheet(0)->mergeCells('A19:D19');
        $objPHPExcel->getActiveSheet(0)->setCellValue('A20', "若对以上内容无异议，请签字确认。");
        $objPHPExcel->getActiveSheet(0)->mergeCells('A20:D20');
        $objPHPExcel->getActiveSheet(0)->setCellValue('A21', "客户签字处:");
        $objPHPExcel->getActiveSheet(0)->getStyle('A21')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet(0)->setCellValue('B21', "");
        $objPHPExcel->getActiveSheet(0)->setCellValue('C21', "签字时间:");
        $objPHPExcel->getActiveSheet(0)->getStyle('C21')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet(0)->setCellValue('D21', "");
        ob_end_clean();//清除缓冲区,避免乱码
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename='.$fileName.'.xls');
        header('Cache-Control: max-age=0');

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        // $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        // $objWriter->save($fileName.".xls");
        // downfile("./".$fileName.".xls",$fileName.".xls");
    }


    public function exportDiannosisExcel(){
        $id = $this->request->get("id/d");
        //订单客户信息信息
        $order = $this->model->where("id",$id)->find();
        $admin=model("Admin")->where("username",$order["kfsendperson"])->find();
        //获取客户信息
        //$user=model("")->where("username",$order["userid"])->find();
        Vendor("phpExcel.PHPExcel");
        //$xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称
        $fileName = $order["truename"]."接诊单".date('_YmdHis');//or $xlsTitle 文件名称可根据自己情况设定
        $cellNum = 4;

        $objPHPExcel = new \PHPExcel();
        $sheetcount=0;
        $objPHPExcel->getDefaultStyle()->getFont()->setName('宋体');
        $objPHPExcel->getDefaultStyle()->getFont()->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objPHPExcel->getActiveSheet(0)->getStyle('A1:D1')->getFont()->setBold(true);
        $styleThinBlackBorderOutline = array(
            'borders' => array(
                'allborders' => array( //设置全部边框
                    'style' => \PHPExcel_Style_Border::BORDER_THIN //粗的是thick
                ),
    
            ),
        );
        $objPHPExcel->getActiveSheet(0)->getStyle('A1:D19')->applyFromArray($styleThinBlackBorderOutline);
        
        $objPHPExcel->getActiveSheet(0)->getStyle('A1:D1')->getFont()->setBold(true)->setSize(16); //字体加粗 
        $objPHPExcel->getActiveSheet(0)->setCellValue('A1', "接诊详情表");
        $objPHPExcel->getActiveSheet(0)->mergeCells('A1:D1');
        $objPHPExcel->getActiveSheet(0)->getStyle('A1:D1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //$objPHPExcel->getActiveSheet(0)->getStyle('A1:D1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);
        $objPHPExcel->getActiveSheet(0)->setCellValue('A2', "预约就诊时间");
        $objPHPExcel->getActiveSheet(0)->getStyle('A2')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet(0)->setCellValue('B2', $order["appointtime"]);
        $objPHPExcel->getActiveSheet(0)->setCellValue('C2', "预约就诊医院");
        $objPHPExcel->getActiveSheet(0)->getStyle('C2')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet(0)->setCellValue('D2', $order["appointhospital"]);
        $objPHPExcel->getActiveSheet(0)->setCellValue('A3', "预约就诊医生");
        $objPHPExcel->getActiveSheet(0)->getStyle('A3')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet(0)->setCellValue('B3', $order["appointdoctor"]);
        $objPHPExcel->getActiveSheet(0)->setCellValue('C3', "预约就诊科室");
        $objPHPExcel->getActiveSheet(0)->getStyle('C3')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet(0)->setCellValue('D3', $order["appointsection"]);
        $objPHPExcel->getActiveSheet(0)->getStyle('D3:D3')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);
        $objPHPExcel->getActiveSheet(0)->setCellValue('A4', "预约就诊总时长");
        $objPHPExcel->getActiveSheet(0)->getStyle('A4')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet(0)->setCellValue('B4', $order["diagnosistotaltime"]);
        $objPHPExcel->getActiveSheet(0)->setCellValue('C4', "总就诊时长");
        $objPHPExcel->getActiveSheet(0)->getStyle('C4')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet(0)->setCellValue('D4', $order["diagnosistime"]);
        $objPHPExcel->getActiveSheet(0)->setCellValue('A5', "检查");
        $objPHPExcel->getActiveSheet(0)->getStyle('A5')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet(0)->mergeCells('A5:A7');
        $objPHPExcel->getActiveSheet(0)->setCellValue('B5', "检查项目");
        $objPHPExcel->getActiveSheet(0)->getStyle('B5')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet(0)->setCellValue('C5', "检查项目");
        $objPHPExcel->getActiveSheet(0)->mergeCells('C5:D5');
        $objPHPExcel->getActiveSheet(0)->setCellValue('B6', "取结果方式");
        $objPHPExcel->getActiveSheet(0)->getStyle('B6')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet(0)->setCellValue('C6', ($order["appointdoctortype"]==0)?"接诊人员代取":"客户自取");
        $objPHPExcel->getActiveSheet(0)->mergeCells('C6:D6');
        $objPHPExcel->getActiveSheet(0)->setCellValue('B7', "备注");
        $objPHPExcel->getActiveSheet(0)->getStyle('B7')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet(0)->setCellValue('C7', $order["diagnosischeckremark"]);
        $objPHPExcel->getActiveSheet(0)->mergeCells('C7:D7');

        $objPHPExcel->getActiveSheet(0)->setCellValue('A8', "看诊");
        $objPHPExcel->getActiveSheet(0)->getStyle('A8')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet(0)->mergeCells('A8:A9');
        $objPHPExcel->getActiveSheet(0)->setCellValue('B8', "疾病诊断");
        $objPHPExcel->getActiveSheet(0)->getStyle('A8')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet(0)->setCellValue('C8', $order["diagnosisdisease"]);
        $objPHPExcel->getActiveSheet(0)->mergeCells('C8:D8');
        $objPHPExcel->getActiveSheet(0)->setCellValue('B9', "医生建议");
        $objPHPExcel->getActiveSheet(0)->getStyle('B9')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet(0)->setCellValue('C9', $order["diagnosisadvice"]);
        $objPHPExcel->getActiveSheet(0)->mergeCells('C9:D9');
        
        $objPHPExcel->getActiveSheet(0)->setCellValue('A10', "取药");
        $objPHPExcel->getActiveSheet(0)->getStyle('A10')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet(0)->mergeCells('A10:D12');
        $objPHPExcel->getActiveSheet(0)->setCellValue('B10', "药物名称及服用方法");
        $objPHPExcel->getActiveSheet(0)->getStyle('B10')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet(0)->setCellValue('C10', $order["diagnosismedicine"]);
        $objPHPExcel->getActiveSheet(0)->mergeCells('C10:D10');
        $objPHPExcel->getActiveSheet(0)->setCellValue('B11', "取药方式");
        $objPHPExcel->getActiveSheet(0)->getStyle('B11')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet(0)->setCellValue('C11', ($order["diagnosismedicineresulttype"]==0)?"接诊人员代取":"客户自取");
        $objPHPExcel->getActiveSheet(0)->mergeCells('C11:D11');
        $objPHPExcel->getActiveSheet(0)->setCellValue('B12', "注意事项");
        $objPHPExcel->getActiveSheet(0)->getStyle('B12')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet(0)->setCellValue('C12', $order["diagnosismedicineremark"]);
        $objPHPExcel->getActiveSheet(0)->mergeCells('C12:D12');

        
        $objPHPExcel->getActiveSheet(0)->setCellValue('A13', "住院");
        $objPHPExcel->getActiveSheet(0)->getStyle('A13')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet(0)->mergeCells('A13:A14');
        $objPHPExcel->getActiveSheet(0)->setCellValue('B13', "是否住院");
        $objPHPExcel->getActiveSheet(0)->getStyle('A13')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet(0)->setCellValue('C13', empty($order["diagnosishospitalnum"])?"否":"是");
        $objPHPExcel->getActiveSheet(0)->mergeCells('C13:D13');
        $objPHPExcel->getActiveSheet(0)->setCellValue('B14', "住院号");
        $objPHPExcel->getActiveSheet(0)->getStyle('B14')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet(0)->setCellValue('C14', $order["diagnosishospitalnum"]);
        $objPHPExcel->getActiveSheet(0)->mergeCells('C14:D14');
        
        $objPHPExcel->getActiveSheet(0)->setCellValue('A15', "复查");
        $objPHPExcel->getActiveSheet(0)->getStyle('A15')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet(0)->mergeCells('C15:D15');
        $objPHPExcel->getActiveSheet(0)->setCellValue('B15', "项目/时间");
        $objPHPExcel->getActiveSheet(0)->getStyle('B15')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet(0)->setCellValue('C15', $order["diagnosisreview"]);
        $objPHPExcel->getActiveSheet(0)->mergeCells('C15:D15');



        $objPHPExcel->getActiveSheet(0)->setCellValue('A16', "备注");
        $objPHPExcel->getActiveSheet(0)->getStyle('A16')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet(0)->mergeCells('B16:D16');

        
        $objPHPExcel->getActiveSheet(0)->setCellValue('A17', "对医生评价");
        $objPHPExcel->getActiveSheet(0)->getStyle('A17')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet(0)->mergeCells('A17:A18');
        $objPHPExcel->getActiveSheet(0)->setCellValue('B17', "客户/家属");
        $objPHPExcel->getActiveSheet(0)->getStyle('B17')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet(0)->setCellValue('C17', $order["diagnosiscustomappra"]);
        $objPHPExcel->getActiveSheet(0)->mergeCells('C17:D17');
        $objPHPExcel->getActiveSheet(0)->setCellValue('B18', "接诊人员");
        $objPHPExcel->getActiveSheet(0)->getStyle('B18')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet(0)->setCellValue('C18', $order["diagnosispersonappra"]);
        $objPHPExcel->getActiveSheet(0)->mergeCells('C18:D18');

        $objPHPExcel->getActiveSheet(0)->setCellValue('A19', "接诊人员");
        $objPHPExcel->getActiveSheet(0)->getStyle('A19')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet(0)->setCellValue('B19', $admin["nickname"]);
        
        $objPHPExcel->getActiveSheet(0)->setCellValue('C19', "接诊日期");
        $objPHPExcel->getActiveSheet(0)->getStyle('C19')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet(0)->setCellValue('D19', $order["kfsendperson"]);

        ob_end_clean();//清除缓冲区,避免乱码
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename='.$fileName.'.xls');
        header('Cache-Control: max-age=0');

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        // $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        // $objWriter->save($fileName.".xls");
        // downfile("./".$fileName.".xls",$fileName.".xls");
    }

    //客服受理
    public function kfcheck(){
        $params = $this->request->post("order/a");
        $customserver = $this->request->post("customserver/a");
        $username=cookie::get("username");
        //新建订单
        if(empty($params["id"])){
            //['orderid'=>$orderid,'userid' => $username, 'type' => $type, 'amount' => $amount, 'createtime' => $time, 'updatetime' => $time, 'phone' => $phone, 'truename' => $nickname,'remark'=>$remark]
            $orderid=md5($username.time().mt_rand(10000,99999));
            $param["orderid"]=trim($orderid);
            //$param["userid"]=trim($user["username"]);
            $param["type"]=2;
            $param["createtime"]=time();
            $param["updatetime"]=time();
            $param["phone"]=$params["phone"];
            $param["truename"]=$params["truename"];
            $param["idcard"]=$params["idcard"];
            $param["cardnum"]=$params["cardnum"];
            $param["region"]=$params["region"];
            $param["paytype"]=$params["paytype"];
            $param["paytime"]=$params["paytime"];
            $param["amount"]=$params["amount"];
            $param["gender"]=$params["gender"];
            $param["age"]=$params["age"];
            $param["customdetail"]=trim($params["customdetail"]);
            $param["expecthospitaltype"]=$params["expecthospitaltype"];
            $param["expectsectiontype"]=$params["expectsectiontype"];
            $param["expectdoctortype"]=$params["expectdoctortype"];
            $param["expecttimetype"]=$params["expecttimetype"];
            $param["expecthospital"]=trim($params["expecthospital"]);
            $param["expectsection"]=trim($params["expectsection"]);
            $param["expectdoctor"]=trim($params["expectdoctor"]);
            $param["expecttime"]=trim($params["expecttime"]);
            $param["appointhospitaltype"]=$params["expecthospitaltype"];
            $param["appointdoctortype"]=$params["expectdoctortype"];
            $param["appointsectiontype"]=$params["expectsectiontype"];
            $param["appointtimetype"]=$params["expecttimetype"];
            $param["appointhospital"]=$params["expecthospital"];
            $param["appointsection"]=$params["expectsection"];
            $param["appointdoctor"]=$params["expectdoctor"];
            $param["appointtime"]=$params["expecttime"];
            $customserver=implode(",",$customserver);
            $param["customserver"]=$customserver;
            $param["customremark"]=trim($params["customremark"]);
            $param["kfdoperson"]=$username;
            $param["kfsendtime"]=time();
            $param["kfsendperson"]=$params["kfsendperson"];
            $param["kfaddtime"]=time();
            $param["kfupdatetime"]=time();
            $rs=$this->model->save($param);
            if ( $rs !== false){
                $this->success();
            }else{
                $this->error($this->model->getError());
            }
        }else{
            $order=$this->model->where("id",$params["id"])->find();
            if(!empty($order["overtime"])){
                $this->error("此订单已经结束,不能进行编辑");
            }
            if(($username == $order["kfdoperson"]&&!empty($order["kfdoperson"])||empty($order["kfdoperson"]))){
                $param["updatetime"]=time();
                $param["phone"]=$params["phone"];
                $param["truename"]=$params["truename"];
                $param["idcard"]=$params["idcard"];
                $param["cardnum"]=$params["cardnum"];
                $param["region"]=$params["region"];
                $param["paytype"]=$params["paytype"];
                $param["paytime"]=$params["paytime"];
                $param["amount"]=$params["amount"];
                $param["gender"]=$params["gender"];
                $param["age"]=$params["age"];
                $param["customdetail"]=trim($params["customdetail"]);
                $param["expecthospitaltype"]=$params["expecthospitaltype"];
                $param["expectsectiontype"]=$params["expectsectiontype"];
                $param["expectdoctortype"]=$params["expectdoctortype"];
                $param["expecttimetype"]=$params["expecttimetype"];
                $param["expecthospital"]=trim($params["expecthospital"]);
                $param["expectsection"]=trim($params["expectsection"]);
                $param["expectdoctor"]=trim($params["expectdoctor"]);
                $param["expecttime"]=trim($params["expecttime"]);
                $param["appointhospitaltype"]=$params["expecthospitaltype"];
                $param["appointdoctortype"]=$params["expectdoctortype"];
                $param["appointsectiontype"]=$params["expectsectiontype"];
                $param["appointtimetype"]=$params["expecttimetype"];
                $param["appointhospital"]=$params["expecthospital"];
                $param["appointsection"]=$params["expectsection"];
                $param["appointdoctor"]=$params["expectdoctor"];
                $param["appointtime"]=$params["expecttime"];
                $customserver=implode(",",$customserver);
                $param["customserver"]=$customserver;
                $param["customremark"]=trim($params["customremark"]);
                $param["kfsendtime"]=time();
                $param["kfsendperson"]=$params["kfsendperson"];
                $param["kfupdatetime"]=time();
                $rs=$this->model->where("id",$params["id"])->update($param);
                if ( $rs !== false){
                    $this->success("保存成功");
                }else{
                    $this->error($this->model->getError());
                }
            }else{
                $this->error("您没有权限操作这个订单");
            }
        }
    }

    //诊前确认
    public function diagnosisconfirm(){
        $params = $this->request->post("order/a");
        $order=$this->model->where("id",$params["id"])->find();
        $username=cookie::get("username");
        if(!empty($order["overtime"])){
            $this->error("此订单已经结束,不能进行编辑");
        }
        if( $username == $order["kfsendperson"]){
            $param["appointhospitaltype"]=$params["appointhospitaltype"];
            $param["appointdoctortype"]=$params["appointdoctortype"];
            $param["appointsectiontype"]=$params["appointsectiontype"];
            $param["appointtimetype"]=$params["appointtimetype"];
            $param["appointhospital"]=$params["appointhospital"];
            $param["appointsection"]=$params["appointsection"];
            $param["appointdoctor"]=$params["appointdoctor"];
            $param["appointtime"]=$params["appointtime"];
            $param["appointremark"]=$params["appointremark"];
            $param["appointsendtime"]=time();
            //$param["appointsendperson"]=$params["appointsendperson"];
            $rs=$this->model->where("id",$params["id"])->update($param);
            if ( $rs !== false){
                $this->success("保存成功");
            }else{
                $this->error($this->model->getError());
            }
        }else{
            $this->error("您没有权限操作这个订单");
        }
        
    }

    //病人诊断
    public function diagnosis(){
        $params = $this->request->post("order/a");
        $order=$this->model->where("id",$params["id"])->find();
        $username=cookie::get("username");
        if(!empty($order["overtime"])){
            $this->error("此订单已经结束,不能进行编辑");
        }
        if( $username == $order["kfsendperson"]){
            $param["diagnosiscustomtime"]=$params["diagnosiscustomtime"];
            $param["diagnosistotaltime"]=$params["diagnosistotaltime"];
            $param["diagnosistime"]=$params["diagnosistime"];
            $param["diagnosisdisease"]=$params["diagnosisdisease"];
            $param["diagnosisadvice"]=$params["diagnosisadvice"];
            $param["diagnosischeckname1"]=$params["diagnosischeckname1"];
            $param["diagnosischeckgreentype1"]=$params["diagnosischeckgreentype1"];
            $param["diagnosischeckgreendoctor1"]=$params["diagnosischeckgreendoctor1"];
            $param["diagnosischeckname2"]=$params["diagnosischeckname2"];
            $param["diagnosischeckgreentype2"]=$params["diagnosischeckgreentype2"];
            $param["diagnosischeckgreendoctor2"]=$params["diagnosischeckgreendoctor2"];
            $param["diagnosischeckname3"]=$params["diagnosischeckname3"];
            $param["diagnosischeckgreentype3"]=$params["diagnosischeckgreentype3"];
            $param["diagnosischeckgreendoctor3"]=$params["diagnosischeckgreendoctor3"];
            $param["diagnosischeckname4"]=$params["diagnosischeckname4"];
            $param["diagnosischeckgreentype4"]=$params["diagnosischeckgreentype4"];
            $param["diagnosischeckgreendoctor4"]=$params["diagnosischeckgreendoctor4"];
            $param["diagnosischeckname5"]=$params["diagnosischeckname5"];
            $param["diagnosischeckgreentype5"]=$params["diagnosischeckgreentype5"];
            $param["diagnosischeckgreendoctor5"]=$params["diagnosischeckgreendoctor5"];
            $param["diagnosischeckresulttype"]=$params["diagnosischeckresulttype"];
            $param["diagnosisreport"]=$params["diagnosisreport"];
            $param["diagnosischeckremark"]=$params["diagnosischeckremark"];
            $param["diagnosismedicine"]=$params["diagnosismedicine"];
            $param["diagnosismedicineresulttype"]=$params["diagnosismedicineresulttype"];
            $param["diagnosismedicineremark"]=$params["diagnosismedicineremark"];
            $param["diagnosishospitalnum"]=$params["diagnosishospitalnum"];
            $param["diagnosisreview"]=$params["diagnosisreview"];
            $param["diagnosiscustomappra"]=$params["diagnosiscustomappra"];
            $param["diagnosispersonappra"]=$params["diagnosispersonappra"];
            $param["diagnosisisreview"]=$params["diagnosisisreview"];
            if($params["diagnosisisreview"]==0){
                $param["overtime"]=time();
            }
            $param["diagnosisover"]=$params["diagnosisover"];
            $param["diagnosisaddtime"]=time();
            $param["diagnosisupdatetime"]=time();
            $rs=$this->model->where("id",$params["id"])->update($param);
            if ( $rs !== false){
                $this->success("保存成功");
            }else{
                $this->error($this->model->getError());
            }
        }else{
            $this->error("您没有权限操作这个订单");
        }
    }

    //客户回访
    public  function visit(){
        $params = $this->request->post("order/a");
        $order=$this->model->where("id",$params["id"])->find();
        $username=cookie::get("username");
        if(!empty($order["overtime"])){
            $this->error("此订单已经结束,不能进行编辑");
        }
        if( $username == $order["kfdoperson"]){
            $param["visitdoctorattitude"]=$params["visitdoctorattitude"];
            $param["visitdoctorskill"]=$params["visitdoctorskill"];
            $param["visitpersonattitude"]=$params["visitpersonattitude"];
            $param["visitpersonquality"]=$params["visitpersonquality"];
            $param["visitcustomadvice"]=$params["visitcustomadvice"];
            $param["visitaddtime"]=time();
            $param["overtime"]=time();
            $rs=$this->model->where("id",$params["id"])->update($param);
            if ( $rs !== false){
                $this->success("此用户的本次治疗已经结束,请到这个用户的历史订单中查看");
            }else{
                $this->error($this->model->getError());
            }
        }else{
            $this->error("您没有权限操作这个订单");
        }
    }

    //获取用户订单信息
    public function getUnreadOrderList(){
        header('Access-Control-Allow-Origin:*');  
        header('Access-Control-Allow-Methods:GET, POST, OPTIONS');
        $callback=$this->request->get("callback");
        $rs=$this->model->where("customdetail is null")->select();
        $ret["new"]=count($rs);
        $ret["newslist"]=$rs;
        echo $callback, '(', json_encode($ret), ')';
    }


    //订单查询
    public function query($ids){
        $order = $this->model->where(["id"=>$ids])->order("createtime desc")->find();
        $userid=$order["userid"];
        $user=\app\admin\model\User::where("username",$userid)->find();
        $truename=$user["nickname"];
        $doperson=cookie::get("username");
        //查询这个人的记录
        $orderid=$order["orderid"];
        $createtime=date('Y-m-d H:i:s', $order["createtime"]);
        $fromuserid=$order["doperson"];
        $procedure=model("ProductOrderProcedure")->where(["orderid"=>$orderid,"fromuserid"=>$fromuserid])->order("addtime desc")->find();
        $record=model("UserRecords")->where(["orderid"=>$orderid,"username"=>$userid])->find();
        $order["dotime"]=Date("Y-m-d H:i:s",$order["dotime"]);
        $order["ispay"]=empty($order["paytime"])?0:1;
        $record["dotime"]=Date("Y-m-d H:i:s",$record["dotime"]);
        $this->view->assign("order",$order);
        $this->view->assign("user",$user);
        $this->view->assign("truename",$truename);
        $this->view->assign('firstList', build_select('procedure[touserid]', \app\admin\model\Admin::column('username,nickname'), empty($procedure)?0:$procedure["touserid"], ['class' => 'form-control tds']));
        $this->view->assign("record",$record);
        $this->view->assign('secondList', build_select('procedure[touserid]', \app\admin\model\Admin::column('username,nickname'), 0, ['class' => 'form-control tds']));
        $this->view->assign("isexists",empty($order)?0:1);
        return $this->view->fetch();
    }

}

