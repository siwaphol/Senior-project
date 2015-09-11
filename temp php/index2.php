<!DOCTYPE html>
<html>
<head>
	<title>Login Form in PHP with Session</title>
</head>
<body>
	<div id="main">
		<h1>PHP Login Session Example</h1>
		<div id="login">
			<h2>Login Form</h2>
			<form action="" method="post">
				<label>UserEmail :</label>
				<input id="name" name="username" placeholder="user email" type="text"><label>@cmu.ac.th</label>
				<label>Password :</label>
				<input id="password" name="password" placeholder="**********" type="password">
				<input name="submit" type="submit" value=" Login ">
				<span><?php echo $error; ?></span>
			</form>
		</div>
	</div>
</body>
</html>