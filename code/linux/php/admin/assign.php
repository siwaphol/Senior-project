<?php
session_start();
include("../connect.php");

?>
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

<script  language="javascript">
<!--
function onSubmit() {
if(document.formSelect.elements['ddlCourse'].value == "") {
alert('กรุณาเลือกวิชา')
return false
}
}

function onSubmitForm1() {
	var flag = false
for (var i=0;i< document.form1.elements['hwbox[]'].length;i++) {
if (document.form1.elements['hwbox[]'][i].checked) {
flag = true
}
}
	if(!flag){
		alert("กรุณาเลือกการบ้าน")
		return false
	}else{
		if(confirm('ยืนยันการลบข้อมูล'))
			return true
		else	return false
	}
}

function SelectAllHw() { //ทำการเลือก Option ทั้งหมด
for (var i=0;i< document.form1.elements.length;i++) {
if (document.form1.elements[i].name == 'hwbox[]') {
document.form1.elements[i].checked = true;
}
}
}
function ResetAllHw() { //ทำการลบ option ที่เลือกทั้งหมด
for (var i=0;i< document.form1.elements.length;i++) {
if (document.form1.elements[i].name == 'hwbox[]') {
document.form1.elements[i].checked = false;
}
}
}

//-->
</script>

<?php
formimport();
// ลบข้อมูลรายละเอียดการบ้าน

if(isset($_POST['delhw'])){ 
	if($_POST['delhw'] !=""){
		$course = $_GET['course'];
		for($i=0;$i<count($_POST['hwbox']);$i++){
			$hwid = $_POST['hwbox'][$i];
			$sql = "select homeworkId from homework_assignment where  homeworkId = '$hwid' ";
			$result = mysql_query($sql) or die (mysql_error());	
			
			if(mysql_num_rows($result) == 1){	
			$del2 = "delete from homework_assignment where homeworkId = '$hwid' ";
			mysql_query($del2) or die (mysql_error());
			}
		}
		mysql_close();
	}
}
?>

<?php

if( isset($_POST['ok']) ){
	if ($_POST['ok'] == "ตกลง" ){	
		$_SESSION['ss_course'] = $_POST['ddlCourse'];
	}
} 
if(isset($_SESSION['ss_course'])) {
	showreport();
}



function formimport(){
?>
<br />
<center>
  <span class="style1">จัดการข้อมูลการส่งการบ้าน</span>
<br /><br />
</center>

<form action="" method="post" name="formSelect" onsubmit="return onSubmit()">
 <table border="1" align="center" cellpadding="0" cellspacing="0">
                <tr>
                  <td colspan="2" align="center" bgcolor="#425969"><span class="style2">กรุณาเลือกวิชาแสดงข้อมูล</span></td>
                </tr>
                <tr>
                  <td width="171" height="20" align="right" bgcolor="#E0F1FC">เลือกวิชากำหนดการบ้าน :</td>
                  <td width="221" bgcolor="#E0F1FC"><label>
                    
					<select id="ddlCourse" name="ddlCourse" >
						<option value="">เลือกวิชา</option>
						<?php
						$admin = $_SESSION['user'];
						$strSQL = "SELECT distinct(courseId) FROM course_section  ORDER BY courseId ASC ";
						$objQuery = mysql_query($strSQL) or die ("Error Query [".$strSQL."]");
						while($objResult = mysql_fetch_array($objQuery))
						{
						?>
						<option value="<?php echo $objResult["courseId"];?>"><?php echo $objResult["courseId"];?></option>
						<?php
						}
						?>
					</select>
                  </label></td>
                </tr>
                <tr>
                  <td colspan="2" align="center" bgcolor="#99CCFF">
                    <input type="submit" name="ok" value="ตกลง" /></td>
                </tr>
              </table>
</form>

 <?php
}

