<?php
session_start();
include("../connect.php");
if($_SESSION['status'] != "student"){
	echo "<script>window.location='../index.php'</script>";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Student</title>
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
.style5 {font-size: 16px}
-->
</style>
</head>
<body>

<div id="wrap">

<div id="header">
</div>

<div id="content">

<div id="logoutdiv">
  <div align="right"><?php if($_SESSION['user']!="") {
  $userId = $_SESSION['user'];
  ?>
  <font color="#0033CC" size="2"> ยินดีต้อนรับ &nbsp;: &nbsp;<?php echo $_SESSION['ssname'];?></font></div>
  <div align="left"><img src="../images/Help_book.png" /><font color="#0033CC" size="2"><a target="_blank" href="../help/help_student.pdf"><b>คู่มือการใช้งาน</b></a></font></div>
  <?php }?>
</div>

<div class="right"> 

<h2 class="style2">สำหรับนักศึกษา</h2>
<br />
	<?php
		if(isset($_GET['register'])) {
			if($_GET['assign'] == "yes")
				include("send_assign.php");
			else
				include("send_no_assign.php");
		}elseif(isset($_GET['upload'])){
			include("upload1.php");
		}elseif(isset($_GET['profile'])){
			include("profile_student.php");
		}elseif(isset($_GET['bin'])){
			include("hw_bin.php");
		} else {
	?>
	<center><br />
	<p><span class="style4">ยินดีต้อนรับเข้าสู่ ระบบจัดการสารสนเทศสำหรับการบ้านปฏิบัติการ<br />
  	ภาควิชาวิทยาการคอมพิวเตอร์ คณะวิทยาศาสตร์ มหาวิทยาลัยเชียงใหม่</span><br />
	<img src="../images/database.jpg"  /></p></center>
	<?php } ?>
			
</div>
</div>

<div class="left"> 

<h2>เมนู</h2>
<ul>
แสดงรายวิชาที่นักศึกษาเรียน
<?php 
$sql = "SELECT courseId, sectionId FROM register WHERE studentId = '$userId' ";
$result = mysql_query($sql);
while($rows = mysql_fetch_array($result)) {
	$course = $rows['courseId'];
	$section = $rows['sectionId'];
	
	$sql2 = "SELECT courseId FROM homework_assignment WHERE courseId = '$course' ";
	$result2 = mysql_query($sql2);
	$row2 = mysql_num_rows($result2);
	if($row2 > 0){
		$assign = "assign=yes";
	}else
		$assign = "assign=no";
?>
<li><a href="?register&course=<?php echo $course?>&section=<?php echo $section?>&<?php echo $assign?>"><?php echo $course?> - <?php echo $section?></a></li>
<?php 
}
mysql_close();
?>
</ul><hr />
<ul><a href="?profile">ข้อมูลส่วนตัว</ul> <hr />
<ul><a href="?bin">ถังชยะ</ul> <hr />
<ul><a onclick="return confirm('ท่านต้องการออกจากระบบ')" href="../testlogout.php">ออกจากระบบ</a></ul> <hr />


</div>

</div>
<div style="clear: both;"> </div>

<div id="footer">

</div>
</div>

</body>
</html>