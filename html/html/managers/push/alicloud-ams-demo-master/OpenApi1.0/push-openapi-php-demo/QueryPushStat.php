<?php

include_once '../aliyun-php-sdk-core/Config.php';
use \Push\Request\V20150827 as Push;

$accessKeyId = "";
$accessSecret = "";
$appKey = 123456;
$iClientProfile = DefaultProfile::getProfile("cn-hangzhou", $accessKeyId, $accessSecret);
$client = new DefaultAcsClient($iClientProfile);
$request = new QueryPushStat\QueryPushStatRequest();

$request->setAppKey($appKey);
$request->setMessageId('500345');

$response = $client->getAcsResponse($request);
print_r("\r\n");
print_r($response);

?>
