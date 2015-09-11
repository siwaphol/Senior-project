<?php
session_start();

if($_SESSION['status'] != "ta"){
	echo "<script>window.location='../index.php'</script>";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Student Assistant</title>
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
  <div align="left"><img src="../images/Help_book.png" /><font color="#0033CC" size="2"><a target="_blank" href="../help/help_ta.pdf"><b>คู่มือการใช้งาน</b></a></font></div>
</div>

<div class="right"> 

<h2 class="style2">สำหรับนักศึกษาช่วยสอน</h2>
<br />
<?php
	if(isset($_GET['profile']))
		include('profile_ta.php');
	else if(isset($_GET['score']))
		include('manage_score.php');
	else if(isset($_GET['report_score']))
		include('report_score.php');
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
<ul><a href="../ta/index_ta.php">หน้าหลักนักศึกษาช่วยสอน</a></ul><hr />
<ul><a href="?profile">แก้ไขข้อมูลส่วนตัว</a></ul> <hr />
<ul><i>(ตรวจสอบด้วยมือ)</i> <br /> <a href="?score">กรอกคะแนนรายข้อ</a><br />
<a href="?report_score">รายงานสรุปผลการตรวจด้วยมือ</a>
</ul>
<hr />
<ul><a onclick="return confirm('ท่านต้องการออกจากระบบ')" href="../testlogout.php">ออกจากระบบ</a></ul> <hr />



</div>

</div>
<div style="clear: both;"> </div>

<div id="footer">

</div>
</div>

</body>
</html>