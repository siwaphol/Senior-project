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
-->
</style>

<script type="text/javascript" src="../js/form.js"></script>

<?php		
//-------ทำการแก้ไขข้อมูลนักศึกษา

$_SESSION['ss_course'] = $_GET['course'];
$_SESSION['ss_sec'] = $_GET['sec'];

if( isset($_POST['Submit']) ){
	if( $_POST['Submit'] == "ตกลง" ){
		
		$stuid = $_POST['checkCode'];// รหัสนักศึกษาก่อนที่จะแก้ไข
		$code = $_POST['user']; // รหัสนักศึกษาที่แก้ไขใหม่
		$stuname = $_POST['name']; 
		$pwd = $_POST['pwd'];
		include("function/function.php");
		if($stuid == $code || checkDuplicate($code, "student"))
			editStudent($stuid,$code,$stuname,$pwd);
	}
}

function editStudent($stuid,$code,$stuname,$pwd) {
	$command2 = "update student set studentId = '$code' , studentName = '$stuname' , studentPw = '$pwd' 
				where  studentId = '$stuid' ";
	$command3 = "update homework_sending set studentId = '$code' where  studentId = '$stuid' ";
	$command4 = "update register set studentId = '$code' where  studentId = '$stuid' ";
	$result2 = mysql_query($command2) or die (mysql_error()); 
	if(mysql_affected_rows() != 0) {
		mysql_query($command3) or die (mysql_error()); 
		mysql_query($command4) or die (mysql_error()); 
		 echo "<script>window.location='index_admin.php?student-data'</script>";
	} else { ?>
		<div id='checkErr'> &nbsp;ข้อมูลไม่ถูกเปลี่ยนแปลง</div>
	<?php }
}

showForm();


function showForm(){

			$stuid = $_GET['edit'];
			$command = "select * from student where studentId = '$stuid' ";
			$result = mysql_query($command);
			$row = mysql_fetch_array($result);
?>
<form action="" method="post" name="form2" id="form2" onSubmit="return ValidateForm()" >
	  
<br />
<center>
  <span class="style1">แก้ไขข้อมูลนักศึกษา</span>
</center>
<br />
		<table width="500" border="1" align="center" cellpadding="0" cellspacing="0">
		  <tr>
			<td height="31" colspan="3" align="center" bgcolor="#990000"><span class="style2">แบบฟอร์มแก้ไขข้อมูลนักศึกษา</span></td>
		  </tr>
		  <tr>
			<td width="170" align="right" bgcolor="#FFFFFF"><br />รหัสนักศึกษา : &nbsp;</td>
			<td colspan="2" bgcolor="#FFFFFF"><br />
			<input name="user" type="text" id="code" onKeyPress="fixkey()" value="<?php echo $row['studentId']; ?>" maxlength="9"  />
			<input type="hidden" id="checkCode" name="checkCode" value="<?php echo $row['studentId'];?>" />
			</td>
		  </tr>
		  <tr>
			<td align="right" bgcolor="#FFFFFF"><br />ชื่อ - สกุล :&nbsp;</td>
			<td colspan="2" bgcolor="#FFFFFF"> <br />
			<input name="name" type="text" id="name" value="<?php echo $row['studentName']; ?>" /> </td>
		  </tr>
		  <tr>
			<td align="right" bgcolor="#FFFFFF"><br />ข้อมูลรหัสผ่าน : &nbsp;</td>
			<td colspan="2" bgcolor="#FFFFFF"> <br />
				  <input name="pwd" type="text" id="pwd" onKeyPress="fixkey()" value="<?php echo $row['studentPw']; ?>" maxlength="4" />
				  <input type="button" name="random" value="สุ่มรหัสผ่าน" onclick="Random();"/>
			</td>
		  </tr>
		  <tr>
			<td height="69" colspan="3" bgcolor="#FFFFFF" align="center">
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
