<?php

include("../connect.php");

?>
<script type="text/javascript" src="../js/jquery-1.4.4.min.js"></script>
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
	
	function newfolder(msg){
	var folder=prompt(msg,"");
	var specialChar = Array("\\","/",":","*","\"","<",">","?","|")
	var flag = true;
	for(i=0;i<folder.length;i++) {
		for(j=0;j<specialChar.length;j++){
			if(folder[i] == specialChar[j]) {
				alert("ชื่อโฟลเดอร์ห้ามมีอักขระพิเศษ ดังนี้\n \\ / : * \" < > ? | ")
				flag = false
				break;
			}
		}
	}
	if(!flag) return false
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
	
	function rename(msg){
		var count = noChecked()
		if(!count)	return false
		if(count == 1){
			var file=prompt(msg, oldname);
			if((file!="") && (file!=null))
			{
				document.listform.action ="?<?php echo $_SERVER['QUERY_STRING']?>&rename=" + escape(file);
				document.listform.submit();
			}
		}else alert("เลือกหนึ่งรายการเท่านั้น!!")	
	}
	
</script>
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
.control{
	border: #99CCFF thin solid;
}
.back{
	border: #FFFFFF thin solid;
}
.control:hover{
	border-right: #555555 thin inset; border-top: white thin outset; border-left: white thin outset; border-bottom: #555555 thin inset;
	cursor:pointer;
}
-->
</style>

<?php
	   include("../config/config.php");
	   $year = $yearConfig2;
	   $semester = $semesterConfig2;
	   $rootFolder = $rootFolder2;
	   $course = $_GET['course'];
	   $studentId = $_SESSION['user'];
		
	   $regis = "SELECT sectionId FROM register WHERE courseId = '$course' AND studentId = '$studentId' ";
	   $resultRegis = mysql_query($regis) or die(mysql_error());
	   $rowRegis = mysql_fetch_array($resultRegis);
	   $section = $rowRegis['sectionId'];
	   
	   $path = $rootFolder.$year."_".$semester."/".$course."/sec".$section."/".$studentId ;

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

if(isset($_POST['deleted'])){ 
	if(count($_POST['hwbox']) > 0){
			$hwid = $_POST['hwbox'];
		for($i=0;$i<sizeof($hwid);$i++){
			if(is_dir($hwid[$i])){
				rrmdir($hwid[$i]);
			} else
				unlink($hwid[$i]);
		}		
	}

}elseif($_GET['newfolder']) {
	$newFolderPath = $path;
	if($_GET['dir']) {
		$newFolderPath .= $_GET['dir'];
	} 
	$newFolderPath .= "/".$_GET['newfolder'];
	
	$pathArr = explode("/",$newFolderPath);
   	for($i = 0; $i < count($pathArr); $i++){
	   $tmpPath .= $pathArr[$i];
		if(!is_dir("$tmpPath")){
			mkdir("$tmpPath");
		}
		$tmpPath .="/";
	}
	
}elseif($_GET['rename']){
	$hwid = $_POST['hwbox'][0];
	$dirname = dirname($hwid);
	$newname = $_GET[rename];
	$newname = $dirname."/".$newname;
	rename($hwid ,$newname);
}
?>
<center>
<span class="style1">รายละเอียดการบ้านที่ส่ง</span>
<br />
<br />
</center>


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
	
 	<td colspan="5" height="30" valign="middle" align="center" bgcolor="#99CCFF">
		<div align="left">ตำแหน่งไดเรคทอรี : <input  type="text" value="<?php echo $dir?>" readonly="readonly" /></div>
		<div style="margin-top:-20px; height:20px;" align="right">
				<img src="../images/upload.gif" align="absmiddle" class="control" alt="อัพโหลดการบ้าน" onclick="window.location='index_student.php?upload&course=<?php echo $course?>&dir=<?php echo str_replace("\\", "/", $dir)?>'"  />&nbsp;
				<img src="../images/newfolder.gif" class="control" alt="สร้างโฟลเดอร์ใหม่" onclick="newfolder('สร้างโฟลเดอร์ใหม่')" align="absmiddle"  />&nbsp;
				<img src="../images/ren.gif" class="control" alt="เปลี่ยนชื่อ" onclick="rename('เปลี่ยนชื่อ')" align="absmiddle"  />&nbsp;
				<img alt="ลบไฟล์ที่เลือก" class="control" src="../images/recycle.gif" onclick="deleted()" align="absmiddle"  />&nbsp;
		</div>
    </td>
</tr>
<th width="30" height="30px" valign="middle" align="center"><?php if(isset($_GET['dir'])) {
		$url = $_SERVER['QUERY_STRING'];
		if(isset($_GET['dir'])){
			$dir = explode("\\", $_GET['dir']);
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
					<?php 
					// link open directory
						if(is_dir($fullnamefile)) {
						$dir = "";
						if(isset($_GET['dir'])){
							$dir = str_replace("\\", "/", $_GET['dir']);
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