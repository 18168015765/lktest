 document.write("<style type=\"text/css\" media=\"all\">");
 document.write("@import url("+m_online.css+");");
  document.write("</style>");
var LorR='0';
$(function () {
       
  if (m_online.status!='1'){ //1=left
		LorR=document.body.clientWidth-161;
  }
 })

  document.write("<div id=\"divStayTopLeftOnline\" style=\"position:absolute;Z-index:99999;width:161px;top:200px;left:"+LorR+"px;\" >");
  document.write("<table cellSpacing=\"0\" cellPadding=\"0\"  border=\"0\" id=\"qqtabOnline\">");
  document.write("<tr>");
  if (m_online.status=='1'){
		document.write("<td></td>");
  }
  document.write("<td class=\"topline\">");

  document.write("</td>");
  if (m_online.status!='1'){
		document.write("<td></td>");
  }
  document.write("</tr>");
  document.write("<tr>");
  if (m_online.status=='1'){
		document.write("<td><div class=\"left\"  onclick=\"onlineHidden()\">&nbsp;</div></td>");
  }
  document.write("<td class=\"content\" align=\"center\">");
  //document.write("<div  class=\"tdzaixiankefu\">online</div>");

document.write("<div style=\"line-height:10px;\">&nbsp;</div>");
for(var i=0;i<m_online.type.length;i++){

	
	switch(m_online.type[i])
	{
		case "0":
			document.write("<div class=\"content_action\" >");
			document.write("<a target=blank href='tencent://message/?uin="+m_online.value[i]+"&Site=abc&Menu=yes'><img border=\"0\" SRC='http://wpa.qq.com/pa?p=1:" + m_online.value[i]+":1' alt=\"click send me Message\" /></a>");
		document.write("</div>");
		break;
		case "1":
			document.write("<div class=\"content_action\" >");
			document.write("<a href='msnim:chat?contact=" + m_online.value[i]+ "' target=blank><img src='http://www.china.jqw.com/images/msn.gif' border=0 /></a>");
			document.write("</div>");
			break;
		case "2":
		
			document.write("<a href=\"skype:" + m_online.value[i] + "?call\"><img src=\"http://download.skype.com/share/skypebuttons/buttons/call_blue_white_124x52.png\" style=\"border: none;\" width=\"100\" height=\"32\" alt=\"Skype Me?!\" /></a>");
document.write("</div>");
			break;
	 case "3":
		 document.write("<div class=\"content_action\" >");
			document.write("<a target='_blank' href='http://wpa.qq.com/msgrd?V=1&Uin=" + m_online.value[i] + "&Exe=TT&Site=Simplelife&Menu=yes'>");
			document.write("<img border=0 src='http://wpa.qq.com/pa?p=4:" + m_online.value[i] + ":3' border=0 />");
			document.write("</a>");
document.write("</div>");
		break;
	case "4":
		document.write("<div class=\"content_action\" >");
			document.write("<a href=\"http://edit.yahoo.com/config/send_webmesg?.target=" +m_online.value[i]+ "&.src=pg\" target=\"_blank\">");
			document.write("<img border=0   src=\"http://opi.yahoo.com/online?u=" +m_online.value[i]+"&m_online=g&t=1&l=cn\"/></a>");
			document.write("</div>");
	 break;

	case "5":
		document.write("<div class=\"content_action\" >");
		document.write("<a href=\"http://amos.im.alisoft.com/msg.aw?v=2&uid=" + m_online.value[i] + "&site=cntaobao&s=1&charset=gb2312\" target=\"_blank\">");
		document.write("<img border=0   src=\"http://amos.im.alisoft.com/online.aw?v=2&uid=" +m_online.value[i] + "&site=cntaobao&s=1&charset=gb2312\"/></a>");
		document.write("</div>");
		break;
	case "6":
		document.write("<div class=\"content_action\" >");
		document.write("<a href=\"http://amos.im.alisoft.com/msg.aw?v=2&uid=" + m_online.value[i] + "&site=cnalichn&s=1&charset=gb2312\" target=\"_blank\">");
		document.write("<img border=0   src=\"http://amos.im.alisoft.com/online.aw?v=2&uid=" +m_online.value[i] + "&site=cnalichn&s=1&charset=gb2312\"/></a>");
		document.write("</div>");
		break;

	case "7":
		document.write("<div class=\"content_action\" >");
			document.write("<div class=\"freebg\">Free Phone</div>");

			document.write("<div class=\"free_phone\">"+m_online.value[i]+"</div>");
			document.write("</div>");
		break;
		case "8":
		document.write("<div class=\"content_action\" >");
			document.write("<div class=\"freebg\">Free Phone</div>");

			document.write("<div class=\"free_phone\">"+m_online.value[i]+"</div>");
			document.write("</div>");
		break;
	case "9":
			 var qrurl = "http://qrcode.jqw.com/qrcode.aspx?web=" + m_online.value[i] + "&size=3";
            if (m_online.QR != "undefined"&&m_online.QR !=null)
            {
                qrurl = m_online.QR;
            }
            //console.log(qrurl);
            document.write("<div style=\"text-align:left;padding-left:18px;margin-top: 18px;\"><img border=0  src=\"" + qrurl + "\" width=\"75px\"/></div>");

		break;
	case "10":
		document.write("<div class=\"content_action\" >");
		document.write("<div class=\"freebg\">400 Phone</div>");

		document.write("<div class=\"free_phone\">"+m_online.value[i]+"</div>");
		document.write("</div>");
		break;
	case "11":
		document.write("<div class=\"content_action\" >");
		document.write("<div class=\"freebg\">微信号</div>");

		document.write("<div class=\"free_phone1\">"+m_online.value[i]+"</div>");
		document.write("</div>");
	default:
			break;

	}
	
	
}
	
	//document.write("</div>");

	document.write("</td>");
	if (m_online.status!='1'){
		document.write("<td><div class=\"left\"  onclick=\"onlineHidden()\">&nbsp;</div></td>");
	}
	
	document.write("</tr>");

	document.write("<tr>");
	if (m_online.status=='1'){
		document.write("<td></td>");
	}
	document.write("<td class=\"bottomline\"></td>");
	if (m_online.status!='1'){
		document.write("<td></td>");
	}
	document.write("</tr>");
	document.write("</table>");


