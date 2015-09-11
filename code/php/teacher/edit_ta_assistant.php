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
<script type="text/javascript">

function fixkey(){
	 if(event.keyCode < 46 || event.keyCode > 57 || event.keyCode == 47) { 
	  event.returnValue = false;
	 }
}

	$(function(){
		$("div.ddlTeacher").html($("div#divDDLTeacher").html())
		
		$("input.addTA").live('click', function(){
			var left = $(this).parent().parent().find("td:eq(0)");
			var countTagP = left.find("p").length + 1
			left.append("<p><br /> นักศึกษาช่วยสอน#" + countTagP + " :&nbsp; </p>")
			var right = $(this).parent()
			right.find("span").append($("<p>")
				.append($("<label>")
					.addClass("labelDDLTA")
					.append($("#divDDLTA").html())
				)
				.append("&nbsp;")
				.append($("<img>")
					.attr({
						src : "../images/close.gif",
						height : 15,
						width : 15,
						alt : "ลบนักศึกษาช่วยสอน"
					})
					.addClass('delTA')
				)
				
				.append('<br />')
					.append($('<font>')
						.attr("color", "#FF0000")
						.append($('<label>')
							.addClass('taClass')
						)
					)
				.append('<br />')
			)
			//.append($("</p>"));
		})
		
		$("img.delTA").live('click', function(){
			if(confirm('ยืนยันการลบนักศึกษาช่วยสอน')){
				$(this).parent().parent().parent().parent().find("td:eq(0) p:last").remove()
				$(this).parent().remove()
			}
		})
		
	});
	
	$(".ddlTA").live("change", function(){
		if($(this).val() != 0) {
			value = $(this).val()
			index = $(this).parent().parent().index()
			flag = true
			$(this).parent().parent().parent().find(".ddlTA").each(function() {
				otherIndex = $(this).parent().parent().index()
				if($(this).val() == value && flag && otherIndex != index) {
					flag = false
					alert("นักศึกษาช่วยสอนซ้ำกัน")
				}
			})
			if(!flag)
				$(this).val("0")
		}
	})
	
	function onSubmit(){
		//$("div#param").text($("#form2").serialize())
		var msgErr = "" //// false
		
		// ตรวจสอบช่องว่าง
		
		if($("#cid").val() == "0"){	//msgErr 
			//$("#ciderr").text("รหัสวิชาต้องไม่เป็นช่องว่าง")
			//msgErr = true
			msgErr += "กรุณาเลือกวิชา \n"
		}/*else{
			$("#ciderr").text("")
		}*/
		
		flag = true
		err = ""
		$(".sec").each(function(){
			if($(this).val() == "" && flag){	//msgErr += "\nเซคชันต้องไม่เป็นช่องว่าง"
			//$(this).parent().find("label.csecerr").text("เซคชันต้องไม่เป็นช่องว่าง")
				err = "กรุณาเลือกตอน \n"////true
				flag = false
			}else if($(this).val().length != 3 && flag){
				err = "ตอนต้องเป็นตัวเลข 3 หลักเท่านั้น \n"////true
				flag = false
			}
		})
		msgErr += err
		
		flag = true
		err = ""
		$("#form2 .ddlteacher").each(function(){
			if($(this).val() == "0" && flag){	//msgErr += "\nเซคชันต้องไม่เป็นช่องว่าง"
			//$(this).parent().parent().find("label.teacher").text("กรุณาเลือกอาจารย์")
			err = "กรุณาเลือกอาจารย์\n"
			flag = false
			}//else{
				//$(this).parent().panret().find("label.teacher").text("")
			//}
		})
		msgErr += err
		
		flag = true
		err = ""
		$("#form2 .ddlTA").each(function(){
			if($(this).val() == "0" && flag){	//msgErr += "\nเซคชันต้องไม่เป็นช่องว่าง"
			//$(this).parent().find("label.taClass").text("กรุณาเลือกนักศึกษาช่วยสอน")
			err = "กรุณาเลือกนักศึกษาช่วยสอน\n"//true
			flag = false
			}//else{
				//$(this).parent().find("label.taClass").text("")
			//}
		})
		msgErr += err
		
		
		if(msgErr != ""){
			alert(msgErr)
			return false
		}else{
			return true
		}
	}
	
	$(".sec").live("keypress", function() {
		fixkey()
	})
	
	
