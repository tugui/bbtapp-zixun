/*
 * @Author: 伟强
 * @Date:   2016-05-27 10:02:24
 * @Last Modified by:   伟强
 * @Last Modified time: 2016-07-18 15:02:12
 */

'use strict';

$(function() {
  var imgUrl;
  var retKey;
  var token;

  /*获取token*/
  $.ajax({
    url: 'php/getToken.php',
    type: 'GET',
    dataType: 'text',
    data: {},

    success: function(argument1,argument2,argument3) {
      console.log(argument1);
      token = argument1;
    },
    error: function() {
      alert("error");
    }
  });


  $(document).ajaxComplete(function() {

    //引入Plupload 、qiniu.js后
    var uploader = Qiniu.uploader({
      runtimes: 'html5,flash,html4', //上传模式,依次退化
      browse_button: 'pickfiles', //上传选择的点选按钮，**必需**
      // uptoken_url: 'php/getToken.php', //若未指定uptoken_url,则必须指定 uptoken ,uptoken由其他程序生成
      
      uptoken: token,
      save_key: true, // 默认 false。若在服务端生成uptoken的上传策略中指定了 `sava_key`，则开启，SDK会忽略对key的处理
      domain: 'o6haukahg.bkt.clouddn.com', //bucket 域名，下载资源时用到，**必需**
      // get_new_uptoken: true, //设置上传文件的时候是否每次都重新获取新的token
      container: 'picUpload', //上传区域DOM ID，默认是browser_button的父元素，
      max_file_size: '100mb', //最大文件体积限制
      flash_swf_url: 'js/plupload/Moxie.swf', //引入flash,相对路径
      max_retries: 3, //上传失败最大重试次数
      dragdrop: true, //开启可拖曳上传
      drop_element: 'picUpload', //拖曳上传区域元素的ID，拖曳文件或文件夹后可触发上传
      chunk_size: '4mb', //分块上传时，每片的体积
      auto_start: true, //选择文件后自动上传，若关闭需要自己绑定事件触发上传
      init: {
        'FilesAdded': function(up, files) {
          plupload.each(files, function(file) {
            // 文件添加进队列后,处理相关的事情
          });
        },
        'BeforeUpload': function(up, file) {
          // 每个文件上传前,处理相关的事情
        },
        'UploadProgress': function(up, file) {
          // 每个文件上传时,处理相关的事情
        },
        'FileUploaded': function(up, file, info) {
          // 每个文件上传成功后,处理相关的事情
          // 其中 info 是文件上传成功后，服务端返回的json，形式如
          // {
          //    "hash": "Fh8xVqod2MQ1mocfI4S4KpRL6D98",
          //    "key": "gogopher.jpg"
          //  }
          // 参考http://developer.qiniu.com/docs/v6/api/overview/up/response/simple-response.html

          var domain = up.getOption('domain');
          var res = JSON.parse(info);
          retKey = res.key;
          console.log('http://' + domain + '/' + res.key);
          imgUrl = 'http://' + domain + '/' + res.key;
          var showPic = imgUrl + '?imageView2/0/w/200';

          $('#uploadDiv').append('<img src="' + showPic + '" alt="封面缩略图"/>');


        },
        'Error': function(up, err, errTip) {
          //上传出错时,处理相关的事情
        },
        'UploadComplete': function() {
          //队列文件处理完毕后,处理相关的事情
        },
        'Key': function(up, file) {
          // 若想在前端对每个文件的key进行个性化处理，可以配置该函数
          // 该配置必须要在 unique_names: false , save_key: false 时才生效

          var key = "";
          // console.log(key);
          // do something with key here
          return key
        }
      }
    });
   



  });



  $('#picUpload').addClass('hidden');

  $('#textType').change(function(event) {
    if (this.value === 'dailySoup') {
      $('#picUpload').addClass('hidden');

    } else if (this.value === 'schoolInformation') {
      $('#picUpload').removeClass('hidden');
    }
  });

  var d = new Date();
  $('#showDay').html('当前日期:' + d.toLocaleDateString());
  var nowDate = d.toLocaleDateString();
  /*摘要字段*/
  // var abstractInput = UE.getEditor('abstract', {
  //   toolbars: [],
  //   initialFrameHeight: 100,
  //   maximumWords: 100,
  // });
  
  /*文章主体*/
  var ue = UE.getEditor('container', {
    toolbars: [['simpleupload']],
    initialFrameHeight: 800,
  });
  /*作者信息*/
  var writerInfo = UE.getEditor('writerInfoContainer', {
    toolbars: [],
    initialFrameHeight: 100,
    maximumWords: 100,
  });
  $('#publicBtn').bind('click', function() {
    // var abstractData = abstractInput.getContent();
    var abstractData = $('#textArea').val();
    var contentData = ue.getContent();
    var writerData = writerInfo.getContent();
    console.log('摘要' + abstractData + '\n内容:' + contentData + '\n作者信息:' + writerData);
    $.ajax({
      url: 'php/filecreate.php',
      method: 'POST',
      dataType: 'text',
      data: {
        'textType': $('#textType').val(),
        'pageTitle': $('#pageTitle').val(),
        'writer': $('#writer').val(),
        'nowDate': nowDate,
        'summary': abstractData,
        'content': contentData,
        'writerData': writerData,
        'picture': imgUrl
      },
      success: function(argument1, argument2, argument3) {
        // alert(argument3.responseText);
        console.log(argument1,argument2,argument3);
        alert(argument1);
      },
      error: function() {
        alert('请求失败');
      },
    });
  });



})