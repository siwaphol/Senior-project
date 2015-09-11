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
frmMain.ddlHwid.length = 0
//*** Insert null Default Value ***//
var secOption = new Option('เลือกตอน','') 
var hwOption = new Option('เลือกลำดับงาน','')
frmMain.ddlSection.options[frmMain.ddlSection.length]= secOption
frmMain.ddlHwid.options[frmMain.ddlHwid.length]= hwOption
<?php
$intRows1 = 0;
$intRows2 = 0;
$ta = $_SESSION['user'];
$strSQL = "SELECT courseId,sectionId FROM assistant WHERE taId ='$ta' ORDER BY sectionId ASC ";
$objQuery = mysql_query($strSQL) or die ("Error Query [".$strSQL."]");
while($objResult = mysql_fetch_array($objQuery))
{
	$intRows1++;
	?>
	x = <?php echo $intRows1;?>;
	mySubList = new Array();
	strGroup = <?php echo $objResult["courseId"];?>;
	strValue = "<?php echo $objResult["sectionId"];?>";
	mySubList[x,0] = strGroup;
	mySubList[x,1] = strValue;
	if (mySubList[x,0] == SelectValue){
	var secOption = new Option(mySubList[x,1]) 
	frmMain.ddlSection.options[frmMain.ddlSection.length]= secOption                  
	}
<?php
}
?>

<?php
$strSQL2 = "SELECT courseId,homeworkId FROM homework_assignment ORDER BY homeworkId ASC ";
$objQuery2 = mysql_query($strSQL2) or die ("Error Query [".$strSQL2."]"); 
while($objResult2 = mysql_fetch_array($objQuery2))
{
	$intRows2++;
	?>
	y = <?php echo $intRows2;?>;
	mySubList2 = new Array();
	strGroup2 = <?php echo $objResult2["courseId"];?>;
	strValue2 = "<?php echo $objResult2["homeworkId"];?>";
	mySubList2[y,0] = strGroup2;
	mySubList2[y,1] = strValue2;
	if (mySubList2[y,0] == SelectValue){
	var hwOption = new Option(mySubList2[y,1]) 
	frmMain.ddlHwid.options[frmMain.ddlHwid.length]= hwOption                  
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
		$sqluser = "SELECT taName FROM ta WHERE taId = '$user'";
		$resultuser = mysql_query($sqluser);
		$rowuser = mysql_fetch_array($resultuser);
		$username = $rowuser['taName'];

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
  <span class="style1">จัดการคะแนนของนักศึกษา</span>
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
						$taid = $_SESSION['user'];
						$strSQL = "SELECT courseId FROM assistant WHERE taId ='$taid' GROUP BY courseId ORDER BY courseId ASC";
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
					
					<select id="ddlHwid" name="ddlHwid">
						<option selected value="">เลือกลำดับงาน</option>
					</select>
					
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
				$hw2 = $_POST['ddlHwid'];
				
			$sqlStudent = "select s.studentId As studentId from student As s,register As r where s.studentId=r.studentId and r.courseId='$course2' and r.sectionId='$sec2' order by s.studentId asc";
			$resultStu = mysql_query($sqlStudent);

?>
<br />
 <center>
 <span class="style1"> วิชา : <?php echo $course2?> &nbsp; ตอน : <?php echo $sec2?> &nbsp; ลำดับการบ้าน : <?php echo $hw2?>
 <br /><br />
 </span>
 </center>
<font color="#FF0000">** หมายเหตุ **</font>
 <br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ผลการส่ง <font color="#00CC00">1 = ส่งถูกต้อง</font> , <font color="#FFFF0D">0.5 = ส่งเกินเวลา </font> , <font color="#FF0000">0 = ไม่ได้ส่ง</font> 
 <br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;คะแนน 1-10 (เต็ม 10)
<form action="?score&course=<?php echo $course2?>&sec=<?php echo $sec2?>&hw=<?php echo $hw2?>" method="post" name="form1" >
  <table width="600" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#425969">
	<tr>
		<td height="19" align="center" bgcolor="#E0F1FC"><b>รหัสนักศึกษา</b></td>  
		<td align="center" bgcolor="#E0F1FC"><b>ผลการส่ง</b></td>
		<td align="center" bgcolor="#E0F1FC"><b>คะแนน</b></td>
		<td align="center" bgcolor="#E0F1FC"><b>คะแนนรวม</b></td>
		<td align="center" bgcolor="#E0F1FC"><b>ชื่อผู้ตรวจ</b></td>
	</tr>
<?php
	while($rowStu = mysql_fetch_array($resultStu)){
		$student = $rowStu['studentId'] ;
	?>
	<tr>
		<td align="center" bgcolor="#FFFFFF"><input type="hidden" name="stuid[]" value="<?php echo $student?>" /><?php echo "$student"; ?></td>
		<?php 
			$command3 = "SELECT sendStatus,checkScore,score,userName FROM homework_sending 
			WHERE courseId='$course2' AND studentId ='$student' AND homeworkId ='$hw2'";
			$result3 = mysql_query($command3);
			$row3 = mysql_fetch_array($result3);
		?>
		<td bgcolor="#FFFFFF" align="center" >
		<input type="hidden" name="send[]" value="<?php echo $row3['sendStatus'];?>" /><?php
		if($row3['sendStatus'] == 1) {
			echo '<font color="#00CC00">1</font>'; 
		}elseif($row3['sendStatus'] == 0.5) {
			echo '<font color="#FFFF0D">0.5</font>'; 
		}else { 
			echo '<font color="#FF0000">0</font>';
		}
		?> </td>
		
		<td bgcolor="#FFFFFF" align="center">
		<input name="score[]" type="text" size="5" onkeypress="fixkey()" maxlength="3" value="<?php echo $row3['checkScore'];?>" /></td>
		
		<td bgcolor="#FFFFFF" align="center">
		<?php echo "$row3[score]"; ?></td>
		<td bgcolor="#FFFFFF" align="center">
		<?php echo "$row3[userName]"; 
		} 
		?></td>
	</tr>
	<tr>
		<td colspan="5" align="center" bgcolor="#CCCCCC"><input type="submit" name="add" value="บันทึก"  /></td>
	</tr>
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

