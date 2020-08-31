$(function() {
    var bannerSlider = new Slider($('#banner_tabs'), {
        time: 5000,
        delay: 400,
        event: 'hover',
        auto: true,
        mode: 'fade',
        controller: $('#bannerCtrl'),
        activeControllerCls: 'active'
    });
})
////---------为window.onload增加函数-----------
//function addLoadEvent(func) {
//var oldonload=window.onload;
//if(typeof window.onload!="function") {
//  window.onload=func;
//} else {
//  window.onload=function() {
//    oldonload();
//    func();
//  }
//}
//}
////---------为某个元素添加类-------------
//function addClass(element,value) {
//if(!element.className) {
//  element.className=value;
//} else {
//  var classNames=element.className.split(" ");
//  for(var i=0; i<classNames.length; i++) {
//    if(classNames[i]==value) return null;
//  }
//  element.className+=" ";
//  element.className+=value;
//}
//}
//
////---------为某个元素删除类-------------
//function removeClass(element,value) {
//if(!element.className||element.className==value) {
//  element.className="";
//} else {
//  var classNames=element.className.split(" ");
//  for(var i=0; i<classNames.length; i++) {
//    if(classNames[i]==value) classNames.splice(i,1);
//  }
//  element.className=classNames.join(" ");
//}
//}
//
////---------主角：轮播图函数-------------
//function slideShow() {
//var slides=document.getElementById("slideshow"),
//imgs=slides.getElementsByTagName("img"),  //得到图片们
//pages=slides.getElementsByTagName("span"),  //得到页码们
//index=0;  //index为当前活跃的图片、页码、描述的编号
//
//imgs[1].style.position = 'absolute';
//imgs[2].style.position = 'absolute';
//imgs[3].style.position = 'absolute';
//
//pages[0].style.backgroundImage = "url(style/images/banner_01.png)";
//pages[1].style.backgroundImage = "url(style/images/banner_02.png)";
//pages[2].style.backgroundImage = "url(style/images/banner_03.png)";
//pages[3].style.backgroundImage = "url(style/images/banner_04.png)";
//
//addClass(imgs[0],"active");  //初始化
//addClass(pages[0],"active");
//
//function changeSlide() {  //切换图片的函数
//  for(var i=0; i<imgs.length; i++) {
//    if(index==i) {
//      addClass(imgs[i],"active");  //添加active类
//      addClass(pages[i],"active");
//    } else {
//      removeClass(imgs[i],"active");  //移除active类
//      removeClass(pages[i],"active");
//    }
//  }
//  index=index+1;  //index自增1，这样下次要显示的图片就变了
//  if(index>=4) index=0;
//}
//
//var slideon=setInterval(changeSlide,3000);  //每2s调用changeSlide函数进行图片轮播
//
//for(var i=0; i<pages.length; i++) {  //定义鼠标移入和移除页码事件
//  pages[i].onmouseover=function(){
//    clearInterval(slideon);  //当鼠标移入时清除轮播事件
//    index=this.innerHTML-1;  //得到当前鼠标指的页码
//    changeSlide(); 
//  }
//  pages[i].onmouseout=function(){
//    slideon=setInterval(changeSlide,5000);  //当鼠标移出时重新开始轮播事件
//  }
//}
//}
//addLoadEvent(slideShow);