<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
<script type="text/javascript" src="../js/form.js"></script>
</head>
<body>        

<?php
//ลบข้อมูลตารางที่เลือก

require_once('../connect.php');

if(isset($_POST['ok'])){
	if($_POST['ok'] == "ตกลง" ){
	
		$err = ""; // check หา error
	for($i=0 ; $i<count($_POST['del']) ;$i++){		
		
		if($_POST['del'][$i] == "1"){
			$sql1 = "select * from student ";
			$result1 = mysql_query($sql1);
			if(mysql_num_rows($result1) == 0){
				$err .= "ไม่สามารถลบได้ เนื่องจากไม่มีข้อมูลนักศึกษาลงทะเบียน<br>";
			}else{
				$delstu = "delete from student";
				$delregis = "delete from register";
				mysql_query($delstu);
				mysql_query($delregis);
			}
		}
		
		if($_POST['del'][$i] == "2"){
			$sql2 = "select * from ta ";
			$result2 = mysql_query($sql2);
			if(mysql_num_rows($result2) == 0){
				$err .= "ไม่สามารถลบได้ เนื่องจากไม่มีข้อมูลนักศึกษาช่วยสอน<br>";
			}else{
				$delta = "delete from ta";
				$delassist = "delete from assistant";
				mysql_query($delta);
				mysql_query($delassist);
			}
		}
		
		if($_POST['del'][$i] == "3"){
			$sql3 = "select * from homework_assignment ";
			$result3 = mysql_query($sql3);
			if(mysql_num_rows($result3) == 0){
				$err .= "ไม่สามารถลบได้ เนื่องจากไม่มีข้อมูลการกำหนดการบ้าน<br>";
			}else{
				$delassign = "delete from homework_assignment";
				mysql_query($delassign);
			}
		}

		if($_POST['del'][$i] == "4"){
			$sql4 = "select * from homework_sending ";
			$result4 = mysql_query($sql4);
			if(mysql_num_rows($result4) == 0){
				$err .= "ไม่สามารถลบได้ เนื่องจากไม่มีข้อมูลการส่งงาน<br>";
			}else{
				$delsend = "delete from homework_sending"; 
				mysql_query($delsend);
			}
		}
		
		if(!empty($err) )
		{ ?>
			<div id="checkErr"><?php echo $err?></div>
		<?php
		}else{ // ทำการแสดงว่าลบข้อมูลเสร็จแล้ว ?>
			<div id="checkErr">ลบข้อมูลเรียบร้อยแล้ว</div>
		<?php }
		
	}
	}
}
?>


		  
	<form id="form" name="form1" method="post" action="" onsubmit="return onSubmitForm1('del[]', 'กรุณาเลือกตารางที่ต้องการลบข้อมูล')">
<br />
<center>              
<span class="style1">เลือกตารางข้อมูลเพื่อทำการลบข้อมูลทั้งหมด</span>
</center>
<br /> 	

	  <table width="500" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#425969">
		<tr>
		  <td height="30" colspan="3" align="center" bgcolor="#425969"><span class="style2">กรุณาเลือกตารางที่ต้องการลบข้อมูล</span></td>
		</tr>
		<tr>
		  <td height="20" width="50" align="center" bgcolor="#E0F1FC">ลำดับที่</td>
		  <td width="400" align="center" bgcolor="#E0F1FC">ตารางข้อมูล</td>
		  <td width="50" align="center" bgcolor="#E0F1FC">ตัวเลือก</td>
		</tr>
		<tr>
		  <td height="30" align="center" bgcolor="#FFFFFF">1</td>
		  <td bgcolor="#FFFFFF">&nbsp;&nbsp;&nbsp;ข้อมูลนักศึกษา (Student,Register)</td>
		  <td align="center" bgcolor="#FFFFFF">
				<input type="checkbox" name="del[]" value="1" /></td>
		</tr>
		<tr>
		  <td height="30" align="center" bgcolor="#FFFFFF">2</td>
		  <td bgcolor="#FFFFFF">&nbsp;&nbsp;&nbsp;ข้อมูลนักศึกษาช่วยสอน (Ta,Assistant)</td>
		  <td align="center" bgcolor="#FFFFFF">
				<input type="checkbox" name="del[]" value="2" /></td>
		</tr>
		<tr>
		  <td height="30" align="center" bgcolor="#FFFFFF">3</td>
		  <td bgcolor="#FFFFFF">&nbsp;&nbsp;&nbsp;ข้อมูลการกำหนดการบ้าน (Homework_Assignment)</td>
		  <td align="center" bgcolor="#FFFFFF">
				<input type="checkbox" name="del[]" value="3" /></td>
		</tr>
		<tr>
		  <td height="30" align="center" bgcolor="#FFFFFF">4</td>
		  <td bgcolor="#FFFFFF">&nbsp;&nbsp;&nbsp;ข้อมูลการส่งการบ้านนักศึกษา (Homework_Sending)</td>
		  <td align="center" bgcolor="#FFFFFF">
				<input type="checkbox" name="del[]" value="4" /></td>
		</tr>
		<tr>
		  <td height="40" colspan="3" align="center" bgcolor="#99CCFF"><label>
			<input type="submit" name="ok" value="ตกลง" />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="reset" name="cancel" value="ยกเลิก" />
		  </label></td>
		</tr>
	  </table>
  </form>

</body>
</html>
