<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:86:"/usr/share/nginx/html/managers/public/../application/admin/view/order/order/order.html";i:1533379031;s:73:"/usr/share/nginx/html/managers/application/admin/view/layout/default.html";i:1528075110;s:70:"/usr/share/nginx/html/managers/application/admin/view/common/meta.html";i:1528075104;s:72:"/usr/share/nginx/html/managers/application/admin/view/common/script.html";i:1528075104;}*/ ?>
<!DOCTYPE html>
<html lang="<?php echo $config['language']; ?>">
    <head>
        <meta charset="utf-8">
<title><?php echo (isset($title) && ($title !== '')?$title:''); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<meta name="renderer" content="webkit">

<link rel="shortcut icon" href="/managers/public/assets/img/favicon.ico" />
<!-- Loading Bootstrap -->
<link href="/managers/public/assets/css/backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.css?v=<?php echo \think\Config::get('site.version'); ?>" rel="stylesheet">

<!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
<!--[if lt IE 9]>
  <script src="/managers/public/assets/js/html5shiv.js"></script>
  <script src="/managers/public/assets/js/respond.min.js"></script>
<![endif]-->
<script type="text/javascript">
    var require = {
        config:  <?php echo json_encode($config); ?>
    };
</script>
    </head>

    <body class="inside-header inside-aside <?php echo defined('IS_DIALOG') && IS_DIALOG ? 'is-dialog' : ''; ?>">
        <div id="main" role="main">
            <div class="tab-content tab-addtabs">
                <div id="content">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <section class="content-header hide">
                                <h1>
                                    <?php echo __('Dashboard'); ?>
                                    <small><?php echo __('Control panel'); ?></small>
                                </h1>
                            </section>
                            <?php if(!IS_DIALOG && !$config['fastadmin']['multiplenav']): ?>
                            <!-- RIBBON -->
                            <div id="ribbon">
                                <ol class="breadcrumb pull-left">
                                    <li><a href="dashboard" class="addtabsit"><i class="fa fa-dashboard"></i> <?php echo __('Dashboard'); ?></a></li>
                                </ol>
                                <ol class="breadcrumb pull-right">
                                    <?php foreach($breadcrumb as $vo): ?>
                                    <li><a href="javascript:;" data-url="<?php echo $vo['url']; ?>"><?php echo $vo['title']; ?></a></li>
                                    <?php endforeach; ?>
                                </ol>
                            </div>
                            <!-- END RIBBON -->
                            <?php endif; ?>
                            <div class="content">
                                  <style>
    .table tbody tr td{
            vertical-align: middle;
            text-align:center;
        }
    .custom tbody tr td{
        vertical-align: middle;
        text-align:left;
        width:20%;
    }
    .info{
        margin-left:12px;
    }
    .tds{
        width:95% !important;;
    }
</style>
<!-- 选项卡 -->
<ul class="nav nav-tabs" role="tablist">
    <!-- 默认标签 -->
    <li role="presentation" class="active"><a href="#home" role="tab" data-toggle="tab">接诊信息</a></li>
    <li role="presentation"><a href="#reset" role="tab" data-toggle="tab">诊前确认信息</a></li>
    <li role="presentation"><a href="#profile" role="tab" data-toggle="tab">就诊详情</a></li>
    <li role="presentation"><a href="#messages" role="tab" data-toggle="tab">客户回访</a></li>
</ul>
  
