<?php

namespace Home\Controller;

use Think\Controller;

/**
 * 单词控制器
 *
 * @author         gm
 * @since          1.0
 */
class WordController extends CheckController {
    /**
     *  importExcel
     *  用excel进行批量导入
     *  @param  void
     *  @return  json 返回单词是否导入的基本信息
     */
    public function importMedicine() {
        Vendor("PHPExcel");
        $filename = I("filename");
        $arr_rs = $this->importMedicineExcel(C("CONST_UPLOADS_EXCEL").$filename); //获取导入的结果集
        $iserr = 0;
        $errmsg = "";
        $i = 0;
        foreach ($arr_rs as $row => $v) {
            if ($v["iserr"]) {
                $iserr = 1;
                $errmsg.= $v["errmsg"] . '<br>';
            } else {
                $i++;
            }
        }
        $data["iserr"] = $iserr;
        $data["errmsg"] = $errmsg;
        $data["sucnum"] = $i;
        $this->ajaxReturn($data);
    }

    //导入病人的药物数据
    public function importPatient(){
      Vendor("PHPExcel");
      $filename = I("filename");
      //导入的时候需要生成一个批次的ID

      $arr_rs = $this->importPatientMedicineExcel(C("CONST_UPLOADS_EXCEL").$filename); //获取导入的结果集
      $iserr = 0;
      $errmsg = "";
      $i = 0;
      foreach ($arr_rs["data"] as $row => $v) {
          if ($v["iserr"]) {
              $iserr = 1;
              $errmsg.= $v["errmsg"] . '<br>';
          } else {
              $i++;
          }
      }
      $data["iserr"] = $iserr;
      $data["errmsg"] = $errmsg;
      $data["batchids"] = $arr_rs["batchids"];
      $data["sucnum"] = $i;
      $this->ajaxReturn($data);
    }


    //读完病人的看病的药物的信息
    protected function importPatientMedicineExcel($filename){
      set_time_limit(0);
      $arr_excel = readExcel($filename,1,0,true);
      $patientmedicine=M("patient_medicine");
      $medicine=M("medicine");
      $arrcolumn = array('A', 'B');
      $i = 0;
      $iserr = false;
      $batchids=array();
      foreach ($arr_excel as $sheet => $value) {
          $batchid=md5(Date("Y-m-d H:i:s").rand(1000,9999));
          array_push($batchids,$batchid);
          foreach($value as $row => $v){
            if($row<3){
              if($row==1){
                $truename=$v["Column"]["B"];
              }
              continue;
            }
            /* 必填列判断 */
            foreach ($arrcolumn as $cv) {
                $v["iserr"] = false;
                if (empty($v["Column"][$cv])) {
                    $v["iserr"] = true;
                    $v["errmsg"] = '第'.$sheet.'个Sheet的第' . $row . '行' . $cv . '列为空';
                    $arr_excel[$sheet][$row] = $v;
                    $iserr = true;
                    break;
                }
            }
            if ($v["iserr"]) {
                continue;
            }

            $name=$v["Column"]["A"];
            $num=$v["Column"]["C"];
            $price=$v["Column"]["B"];

            //查询看这个药物有没有
            $data["patient_batchid"]=$batchid;
            $data["patient_name"]=trim($truename);
            $data["medicine_name"]=str_replace('"', '\'', trim($name));
            //根据名称查询药物的ID
            $rs=$medicine->where("medicine_name='%s' and isdel=1",trim($name))->find();
            $data["medicine_price"]=$price;
            $data["medicine_num"]=$num;
            $data["sheetnum"]=$sheet;
            $data["addtime"]=Date("Y-m-d H:i:s");
            $data["medicine_id"]=0;
            if(!empty($rs)){
              $data["medicine_id"]=$rs["id"];
            }
            $patientmedicine->add($data);
          }
      }
      $result["data"]=$arr_excel;
      $result["batchids"]=$batchids;
      return $result;
    }

