<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>รายละเอียดการส่งการบ้านนักศึกษา</title>
</head>
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
.control{
	border: #99CCFF thin solid;
}
.back{
	border: #FFFFFF thin solid;
}
.control:hover{
	border-right: #555555 thin inset; border-top: white thin outset; border-left: white thin outset; border-bottom: #555555 thin inset;
	cursor:pointer;
}
-->
</style>

<body>
<?php 
require_once('../connect.php');
$studentId = $_GET['stuid'];
$course = $_GET['course'];

$sqluser = "SELECT studentId,studentName FROM student WHERE studentId = '$studentId'";
$resultuser = mysql_query($sqluser);
$rowuser = mysql_fetch_array($resultuser);
$code = $rowuser['studentId'];
$fname = $rowuser['studentName'];
?>
<center>
<span class="style1">ผลการส่งการบ้านของ &nbsp;<?php echo $fname?> &nbsp;&nbsp;รหัสนักศึกษา &nbsp;<?php echo $code?></span>
<br />
<br />
</center>
<table width="600" border="1" align="center" cellpadding="2" cellspacing="0" bordercolor="#425969">
<thead>
<tr>
  <td height="31" colspan="5" align="center" bgcolor="#425969"><span class="style2">ผลการส่งการบ้าน</span></td>
</tr>
<th>ลำดับ</th>
<th>รายละเอียด</th>
<th>ชื่อไฟล์</th>
<th>วันกำหนดส่ง</th>
<th>สถานะ</th>
</thead>
<tbody>
<?php 

$sqlAssign = "SELECT homeworkId, homeworkFileName, homeworkFileType, homeworkDetail, dueDate FROM homework_assignment 
WHERE courseId = '$course' ";
$resultAssign = mysql_query($sqlAssign);

while($rowAssign = mysql_fetch_array($resultAssign) ) {
	$sqlSending = "SELECT sendStatus FROM homework_sending 
WHERE courseId = '$course' AND studentId = '$studentId' AND homeworkId = '$rowAssign[homeworkId]' ";
	$resultSending = mysql_query($sqlSending);
	$rowSending = mysql_fetch_array($resultSending)
?>
<tr>

	<td align="center"><?php echo $rowAssign['homeworkId']?></td>
	<td><?php echo $rowAssign['homeworkDetail']?></td>
	<td><?php echo $rowAssign['homeworkFileName']?>_<?php echo $studentId?>.<?php echo $rowAssign['homeworkFileType']?></td>
	<td><?php echo $rowAssign['dueDate']?></td>
	<?php
	if(mysql_num_rows($resultSending) > 0){
	// ถ้ามีการบ้านส่ง?>
	<td align="center"><?php 
						if($rowSending['sendStatus'] == 1) {
							echo '<font color="#00CC00">ส่งแล้ว</font>'; 
						}elseif($rowSending['sendStatus'] == 0.5) {
							echo '<font color="#FFFF00">ส่งเกินกำหนด</font>'; 
						}?></td>
	<?php 
		$disable = ""; 
	}else { 
	// ไม่มีการบ้านส่ง ?>
		<td align="center"><font color="#FF0000">ไม่ได้ส่ง</font></td>
	<?php		
	}
	?>

	
</tr>
<?php 
}
?>
</tbody>
</table>
</body>
</html>
