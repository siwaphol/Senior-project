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
-->
</style>
<script type="text/javascript" src="../js/jquery-1.4.4.min.js"></script>
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

function onSubmit() {
	var msgErr = ""
	if($("#user").val() == ""){	
		msgErr += "กรุณากรอกข้อมูลชื่อผู้ใช้\n"
	}
	if($("#name").val() == ""){	
		msgErr += "กรุณากรอกข้อมูลชื่อ - สกุล\n"
	}
	if($("#pwd").val() == ""){	
		msgErr += "กรุณากรอกข้อมูลรหัสผ่าน\n"
	}else if($("#pwd").val().length != 4){
		msgErr += "ข้อมูลรหัสผ่านต้องเป็นตัวเลข 4 หลักเท่านั้น\n"
	}
	if(msgErr != ""){
		alert(msgErr)
		return false
	}else{
		return true
	}
}

function fixkey(){
	 if(event.keyCode < 48 || event.keyCode > 57) { 
	  event.returnValue = false;
	 }
}

</script>
<br />
<?php

//-------ทำการแก้ไขข้อมูลอาจารย์ผู้สอน		
if( isset($_POST['Submit']) ){
		if(validate_user()){
			editteacher();
		}
}
formedit();

		
function validate_user(){ // ฟังก์ชั่นการตรวจสอบ
	$useredit = $_SESSION['user'];
	
	$user = $_POST['user'];	// ชื่อผู้ใช้งานใหม่
	if($useredit != $user){ 
		$command4 = "select teacherId from teacher where teacherId = '$user' ";
		$result4 = mysql_query($command4);
		if( mysql_num_rows($result4) > 0 ) { // ที่ส่งมาต้องไม่ซ้ำอันเก่าถึงจะตรวจว่าซ้ำคนอื่นไหม?> 
			<div id="checkErr">ชื่อผู้ใช้นี้มีผู้ใช้แล้ว</div><br />
        <?php 
		return false;
		}		
	}
	return true;
}
			  
function editteacher(){ // ฟังก์ชั่นการเพิ่ม
		$teacher = $_SESSION['user']; // ชื่อ user ก่อนที่จะแก้ไข
		$teachname = $_POST['name'];
		$pwd = $_POST['pwd'];
		
		$command2 = "update teacher set teacherName = '$teachname' , teacherPw = '$pwd'  
						where teacherId = '$teacher' ";
		mysql_query($command2); 
		if(mysql_affected_rows() != 0) { 
			?>
		<div id="checkErr">อัพเดทข้อมูลเรียบร้อยแล้ว</div><br />
		<?php
		}else {
		?>
        <div id="checkErr">ไม่สามารถอัพเดทข้อมูลได้</div><br />
              <?php
		}
}
	?>

<?php
function formedit(){

			$teacher = $_SESSION['user'];
			$command = "select * from teacher where teacherId = '$teacher' ";
			$result = mysql_query($command) or die(mysql_error());
			$row = mysql_fetch_array($result);

?>
	  <form action="" method="post" name="form2" id="form2" onsubmit="return onSubmit()">
	  
<br />
<center>
  <span class="style1">แก้ไขข้อมูลอาจารย์ผู้สอน</span>
</center>
<br />
		<table width="500" border="1" align="center" cellpadding="0" cellspacing="0">
		  <tr>
			<td height="31" colspan="3" align="center" bgcolor="#990000"><span class="style2">แบบฟอร์มแก้ไขข้อมูลอาจารย์ผู้สอน</span></td>
		  </tr>
		  <tr>
			<td width="170" align="right" bgcolor="#FFFFFF"><br />ชื่อผู้ใช้ : &nbsp; </td>
			<td colspan="2" bgcolor="#FFFFFF"><br />
			  <input name="user" type="text" id="user" disabled="disabled" value="<?php echo $row['teacherId']; ?>" /> </td>
		  </tr>
		  <tr>
			<td align="right" bgcolor="#FFFFFF"><br />ชื่อ - สกุล : &nbsp;</td>
			<td colspan="2" bgcolor="#FFFFFF"> <br />
				  <input name="name" type="text" id="name"  value="<?php echo $row['teacherName']; ?>" />
			</td>
		  </tr>
		  <tr>
			<td align="right" bgcolor="#FFFFFF"><br />ข้อมูลรหัสผ่าน : &nbsp;</td>
			<td colspan="2" bgcolor="#FFFFFF"><br />
				  <input name="pwd" type="text" id="pwd" onkeypress="fixkey()" value="<?php echo $row['teacherPw']; ?>" maxlength="4" />
				  <input type="button" name="random" value="สุ่มรหัสผ่าน" onclick="Random();"/>
			</td>
		  </tr>
		  <tr>
			<td height="69" colspan="3" bgcolor="#FFFFFF" align="center">
				<input type="submit" name="Submit" value="ตกลง" />
			  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="button" name="cancel" onclick="window.location='index_teacher.php'" value="ยกเลิก" />
			</td>
		  </tr>
		</table>
	  </form>
<?php
}
?>
