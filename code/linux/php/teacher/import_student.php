<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
<script language = "JavaScript">
<?php
require('../connect.php');
?>

function onSubmit() {
	var msgErr = ""
	if(document.frmMain.ddlCourse.value == "") {
		msgErr += "กรุณาเลือกวิชา \n" 
	}
	if($("#ddlSection").val() == "") {
		msgErr += "กรุณาเลือกตอน \n"
	}
	if(document.frmMain.fileupload.value == "") {
		msgErr += "คุณยังไม่ได้ใส่ลิงค์ไฟล์ในการอัพโหลด \n"
	}else {
		var myArr = document.frmMain.fileupload.value.split(".")
		var ext = myArr[myArr.length - 1]
		if(ext != "xls" )
			msgErr += "กรุณาเลือกไฟล์นักศึกษา excel(.xls) เท่านั้น!! \n"
	}
	if(msgErr != "") {
		alert(msgErr)
		return false;
	}else
		return true;
}

//**** List Section (Start) ***//
function ListSection(SelectValue)
{
frmMain.ddlSection.length = 0
//*** Insert null Default Value ***//
var myOption = new Option('เลือกตอน','') 
frmMain.ddlSection.options[frmMain.ddlSection.length]= myOption
<?php

//แก้ไขตอนไม่ออก
$intRows = 0;
$teach = $_SESSION['user'];
$strSQL = "SELECT courseId,sectionId FROM course_section WHERE teacherId ='$teach' ORDER BY sectionId ASC ";
$objQuery = mysql_query($strSQL) or die ("Error Query [".$strSQL."]");
while($objResult = mysql_fetch_array($objQuery))
{
$intRows++;
?>
x = <?php echo $intRows;?>;
mySubList = new Array();
strGroup = <?php echo $objResult["courseId"];?>;
strValue = "<?php echo $objResult["sectionId"];?>";
mySubList[x,0] = strGroup;
mySubList[x,1] = strValue;
if (mySubList[x,0] == SelectValue){
var myOption = new Option(mySubList[x,1]) 
frmMain.ddlSection.options[frmMain.ddlSection.length]= myOption                  
}
<?php
}
?>                                                                  
}
//**** List Section (End) ***//
</script>
</head>

<body>
<br />
<center>
  <span class="style1">นำเข้าข้อมูลนักศึกษา</span>
</center>
<br />

<?php 
if(isset($_POST['ok2'])){
	if($_POST['ok2'] == "ตกลง"){
	}
} 
?>			
<?php
function importdata(){
	require_once '../read-excel/reader.php';
	$reader = new Spreadsheet_Excel_Reader();
	$reader->setOutputEncoding('utf-8');
	$reader->setUTFEncoder('iconv');

	$fileupload = $_FILES['fileupload']['tmp_name'];		
	$fileupload_name=$_FILES['fileupload']['name'];
	copy($fileupload,$fileupload_name);

			$reader->read("$fileupload_name");	
			require("../connect.php");
			$course = $_POST['ddlCourse'];
			$sec = $_POST['ddlSection'];
			$stu="select studentId,studentName  from student";
			$studentcheck=mysql_query($stu);
			$reg="select studentId,courseId from register";
			$regischeck=mysql_query($reg);
			/*while($objstudentcheck = mysql_fetch_array($studentcheck))
						{
						?>
						<?php 	$stuid=$objstudentcheck["studentId"];
							$stuname=$objstudentcheck["studentName"];
						*/?>						
					
						<?php
			for ($i = 8; $i <= $reader->sheets[0]["numRows"] && $reader->sheets[0]["cells"][$i][2] != ""; $i++) {
				$code =$reader->sheets[0]["cells"][$i][3];
				$fullname =$reader->sheets[0]["cells"][$i][4];
				 // ค่าเลข random
			  	$pwd = random(4);
			  	$regisstatus=0;
				while($objregischeck = mysql_fetch_array($regischeck))
						{						
						 	$stuid=$objregischeck["studentId"];
						 	$courseregisid=$objregischeck["courseId"];

							if($stuid!=$code && $courseregisid!=$course){
								$regisstatus=1; //เมื่อยังไม่เคยลงทะเบียน
								}elseif ($stuid==$code && $courseregisid != $course) {
										$regisstatus=2;  // ลงทะเบียนเพิ่ม
								}
							}					
						
				// ลงทะเบียนนักศึกษา
				if ($regisstatus==1) {
					$regis = "insert into register (studentId,courseId,sectionId) values ('$code','$course','$sec') ";
					mysql_query($regis);
					
					$command = "insert into student (studentId,studentName,studentPw) values ('$code','$fullname','$pwd') ";
					mysql_query($command);
					
					}elseif ($regisstatus==2) {
						$regis = "insert into register (studentId,courseId,sectionId) values ('$code','$course','$sec') ";
						mysql_query($regis);
				

				}
				/*$regis = "insert into register (studentId,courseId,sectionId) values ('$code','$course','$sec') ";
				mysql_query($regis);
				
				$command = "insert into student (studentId,studentName,studentPw) values ('$code','$fullname','$pwd') ";
				mysql_query($command);*/
			}
			unlink ($fileupload_name);
			mysql_close();
?>
     <div id="checkErr">ระบบได้ทำการนำเข้าข้อมูลนักศึุกษาเรียบร้อยแล้ว</div><br /><br />
<?php
}

