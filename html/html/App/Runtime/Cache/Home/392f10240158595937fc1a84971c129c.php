<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>版本列表</title>
        <link rel="stylesheet" type="text/css" href="/public/webuploader//webuploader.css" />
        <link rel="stylesheet" type="text/css" href="/public/webuploader//style.css" />
        <link rel="stylesheet" type="text/css" href="/public/Home//style/manage.css" />
        <script type="text/javascript" src="/public/Home//js/jquery.min.js"></script>
        <script type="text/javascript" src="/public/webuploader//webuploader.js"></script>
        <script type="text/javascript" src="/public/webuploader//uploader.js"></script>
        <script type="text/javascript" src="/public/Home//js/jquery.artDialog.js?skin=default"></script>
        <script type="text/javascript" src="/public/Home//js/iframeTools.js"></script>
        <style>
            #name{text-align:center;list-style-type:none;margin-top:10px;margin-bottom:10px;}
            #name li{display:inline;list-style-type:none;margin-right:10px;}
            li.cur{background-color:blue;color:white;}
        </style>
    </head>
    <body>
        <div class="container">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="box_border">
                <tr>
                    <td class="p5">
                        <div id="wrapper">
                          <div id="uploader" class="wu-example">
                                <div id="thelist" class="uploader-list"></div>
                                <div class="btns">
                                    <div id="picker">选择病人文件</div>
                                    <button id="ctlBtn" class="btn btn-default">开始上传</button>
                                </div>
                            </div>
                        </div>
                        <div class="h10"></div>
                    </td>
                </tr>
            </table>
            <div class="h10"></div>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="box_border">
                <tr>
                    <td class="pl_10" height="42">
                        <input id="export" name="addmedicine" type="button" class="ext_btn ext_btn_submit" value="存储并导出" />&nbsp;
                        <input id="download" name="uploader" type="button" onClick="window.location.href='downloadPatientExcelDemo';" class="ext_btn ext_btn_submit" value="病人药物导入EXCEL模板" />
                        <input id="upload" name="download" type="button" class="ext_btn ext_btn_submit transfer" value="导入"  />
                        <input id="editmedicine" name="editpatmedicine" type="button" class="ext_btn ext_btn_submit editpatmedicine" value="编辑未匹配药品"  />
                        <input id="refresh" name="refresh" type="button" class="ext_btn ext_btn_submit refresh" value="刷新"  />
                    </td>
                </tr>
            </table>
            <ul id="names"></ul>
            <table width="100%" border="0" cellpadding="0" cellspacing="0" class="list_table" id="table_data">
                <tr>
                    <th width="5%" align="center">序号</th>
                    <th width="30%" align="center">病人药物</th>
                    <th width="30%" align="center">匹配药物</th>
                    <th width="10%">数量</th>
                    <th width="10%">金额</th>
                    <th align="left">自费比例</th>
                </tr>
            </table>
            <table width="100%" border="0" cellpadding="0" cellspacing="0" style="display:none" id="table_demo">
                <tr class="tr">
                    <td align="center"></td>
                    <td align="center"></td>
                    <td align="center">
                        <input type="text" id="pwd" name="key" value="" class="input-text lh30 keysearch" size="65" />
                    </td>
                    <td><input type="text" id="pwd" name="pwd" value="" class="input-text lh30" size="5"  /></td>
                    <td><input type="text" id="pwd" name="pwd" value="" class="input-text lh30" size="5"  /></td>
                    <td align="left"><input type="text" id="pwd" name="pwd" value="" class="input-text lh30" disabled="true" size="5"  /></td>
                </tr>
            </table>
        </div>
        <script type="text/javascript">
            ///开始定义全局内容
            var fouce_li_num = -1;///默认没有选择任何下拉内容
            var width_ = 300;//这里设置的是搜索框的宽度，目的为了与下面的列表宽度相同
            var li_color = "#fff";//默认的下拉背景颜色
            var li_color_ = "#CCC";//当下拉选项获取焦点后背景颜色
            var batchids="";
            $(function() {
                $.ajaxSetup({async: false});
                //文件上传插件初始化开始

                $("#upload").click(function() //excel表导入按钮点击事件
                {
                    importExcel();
                });


                $(".keysearch").live("focus",function(event){
                      var keycode = event.keyCode;
                      if(delkeycode(keycode))return;
                      var key_ = $(this).val();//获取搜索值
                      var top_ = $(this).offset().top;//获搜索框的顶部位移
                      var left_ = $(this).offset().left;//获取搜索框的左边位移
                      if(keycode==13){
                            if(fouce_li_num>=0){
                                $(this).val($.trim($("#foraspcn >li:eq("+fouce_li_num+")").text()));
                                fouce_li_num=-1;
                            }
                            $("#foraspcn").hide();
                       }else if(keycode==40){
                            fouce_li_num++;
                            var li_allnum = $("#foraspcn >li").css("background-color",li_color).size();

                            if(fouce_li_num>=li_allnum&&li_allnum!=0){
                                fouce_li_num=0;
                            }else if(li_allnum==0){
                                fouce_li_num--;return;
                            }
                            $("#foraspcn >li:eq("+fouce_li_num+")").css("background-color",li_color_);
                       }else if(keycode==38){
                            fouce_li_num--;
                            var li_allnum = $("#foraspcn >li").css("background-color",li_color).size();
                            if(fouce_li_num<0&&li_allnum!=0){
                                fouce_li_num=li_allnum-1;
                            }else if(li_allnum==0){
                                fouce_li_num++;
                                return;
                            }
                            $("#foraspcn >li:eq("+fouce_li_num+")").css("background-color",li_color_);
                        }else{
                            fouce_li_num=-1;
                            $("#foraspcn").empty();

                            ajax_getdata(key_,$(this));

                            $("#foraspcn").show().css({"top":top_+22,"left":left_,"background-color":"white"});
                        }
                  });

                  $(".keysearch").live("keyup",function(event){
                      var keycode = event.keyCode;
                      if(delkeycode(keycode))return;
                      var key_ = $(this).val();//获取搜索值
                      var top_ = $(this).offset().top;//获搜索框的顶部位移
                      var left_ = $(this).offset().left;//获取搜索框的左边位移
                      if(keycode==13){
                            if(fouce_li_num>=0){
                                $(this).val($.trim($("#foraspcn >li:eq("+fouce_li_num+")").text()));
                                fouce_li_num=-1;
                            }
                            $("#foraspcn").hide();
                       }else if(keycode==40){
                            fouce_li_num++;
                            var li_allnum = $("#foraspcn >li").css("background-color",li_color).size();

                            if(fouce_li_num>=li_allnum&&li_allnum!=0){
                                fouce_li_num=0;
                            }else if(li_allnum==0){
                                fouce_li_num--;return;
                            }
                            $("#foraspcn >li:eq("+fouce_li_num+")").css("background-color",li_color_);
                       }else if(keycode==38){
                            fouce_li_num--;
                            var li_allnum = $("#foraspcn >li").css("background-color",li_color).size();
                            if(fouce_li_num<0&&li_allnum!=0){
                                fouce_li_num=li_allnum-1;
                            }else if(li_allnum==0){
                                fouce_li_num++;
                                return;
                            }
                            $("#foraspcn >li:eq("+fouce_li_num+")").css("background-color",li_color_);
                        }else{
                            fouce_li_num=-1;
                            $("#foraspcn").empty();

                            ajax_getdata(key_,$(this));

                            $("#foraspcn").show().css({"top":top_+22,"left":left_});
                        }
                  });

                  //$("body").click(function(){ $("#foraspcn").hide(); });

                  $("body").append("<div id='foraspcn'></div>");
                  $("#foraspcn").css({"width":""+width_+"px","position":"absolute","z-index":"999","list-style":"none","border":"solid #E4E4E4 1px","display":"none"});

                  $("#export").click(function(){
                    //将数据全部上传到服务器中
                    if(batchids==""){
                        var dloading = art.dialog.alert("EXCEL未导入不能导出");
                        return false;
                    }

                    var arr=[];
                    var tr=$("#table_data").find("td").parents("tr");
                    var i=1;
                    $.each(tr,function(key,val){
                        var temp={};
                        temp.id=i;
                        temp.ind=$(val).find("td").eq(5).find("input").attr("ind").replace(/\"/g, "\\\"");
                        temp.patient_name=$(val).find("td").eq(5).find("input").attr("patient_name").replace(/\"/g, "\\\"");
                        temp.patient_medicine_name=$(val).find("td").eq(1).text().replace(/\"/g, "\\\"");
                        temp.medicine_name=$(val).find("td").eq(2).find("input").val().replace(/\"/g, "\\\"");
                        temp.patient_batchid=$(val).find("td").eq(2).find("input").attr("batchid");
                        temp.patient_medicine_num=$(val).find("td").eq(3).find("input").val().replace(/\"/g, "\\\"");
                        temp.patient_medicine_price=$(val).find("td").eq(4).find("input").val().replace(/\"/g, "\\\"");
                        temp.patient_medicine_rate=$(val).find("td").eq(5).find("input").val().replace(/\"/g, "\\\"");
                        arr.push(temp);
                        i++;
                    })

                    var tempform = document.createElement("form");
                    tempform.action = "exportExcel";
                    tempform.method = "post";
                    tempform.style.display="none"
                    // if(target) {
                    //     tempform.target = target;
                    // }

                    var opt = document.createElement("input");
                    opt.name = "data";
                    opt.value = JSON.stringify(arr);
                    tempform.appendChild(opt);

                    var opt = document.createElement("input");
                    opt.type = "submit";
                    tempform.appendChild(opt);
                    document.body.appendChild(tempform);
                    tempform.submit();
                    document.body.removeChild(tempform);
                    // $.post("exportExcel",{data:JSON.stringify(arr),ran:Math.random()},function(data){
                    //     window.location.href="downloadresult?filename="+data.filename+"&ran="+Math.random();
                    // });
                  })

                  $("#refresh").click(function(){
                    if(batchids==""){
                        var dloading = art.dialog.alert("EXCEL未导入不能进行刷新");
                        return false;
                    }
                    getlist(batchids);
                  })

                  $("#names li").live("click",function(){
                    var key=$(this).attr("bid");
                    $(this).parent().find(".cur").removeClass("cur");
                    $(this).addClass("cur");
                    $(".tr").hide();
                    $("."+key).show();
                  });

                  $("#editmedicine").click(function(){
                      art.dialog.open('editmedicine?batchids=' + batchids+"&ran="+Math.random(), {
                          title: "未匹配药品编辑",
                          width: 650,
                          height: 800,
                          id:"editmedicine",
                          lock: true,
                          opacity: 0.3,
                          button: [
                              {
                                  name: '保存',
                                  callback: function() {
                                      var iframe = this.iframe.contentWindow;
                                      var re = iframe.save();
                                      if (re) {
                                          getlist(batchids);
                                          return true;
                                      }
                                      else {
                                          return false;
                                      }
                                  },
                                  focus: true
                              },
                              {
                                  name: '关闭',
                                  callback: function() {
                                      //$("#gradeid").change();
                                  },
                                  focus: false
                              }
                          ]
                      });
                  });
            });



            function importExcel()
            {
              var filename=$( '.fileuploaders' ).find('p.state').attr("path");
              if(filename==""||filename=='undefined'||filename==undefined){
                art.dialog.tips("请先上传在导入");
                return false;
              }
              var dloading = art.dialog({time: 50, title: '导入中……', width: 130, height: 30, opacity: 0.3, lock: true});
              setTimeout(function(){
                  $.getJSON('importPatient',{filename:filename,random:Math.random()},function(data){
                      dloading.close();
                      //进行batchid的组合
                      var arr=[];
                      $.each(data.batchids,function(k,v){
                        var temp={};
                        temp.id=v;
                        arr.push(temp);
                      });
                      batchids=JSON.stringify(arr);
                      getlist(JSON.stringify(arr));
                  });
                },1000)
              }

              function  getlist(batchid){
                  $("#table_data td").parents("tr").remove();
                  $("#names").empty();
                  var dloadings = art.dialog({time: 30, title: '加载中……', width: 130, height: 30, opacity: 0.3, lock: true});
                  var ind=0;
                  setTimeout(function(){
                    $.getJSON("getpatientlist", {batchid: batchid,random: Math.random()}, function(data) {
                        $.each(data,function(key,values){
                            //上面的li进行展示名字
                            $("#names").append("<li class='' bid='"+key+"' style='width: 60px;text-align: center;float:left;margin-left:10px; cursor:pointer;'>"+values.name+"</li>")
                            var i = 0;
                            $.each(values.data, function(i, val) {
                                i++;
                                var tr = $("#table_demo tr").eq(0).clone();
                                $(tr).addClass(key);
                                var td = tr.children('td').eq(0);
                                td.html(i);
                                td = tr.children('td').eq(1);
                                td.html(val.medicine_name);
                                td = tr.children('td').eq(2);
                                td.find("input").val(val.name);
                                td = tr.children('td').eq(3);
                                td.find("input").val(val.medicine_num);
                                td = tr.children('td').eq(4);
                                td.find("input").val(val.medicine_price);
                                td = tr.children('td').eq(5);
                                td.find("input").val(val.medicine_rate);
                                tr.find("input").attr("bid", val.id);
                                tr.find("input").attr("ind", ind);
                                tr.find("input").attr("batchid", val.patient_batchid);
                                tr.find("input").attr("patient_name", val.patient_name);
                                tr.appendTo("#table_data");
                          });
                          ind=ind+1;
                    });
                    $(".tr:odd").css("background", "#F5F8FA");
                    dloadings.close();
                    $("#names li").eq(0).click();
                  })
                },1000)
            }

            //定义非开始运行函数
            function delkeycode(keycode){
                var array = new Array();
                array =[8,16,19,20,27,33,34,35,36,45,46,91,112,113,114,115,116,117,118,119,120,121,122,123,145,192];
                for(i=0;i<array.length;i++){
                    if(keycode==array[i]){return true;break;}
                }
                return false;
            }

            //这里是正式的ajax调用
            function ajax_getdata(key,obj){
                $.post("findMedicine",{"keyword":key,ran:Math.random()},function(data){
                    data_array = data;
                    for(i=0;i<data_array.length;i++){
                        $("#foraspcn").append("<li style='width:"+width_+"px;' rate='"+data_array[i].medicine_rate+"'>"+data_array[i].medicine_name+"</li>");
                        $("#foraspcn >li").mouseover(function(){$(this).css("background-color",li_color_);});
                        $("#foraspcn >li").mouseout(function(){$(this).css("background-color",li_color);});
                        $("#foraspcn >li").click(function(){
                            $(obj).val($.trim($(this).text()));
                             $(obj).parents("tr").find("input:last").val($(this).attr("rate"));
                            $(this).parent().hide();
                        });
                    }
                });
            }


        </script>
    </body>
</html>