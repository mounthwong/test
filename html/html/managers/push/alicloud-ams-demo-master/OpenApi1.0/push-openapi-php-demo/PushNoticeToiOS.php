<?php

include_once '../aliyun-php-sdk-core/Config.php';
use \Push\Request\V20150827 as Push;

// 设置你的AccessKeyId/AccessSecret/AppKey
$accessKeyId = "";
$accessSecret = "";
$appKey = 123456;
$iClientProfile = DefaultProfile::getProfile("cn-hangzhou", $accessKeyId, $accessSecret);
$client = new DefaultAcsClient($iClientProfile);
$request = new Push\PushNoticeToiOSRequest();


$request->setAppKey($appKey);
$request->setTarget("all"); //推送目标: device:推送给设备; account:推送给指定帐号,tag:推送给自定义标签; all: 推送给全部
$request->setTargetValue("all"); //根据Target来设定，如Target=device, 则对应的值为 设备id1,设备id2. 多个值使用逗号分隔.(帐号与设备有一次最多100个的限制)
$request->setEnv("DEV");
$request->setSummary("summary");
$request->setiOSExtParameters("{\"k1\":\"v1\",\"k2\":\"v2\"}");
$request->setExt("{\"sound\":\"default\",\"badge\":\"42\"}");


$response = $client->getAcsResponse($request);
print_r("\r\n");
print_r($response);

?>
