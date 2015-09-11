<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Administrator</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
<script type="text/javascript" src="../js/form.js"></script>
<script type="text/javascript">

function onSubmit(){
	var msgErr = ""
	if($("#cid").val() == ""){	//msgErr 
		msgErr = "รหัสวิชาต้องไม่เป็นช่องว่าง \n";
	}else if($("#cid").val().length != 6){
		msgErr += "รหัสวิชาต้องเป็นตัวเลข 6 หลักเท่านั้น\n";
	}
	
	if($("#cname").val() == ""){	//msgErr += "\nชื่อวิชาต้องไม่เป็นช่องว่าง"
		msgErr += "ชื่อวิชาต้องไม่เป็นช่องว่าง";
	}
	
	if(msgErr){
		alert(msgErr)
		return false
	}else{
		return true
	}
}

</script>
</head>
<body>


<?php
require_once("../connect.php");

// check submit
if( isset($_POST['Submit']) ){
	$courseId = $_POST['cid'];
	$courseName = $_POST['cname'];
	$sql="select * from course where courseId='$courseId'";
	$resql=mysql_query($sql) or die("cannot query course".mysql_error());
	$rowcorse=mysql_num_rows($resql);
	echo $rowcorse;
	if ($rowcorse==0) {

		$sqlCourseSec = "INSERT INTO course (courseId, courseName) VALUES ('$courseId', '$courseName') ";
		mysql_query($sqlCourseSec);
		echo "<script>window.location='index_admin.php?subject'</script>";
	}else { ?>
			<div id="checkErr">ไม่สามารถเพิ่มข้อมูลได้</div><br />
		<?php }
	/*include("function/function.php");
	if(checkDuplicate($courseId, "course")) {
		$sqlCourseSec = "INSERT INTO course (courseId, courseName) VALUES ('$courseId', '$courseName') ";
		mysql_query($sqlCourseSec);
		if(mysql_affected_rows() != 0) {
			echo "<script>window.location='index_admin.php?subject'</script>";	 //ตกลง เพิ่มลง db กลับไปหน้า course
		}else { ?>
			<div id="checkErr">ไม่สามารถเพิ่มข้อมูลได้</div><br />
		<?php }
	} */
} 
?>


<form action="" method="post" name="form2" id="form2" onsubmit="return onSubmit()">
<br />
<center>
  <span class="style1">เพิ่มวิชาเปิดสอน</span>
</center>
<br />
        <table id="myTable" width="400" border="1" align="center" cellpadding="0" cellspacing="0">
			<thead>
                  <tr>
                    <td height="31" colspan="2" align="center" bgcolor="#425969"><span class="style2">แบบฟอร์มเพิ่มข้อมูลกระบวนวิชา</span></td>
                  </tr>
			
                  <tr>
                    <td height="50" width="100" align="right" valign="middle" bgcolor="#FFFFFF">
                    รหัสวิชา: &nbsp; </td>
                    <td bgcolor="#FFFFFF">&nbsp;
                    <input name="cid" type="text" id="cid" onkeypress="fixkey()" maxlength="6" />
					</td>
                  </tr>
                  <tr>
                     <td height="50" width="100" align="right" valign="middle" bgcolor="#FFFFFF">
					 ชื่อวิชา : &nbsp;</td>
                    <td bgcolor="#FFFFFF">&nbsp;
                    <input name="cname" type="text" id="cname" size="35" /> 
					</td>
                  </tr>
                  <tr>
                    <td valign="middle" height="50" colspan="2" bgcolor="#FFFFFF" align="center">
                        <input type="Submit" name="Submit" value="ตกลง" id="Submit"/>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="button" name="Cancel" onclick="window.location='index_admin.php?subject'" value="ยกเลิก" />
					</td>
                  </tr>
				  </tfoot>
              </table>
            </form>

</body>
</html>
