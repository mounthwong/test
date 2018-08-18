<?php

namespace app\admin\controller\user;

use app\common\controller\Backend;
use fast\Random;
use think\Config;
/**
 * 会员管理
 *
 * @icon fa fa-user
 */
class User extends Backend
{

    protected $relationSearch = true;

    /**
     * User模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('User');
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
            if($this->request->request('addtabs')){
                return $this->import();
            }
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField'))
            {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                    ->with('group,order')
                    ->where($where)
                    ->order($sort, $order)
                    ->count();
            $list = $this->model
                    ->with('group,order')
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
    public function edit($ids = NULL)
    {
        $row = $this->model->get($ids);
        if (!$row)
            $this->error(__('No Results were found'));
        $this->view->assign('groupList', build_select('row[group_id]', \app\admin\model\UserGroup::column('id,name'), $row['group_id'], ['class' => 'form-control selectpicker']));
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
        $this->view->assign('groupList', build_select('row[group_id]', \app\admin\model\UserGroup::column('id,name'), 0, ['class' => 'form-control selectpicker']));
        return $this->view->fetch();
    }

    /**
     * 删除
     */
    public function del($ids = "")
    {
        if ($ids)
        {
            $count = $this->model->where('id', $ids)->delete();
            if ($count)
            {
                $this->success();
            }
        }
        $this->error();
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
    //导入
    public function import(){
        //从第二行开始
        //1、第二列机构 第三列机构
        //2、客户姓名 和身份证号 手机号 易医卡号 进行md5之后和系统中进行比对
        //如果易医卡号相同 身份证推算年龄 不超过18岁进入系统
        //验证身份证号码
        //验证手机号
        //验证易医卡号 8为数字
        $filename=$this->request->request("file");
        //文件的路径截取
        $filename=str_replace(Config::get("upload_dir"),"",$filename);
        $filename="../public".$filename;
        //读取excel文件
        vendor('phpexcel.PHPExcel');
        $arr_excel = readExcel($filename);
        $data=array();
        //A B C D E G 
        $arrcolumn = array('B', 'C', 'D','E','F','G');
        $i = 0;
        $iserr = false;
	$key=0;
        foreach ($arr_excel as $row => $v) {
            /* 必填列判断 */
            foreach ($arrcolumn as $cv) {
                $v["iserr"] = false;
                if (empty($v["Column"][$cv])) {
                    $temp["A"] = $v["Column"]["A"];
                    $temp["B"] = $v["Column"]["B"];
                    $temp["C"] = $v["Column"]["C"];
                    $temp["D"] = $v["Column"]["D"];
                    $temp["E"] = (string)$v["Column"]["E"];
                    $temp["F"] = $v["Column"]["F"];
                    $temp["G"] = $v["Column"]["G"];
                    $temp["H"] = $v["Column"]["H"];
                    $temp["I"] = '第' . $row . '行' . $cv . '列为空';
                    $data[] = $temp;
                    $v["iserr"]=true;
                    $iserr = true;
                    break;
                }
            }
            if ($v["iserr"]) {
                continue;
            }
            $i++;
            //1、客户姓名 和身份证号 手机号 易医卡号 进行md5之后和系统中进行比对
            $city=$v["Column"]["B"];
            $region=$v["Column"]["C"];
            $truename=$v["Column"]["D"];
            $idcard=$v["Column"]["E"];
            $mobile=$v["Column"]["F"];
            $cardnum=$v["Column"]["G"];
            $cardtype=$v["Column"]["H"];
            $age=0;
            //2、身份证号验证
            $iscredit=$this->isCreditNo_simple($idcard);
            if(!$iscredit){
                $temp["A"] = $v["Column"]["A"];
                $temp["B"] = $v["Column"]["B"];
                $temp["C"] = $v["Column"]["C"];
                $temp["D"] = $v["Column"]["D"];
                $temp["E"] = (string)$v["Column"]["E"];
                $temp["F"] = $v["Column"]["F"];
                $temp["G"] = $v["Column"]["G"];
                $temp["H"] = $v["Column"]["H"];
                $temp["I"] = '第' . $row . '行身份证号错误';
                $data[] = $temp;
                $v["iserr"]=true;
                continue;
            }else{
                //计算年龄
                $age=$this->getAgeByCardID($idcard);
            }
            //3、验证手机号
            $is_mobile=$this->is_mobile($mobile);
            if(!$is_mobile){
                $temp["A"] = $v["Column"]["A"];
                $temp["B"] = $v["Column"]["B"];
                $temp["C"] = $v["Column"]["C"];
                $temp["D"] = $v["Column"]["D"];
                $temp["E"] = (string)$v["Column"]["E"];
                $temp["F"] = $v["Column"]["F"];
                $temp["G"] = $v["Column"]["G"];
                $temp["H"] = $v["Column"]["H"];
                $temp["I"] = '第' . $row . '行手机号错误';
                $data[]  = $temp;
                $v["iserr"]=true;
                continue;
            }
            //4、验证易卡号
            $is_card=$this->is_card($cardnum);
            if(!$is_card){
                $temp["A"] = $v["Column"]["A"];
                $temp["B"] = $v["Column"]["B"];
                $temp["C"] = $v["Column"]["C"];
                $temp["D"] = $v["Column"]["D"];
                $temp["E"] = (string)$v["Column"]["E"];
                $temp["F"] = $v["Column"]["F"];
                $temp["G"] = $v["Column"]["G"];
                $temp["H"] = $v["Column"]["H"];
                $temp["I"] = '第' . $row . '行易卡号错误';
                $data[] = $temp;
                $v["iserr"]=true;
                continue;
            }
            //md5比对
            $md5=md5($truename.$idcard.$mobile);
            //查询用户表
            $rs=$this->model->where("md5",$md5)->find();
            if(!empty($rs)){
                $temp["A"] = $v["Column"]["A"];
                $temp["B"] = $v["Column"]["B"];
                $temp["C"] = $v["Column"]["C"];
                $temp["D"] = $v["Column"]["D"];
                $temp["E"] = (string)$v["Column"]["E"];
                $temp["F"] = $v["Column"]["F"];
                $temp["G"] = $v["Column"]["G"];
                $temp["H"] = $v["Column"]["H"];
                $temp["I"] = '第' . $row . '行系统中存在姓名身份证手机以及易卡号相同的用户';
                $data[] = $temp;
                $v["iserr"]=true;
                continue;
            }
            $rs=model("UserCard")->where("cardnum",$cardnum)->find();
             if(!empty($rs)){
                if($age>18){
                    $temp["A"] = $v["Column"]["A"];
                    $temp["B"] = $v["Column"]["B"];
                    $temp["C"] = $v["Column"]["C"];
                    $temp["D"] = $v["Column"]["D"];
                    $temp["E"] = (string)$v["Column"]["E"];
                    $temp["F"] = $v["Column"]["F"];
                    $temp["G"] = $v["Column"]["G"];
                    $temp["H"] = $v["Column"]["H"];
                    $temp["I"] = '第' . $row . '行系统中存在绑定易卡号的用户并且此用户大于18岁';
                    $data[] = $temp;
                    $v["iserr"]=true;
                    continue;
                }else{
                    $card["userid"]=$mobile;
                    $card["cardnum"]=$cardnum;
                    model("UserCard")->save($card);
                }
            }else{
                $card["userid"]=$mobile;
                $card["cardnum"]=$cardnum;
                model("UserCard")->save($card);
            }
            //查询此卡在不在卡号管理中
            $rs=model("Card")->where("cardnum",$cardnum)->find();
            unset($params);
            if(empty($rs)){
                $params["createtime"]=time();

                $params["cardnum"]=$cardnum;
                $type=0;
                $cardtypes=config::get("card");
                foreach($cardtypes as $key=>$value){
                    if($value == $cardtype){
                        $type=$key;
                        break;
                    }
                }
                $params["cardtype"]=$type;
                $params["begintime"]=time();
                $params["endtime"]=time();
                $result = model("Card")->save($params);
            }
            unset($params);
            $params["username"]=$mobile;
            $params["nickname"]=$truename;
            $group=model("UserGroup")->where(["name"=>$cardtype])->find();
            $params["group_id"]=empty($group)?2:$group["id"];
            $params["status"]="normal";
            $params['salt'] = Random::alnum();
            $params['password'] = md5(md5("123456").$params['salt']);
            $params["idcard"]=$idcard;
            $params["mobile"]=$mobile;
            $params["avatar"]='/assets/img/avatar.png';
            $params["gender"]=1;
            $params["age"]=$age;
            $params["birthday"]=Date("Y-m-d",strtotime(substr($idcard,6,8)));
            $params["md5"]=$md5;
            $params["city"]=$city;
            $params["region"]=$region;
            if($key==0){
                $result = $this->model->save($params);
            }else{
                $result = $this->model->isUpdate(false)->save($params);
            }
            $key=$key+1;

        }
        //将数据写入excel
        $this->exportExcel("导入错误信息",["序号","三级机构","四级机构","客户姓名","身份证","手机号","易医卡号","原因"],$data);
        //$this->success($data);
    }    



    /**
     * 验证身份证号
     * @param $vStr
     * @return bool
     */
    protected function isCreditNo_simple($vStr)
    {
        $vCity = array(
            '11', '12', '13', '14', '15', '21', '22',
            '23', '31', '32', '33', '34', '35', '36',
            '37', '41', '42', '43', '44', '45', '46',
            '50', '51', '52', '53', '54', '61', '62',
            '63', '64', '65', '71', '81', '82', '91'
        );
    
        if (!preg_match('/^([\d]{17}[xX\d]|[\d]{15})$/', $vStr)) return false;
    
        if (!in_array(substr($vStr, 0, 2), $vCity)) return false;
    
        $vStr = preg_replace('/[xX]$/i', 'a', $vStr);
        $vLength = strlen($vStr);
    
        if ($vLength == 18) {
            $vBirthday = substr($vStr, 6, 4) . '-' . substr($vStr, 10, 2) . '-' . substr($vStr, 12, 2);
        } else {
            $vBirthday = '19' . substr($vStr, 6, 2) . '-' . substr($vStr, 8, 2) . '-' . substr($vStr, 10, 2);
        }
    
        if (date('Y-m-d', strtotime($vBirthday)) != $vBirthday) return false;
        if ($vLength == 18) {
            $vSum = 0;
    
            for ($i = 17; $i >= 0; $i--) {
                $vSubStr = substr($vStr, 17 - $i, 1);
                $vSum += (pow(2, $i) % 11) * (($vSubStr == 'a') ? 10 : intval($vSubStr, 11));
            }
    
            if ($vSum % 11 != 1) return false;
        }
    
        return true;
    }

    /**
     * 验证身份证号
     * @param $vStr
     * @return bool
     */
    protected function is_mobile($text) {
        $search = '/^0?1[3|4|5|6|7|8][0-9]\d{8}$/';
        if ( preg_match( $search, $text ) ) {
            return ( true );
        } else {
            return ( false );
        }
    }

    /**
     * 易卡账号
     * @param $vStr
     * @return bool
     */
    protected function is_card($text){
        if (!preg_match('|^\d{8}$|',$text)) {
            return false;
        }
        return true;
    }

    /**
     * 身份证号计算年龄
     * @param $vStr
     * @return bool
     */
    protected function getAgeByCardID($id){
        //过了这年的生日才算多了1周岁 
        if(empty($id)) return ''; 
        $date=strtotime(substr($id,6,8));
        //获得出生年月日的时间戳 
        $today=strtotime('today');
        //获得今日的时间戳 
        $diff=floor(($today-$date)/86400/365);
        //得到两个日期相差的大体年数 

        //strtotime加上这个年数后得到那日的时间戳后与今日的时间戳相比 
        $age=strtotime(substr($id,6,8).' +'.$diff.'years')>$today?($diff+1):$diff; 

        return $age; 
    }

    protected function exportExcel($title,$cellName,$data){
        if(!empty($data)){
            vendor('phpExcel.PHPExcel');
            $response=exportOrderExcel($title,$cellName,$data);
            $ret["url"]=$response;
            $this->success(__('Logged in successful'), "",$ret);
        }else{
            $this->success();
        }
    }

    
    public function history($ids){
        //查询总共有几次订单
        $user=$this->model->where("id",$ids)->find();
        $userid=$user["username"];
        //查询这个人的记录
        $orderlist = model("ProductOrder")->where(["userid"=>$userid])->where("overtime is not null")->order("createtime desc")->select();
        $this->view->assign("orderlist",$orderlist);
        return $this->view->fetch();
    }


    //获取用户的卡类型
    public function getUserCard(){
        $username = $this->request->get("username");
        $rs=\app\admin\model\UserCard::with("card")->where("userid",$username)->select();
        $productinfos=model("CardProduct")->getCardProductinfos();
        foreach($rs as $key=>$value){
            $rs[$key]["card"]["cardproduct"]=$productinfos[$value["card"]["cardtype"]];
            $rs[$key]["card"]["cardtype"]=Config::get("card")[$value["card"]["cardtype"]];
        }
        return json($rs);
    }

}
