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
	include("connect.php");
?>
	<form name="formlogin" id="login" action="login.php" method="post" >
		<p><?php echo $attributes['id'][0] ?></p>
		<input type="button" value="Logout"  id="logout">
	</form>
	<?php

$id=$attributes['id'][0];
echo "id= ".$id;
$sql = "select * from student where studentId='$id'";
	$result = mysql_query($sql);
	echo "var_dump= ";
	var_dump($result);
	if (mysql_num_rows($result) >=1 ){
		$row = mysql_fetch_array($result);
		$_SESSION['ssname'] = $row['studentName'];
		$_SESSION['user']  = $row['studentId'];
		$_SESSION['status'] = "student" ;
		echo "student";
		header("Location:student/index_student.php");
		}else{
			echo "not have username or more than one";
			# code...
		}
	mysql_close();
	//header("Location:student/index_student.php");

	?>
</body>
</html>