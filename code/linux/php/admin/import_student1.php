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
<script type="text/javascript" src="../js/jquery-1.4.4.min.js"></script>
<script language = "JavaScript">
<?php
require('../connect.php');
?>

function onSubmit() {
	var msgErr = ""
	if(document.frmMain.ddlCourse.value == "") {
		msgErr += "กรุณาเลือกวิชา \n" 
	}
	if($("#ddlSection").val() == "") {
		msgErr += "กรุณาเลือกตอน \n"
	}
	if(document.frmMain.fileupload.value == "") {
		msgErr += "คุณยังไม่ได้ใส่ลิงค์ไฟล์ในการอัพโหลด \n"
	}else {
		var myArr = document.frmMain.fileupload.value.split(".")
		var ext = myArr[myArr.length - 1]
		if(ext != "xlsx" )
			msgErr += "กรุณาเลือกไฟล์นักศึกษา excel(.xlsx) เท่านั้น!! \n"
	}
	if(msgErr != "") {
		alert(msgErr)
		return false;
	}else
		return true;
}

//**** List Section (Start) ***//
function ListSection(SelectValue)
{
frmMain.ddlSection.length = 0
//*** Insert null Default Value ***//
var myOption = new Option('เลือกตอน','') 
frmMain.ddlSection.options[frmMain.ddlSection.length]= myOption
<?php

//แก้ไขตอนไม่ออก
$intRows = 0;
$strSQL = "SELECT * FROM course_section ";
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
?>                                                                  
}
//**** List Section (End) ***//
</script>
</head>

<body>
<br />
<center>
  <span class="style1">นำเข้าข้อมูลนักศึกษา</span>
</center>
<br />

<?php 
if(isset($_POST['ok2'])){
	if($_POST['ok2'] == "ตกลง"){
	}
} 
?>			
<?php
function importdata(){
	//require_once '../read-excel/reader.php';
	//require_once '../exel/excel_reader2.php';
	require_once '../Classes/PHPExcel/IOFactory.php';
	require("../connect.php");
	//$reader = new Spreadsheet_Excel_Reader();
	//$reader->setOutputEncoding('utf-8');
	//$reader->setUTFEncoder('iconv');

	$fileupload = $_FILES['fileupload']['tmp_name'];		
	$fileupload_name=$_FILES['fileupload']['name'];
	$course = $_POST['ddlCourse'];
	$sec = $_POST['ddlSection'];

	chmod($fileupload, 0755);
	chmod($fileupload_name, 0755);
	copy($fileupload,$fileupload_name);
	//$data = new Spreadsheet_Excel_Reader($fileupload);
	
	$objPHPExcel = PHPExcel_IOFactory::load($fileupload);
	foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
    $worksheetTitle     = $worksheet->getTitle();
    $highestRow         = $worksheet->getHighestRow(); // e.g. 10
    $highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
    $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
    $nrColumns = ord($highestColumn) - 64;
    //echo "<br>The worksheet ".$worksheetTitle." has ";
    //echo $nrColumns . ' columns (A-' . $highestColumn . ') ';
    //echo ' and ' . $highestRow . ' row.';
    //echo '<br>Data: <table border="1"><tr>';
    for ($row =8; $row <= $highestRow; ++ $row) {
        //echo '<tr>';
        //for ($col = 1; $col < $highestColumnIndex; ++ $col) {
        	$cell = $worksheet->getCellByColumnAndRow(1, $row);
            $no = $cell->getValue();
            $cell = $worksheet->getCellByColumnAndRow(2, $row);
            $code = $cell->getValue();
             $cell = $worksheet->getCellByColumnAndRow(3, $row);
            $fname = $cell->getValue();
            $cell = $worksheet->getCellByColumnAndRow(4, $row);
            $lname = $cell->getValue();
            //$dataType = PHPExcel_Cell_DataType::dataTypeForValue($val);
        
			$fullnames=$fname."  ".$lname;
			//$dob = mysqli_real_escape_string($connection,$data->sheets[$i][cells][$j][4]);
			//$query = "insert into excel(eid,name,email,dob) values('".$eid."','".$name."','".$email."','".$dob."')";
 
			//mysqli_query($query);
			if ($no>0 && $no<=200) {
			
			$stu="SELECT *  from student Where studentid='$code'";
				$studentcheck=mysql_query($stu);
				$reg="SELECT * from register where courseId='$course' and sectionId='$sec' and studentId='$code'";
				$regischeck=mysql_query($reg);
				$rowstudent=mysql_num_rows($studentcheck);
				$rowregist=mysql_num_rows($regischeck);
			if ($rowstudent==0 ) {
					$regis = "insert into register (studentId,courseId,sectionId) values ('$code','$course','$sec') ";
					mysql_query($regis);
					
					$command = "insert into student (studentId,studentName) values ('$code','$fullnames') ";
					mysql_query($command);
					
					}elseif ($rowstudent>=1 && $rowregist==0) {
						$regis = "insert into register (studentId,courseId,sectionId) values ('$code','$course','$sec') ";
						mysql_query($regis);
				

				}
      	 }
        
    	}
    }
