function myclick01(data) {
    var strs = new Array(); //定义一数组 
    strs = data.substr(1, data.length).split("&"); //字符分割 
    var sourceUrl = window.location.href;//当前网址
    var proid=strs[0];
    var url = strs[1];//产品网址
    var produce = strs[2];//产品标题
    var XYZ = data.substr(0, 1);
	var imgObj = document.getElementById(XYZ+proid);
    if (getCookieValue("memberid") == null) {
        alert("请登录");
        window.open("http://www.jqw.com/login/login.aspx");
    }
    else {
        var ownerid = getCookieValue("memberid").substring(0, getCookieValue("memberid").indexOf('&'));
        if (XYZ== "X") {//选中
            if (imgObj.getAttribute("src").indexOf("ClickChecked.png")>=0) {
                imgObj.src = imgObj.getAttribute("src").replace("ClickChecked.png", "ClickChecked-1.png"); //更换图片
                //选中之后代码编写处
                alert("你已选中");
            }
            else {
                imgObj.src = imgObj.getAttribute("src").replace("ClickChecked-1.png", "ClickChecked.png"); //更换图片
            }  
        }    else if (XYZ == "Y") {//收藏
    
            if (imgObj.getAttribute("src").indexOf('ClickCollection.png') >= 0 || imgObj.getAttribute("src").indexOf('ClickShoucang.png') >= 0) {
                if (window.confirm('是否收藏')) {
                    imgObj.src = imgObj.getAttribute("src").replace(".png", "-1.png"); //更换图片ClickShoucang-1
                    //选中之后代码编写处
                    $.ajax({
                        type: 'POST',
                        url: "/sp/Favorites.aspx",
                        data: "ownerid=" + ownerid + "&typeid=0&url=" + url + "&sourceUrl=" + sourceUrl + "&title=" + produce + "",
                        success: function () {
                            alert("你已收藏");
                        }
                    });
                  
                }
         
            } else {
                imgObj.src = imgObj.getAttribute("src").replace("ClickCollection-1.png", "ClickCollection.png"); //更换图片
            }
        }
        else if (XYZ == "Z") {//询价
            if (imgObj.getAttribute("src").indexOf('Clickinquiry.png') >= 0) {
                imgObj.src = imgObj.getAttribute("src").replace("Clickinquiry.png", "Clickinquiry-1.png"); //更换图片
                //选中之后代码编写处
                alert("你已询价");
            } else {
                imgObj.src = imgObj.getAttribute("src").replace("Clickinquiry-1.png", "Clickinquiry.png"); //更换图片
            }
        }
    }
  


}
    function readCookie (name)
    {
        var cookieValue = "";
        var search = name + "=";
        if (document.cookie.length > 0)
        {
            offset = document.cookie.indexOf (search);
            if (offset != -1)
            {
                offset += search.length;
                end = document.cookie.indexOf (";", offset);
                if (end == -1)
                    end = document.cookie.length;
                cookieValue = unescape (document.cookie.substring (offset, end))
            }
        }
        return cookieValue;
    }
function myclick02(data) {

var strs = new Array(); //定义一数组 
    strs = data.substr(1, data.length).split("&"); //字符分割 
    var sourceUrl = window.location.href;//当前网址

	 var proid=strs[0];
    var url = strs[1];//产品网址
    var produce = strs[2];//产品标题
    var XYZ = data.substr(0, 1);
	var imgObj = document.getElementById(XYZ+proid);

    if (XYZ == "X") {//选中
        if (imgObj.getAttribute("src").indexOf("ClickChecked.png") >= 0) {
            imgObj.src = imgObj.getAttribute("src").replace("ClickChecked.png", "ClickChecked-1.png"); //更换图片
            //选中之后代码编写处
            alert("你已选中");
        }
        else {
            imgObj.src = imgObj.getAttribute("src").replace("ClickChecked-1.png", "ClickChecked.png"); //更换图片
        }
    }
    else if (XYZ == "Y") {//收藏

        if (imgObj.getAttribute("src").indexOf('ClickShoucang.png') >= 0) {
            if (window.confirm('是否收藏')) {
				imgObj.src = imgObj.getAttribute("src").replace("ClickCollection.png", "ClickCollection-1.png"); //更换图片
				//选中之后代码编写处
				$.ajax({
					type: 'POST',
					url: "/sp/Favorites.aspx",
					data: "ownerid=" + ownerid + "&typeid=0&url=" + url + "&sourceUrl=" + sourceUrl + "&title=" + produce + "",
					success: function () {
						alert("你已收藏");
					}
				});
			  
			}

        } else {
            imgObj.src = imgObj.getAttribute("src").replace("ClickShoucang-1.png", "ClickShoucang.png"); //更换图片
        }
    }
    else if (XYZ == "Z") {//询价
        if (imgObj.getAttribute("src").indexOf('ClickXunjia.png') >= 0) {
            imgObj.src = imgObj.getAttribute("src").replace("ClickXunjia.png", "ClickXunjia-1.png"); //更换图片
            //选中之后代码编写处
            alert("你已询价");
        } else {
            imgObj.src = imgObj.getAttribute("src").replace("ClickXunjia-1.png", "ClickXunjia.png"); //更换图片
        }
    }
}

function allcheck() { 
    var tiheid = document.getElementById('allg');
    var imgs = document.getElementsByName('Ximg');
    if (tiheid.getAttribute("src").indexOf("ClickChecked.png") >= 0) {
        tiheid.src = tiheid.getAttribute("src").replace("ClickChecked.png", "ClickChecked-1.png"); //更换图片
        for (var i = 0; i < imgs.length; i++) {
            imgs[i].src = tiheid.src;
        }
    }
    else {
        tiheid.src = tiheid.getAttribute("src").replace("ClickChecked-1.png", "ClickChecked.png"); //更换图片
        for (var i = 0; i < imgs.length; i++) {
            imgs[i].src = tiheid.src;
        }
    }
}

function Clickallinquiry() {
    var imgs = document.getElementsByName('Ximg');
    var produceid = "";
    for (var i = 0; i < imgs.length; i++) {
        if (imgs[i].getAttribute("src").indexOf("ClickChecked-1.png") >= 0) {
            alert(1);
            produceid += imgs[i].id.substr(1, imgs[i].id.length)+",";
       }
    }
    if (produceid.length > 0) {
        produceid=produceid.substr(0,produceid.length-1);
    }
    alert("选取的产品id分别是："+produceid);
 
}

//获取Cookie
function getCookieValue(cookieName) {
    var cookieValue = document.cookie;
    var cookieStartAt = cookieValue.indexOf("" + cookieName + "=");
    if (cookieStartAt == -1) {
        cookieStartAt = cookieValue.indexOf(cookieName + "=");
    }
    if (cookieStartAt == -1) {
        cookieValue = null;
    }
    else {
        cookieStartAt = cookieValue.indexOf("=", cookieStartAt) + 1;
        cookieEndAt = cookieValue.indexOf(";", cookieStartAt);
        if (cookieEndAt == -1) {
            cookieEndAt = cookieValue.length;
        }
        cookieValue = unescape(cookieValue.substring(cookieStartAt, cookieEndAt));//解码latin-1  
    }
    return cookieValue;
}


