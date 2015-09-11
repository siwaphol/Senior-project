
<<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>


<?php
session_start();
include("../connect.php");

?>

<script type="text/javascript" src="../js/jquery-1.4.4.min.js">
	
</script>
<script type="text/javascript">
	$(function(){
		$(".control").live("mouseover",function(){
			var pos = $(this).offset();
			var left = pos.left + 20 + "px";
			var top = pos.top + 20 + "px";
			$("div#alt").css({
				"zIndex" : 500 ,      
				"left": left,                  
				"top": top 
			})
			$("div#alt").text($(this).attr('alt'))
			$("div#alt").show()
		})
		
		$(".control").live("mouseout",function(){
			$("div#alt").hide()
		})
	})
	
	function newfolder(msg){
	var folder=prompt(msg,"");
	if ((folder!="") && (folder!=null))
	{
		document.listform.action="?<?php echo $_SERVER['QUERY_STRING']?>&newfolder=" + escape(folder);
		document.listform.submit();
	}
	}
	
	function deleted(){
		if(!noChecked())	return false
		if(confirm('ยืนยันการลบ')) {
			$("form#listform").append(
				$("<input>").attr({
					"type" : "hidden",
					"name" : "deleted"
				})
			)
			$("#listform").submit()
		}
	} 
	function download(){
		if(!noChecked())	return false
		if(confirm('ยืนยันการดาวน์โหลด')) {
			$("form#listform").append(
				$("<input>").attr({
					"type" : "hidden",
					"name" : "download1"
				})
			)
			$("#listform").submit()
		}/*
		var count = noChecked()
		if(!count)	return false
		if(count == 1){
			document.listform.action="?<?php echo $_SERVER['QUERY_STRING']?>&download1";
			document.listform.submit();	
			}
		}else alert("เลือกหนึ่งรายการเท่านั้น!!")	
		*/
	} 
	function noChecked() {
		var count = 0;	
		$("#listform tr").find(" :checked").each(function(){
			oldname = $(this).parent().parent().find("td:eq(1)").text()
				count++;
		})
		if(count == 0){
			alert("กรุณาเลือกไฟล์")
		}
		return count
	}
	
	function rename(msg){
		var count = noChecked()
		if(!count)	return false
		if(count == 1){
			var file=prompt(msg, oldname);
			if((file!="") && (file!=null))
			{
						document.listform.action="?<?php echo $_SERVER['QUERY_STRING']?>&rename=" + escape(file);
						document.listform.submit();	
			}
		}else alert("เลือกหนึ่งรายการเท่านั้น!!")	
	}

</script>

<?php
	   include("../config/config.php");
    	include_once("../student/download.php");
    	
	   $year = $yearConfig2;
	   $semester = $semesterConfig2;
	   $rootFolder = $rootFolder2;
	   $binFolder= $binFolder;
	   $course = $_GET['course'];
	   $studentId = $_SESSION['user'];
		
	   $regis = "SELECT sectionId FROM register WHERE courseId = '$course' AND studentId = '$studentId' ";
	   $resultRegis = mysql_query($regis) or die(mysql_error());
	   $rowRegis = mysql_fetch_array($resultRegis);
	   $section = $rowRegis['sectionId'];
	   
	   $path = $rootFolder.$year."_".$semester."/".$course."/sec".$section."/".$studentId ;
	   $binpath = $binFolder.$year."_".$semester."/".$studentId ;
function rrmdir($dir) { 
   if (is_dir($dir)) { 
     $objects = scandir($dir); 
     foreach ($objects as $object) { 
       if ($object != "." && $object != "..") { 
         if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object); 
       } 
     } 
     reset($objects); 
     rmdir($dir); 
   } 
 }
	   
