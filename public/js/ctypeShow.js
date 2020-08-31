 //鼠标经过分类显示子类事件
      //onMouseOver
        $(function(){
            var timer=null;		//设置的变量
            $(".linav-img").hover(function(){   //鼠标移上ODiv1，让oDiv2显示
                clearTimeout(timer);	//小技巧
                $(this).parents('li').siblings().find('.linav-down').slideUp();
                $(this).parents('li').addClass("linav2back").siblings().removeClass("linav2back");
                $(".linav-img").parents('li').children('a').removeClass("linav2color");//先清除
                $(this).parents('li').children('a').addClass("linav2color");//在添加
                $(".linav-img").attr("src", "/images2016/jiantu.png");
                $(this).attr("src", "/images2016/jiantu2.png");

                $(this).parents(".linav2").find(".linav-down").slideDown(100);
                $(this).parents(".linav2").css;
    $(this).next().show();

            },function(){
                timer=setTimeout(function(){
                    $(".linav-down").slideUp(100);

                    $(".linav-down").slideUp();
                    $(".linav2").removeClass("linav2back");
                    $(".linav2").find('a').removeClass("linav2color");
                    $(".linav-img").attr("src", "/images2016/jiantu.png");

  $(this).next().hiden();

                },200);
            });

            $(".linav-down").hover(function(){   //鼠标移上oDiv2时，关闭定时器及oDiv2的消失效果
                clearTimeout(timer);
            },function(){
                timer=setTimeout(function(){
                    $(".linav-down").slideUp(100);

                    $(".linav-down").slideUp();
                    $(".linav2").removeClass("linav2back");
                    $(".linav2").find('a').removeClass("linav2color");
                    $(".linav-img").attr("src", "/images2016/jiantu.png");

                },200);
            });
        });

 /*$(function(){  
     $(".linav2").mouseover(function (event) {
         // $(".linav-img").click(function(event){
         var e=window.event || event;
         if(e.stopPropagation){
             e.stopPropagation();
         }else{
             e.cancelBubble = true;
         }
         $(this).siblings().find('.linav-down').slideUp();
         $(this).find('.linav-down').stop().slideToggle();
         $(this).addClass("linav2back").siblings().removeClass("linav2back");
         $('.linav2').children('a').removeClass("linav2color");//先清除
         $(this).children('a').addClass("linav2color");//在添加
         $(".linav-img").attr("src", "/images2016/jiantu.png");
         $(this).find(".linav-img").attr("src", "/images2016/jiantu2.png");
     });
     //$(".linav-down").click(function (event) {
     $(".linav-down").mouseout(function (event) {
         var e=window.event || event;
         if(e.stopPropagation){
             e.stopPropagation();
         }else{
             e.cancelBubble = true;
         }
     });
     document.onmouseover = function () {
         $(".linav-down").slideUp();
         $(".linav2").removeClass("linav2back");
         $(".linav2").find('a').removeClass("linav2color");
         $(".linav-img").attr("src", "/images2016/jiantu.png");
     };
 })*/


 /*$(function(){  
     $(".linav-img").mouseover(function (event) {
         // $(".linav-img").click(function(event){
         var e=window.event || event;
         if(e.stopPropagation){
             e.stopPropagation();
         }else{
             e.cancelBubble = true;
         }
         $(this).parents('li').siblings().find('.linav-down').slideUp();
         $(this).siblings('.linav-down').stop().slideToggle();
         $(this).parents('li').addClass("linav2back").siblings().removeClass("linav2back");
         $(".linav-img").parents('li').children('a').removeClass("linav2color");//先清除
         $(this).parents('li').children('a').addClass("linav2color");//在添加
         $(".linav-img").attr("src", "/images2016/jiantu.png");
         $(this).attr("src", "/images2016/jiantu2.png");
     });
     //$(".linav-down").click(function (event) {
     $(".linav-down").mouseout(function (event) {
         var e=window.event || event;
         if(e.stopPropagation){
             e.stopPropagation();
         }else{
             e.cancelBubble = true;
         }
     });
     document.onmouseover = function () {
         $(".linav-down").slideUp();
         $(".linav2").removeClass("linav2back");
         $(".linav2").find('a').removeClass("linav2color");
         $(".linav-img").attr("src", "/images2016/jiantu.png");
     };
 })*/
