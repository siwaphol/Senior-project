<?php 
session_start();
include("../connect.php");
?>
<script src="../js/jquery-1.4.4.min.js" type="text/javascript"></script>
<script type="text/javascript">
function onSubmit(){
	var msgErr = ""
	var check = true
	$("input[type=file]").each(function(){
		if($(this).val() != "" && check)
			check = false
	})
	
	if(check){
		msgErr = "กรุณาเลือกไฟล์"
	} else if($("#assignmenttype")=='1'){
		var filenameArr = $("#fname").val().split("\\");
		var filename = filenameArr[filenameArr.length - 1];
		if(filename != $("#hwname").find("font").text() && filename != ""){
			msgErr = "ชื่อไฟล์ไม่ถูกต้อง"
			$("#fname").val("")
			}else{
				var filenameArr = $("#fname").val().split("\\");
				var filename = filenameArr[filenameArr.length - 1];
				//if(filename != $("span#formatFile").find("font").text() && filename != ""){
				//msgErr = "ชื่อไฟล์ไม่ถูกต้อง"
				var filename=$("span#formatFile").find("font").text();
				//$("#fname").val("")

			}
	}else if($("#assignmenttype")!='1'){
		//var filenameArr = $("#fname").val().split("\\");
		//var studentid="_".concat($("#stuid"));
		var filenameArr = $("#hwname").val().split("\\");
		var filename = filenameArr[filenameArr.length - 1];
		//var filenameArr = $("#fname").val().split("\\");
				//var filename = filenameArr[filenameArr.length - 1];
				//if(filename != $("span#formatFile").find("font").text() && filename != ""){
				//msgErr = "ชื่อไฟล์ไม่ถูกต้อง"
				var filename=$("span#formatFile").find("font").text();
				//$("#fname").val("")
		/*if(filename != $("#hwname").find("font").text() && filename != ""){
			msgErr = "ชื่อไฟล์ไม่ถูกต้อง"
			$("#fname").val("")
			}else{
				var filenameArr = $("#fname").val().split("\\");
				var filename = filenameArr[filenameArr.length - 1];
				//if(filename != $("span#formatFile").find("font").text() && filename != ""){
				//msgErr = "ชื่อไฟล์ไม่ถูกต้อง"
				var filename=$("span#formatFile").find("font").text();
				//$("#fname").val("")

			} */
	}
	if(msgErr != ""){
		alert(msgErr)
		return false
	}else return true
}
</script>
<style type="text/css">
.style1 {
	font-size: medium;
	font-weight: bold;
	color: #0066CC;
}
td#row1 {
	border: solid 1px #FF0000
}
</style>
<?php
if(isset($_POST['upbutton']))
{
	   include("../config/config.php");
	   $year = $yearConfig2;
	   $semester = $semesterConfig2;
	   $rootFolder = $rootFolder2;
	   $course = $_GET['course'];
	   $homeworkId = $_GET['homeworkId'];
	   $studentId = $_SESSION['user'];
	   $assign = "assign=no";
		
	   $regis = "SELECT sectionId FROM register WHERE courseId = '$course' AND studentId = '$studentId' ";
	   $resultRegis = mysql_query($regis) or die(mysql_error());
	   $rowRegis = mysql_fetch_array($resultRegis);
	   $section = $rowRegis['sectionId'];

	//INSERT ลงฐานข้อมูล homework_sending   
	if(isset($_GET['homeworkId'])){
		$assign = "assign=yes";
	
		
	   $assignsql = "SELECT subFolder,dueDate FROM homework_assignment WHERE courseId = '$course' AND homeworkId = '$homeworkId' ";
	   $resultAs = mysql_query($assignsql) or die(mysql_error());
	   $rowAs = mysql_fetch_array($resultAs);
	   $subfolder = $rowAs['subFolder'];
		
		if($_FILES["filename"]['name'] != ""){
		   $dateAs = $rowAs['dueDate'];
		   $dateAsArr = explode("-", $dateAs);
		   $dateAssign = mktime(0,0,0,$dateAsArr[1],$dateAsArr[2]+1,$dateAsArr[0]);
		
		   $today = date("Y-m-d");
		   $timeday = date("H:i:s");
	
		   $todayArr = explode("-", $today);
		   $timedayArr = explode(":", $timeday);
		   $dateSending = mktime($timedayArr[0],$timedayArr[1],$timedayArr[2],$todayArr[1],$todayArr[2],$todayArr[0]);
	
		   if(number_format($dateSending) > number_format($dateAssign) ){
				$score = 0.5 ; 
			}elseif(number_format($dateSending) <= number_format($dateAssign) ){
				$score = 1 ;
			}
	
			$check = "SELECT courseId FROM homework_sending WHERE courseId = '$course' AND studentId = '$studentId' AND homeworkId = '$homeworkId' ";
			$resultCheck = mysql_query($check);
			
			if(mysql_num_rows($resultCheck) == 0){
			   $send = "INSERT INTO homework_sending (studentId, courseId, homeworkId, sendStatus, checkScore) 
			   VALUES('$studentId','$course','$homeworkId','".$score."','0')";
			   mysql_query($send) or die(mysql_error());
		   }elseif(mysql_num_rows($resultCheck) == 1){
				$send = "UPDATE homework_sending SET sendStatus='".$score."' WHERE courseId = '$course' AND studentId = '$studentId' AND homeworkId = '$homeworkId' ";
				mysql_query($send) or die(mysql_error());
		   }
		}
	}

	   $path = $rootFolder.$year."_".$semester."/".$course."/sec".$section."/".$studentId ;
	   if($subfolder != "")
			$path .= "/".$subfolder ;
	   $pathArr = explode("/",$path);
	   $tmpPath = "";

	   for($i = 0; $i < count($pathArr); $i++){
		   $tmpPath .= $pathArr[$i];
			if(!is_dir("$tmpPath")){
				mkdir("$tmpPath");
			}
			$tmpPath .="/";
		}
		
		if(isset($_GET['dir'])){
			$myString = str_replace("\\\\\\\\","/",$_GET['dir']);
			$tmpPath .= substr($myString,1,strlen($myString));
			$tmpPath .="/";
		}

// upload file ตรวจสอบกำหนดการบ้าน
if(isset($_FILES["filename"])){
	if($_FILES["filename"]['name'] != "") {
		if ($_FILES["filename"]["error"] > 0)
		  {
		  echo "Error 1: " . $_FILES["filename"]["error"] . "<br>";
		  }
		else
		  {
		  	$pathdest=$_FILES["filename"]["tmp_name"];
		  	$pathsource=$tmpPath . $_FILES["filename"]["name"];

			  move_uploaded_file($_FILES["filename"]["tmp_name"],$tmpPath . $_FILES["filename"]["name"]);
		  }
	  }
}	
	  // upload file อื่นๆ ที่ไม่กำหนดการบ้าน
	  for($i=0;$i<count($_FILES["fileother"]);$i++)
		{
			if($_FILES["fileother"]["name"][$i] != "")
			{
				if ($_FILES["fileother"]["error"][$i] > 0)
				  {
				  echo "Error 2: " . $_FILES["fileother"]["error"][$i] . "<br>";
				  }
				else
				  {
					  move_uploaded_file($_FILES["fileother"]["tmp_name"][$i],$tmpPath . $_FILES["fileother"]["name"][$i]);
				  }
			}
		}
		if(isset($_GET['dir'])){
			$dir = "&dir=".str_replace("\\", "/", $_GET['dir']);
		}else {
			$dir = "";
		}
		
		echo "<script> window.location='index_student.php?register&course=$course&$assign$dir'; </script>";
}


