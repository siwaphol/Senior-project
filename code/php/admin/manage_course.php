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
  <span class="style1">จัดการรายละเอียดตอน</span>
</center>
<br />

<?php
require_once("../connect.php");
// ลบข้อมูลวิชาและเซคชัน
if(isset($_POST['delcourse'])){ 
	if($_POST['delcourse'] !=""){
		
		for($i=0;$i<count($_POST['coursesec']);$i++){
			$arr = explode("-",$_POST['coursesec'][$i]);	
			$courseid = $arr[0];
			$secid = $arr[1];
			
			$del = "DELETE FROM course_section WHERE courseId = '$courseid' AND sectionId ='$secid'";
			$delTa = "DELETE FROM assistant WHERE courseId = '$courseid' AND sectionId ='$secid'";
			mysql_query($del);
			mysql_query($delTa);
		}
		//if(mysql_affected_rows() == 0) { 
		//	<br /><div id="checkErr">ไม่สามารถลบข้อมูลได้</div><br />
		//}
	}
}
		
?>

<form action="" method="post" name="form1"  onsubmit="return onSubmitForm1('coursesec[]','กรุณาเลือกตอน')" >
              <table width="600" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#425969">
                <tr>
                  <td height="31" colspan="6" align="center" bgcolor="#425969"><span class="style2">รายละเอียดตอน</span></td>
                </tr>
                <tr>
                  <td colspan="6" bgcolor="#BDE3F9">&nbsp;<a href="index_admin.php?editcourse&addsec">เพิ่มรายละเอียดตอน</a></td>
                </tr>
				<tr>
                  <td colspan="6" align="right" bgcolor="#99CCFF"><input type="button" name="check" value="เลือกทั้งหมด"  onclick="javascript:SelectAll('coursesec[]');"/>
                    &nbsp;
                    <input type="button" name="uncheck" value="ไม่เลือกทั้งหมด" onclick="javascript:ResetAll('coursesec[]');" />
                    &nbsp;
                    <input type="submit" name="delcourse" value="ลบข้อมูล" />
                  </td>
                </tr>
                <tr>
                  <td width="60" height="19" align="center" bgcolor="#E0F1FC">รหัสวิชา</td>
                  <td width="220" align="center" bgcolor="#E0F1FC">ชื่อชื่อวิชา</td>
                  <td width="45" align="center" bgcolor="#E0F1FC">ตอน</td>
				  <td width="170" align="center" bgcolor="#E0F1FC">อาจารย์ผู้สอน</td>
                  <td width="50" align="center" bgcolor="#E0F1FC">ตัวเลือก</td>
                  <td width="50" align="center" bgcolor="#E0F1FC">ลบ</td>
                </tr>
                <?php
				$command = "select c.courseId As courseId,courseName,s.sectionId As sectionId,t.teacherName As teacherName
							from course c,course_section s,teacher t
							where s.teacherId=t.teacherId and c.courseId=s.courseId order by c.courseId ,s.sectionId asc";
				$result = mysql_query($command) or die (mysql_error());
				while($row = mysql_fetch_array($result)){
					$course = $row['courseId'];
					$sec = $row['sectionId']
				?>
                <tr>
                  <td align="center" bgcolor="#FFFFFF"><?php echo $row['courseId']; ?></td>
                  <td bgcolor="#FFFFFF">&nbsp;<?php echo $row['courseName']; ?></td>
                  <td bgcolor="#FFFFFF" align="center">&nbsp;<?php echo $row['sectionId']; ?> </td>
				  <td bgcolor="#FFFFFF">&nbsp;<?php echo $row['teacherName']; ?></td>
                  <td align="center" bgcolor="#FFFFFF"><a href="index_admin.php?editcourse&edit=<?php echo $course;?>&sec=<?php echo $sec;?>">แก้ไข</a></td>
                  <td align="center" bgcolor="#FFFFFF"><input type="checkbox" name="coursesec[]" value="<?php echo $course;?>-<?php echo $sec;?>" /></td>
                </tr>
                <?php
				}
				?>
              </table>
              <table width="600" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#425969">
                <tr>
                  <td colspan="5" align="center" bgcolor="#99CCFF">
				  <a href="#top">กลับสู่ด้านบน</a>				  
				  </td>
                </tr>
  			</table>
</form>

</body>
</html>