</script>
</head>
<body>


<?php
require_once("../connect.php");
// check submit
if( isset($_POST['Submit']) ){
	$course = $_GET['course'];
	$sec = $_GET['sec'];
	$section = $_POST['sec'];
	$teacher = $_POST['ddlTeacher'];
	$ta = $_POST['ddlTA'];
	// for of Check duplicate key course_section
	$check = true;
	for($x = 0 ; $x < count($teacher) && $check != false && $sec != $section; $x++){
		$sqlCheckSec = "SELECT * FROM course_section 
					WHERE courseId = '$course' AND sectionId = '$section' ";
		
		$resultCheckSec = mysql_query($sqlCheckSec) or die(mysql_error());
		if(mysql_num_rows($resultCheckSec) > 0)
			$check = false;
	}
	if($check) {
		$sqlCourseSec = "UPDATE course_section SET sectionId = '$section', teacherId = '$teacher'
					WHERE courseId = '$course' AND  sectionId = '$sec' ";
		mysql_query($sqlCourseSec)or die(mysql_error());
	
			//กรณีที่มี นักศึกษาช่วยสอน
			
			$delTA = "DELETE FROM assistant WHERE courseId = '$course' AND sectionId = '$sec' ";
			mysql_query($delTA);
			
			for($j = 0; $j < count($ta); $j++){
					$sqlAssistant = "INSERT INTO assistant (courseId,sectionId,taId) 
					VALUES('$course','$section','$ta[$j]') ";
					mysql_query($sqlAssistant)or die(mysql_error());
			}
			
		echo "<script> window.location='index_admin.php?course' </script> ";
		
	} else { ?>
		<div id="checkErr">ไม่สามารถอัพเดทข้อมูลได้</div><br />
	<?php }
} // ปิด func addCourse
?>

<?php
$course = $_GET['edit'];
$sec = $_GET['sec'];
$taid= $_GET['ta'];
$csql = "SELECT courseName FROM course WHERE courseId = '$course'";
$cresult = mysql_query($csql);
$row = mysql_fetch_array($cresult)
?>
<form action="" method="post" name="form2" id="form2" onsubmit="return onSubmit()">

<br />
<center>
  <span class="style1">แก้ไขรายละเอียดวิชา</span>
</center>
<br />
        <table id="myTable" width="500" border="1" align="center" cellpadding="0" cellspacing="0">
			<thead>
                  <tr>
                    <td height="31" colspan="3" align="center" bgcolor="#990000"><span class="style2">แบบฟอร์มแก้ไขข้อมูลรายละเอียดวิชา</span></td>
                  </tr>
                  <tr>
                    <td height="50" width="170" align="right" valign="middle" bgcolor="#FFFFFF">
                    	<br />รหัสวิชา : &nbsp;<br /><br />
						ชื่อวิชา : &nbsp;
					</td>
                    <td valign="top" colspan="2" bgcolor="#FFFFFF">
					<br /><input type="text" name="cid" value="<?php echo $course?>" disabled="disabled" />
					<br /><br /><input name="cname" type="text" disabled="disabled" value="<?php echo $row['courseName']?>" size="40" />
					</td>
                  </tr>
            </thead>
			<tbody>  
                  <tr>
                    <td valign="top" align="right" bgcolor="#FFFFFF">&nbsp;
						<br />
						ตอน : &nbsp;<br />
						<br />
						อาจารย์ : &nbsp;
						<?php
						$sql = "SELECT taId FROM assistant_homework WHERE courseId ='$course' and sectionId = '$sec' and taid='$taid' ";
						$result = mysql_query($sql) or die($sql);
						$num = mysql_num_rows($result);
						for($i = 1; $i <= $num; $i++){
						?>
						<p>
						<br />
						นักศึกษาช่วยสอน#<?php echo $i?>
						</p>
						<?php }?>
					</td>
                    <td valign="top" colspan="2" bgcolor="#FFFFFF">
				
			  <br /><input name="sec" value="<?php echo $sec?>" type="text" id="sec" class="sec" maxlength="3" />
			  <br /><font color="#FF0000" ><label class="csecerr"></label></font>
			  <br /><span>	