$course = $_GET['course'];
$homeworkId = $_GET['homeworkId'];
$studentId = $_SESSION['user'];
$sqlAssign = "SELECT homeworkFileName, homeworkFileType, homeworkDetail,assignmenttype FROM homework_assignment 
WHERE courseId = '$course' AND homeworkId ='$homeworkId' ";
$resultAssign = mysql_query($sqlAssign);
$rowAssign = mysql_fetch_array($resultAssign);
if(isset($_GET['homeworkId'])){
	$hw ="&homeworkId=$homeworkId";
}else
	$hw = "";
	
	if(isset($_GET['dir'])){
		$dir = "&dir=".$_GET['dir'];
	}else {
		$dir = "";
	}


?>
<center> <span class="style1"><?php echo $rowAssign['homeworkDetail']?></span></center><br />
<form name="form1" method="post" action="?upload&course=<?php echo $course?><?php echo $hw?><?php echo $dir?>" onSubmit="return onSubmit()" enctype="multipart/form-data">
<table align="center" cellpadding="5">
	<?php 
	if(isset($_GET['homeworkId'])){
	?>
	<tr>
		<td width="500"><span id="formatFile">ส่งงานที่กำหนด : 
		<?php if ($rowAssign['assignmenttype']=='1') {?>
			<font color="#FF0000"><?php echo $rowAssign['homeworkFileName'];?>_<?php echo $studentId?>.<?php echo $rowAssign['homeworkFileType']?></font></span></td>
			<input type="hideden" name="hwname" value=<?php  echo $rowAssign['homeworkFileName'];?> >
			<input type="hidden" name="stuid" value=<?php echo $studentId;?>>
			<input type="hidden" name="assignmenttype" value=<?php echo $rowAssign['assignmenttype']; ?>>
		<?php } else if ($rowAssign['assignmenttype']!='1') { ?>

			<font color="#FF0000"><?php echo "ไม่ได้ระบุ";?></font></span></td>
			<input type="hidden" name="hwname" value=<?php echo $rowAssign['homeworkFileName']."_".$rowAssign['assignmenttype'];?>>
			<input type="hidden" name="assignmenttype" value=<?php echo $rowAssign['assignmenttype']; ?>>
		<?php } ?>
		<td id="row1"><input type="file" name="filename" id="fname"></td>
	</tr>
	<tr>
	<td> ไฟล์อื่น ๆ</td>
	<td><input type="file" name="fileother[]"></td>
	</tr>
	<?php
	}
	for($i=0;$i<8;$i++){
	?>
	<tr>
	<td >&nbsp;</td>
	<td><input type="file" name="fileother[]"></td>
	</tr>
	<?php
	}
	?>
	<tr>
	<td height="50" colspan="2" align="center"><input type="submit" name="upbutton" value="Upload"></td>
	</tr>
</table>
</form>
 
