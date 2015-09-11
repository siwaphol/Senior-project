<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="style.css" media="screen" />
<title>ลงชื่อเข้าใช้ระบบสำหรับนักศึกษา</title>
</head>
<style type="text/css">
table tr td{ padding-top: 10px; padding-bottom: 10px;
}
</style>
<script src="js/jquery-1.4.4.min.js"  type="text/javascript"></script>
<script type="text/javascript">
$(function(){
	$("input[type=button]#submit").click(function(){
		var msgErr = false
		$("span").text("")
		if($("#user_email").val() == ""){	
			$("#userErr").text("กรุณากรอกอีเมล์")
			msgErr = true
		}
		if($("#user_pass").val() == ""){	
			$("#passErr").text("กรุณากรอกรหัสผ่าน")
			msgErr = true
		}
		//if($("#status").val() == "0"){	
		//	$("#statErr").text("กรุณาเลือกสถานะ")
		//	msgErr = true
		//}
		if(msgErr==false){
			$.ajax({
				url: "login.php",
				type: "POST",
				data: $("form#loginform").serialize(),
				success: function(data){
					alert(data)
					var url = ""
					if(data == "student"){
						url = "student/index_student.php"
					}else {
						alert("กรุณาตรวจสอบข้อมูลให้ถูกต้อง")
					}
					if(url != "")	window.location = url
				},
				error: function(error){
					alert(error.statusText)
				}
			})
		}
	})
})
</script>

<body>
<script type="text/javascript">
//var testecho = $(#login).serialize();
////window.onload = function(){
//var form = document.getElementById('login');
////var data_string = $('form#loginform').serialize();
////alert(data_string);
////}
</script>
<form name="loginform" id="loginform" action="" method="post" >
<table width="300" height="200" border="1" cellpadding="10" cellspacing="" borderColor=#444444 id="logintable">
 <tr>
    <td colSpan=2 align="middle" bgColor=#000099><font color=#ffffff><b>หน้าจอการล็อกอินเข้าสู่ระบบ</b></font></td>
</tr>
<tr>
    <td bgColor=#dddddd valign="top" align="right"><b>อีเมล์ผู้ใช้ : &nbsp;</b></td>
    <td valign="top" align="left">&nbsp;<input name="user_email" id="user_email" type="text" size="20" /><br /><font color="#FF0000"><span id="userErr"></span></font></td>
    <td><bgColor=#dddddd valign="top" align="right"><b>&nbsp; @cmu.ac.th</b></td>
</tr>
<tr>
    <td valign="top" align="right" bgColor=#dddddd><b>รหัสผ่าน : &nbsp;</b></td>
    <td valign="top" align="left">&nbsp;<input name="user_pass" id="user_pass" type="password" size="21"  /><br /><font color="#FF0000"><span id="passErr"></span></font></td>
</tr>
<tr>
    <td colSpan=2 align="center"><input type="button" value="Login"  id="submit"></td>
</tr>
</table>
</form>
</body>
</html>
