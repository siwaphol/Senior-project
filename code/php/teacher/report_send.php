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

<script  language="javascript">
<!--

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


function formimport(){
?>
<br />
<center>
  <span class="style1">รายงานการส่งการบ้านอัตโนมัติ</span>
<br />
<br />
</center>
            <form action="" method="post" name="frmMain" id="frmMain">
              <table border="1" align="center" cellpadding="0" cellspacing="0">
                <tr>
                  <td colspan="2" align="center" bgcolor="#425969"><span class="style2">กรุณาเลือกวิชาแสดงข้อมูล</span></td>
                </tr>
                <tr>
                  <td width="171" height="20" align="right" bgcolor="#E0F1FC">เลือกวิชาสรุปรายงาน :</td>
                  <td width="221" bgcolor="#E0F1FC"><label>
                    
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

			require_once("../config/config.php");	
				$course2 = $_POST['ddlCourse'];
				$sec2 = $_POST['ddlSection'];
				
				$year = $yearConfig2;
				$sem = $semesterConfig2;
				
			$command1 = "select s.studentId As studentId,studentName from student As s,register As r where s.studentId=r.studentId and r.courseId='$course2' and r.sectionId='$sec2' order by s.studentId asc";
			$command2 = "select homeworkId from homework_assignment where courseId='$course2' ";
			$result1 = mysql_query($command1);
			$result2 = mysql_query($command2);
			
?>
<br />
 <center>
 <span class="style1">ผลการส่งการบ้านของนักศึกษา<br />
 วิชา : &nbsp;<?php echo $course2?> &nbsp;&nbsp; ตอน : &nbsp;<?php echo $sec2?> <br /><br />
 </span>
 </center>
 
 <img src="../images/excel.gif" /><a href="exportReport/export_send.php?course=<?php echo $course2?>&sec=<?php echo $sec2?>&year=<?php echo $year?>&sem=<?php echo $sem?>">ดาวน์โหลดรายงานการส่งการบ้านอัตโนมัติ</a>
 
 <form action="" method="post" name="form1" >
  <table width="600" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#425969">
                <tr>
                  <td height="19" align="center" bgcolor="#E0F1FC"><b>รหัสนักศึกษา</b></td>
                  <td align="center" bgcolor="#E0F1FC"><b>ชื่อ - สกุล</b></td>  
				 <?php
				
				for($i=0;$i<mysql_num_rows($result2);$i++){
					$row2 = mysql_fetch_array($result2);
					?>
						<td align="center" bgcolor="#E0F1FC"><b><?php echo $row2['homeworkId']?></b></td>
					<?php
				}
				?>
				</tr>
				<?php
				while($row1 = mysql_fetch_array($result1)){
					$student = $row1['studentId'] ;
				?>
                <tr>
                  <td align="center" bgcolor="#FFFFFF"><?php echo $student?></td>
                  <td bgcolor="#FFFFFF">&nbsp;<?php echo $row1['studentName'] ?></td>
					  <?php 
					  $result2 = mysql_query($command2);
					  	while($rows = mysql_fetch_array($result2)) {
							$command3 = "select sendStatus from homework_sending where studentId ='$student' and courseId='$course2' AND homeworkId = '$rows[homeworkId]' ";
							$result3 = mysql_query($command3);
							$row3 = mysql_fetch_array($result3);
							if(mysql_num_rows($result3) == 0 || $row3['sendStatus'] == 0)
								$status = 0;
							else
								$status = $row3['sendStatus'];
					  ?>
					  <td width="30" align="center" bgcolor="#FFFFFF"><?php echo $status?></td>
					  <?php } } ?>
                </tr>

                <tr>
  </table>
				<table width="600" border="1" align="center" cellspacing="0" bordercolor="#425969">
				<tr>
                  <td colspan="5" align="center" bgcolor="#99CCFF">
				  <a href="#top">กลับสู่ด้านบน</a>				  </td>
                </tr>
				</table>
  
</form>
<?php		
}
?>
</body>
</html>