if(isset($_POST['deletedd'])){ 
	include("../config/config.php");
	   /*$year = $yearConfig2;
	   $semester = $semesterConfig2;
	   $rootFolder = $rootFolder2;
	   $binFolder= $binFolder;
	   $course = $_GET['course'];
	   $studentId = $_SESSION['user'];
	   $binpath = $binFolder."/".$year."_".$semester ;
		$course = $_GET['course'];
		$studentId = $_SESSION['user'];
		*/
		
	if(count($_POST['hwbox']) > 0){
		$hwpath = $_POST['hwbox'];
		for($i=0;$i<sizeof($hwpath);$i++){
			if(is_dir($hwpath[$i])){
					rrmdir($hwpath[$i]);
			} else
			    // move file to bin
				$hw = basename($hwpath[$i]);
				$hwname = substr($hw,0,strpos($hw,"-"));
			    if(!is_dir("$binpath")){
				mkdir("$binpath");
				}
				$binpath.="/";
				copy($hwpath[$i],$binpath.$hw);
				//ทำถึงตรงนี้
				$binpath.=$hw;
				$hwnames = substr($hw,0,strpos($hw,"."));
				$checkfilename=" SELECT name FROM fileremove WHERE courseid='$course' AND createby='$studentId' and name='$hw' ";
				$resultchecknames = mysql_query($checkfilename) or die("error " ."$checkfilename".mysql_error());
				$forcheck=5;
				echo "$resultchecknames";
				$numrow=mysql_num_rows($resultchecknames);
				echo "$numrow";
				if(mysql_num_rows($resultchecknames) > 0 ){
				while($rownames=mysql_fetch_array($resultChecknames)){
					 if ($rownames["name"]==$hw) {
					 	$forcheck=1;
					 	$oldfilename=$rownames["name"];
					 	
					 }else{
					 		$forcheck=0;
					 	}
					 	
				}
				}
				if ($numrow>0){
					$deletename="DELETE FROM fileremove WHERE name like '$hw' ";
					$resultdelete=mysql_query($deletename) or die(mysql_error());
					$insertre="INSERT INTO fileremove(name,courseId,createby,path) 
			   		VALUES('$hw','$course','$studentId','$binpath')";
			   		$resultremovename = mysql_query($insertre) or die(mysql_error());
				}elseif ($numrow=0){
					$insertre="INSERT INTO fileremove(name,courseId,createby,path) 
			   		VALUES('$hw','$course','$studentId','$binpath')";
			   		$resultremovename = mysql_query($insertre) or die(mysql_error());
					/*$updatere="UPDATE fileremove SET name=$hw WHERE name=$oldfilename";
					$resultupdatere=mysql_query($updatere) or die(mysql_error());
					*/
				}
				
				//deletefile 
				unlink($hwpath[$i]);
				$hw = basename($hwpath[$i]);
				$hwname = substr($hw,0,strpos($hw,"-"));
				$sqlCheckname = "SELECT homeworkId FROM homework_assignment WHERE courseId = '$course' AND homeworkFileName = '$hwname' ";
				$resultCheckname = mysql_query($sqlCheckname) or die(mysql_error());
				$rowCheckname = mysql_fetch_array($resultCheckname);
				$delSend = "DELETE FROM homework_sending 
				WHERE homeworkId = '$rowCheckname[homeworkId]' AND courseId = '$course' AND studentId = '$studentId'";
				mysql_query($delSend) or die (mysql_error());
		}
	}
}else if(isset($_POST['deleted'])){ 
	if(count($_POST['hwbox']) > 0){
		//$course = $_GET['course'];
		//$studentId = $_SESSION['user'];
		$hwpath = $_POST['hwbox'];
		for($i=0;$i<sizeof($hwpath);$i++){
			if(is_dir($hwpath[$i])){
					rrmdir($hwpath[$i]);
			} else{
				$hw = basename($hwpath[$i]);
				$hwname = substr($hw,0,strpos($hw,"-"));
			    if(!is_dir("$binpath")){
				mkdir("$binpath");
				}
				$binpath.="/";
				copy($hwpath[$i],$binpath.$hw);
				$binpath.=$hw;

				$hwnames = substr($hw,0,strpos($hw,"."));
				$checkfilename=" SELECT name FROM fileremove WHERE courseid='$course' AND createby='$studentId' and name='$hw' ";
				$resultchecknames = mysql_query($checkfilename) or die("error " ."$checkfilename".mysql_error());
				//$forcheck=5;
				//echo "$resultchecknames";
				$numrow=mysql_num_rows($resultchecknames);
				//echo "$numrow";
				/*if(mysql_num_rows($resultchecknames) > 0 ){
				while($rownames=mysql_fetch_array($resultChecknames)){
					 if ($rownames["name"]==$hw) {
					 	$forcheck=1;
					 	$oldfilename=$rownames["name"];
					 	
					 }else{
					 		$forcheck=0;
					 	}
					 	
				}
				}*/
				if ($numrow>=1){
					$deletename="DELETE FROM fileremove WHERE name like '$hw' ";
					$resultdelete=mysql_query($deletename) or die(mysql_error());
					$insertre="INSERT INTO fileremove(name,courseId,createby,path) 
			   		VALUES('$hw','$course','$studentId','$binpath')";
			   		$resultremovename = mysql_query($insertre) or die(mysql_error());
				}elseif ($numrow<1){
					$insertre="INSERT INTO fileremove(name,courseId,createby,path) 
			   		VALUES('$hw','$course','$studentId','$binpath')";
			   		$resultremovename = mysql_query($insertre) or die(mysql_error());
					/*$updatere="UPDATE fileremove SET name=$hw WHERE name=$oldfilename";
					$resultupdatere=mysql_query($updatere) or die(mysql_error());
					*/
				}
				unlink($hwpath[$i]);
				$hw = basename($hwpath[$i]);
				$hwname = substr($hw,0,strpos($hw,"-"));
				$sqlCheckname = "SELECT homeworkId FROM homework_assignment WHERE courseId = '$course' AND homeworkFileName = '$hwname' ";
				$resultCheckname = mysql_query($sqlCheckname) or die(mysql_error());
				$rowCheckname = mysql_fetch_array($resultCheckname);
				$delSend = "DELETE FROM homework_sending 
				WHERE homeworkId = '$rowCheckname[homeworkId]' AND courseId = '$course' AND studentId = '$studentId'";
				mysql_query($delSend) or die (mysql_error());
		}
	}
}

}elseif($_GET['newfolder']) {
	$newFolderPath = $path;
	if($_GET['dir']) {
		$newFolderPath .= $_GET['dir'];
	} 
	$newFolderPath .= "/".$_GET['newfolder'];
	if(is_dir($path)){
		mkdir($newFolderPath);
	}
}elseif($_GET['rename']){
	$hwid = $_POST['hwbox'][0];
	$dirname = dirname($hwid);
	$newname = $_GET[rename];
	$newname = $dirname."/".$newname;
	rename($hwid ,$newname);
}if(isset($_POST['download1'])){ 	
		$hwpath = $_POST['hwbox'];
		$i=0;
		
			//$hwid = $_POST['hwbox'][0];
	//$dirname = dirname($hwid);
	$dirname = dirname($hwpath[$i]);
	$hw = basename($hwpath[$i]);
	//$dir = $dirname."/".$hw;
	$dir = $dirname."\\".$hw;
		//echo $dir;
			if (is_dir($dir)) {
  			 echo "    the folder is NOT empty";
			}else{
  				//echo "the folder is empty";
  				}  			
  				/*ini_set('mbstring.internal_encoding','UTF-8');
    			ini_set('mbstring.http_input','auto');
    			ini_set('mbstring.http_output','UTF-8');
   			 	ini_set('mbstring.detect_order','auto');
    			ini_set('default_charset','UTF-8');
    			*/
				//set_time_limit(0); 
        		//ini_set('memory_limit', '512M'); 
        		//include_once("../student/download13.php");
        		download($dir);
        		//downloadFile($dir);
        		//include_once("../student/download14.php");
        		//output_file($dir,$hw,$mime_type);
        		//$file_url = $dir
				//header('Content-Type: application/octet-stream');
				//header("Content-Transfer-Encoding: Binary"); 
				//header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\""); 
				//readfile($file_url);
    	//$files_to_zip = array();
//if true, good; if false, zip creation failed
//$result = create_zip($dir,$hw.'.'.'zip');

           /* $filename = realpath($dir);

            $file_extension = strtolower(substr(strrchr($filename,"."),1));

            switch ($file_extension) {
                case "pdf": $ctype="application/pdf"; break;
                case "exe": $ctype="application/octet-stream"; break;
                case "zip": $ctype="application/zip"; break;
                case "doc": $ctype="application/msword"; break;
                case "xls": $ctype="application/vnd.ms-excel"; break;
                case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
                case "gif": $ctype="image/gif"; break;
                case "png": $ctype="image/png"; break;
                case "jpe": case "jpeg":
                case "jpg": $ctype="image/jpg"; break;
                default: $ctype="application/force-download";
            }

            if (!file_exists($filename)) {
                die("NO FILE HERE");
            }

            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Cache-Control: private",false);
            header("Content-Type: $ctype");
            header("Content-Disposition: attachment; filename=\"".basename($filename)."\";");
            header("Content-Transfer-Encoding: binary");
            header("Content-Length: ".@filesize($filename));
            set_time_limit(0);
            @readfile("$filename") or die("File not found.");
		*/
	}