<!-- 选项卡内容 -->
<div class="tab-content" style="margin-top:20px;">
    <!-- 默认显示内容 -->
    <div role="tabpanel" class="tab-pane active row" id="home">
        <form class="form-horizontal" method="POST" action="../../kfcheck">
            <input name="order[id]" value="<?php echo $order['id']; ?>" type="hidden" />
            <div class="form-group">
                <p class="text-center"><strong style="font-size: 20px;">个人基本信息</strong></p>
                <div class="col-sm-10">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">姓名</label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control inline"  data-url="require" placeholder="姓名" name="order[truename]" value="<?php echo $order['truename']; ?>"/>
                        </div>
                        <label class="col-sm-2 control-label">身份证号</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control inline"  data-url="require" placeholder="身份证" name="order[idcard]" value="<?php echo $order['idcard']; ?>"/>
                        </div>
                        <label class="col-sm-1 control-label">性别</label>
                        <div class="col-sm-2">
                            <select class="form-control yilian" name="order[gender]]"  data-rule="require">
                                <option value="1" <?php if($order['expecthospitaltype'] == '1'): ?>selected="selected"<?php endif; ?>>男</option>
                                <option value="0" <?php if($order['expecthospitaltype'] == '0'): ?>selected="selected"<?php endif; ?>>女</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">手机号</label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control inline"  placeholder="手机号" name="order[phone]" value="<?php echo $order['phone']; ?>"/>
                        </div>
                        <label class="col-sm-2 control-label">地区</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control inline"  placeholder="地区" name="order[region]" value="<?php echo $order['region']; ?>"/>
                        </div>
                        <label class="col-sm-1 control-label">年龄</label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control inline"  placeholder="年龄" name="order[age]" value="<?php echo $order['age']; ?>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">支付方式</label>
                        <div class="col-sm-2">
                            <select class="form-control yilian" name="order[paytype]"  data-rule="require">
                                <option value="1" <?php if($order['paytype'] == '1'): ?>selected="selected"<?php endif; ?>>线下</option>
                                <option value="0" <?php if($order['paytype'] == '0'): ?>selected="selected"<?php endif; ?>>线上</option>
                            </select>
                        </div>
                        <label class="col-sm-2 control-label">卡号</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control inline"  placeholder="卡号" name="order[cardnum]" value="<?php echo $order['cardnum']; ?>"/>
                        </div>
                        <label class="col-sm-1 control-label">金额</label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control inline"  placeholder="金额" name="order[amount]" value="<?php echo $order['amount']; ?>"/>
                        </div>
                    </div>
                    <div class="form-group">
                            <label class="col-sm-2 control-label">支付时间</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control inline"  placeholder="金额" name="order[paytime]" value="<?php echo $order['paytime']; ?>"/>
                            </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">客户现状</label>
                <div class="col-sm-10">
                    <textarea rows="5" cols="87" name="order[customdetail]" data-rule="require"><?php echo $order['customdetail']; ?></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">期望就诊医院</label>
                <div class="col-sm-10">
                    <div class="col-sm-4">
                        <select class="form-control yilian" name="order[expecthospitaltype]"  data-rule="require" onchange='var value=$(this).val();if(value == "0"){$(this).parents(".form-group").find("input").attr("date-rule","");$(this).parents(".form-group").find("input").attr("type","hidden");}else{$(this).parents(".form-group").find("input").attr("date-rule","require");$(this).parents(".form-group").find("input").attr("type","text");}'>
                            <option value="0" <?php if($order['expecthospitaltype'] == '0'): ?>selected="selected"<?php endif; ?>>医联推荐</option>
                            <option value="1" <?php if($order['expecthospitaltype'] == '1'): ?>selected="selected"<?php endif; ?>>客户指定</option>
                            
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <?php if($order['expecthospitaltype'] == '1'): ?>
                            <input type="text" data-rule="require" name="order[expecthospital]" class="form-control inline" placeholder="医院" value="<?php echo $order['expecthospital']; ?>"/>
                        <?php else: ?>
                            <input type="hidden" name="order[expecthospital]" class="form-control inline" placeholder="医院" value="<?php echo $order['expecthospital']; ?>"/>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                    <label class="col-sm-2 control-label">期望就诊科室</label>
                    <div class="col-sm-10">
                        <div class="col-sm-4">
                            <select  data-rule="require" class="form-control yilian" name="order[expectsectiontype]" onchange='var value=$(this).val();if(value == "0"){$(this).parents(".form-group").find("input").attr("date-rule","");$(this).parents(".form-group").find("input").attr("type","hidden");}else{$(this).parents(".form-group").find("input").attr("date-rule","require");$(this).parents(".form-group").find("input").attr("type","text");}'>
                                <option value="0" <?php if($order['expectsectiontype'] == '0'): ?>selected="selected"<?php endif; ?>>医联推荐</option>
                                <option value="1" <?php if($order['expectsectiontype'] == '1'): ?>selected="selected"<?php endif; ?>>客户指定</option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <?php if($order['expectsectiontype'] == '1'): ?>
                                <input type="text" data-rule="require" name="order[expectsection]" class="form-control inline" placeholder="科室" value="<?php echo $order['expectsection']; ?>"/>
                            <?php else: ?>
                                <input type="hidden" name="order[expectsection]" class="form-control inline" placeholder="科室" value="<?php echo $order['expectsection']; ?>"/>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">期望就诊医生</label>
                <div class="col-sm-10">
                    <div class="col-sm-4">
                        <select  data-rule="require" class="form-control yilian"  name="order[expectdoctortype]" onchange='var value=$(this).val();if(value == "0"){$(this).parents(".form-group").find("input").attr("date-rule","");$(this).parents(".form-group").find("input").attr("type","hidden");}else{$(this).parents(".form-group").find("input").attr("date-rule","require");$(this).parents(".form-group").find("input").attr("type","text");}'>
                            <option value="0" <?php if($order['expectdoctortype'] == '0'): ?>selected="selected"<?php endif; ?>>医联推荐</option>
                            <option value="1" <?php if($order['expectdoctortype'] == '1'): ?>selected="selected"<?php endif; ?>>客户指定</option>
                            
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <?php if($order['expectdoctortype'] == '1'): ?>
                            <input type="text" data-rule="require" name="order[expectdoctor]" class="form-control inline" placeholder="医生" value="<?php echo $order['expectdoctor']; ?>"/>
                        <?php else: ?>
                            <input type="hidden" name="order[expectdoctor]" class="form-control inline" placeholder="医生" value="<?php echo $order['expectdoctor']; ?>"/>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">期望就诊时间</label>
                <div class="col-sm-10">
                    <div class="col-sm-4">
                        <select  data-rule="require"  class="form-control yilian" name="order[expecttimetype]" onchange='var value=$(this).val();if(value == "0"){$(this).parents(".form-group").find("input").attr("date-rule","");$(this).parents(".form-group").find("input").attr("type","hidden");}else{$(this).parents(".form-group").find("input").attr("date-rule","require");$(this).parents(".form-group").find("input").attr("type","text");}'>
                            <option value="0" <?php if($order['expecttimetype'] == '0'): ?>selected="selected"<?php endif; ?>>医联推荐</option>
                            <option value="1" <?php if($order['expecttimetype'] == '1'): ?>selected="selected"<?php endif; ?>>客户指定</option>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <?php if($order['expecttimetype'] == '1'): ?>
                            <input type="text" data-rule="require" name="order[expecttime]" class="form-control inline" placeholder="时间" value="<?php echo $order['expecttime']; ?>"/>
                        <?php else: ?>
                            <input type="hidden" name="order[expecttime]" class="form-control inline" placeholder="时间" value="<?php echo $order['expecttime']; ?>"/>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">具体服务需求</label>
                <div class="col-sm-10">
                    <div class="col-sm-10">
                        <?php if(is_array($groupdata) || $groupdata instanceof \think\Collection || $groupdata instanceof \think\Paginator): $i = 0; $__LIST__ = $groupdata;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                            <div class="col-sm-3">
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="customserver[]"  data-rule="require"  value="<?php echo $vo; ?>" <?php if(in_array(($key), is_array($order['customserver'])?$order['customserver']:explode(',',$order['customserver']))): ?>checked<?php endif; ?>><?php echo $vo; ?>
                                </label>
                            </div>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">备注</label>
                <div class="col-sm-10">
                    <textarea rows="5" cols="87" name="order[customremark]"><?php echo $order['customremark']; ?></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">受理客服</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control inline" value="<?php echo $order['kfdoperson']; ?>" name="order[kfdoperson]" disabled/>
                </div>
                <label class="col-sm-1 control-label">发送时间</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control inline" value="<?php echo date('Y-m-d H:i:s',$order['kfsendtime']); ?>" name="order[kfsendtime]" disabled/>
                </div>
                <label class="col-sm-1 control-label">发送给</label>
                <div class="col-sm-2">
                    <?php echo $firstList; ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-2"></div>
                <div class="col-xs-12 col-sm-8">
                    <button type="submit" class="btn btn-success btn-embossed">保存</button>
                    <button type="reset" class="btn btn-default btn-embossed"><?php echo __('Reset'); ?></button>
                </div>
            </div>
        </form>
    </div>
    <div role="tabpanel" class="tab-pane" id="reset">
        <form class="form-horizontal" method="post" action="../../diagnosisconfirm">
                <input name="order[id]" value="<?php echo $order['id']; ?>" type="hidden" />
            <div class="form-group">
                <label class="col-sm-2 control-label">预约就诊医院</label>
                <div class="col-sm-10">
                    <div class="col-sm-4">
                        <select class="form-control"  name="order[appointhospitaltype]">
                            <option value="0" <?php if($order['expecthospitaltype'] == '0'): ?>selected="selected"<?php endif; ?>>医联推荐</option>
                            <option value="1" <?php if($order['expecthospitaltype'] == '1'): ?>selected="selected"<?php endif; ?>>客户指定</option>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <input type="text" name="order[appointhospital]"  class="form-control inline" placeholder="医院*" value="<?php echo $order['expecthospital']; ?>"/>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">预约就诊科室</label>
                <div class="col-sm-10">
                    <div class="col-sm-4">
                        <select class="form-control" name="order[appointsectiontype]">
                            <option value="0" <?php if($order['appointsectiontype'] == '0'): ?>selected="selected"<?php endif; ?>>医联推荐</option>
                            <option value="1" <?php if($order['appointsectiontype'] == '1'): ?>selected="selected"<?php endif; ?>>客户指定</option>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <input type="text" name="order[appointsection]" class="form-control inline" placeholder="科室*" value="<?php echo $order['appointsection']; ?>"/>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">预约就诊医生</label>
                <div class="col-sm-10">
                    <div class="col-sm-4">
                        <select class="form-control" name="order[appointdoctortype]">
                            <option value="0" <?php if($order['expectdoctortype'] == '0'): ?>selected="selected"<?php endif; ?>>医联推荐</option>
                            <option value="1" <?php if($order['expectdoctortype'] == '1'): ?>selected="selected"<?php endif; ?>>客户指定</option>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <input type="text" name="order[appointdoctor]" class="form-control inline" placeholder="医生*" value="<?php echo $order['appointdoctor']; ?>"/>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">预约就诊时间</label>
                <div class="col-sm-10">
                    <div class="col-sm-4">
                        <select class="form-control" name="order[appointtimetype]">
                            <option value="0" <?php if($order['expecttimetype'] == '0'): ?>selected="selected"<?php endif; ?>>医联推荐</option>
                            <option value="1" <?php if($order['expecttimetype'] == '1'): ?>selected="selected"<?php endif; ?>>客户指定</option>
                            
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <input type="text" name="order[appointtime]" class="form-control inline" placeholder="时间*" value="<?php echo $order['appointtime']; ?>"/>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">备注</label>
                <div class="col-sm-10">
                    <textarea rows="5" cols="72" name="order[appointremark]"><?php echo $order['appointremark']; ?></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">接诊人员</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control inline" value="<?php echo $order['kfsendperson']; ?>" disabled/>
                </div>
                <label class="col-sm-2 control-label">发送时间</label>
                <div class="col-sm-2">
                    <?php if($order['appointsendtime'] == ''): ?>
                        <input type="text" class="form-control inline" value="" name="order[appointsendtime]" disabled/>
                    <?php else: ?>
                        <input type="text" class="form-control inline" value="<?php echo date('Y-m-d H:i:s',$order['appointsendtime']); ?>" name="order[appointsendtime]" disabled/>
                    <?php endif; ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-2"></div>
                <div class="col-xs-12 col-sm-8">
                    <button type="submit" class="btn btn-success btn-embossed">保存</button>
                    <button type="reset" class="btn btn-default btn-embossed" onclick="javascript:window.open('../../../../order/order/exportComfirmExcel?id=<?php echo $order['id']; ?>');" >导出</button>
                </div>
            </div>
        </form>
    </div>
    <div role="tabpanel" class="tab-pane" id="profile">
        <form class="form-horizontal"  method="post" action="../../diagnosis">
                <input name="order[id]" value="<?php echo $order['id']; ?>" type="hidden" />
            <div class="form-group">
                <p class="text-center"><strong style="font-size: 20px;">看诊</strong></p>
                <div class="col-sm-10">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">客户到达医院时间</label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control inline"  placeholder="客户到达医院时间" name="order[diagnosiscustomtime]" value="<?php echo $order['diagnosiscustomtime']; ?>"/>
                        </div>
                        <label class="col-sm-2 control-label">就诊总时长</label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control inline"  placeholder="就诊总时长" name="order[diagnosistotaltime]" value="<?php echo $order['diagnosistotaltime']; ?>"/>
                        </div>
                        <label class="col-sm-2 control-label">看诊时长</label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control inline"  placeholder="看诊时长" name="order[diagnosistime]" value="<?php echo $order['diagnosistime']; ?>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">疾病诊断</label>
                        <div class="col-sm-10">
                            <textarea rows="3" cols="90"   name="order[diagnosisdisease]" ><?php echo $order['diagnosisdisease']; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">医生建议（治疗方案）</label>
                        <div class="col-sm-10">
                            <textarea rows="3" cols="90"   name="order[diagnosisadvice]" ><?php echo $order['diagnosisadvice']; ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <p class="text-center">
                    <strong style="font-size: 20px;">检查</strong>
                    <input type="radio" value="0" class="toogle" name="check" onclick="javascript:"/>无
                    <input type="radio" value="1" class="toogle" name="check" onclick="javascript:"/>有
                </p>
                <div class="col-sm-10">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">检查项目1</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control inline"   name="order[diagnosischeckname1]" value="<?php echo $order['diagnosischeckname1']; ?>"/>
                        </div>
                        <label class="col-sm-1 control-label">绿通</label>
                        <div class="col-sm-2">
                            <select class="form-control" name="order[diagnosischeckgreentype1]" >
                                <option value="0" <?php if($order['diagnosischeckgreentype1'] == '0'): ?>selected="selected"<?php endif; ?>>无</option>
                                <option value="1" <?php if($order['diagnosischeckgreentype1'] == '1'): ?>selected="selected"<?php endif; ?>>有</option>
                            </select>
                        </div>
                        <label class="col-sm-2 control-label">绿通医生</label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control inline"   name="order[diagnosischeckgreendoctor1]" value="<?php echo $order['diagnosischeckgreendoctor1']; ?>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">检查项目2</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control inline"   name="order[diagnosischeckname2]" value="<?php echo $order['diagnosischeckname1']; ?>"/>
                        </div>
                        <label class="col-sm-1 control-label">绿通</label>
                        <div class="col-sm-2">
                            <select class="form-control" name="order[diagnosischeckgreentype2]" >
                                <option value="0" <?php if($order['diagnosischeckgreentype2'] == '0'): ?>selected="selected"<?php endif; ?>>无</option>
                                <option value="1" <?php if($order['diagnosischeckgreentype2'] == '1'): ?>selected="selected"<?php endif; ?>>有</option>
                            </select>
                        </div>
                        <label class="col-sm-2 control-label">绿通医生</label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control inline"   name="order[diagnosischeckgreendoctor2]" value="<?php echo $order['diagnosischeckgreendoctor2']; ?>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">检查项目3</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control inline"   name="order[diagnosischeckname3]" value="<?php echo $order['diagnosischeckname3']; ?>"/>
                        </div>
                        <label class="col-sm-1 control-label">绿通</label>
                        <div class="col-sm-2">
                            <select class="form-control" name="order[diagnosischeckgreentype3]" >
                                <option value="0" <?php if($order['diagnosischeckgreentype3'] == '0'): ?>selected="selected"<?php endif; ?>>无</option>
                                <option value="1" <?php if($order['diagnosischeckgreentype3'] == '1'): ?>selected="selected"<?php endif; ?>>有</option>
                            </select>
                        </div>
                        <label class="col-sm-2 control-label">绿通医生</label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control inline"   name="order[diagnosischeckgreendoctor3]" value="<?php echo $order['diagnosischeckgreendoctor3']; ?>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">检查项目4</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control inline"   name="order[diagnosischeckname4]" value="<?php echo $order['diagnosischeckname4']; ?>"/>
                        </div>
                        <label class="col-sm-1 control-label">绿通</label>
                        <div class="col-sm-2">
                            <select class="form-control" name="order[diagnosischeckgreentype4]" >
                                <option value="0" <?php if($order['diagnosischeckgreentype4'] == '0'): ?>selected="selected"<?php endif; ?>>无</option>
                                <option value="1" <?php if($order['diagnosischeckgreentype4'] == '1'): ?>selected="selected"<?php endif; ?>>有</option>
                            </select>
                        </div>
                        <label class="col-sm-2 control-label">绿通医生</label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control inline"   name="order[diagnosischeckgreendoctor4]" value="<?php echo $order['diagnosischeckgreendoctor4']; ?>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">检查项目5</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control inline"   name="order[diagnosischeckname5]" value="<?php echo $order['diagnosischeckname5']; ?>"/>
                        </div>
                        <label class="col-sm-1 control-label">绿通</label>
                        <div class="col-sm-2">
                            <select class="form-control" name="order[diagnosischeckgreentype5]" >
                                <option value="0" <?php if($order['diagnosischeckgreentype5'] == '0'): ?>selected="selected"<?php endif; ?>>无</option>
                                <option value="1" <?php if($order['diagnosischeckgreentype5'] == '1'): ?>selected="selected"<?php endif; ?>>有</option>
                            </select>
                        </div>
                        <label class="col-sm-2 control-label">绿通医生</label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control inline"   name="order[diagnosischeckgreendoctor5]" value="<?php echo $order['diagnosischeckgreendoctor5']; ?>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">取结果方式</label>
                        <div class="col-sm-3">
                            <select class="form-control" name="order[diagnosischeckresulttype]" >
                                <option value="0" <?php if($order['diagnosischeckresulttype'] == '0'): ?>selected="selected"<?php endif; ?>>接诊人员代取</option>
                                <option value="1" <?php if($order['diagnosischeckresulttype'] == '1'): ?>selected="selected"<?php endif; ?>>客户自取</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">报告上传</label>
                        <div class="col-xs-12 col-sm-8">
                            <div class="input-group">
                                <input id="c-avatar" data-rule="" class="form-control" size="50" name="order[diagnosisreport]" type="text" value="">
                                <div class="input-group-addon no-border no-padding">
                                    <span><button type="button" id="plupload-avatar" class="btn btn-danger plupload" data-input-id="c-avatar" data-mimetype="image/gif,image/jpeg,image/png,image/jpg,image/bmp" data-multiple="false" data-preview-id="p-avatar"><i class="fa fa-upload"></i> <?php echo __('Upload'); ?></button></span>
                                </div>
                                <span class="msg-box n-right" for="c-avatar"></span>
                            </div>
                            <ul class="row list-inline plupload-preview" id="p-avatar"></ul>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">备注</label>
                        <div class="col-sm-10">
                            <textarea rows="5" cols="90"   name="order[diagnosischeckremark]" ><?php echo $order['diagnosischeckremark']; ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <p class="text-center">
                    <strong style="font-size: 20px;">药物</strong>
                    <input type="radio" value="0" class="toogle" name="medicine"/>无
                    <input type="radio" value="1" class="toogle" name="medicine"/>有
                </p>
                <div class="col-sm-10">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">药物名称及用法</label>
                        <div class="col-sm-10">
                            <textarea rows="5" cols="90"   name="order[diagnosismedicine]" ><?php echo $order['diagnosismedicine']; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">取药方式</label>
                        <div class="col-sm-3">
                                <select class="form-control" name="order[diagnosismedicineresulttype]" >
                                    <option value="0" <?php if($order['diagnosismedicineresulttype'] == '0'): ?>selected="selected"<?php endif; ?>>接诊人员代取</option>
                                    <option value="1" <?php if($order['diagnosismedicineresulttype'] == '1'): ?>selected="selected"<?php endif; ?>>客户自取</option>
                                </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">注意事项</label>
                        <div class="col-sm-10">
                            <textarea rows="5" cols="90"   name="order[diagnosismedicineremark]" ><?php echo $order['diagnosismedicineremark']; ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <p class="text-center">
                    <strong style="font-size: 20px;">住院</strong>
                    <input type="radio" value="0" class="toogle" name="hospital"/>无
                    <input type="radio" value="1" class="toogle" name="hospital"/>有
                </p>
                <div class="col-sm-10">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">住院号</label>
                        <div class="col-sm-2">
                            <input type="text" name="order[diagnosishospitalnum]"  class="form-control inline" value="<?php echo $order['diagnosishospitalnum']; ?>" placeholder="住院号" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-10">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">复查项目/时间</label>
                        <div class="col-sm-10">
                            <textarea rows="5" cols="90"   name="order[diagnosisreview]" ><?php echo $order['diagnosisreview']; ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <p class="text-center">
                    <strong style="font-size: 20px;">评价</strong>
                    <input type="radio" value="0" class="toogle" name="appra"/>无
                    <input type="radio" value="1" class="toogle" name="appra"/>有
                </p>
                <div class="col-sm-10">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">客户/家属对医生评价</label>
                        <div class="col-sm-10">
                            <textarea rows="5" cols="90"   name="order[diagnosiscustomappra]" ><?php echo $order['diagnosiscustomappra']; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">接诊人员对医生评价</label>
                        <div class="col-sm-10">
                            <textarea rows="5" cols="90"   name="order[diagnosispersonappra]" ><?php echo $order['diagnosispersonappra']; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">是否回访</label>
                        <div class="col-sm-2">
                            <select class="form-control" name="order[diagnosisisreview]">
                                <option value="1" <?php if($order['diagnosisisreview'] == '1'): ?>selected="selected"<?php endif; ?>>是</option>
                                <option value="0" <?php if($order['diagnosisisreview'] == '0'): ?>selected="selected"<?php endif; ?>>否</option>
                            </select>
                        </div>
                        <label class="col-sm-2 control-label">就诊结束</label>
                        <div class="col-sm-2">
                            <select class="form-control" name="order[diagnosisover]">
                                <option value="0" <?php if($order['diagnosisover'] == '0'): ?>selected="selected"<?php endif; ?>>否</option>
                                <option value="1" <?php if($order['diagnosisover'] == '1'): ?>selected="selected"<?php endif; ?>>是</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">接诊人员</label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control inline" id="server" value="<?php echo $order['kfsendperson']; ?>" name="order[kfsendperson]" disabled/>
                        </div>
                        <label class="col-sm-2 control-label">填写时间</label>
                        <div class="col-sm-3">
                            <?php if($order['diagnosisaddtime'] == ''): ?>
                                <input type="text" class="form-control inline" id="sendtime" value="" name="order[diagnosisaddtime]" disabled/>
                            <?php else: ?>
                                <input type="text" class="form-control inline" id="sendtime" value="<?php echo date('Y-m-d H:i:s',$order['diagnosisaddtime']); ?>" name="order[diagnosisaddtime]" disabled/>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                    <div class="col-xs-2"></div>
                    <div class="col-xs-12 col-sm-8">
                        <button type="submit" class="btn btn-success btn-embossed">保存</button>
                        <button type="reset" class="btn btn-default btn-embossed" onclick="javascript:window.open('../../../../order/order/exportDiannosisExcel?id=<?php echo $order['id']; ?>');" >导出</button>
                    </div>
                </div>
        </form>
    </div>
    <div role="tabpanel" class="tab-pane" id="messages">
        <form class="form-horizontal"  method="post" action="../../visit">
                <input name="order[id]" value="<?php echo $order['id']; ?>" type="hidden" />
                <div class="form-group">
                <div class="col-sm-10">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">医生服务态度</label>
                        <div class="col-sm-3">
                            <select class="form-control" name="order[visitdoctorattitude]" >           
                                <option value="1" <?php if($order['visitdoctorattitude'] == '1'): ?>selected="selected"<?php endif; ?>>满意</option>
                                <option value="0" <?php if($order['visitdoctorattitude'] == '0'): ?>selected="selected"<?php endif; ?>>不满意</option>
                            </select>
                        </div>
                        <label class="col-sm-3 control-label">医生医技水平</label>
                        <div class="col-sm-3">
                            <select class="form-control" name="order[visitdoctorskill]" >           
                                <option value="1" <?php if($order['visitdoctorskill'] == '1'): ?>selected="selected"<?php endif; ?>>满意</option>
                                <option value="0" <?php if($order['visitdoctorskill'] == '0'): ?>selected="selected"<?php endif; ?>>不满意</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">接诊人员的服务态度</label>
                        <div class="col-sm-3">
                            <select class="form-control" name="order[visitpersonattitude]" >           
                                <option value="1" <?php if($order['visitpersonattitude'] == '1'): ?>selected="selected"<?php endif; ?>>满意</option>
                                <option value="0" <?php if($order['visitpersonattitude'] == '0'): ?>selected="selected"<?php endif; ?>>不满意</option>
                            </select>
                        </div>
                        <label class="col-sm-3 control-label">接诊人员的执业素质</label>
                        <div class="col-sm-3">
                            <select class="form-control" name="order[visitpersonquality]" >           
                                <option value="1" <?php if($order['visitpersonquality'] == '1'): ?>selected="selected"<?php endif; ?>>满意</option>
                                <option value="0" <?php if($order['visitpersonquality'] == '0'): ?>selected="selected"<?php endif; ?>>不满意</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">客户意见</label>
                        <div class="col-sm-7">
                            <textarea rows="10" cols="84" name="order[visitcustomadvice]" ><?php echo $order['visitcustomadvice']; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                            <div class="col-xs-2"></div>
                            <div class="col-xs-12 col-sm-8">
                                <button type="submit" class="btn btn-success btn-embossed">保存</button>
                                <button type="reset" class="btn btn-default btn-embossed"><?php echo __('Reset'); ?></button>
                            </div>
                        </div>
                </div>
            </div>
        </form>
    </div>
</div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="/managers/public/assets/js/require<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js" data-main="/managers/public/assets/js/require-backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js?v=<?php echo $site['version']; ?>"></script>
    </body>
</html>