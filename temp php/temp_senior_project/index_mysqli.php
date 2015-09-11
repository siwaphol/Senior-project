<?php
	$db = new mysqli('localhost','root','siwaphol01','mydb');
	//$db->set_charset('utf8');
	if(mysqli_connect_errno()){
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}
	
	$db->query("SET NAMES 'utf8' COLLATE 'utf8_general_ci';");
	/* return name of current default database */
	if ($result = $db->query("SELECT DATABASE()")){
		$row = $result->fetch_row();
		printf("Default database is %s.\n", $row[0]);
		echo "</br>";
		$result->close();
	}
	
	if ($result = $db->query("SELECT * FROM teacher")) {
		printf("Select returned %d rows.</br>", $result->num_rows);

		$result->close();
	}
		
	if ($result = $db->query("SELECT * FROM teacher")) {
		while ($row = $result->fetch_array()){
			printf("%s </br>", $row['teacherName']);
			//printf("%s </br>", $row[1]);
		}
		$result->close();
	}
	/*if ($result = $db->query("SELECT * FROM teacher", MYSQLI_USE_RESULT)) {
		
		while($row = $result->fetch_array()){
			echo $row['TeacherId'] . '<br/>';
		}
		if(!$db->query("SET @a:='this will not work'")) {
			printf("Error: %s\n", $db->error);
		}
		$result->close();
	}*/

	$db->close();

?>
