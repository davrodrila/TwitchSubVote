<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Send your vote</title>
</head>
<body>
	<?php
		require "configuration.php";
		$user = $_POST['user'];
		$vote = htmlspecialchars(strtolower($_POST['vote']));
		$conection = new mysqli($mysql_host,$mysql_user,$mysql_password,$mysql_database);
		if ($stm = $conection -> prepare("INSERT INTO Results (User,Vote) VALUES(?,?)")) {
			$stm->bind_param("ss",$user,$vote);
			$stm->execute();
			printf("<p>Vote sended.</p>");
		}
		$connection.close();
	?>
</body>
</html>