    /**
     *  importWordExcel
     *  读取Excel文件并将文件中的单词插入数据库
     * @param   filename表示文件存放路径
     * @return  array
     */
     protected function importMedicineExcel($filename) {
        set_time_limit(0);
        $arr_excel = readExcel($filename,1);
        $medicine=M("medicine");
        $arrcolumn = array('B');
        $i = 0;
        $iserr = false;
        foreach ($arr_excel[0] as $row => $v) {
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

            $price=$v["Column"]["A"];
            $name=$v["Column"]["B"];

            //查询看这个药物有没有
            $rs=$medicine->where("medicine_name='%s' and isdel=1",trim($name))->find();
            if(empty($rs)){
              $data["medicine_code"]="";
              $data["medicine_rate"]=empty($price)?0:$price;
              $data["medicine_name"]=str_replace('"', '\'', trim($name));
              $data["addtime"]=Date("Y-m-d H:i:s");
              $medicine->add($data);
            }else{
              // $data["price"]=empty($price)?0:$price;
              // $data["addtime"]=Date("Y-m-d H:i:s");
              // $medicine->where("id=%d",$rs["id"])->save($data);
              $v["iserr"] = true;
              $v["errmsg"] = '第' . $row . '行药品重复';
              $arr_excel[$row] = $v;
              $iserr = true;
            }
        }
        return $arr_excel;
    }

    /**
     * getlist
     * 获取所有的药物
     *
     * @access   public
     * @param    void
     * @return 	json 返回每次的属性
     */
    public function getList() {
        $medicine=M("medicine");
        $pagenum=I("pagenum/d",0);
        $name=I("name/s",0);
        $pagesize=200;
        if(!empty($name)){
          $sql="select * from md_medicine where medicine_name like '%".$name."%' and isdel=1";
        }else{
          $sql="select * from md_medicine where  isdel=1";
        }
        $rs=$medicine->query($sql);
        $count=count($rs);
        $count=ceil($count/200);
        if(!empty($name)){
          $wsql="select * from md_medicine where medicine_name like '%".$name."%' and isdel=1 limit ".$pagenum*$pagesize.",".$pagesize;
        }else{
          $wsql="select * from md_medicine where isdel=1 limit ".$pagenum*$pagesize.",".$pagesize;
        }
        $rs=$medicine->query($wsql);
        $result["count"]=$count;
        $result["cur"]=$pagenum+1;
        $result["list"]=$rs;
        $this->ajaxReturn($result);
    }

    /**
     *  edit
     *  小节正文编辑页面
     * @param  void
     * @return
     */
    public function edit() {
        $id = I("id/d",0);
        $medicine = M("medicine");
        $this->assign("id", $id);
        if (($id) != 0) {
            $rs = $medicine->where("id=%d", $id)->find();
            if (!empty($rs)) {
                $this->assign("medicine_code", $rs['medicine_code']);
                $this->assign("medicine_name", $rs['medicine_name']);
                $this->assign("medicine_rate", $rs['medicine_rate']);
            }
        }
        $this->display();
    }


    /**
     * del删除药物
     *
     * @param void
     * @return json 表示单词是否更新成功
     */
    public function updel() {
        $id=I("id/d",0);
        $medicine=M("medicine");
        $medicine->where("id=%d",$id)->setField("isdel",0);
    }

    /**
     * edit编辑药物
     *
     * @param void
     * @return json 返回单词是否添加成功 isadd表示是否添加 msg表示错误信息
     */
    public function medicineedit() {
      $data = stripslashes(I("data"));
      $data = rtrim($data, '"');
      $data = ltrim($data, '"');
      $data = str_replace('&quot;', '"', $data);
      $data = json_decode($data);
      $id = I('id/d',0);
      $medicine=M("medicine");
      $code=$data->code;
      $name=$data->name;
      //判断是否有重名的药物

        if(empty($id)){
          $rs=$medicine->where("medicine_name='%s'",$name)->select();
          if(empty($rs)){
              $datas["medicine_code"]=$data->code;
              $datas["medicine_rate"]=empty($data->price)?0:$data->price;
              $datas["medicine_name"]=str_replace('"', '\'', trim($data->name));
              $datas["addtime"]=Date("Y-m-d H:i:s");
              $medicine->add($datas);
              $res["suc"]=1;
              $res["msg"]="";
            }else{
              $res["suc"]=0;
              $res["msg"]="存在同名药物";
            }
        }else{
          $datas["medicine_code"]=$data->code;
          $datas["medicine_rate"]=empty($data->price)?0:$data->price;
          $datas["medicine_name"]=str_replace('"', '\'', trim($data->name));
          $datas["addtime"]=Date("Y-m-d H:i:s");
          $medicine->where("id=%d",$id)->save($datas);
          $res["suc"]=1;
          $res["msg"]="";
      }
      $this->ajaxReturn($res);

    }

