<?php
require_once("../connect.php");
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
<script  language="javascript" src="../js/form.js"></script>

<?php		
//-------ทำการ insert ค่าเพิ่มข้อมูลอาจารย์ลง database 

if( isset($_POST['Submit']) ){
	include("function/function.php");
	insert('teacher');
}
showForm();

function showForm() {

?>

<form action="" method="post" name="form2" id="form2" onSubmit="return ValidateForm()" >
<br />
<center>
  <span class="style1">เพิ่มข้อมูลอาจารย์ผู้สอน</span>
</center>
<br />
        <table width="500" border="1" align="center" cellpadding="0" cellspacing="0">
                  <tr>
                    <td height="31" colspan="3" align="center" bgcolor="#425969"><span class="style2">แบบฟอร์มเพิ่มข้อมูลอาจารย์ผู้สอน</span></td>
                  </tr>
                  <tr>
                    <td width="170" align="right" bgcolor="#FFFFFF">ชื่อผู้ใช้ : &nbsp; </td>
                    <td colspan="2" bgcolor="#FFFFFF"><label>
                    <input name="user" type="text" id="user" />
                    </label></td>
                  </tr>
                  <tr>
                    <td align="right" bgcolor="#FFFFFF"><br />
                      ชื่อ - สกุล : &nbsp;</td>
                    <td colspan="2" bgcolor="#FFFFFF"><label> <br />
                          <input name="name" type="text" id="name" />
                    </label></td>
                  </tr>
                  <tr>
                    <td align="right" bgcolor="#FFFFFF"><br />
  						ข้อมูลรหัสผ่าน : &nbsp;</td>
                    <td colspan="2" bgcolor="#FFFFFF"><label> <br />
                      <input name="pwd" type="text" id="pwd" onKeyPress="fixkey()" maxlength="4" />
					  <input type="button" name="random" value="สุ่มรหัสผ่าน" onclick="Random();"/>
                 </label> </td>
				 </tr>
                  <tr>
                    <td height="69" colspan="3" bgcolor="#FFFFFF" align="center">

                        <input type="submit" name="Submit" value="ตกลง" />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="button" name="cancel" onclick="window.location='index_admin.php?teacher-data'" value="ยกเลิก" />                    </td>
                  </tr>
              </table>
</form>
<?php }?>
