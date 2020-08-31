/// <reference path="bigimg.js" />
/// <reference path="gallery.html" />
/// <reference path="gallery.html" />
/// <reference path="gallery.html" />
/// <reference path="gallery.html" />




/// 跨域 读取 数据描述 （存文档）

$(document).ready(function () {
    var orderUrl = 'http://www.category.jqw.com/ashx/jqw_dataxx.ashx?jsoncallback=?';
  var url = jqw_dataxx_url;//"http://img0.jqw.com/2008/03/14/20757/Producttxt/2018050214051820757.txt";// 
  
  
    $.ajax({
        type: "get",
        async: false,
        url: orderUrl,
        dataType: "jsonp",
        data: { "url": url },
        jsonp: "callbackparam",//传递给请求处理程序或页面的，用以获得jsonp回调函数名的参数名(默认为:callback)
        jsonpCallback: "success_jsonpCallback",//自定义的jsonp回调函数名称，默认为jQuery自动生成的随机函数名
        success: function (json) {
         
            //console.log(json.result);
            $("#jqw_dataxx2018").html(json.result);
            //var phpTextdomain = JSON.stringify(json);
            //console.log(phpTextdomain);
            //$("#jqw_dataxx2018").html(phpTextdomain);

      


        },
        error: function () {

        }
    });



});


//$(document).ready(function () {
//    htmlobj = $.ajax({ url: "/script2016/gallery.html", async: false });

//    console.log(htmlobj.responseText);
//    alert(htmlobj.responseText);
//    //$("#myDiv").html(htmlobj.responseText);
//});












//$(document).ready(function () {

//    url = jqw_dataxx_url;//"http://www.category.jqw.com/text/sql.txt";

//    $.ajax({
//        url: "http://www.category.jqw.com/ashx/jqw_dataxx.ashx?jsoncallback=?",
//        dataType: "jsonp",
//        data: { "url": url },

//        //beforeSend: function (XMLHttpRequest) {

//        //    console.log("正在获取，请稍候...");
//        //},
//        success: function (data, textStatus) {
//            //console.log(textStatus);
//            console.log("获取值：" + data.result);
//            //document.write(data.result);
//            $("#jqw_dataxx2018").html(data.result);
//        },
//        //error: function (XMLHttpRequest, textStatus, errorThrown) {

//        //    // console.log("获取出错"); 
//        //}
//    });

//});
