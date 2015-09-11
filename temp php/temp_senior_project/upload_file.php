<?php

if ($_FILES['file']['error'] !== UPLOAD_ERR_OK) {
	die("Upload failed with error " . $_FILES['file']['error']);
}
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $_FILES['file']['tmp_name']);
echo $mime;
$ok = false;
switch ($mime) {
	case 'application/msword':
	case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
		$ok = true;
	default:
		die("Unknown/not permitted file type");
}
if($ok){

	echo "Upload: " . $_FILES["file"]["name"] . "<br>";
	echo "Type: " . $_FILES["file"]["type"] . "<br>";
	echo "Size: " . $_FILES["file"]["size"] / 1024 . " kB<br>";
	echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";
	
	move_uploaded_file($_FILES["file"]["tmp_name"], "/home/siwaphol/" . $_FILES["file"]["name"]);
	echo "Stored in: " . "/home/siwaphol/" . $_FILES["file"]["name"];
}

?>
