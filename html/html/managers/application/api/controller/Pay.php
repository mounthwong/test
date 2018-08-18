<?php

namespace app\api\controller;

use app\common\controller\Api;
use app\common\library\Order as UserOrder;
use fast\Random;
use think\Validate;

// require_once "../lib/WxPay.Api.php";
// require_once "WxPay.JsApiPay.php";
// require_once 'log.php';
use pay\wxpay\WxPayApi;
use pay\wxpay\WxPayConfig;
use pay\wxpay\database\WxPayUnifiedOrder;


/**
 * 会员接口
 */
class Pay extends Api
{

    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';

    public function _initialize()
    {
        parent::_initialize();
    }

    //用户支付
    public function orderpay(){
        $trade_no=$this->request->request("orderid","0");
        $total_fee=$this->request->request("total",0);
        $type=$this->request->request("type",0);
        if($type==0){
            $this->wxpay($trade_no,$total_fee);
        }else if($type==1){
            $this->alipay($trade_no,$total_fee);
        }
    }

    //微信用户通知
    public function wxnotify(){
         file_put_contents("wxlog.txt",json_encode($_REQUEST));
        //file_put_contents("wxlog.txt",$_REQUEST);
         $xml = file_get_contents('php://input');
         $arr = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
         // $arr=json_decode($_GET["data"],true);
        
        //file_put_contents("wxlog.txt",json_encode($arr));
        //用户http_build_query()将数据转成URL键值对形式
        $sign = http_build_query($arr);
        var_dump($sign);
        //md5处理
        $sign = md5($sign);

        //var_dump($sign);exit;
        //转大写
        $sign = strtoupper($sign);
        //验签名。默认支持MD5
        //if ( $sign === $arr['sign']) {
            //校验返回的订单金额是否与商户侧的订单金额一致。修改订单表中的支付状态。
            $orderid=$arr["out_trade_no"];
            $transaction_id=$arr["transaction_id"];
            $result=UserOrder::changeOrderStatus($orderid,'2',$transaction_id,0);
            echo '<xml>
                    <return_code><![CDATA[SUCCESS]]></return_code>
                    <return_msg><![CDATA[OK]]></return_msg>
                </xml>';
            exit(); 
        //}
    }

    //阿里用户通知
    public function alinotify(){
        
        file_put_contents("wxlog.txt",json_encode($_REQUEST)."\n\r",FILE_APPEND);
        foreach($_REQUEST as $key=>$value){
            file_put_contents("wxlog.txt",$key."=".$value."\n\r",FILE_APPEND);
        }
        //交易状态
        $trade_status = $_POST['trade_status'];
        if($_REQUEST['trade_status'] == 'TRADE_SUCCESS') {
            $out_trade_no = $_POST['out_trade_no'];
            file_put_contents("wxlog.txt","out_trade_no=".$out_trade_no."\n\r");
            //支付宝交易号
            $trade_no = $_POST['trade_no'];
            $result=UserOrder::changeOrderStatus($out_trade_no,'2',$trade_no,1);
            echo "SUCCES";
        }else{
            echo "fail";
        }
    }

