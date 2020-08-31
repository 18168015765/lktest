jQuery(document).ready(function($){
	// browser window scroll (in pixels) after which the "back to top" link is shown
	// 浏览器窗口滚动（以像素为单位），之后显示“返回顶部”链接
	var offset = 1000,
		//duration of the top scrolling animation (in ms)
		//顶部滚动动画的持续时间（以毫秒为单位）
		scroll_top_duration = 500,
		//grab the "back to top" link
		//抓取“返回顶部”链接
		$back_to_top = $('.cd-top');
	//hide or show the "back to top" link
	//隐藏或显示“回到顶部”链接
	$(window).scroll(function(){
		( $(this).scrollTop() > offset ) ? $back_to_top.addClass('cd-is-visible') : $back_to_top.removeClass('cd-is-visible');
	});
	//smooth scroll to top
	//平滑滚动到顶部
	$back_to_top.on('click', function(event){
		event.preventDefault();
		$('body,html').animate({
			scrollTop: 0 ,
		 	}, scroll_top_duration
		);
	});
});

/**
 * 内页导航
*/
$(document).ready(function() {
	$(".pageNav li").eq(0).css("border-left","1px solid #d6d6d6");
});

$(document).ready(function(){
	//限制字符个数
	$(".char_limit span a").each(function(){
		var maxwidth=30;
		if($(this).text().length>maxwidth){
		$(this).text($(this).text().substring(0,maxwidth));
		$(this).html($(this).html()+"...");
		}
	});
});