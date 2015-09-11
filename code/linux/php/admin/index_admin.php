<?php
session_start();

if($_SESSION['status'] != "admin"){
	echo "<script>window.location='../index.php'</script>";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Administrator</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../style.css" media="screen" />
<style type="text/css">
<!--
.style2 {color: #FFFFFF}
.style4 {
	font-size: large;
	color: #0066CC;
}
.style5 {font-size: 16px}
-->
</style>
<script type="text/javascript" src="../js/jquery-1.4.4.min.js"></script>
</head>
<body>

<div id="wrap">

<div id="header">
</div>

<div id="content">

<div id="logoutdiv">
  <div align="right"><?php if($_SESSION['user']!="") ?>
  <font color="#0033CC" size="2"> ยินดีต้อนรับ &nbsp;: &nbsp;<?php echo $_SESSION['ssname'];?></font></div>
  <div align="left"><img src="../images/Help_book.png" /><font color="#0033CC" size="2"><a target="_blank" href="../help/help_admin.pdf"><b>คู่มือการใช้งาน</b></a></font></div>
</div>

<div class="right"> 

<h2 class="style2">สำหรับผู้ดูแลระบบ</h2>
<br />
<?php 
	if(isset($_GET['student-data']))
		if(isset($_GET['add'])) {
			include('manageuser/add_student.php');
		}else if(isset($_GET['edit'])){
			include('manageuser/edit_student.php');
		}else{
			include('manage_student.php');
		}
	else if(isset($_GET['teacher-data']))
		if(isset($_GET['add'])) {
			include('manageuser/add_teacher.php');
		}else if(isset($_GET['edit'])){
			include('manageuser/edit_teacher.php');
		}else {
			include('manage_teacher.php');
		}
	else if(isset($_GET['ta-data']))
		if(isset($_GET['add'])) {
			include('manageuser/add_ta.php');
		}else if(isset($_GET['edit'])){
			include('manageuser/edit_ta.php');
		}else {
			include('manage_ta.php');
		}
	else if(isset($_GET['admin-data']))
		if(isset($_GET['add'])) {
			include('manageuser/add_admin.php');
		}else if(isset($_GET['edit'])){
			include('manageuser/edit_admin.php');
		}else {
			include('manage_admin.php');
		}
	else if(isset($_GET['subject']))
		if(isset($_GET['add'])) {
			include('managecourse/add_subject.php');
		}else if(isset($_GET['improve'])){
			include('managecourse/edit_subject.php');
		}else {
			include('manage_subject.php');
		}
	else if(isset($_GET['editcourse']))
		if(isset($_GET['addsec'])) {
			include('managecourse/addsec_course.php');
		}else if(isset($_GET['edit'])){
			include('managecourse/editsec_course.php');
		}else {
			include('manage_course.php');
		}
	else if(isset($_GET['assign'])){
					if(isset($_GET['add'])){
						include('assign_homework.php');
					}
					else if(isset($_GET['edit'])){
						include('edit_homework.php');
					}
					else {
						include('assign.php');
					}
			}
	else if(isset($_GET['register']))
		include('import_student1.php');
	else if(isset($_GET['cleardb']))
		include('clear_database.php');
	else if(isset($_GET['score']))
				include('manage_score.php');
			else if(isset($_GET['report_send']))
				include('report_send.php');
			else if(isset($_GET['report_score']))
				include('report_score.php');
			else if(isset($_GET['report_summary']))
				include('report_summary.php');
	else {?>
	<center><br />
	<p><span class="style4">ยินดีต้อนรับเข้าสู่ ระบบจัดการสารสนเทศสำหรับการบ้านปฏิบัติการ<br />
  	ภาควิชาวิทยาการคอมพิวเตอร์ คณะวิทยาศาสตร์ มหาวิทยาลัยเชียงใหม่</span><br />
	<img src="../images/database.jpg" /> </p></center>
	<?php };
?>

 
</div>
</div>

<div class="left"> 

<h2>เมนู</h2>

<ul><a href="../admin/index_admin.php">หน้าหลักผู้ดูแลระบบ</a></ul><hr />
<ul><span style="color:#425969; text-decoration:none; font-weight:600; ">จัดการข้อมูล</span>
<li><a href="?student-data">นักศึกษา</a></li>
<li><a href="?teacher-data">อาจารย์</a></li> 
<li><a href="?ta-data">นักศึกษาช่วยสอน</a></li> 
<li><a href="?admin-data">ผู้ดูแลระบบ</a></li> </ul><hr />
<ul><span style="color:#425969; text-decoration:none; font-weight:600; ">จัดการวิชาเปิดสอน</span>
<li><a href="?subject">วิชา</a></li>
<li><a href="?editcourse">ตอน</a></li> </ul> <hr />
<ul><a href="?assign">กำหนดการบ้าน</a></ul> <hr />
<ul><a href="?register">เพิ่มข้อมูลนักศึกษา</a></ul> <hr />
<ul><i>(ตรวจสอบอัตโนมัติ)</i> <br />
		<a href="?report_send">รายงานสรุปผลการตรวจแบบอัตโนมัติ</a> <font color="#CC0000">(A)</font> 
		</ul> 
		<hr />
		<ul><i>(ตรวจสอบด้วยมือ)</i> <br /> <a href="?score">กรอกคะแนนรายข้อ</a><br />
		<a href="?report_score">รายงานสรุปผลการตรวจด้วยมือ</a>
		<font color="#CC0000">(B)</font>
		</ul><hr />
		<ul> 
		<a href="?report_summary">รายงานสรุปผลการตรวจการบ้าน</a> <font color="#CC0000">(C)</font><br />
		<font color="#CC0000"><i>(C = A x B)</i></font>
		</ul> 
		<hr />
<ul><a href="?cleardb">ลบข้อมูลทั้งหมด</a></ul> <hr />
<ul><a onclick="return confirm('ท่านต้องการออกจากระบบ')" href="../testlogout.php">ออกจากระบบ</a></ul> <hr />




</div>

</div>
<div style="clear: both;"> </div>

<div id="footer">

</div>
</div>

</body>
</html>