//}
	//echo "Total Sheets in this xls file: ".count($data->sheets)."<br /><br />";
	//echo "Total Sheets in this xls file: ".count($data->sheets[$i][cells])."<br /><br />";
	//for($i=0;$i<count($data->sheets);$i++) // Loop to get all sheets in a file.
/*{	
	//if(count($data->sheets[$i]['cells'])>0) // checking sheet not empty
	//{
		//echo "Sheet $i:<br /><br />Total rows in sheet $i  ".count($data->sheets[$i]['cells'])."<br />";
		//for($j=7;$j<=count($data->sheets[$i]['cells']);$j++) // loop used to get each row of the sheet
		{ 
			//$html.="<tr>";
			//for($k=1;$k<=count($data->sheets[$i][cells][$j]);$k++) // This loop is created to get data in a table format.
			//{
			//	$html.="<td>";
			//	$html.=$data->sheets[$i][cells][$j][$k];
			//	$html.="</td>";
			//}
			//$code = mysqli_real_escape_string($connection,$data->sheets[$i][cells][$j][3]);
			//$fname = mysqli_real_escape_string($connection,$data->sheets[$i][cells][$j][4]);
			//$lname = mysqli_real_escape_string($connection,$data->sheets[$i][cells][$j][5]);
			//$name=$fname."  ".$lname;
			$code = $data->sheets[$i]['cells'][$j][3];
			$fname = $data->sheets[$i]['cells'][$j][4];
			$lname = $data->sheets[$i]['cells'][$j][5];
			$fullname=$fname."  ".$lname;
			//$dob = mysqli_real_escape_string($connection,$data->sheets[$i][cells][$j][4]);
			//$query = "insert into excel(eid,name,email,dob) values('".$eid."','".$name."','".$email."','".$dob."')";
 
			//mysqli_query($query);
			$stu="SELECT *  from student Where studentid='$code'";
				$studentcheck=mysql_query($stu);
				$reg="SELECT * from register where courseId='$course' and sectionId='$sec' and studentId='$code'";
				$regischeck=mysql_query($reg);
				$rowstudent=mysql_num_rows($studentcheck);
				$rowregist=mysql_num_rows($regischeck);
			if ($rowstudent==0 ) {
					$regis = "insert into register (studentId,courseId,sectionId) values ('$code','$course','$sec') ";
					mysql_query($regis);
					
					$command = "insert into student (studentId,studentName,studentPw) values ('$code','$fullname','$pwd') ";
					mysql_query($command);
					
					}elseif ($rowstudent>=1 && $rowregist==0) {
						$regis = "insert into register (studentId,courseId,sectionId) values ('$code','$course','$sec') ";
						mysql_query($regis);
				

				}
			
		}
	}
 
}*/
 
	/*copy($fileupload,$fileupload_name);

			//$reader->read("$fileupload_name");	
			require("../connect.php");
			$course = $_POST['ddlCourse'];
			$sec = $_POST['ddlSection'];
			/*
			$stu="select studentId,studentName  from student";
			$studentcheck=mysql_query($stu);
			$reg="select studentId,courseId from register";
			$regischeck=mysql_query($reg);
			while($objstudentcheck = mysql_fetch_array($studentcheck))
						{
						?>
						<?php 	$stuid=$objstudentcheck["studentId"];
							$stuname=$objstudentcheck["studentName"];
						*/?>						
					
						<?php
						//$k=$reader->sheets[0]["numRows"];
						//$code =$reader->sheets[0]["cells"][8][3];
						/*$i = 8;
						$fullnames =$reader->sheets[0]["cells"][$i][4];
			//for ($i = 8; $i <= $reader->sheets[0]["numRows"] && $reader->sheets[0]["cells"][$i][2] >=1 && $reader->sheets[0]["cells"][$i][2]<=200; $i++) {
				while ( $reader->sheets[0]["cells"][$i][2] >=1 && $reader->sheets[0]["cells"][$i][2]<=200 && $reader->sheets[0]["cells"][$i][2]!="") {
					# code...
				
				$code =$reader->sheets[0]["cells"][$i][3];
				$fullname =$reader->sheets[0]["cells"][$i][4]."  ".$reader->sheets[0]["cells"][$i][5];
				echo "fullname is ".$fullname;
				 // ค่าเลข random
			  	$pwd = random(4);
			  	$stu="SELECT *  from student Where studentid='$code'";
				$studentcheck=mysql_query($stu);
				$reg="SELECT * from register where courseId='$course' and sectionId='$sec' and studentId='$code'";
				$regischeck=mysql_query($reg);
				$rowstudent=mysql_num_rows($studentcheck);
				$rowregist=mysql_num_rows($regischeck);

			  	$regisstatus=0;
				/*while($objregischeck = mysql_fetch_array($regischeck))
						{						
						 	$stuid=$objregischeck["studentId"];
						 	$courseregisid=$objregischeck["courseId"];

							if($stuid!=$code && $courseregisid!=$course){
								$regisstatus=1; //เมื่อยังไม่เคยลงทะเบียน
								}elseif ($stuid==$code && $courseregisid != $course) {
										$regisstatus=2;  // ลงทะเบียนเพิ่ม
								}
							}
							*/					
						
				// ลงทะเบียนนักศึกษา
				/*if ($rowstudent==0 ) {
					$regis = "insert into register (studentId,courseId,sectionId) values ('$code','$course','$sec') ";
					mysql_query($regis);
					
					$command = "insert into student (studentId,studentName,studentPw) values ('$code','$fullname','$pwd') ";
					mysql_query($command);
					
					}elseif ($rowstudent>=1 && $rowregist==0) {
						$regis = "insert into register (studentId,courseId,sectionId) values ('$code','$course','$sec') ";
						mysql_query($regis);
				

				}
				/*$regis = "insert into register (studentId,courseId,sectionId) values ('$code','$course','$sec') ";
				mysql_query($regis);
				
				$command = "insert into student (studentId,studentName,studentPw) values ('$code','$fullname','$pwd') ";
				mysql_query($command);*/
				//$i++;
			//}
			
			unlink ($fileupload_name);
			mysql_close();
			//echo $code." name is  ".$k.$fullnames;
