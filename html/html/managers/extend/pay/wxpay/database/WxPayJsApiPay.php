<?php
/**
* 2015-06-29 修复签名问题
**/

namespace pay;
use pay\WxPayException;
use pay\WxPayConfig;
use pay\WxPayDataBase;


/**
 * 
 * 提交JSAPI输入对象
 * @author widyhu
 *
 */
class WxPayJsApiPay extends WxPayDataBase
{
	/**
	* 设置微信分配的公众账号ID
	* @param string $value 
	**/
	public function SetAppid($value)
	{
		$this->values['appId'] = $value;
	}
	/**
	* 获取微信分配的公众账号ID的值
	* @return 值
	**/
	public function GetAppid()
	{
		return $this->values['appId'];
	}
	/**
	* 判断微信分配的公众账号ID是否存在
	* @return true 或 false
	**/
	public function IsAppidSet()
	{
		return array_key_exists('appId', $this->values);
	}


	/**
	* 设置支付时间戳
	* @param string $value 
	**/
	public function SetTimeStamp($value)
	{
		$this->values['timeStamp'] = $value;
	}
	/**
	* 获取支付时间戳的值
	* @return 值
	**/
	public function GetTimeStamp()
	{
		return $this->values['timeStamp'];
	}
	/**
	* 判断支付时间戳是否存在
	* @return true 或 false
	**/
	public function IsTimeStampSet()
	{
		return array_key_exists('timeStamp', $this->values);
	}
	
	/**
	* 随机字符串
	* @param string $value 
	**/
	public function SetNonceStr($value)
	{
		$this->values['nonceStr'] = $value;
	}
	/**
	* 获取notify随机字符串值
	* @return 值
	**/
	public function GetReturn_code()
	{
		return $this->values['nonceStr'];
	}
	/**
	* 判断随机字符串是否存在
	* @return true 或 false
	**/
	public function IsReturn_codeSet()
	{
		return array_key_exists('nonceStr', $this->values);
	}


	/**
	* 设置订单详情扩展字符串
	* @param string $value 
	**/
	public function SetPackage($value)
	{
		$this->values['package'] = $value;
	}
	/**
	* 获取订单详情扩展字符串的值
	* @return 值
	**/
	public function GetPackage()
	{
		return $this->values['package'];
	}
	/**
	* 判断订单详情扩展字符串是否存在
	* @return true 或 false
	**/
	public function IsPackageSet()
	{
		return array_key_exists('package', $this->values);
	}
	
	/**
	* 设置签名方式
	* @param string $value 
	**/
	public function SetSignType($value)
	{
		$this->values['signType'] = $value;
	}
	/**
	* 获取签名方式
	* @return 值
	**/
	public function GetSignType()
	{
		return $this->values['signType'];
	}
	/**
	* 判断签名方式是否存在
	* @return true 或 false
	**/
	public function IsSignTypeSet()
	{
		return array_key_exists('signType', $this->values);
	}
	
	/**
	* 设置签名方式
	* @param string $value 
	**/
	public function SetPaySign($value)
	{
		$this->values['paySign'] = $value;
	}
	/**
	* 获取签名方式
	* @return 值
	**/
	public function GetPaySign()
	{
		return $this->values['paySign'];
	}
	/**
	* 判断签名方式是否存在
	* @return true 或 false
	**/
	public function IsPaySignSet()
	{
		return array_key_exists('paySign', $this->values);
	}
}

/**
 * 
 * 扫码支付模式一生成二维码参数
 * @author widyhu
 *
 */
class WxPayBizPayUrl extends WxPayDataBase
{
		/**
	* 设置微信分配的公众账号ID
	* @param string $value 
	**/
	public function SetAppid($value)
	{
		$this->values['appid'] = $value;
	}
	/**
	* 获取微信分配的公众账号ID的值
	* @return 值
	**/
	public function GetAppid()
	{
		return $this->values['appid'];
	}
	/**
	* 判断微信分配的公众账号ID是否存在
	* @return true 或 false
	**/
	public function IsAppidSet()
	{
		return array_key_exists('appid', $this->values);
	}


	/**
	* 设置微信支付分配的商户号
	* @param string $value 
	**/
	public function SetMch_id($value)
	{
		$this->values['mch_id'] = $value;
	}
	/**
	* 获取微信支付分配的商户号的值
	* @return 值
	**/
	public function GetMch_id()
	{
		return $this->values['mch_id'];
	}
	/**
	* 判断微信支付分配的商户号是否存在
	* @return true 或 false
	**/
	public function IsMch_idSet()
	{
		return array_key_exists('mch_id', $this->values);
	}
	
	/**
	* 设置支付时间戳
	* @param string $value 
	**/
	public function SetTime_stamp($value)
	{
		$this->values['time_stamp'] = $value;
	}
	/**
	* 获取支付时间戳的值
	* @return 值
	**/
	public function GetTime_stamp()
	{
		return $this->values['time_stamp'];
	}
	/**
	* 判断支付时间戳是否存在
	* @return true 或 false
	**/
	public function IsTime_stampSet()
	{
		return array_key_exists('time_stamp', $this->values);
	}
	
	/**
	* 设置随机字符串
	* @param string $value 
	**/
	public function SetNonce_str($value)
	{
		$this->values['nonce_str'] = $value;
	}
	/**
	* 获取随机字符串的值
	* @return 值
	**/
	public function GetNonce_str()
	{
		return $this->values['nonce_str'];
	}
	/**
	* 判断随机字符串是否存在
	* @return true 或 false
	**/
	public function IsNonce_strSet()
	{
		return array_key_exists('nonce_str', $this->values);
	}
	
	/**
	* 设置商品ID
	* @param string $value 
	**/
	public function SetProduct_id($value)
	{
		$this->values['product_id'] = $value;
	}
	/**
	* 获取商品ID的值
	* @return 值
	**/
	public function GetProduct_id()
	{
		return $this->values['product_id'];
	}
	/**
	* 判断商品ID是否存在
	* @return true 或 false
	**/
	public function IsProduct_idSet()
	{
		return array_key_exists('product_id', $this->values);
	}
}
