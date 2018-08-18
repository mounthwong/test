<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:89:"/usr/share/nginx/html/managers/public/../application/admin/view/product/product/edit.html";i:1528396172;s:73:"/usr/share/nginx/html/managers/application/admin/view/layout/default.html";i:1528075110;s:70:"/usr/share/nginx/html/managers/application/admin/view/common/meta.html";i:1528075104;s:72:"/usr/share/nginx/html/managers/application/admin/view/common/script.html";i:1528075104;}*/ ?>
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
                                <form id="edit-form" class="form-horizontal" role="form" data-toggle="validator" method="POST" action="">
    <div class="form-group">
        <label for="c-group_id" class="control-label col-xs-12 col-sm-2"><?php echo __('Cataglory'); ?>:</label>
        <div class="col-xs-12 col-sm-4">
            <?php echo $catalist; ?>
        </div>
    </div>
    <div class="form-group">
        <label for="c-avatar" class="control-label col-xs-12 col-sm-2"><?php echo __('ProductPic'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <div class="input-group">
                <input id="c-avatar" data-rule="" class="form-control" size="50" name="row[pic]" type="text" value="<?php echo $row['pic']; ?>">
                <div class="input-group-addon no-border no-padding">
                    <span><button type="button" id="plupload-avatar" class="btn btn-danger plupload" data-input-id="c-avatar" data-mimetype="image/gif,image/jpeg,image/png,image/jpg,image/bmp" data-multiple="false" data-preview-id="p-avatar"><i class="fa fa-upload"></i> <?php echo __('Upload'); ?></button></span>
                </div>
                <span class="msg-box n-right" for="c-avatar"></span>
            </div>
            <ul class="row list-inline plupload-preview" id="p-avatar"></ul>
        </div>
    </div>
    <div class="form-group">
        <label for="c-username" class="control-label col-xs-12 col-sm-2"><?php echo __('Name'); ?>:</label>
        <div class="col-xs-12 col-sm-4">
            <input id="c-username" data-rule="required" class="form-control" name="row[name]" type="text" value="<?php echo $row['name']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="remark" class="control-label col-xs-12 col-sm-2"><?php echo __('Info'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <textarea class="editor" id="remark" name="row[intro]"><?php echo $row['intro']; ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="remark" class="control-label col-xs-12 col-sm-2"><?php echo __('价格'); ?>:</label>
        <div class="col-xs-12 col-sm-3">
            <input id="c-username" data-rule="required" class="form-control" name="row[price]" type="text" value="<?php echo $row['price']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="remark" class="control-label col-xs-12 col-sm-2"><?php echo __('折扣价'); ?>:</label>
        <div class="col-xs-12 col-sm-3">
            <input id="c-username" data-rule="required" class="form-control" name="row[discount]" type="text" value="<?php echo $row['discount']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="c-gender" class="control-label col-xs-12 col-sm-2"><?php echo __('Issale'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <?php echo build_radios('row[issale]', ['1'=>__('是'), '0'=>__('否')], '1'); ?>
        </div>
    </div>
<!--     <div class="form-group">
        <label for="c-gender" class="control-label col-xs-12 col-sm-2"><?php echo __('Isshow'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <?php echo build_radios('row[isshow]', ['1'=>__('是'), '0'=>__('否')], '1'); ?>
        </div>
    </div> -->
    <div class="form-group">
        <label for="c-password" class="control-label col-xs-12 col-sm-2"><?php echo __('BuyNum'); ?>:</label>
        <div class="col-xs-12 col-sm-2">
            <input id="c-password" data-rule="" class="form-control" name="row[buynum]" type="text" value="<?php echo $row['buynum']; ?>" placeholder="" autocomplete="new-password" />
        </div>
    </div>
    <div class="form-group">
        <label for="c-password" class="control-label col-xs-12 col-sm-2"><?php echo __('CartNum'); ?>:</label>
        <div class="col-xs-12 col-sm-2">
            <input id="c-password" data-rule="" class="form-control" name="row[cartnum]" type="text" value="<?php echo $row['cartnum']; ?>" placeholder="" autocomplete="new-password" />
        </div>
    </div>
    <div class="form-group">
        <label for="c-password" class="control-label col-xs-12 col-sm-2"><?php echo __('CollectNum'); ?>:</label>
        <div class="col-xs-12 col-sm-2">
            <input id="c-password" data-rule="" class="form-control" name="row[collectnum]" type="text" value="<?php echo $row['collectnum']; ?>" placeholder="" autocomplete="new-password" />
        </div>
    </div>
    <div class="form-group layer-footer">
        <label class="control-label col-xs-12 col-sm-2"></label>
        <div class="col-xs-12 col-sm-8">
            <button type="submit" class="btn btn-success btn-embossed disabled"><?php echo __('OK'); ?></button>
            <button type="reset" class="btn btn-default btn-embossed"><?php echo __('Reset'); ?></button>
        </div>
    </div>
</form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="/managers/public/assets/js/require<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js" data-main="/managers/public/assets/js/require-backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js?v=<?php echo $site['version']; ?>"></script>
    </body>
</html>