?>
     <div id="checkErr">ระบบได้ทำการนำเข้าข้อมูลนักศึุกษาเรียบร้อยแล้ว</div><br /><br />
<?php
}

formimport();

if(isset($_POST['ok'])){
	importdata();
	showimport();
}


function formimport(){
?>
		<?php
		require('../connect.php');
		?>
            <form action="" method="post" enctype="multipart/form-data" name="frmMain" id="frmMain" onsubmit="return onSubmit()">
              <table width="400" height="150" border="1" align="center" cellpadding="0" cellspacing="0">
                <tr>
                  <td height="30" colspan="2" align="center" bgcolor="#425969"><span class="style2">กรุณาเลือกไฟล์นำเข้าข้อมูลนักศึกษา</span></td>
                </tr>
                <tr>
                  	<td width="180" height="20" align="right" bgcolor="#E0F1FC">เลือกวิชาลงทะเบียน :&nbsp;</td>
                  	<td bgcolor="#E0F1FC"><label>
                    
					<select id="ddlCourse" name="ddlCourse" onChange = "ListSection(this.value)">
						<option value="">เลือกวิชา</option>
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
						<option value="">เลือกตอน</option>
					</select>

                  </label></td>
                </tr>
                <tr>
                  <td width="180" height="41" align="right" bgcolor="#FFFFFF"><br />
                  ไฟล์นำเข้า : &nbsp;</td>
                  <td width="220" bgcolor="#FFFFFF"><br />
                  <input type="file" name="fileupload"  />				  </td></tr>
                <tr>
                  <td colspan="2" align="center" bgcolor="#BDE3F9"><font color="#FF0000">* รับได้เฉพาะไฟล์นักศึกษา excel(.xls) เท่านั้น!! *</font></td>
                </tr>
                <tr>
                  <td colspan="2" align="center" bgcolor="#99CCFF">
                    <br />
                    <input type="submit" name="ok" value="ตกลง" />
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <input type="reset" name="cancel" value="ยกเลิก" />
                  <br />
                  <br /></td>
                </tr>
              </table>
			  <br />
          </form>
<?php
} 
 ?>
