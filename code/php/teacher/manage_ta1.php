<?php
session_start();
?>
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
<script  language="javascript" src="../js/form.js"></script>

<script  language="javascript">
<!--
function fixkey(){
	 if(event.keyCode < 46 || event.keyCode > 57 || event.keyCode == 47) { 
	  event.returnValue = false;
	 }
}

function formatNumber(no){
		x = no.split('.');
		if( x.length != 2)
			return false
			
		if(x[0] == '' || x[1] == '')
			return false
}
<?php
require_once('../connect.php');
?>
//**** List Section (Start) ***//
function ListSection(SelectValue)
{
frmMain.ddlSection.length = 0
//*** Insert null Default Value ***//
var myOption = new Option('เลือกตอน','') 
frmMain.ddlSection.options[frmMain.ddlSection.length]= myOption
<?php
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

//-->
</script>
</head>
<body>

<?php

if( isset($_POST['ok']) ){
	if ($_POST['ok'] == "ตกลง" ){	
		formimport();
		showreport();
	}
}
else{
	formimport();
}

if(isset($_POST['add']) ){
	if($_POST['add'] == "บันทึก" ){
		
		$course = $_GET['course'];
		$sec = $_GET['sec'];
		$hw = $_GET['hw'];
		
		$user = $_SESSION['user'];
		$sqluser = "SELECT teacherName FROM teacher WHERE teacherId = '$user'";
		$resultuser = mysql_query($sqluser);
		$rowuser = mysql_fetch_array($resultuser);
		$username = $rowuser['teacherName'];

		for($i=0;$i<count($_POST['score']);$i++){
		
			$sendStatus = $_POST['send'][$i];
			$score = $_POST['score'][$i];
			$studentId = $_POST['stuid'][$i];

			$sum = $sendStatus * $score ;	

			$sqlScore = "UPDATE homework_sending SET checkScore = '$score' , score = '$sum', userName = '$username'
					WHERE studentId='$studentId' AND courseId='$course' AND homeworkId='$hw' ";
			mysql_query($sqlScore) or die(mysql_error()); 
		}
		
	}
}

function formimport(){
?>
<br />
<center>
  <span class="style1">จัดการนักศึกษาช่วยสอน</span>
<br /><br />
</center>
            <form action="" method="post" name="frmMain" id="frmMain">
              <table border="1" align="center" cellpadding="0" cellspacing="0">
                <tr>
                  <td colspan="2" align="center" bgcolor="#425969"><span class="style2">กรุณาเลือกวิชาแสดงข้อมูล</span></td>
                </tr>
                <tr>
                  <td height="50" align="right" bgcolor="#E0F1FC">จัดการ : </td>
                  <td bgcolor="#E0F1FC">&nbsp;<label>
                    
					<select id="ddlCourse" name="ddlCourse" onChange = "ListSection(this.value)">
						<option selected value="">เลือกวิชา</option>
						<?php
						$teach = $_SESSION['user'];
						$strSQL = "SELECT courseId FROM course_section WHERE teacherId ='$teach' GROUP BY courseId ORDER BY courseId ASC";
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
						<option selected value="">เลือกตอน</option>
					</select>
					
					<!--<select id="ddlHwid" name="ddlHwid">
						<option selected value="">เลือกลำดับงาน</option>
					</select>
					-->
					
					<?php //<select id="ddlStuid" name="ddlStuid">
						//<option selected value="">เลือกรหัสนักศึกษา</option>
					//</select><?php 
					?>

                  </label></td>
                </tr>
                <tr>
                  <td colspan="2" align="center" bgcolor="#99CCFF">
                    <input type="submit" name="ok" value="ตกลง" /></td>
                </tr>
              </table>
          </form>
<?php
}

function showreport(){ 

				$course2 = $_POST['ddlCourse'];
				$sec2 = $_POST['ddlSection'];
				//$hw2 = $_POST['ddlHwid'];
				
			//$sqlStudent = "select s.studentId As studentId from student As s,register As r where s.studentId=r.studentId and r.courseId='$course2' and r.sectionId='$sec2' order by s.studentId asc";
			//$resultStu = mysql_query($sqlStudent);
				//$sqlta = "SELECT taname from ta t left join assistant a on t.taid=a.taid where a.courseid='$course2' and a.sectionid='$sec2' ";
			//$resultStu = mysql_query($sqlta);

?>
<br />
 <center>
 <span class="style1"> วิชา : <?php echo $course2?> &nbsp; ตอน : <?php echo $sec2?> &nbsp;>
 <br /><br />
 </span>
 </center>
 <font color="#FF0000">** หมายเหตุ **</font>
<form action="" method="post" name="form1"  onsubmit="return onSubmitForm1('coursesec[]','กรุณาเลือกตอน')" >
              <table width="600" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#425969">
                <tr>
                  <td height="31" colspan="6" align="center" bgcolor="#425969"><span class="style2">รายละเอียดตอน</span></td>
                </tr>
                <tr>
                  <!--<td colspan="6" bgcolor="#BDE3F9">&nbsp;<a href="index_teacher.php?editta&addta">เพิ่มนักศึกษาช่วยสอน</a></td>

                  -->
                  <td colspan="6" bgcolor="#BDE3F9">&nbsp;<a href="index_teacher.php?editta&edit=<?php echo $course2;?>&sec=<?php echo $sec2;?> ">เพิ่มนักศึกษาช่วยสอน</a></td>

                  
                </tr>
				<tr>
                  <td colspan="6" align="right" bgcolor="#99CCFF"><input type="button" name="check" value="เลือกทั้งหมด"  onclick="javascript:SelectAll('coursesec[]');"/>
                    &nbsp;
                    <input type="button" name="uncheck" value="ไม่เลือกทั้งหมด" onclick="javascript:ResetAll('coursesec[]');" />
                    &nbsp;
                    <input type="submit" name="delcourse" value="ลบข้อมูล" />
                  </td>
                </tr>
                <tr>
                  <td width="60" height="19" align="center" bgcolor="#E0F1FC">รหัสวิชา</td>
                  <td width="220" align="center" bgcolor="#E0F1FC">ชื่อชื่อวิชา</td>
                  <td width="45" align="center" bgcolor="#E0F1FC">ตอน</td>
				  <td width="170" align="center" bgcolor="#E0F1FC">นักศึกษาช่วยสอน</td>
                  <td width="50" align="center" bgcolor="#E0F1FC">ตัวเลือก</td>
                  <td width="50" align="center" bgcolor="#E0F1FC">ลบ</td>
                </tr>
                <?php
                $command = "select  a.courseid,c.coursename,t.taname
                from assistant a 
                left join course c on a.courseid=c.courseid
                left join ta t on a.taid=t.taId
                where a.courseid='$course2' and a.sectionid='$sec2'";
				$result = mysql_query($command) or die (mysql_error());
				while($row = mysql_fetch_array($result)){
					$course = $row['courseId'];
					$sec = $row['sectionId'];
               // $command = "SELECT t.taname, c.courseid, c.courseName,t.taid from ta t 
                //left join assistant_homework a on t.taid=a.taid
                //left join course c on a.courseid=c.courseid 
                //where a.courseid='$course2' and a.sectionid='$sec2' and a.homeworkId='$hw2' ";
			
				//$command = "select c.courseId As courseId,courseName,s.sectionId As sectionId,t.taname As taName
							//from course c,course_section s,ta t,assistant a
							//where t.taId=a.taId and c.courseId=s.courseId order by c.courseId ,s.sectionId asc";
				$result = mysql_query($command) or die (mysql_error());
				while($row = mysql_fetch_array($result)){
					$course = $row['courseid'];
					$sec = $sec2;
				?>
                <tr>
                  <td align="center" bgcolor="#FFFFFF"><?php echo $row['courseid']; ?></td>
                  <td bgcolor="#FFFFFF">&nbsp;<?php echo $row['coursename']; ?></td>
                  <td bgcolor="#FFFFFF" align="center">&nbsp;<?php echo $sec; ?> </td>
				  <td bgcolor="#FFFFFF">&nbsp;<?php echo $row['taname']; ?></td>
                  <td align="center" bgcolor="#FFFFFF"><a href="index_teacher.php?editta&edit=<?php echo $course2;?>&sec=<?php echo $sec2;?> ">แก้ไข</a></td>
                  <td align="center" bgcolor="#FFFFFF"><input type="checkbox" name="coursesec[]" value="<?php echo $course;?>-<?php echo $sec;?>" /></td>
                </tr>
                <?php
				}
				?>
              </table>
              <table width="600" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#425969">
                <tr>
                  <td colspan="5" align="center" bgcolor="#99CCFF">
				  <a href="#top">กลับสู่ด้านบน</a>				  
				  </td>
                </tr>
  			</table>
</form>
<?php		
}
}
?>
</body>
</html>

<?php


require_once("../connect.php");
// ลบข้อมูลวิชาและเซคชัน
if(isset($_POST['delcourse'])){ 
	if($_POST['delcourse'] !=""){
		
		for($i=0;$i<count($_POST['coursesec']);$i++){
			$arr = explode("-",$_POST['coursesec'][$i]);	
			$courseid = $arr[0];
			$secid = $arr[1];
			
			$del = "DELETE FROM course_section WHERE courseId = '$courseid' AND sectionId ='$secid'";
			$delTa = "DELETE FROM assistant WHERE courseId = '$courseid' AND sectionId ='$secid'";
			mysql_query($del);
			mysql_query($delTa);
		}
		//if(mysql_affected_rows() == 0) { 
		//	<br /><div id="checkErr">ไม่สามารถลบข้อมูลได้</div><br />
		//}
	}
}
?>