function WeChatApi() {
    var ownerid = document.getElementById("companyid").value;
    var textarea = document.getElementById("message").value;
    var url = "http://www.member3.jqw.com/wx/ashx/Guestbook.ashx?ownerid=" + ownerid + "&textarea=" + textarea;

    $.ajax({
        dataType: 'jsonp',
        type: 'post',
        url: url,
        success: function (data) {
            //alert(data);
        }
    })
}
