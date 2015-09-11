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
  <span class="style1">จัดการข้อมูลอาจารย์ผู้สอน</span>
</center>
<br />

<?php
// ลบข้อมูลอาจารย์ผู้สอน
if(isset($_POST['delteach'])){
	if($_POST['delteach'] !=""){
		require("../connect.php");
		for($i=0;$i<count($_POST['teachbox']);$i++){
			$teachuser = $_POST['teachbox'][$i];	
			$del2= "delete from teacher where teacherId = '$teachuser' ";
			mysql_query($del2);
		}
		if(mysql_affected_rows() == 0) { ?>
			<div id="checkErr">ไม่สามารถลบข้อมูลได้</div><br />
		<?php }
		mysql_close();
	}
}
formResult();
function formResult() {
?>

<form method="post" action="" name="form1" onsubmit="return onSubmitForm1('teachbox[]','กรุณาเลือกอาจารย์')">
              <table width="600" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#425969">
                <tr>
                  <td height="31" colspan="6" align="center" bgcolor="#425969"><span class="style2">รายการข้อมูลอาจารย์ผู้สอน</span></td>
                </tr>
                <tr>
                  <td colspan="6" bgcolor="#BDE3F9">&nbsp;<a href="index_admin.php?teacher-data&add">เพิ่มข้อมูลอาจารย์ผู้สอน</a></td>
                </tr>
				<tr>
                  <td colspan="6" align="right" bgcolor="#99CCFF"><input type="button" name="checkteach" value="เลือกทั้งหมด"  onclick="javascript:SelectAll('teachbox[]');"/>
                    &nbsp;
                    <input type="button" name="uncheckteach" value="ไม่เลือกทั้งหมด" onclick="javascript:ResetAll('teachbox[]');" />
                    &nbsp;
                    <input type="submit" name="delteach" value="ลบข้อมูล" />
				</td>
                </tr>
                <tr>
                  <td width="44" align="center" bgcolor="#E0F1FC">ลำดับที่</td>
                  <td width="245" align="center" bgcolor="#E0F1FC">ชื่อ - สกุล</td>
                  <td width="106" align="center" bgcolor="#E0F1FC">ชื่อผู้ใช้งาน</td>
                  <td width="102" align="center" bgcolor="#E0F1FC">ข้อมูลรหัสผ่าน</td>
                  <td width="41" align="center" bgcolor="#E0F1FC">ตัวเลือก</td>
                  <td width="48" align="center" bgcolor="#E0F1FC">ลบ</td>
                </tr>
                <?php
			require("../connect.php");
			$command2 = "select * from teacher order by teacherName asc";
			$result2 = mysql_query($command2);
			for($i=0;$i<mysql_num_rows($result2);$i++){
				$row2 = mysql_fetch_array($result2);
?>
                <tr>
                  <td align="center" bgcolor="#FFFFFF"><?php echo $i+1; ?></td>
                  <td bgcolor="#FFFFFF">&nbsp;<?php echo $row2['teacherName']; ?></td>
                  <td bgcolor="#FFFFFF">&nbsp;<?php echo $row2['teacherId']; ?></td>
                  <td align="center" bgcolor="#FFFFFF"><?php echo $row2['teacherPw']; ?>&nbsp;</td>
                  <td align="center" bgcolor="#FFFFFF"><a href="index_admin.php?teacher-data&edit=<?php echo $row2['teacherId']; ?>">แก้ไข</a></td>
                  <td align="center" bgcolor="#FFFFFF"><input name="teachbox[]" type="checkbox" value="<?php echo $row2['teacherId']; ?>" />
                  </td>
                  <?php
				  }
				  ?>
                </tr>
              </table>
              <table width="600" border="1" align="center" cellspacing="0" bordercolor="#425969">
                <tr>
                  <td colspan="3" align="center" bgcolor="#99CCFF">
				  <a href="#top">กลับสู่ด้านบน</a>				  
				  </td>
                </tr>
              </table>
</form>		
<?php
}
?>
</body>
</html>