/* creates a compressed zip file */
/*
function create_zip($files = array(),$destination = '',$overwrite = false) {
	//if the zip file already exists and overwrite is false, return false
	if(file_exists($destination) && !$overwrite) { return false; }
	//vars
	$valid_files = array();
	//if files were passed in...
	if(is_array($files)) {
		//cycle through each file
		foreach($files as $file) {
			//make sure the file exists
			if(file_exists($file)) {
				$valid_files[] = $file;
			}
		}
	}
	//if we have good files...
	if(count($valid_files)) {
		//create the archive
		$zip = new ZipArchive();
		if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
			return false;
		}
		//add the files
		foreach($valid_files as $file) {
			$zip->addFile($file,$file);
		}
		//debug
		//echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
		
		//close the zip -- done!
		$zip->close();
		
		//check to make sure the file exists
		return file_exists($destination);
	}
	else
	{
		return false;
	}
}*/
	

?>
<center>
<span class="style1">กรุณาเลือกรายละเอียดการบ้านที่ต้องการส่ง</span>
<br />
<br />
</center>
<table width="600" border="1" align="center" cellpadding="2" cellspacing="0" bordercolor="#425969">
<thead>
<tr>
  <td height="31" colspan="5" align="center" bgcolor="#425969"><span class="style2">ผลการส่งการบ้าน</span></td>
