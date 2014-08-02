<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Send your vote</title>
</head>
<body>
	<?php
		require "configuration.php";
		$user = htmlspecialchars($_POST['user']);
		$vote = htmlspecialchars( strtolower($_POST['vote']));
		$conection = new mysqli($mysql_host,$mysql_user,$mysql_password,$mysql_database);
		mysqli_query($conexion,"INSERT INTO Results (User,Vote) VALUES('$user','$vote')") or die("<p>Error</p>");
		printf("<p>Vote sended.</p>");
	?>
</body>
</html>
