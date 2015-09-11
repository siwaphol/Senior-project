<?php
require_once("../connect.php");
?>
<style type="text/css">
<!--
.style1 {
	font-size: large;
	font-weight: bold;
	color: #0066CC;
}
.style2 {
	color: #FFFFFF;
	font-size: 16px;
	font-weight: bold;
}
input[type=text] {
	width:150px;
}
-->
</style>
<script  language="javascript"> 
function Random() { 
	var rc = "";
	var ubound = 10;
	var lbound = 0;
	var value=0;
	for (var idx = 0; idx < 4; ++idx) {
		value = Math.floor(Math.random() * (ubound - lbound)) + lbound;
		rc = rc + value;
	}
	document.form2.pwd.value = rc;
}

/**
 * DHTML email validation script. Courtesy of SmartWebby.com (http://www.smartwebby.com/dhtml/)
 */

function echeck(str) {
		var at="@"
		var dot="."
		var lat=str.indexOf(at)
		var lstr=str.length
		var ldot=str.indexOf(dot)
		
		if (str.indexOf(at)==-1){
		   return true
		}

		if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
		   return true
		}

		if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){
		    return true
		}

		 if (str.indexOf(at,(lat+1))!=-1){
		    return true
		 }

		 if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
		    return true
		 }

		 if (str.indexOf(dot,(lat+2))==-1){
		    return true
		 }
		
		 if (str.indexOf(" ")!=-1){
		    return true
		 }

 		 return false					
}

function fixkey(){
	 if(event.keyCode < 46 || event.keyCode > 57 || event.keyCode == 47) { 
	  event.returnValue = false;
	 }
}

function ValidateForm(){
	var code = document.form2.user
	var name = document.form2.name
	var pwd = document.form2.pwd
	var email = document.form2.email
	var phone = document.form2.phone
	var err=""
	
	if(code.value == "") {
		err += "กรุณากรอกชื่อผู้ใช้ \n";
		email.focus()
	}
	
	if(name.value == "") {
		err += "กรุณากรอกชื่อ - นามสกุล \n";
		email.focus()
	}
	
	if(pwd.value == "") {
		err += "กรุณากรอกรหัสผ่าน \n";
	}
	
	if (email.value == "") {
		err += "กรุณากรอกอีเมล์ \n";
		email.focus()
	}
	else if (echeck(email.value)) {
		err += "รูปแบบอีเมล์ไม่ถูกต้อง \n";
		email.focus()
	}
	
	if(phone.value == "") {
		err += "กรุณากรอกเบอร์โทรศัพท์ \n";
		email.focus()
	}
	
	if(err != "" )
	{ 
		alert(err)
		return false
	} else
		return true
 }
</script>

<?php
if(isset($_POST['Submit']))
{	
	if( $_POST['Submit'] == "ตกลง" ){
		$taid = $_SESSION['user']; // รหัสนักศึกษาก่อนที่จะแก้ไข
		$ta = $_POST['name']; 
		$pwd = $_POST['pwd'];
		$email = $_POST['email'];
		$phone = $_POST['phone'];
		
		$command2 = "UPDATE ta SET taName = '$ta' , taPw = '$pwd', email = '$email', phone = '$phone' 
		WHERE  taId = '$taid' ";
		mysql_query($command2) or die (mysql_error()); 
		if(mysql_affected_rows() != 0) {
			echo "<script>window.location='index_ta.php'</script>";
		}else { ?>
			<div id="checkErr">ไม่สามารถอัพเดทข้อมูลได้</div>
		<?php }
	}
}
?>
          
<?php 

			$taid = $_SESSION['user'];
			$command = "select * from ta where taId = '$taid' ";
			$result = mysql_query($command);
			$row = mysql_fetch_array($result);
?>
	  <form action="" method="post" name="form2" id="form2" onSubmit="return ValidateForm()" > 
<br />
<center>
  <span class="style1">ข้อมูลส่วนตัวนักศึกษาช่วยสอน</span>
</center>
<br />
		<table width="500" border="1" align="center" cellpadding="0" cellspacing="0">
		  <tr>
			<td height="31" colspan="3" align="center" bgcolor="#990000"><span class="style2">แบบฟอร์มแก้ไขข้อมูลนักศึกษาช่วยสอน</span></td>
		  </tr>
		  <tr>
			<td width="170" align="right" bgcolor="#FFFFFF"><br />ชื่อผู้ใช้ : &nbsp;</td>
			<td colspan="2" bgcolor="#FFFFFF"><br />
			<input name="user" type="text" id="user"  value="<?php echo $row['taId']; ?>" /></td>
		  </tr>
		  <tr>
			<td align="right" bgcolor="#FFFFFF"><br />ชื่อ - สกุล :&nbsp;</td>
			<td colspan="2" bgcolor="#FFFFFF"> <br />
			<input name="name" type="text" id="name" value="<?php echo $row['taName']; ?>" /> </td>
		  </tr>
		  <tr>
			<td align="right" bgcolor="#FFFFFF"><br />ข้อมูลรหัสผ่าน : &nbsp;</td>
			<td colspan="2" bgcolor="#FFFFFF"> <br />
				  <input name="pwd" type="text" id="pwd" onKeyPress="fixkey()" value="<?php echo $row['taPw']; ?>" maxlength="4" />
				  <input type="button" name="random" value="สุ่มรหัสผ่าน" onclick="Random();"/>
			</td>
		  </tr>
		  <tr>
			<td align="right" bgcolor="#FFFFFF"><br />อีเมล์ :&nbsp;</td>
			<td colspan="2" bgcolor="#FFFFFF"> <br />
			<input name="email" type="text" id="email" value="<?php echo $row['email']; ?>" /> </td>
		  </tr>
		  <tr>
			<td align="right" bgcolor="#FFFFFF"><br />เบอร์โทรศัพท์ :&nbsp;</td>
			<td colspan="2" bgcolor="#FFFFFF"> <br />
			<input name="phone" type="text" onKeyPress="fixkey()" id="phone" value="<?php echo $row['phone']; ?>" maxlength="10" /> </td>
		  </tr>
		  <tr>
			<td height="69" colspan="3" bgcolor="#FFFFFF" align="center">
				<input type="submit" name="Submit" value="ตกลง" />
			  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="button" name="cancel" onclick="window.location='index_ta.php'" value="ยกเลิก" />                    
			</td>
		  </tr>
		</table>
	  </form>
    
