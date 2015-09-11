function SelectAll(box) { //ทำการเลือก Option ทั้งหมด
for (var i=0;i< document.form1.elements[box].length;i++) {
document.form1.elements[box][i].checked = true;
}
}
function ResetAll(box) { //ทำการลบ option ที่เลือกทั้งหมด
for (var i=0;i< document.form1.elements[box].length;i++) {
document.form1.elements[box][i].checked = false;
}
}

function onSubmitForm1(box, word) {
	var flag = false
for (var i=0;i< document.form1.elements[box].length;i++) {
if (document.form1.elements[box][i].checked) {
flag = true
}
}
	if(!flag){
		alert(word)
		return false
	}else{
		if(confirm('ยืนยันการลบข้อมูล'))
			return true
		else	return false
	}
}
//manageuser
////////////////////////////////////////////////////////////////

function Random() { 
	var rc = "";
	var ubound = 10;
	var lbound = 0;
	var value=0;
	for (var idx = 0; idx < 4; ++idx) {
		value = Math.floor(Math.random() * (ubound - lbound)) + lbound;
		rc = rc + value;
	}
	document.form2.pwd.value = rc;
}

function fixkey(){
	 if(event.keyCode < 48 || event.keyCode > 57) { 
	  event.returnValue = false;
	 }
}

function ValidateForm(){
	var code = document.form2.user
	var name = document.form2.name
	var pwd = document.form2.pwd
	var err = ""
	
	if(code.value == "") {
		if(code.id == 'code')
			err += "กรุณากรอกรหัสนักศึกษา \n";
		else err += "กรุณากรอกชื่อผู้ใช้ \n";
		code.focus()
	}
	
	if(name.value == "") {
		err += "กรุณากรอกชื่อ - นามสกุล \n";
		name.focus()
	}
	
	if(pwd.value == "") {
		err += "กรุณากรอกรหัสผ่าน \n";
		pwd.focus()
	}else if(pwd.value.length != 4){
		err += "ข้อมูลรหัสผ่านต้องเป็นตัวเลข 4 หลักเท่านั้น\n";
		pwd.focus()
	}
	
	if(err != "" )
	{ 
		alert(err)
		return false
	} else
		return true
}