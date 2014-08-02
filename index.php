<html lang="en">
<head>
	<title>Subscriber's Poll</title>
	<meta charset="UTF-8">
</head>
<body>
<?php
	require "configuration.php";

	if (isset($_GET['code']))
	{
		$postFields = array(
			'client_id' => $client_id,
			'client_secret' => $client_secret,
			'grant_type' => 'authorization_code',
			'redirect_uri' => $redirect_uri,
			'code' => $_GET['code']
		);

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL,'https://api.twitch.tv/kraken/oauth2/token');
		curl_setopt($curl, CURLOPT_POST,true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); 
		curl_setopt($curl, CURLOPT_POSTFIELDS,$postFields);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

		$json = curl_exec($curl);
		$data = json_decode($json,$obtain_as_array); //Makes json_decode return an associative array, so we can access it on a more json-like way.
		curl_close($curl);
		$token = $data['access_token'];
			
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, "https://api.twitch.tv/kraken/user?oauth_token=".$token);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); 
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);				

		$json = curl_exec($curl);
		$user_data = json_decode($json,$obtener_como_array);
		curl_close($curl);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, "https://api.twitch.tv/kraken/users/" . $user_data['display_name'] . "/subscriptions/" . $channel);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); 
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'Accept: application/vnd.twitchtv.v2+json',
		    'Authorization: OAuth ' . $token
		));
		$json = curl_exec($curl);
		$sub_data = json_decode($json,$obtener_como_array);
		printf("<div id='content'>");
		if (isset($sub_data['error']))
		{
			printf("<p>You are not suscribed</p>");
		}
		else 
		{
			$conexion = new mysqli($mysql_host,$mysql_user,$mysql_password,$mysql_database);
			if ($user_data['name']==$channel) {
				printf("<p>Result table</p>");
				printf("<table>");
				printf("<tr><th>Vote</th><th>Number of Votes</th></tr>");
				$query = "SELECT Vote, count(User) AS Votos FROM votos Group BY Juego ORDER BY Votos DESC";
				$cursor = mysqli_query($conexion,$query);
				while ($fila = mysqli_fetch_assoc($cursor)) {
					printf("<tr>");
					printf("<td>" . $fila['Vote'] . "</td>");
					printf("<td>" . $fila['Votos'] . "</td>");
					printf("</tr>");
				}
				printf("</table>");
			} else 
			{
				$username = $user_data['name'];
				$query = "SELECT * FROM votos WHERE Usuario LIKE '$username'";
				$existeUsuario = mysqli_query($conexion,$query);
				if (mysqli_num_rows($existeUsuario)>0)
				{
					printf("<p>You already voted!</p>");

				} else {
					printf("<form method='post' action='enviarVoto.php'>");
					printf("<p>What is your vote?: <input type='text' name='vote'></p>");
					printf("<input type='hidden' name='user' value='" . $user_data['name'] . "'>" );
					printf("<input type='submit'>");
					printf("</form>");
				}
				
			}
		}
		printf("</div>");
	} else {
			printf("<a href='https://api.twitch.tv/kraken/oauth2/authorize?response_type=code&client_id=" . $client_id ."&redirect_uri=" . $redirect_uri . "&scope=" . $scopes . "'>Connect with Twitch</a>");
	}
?>
</body>
</html>