formimport();

if(isset($_POST['ok'])){
	importdata();
	showimport();
}


function formimport(){
?>
		<?php
		require('../connect.php');
		?>
            <form action="" method="post" enctype="multipart/form-data" name="frmMain" id="frmMain" onsubmit="return onSubmit()">
              <table width="400" height="150" border="1" align="center" cellpadding="0" cellspacing="0">
                <tr>
                  <td height="30" colspan="2" align="center" bgcolor="#425969"><span class="style2">กรุณาเลือกไฟล์นำเข้าข้อมูลนักศึกษา</span></td>
                </tr>
                <tr>
                  	<td width="180" height="20" align="right" bgcolor="#E0F1FC">เลือกวิชาลงทะเบียน :&nbsp;</td>
                  	<td bgcolor="#E0F1FC"><label>
                    
					<select id="ddlCourse" name="ddlCourse" onChange = "ListSection(this.value)">
						<option value="">เลือกวิชา</option>
						<?php
						$teach = $_SESSION['user'];
						$strSQL = "SELECT *  FROM course_section WHERE teacherId ='$teach' ORDER BY sectionId ASC ";

						$objQuery = mysql_query($strSQL) or die ("Error Query [".$strSQL."]");
						while($objResult = mysql_fetch_array($objQuery))
						{
						?>
						<option value="<?php echo $objResult["courseId"];?>"><?php echo $objResult["courseId"];?></option>
						<?php
						}
						?>
					</select>
					  
					<select id="ddlSection" name="ddlSection">
						<option value="">เลือกตอน</option>
					</select>

                  </label></td>
                </tr>
                <tr>
                  <td width="180" height="41" align="right" bgcolor="#FFFFFF"><br />
                  ไฟล์นำเข้า : &nbsp;</td>
                  <td width="220" bgcolor="#FFFFFF"><br />
                  <input type="file" name="fileupload" />				  </td></tr>
                <tr>
                  <td colspan="2" align="center" bgcolor="#BDE3F9"><font color="#FF0000">* รับได้เฉพาะไฟล์นักศึกษา excel(.xls) เท่านั้น!! *</font></td>
                </tr>
                <tr>
                  <td colspan="2" align="center" bgcolor="#99CCFF">
                    <br />
                    <input type="submit" name="ok" value="ตกลง" />
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <input type="reset" name="cancel" value="ยกเลิก" />
                  <br />
                  <br /></td>
                </tr>
              </table>
			  <br />
          </form>
<?php
} 
 ?>
<?php
function showimport(){
		   ?>
		   
	  <form id="form1" name="form1" method="post" action="">
	  <table width="550" height="75" border="1" align="center" cellpadding="0" cellspacing="0">
		<tr>
		  <td width="50" height="25"  align="center" bgcolor="#425969"><span class="style2">ลำดับ</span></td>
		  <td width="100" height="25"  align="center" bgcolor="#425969"><span class="style2">รหัสนักศึกษา</span></td>
		  <td width="250" align="center" bgcolor="#425969"><span class="style2">ชื่อ - นามสกุล</span></td>
		  <td width="150" align="center" bgcolor="#425969"><span class="style2">รหัสผ่านจากการสุ่ม</span></td>
		</tr>
	<?php 
	require('../connect.php');
	$course2 = $_POST['ddlCourse'];
	$sec2 = $_POST['ddlSection'];
	$sql = "select s.studentId As stu,studentName,studentPw from student As s,register As r where s.studentId=r.studentId and r.courseId='$course2' and r.sectionId='$sec2' order by s.studentId asc";
	$result = mysql_query($sql) or die (mysql_error());
	$i=0;
	while($row = mysql_fetch_array($result))
	{
		$i++;
	?>
		<tr>
		  <td align="center" bgcolor="#FFFFFF"><?php echo $i ?></td>
		  <td align="center" bgcolor="#FFFFFF"><?php echo $row['stu'] ?></td>
		  <td bgcolor="#FFFFFF"><?php echo $row['studentName'] ?></td>
		  <td bgcolor="#FFFFFF" align="center"><?php echo $row['studentPw'] ?> </td>
		</tr>
	<?php
	}
	?>
		<tr>
		 <td height="26" colspan="4" align="center" bgcolor="#99CCFF">
		   <input type="submit" value="ตกลง" name="ok2" />
		</td>
		</tr>	
	  </table>
	  </form>
<?php	
} // end of showimport
mysql_close();
?>

<?php
function random($len) {  
	$chars = "1234567890"; 
	$ret_str = ""; 
	$num = strlen($chars); 
	for($i=0; $i < $len; $i++) { 
		$ret_str.= $chars[rand()%$num]; 
	} 
	return $ret_str; 
} 
?>		  
</body>
</html>