<?php
			$sql1 = "SELECT teacherId,teacherName FROM teacher";
			$sql2 = "SELECT teacherId FROM course_section WHERE courseId ='$course' and sectionId = '$sec' ";
			$result1 = mysql_query($sql1) or die($sql1);
			$result2 = mysql_query($sql2) or die($sql2);
			echo "<select name='ddlTeacher'>";
			echo "<option value='0'>อาจารย์ผู้สอน</option>";
			$row2 = mysql_fetch_array($result2);
			while($row1 = mysql_fetch_array($result1)){
				echo "<option value='" . $row1['teacherId']."' ";
				if($row2['teacherId']==$row1['teacherId'])
					echo "selected=selected";
				echo " >" . $row1['teacherName'] . "</option>";
			}
			echo "</select>";
			echo "<font color='#FF0000' ><label class='teacher'></label></font><br /><br />";
			

			$sql3 = "SELECT taId FROM assistant WHERE courseId ='$course' and sectionId = '$sec' ";
			$sql4 = "SELECT taId,taName FROM ta ";
			$result3 = mysql_query($sql3) or die($sql3);
			
			while($row3 = mysql_fetch_array($result3)){
			$result4 = mysql_query($sql4) or die($sql4);
?>	
			
				<p>
					<label class="labelDDLTA"><?php
						echo "<select name='ddlTA[]' class='ddlTA'>";
						echo "<option value='0'>นักศึกษาช่วยสอน</option>";
						while($row4 = mysql_fetch_array($result4)) {
							echo "<option value='" . $row4['taId'] . "' ";
							if($row3['taId']==$row4['taId'])
								echo "selected=selected";
							echo " >" . $row4['taName'] . "</option>";
						}
						echo "</select>";
					?></label>&nbsp;
					<img src="../images/close.gif" height="15" width="15" alt="ลบนักศึกษาช่วยสอน" class="delTA" />
					<br />
					<font color='#FF0000' >
						<label class='taClass'></label>
					</font>
					<br />
				</p>
			
<?php }?>
			  </span>

					  <input type="button" class="addTA" value="เพิ่มนักศึกษาช่วยสอน" />
					  <br />
					</td>
				 </tr>
			</tbody>
			<tfoot>
                  <tr>
                    <td valign="middle" height="69" colspan="3" bgcolor="#FFFFFF" align="center">
                        <input type="Submit" name="Submit" value="ตกลง" id="Submit"/>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="button" name="Cancel" onclick="window.location='index_admin.php?course'" value="ยกเลิก" />
					</td>
                  </tr>
				  </tfoot>
              </table>
            </form>

</body>
</html>

<div id="divDDLTA" style="display:none">
<?php
	$sql = "SELECT taId,taName FROM ta ";
	$result = mysql_query($sql) or die($sql);
	echo "<select name='ddlTA[]' class='ddlTA'>";
	echo "<option value='0'>นักศึกษาช่วยสอน</option>";
	while($rows = mysql_fetch_array($result)){
		echo "<option value='" . $rows['taId'] . "' >" . $rows['taName'] . "</option>";
	}
	echo "</select>";
		
mysql_close();
//<input type="button" onclick="$('div#src').text($('#myTable tbody').html())" value="viewSrc" />
?>
</div>

<pre><div id="src"></div></pre>
<div id="param"></div>