    //微信支付
    private function wxpay($trade_no,$total_fee){
        ini_set('date.timezone','Asia/Shanghai');
        if(empty($trade_no)){
            $ret["msg"]="订单不能为空";
            $this->error(__('user wxpay successful'), $ret);
        }
        if($total_fee<=0){
            $ret["msg"]="金额不能小于0";
            $this->error(__('user wxpay successful'), $ret);
        }
        $start_time=date("YmdHis");
        $time_expire=date("YmdHis", time() + 600);
        $body="医联医疗-商品购买";
        $trade_type="APP";
        $notify_url="http://www.hnumg.cn/managers/public/index.php/api/pay/wxnotify";
        //②、统一下单
        $input = new WxPayUnifiedOrder();
        $input->SetBody($body);
        $input->SetOut_trade_no($trade_no);
        $input->SetTotal_fee($total_fee);
        $input->SetTime_start($start_time);
        $input->SetTime_expire($time_expire);
        $input->SetNotify_url($notify_url);
        $input->SetTrade_type($trade_type);
        $order = WxPayApi::unifiedOrder($input);
        $order["timestamp"]=(string)time();
        $order["package_value"]=WxPayConfig::PACKAGEVALUE;
        $arr["appid"]=$order["appid"];
        $arr["noncestr"]=$order["nonce_str"];
        if(isset($order["prepay_id"])){
            $arr["prepayid"]=$order["prepay_id"];
            $arr["package"]=$order["package_value"];
            $arr["partnerid"]=$order["mch_id"];
            $arr["timestamp"]=$order["timestamp"];
            $sings=$this->MakeSign($arr);
            $order["sign"]=$sings;
            $this->success(__('user wxpay successful'), $order);
        }else{
            $this->error(__('user wxpay error'), array());
        }
		
        //echo json_encode($ret);
	}

	public function ToUrlParams($values)
	{
		$buff = "";
		foreach ($values as $k => $v)
		{
			if($k != "sign" && $v != "" && !is_array($v)){
				$buff .= $k . "=" . $v . "&";
			}
		}
		
		$buff = trim($buff, "&");
		return $buff;
	}
	
	/**
	 * 生成签名
	 * @return 签名，本函数不覆盖sign成员变量，如要设置签名需要调用SetSign方法赋值
	 */
	public function MakeSign($values)
	{
		//签名步骤一：按字典序排序参数
		ksort($values);
		$string = $this->ToUrlParams($values);
		//签名步骤二：在string后加入KEY
		$string = $string . "&key=".WxPayConfig::KEY;
		//签名步骤三：MD5加密
		$string = md5($string);
		//签名步骤四：所有字符转为大写
		$result = strtoupper($string);
		return $result;
	}

    private function  alipay($order_sn, $total_amount){
        $total_amount=(double)($total_amount/100);
        require_once(EXTEND_PATH.'pay/alipay/aop/AopClient.php');
        $aop    =    new \AopClient();
        // $aop->gatewayUrl             = config('alipay_gatewayUrl');
        // $aop->appId                 = config('alipay_appId');
        // $aop->rsaPrivateKey         = config('alipay_rsaPrivateKey');//私有密钥
        $aop->format                 = "JSON";
        $aop->charset                = "utf-8";
        $aop->signType            = "RSA2";
        //$aop->alipayrsaPublicKey     = config('alipay_rsaPublicKey');//共有密钥
        require_once(EXTEND_PATH.'pay/alipay/aop/request/AlipayTradeAppPayRequest.php');
        //实例化具体API对应的request类,类名称和接口名称对应,当前调用接口名称：alipay.trade.app.pay
        $request = new \AlipayTradeAppPayRequest();
        //SDK已经封装掉了公共参数，这里只需要传入业务参数
        $body="医联医疗-商品购买";
        $trade_type="APP";
        $bizcontent    =    [
            'body'                =>    $body,
            'subject'            =>    $trade_type,
            'out_trade_no'        =>    $order_sn,
            'timeout_express'    =>    '1d',//失效时间为 1天
            'total_amount'        =>    $total_amount,//价格
            'product_code'        =>    'QUICK_MSECURITY_PAY'
        ];
        //商户外网可以访问的异步地址 (异步回掉地址，根据自己需求写)
        $request->setNotifyUrl("http://www.hnumg.cn/managers/public/index.php/api/pay/alinotify");
        $request->setBizContent(json_encode($bizcontent));
        //这里和普通的接口调用不同，使用的是sdkExecute
        $response = $aop->sdkExecute($request);
        //return $response;
        $this->success(__('user wxpay successful'), $response);
        //htmlspecialchars是为了输出到页面时防止被浏览器将关键参数html转义，实际打印到日志以及http传输不会有这个问题
    }
    
}