function showreport(){ 

 $course= $_SESSION['ss_course'];
 unset($_SESSION['ss_course']);
 
 ?>
 <br />
  <center>
 <span class="style1"> วิชา : <?php echo $course?>
 </span>
 </center>
 
<form name="form1" method="post" onsubmit="return onSubmitForm1()">
  <table width="700" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#425969">
                <tr>
                  <td height="31" colspan="9" align="center" bgcolor="#425969"><span class="style2">รายละเอียดการบ้าน</span></td>
                </tr>
                <tr>
                  <td colspan="9" bgcolor="#BDE3F9">&nbsp;<a href="index_admin.php?assign&add&course=<?php echo $course?>">กำหนดการบ้าน</a></td>
				</tr>
				 <tr>
				  <td colspan="9" align="right" bgcolor="#99CCFF">
				  <input type="button" name="checkhw" value="เลือกทั้งหมด"  onclick="javascript:SelectAllHw();"/>
                    &nbsp;
                    <input type="button" name="uncheckhw" value="ไม่เลือกทั้งหมด" onclick="javascript:ResetAllHw();" />
                    &nbsp;
                    <input type="submit" name="delhw" value="ลบข้อมูล" />
					
                  </td>
                </tr>
                <tr>
                 	<td width="38"  align="center" bgcolor="#E0F1FC">ลำดับ</td>
                  	<td width="110" align="center" bgcolor="#E0F1FC">ชื่อไฟล์</td>
                 	<td width="47" align="center" bgcolor="#E0F1FC">ชนิด</td>
				  	<td width="149" align="center" bgcolor="#E0F1FC">รายละเอียด</td>
					<td width="59" align="center" bgcolor="#E0F1FC">ตำแหน่ง</td>
				  	<td width="93" align="center" bgcolor="#E0F1FC">วันกำหนดส่ง</td>
                  	<td width="93" align="center" bgcolor="#E0F1FC">วันที่กำหนด</td>
					<td width="55" align="center" bgcolor="#E0F1FC">ตัวเลือก</td>
                  	<td width="38" align="center" bgcolor="#E0F1FC">ลบ</td>
                </tr>
                <?php 
			  	
				$sql2 = "SELECT homeworkId,homeworkFileName,homeworkFileType,homeworkDetail,subFolder,dueDate,assignDate FROM homework_assignment WHERE courseId='$course' ORDER BY homeworkId ASC ";
				$result2 = mysql_query($sql2) or die (mysql_error());
				
				while($row2 = mysql_fetch_array($result2))
				{	
				?>
                <tr>
				  <td align="center" bgcolor="#FFFFFF"><?php echo $row2['homeworkId']; ?></td>
				   <td align="left" bgcolor="#FFFFFF"><?php echo $row2['homeworkFileName']; ?></td>
                  <td align="center" bgcolor="#FFFFFF"><?php echo $row2['homeworkFileType']; ?></td>
                  <td bgcolor="#FFFFFF">&nbsp;<?php echo $row2['homeworkDetail']; ?></td>
				  <td align="center" bgcolor="#FFFFFF"><?php echo $row2['subFolder']; ?></td>
                  <td bgcolor="#FFFFFF" align="center">&nbsp;<?php echo $row2['dueDate']; ?> </td>
				  <td bgcolor="#FFFFFF" align="center">&nbsp;<?php echo $row2['assignDate']; ?> </td>
                  <td align="center" bgcolor="#FFFFFF"><a href="index_admin.php?assign&edit&course=<?php echo $course?>&id=<?php echo $row2['homeworkId']; ?>">แก้ไข</a></td>
                  <td align="center" bgcolor="#FFFFFF"><input type="checkbox" name="hwbox[]" value="<?php echo $row2['homeworkId']; ?>" />
                  </td>
                </tr>
                <?php
				}
				
				?>
                <tr>
				</table>
	<table width="700" border="1" align="center" cellspacing="0" bordercolor="#425969">
	<tr>
	  <td colspan="9" align="center" bgcolor="#99CCFF">
	  <a href="#top">กลับสู่ด้านบน</a>				  </td>
	</tr>
	</table>
  
</form>
<?php		
}
?>

