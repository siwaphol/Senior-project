<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
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
		if($("#user").val() == ""){	
			$("#userErr").text("กรุณากรอกชื่อผู้ใช้")
			msgErr = true
		}
		if($("#pass").val() == ""){	
			$("#passErr").text("กรุณากรอกรหัสผ่าน")
			msgErr = true
		}
		if($("#status").val() == "0"){	
			$("#statErr").text("กรุณาเลือกสถานะ")
			msgErr = true
		}
		if(msgErr==false){
			$.ajax({
				url: "login1.php",
				type: "POST",
				data: $("form#login").serialize(),
				success: function(data){
					var url = ""
					if(data == "student"){
						url = "student/index_student.php"
					}else if(data == "teacher"){
						url = "teacher/index_teacher.php"
					}else if(data == "ta"){
						url = "ta/index_ta.php"
					}else if(data == "admin"){
						url = "admin/index_admin.php"
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
<title>::ภาควิชาวิทยาการคอมพิวเตอร์::</title>
<meta http-equiv="Content-Language" content="English" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="style.css" media="screen" />
</head>

<body>

<div id="wrap">

<div id="header">

</div>

<div id="content">

<center>
<br />
<h1><font color="#0033CC">ยินดีต้อนรับ</font></h1>
<br /><br />

<h2><font color="#0033CC">กรุณาทำการลงชื่อเข้าใช้ระบบ</font></h2>
<br />
<?php/* include('login_form.php');*/
?>
<form name="formlogin" id="login" action="" method="post" >
<table width="300" height="200" border="1" cellpadding="10" cellspacing="" borderColor=#444444 id="login">
 <tr>
    <td colSpan=2 align="middle" bgColor=#000099><font color=#ffffff><b>หน้าจอการล็อกอินเข้าสู่ระบบ</b></font></td>
</tr>
<tr>
    <td bgColor=#dddddd valign="top" align="right"><b>ชื่อผู้ใช้ : &nbsp;</b></td>
    <td valign="top" align="left">&nbsp;<input name="user" id="user" type="text" size="20" /><br /><font color="#FF0000"><span id="userErr"></span></font></td>
</tr>
<tr>
    <td valign="top" align="right" bgColor=#dddddd><b>รหัสผ่าน : &nbsp;</b></td>
    <td valign="top" align="left">&nbsp;<input name="pass" id="pass" type="password" size="21" maxlength="4" /><br /><font color="#FF0000"><span id="passErr"></span></font></td>
</tr>
<tr>
    <td bgColor=#dddddd valign="top" align="right"><b>สถานะ : &nbsp;</b></td>
    <td valign="top" bgcolor="#FFFFFF" align="left">&nbsp;<select name="select" id="status">
                        <option value="0">สถานะ</option>
						<option value="student">นักศึกษา</option>
                        <option value="teacher">อาจารย์ผู้สอน</option>
                        <option value="admin">ผู้ดูแลระบบ</option>
						<option value="ta">นักศึกษาช่วยสอน</option>
            			</select><br /><font color="#FF0000"><span id="statErr"></span></font>
	</td>
</tr>
<tr>
    <td colSpan=2 align="center"><input type="button" value="Login"  id="submit"></td>
</tr>
</table>
</form>
<br /><br />
<font color="#CCCCCC" size="2"><b>ข้อแนะนำ: การใช้งานให้ได้ผลดีที่สุด ควรเลือกใช้ Internet Explorer Browser</b></font>&nbsp;<img width="20" height="20" src="images/IE.png" /></p>
</center>

</div>

<div id="footer">
<center>
ภาควิชาวิทยาการคอมพิวเตอร์ คณะวิทยาศาสตร์ มหาวิทยาลัยเชียงใหม่
<br /> 239 ถ.ห้วยแก้ว ต.สุเทพ อ.เมือง จ.เชียงใหม่ 50202 โทรศัพท์ 0-5394-3409
<a href="mailto:webmaster@cs.science.cmu.ac.th" target="_blank">webmaster@cs.science.cmu.ac.th</a>
</center>
</div>
</div>

</body>
</html>