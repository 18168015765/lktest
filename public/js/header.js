/**
@Author: yu_Wj
@Description：header js文件
*/

$(document).ready(function(){
	$(".nav li").mouseover(function(){
		$(this).addClass("normal");
	});
	$(".nav li").mouseout(function(){
		$(this).removeClass("normal");
	});
	$(".nav li").hover(function(){
		$(this).find('nav').stop().fadeToggle("slow");
	});
	$(".nav li nav").eq(0).css("padding","0");
	$(".nav li nav").eq(2).css("width","125px");
	$(".nav li nav").eq(2).css("left","-16px");
});