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
#checkErr {
	color:#FF0000;
	background:#FFFFCC;
	border: solid #425969 1px;
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
		$("#addSecBTN").click(function(){
			$("#myTable").find('tbody')
				.append($('<tr>')
					.append($('<td align=right valign=top>')
						.html('<br />ตอน : &nbsp;<br /><br />อาจารย์ :&nbsp;')
					)
					.append($('<td valign=top>')
						.append($('<div align=right>')
							.append($('<img>')
								.attr({
									src : "../images/close.gif",
									height : 15,
									width : 15,
									alt : "ลบตอน"
								})
								.addClass('closeBTN')
							)
						)
						.append($('<span>')
							.append($('<input>')
								.attr({
									name : "sec[]",
									maxlength : 3
								})
								.addClass("sec")
							)
							.append('<br />')
							/*.append($('<font>')
								.attr("color", "#FF0000")
								.append($('<label>')
									.addClass('csecerr')
								)
							)*/
							.append('<br />')
							.append($('<div>')
								.append($("#divDDLTeacher").html())
							)
							/*.append($('<font>')
								.attr("color", "#FF0000")
								.append($('<label>')
									.addClass('teacher')
								)
							)*/
							.append('<br />')
						)
						.append($('<input>')
							.attr({
								type : "button",
								value : "เพิ่มนักศึกษาช่วยสอน"
							})
							.addClass('addTA')
						)
					)
				);
		});
		
		$("img.closeBTN").live('click', function(){
			if(confirm("ยืนยันการลบตอน")){
				$(this).parent().parent().parent().remove()
			}
		})
		
		$("input.addTA").live('click', function(){
			var left = $(this).parent().parent().find("td:eq(0)");
			var countTagP = left.find("p").length + 1
			left.append("<p><br /> นักศึกษาช่วยสอน#" + countTagP + " :&nbsp; </p>")
			var right = $(this).parent()
			right.find("span").append($("<p>")
				.append($("#divDDLTA").html())
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
					/*.append($('<font>')
						.attr("color", "#FF0000")
						.append($('<label>')
							.addClass('taClass')
						)
					)*/
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
		
		$("#Submit").click(function(){
			$("#myTable tbody").find("tr").each(function(){
				var length = $(this).find("select.ddlTA").length
				$("form#form2")
					.append($("<input>")
						.attr({
							name : "countTA[]",
							value : length,
							type : "hidden"
						})
					)
			})
		})
	});
	
	$(".sec").live("blur", function(){
		if($(this).val() != "") {
			value = $(this).val()
			index = $(this).parent().parent().parent().index()
			flag = true
			$(".sec").each(function() {
				otherIndex = $(this).parent().parent().parent().index()
				if($(this).val() == value && flag && otherIndex != index) {
					flag = false
					alert("ตอนที่ไม่สามารถซ้ำได้")
				}
			})
			if(!flag)
				$(this).val("")
		}
	})
	
	$(".ddlTA").live("change", function(){
		if($(this).val() != 0) {
			value = $(this).val()
			index = $(this).parent().index()
			flag = true
			$(this).parent().parent().parent().find(".ddlTA").each(function() {
				otherIndex = $(this).parent().index()
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
	$course = $_POST['cid'];
	$section = $_POST['sec'];
	$teacher = $_POST['ddlTeacher'];
	$countTA = $_POST['countTA'];
	$ta = $_POST['ddlTA'];
	$k = 0; // $k is index of TA
	// for of Check duplicate key course_section
	
	for($x = 0, $check = true; $x < count($teacher) && $check != false; $x++){
		$sqlCheckSec = "SELECT * FROM course_section 
					WHERE courseId = '$course' AND sectionId = '$section[$x]' ";
		$resultCheckSec = mysql_query($sqlCheckSec) or die(mysql_error());
		if(mysql_num_rows($resultCheckSec) > 0)
			$check = false;
	}
	
	if($check) {
		for($i = 0; $i < count($teacher); $i++){
			$sqlCourseSec = "INSERT INTO course_section (courseId, sectionId, teacherId) "
			." VALUES ('$course', '$section[$i]', '$teacher[$i]')";
			mysql_query($sqlCourseSec)or die(mysql_error());
			//echo $sqlCourseSec."<br />";
			//กรณีที่มี นักศึกษาช่วยสอน
				if(isset($countTA)){
				//echo "countTA#$i : ".$countTA[$i]."<br />";
					for($j = 0; $j < $countTA[$i]; $j++){
							$sqlAssistant = "INSERT INTO assistant (courseId, sectionId, taId) "
							." VALUE ('$course', '$section[$i]', '$ta[$k]')";
							mysql_query($sqlAssistant)or die(mysql_error());
							//echo $sqlAssistant."<br />";	
						$k++;
					}
				}	
		}
		echo "<script> window.location='index_admin.php?course' </script> ";
	} else { ?>
		<div id="checkErr">ไม่สามารถเพิ่มข้อมูลได้</div>
	<?php }
} // ปิด func addCourse
?>
<form action="" method="post" name="form2" id="form2" onsubmit="return onSubmit()">
<br />
<center>
  <span class="style1">เพิ่มรายละเอียดวิชา</span>
</center>
<br />

        <table id="myTable" width="500" border="1" align="center" cellpadding="0" cellspacing="0">
			<thead>
                  <tr>
                    <td height="31" colspan="3" align="center" bgcolor="#425969"><span class="style2">แบบฟอร์มเพิ่มข้อมูลรายละเอียดวิชา</span></td>
                  </tr>
			
                  <tr>
				  
                    <td height="50" width="170" align="right" valign="middle" bgcolor="#FFFFFF">
                    รหัสวิชา: &nbsp; </td>
                    <td colspan="2" bgcolor="#FFFFFF" >
                    <select name="cid" id="cid">
					<?php
					echo "<option value='0'>เลือกวิชา</option>";
				  	$csql = "SELECT * FROM course ORDER BY courseId ASC";
				  	$cresult = mysql_query($csql);
				 	 while($row = mysql_fetch_array($cresult)){
				  	?>
					<option value="<?php echo $row['courseId']?>"><?php echo "$row[courseId]"." - "."$row[courseName]";?></option>
					<?php 
					}
					?>
					</select>
                    <!--br /><font color="#FF0000" ><label id="ciderr"></label></font-->
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
					</td>
                    <td valign="top" colspan="2" bgcolor="#FFFFFF"><div align="right"><img src="../images/close.gif" alt="ลบตอน" width="15px" height="15px" class="closeBTN" /></div>
                  <span>
                      <input name="sec[]" type="text" id="sec" class="sec"  maxlength="3" />
					  <br /><!--font color="#FF0000" ><label class="csecerr"></label></font-->
					  <br />
					  <div class="ddlTeacher"></div>
					  <!--font color='#FF0000' ><label class='teacher'></label></font--><br />
					  
                    </span>
					  <input type="button" class="addTA" value="เพิ่มนักศึกษาช่วยสอน" />
					  <br />
					</td>
				 </tr>
			</tbody>
			<tfoot>
                  <tr>
                    <td valign="top" height="69" colspan="3" bgcolor="#FFFFFF" align="center">
						<div align="right"><input id="addSecBTN" type="button" value="เพิ่มตอน" /></div>
						<p>
                        <input type="Submit" name="Submit" value="ตกลง" id="Submit"/>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="button" name="Cancel" onclick="window.location='index_admin.php?course'" value="ยกเลิก" />
						</p>
					</td>
                  </tr>
				  </tfoot>
              </table>
            </form>

</body>
</html>
<div id="divDDLTeacher" style="display:none">
<?php
	$sql = "SELECT teacherId,teacherName FROM teacher ";
	$result = mysql_query($sql) or die($sql);
	echo "<select name='ddlTeacher[]' class='ddlteacher'>";
	echo "<option value='0'>อาจารย์ผู้สอน</option>";
	while($rows = mysql_fetch_array($result)){
		echo "<option value='" . $rows['teacherId']."' >" . $rows['teacherName'] . "</option>";
	}
	echo "</select>";
	
?>
</div>
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
		
//<input type="button" onclick="$('div#src').text($('#myTable tbody').html())" value="viewSrc" />
?>
</div>

<pre><div id="src"></div></pre>
<div id="param"></div>
