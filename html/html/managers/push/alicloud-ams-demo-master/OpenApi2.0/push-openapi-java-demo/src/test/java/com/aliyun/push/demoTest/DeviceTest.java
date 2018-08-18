package com.aliyun.push.demoTest;

import com.alibaba.fastjson.JSON;
import com.aliyuncs.push.model.v20160801.*;
import org.junit.Test;

/**
 * Created by lingbo on 2016/12/15.
 * 设备相关接口demo
 */
public class DeviceTest extends BaseTest {
    /**
     * 查询设备信息
     * 参考文档：https://help.aliyun.com/document_detail/48098.html
     */
    @Test
    public void testQueryDeviceInfo() throws Exception {

        QueryDeviceInfoRequest request = new QueryDeviceInfoRequest();
        request.setAppKey(appKey);
        request.setDeviceId(deviceIds);

        QueryDeviceInfoResponse response = client.getAcsResponse(request);
        System.out.printf("RequestId: %s\n", response.getRequestId());
        QueryDeviceInfoResponse.DeviceInfo deviceInfo = response.getDeviceInfo();
        System.out.print(JSON.toJSONString(deviceInfo));

    }

    @Test
    public void testCheckDevices() throws Exception {
        CheckDevicesRequest request = new CheckDevicesRequest();
        request.setAppKey(appKey);
        request.setDeviceIds(deviceIds);
        CheckDevicesResponse response = client.getAcsResponse(request);
        System.out.print(JSON.toJSONString(response)); 
    }	

}