</tr>
<th>ลำดับ</th>
<th>รายละเอียด</th>
<th>ชื่อไฟล์</th>
<th>วันกำหนดส่ง</th>
<th>สถานะ</th>
</thead>
<tbody>
<?php 
//$course = $_GET['course'];
//$studentId = $_SESSION['user'];
$sqlAssign = "SELECT homeworkId, homeworkFileName, homeworkFileType, homeworkDetail, dueDate FROM homework_assignment 
WHERE courseId = '$course' ";
$resultAssign = mysql_query($sqlAssign);

while($rowAssign = mysql_fetch_array($resultAssign) ) {
	$sqlSending = "SELECT sendStatus FROM homework_sending 
WHERE courseId = '$course' AND studentId = '$studentId' AND homeworkId = '$rowAssign[homeworkId]' ";
	$resultSending = mysql_query($sqlSending);
	$rowSending = mysql_fetch_array($resultSending)
?>
<tr>

	<td align="center"><?php echo $rowAssign['homeworkId']?></td>
	<td><a href="index_student.php?upload&course=<?php echo $course?>&homeworkId=<?php echo $rowAssign['homeworkId']?>"><?php echo $rowAssign['homeworkDetail']?></a></td>
	<td><?php echo $rowAssign['homeworkFileName']?>_<?php echo $studentId?>.<?php echo $rowAssign['homeworkFileType']?></td>
	<td><?php echo $rowAssign['dueDate']?></td>
	<?php
	if(mysql_num_rows($resultSending) > 0){
	// ถ้ามีการบ้านส่ง?>
	<td align="center"><?php 
						if($rowSending['sendStatus'] == 1) {
							echo '<font color="#00CC00">ส่งแล้ว</font>'; 
						}elseif($rowSending['sendStatus'] == 0.5) {
							echo '<font color="#FFFF00">ส่งเกินกำหนด</font>'; 
						}?></td>
	<?php 
		$disable = ""; 
	}else { 
	// ไม่มีการบ้านส่ง ?>
		<td align="center"><font color="#FF0000">ไม่ได้ส่ง</font></td>
	<?php		
	}
	?>

	
</tr>
<?php 
}
?>
</tbody>
</table>


<br />
<form action="?<?php echo $_SERVER['QUERY_STRING']?>" id="listform" method="post" name="listform" >
<table width="500" border="1" align="center" cellpadding="2" cellspacing="0" bordercolor="#425969">
<thead>
<tr>
  <td height="31" colspan="5" align="center" bgcolor="#425969"><span class="style2">รายละเอียดการบ้าน</span></td>
</tr>
<tr>
	<?php
		if(isset($_GET['dir'])){
			$dir = str_replace("\\\\","\\",$_GET['dir']);
		}else{
			$dir = "\\";
		} 
	?>
	
 	<td colspan="5" height="30px" valign="middle" align="center" bgcolor="#99CCFF">
		<div align="left">ตำแหน่งไดเรคทอรี : <input  type="text" value="<?php echo $dir?>" readonly="readonly" /></div>
		<div style="margin-top:-20px; height:20px;" align="right">
				<img src="../images/download.png" class="control" alt="ดาวน์โหลด" onclick="download()" align="absmiddle"  />&nbsp;
				<img src="../images/ren.gif" class="control" alt="เปลี่ยนชื่อ" onclick="rename('เปลี่ยนชื่อ')" align="absmiddle"  />&nbsp;
				<img alt="ลบไฟล์ที่เลือก" class="control" src="../images/recycle.gif"  align="absmiddle" onclick="deleted()" />&nbsp;
		</div>
    </td>
