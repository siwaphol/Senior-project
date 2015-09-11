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

<script type="text/javascript" src="../js/form.js"></script>

<?php		
//-------ทำการ insert ค่าเพิ่มข้อมูลนักศึกษาลง database 

$_SESSION['ss_course'] = $_GET['course'];
$_SESSION['ss_sec'] = $_GET['sec'];



if( isset($_POST['Submit']) ){
		include("function/function.php");
		
		$stuid = $_POST['user'];
		$stuname = $_POST['name'];
		$pwd = $_POST['pwd'];
		$course = $_GET['course'];
		$sec = $_GET['sec'];
		// เช็คนักศึกษาว่ามีอยู่ วิชาและตอน นี้ไหม
		$sql = "select studentId from register where studentId='$stuid' and courseId='$course' and sectionId='$sec' ";
		$result = mysql_query($sql);
		
		if(mysql_num_rows($result) == 0) {
			//บันทึกลงตาราง ลงทะเบียน
			$regis = "insert into register (studentId,courseId,sectionId) values ('$stuid','$course','$sec') ";
			mysql_query($regis) or die (mysql_error());
			// บันทึกค่านักศึกษาลงฐานข้อมูล ไม่ให้ซ้ำ
			$command = "insert into student (studentId,studentName,studentPw) values ('$stuid','$stuname','$pwd') ";
			mysql_query($command);
			
			if(mysql_affected_rows() != 0)
				echo "<script>window.location='index_admin.php?student-data'</script>";
			else {?>
				<div id="checkErr">ไม่สามารถเพิ่มข้อมูลได้</div>
			<?php }
		}else {?>
				<div id="checkErr">มีข้อมูลนักศึกษาคนนี้อยู่แล้ว</div>
			<?php }
}
showForm();

	
			
function showForm(){
?>

<form action="" method="post" name="form2" id="form2" onSubmit="return ValidateForm()" >

<br />
<center>
  <span class="style1">เพิ่มข้อมูลนักศึกษา</span>
</center>
<br />

        <table width="500" border="1" align="center" cellpadding="0" cellspacing="0">
                  <tr>
                    <td height="31" colspan="3" align="center" bgcolor="#425969"><span class="style2">แบบฟอร์มเพิ่มข้อมูลนักศึกษา</span></td>
                  </tr>
                  <tr>
                    <td width="170" align="right" bgcolor="#FFFFFF">รหัสนักศึกษา : &nbsp; </td>
                    <td colspan="2" bgcolor="#FFFFFF"><label>
                    <input name="user" type="text" id="code" onKeyPress="fixkey()" maxlength="9" />
                    </label></td>
                  </tr>
                  <tr>
                    <td align="right" bgcolor="#FFFFFF"><br />
                      ชื่อ - สกุล : &nbsp;</td>
                    <td colspan="2" bgcolor="#FFFFFF"><label> <br />
                          <input name="name" type="text" id="name" />
                    </label></td>
                  </tr>
                  <tr>
                    <td align="right" bgcolor="#FFFFFF"><br />
                      ข้อมูลรหัสผ่าน : &nbsp;</td>
                    <td colspan="2" bgcolor="#FFFFFF"><label> <br />
                          <input name="pwd" type="text" id="pwd" onKeyPress="fixkey()" maxlength="4" />
                          <input type="button" name="random" value="สุ่มรหัสผ่าน"  onClick="Random();"/>
                    </label></td>
                  </tr>
                  <tr>
                    <td height="50" colspan="3" bgcolor="#FFFFFF" align="center">
                        <input type="submit" name="Submit" value="ตกลง" />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="button" name="cancel" onClick="window.location='index_admin.php?student-data'" value="ยกเลิก" />
					</td>
                  </tr>
              </table>
</form>
<?php 
}
?>

