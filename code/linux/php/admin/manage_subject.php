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
</head>
<body>
<br />
<center>
  <span class="style1">จัดการวิชาเปิดสอน</span>
</center>
<br />

<?php
require_once("../connect.php");
// ลบวิชาเปิดสอน
if(isset($_POST['delsub'])){
	if($_POST['delsub'] !=""){
		
		for($i=0;$i<count($_POST['subject']);$i++){
			$sub = $_POST['subject'][$i];	
			$delsub = "delete from course where courseId = '$sub' ";
			mysql_query($delsub);
		}
		if(mysql_affected_rows() == 0) { ?>
			<br /><div id="checkErr">ไม่สามารถลบข้อมูลได้</div><br />
		<?php }
	}
}
?>

<form action="" method="post" name="form1"  onsubmit="return onSubmitForm1('subject[]','กรุณาเลือกวิชา')" >
              <table width="380" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#425969">
                <tr>
                  <td height="31" colspan="4" align="center" bgcolor="#425969"><span class="style2">วิชาเปิดสอน</span></td>
                </tr>
                <tr>
                  <td colspan="4" bgcolor="#BDE3F9">&nbsp;<a href="index_admin.php?subject&add">เพิ่มวิชา</a></td>
                </tr>
				<tr>
                  <td colspan="4" align="right" bgcolor="#99CCFF"><input type="button" name="check" value="เลือกทั้งหมด"  onclick="javascript:SelectAll('subject[]');"/>
                    &nbsp;
                    <input type="button" name="uncheck" value="ไม่เลือกทั้งหมด" onclick="javascript:ResetAll('subject[]');" />
                    &nbsp;
                    <input type="submit" name="delsub" value="ลบข้อมูล" />
                  </td>
                </tr>
                <tr>
                  <td width="60" height="19" align="center" bgcolor="#E0F1FC">รหัสวิชา</td>
                  <td width="220" align="center" bgcolor="#E0F1FC">ชื่อชื่อวิชา</td>
                  <td width="50" align="center" bgcolor="#E0F1FC">ตัวเลือก</td>
                  <td width="50" align="center" bgcolor="#E0F1FC">ลบ</td>
                </tr>
                <?php
				$command = "SELECT courseId,courseName FROM course ORDER BY courseId asc";
				$result = mysql_query($command) or die (mysql_error());
				while($row = mysql_fetch_array($result)){
				?>
                <tr>
                  <td align="center" bgcolor="#FFFFFF"><?php echo $row['courseId']; ?> </td>
                  <td bgcolor="#FFFFFF">&nbsp;<?php echo $row['courseName']; ?></td>
                  <td align="center" bgcolor="#FFFFFF"><a href="index_admin.php?subject&improve=<?php echo $row['courseId']; ?>">แก้ไข</a></td>
                  <td align="center" bgcolor="#FFFFFF"><input type="checkbox" name="subject[]" value="<?php echo $row['courseId']; ?>" /></td>
                </tr>
                <?php
				}
				?>
              </table>
              <table width="380" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#425969">
                <tr>
                  <td colspan="5" align="center" bgcolor="#99CCFF">
				  <a href="#top"> กลับสู่ด้านบน</a>				  
				  </td>
                </tr>
  			</table>
</form>

</body>
</html>