document.write("</div>");

var verticalpos="";
function scrollqq()
{
	var posXqq,posYqq;
	if (window.innerHeight)
	{
		posXqq=window.pageXOffset;
		posYqq=window.pageYOffset;
	}
	else if (document.documentElement && document.documentElement.scrollTop)
	{
		posXqq=document.documentElement.scrollLeft;
		posYqq=document.documentElement.scrollTop;
	}
	else if (document.body)
	{
		posXqq=document.body.scrollLeft;
		posYqq=document.body.scrollTop;
	}
	var divStayTopLeftOnline=document.getElementById('divStayTopLeftOnline');
	divStayTopLeftOnline.style.top=(posYqq+200)+"px";
	
	if(m_online.status!=1){
		if(document.getElementById('divStayTopLeftOnline').style.width=='161px'){
			document.getElementById('divStayTopLeftOnline').style.left=posXqq+LorR+"px";
		}
		else {
			document.getElementById('divStayTopLeftOnline').style.left=posXqq+LorR+134+"px";
		}
	}
	else{
		divStayTopLeftOnline.style.left=posXqq+LorR+"px";
	}
	setTimeout("scrollqq()",500);
}
scrollqq();

function onlineHidden(){
	var posXqq,posYqq;
	if (window.innerHeight)
	{
		posXqq=window.pageXOffset;
		posYqq=window.pageYOffset;
	}
	else if (document.documentElement && document.documentElement.scrollTop)
	{
		posXqq=document.documentElement.scrollLeft;
		posYqq=document.documentElement.scrollTop;
	}
	else if (document.body)
	{
		posXqq=document.body.scrollLeft;
		posYqq=document.body.scrollTop;
	}
	
	if(m_online.status!=1){
		
		if(document.getElementById('divStayTopLeftOnline').style.width=='161px'){
			document.getElementById('divStayTopLeftOnline').style.width=27+'px';
			document.getElementById('divStayTopLeftOnline').style.left=posXqq+LorR+134+"px";
			document.getElementById('qqtabOnline').style.marginLeft=-134+"px";
		}
		else {
			document.getElementById('divStayTopLeftOnline').style.width=161+'px';
			document.getElementById('divStayTopLeftOnline').style.left=posXqq+LorR+"px";
			document.getElementById('qqtabOnline').style.marginLeft=0+"px";
		}
	}
	else{
	
		if(document.getElementById('divStayTopLeftOnline').style.width=='161px'){
			document.getElementById('divStayTopLeftOnline').style.width=27+'px';
		}
		else {
			document.getElementById('divStayTopLeftOnline').style.width=161+'px';
		}
	}
       
}