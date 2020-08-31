
///***点击切换热销与热门收藏
$(function () {
    $(".sell_sp1").css('background', '#5d8b33');
    $(".collection_sp2").css('background', '#ffffff');

    $(".sell_sp1").live("click", function () {
        $(".sellsp").removeClass('tageA');
        $(".collectionsp").addClass('tageA');
        $(this).css('background', '#5d8b33');
        $(".collection_sp2").css('background', '#ffffff');

    });

    $(".collection_sp2").live("click", function () {

        $(".collectionsp").removeClass('tageA');
        $(".sellsp").addClass('tageA');
        $(this).css('background', '#5d8b33');
        $(".sell_sp1").css('background', '#ffffff');
    }); //js 获取
});


///***点击切换热销与热门收藏  ***结束///
$(function () {
    $(".Design_cent ul li div").bind("mouseover", function () {
        var url = $(this).attr('alt');
        var id = $(this).attr('id');
        var name = $(this).attr('name');
        $(".Design_min a").html("<img src='" + url + "'>");
        $(".Design_min div").html("<div><b class=\"serial_number\">"+id+"</b></div><div class=\"desfot_rig\"><p class=\"p1\"><span class=\"goods_name\">"+name+"</span></p></div>");
    })
})