</tr>
<th width="30" height="30px" valign="middle" align="center"><?php if(isset($_GET['dir'])) {
		$url = $_SERVER['QUERY_STRING'];
		if(isset($_GET['dir'])){
			$dir = explode("\\\\", $_GET['dir']);
			if(count($dir) > 2)
				$realURL = substr($url, 0, strrpos($url,"/"));
			else
				$realURL = substr($url, 0, strrpos($url,"&"));		
		}	

	?><img alt="กลับไป" class="back control" src="../images/back.gif" style="cursor:pointer" onclick="window.location='index_student.php?<?php echo $realURL?>'" /><?php } ?></th>
<th>ชื่อไฟล์</th>
<th>ขนาด(KB)</th>
<th>วันที่ส่ง</th>
<th>เวลาที่ส่ง</th>

</thead>
<tbody>
<?php 
	   
	   if(isset($_GET['dir'])){
	   		$path .= $_GET['dir'];
	   }

function loadFile($path) {
	$dirArr = array();
	$fileArr = array();
	if(is_dir($path)){
		if($dh = opendir($path)){
			while($file = readdir($dh)){
				
				if($file == ".." || $file == ".")
					continue;
				$fullnamefile = $path.'/'.$file;
				$filetime = filemtime($fullnamefile);
				$filesize = filesize($fullnamefile);
				$file_kb = round(($filesize/1024),2);
				$filetype = filetype($fullnamefile);
				$myArray = array(
					"file" => $file,
					"fullnamefile" => $fullnamefile,
					"filetime" => $filetime,
					"file_kb" => $file_kb,
					"filetype" => $filetype
				);
				if(strtolower($filetype) == "dir"){
					$dirArr[] = $myArray;
				}else{
					$fileArr[] = $myArray;
				}
			} //ปิด while
			closedir($dh);
		} //ปิด if ใน
	} //ปิด if นอก
	$result = array_merge($dirArr, $fileArr);
	return $result;
}

	$fileArr = loadFile($path);
	for($i = 0; $i < count($fileArr); $i++) {
	$fileitem = $fileArr[$i];
	$file = $fileitem['file'];
	$fullnamefile = $fileitem['fullnamefile'];
	$filetime = $fileitem['filetime'];
	$file_kb = $fileitem['file_kb'];
	$filetype = $fileitem['filetype'];
	if($filetype == "dir"){	
		$src = "../images/item/folder.gif";
		$bgColor = "#F0FAFF";
	}else{
		$src = "../images/item/file.gif";
		$bgColor = "#FFFFFF";
	}
?>
			<tr bgcolor="<?php echo $bgColor?>">
			<td width="50px" align="center"><input type="checkbox" name="hwbox[]" value="<?php echo str_replace("\\\\","/",$fullnamefile)?>" />&nbsp;<img src="<?php echo $src?>" /></td>
					<?php if(is_dir($fullnamefile)) {
						$dir = "";
						if(isset($_GET['dir'])){
							$dir = str_replace("\\\\", "/", $_GET['dir']);
						}
						$dir .= "/".$file;
					?>
						<td><a href="?register&course=<?php echo $course?>&section=<?php echo $section?>&assign=<?php echo $_GET['assign']?>&dir=<?php echo $dir?>"> <?php echo $file; ?></a></td>
					<?php } else {?>
						<td><?php echo $file; ?></td>
					<?php }?>
					
					<td align="center"><?php echo $file_kb; ?></td>
					<td align="center"><?php echo date("d F Y",$filetime); ?></td>
					<td align="center"><?php echo date("H:i:s",$filetime); ?></td>	
			</tr>
<?php
	}// ปิด for
?>
</tbody>
</table>
	<table width="500" border="1" align="center" cellspacing="0" bordercolor="#425969">
	<tr>
	  <td colspan="5" align="center" bgcolor="#99CCFF">
	  <a href="#top">กลับสู่ด้านบน</a>				  </td>
	</tr>
	</table>
</form>
<div id="alt" style="position:absolute;display:none; background-color: #FFFFCC; border:inset 1px"></div>
</body>
</html>>