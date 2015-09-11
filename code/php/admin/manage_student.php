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
.style3 {
	font-weight: bold;
	color: #0066CC;
}
-->
</style>
<script type="text/javascript" src="../js/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="../js/form.js"></script>
<script  language="javascript">
<!--
function onSubmitMain() {
	var msgErr = ""
	if($("#ddlCourse").val() == ""){	
		msgErr += "กรุณาเลือกวิชา\n"
	}
	if($("#ddlSection").val() == ""){	
		msgErr += "กรุณาเลือกตอน\n"
	}
	if(msgErr != ""){
		alert(msgErr)
		return false
	}else{
		return true
	}
}




<?php
require('../connect.php');
?>
//**** List Section (Start) ***//
function ListSection(SelectValue)
{
frmMain.ddlSection.length = 0
//*** Insert null Default Value ***//
var myOption = new Option('เลือกตอน','') 
frmMain.ddlSection.options[frmMain.ddlSection.length]= myOption
<?php
$intRows = 0;
$strSQL = "SELECT sectionId,courseId FROM course_section ORDER BY sectionId ASC ";
$objQuery = mysql_query($strSQL) or die ("Error Query [".$strSQL."]");
while($objResult = mysql_fetch_array($objQuery))
{
$intRows++;
?>
x = <?php echo $intRows;?>;
mySubList = new Array();
strGroup = <?php echo $objResult["courseId"];?>;
strValue = "<?php echo $objResult["sectionId"];?>";
mySubList[x,0] = strGroup;
mySubList[x,1] = strValue;
if (mySubList[x,0] == SelectValue){
var myOption = new Option(mySubList[x,1]) 
frmMain.ddlSection.options[frmMain.ddlSection.length]= myOption                  
}
<?php
}
mysql_close();
?>                                                                  
}
/*function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}*/
//-->
</script>

</head>
<body>
<br />
<center>
  <span class="style1">จัดการข้อมูลนักศึกษา</span>
</center>
<br />

<?php
// ลบข้อมูลนักศึกษา
formimport();
require("../connect.php");
if(isset($_POST['delstu'])){ 
	if($_POST['delstu'] !=""){
		$course3 = $_POST['course'];
		$sec = $_POST['sec'];
		$_SESSION['ss_course'] = $course3;
		$_SESSION['ss_sec'] = $sec;
		for($i=0;$i<count($_POST['stubox']);$i++){
			$stuid = $_POST['stubox'][$i];
			$sql = "select studentId from register where studentId= '$stuid'";
			$result = mysql_query($sql);	
			
			$del1 ="delete from register where studentId = '$stuid' and courseId = '$course3'";
			mysql_query($del1);
			if(mysql_num_rows($result) == 1){	
			$del2 = "delete from student where studentId = '$stuid' ";
			mysql_query($del2);
			}
		}
		if(mysql_affected_rows() == 0) { ?>
			<br /><div id="checkErr">ไม่สามารถลบข้อมูลได้</div><br />
		<?php }
		mysql_close();
	}
}
?>
<?php


if( isset($_POST['ok']) ){
	if ($_POST['ok'] == "ตกลง" ){	
		$_SESSION['ss_course'] = $_POST['ddlCourse'];
		$_SESSION['ss_sec'] = $_POST['ddlSection'];
	}
}

if(isset($_SESSION['ss_course'])) {
	showstudent();
}

