
    $("#Button1").click(function () {
        document.getElementById('companys').value = "";
        document.getElementById('cellphone').value = "";
        document.getElementById('address').value = "";
        document.getElementById('message').value = "";
        document.getElementById('Text1').value = "";
    });    $("#Button2").click(function () {
        document.getElementById('companys').value = "";
        document.getElementById('cellphone').value = "";
        document.getElementById('email').value = "";
        document.getElementById('QQ').value = "";
        document.getElementById('contacter').value = "";
        document.getElementById('message').value = "";
        document.getElementById('Text1').value = "";
    });function submitGest() {
    if (companys.value == "" || cellphone.value == "" || contacter.value == "" || message.value == "" || Text1.value == "") {
        alert("请输入完整");
    }
    else {     
        formid.action = "sp/gbook.aspx";
        formid.method = "post";
        formid.target = "_blank";
        formid.submit();
        WeChatApi();
    }
}
function reftchBar() { var a = document.getElementById("imgBarCode"); a.src = a.src + "?" + Math.random(); }
