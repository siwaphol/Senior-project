<?php
session_start();

if($_SESSION['status'] != "teacher"){
	echo "<script>window.location='../index.php'</script>";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Teacher</title>
<meta http-equiv="Content-Language" content="English" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="../style.css" media="screen" />
<style type="text/css">
<!--
.style2 {color: #FFFFFF}
.style4 {
	font-size: large;
	color: #0066CC;
}
-->
</style>
</head>
<body>

<div id="wrap">

	<div id="header">
	</div>
	
	<div id="content">
		<div id="logoutdiv">
		  <div align="right"><?php if($_SESSION['user']!="") ?>
		  <font color="#0033CC" size="2"> ยินดีต้อนรับ &nbsp;: &nbsp;<?php echo $_SESSION['ssname'];?></font></div>
		  <div align="left"><img src="../images/Help_book.png" /><font color="#0033CC" size="2"><a target="_blank" href="../help/help_teacher.pdf"><b>คู่มือการใช้งาน</b></a></font></div>
		</div>
	
		<div class="left"> 
		
		<h2>เมนู</h2>
		<ul><a href="../teacher/index_teacher.php">หน้าหลักอาจารย์ผู้สอน</a></ul><hr />
		<ul><a href="?profile">แก้ไขข้อมูลส่วนตัว</a></ul> <hr />
		<ul><a href="?importstudent">เพิ่มรายชื่อนักศึกษา</a></ul> <hr />
		<ul><a href="?assign">กำหนดการบ้าน</a></ul> <hr />
		<ul><a href="?editta">เพิ่ม  TA</a></ul> <hr />
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
		<ul><a onclick="return confirm('ท่านต้องการออกจากระบบ')" href="../testlogout.php">ออกจากระบบ</a></ul> <hr />
		
		
		</div>
		<div class="right"> 
		
		<h2 class="style2">สำหรับอาจารย์ผู้สอน</h2>
		<?php
			if(isset($_GET['profile']))
				include('profile_teacher.php');
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
			else if(isset($_GET['score']))
				include('manage_score.php');
			else if(isset($_GET['report_send']))
				include('report_send.php');
			else if(isset($_GET['report_score']))
				include('report_score.php');
			else if(isset($_GET['report_summary']))
				include('report_summary.php');
			else if(isset($_GET['importstudent']))
				include('import_student.php');
			else if(isset($_GET['editta'])){
				if(isset($_GET['addta'])) {
				//include('addsec_ta_assistant.php');
					include('addsec_course.php');
			}else if(isset($_GET['edit'])){
				//include('edit_ta_assistant.php');
				include('editsec_courseta.php');
			}else {
			include('manage_ta1.php');
				//include('manage_course.php');
			}
		}
			else {?>
			<center><br />
			<p><span class="style4">ยินดีต้อนรับเข้าสู่ ระบบจัดการสารสนเทศสำหรับการบ้านปฏิบัติการ<br />
			ภาควิชาวิทยาการคอมพิวเตอร์ คณะวิทยาศาสตร์ มหาวิทยาลัยเชียงใหม่</span><br />
			<img src="../images/database.jpg"  /></p></center>
			<?php };
		?>
		
		</div>
	</div>
</div>
<div style="clear: both;"> </div>

<div id="footer">

</div>
</div>

</body>
</html>