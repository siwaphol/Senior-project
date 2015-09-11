<?php
function checkDuplicate($check,$type) {
	$command2 = "select * from ".$type." where ".$type."Id = '$check' ";
	$result2 =  mysql_query($command2);
	if( mysql_num_rows($result2) > 0){
		if($type == "student") {?>
			<div id='checkErr'> &nbsp;รหัสนักศึกษานี้ี้มีผู้ใช้้แล้ว</div>
		<? } else if($type == "course") {?>
			<div id='checkErr'> &nbsp;รหัสวิชานี้มีแล้ว</div>
		<? } else {?>
			<div id='checkErr'> &nbsp;ชื่อผู้ใช้นี้มีผู้ใช้้แล้ว</div>
		<? }
		return false;
	}else return true;
}

function insert($type) {
	$user = $_POST['user'];
	$name = $_POST['name'];
	$pwd = $_POST['pwd'];
	if(checkDuplicate($user, $type)) {
		$command = "INSERT INTO ".$type." (".$type."Id,".$type."Name,".$type."Pw) VALUES ('$user','$name','$pwd') ";
		mysql_query($command);
		if(mysql_affected_rows() != 0)
			echo "<script>window.location='index_admin.php?".$type."-data'</script>";
		else {?>
			<div id="checkErr">ไม่สามารถเพิ่มข้อมูลได้</div>
		<?php }
	}
}


?>