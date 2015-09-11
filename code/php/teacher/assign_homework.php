<?php
	session_start();
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
<script type="text/javascript" src="../js/jquery-autocomplete/jquery.autocomplete.js"></script>
<script type="text/javascript" src="../js/jquery-autocomplete/lib/jquery.bgiframe.min.js"></script>
<script type="text/javascript" src="../js/jquery-autocomplete/lib/jquery.ajaxQueue.js"></script>
<script type="text/javascript" src="../js/jquery-autocomplete/lib/thickbox-compressed.js"></script>
<link rel="stylesheet" href="../js/jquery-autocomplete/jquery.autocomplete.css" />

<SCRIPT language=javascript src="../calendar1/images/calendar1.js" type="text/javascript"></SCRIPT>

<script type="text/javascript">
	
	$(function() {
		var myArr = $("div#autoList").text().split(" ")
		$("input#ftype").autocomplete(myArr)
	})
	
	function onSubmit(){
		var msgErr = ""
		if($("#hid").val() == ""){	
			msgErr += "ลำดับต้องไม่เป็นช่องว่าง\n"
		}
		if ($("#assigntype").val()=="1") {
			if($("#fname").val() == ""){
			msgErr += "ชื่อไฟล์ต้องไม่เป็นช่องว่าง\n"
			}
		if($("#ftype").val() == ""){	
			msgErr += "ชนิดไฟล์ต้องไม่เป็นช่องว่าง\n"
		}
		if($("#detail").val() == ""){	
			msgErr += "รายละเอียดต้องไม่เป็นช่องว่าง\n"
		}
		if($("#ddate").val() == ""){	
			msgErr += "วันกำหนดส่งต้องไม่เป็นช่องว่าง\n"
		}
		if(msgErr != ""){
			alert(msgErr)
			return false
		}else{
			return true
		}
	}
</script>
<script type="text/javascript">

function yesnoCheck() {
    if (document.getElementById('yesCheck').checked) {
        document.getElementById('ifYes').style.display = 'block';
    }
    else document.getElementById('ifNo').style.display = 'block';

}

</script>
	
</head>
<body>
<br />
<center>
  <span class="style1">กำหนดการบ้าน</span>
</center>
<br />

<?php
require_once("../connect.php");
// check submit
if( isset($_POST['Submit']) ){

	$course= $_GET['course'];
	$lab = $_POST['lab'];
	$hwId = $_POST['hid'];
	$filename = $_POST['fname'];
	$filetype = trim($_POST['ftype']);
	$detail = $_POST['detail'];
	$duedate = $_POST['ddate'];
	$asdate = date("Y-m-d");
	$sqlHEType = "INSERT INTO homework_type (hwTypeName) VALUES ('$filetype')";
	mysql_query($sqlHEType);
	if ($assigntype=='1') {
		$assigntype='1';
	}elseif ($assigntype!='1') {

		    $assigntype='0';
		}
	
	$sql = "INSERT INTO homework_assignment (courseId,homeworkId,homeworkFileName,homeworkFileType,homeworkDetail,subFolder,dueDate,assignDate) VALUES ('$course', '$hwId', '$filename', '$filetype', '$detail', '$lab', '$duedate', '$asdate') ";
	mysql_query($sql) or die(mysql_error());
	if(mysql_affected_rows() != 0) {
		echo "<script>window.location='index_teacher.php?assign'</script>"; //ตกลง เพิ่มลง db กลับไปหน้า course
	} else { ?>
		<div id="checkErr">ไม่สามารถเพิ่มข้อมูลได้</div><br />
	<?php }
} // ปิด func addCourse
?>

<?php
	$course = $_GET['course'];
	$_SESSION['ss_course'] = $course;
	$sqlCourse = "SELECT courseId,courseName FROM course WHERE courseId = '$course' ";
	$resultCourse = mysql_query($sqlCourse);
	$rowCourse = mysql_fetch_array($resultCourse);

?>
<form action="" method="post" name="form2" id="form2" onsubmit="return onSubmit()">
        <table id="myTable" width="400" border="1" align="center" cellpadding="0" cellspacing="0">
			<thead>
                  <tr>
                    <td height="31" colspan="2" align="center" bgcolor="#425969"><span class="style2">แบบฟอร์มเพิ่มรายละเอียดการบ้าน</span></td>
                  </tr>
            </thead>
            <tbody>
                 <tr>
                    <td height="50" width="170" align="right" valign="middle" bgcolor="#FFFFFF">
                    	<br />รหัสวิชา : &nbsp;<br /><br />
						ชื่อวิชา : &nbsp;
					</td>
                    <td valign="top" colspan="2" bgcolor="#FFFFFF">
					<br /><input type="text" name="cid" value="<?php echo $rowCourse['courseId']?>" disabled="disabled" />
					<br /><br /><input name="sid" type="text" disabled="disabled" value="<?php echo $rowCourse['courseName']?>" size="40" />
					</td>
                  </tr>
				  <tr>
				  	<td height="50" width="100" align="right" valign="middle" bgcolor="#FFFFFF">
					ลำดับ &nbsp;</td>
                    <?php
                    	$sqlFindMaxHW = "SELECT homeworkId FROM homework_assignment WHERE courseId ='$course' ";
						$resultMax = mysql_query($sqlFindMaxHW);
						$id = mysql_num_rows($resultMax) + 1;
					?>
					<td> <input type="text" value="<?php echo $id?>" name="hid" id="hid" /> <font color="#FF0000">*</font>
					</td>
				  </tr>
				  <tr>
				  	<td height="50" width="100" align="right" valign="middle" bgcolor="#FFFFFF">
					กำหนดชื่อไฟล์&nbsp;</td>
					<td><br />
					บังคับชื่อ <input type="radio" onclick="javascript:yesnoCheck();" name="yesno" id="yesCheck"  > ไม่บังคับชื่อ <input type="radio" onclick="javascript:yesnoCheck();" name="yesno" id="noCheck"><br>
    				<div id="ifYes" style="display:none">
        			<input type="text" value="<?php //echo $_POST['fname']?>" name="fname" id="fname" /><font color="#FF0000">*</font>
        			<input type="hidden" name="assigntype" value="1">  <!--//เมื่อกำหนดชื่อประเภทจะเท่ากับหนึ่ง -->
				    <br /><font color="#000099">ชื่อไฟล์ตามด้วยรหัสนักศึกษา เช่น it1 <br /> นักศึกษาส่งไฟล์ชื่อ it1_500510XXX</font>
				    </td>
				   </div>

				    </td>
				   </div>
					</tr>
				  <tr>
				  	<td height="50" width="100" align="right" valign="middle" bgcolor="#FFFFFF">
					ชนิดไฟล์ &nbsp;</td>
					<td> <br />
					<input type="text" value="<?php //echo $_POST['ftype']?>" name="ftype" id="ftype" > <font color="#FF0000">*</font>
					<br /><font color="#000099">ชนิดไฟล์ เช่น doc</font></td>
				  </tr>
				  <tr>
				  	<td height="50" width="100" align="right" valign="middle" bgcolor="#FFFFFF">
					รายละเอียด &nbsp;</td>
					<td> <textarea name="detail" id="detail" cols="" rows=""><?php //echo $_POST['detail']?></textarea> 
					<font color="#FF0000">*</font> </td> 
				  </tr>
				  <tr>
				  	<td height="50" width="100" align="right" valign="middle" bgcolor="#FFFFFF">
				    โฟลเดอร์ย่อย &nbsp;</td>
					<td><br />
					    <input type="text" name="lab" value="<?php //echo $_POST['lab']?>" id="lab" /><br />
			          <font color="#000099">ชื่อตำแหน่งเก็บการบ้าน เช่น lab01</font></td>
				  </tr>
				  <tr>
				  	<td height="50" width="100" align="right" valign="middle" bgcolor="#FFFFFF">
					วันกำหนดส่ง &nbsp;</td> 
					<td> 
					<input type="text" name='ddate' id="ddate" size="21" value="<?php //echo $_POST['ddate']?>" onClick="blur()">
       				<a href="javascript:cal1.popup();"><img src="../calendar1/images/cal.gif" width="16" height="15" border="0" alt="คลิกเลือกวันส่ง"></a>
					 <font color="#FF0000">*</font> </td>
				  </tr>
                 </tbody>
                 <tfoot>
                  <tr>
                    <td valign="middle" height="50" colspan="2" bgcolor="#FFFFFF" align="center">
                        <input type="Submit" name="Submit" value="ตกลง" id="Submit"/>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="button" name="Cancel" onclick="window.location='index_teacher.php?assign'" value="ยกเลิก" />
					</td>
                  </tr>
				  </tfoot>
              </table>
<SCRIPT language=JavaScript>           
				var cal1 = new calendar1(document.forms['form2'].elements['ddate']);
				cal1.year_scroll = true;
				cal1.time_comp = false;	 								
</script>	   
</form>
<?php
	//mysql_close();
?>
</body>
</html>

<div id="autoList" style="display:none">
<?php 
		$sqlHWType = "SELECT * FROM homework_type ";
		$resultHWType = mysql_query($sqlHWType) or die(mysql_error());
		while($rows = mysql_fetch_array($resultHWType)) { 
			echo $rows['hwTypeName'] . " ";
		 }
		?>
</div>