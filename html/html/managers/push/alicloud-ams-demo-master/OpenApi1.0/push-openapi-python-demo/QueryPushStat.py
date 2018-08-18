#!/usr/bin/python
#coding=utf-8
import properties
from aliyunsdkpush.request.v20150827 import QueryPushStatRequest
from aliyunsdkcore import client

clt = client.AcsClient(properties.accessKeyId,properties.accessKeySecret,properties.regionId)

request = QueryPushStatRequest.QueryPushStatRequest()
request.set_AppKey(properties.appKey)

## 推送成功后返回的消息ID(ResponseId)
request.set_MessageId('MessageId')

result = clt.do_action(request)

print result
