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
<script  language="javascript" src="../js/form.js"></script>

<?php
//-------ทำการแก้ไขข้อมูลอาจารย์ผู้สอน		
if( isset($_POST['Submit']) ){
	$useredit = $_GET['edit']; // ชื่อ user ก่อนที่จะแก้ไข
	$teachuser = $_POST['user'];// ชื่อ user ที่ต้องการแก้ไข
	$teachname = $_POST['name'];
	$pwd = $_POST['pwd'];
	include("function/function.php");
	if($useredit == $teachuser || checkDuplicate($teachuser,'teacher')) {
		editteacher($useredit,$teachuser,$teachname,$pwd);
	}
}


formedit();
			  
function editteacher($useredit,$teachuser,$teachname,$pwd){ // ฟังก์ชั่นการเพิ่ม
		
		$command2 = "update teacher set teacherId = '$teachuser' , teacherName = '$teachname' , teacherPw = '$pwd'  
						where teacherId = '$useredit' ";
		$command3 = "update course_section set teacherId = '$teachuser' where teacherId = '$useredit' ";
		mysql_query($command2);
		if(mysql_affected_rows() != 0) {
			mysql_query($command3); 
			echo "<script>window.location='index_admin.php?teacher-data'</script>";
		}else { ?>
			<div id='checkErr'> &nbsp;ข้อมูลไม่ถูกเปลี่ยนแปลง</div>
		<?php
		 }
			
}
	?>

<?php
function formedit(){

			$teacher = $_GET['edit'];
			$command = "select * from teacher where teacherId = '$teacher' ";
			$result = mysql_query($command);
			$row = mysql_fetch_array($result);
?>
<form action="" method="post" name="form2" id="form2" onSubmit="return ValidateForm()" >
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
			  <input name="user" type="text" id="user" value="<?php echo $row['teacherId']; ?>" /> </td>
		  </tr>
		  <tr>
			<td align="right" bgcolor="#FFFFFF"><br />ชื่อ - สกุล : &nbsp;</td>
			<td colspan="2" bgcolor="#FFFFFF"> <br />
				  <input name="name" type="text"  value="<?php echo $row['teacherName']; ?>" />
			</td>
		  </tr>
		  <tr>
			<td align="right" bgcolor="#FFFFFF"><br />ข้อมูลรหัสผ่าน : &nbsp;</td>
			<td colspan="2" bgcolor="#FFFFFF"><br />
				  <input name="pwd" type="text" value="<?php echo $row['teacherPw']; ?>" />
				  <input type="button" name="random" value="สุ่มรหัสผ่าน" onclick="Random();"/>
			</td>
		  </tr>
		  <tr>
			<td height="69" colspan="3" bgcolor="#FFFFFF" align="center">
				<input type="submit" name="Submit" value="ตกลง" />
			  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="button" name="cencel"  onclick="window.location='index_admin.php?teacher-data'" value="ยกเลิก" />
			</td>
		  </tr>
		</table>
</form>
<?php
}
?>