    //查询这个batchid病人的药物
    public function getpatientlist(){
      set_time_limit(0);
      $data = stripslashes($_REQUEST["batchid"]);
      $data = rtrim($data, '"');
      $data = ltrim($data, '"');
      $data = str_replace('&quot;', '"', $data);
      $res = json_decode($data,true);
      $arr=array();
      foreach($res as $key=>$value){
        array_push($arr,$value["id"]);
      }
      $patient_medicine=M("patient_medicine");
      $sql="select distinct md_patient_medicine.*,s.medicine_name as name,s.medicine_rate from md_patient_medicine left join (select * from md_medicine where isdel=1) as s on md_patient_medicine.medicine_name=s.medicine_name where patient_batchid in (".implode(',', array_map('change_to_quotes', $arr )).") order by md_patient_medicine.sheetnum,md_patient_medicine.patient_batchid,md_patient_medicine.id";
      $rs=$patient_medicine->query($sql,$batchid);
      $ret=array();
      foreach($rs as $key=>$value){
        $ret[$value["patient_batchid"]]["data"][]=$value;
        $ret[$value["patient_batchid"]]["name"]=$value["patient_name"];
      }
      $this->ajaxReturn($ret);
    }

    //查找药物
    public function findMedicine(){
      $keyword=I("keyword/s","");
      if(empty($keyword)){
        $rs=array();
        $this->ajaxReturn($rs);
      }
      $sql="select * from md_medicine where medicine_name like '%".trim($keyword)."%' and isdel=1";
      $rs=M()->query($sql);
      $this->ajaxReturn($rs);
    }

    //导出EXCEL
    public function exportExcel(){
        set_time_limit(0);
        $data = stripslashes($_REQUEST["data"]);
        $data = rtrim($data, '"');
        $data = ltrim($data, '"');
        $data = str_replace('&quot;', '"', $data);

        $res = json_decode($data,true);
        //这里对二位数组进行排序
        //向EXCEL中写入数据
        $prices=0.0;
        $parentPrices=0.0;
        $pricesarr=array();
        $arr=array();
        foreach($res as $key=>$value){
          //首先答应的是药品
          $patientmedicinename=$value["patient_medicine_name"];
          $medicine_name=$value["medicine_name"];
          $ind=$value["ind"];
          $id=$value["id"];
          $patient_name=$value["patient_name"];
          $patient_batchid=$value["patient_batchid"];
          $patientmedicineprice=round((float)$value["patient_medicine_price"],2);
          $medicinenum=(float)$value["patient_medicine_num"];

          $medicinerate=(float)($value["patient_medicine_rate"]=='')?1:round((float)$value["patient_medicine_rate"],2);

          //$prices=$prices+$patientmedicineprice;
          $pricesarr[$patient_batchid]["prices"]=(isset($pricesarr[$patient_batchid]["prices"])?$pricesarr[$patient_batchid]["prices"]:(0.0))+$patientmedicineprice;
          if(false !== strpos($patientmedicinename, "床位费")){
            $price=round((float)(($patientmedicineprice/$medicinenum)-25)*$medicinenum,2)>0?(round((float)(($patientmedicineprice/$medicinenum)-25)*$medicinenum,2)):0;
          }else{
            $price=round($patientmedicineprice*((float)$medicinerate),2);
          }
          $pricesarr[$patient_batchid]["parentPrices"]=(isset($pricesarr[$patient_batchid]["parentPrices"])?$pricesarr[$patient_batchid]["parentPrices"]:(0.0))+$price;

          //$parentPrices=$parentPrices+$price;
          if($value["patient_medicine_rate"]==="0"){
            continue;
          }
          $temp=array();
          $temp[0]=$patientmedicinename;
          $temp[1]=$patientmedicineprice;
          $temp[2]=(float)$medicinerate;
          $temp[3]=$price;
          $temp[4]=(empty($medicine_name))?1:0;
          $temp[5]=$patient_name;
          $temp[6]=$id;
          $arr[$patient_batchid]["data"][]=$temp;
          $arr[$patient_batchid]["name"]=$patient_name;
          $arr[$patient_batchid]["ind"]=$ind;
        }
        $ind=0;
        $count=count($arr)-1;
        $orderarr=array();
        //$rates=round($parentPrices/$prices,4)*100;
        foreach($arr as $key=>$value){
          $arr[$key]["parentPrices"]=$pricesarr[$key]["parentPrices"];
          $arr[$key]["prices"]=round($pricesarr[$key]["prices"],2);
          $arr[$key]["rates"]=round($pricesarr[$key]["parentPrices"]/$pricesarr[$key]["prices"],4)*100;
          $orderarr[((int)$value["ind"])]["data"]=$arr[$key];
          $orderarr[((int)$value["ind"])]["batchid"]=$key;
        }

        $this->exportPatientExcel("病人结果清单",$orderarr);
    }

