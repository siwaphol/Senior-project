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
  <span class="style1">จัดการข้อมูลนักศึกษาช่วยสอน</span>
</center>
<br />

<?php
// ลบข้อมูลนักศึกษาช่วยสอน
if(isset($_POST['delta'])){
	if($_POST['delta'] !=""){
		require("../connect.php");
		for($i=0;$i<count($_POST['tabox']);$i++){
			$tauser = $_POST['tabox'][$i];	
			$del3= "delete from ta where taId = '$tauser' ";
			mysql_query($del3);
		}
		if(mysql_affected_rows() == 0) { ?>
			<br /><div id="checkErr">ไม่สามารถลบข้อมูลได้</div><br />
		<?php }
		mysql_close();
	}
}

?>
		
<form name="form1" method="post" action=""  onsubmit="return onSubmitForm1('tabox[]','กรุณาเลือกนักศึกษาช่วยสอน')">
              <table width="600" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#425969">
                <tr>
                  <td height="31" colspan="6" align="center" bgcolor="#425969"><span class="style2">รายการข้อมูลนักศึกษาช่วยสอน</span></td>
                </tr>
                <tr>
                  <td colspan="6" bgcolor="#BDE3F9">&nbsp;<a href="index_admin.php?ta-data&add">เพิ่มข้อมูลนักศึกษาช่วยสอน</a></td>
                </tr>
				<tr>
                  <td colspan="6" align="right" bgcolor="#99CCFF"><input type="button" name="checkta" value="เลือกทั้งหมด"  onclick="javascript:SelectAll('tabox[]');"/>
                    &nbsp;
                    <input type="button" name="uncheckta" value="ไม่เลือกทั้งหมด" onclick="javascript:ResetAll('tabox[]');" />
                    &nbsp;
                    <input type="submit" name="delta" value="ลบข้อมูล" /></td>
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
			$command3 = "select taId,taName,taPw from ta order by taName asc";
			$result3 = mysql_query($command3);
			for($i=0;$i<mysql_num_rows($result3);$i++){
				$row3 = mysql_fetch_array($result3);
				?>
                <tr>
                  <td align="center" bgcolor="#FFFFFF"><?php echo $i+1; ?></td>
                  <td bgcolor="#FFFFFF">&nbsp;<?php echo $row3['taName']; ?></td>
                  <td bgcolor="#FFFFFF">&nbsp;<?php echo $row3['taId']; ?></td>
                  <td align="center" bgcolor="#FFFFFF"><?php echo $row3['taPw']; ?>&nbsp;</td>
                  <td align="center" bgcolor="#FFFFFF"><a href="index_admin.php?ta-data&edit=<?php echo $row3['taId']; ?>">แก้ไข</a></td>
                  <td align="center" bgcolor="#FFFFFF"><input name="tabox[]" type="checkbox" value="<?php echo $row3['taId']; ?>" />
                  </td>
                  <?php
				  }
				  ?>
                </tr>
              </table>
              <table width="600" border="1" align="center" cellspacing="0" bordercolor="#425969">
                <tr>
                  <td colspan="3" align="center" bgcolor="#99CCFF">
				  <a href="#top">กลับสู่ด้านบน</a>				  </td>
                </tr>
              </table>
</form>		

</body>
</html>