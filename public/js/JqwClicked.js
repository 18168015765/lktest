(function(){
    var bp = document.createElement('script');
    var curProtocol = window.location.protocol.split(':')[0];
    if (curProtocol === 'https'){
   bp.src = 'https://zz.bdstatic.com/linksubmit/push.js';
  }
  else{
  bp.src = 'http://push.zhanzhang.baidu.com/push.js';
  }
    var s = document.getElementsByTagName("script")[0];
    s.parentNode.insertBefore(bp, s);
})();

/*
var   ifr=document.createElement("iframe");   
ifr.frameborder="0";   
ifr.scrolling="no";   
ifr.width="0";   
ifr.height="0";   
ifr.src="http://www.china.jqw.com/click.aspx?id="+companyid_No; 
document.body.appendChild(ifr); 
*/
var str=window.location.href;
var reg=/[0-9]{4}\/[0-9]{2}\/[0-9]{2}\/[0-9]{1,10}\//gi;
if(reg.test(str) && str.indexOf("user.jqw.com")<0){
	if(str.indexOf("out.jqw.com")>0){
		
	}
	else{
		window.location.href=str.replace(reg,"");
	}
}
//document.write("<div style=\"display:none\"><img src=\"http://www.jqw.com/clickdemo.aspx?id="+companyid_No+"\"></div>");

function getCookie(cookie_name) {
    var allcookies = document.cookie;     //��ȡҳ�������Cookie
    var cookie_pos = allcookies.indexOf(cookie_name);
    // ����ҵ����������ʹ���cookie���ڣ�
    // ��֮����˵�������ڡ�
    if (cookie_pos != -1) {
        // ��cookie_pos����ֵ�Ŀ�ʼ��ֻҪ��ֵ��1���ɡ�
        cookie_pos += cookie_name.length + 1;
        var cookie_end = allcookies.indexOf(";", cookie_pos);
        if (cookie_end == -1) {
            cookie_end = allcookies.length;
        }
        var value = unescape(allcookies.substring(cookie_pos, cookie_end));
    }
    return value;
}

/*
window.onload = function () {
 
    var urll = window.location.href;//��ǰҳ��
    x = screen.width;//��
    y = screen.height;//��
    var r = x + "*" + y;
    var syy = document.referrer;
    $.ajax({
        url: 'http://www1.jqw.com/hrx/Handler1.ashx',
        type: 'get',
        dataType: 'jsonp',
        jsonp: 'jsoncallback',
        data: "wh=" + r + "&syy=" + syy + "&u=" + companyid_No + "&zyy=" + urll
    })
}
*/

