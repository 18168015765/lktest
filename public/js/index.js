/**
@Author: yu_Wj
@Description：首页 js文件
*/

/*学习优势*/
$(document).ready(function() {
	$("#s1").xslider({
		unitdisplayed:3,
		movelength:1,
		unitlen:340,
		autoscroll:3000
	});
});

//我们承诺
$(document).ready(function() {
	$(".committed li a").mouseover(function(){
		$(this).find("aside").stop().fadeOut("5000");
	});
	$(".committed li a").mouseout(function(){
		$(this).find("aside").stop().fadeIn("5000");
	});
});

/*热门课程*/
$(document).ready(function() {
	$(".mostPopular li").eq(0).css("margin-top","117px");
	$(".mostPopular li").eq(2).css("margin-top","117px");
	$(".mostPopular li").eq(3).css("margin-right","0");
	$(".mostPopular li a").mouseover(function(){
		$(this).find("aside").stop().fadeOut("5000");
		$(this).find("span").stop().fadeOut("5000");
	});
	$(".mostPopular li a").mouseout(function(){
		$(this).find("aside").stop().fadeIn("5000");
		$(this).find("span").stop().fadeIn("5000");
	});
});

/*学习项目*/
$(document).ready(function() {
	$(".learningRogram li").mouseover(function(){
		$(this).find("aside").stop().fadeOut("5000");
		$(this).find("span").stop().fadeOut("5000");
		$(this).find("article").stop().fadeIn("5000");
	});
	$(".learningRogram li").mouseout(function(){
		$(this).find("aside").stop().fadeIn("5000");
		$(this).find("span").stop().fadeIn("5000");
		$(this).find("article").stop().fadeOut("5000");
	});
});

/*师资力量*/
$(document).ready(function() {
	$(".facultyList li").mouseover(function(){
		$(this).find("article").stop().fadeIn("5000");
	});
	$(".facultyList li").mouseout(function(){
		$(this).find("article").stop().fadeOut("5000");
	});
	$("#s2").xslider({
		unitdisplayed:3,
		movelength:1,
		unitlen:350,
		autoscroll:3000
	});
});

/*开班预告*/
$(function(){
	$("div.list_lh").myScroll({
		speed:30, //数值越大，速度越慢
		rowHeight:26 //li的高度
	});
});

(function($){
	$.fn.myScroll = function(options){
	//默认配置
	var defaults = {
		speed:40,  //滚动速度,值越大速度越慢
		rowHeight:24 //每行的高度
	};
	
	var opts = $.extend({}, defaults, options),intId = [];
	
	function marquee(obj, step){
	
		obj.find("ul").animate({
			marginTop: '-=1'
		},0,function(){
				var s = Math.abs(parseInt($(this).css("margin-top")));
				if(s >= step){
					$(this).find("li").slice(0, 1).appendTo($(this));
					$(this).css("margin-top", 0);
				}
			});
		}
		
		this.each(function(i){
			var sh = opts["rowHeight"],speed = opts["speed"],_this = $(this);
			intId[i] = setInterval(function(){
				if(_this.find("ul").height()<=_this.height()){
					clearInterval(intId[i]);
				}else{
					marquee(_this, sh);
				}
			}, speed);

			_this.hover(function(){
				clearInterval(intId[i]);
			},function(){
				intId[i] = setInterval(function(){
					if(_this.find("ul").height()<=_this.height()){
						clearInterval(intId[i]);
					}else{
						marquee(_this, sh);
					}
				}, speed);
			});
		
		});

	}

})(jQuery);
