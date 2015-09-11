<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Test Login Form</title>

</head>

<script src="js/jquery-1.4.4.min.js"  type="text/javascript"></script>
<script type="text/javascript">
	$(function(){
		$("input[type=button]#logout").click(function(){
			$.ajax({
				url: "testlogout.php",
				type: "GET",
				success: function(){window.location.reload()},
				error: function(error){
					alert(error.statusText)
				}
			})
		})
	})
</script>
<body>
	<?php include 'testlogin.php' ;
//	include("connect.php");
    $checkmysqli = mysqli_connect("localhost","admin","R71lMJUU","project499");
    if ($checkmysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $checkmysqli->connect_errno . ") " . $checkmysqli->connect_error;
    }
    $checkmysqli->set_charset("utf8");

	?>
	<form name="formlogin" id="login" action="login.php" method="post" >
		<p><?php echo $attributes['id'][0] ?></p>
		<input type="button" value="Logout"  id="logout">
	</form>
	<?php

	$role = $attributes['role'][0];
	if($role=="ST"){
		$id=$attributes['id'][0];
		$sql = "select * from student where studentId='$id'";
//		$result = mysql_query($sql);
        $result = $checkmysqli->query($sql);
		if ($result->num_rows >=1 ){
			$row = $result->fetch_array();
			$_SESSION['ssname'] = $row['studentName'];
			$_SESSION['user']  = $row['studentId'];
			$_SESSION['status'] = "student" ;
            $checkmysqli->close();
			header("Location:student/index_student.php");
		}else{
			echo "not have username or more than one";
		}
	}
	else if($role=="AD"){
		$username = $attributes['username'][0];
		$sql = "select * from admin where adminId='$username'";
        $result = $checkmysqli->query($sql);
        if ($result->num_rows >=1 ){
            $row = $result->fetch_array();
			$_SESSION['ssname'] = $row['adminName'];
			$_SESSION['user']  = $row['adminId'];
			$_SESSION['status'] = "admin" ;
            $checkmysqli->close();
			header("Location:admin/index_admin.php");
		}else{
			echo "not have username or more than one";
		}
	}else if($role=="TE"){
		$username = $attributes['username'][0];
		$sql = "select * from teacher where teacherId='$username'";
        $result = $checkmysqli->query($sql);
        if ($result->num_rows >=1 ){
            $row = $result->fetch_array();
			$_SESSION['ssname'] = $row['teacherName'];
			$_SESSION['user']  = $row['teacherId'];
			$_SESSION['status'] = "teacher" ;
            $checkmysqli->close();
			header("Location:teacher/index_teacher.php");
		}else{
			echo "not have username or more than one";
		}
	}else if($role=="TA"){
		$username = $attributes['username'][0];
		$sql = "select * from ta where taId='$username'";
        $result = $checkmysqli->query($sql);
        if ($result->num_rows >=1 ){
            $row = $result->fetch_array();
			$_SESSION['ssname'] = $row['taName'];
			$_SESSION['user']  = $row['taId'];
			$_SESSION['status'] = "ta";
            $checkmysqli->close();
			header("Location:ta/index_ta.php");
		}else{
			echo "not have username or more than one";
		}
	}else{
		//log out of current session in SSO
        $checkmysqli->close();
        throw_SP_no_user($as);
	}

	
	//header("Location:student/index_student.php");

	?>
</body>
</html>