<?php
function showimport(){
		   ?>
		   
	  <form id="form1" name="form1" method="post" action="">
	  <table width="550" height="75" border="1" align="center" cellpadding="0" cellspacing="0">
		<tr>
		  <td width="50" height="25"  align="center" bgcolor="#425969"><span class="style2">ลำดับ</span></td>
		  <td width="100" height="25"  align="center" bgcolor="#425969"><span class="style2">รหัสนักศึกษา</span></td>
		  <td width="250" align="center" bgcolor="#425969"><span class="style2">ชื่อ - นามสกุล</span></td>
		  <td width="150" align="center" bgcolor="#425969"><span class="style2">อีเมล</span></td>
		</tr>
	<?php 
	require('../connect.php');
	$course2 = $_POST['ddlCourse'];
	$sec2 = $_POST['ddlSection'];
	$sql = "select s.studentId As stu,studentName,email from student As s,register As r where s.studentId=r.studentId and r.courseId='$course2' and r.sectionId='$sec2' order by s.studentId asc";
	$result = mysql_query($sql) or die (mysql_error());
	$i=0;
	while($row = mysql_fetch_array($result))
	{
		$i++;
	?>
		<tr>
		  <td align="center" bgcolor="#FFFFFF"><?php echo $i ?></td>
		  <td align="center" bgcolor="#FFFFFF"><?php echo $row['stu'] ?></td>
		  <td bgcolor="#FFFFFF"><?php echo $row['studentName'] ?></td>
		  <td bgcolor="#FFFFFF" align="center"><?php echo $row['email'] ?> </td>
		</tr>
	<?php
	}
	?>
		<tr>
		 <td height="26" colspan="4" align="center" bgcolor="#99CCFF">
		   <input type="submit" value="ตกลง" name="ok2" />
		</td>
		</tr>	
	  </table>
	  </form>
<?php	
} // end of showimport
mysql_close();
?>

<?php
function random($len) {  
	$chars = "1234567890"; 
	$ret_str = ""; 
	$num = strlen($chars); 
	for($i=0; $i < $len; $i++) { 
		$ret_str.= $chars[rand()%$num]; 
	} 
	return $ret_str; 
} 
?>		  
</body>
</html>