    //下载模板
    public  function downloadMedicineExcelDemo(){
       //文件下载
        $filename="./uploads/demo/medicine/medicine.xls";
        downfile($filename,"medicine.xls");
    }

    //下载模板
    public  function downloadPatientExcelDemo(){
       //文件下载
        $filename="./uploads/demo/patient/patient.xls";
        downfile($filename,"patient.xls");
    }


    private function exportPatientExcel($expTitle,$expTableData){
        $count=count($expTableData)-1;
        Vendor("PHPExcel");
        $xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称
        $fileName = $_SESSION['adminuser'].date('_YmdHis');//or $xlsTitle 文件名称可根据自己情况设定
        $cellNum = 4;

        $objPHPExcel = new \PHPExcel();
        $sheetcount=0;
        for($key=0;$key<=$count;$key++){
          //$objWriter = new \PHPEXCEL_Writer_Excel2007($objPHPExcel);
          $cellName = array('A','B','C','D');
          $dataNum = count($expTableData[$key]["data"]["data"]);
          $expTableData[$key]["data"]["data"]=sortt($expTableData[$key]["data"]["data"],6);
          $objPHPExcel->getDefaultStyle()->getFont()->setName('黑体');
          $objPHPExcel->getDefaultStyle()->getFont()->setSize(16);
          if($sheetcount==0){
            $objPHPExcel->getActiveSheet(0)->getColumnDimension('A')->setWidth(45);
            $objPHPExcel->getActiveSheet(0)->getColumnDimension('B')->setWidth(25);
            $objPHPExcel->getActiveSheet(0)->getColumnDimension('C')->setWidth(25);
            $objPHPExcel->getActiveSheet(0)->getColumnDimension('D')->setWidth(25);
            $objPHPExcel->getActiveSheet(0)->getStyle('A')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet(0)->getStyle('B')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet(0)->getStyle('C')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet(0)->getStyle('D')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet(0)->getStyle('E')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet(0)->getStyle('A')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            //左对齐
            $objPHPExcel->getActiveSheet(0)->getStyle('B')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            //右对齐
            $objPHPExcel->getActiveSheet(0)->getStyle('C')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objPHPExcel->getActiveSheet(0)->getStyle('D')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objPHPExcel->getActiveSheet(0)->getStyle('A1:D3')->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet(0)->getRowDimension('1')->setRowHeight(40);
            $objPHPExcel->getActiveSheet(0)->setCellValue('A1', "姓名:".$expTableData[$key]["data"]["name"]);
            $objPHPExcel->getActiveSheet(0)->mergeCells('A1:B1');


            //设置日期
            // $objPHPExcel->getActiveSheet(0)->setCellValue('A2', "日期");
            // $objPHPExcel->getActiveSheet(0)->setCellValue('B2',Date("Y-m-d"));

            //设置第二行
            $objPHPExcel->getActiveSheet(0)->setCellValue('C1', "总金额");
            $objPHPExcel->getActiveSheet(0)->setCellValue('D1',$expTableData[$key]["data"]["prices"]);

            $objPHPExcel->getActiveSheet(0)->getRowDimension('2')->setRowHeight(40);
            $objPHPExcel->getActiveSheet(0)->setCellValue('A2',"自费扣除比例（自费金额/总金额）");
            $objPHPExcel->getActiveSheet(0)->setCellValue('B2',$expTableData[$key]["data"]["rates"]."%");
            $objPHPExcel->getActiveSheet(0)->setCellValue('C2',"票据");

            $objPHPExcel->getActiveSheet(0)->getRowDimension('3')->setRowHeight(40);
            $objPHPExcel->getActiveSheet(0)->setCellValue('A3',"药品/诊疗项目名称");
            $objPHPExcel->getActiveSheet(0)->setCellValue('B3',"金额");
            $objPHPExcel->getActiveSheet(0)->setCellValue('C3',"自费比例");
            $objPHPExcel->getActiveSheet(0)->setCellValue('D3',"扣除金额");
            $objPHPExcel->getActiveSheet(0)->setCellValue('E3',"是否匹配");


              // Miscellaneous glyphs, UTF-8
            for($i=0;$i<$dataNum;$i++){
              $objPHPExcel->getActiveSheet(0)->getRowDimension($i+4)->setRowHeight(40);
              for($j=0;$j<($cellNum);$j++){
                $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i+4), $expTableData[$key]["data"]["data"][$i][$j]);
              }
              //如果这一行没有匹配就标红色
              if($expTableData[$i][$cellNum]==1){
                //$objPHPExcel->getActiveSheet(0)->getStyle( 'A'.($i+5).':D'.($i+5))->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
                $objPHPExcel->getActiveSheet(0)->setCellValue('E'.($i+4),"A");
              }else{
                //$objPHPExcel->getActiveSheet(0)->setCellValue('E'.($i+5),"1");
              }
            }

            $objPHPExcel->getActiveSheet(0)->getRowDimension(($dataNum+4))->setRowHeight(40);
            $objPHPExcel->getActiveSheet(0)->setCellValue('A'.($dataNum+4),"小计：");
            $objPHPExcel->getActiveSheet(0)->setCellValue('B'.($dataNum+4),$expTableData[$key]["data"]["parentPrices"]);
            $objPHPExcel->getActiveSheet(0)->mergeCells('B'.($dataNum+4).':D'.($dataNum+4));
            $objPHPExcel->getActiveSheet(0)->getStyle('A'.($dataNum+4).':D'.($dataNum+5))->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet(0)->getRowDimension(($dataNum+5))->setRowHeight(40);
            $objPHPExcel->getActiveSheet(0)->setCellValue('A'.($dataNum+5),"审核人：");
            $objPHPExcel->getActiveSheet(0)->setCellValue('B'.($dataNum+5),"复核人：");
            $objPHPExcel->getActiveSheet(0)->setCellValue('D'.($dataNum+5),"日期：".Date("Y-m-d"));
          }else{
            $objPHPExcel->createSheet();
            $objPHPExcel->setactivesheetindex($sheetcount);
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(45);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
            $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(40);
            $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            //左对齐
            $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            //右对齐
            $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objPHPExcel->getActiveSheet()->getStyle('A1:D3')->getFont()->setBold(true);
            //写入多行数据
            $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(40);
            $objPHPExcel->getActiveSheet()->setCellValue('A1', "姓名:".$expTableData[$key]["data"]["name"]);
            $objPHPExcel->getActiveSheet()->mergeCells('A1:B1');

            //设置日期
            // $objPHPExcel->getActiveSheet(0)->setCellValue('A2', "日期");
            // $objPHPExcel->getActiveSheet(0)->setCellValue('B2',Date("Y-m-d"));

            //设置第二行
            $objPHPExcel->getActiveSheet()->setCellValue('c1', "总金额");
            $objPHPExcel->getActiveSheet()->setCellValue('D1',$expTableData[$key]["data"]["prices"]);

            $objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(40);
            $objPHPExcel->getActiveSheet()->setCellValue('A2',"自费扣除比例（自费金额/总金额）");
            $objPHPExcel->getActiveSheet()->setCellValue('B2',$expTableData[$key]["data"]["rates"]."%");
            $objPHPExcel->getActiveSheet()->setCellValue('C2',"票据");

            $objPHPExcel->getActiveSheet()->getRowDimension('3')->setRowHeight(40);
            $objPHPExcel->getActiveSheet()->setCellValue('A3',"药品/诊疗项目名称");
            $objPHPExcel->getActiveSheet()->setCellValue('B3',"金额");
            $objPHPExcel->getActiveSheet()->setCellValue('C3',"自费比例");
            $objPHPExcel->getActiveSheet()->setCellValue('D3',"扣除金额");
            $objPHPExcel->getActiveSheet()->setCellValue('E3',"是否匹配");


              // Miscellaneous glyphs, UTF-8
            for($i=0;$i<$dataNum;$i++){
              $objPHPExcel->getActiveSheet()->getRowDimension(($i+4))->setRowHeight(40);
              for($j=0;$j<($cellNum);$j++){
                $objPHPExcel->getActiveSheet()->setCellValue($cellName[$j].($i+4), $expTableData[$key]["data"]["data"][$i][$j]);
              }
              //如果这一行没有匹配就标红色
              if($expTableData[$i][$cellNum]==1){
                //$objPHPExcel->getActiveSheet(0)->getStyle( 'A'.($i+5).':D'.($i+5))->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
                $objPHPExcel->getActiveSheet()->setCellValue('E'.($i+4),"A");
              }else{
                //$objPHPExcel->getActiveSheet(0)->setCellValue('E'.($i+5),"1");
              }
            }

            $objPHPExcel->getActiveSheet()->getRowDimension(($dataNum+4))->setRowHeight(40);
            $objPHPExcel->getActiveSheet()->setCellValue('A'.($dataNum+4),"小计：");
            $objPHPExcel->getActiveSheet()->setCellValue('B'.($dataNum+4),$expTableData[$key]["data"]["parentPrices"]);
            $objPHPExcel->getActiveSheet()->mergeCells('B'.($dataNum+4).':D'.($dataNum+4));
            $objPHPExcel->getActiveSheet()->getStyle('A'.($dataNum+4).':D'.($dataNum+5))->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getRowDimension(($dataNum+5))->setRowHeight(40);
            $objPHPExcel->getActiveSheet()->setCellValue('A'.($dataNum+5),"审核人：");
            $objPHPExcel->getActiveSheet()->setCellValue('B'.($dataNum+5),"复核人：");
            $objPHPExcel->getActiveSheet()->setCellValue('D'.($dataNum+5),"日期：".Date("Y-m-d"));
          }
          $sheetcount=$sheetcount+1;
        }


        //$objPHPExcel->getActiveSheet(0)->setCellValue('E'.($dataNum+6),);


        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save($fileName.".xls");

        downfile("./".$fileName.".xls",$fileName.".xls");
        //unlink("./".$fileName.".xls");
    }

    public function downloadresult(){
      $fileName=I("filename");
      downfile("./".$fileName.".xls",$fileName.".xls");

    }

    public function getEditPatientMedical(){
      $data = stripslashes($_REQUEST["batchids"]);
      $data = rtrim($data, '"');
      $data = ltrim($data, '"');
      $data = str_replace('&quot;', '\'', $data);
      $res = json_decode($data,true);
      $batchids=array();
      foreach($res as $key=>$value){
        array_push($batchids,$value["id"]);
      }
      $sql="SELECT distinct t.medicine_name FROM md_patient_medicine t left join (select * from md_medicine where isdel=1) s on t.medicine_name=s.medicine_name WHERE s.id is null and t.patient_batchid IN (".implode(',', array_map('change_to_quotes', $batchids)).")";
      $rs=M()->query($sql);
      $this->ajaxReturn($rs);
    }

    public function editPatientMedical(){
      $data = stripslashes($_REQUEST["data"]);
      $data = rtrim($data, '"');
      $data = ltrim($data, '"');
      $data = str_replace('&quot;', '"', $data);
      $data = str_replace('&quot;', '"', $data);
      $res = json_decode($data,true);
      $sql="INSERT INTO md_medicine(medicine_name,medicine_rate) VALUES ";
      $values=array();
      foreach($res as $key=>$value){
        array_push($values,"(\"".str_replace('"', '\'', trim($value["medicine_name"]))."\",\"".(trim($value["medicine_rate"])==""?1:trim($value["medicine_rate"]))."\")");
      }
      $sql=$sql.implode($values,",").";";
      $rs=M()->execute($sql);
    }

    public function downloadNullMedicineExcel(){
      $data = stripslashes($_REQUEST["batchids"]);
      $data = rtrim($data, '"');
      $data = ltrim($data, '"');
      $data = str_replace('&quot;', '\'', $data);
      $res = json_decode($data,true);
      $batchids=array();
      foreach($res as $key=>$value){
        array_push($batchids,$value["id"]);
      }
      $sql="SELECT distinct t.medicine_name FROM md_patient_medicine t left join (select * from md_medicine where isdel=1) s on t.medicine_name=s.medicine_name WHERE s.id is null and t.patient_batchid IN (".implode(',', array_map('change_to_quotes', $batchids)).")";
      $rs=M()->query($sql);
      Vendor("PHPExcel");
      $xlsTitle = iconv('utf-8', 'gb2312', "未匹配药品");//文件名称

      $fileName = $_SESSION['adminuser']."nullmedicine".date('_YmdHis');//or $xlsTitle 文件名称可根据自己情况设定

      $objPHPExcel = new \PHPExcel();
      $sheetcount=0;
      if(empty($rs)){
        exit;
      }
      $i=1;
      foreach($rs as $key=>$value){
        $objPHPExcel->getActiveSheet(0)->setCellValue('B'.($i),$value["medicine_name"]);
        $i=$i+1;
      }
      $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
      $objWriter->save($fileName.".xls");

      downfile("./".$fileName.".xls",$fileName.".xls");
    }
}
