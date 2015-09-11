<?php
session_start();
if($_SESSION['status'] != "teacher"){
	echo "<script>window.location='../index.php'</script>";
}
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
<script type="text/javascript" src="../js/jquery-1.4.4.min.js"></script>
<script type="text/javascript">
<body>
 <br />
<center>
 <span class="style1">จัดการนักศึกษาช่วยสอน</span>
<br /><br />
</center>
<?php
require_once('../connect.php');
$teach = $_SESSION['user'];
$strSQL = "SELECT courseId,sectionId FROM course_section WHERE teacherId ='$teach' ORDER BY sectionId ASC ";
$objQuery = mysql_query($strSQL) or die ("Error Query [".$strSQL."]");
?>
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













</body>
</html>>