function formimport(){
?>
            <form action="" method="post" name="frmMain" id="frmMain" onsubmit="return onSubmitMain()">
              <table border="1" align="center" cellpadding="0" cellspacing="0">
                <tr>
                  <td colspan="2" align="center" bgcolor="#425969"><span class="style2">กรุณาเลือกวิชาแสดงข้อมูล</span></td>
                </tr>
                <tr>
                  <td width="147" height="20" align="right" bgcolor="#E0F1FC">เลือกวิชาลงทะเบียน :</td>
                  <td bgcolor="#E0F1FC"><label>
                    
					<?php
					require('../connect.php');
					?>
					<select id="ddlCourse" name="ddlCourse" onChange = "ListSection(this.value)">
						<option selected value="">เลือกวิชา</option>
						<?php
						$strSQL = "SELECT * FROM course ORDER BY courseId ASC ";
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
                    <?php 
					mysql_close();
					?>
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

function showstudent(){ 
			require("../connect.php");
			require_once("../config/config.php");	
				$course2 = $_SESSION['ss_course'];
				$sec2 = $_SESSION['ss_sec'];
				unset($_SESSION['ss_course']);
				unset($_SESSION['ss_sec']);
				
				$year = $yearConfig2;
				$sem = $semesterConfig2;
?>
<br />
<img src="../images/excel.gif" /><a href="exportExcel.php?course=<?php echo $course2?>&sec=<?php echo $sec2?>&year=<?php echo $year?>&sem=<?php echo $sem?>">ดาวน์โหลดรายชื่อนักศึกษา</a> 

<span class="style3"><?php echo "วิชา : $course2  ตอน : $sec2";?></span>

<form action="" method="post" name="form1" onsubmit="return onSubmitForm1('stubox[]','กรุณาเลือกนักศึกษา')" >
 
  <table width="600" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#425969">
                <tr>
                  <td height="31" colspan="5" align="center" bgcolor="#425969"><span class="style2">   รายการข้อมูลนักศึกษา</span></td>
                </tr>
                <tr>
                  <td colspan="5" bgcolor="#BDE3F9">&nbsp;<a href="index_admin.php?student-data&add&course=<?php echo $course2?>&sec=<?php echo $sec2?>">เพิ่มข้อมูลนักศึกษา</a></td>
				</tr>
				 <tr>
				  <td colspan="5" align="right" bgcolor="#99CCFF">
				  <input type="button" name="checkstu" value="เลือกทั้งหมด"  onclick="javascript:SelectAll('stubox[]');"/>
                    &nbsp;
                    <input type="button" name="uncheckstu" value="ไม่เลือกทั้งหมด" onclick="javascript:ResetAll('stubox[]');" />
                    &nbsp;
                    <input type="submit" name="delstu" value="ลบข้อมูล" />
					<input type="hidden" name="course" value="<?php echo $course2?>" />
                    <input type="hidden" name="sec" value="<?php echo $sec2?>" />
                  </td>
                </tr>
                <tr>
                  <td width="88" height="19" align="center" bgcolor="#E0F1FC">รหัสนักศึกษา</td>
                  <td width="270" align="center" bgcolor="#E0F1FC">ชื่อ - สกุล
                    </div></td>
                  <td width="130" align="center" bgcolor="#E0F1FC">ข้อมูลรหัสผ่าน
                    </div></td>
                  <td width="52" align="center" bgcolor="#E0F1FC">ตัวเลือก</td>
                  <td width="48" align="center" bgcolor="#E0F1FC">ลบ</td>
                </tr>
                <?php
				
				$command = "select s.studentId As studentId,studentName,studentPw from student As s,register As r where s.studentId=r.studentId and r.courseId='$course2' and r.sectionId='$sec2' order by s.studentId asc";
				$result = mysql_query($command);
				for($i=0;$i<mysql_num_rows($result);$i++){
					$row = mysql_fetch_array($result);
					
				?>
                <tr>
                  <td align="center" bgcolor="#FFFFFF"><?php echo $row['studentId']; ?></td>
                  <td bgcolor="#FFFFFF">&nbsp;<?php echo $row['studentName']; ?></td>
                  <td bgcolor="#FFFFFF" align="center">&nbsp;<?php echo $row['studentPw']; ?> </td>
                  <td align="center" bgcolor="#FFFFFF"><a href="index_admin.php?student-data&course=<?php echo $course2?>&sec=<?php echo $sec2?>&edit=<?php echo $row['studentId']; ?>">แก้ไข</a></td>
                  <td align="center" bgcolor="#FFFFFF"><input type="checkbox" name="stubox[]" value="<?php echo $row['studentId']; ?>" />
                  </td>
                </tr>
                <?php
				}
				mysql_close();
				?>
                <tr>
				</table>
				<table width="600" border="1" align="center" cellspacing="0" bordercolor="#425969">
				<tr>
                  <td colspan="5" align="center" bgcolor="#99CCFF">
				  <a href="#top">กลับสู่ด้านบน</a>				  </td>
                </tr>
				</table>
  
</form>
<?php
}
?>
</body>
</html>
