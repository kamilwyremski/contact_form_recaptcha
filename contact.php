<?php

header('Content-Type: text/html; charset=utf-8');

session_start();

if(!empty($_POST['name']) and !empty($_POST['email']) and !empty($_POST['message'])){
	
	$response=json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=<SECRET_KEY>&response=".$_POST['g-recaptcha-response']."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
	
    if($response['success'] == false){
		die('Kod captcha jest nieprawidłowy');
		$input = $_POST;
	}else{
		$email_odbiorcy = 'example@example.com';
		
		$header = 'Reply-To: <'.$_POST['email']."> \r\n"; 
		$header .= "MIME-Version: 1.0 \r\n"; 
		$header .= "Content-Type: text/html; charset=UTF-8"; 
		
		$wiadomosc = "<p>Dostałeś wiadomość od:</p>";
		$wiadomosc .= "<p>Imie i nazwisko: " . $_POST['name'] . "</p>";
		$wiadomosc .= "<p>Email: " . $_POST['email'] . "</p>";
		$wiadomosc .= "<p>Wiadomość: " . $_POST['message'] . "</p>";
		
		$message = '<!doctype html><html lang="pl"><head><meta charset="utf-8">'.$wiadomosc.'</head><body>';

		$subject = 'Wiadomość ze strony...';
		$subject = '=?utf-8?B?'.base64_encode($subject).'?=';
		
		if(mail($email_odbiorcy, $subject, $message, $header)){
			die('Wiadomość została wysłana');
		}else{
			die('Wiadomość nie została wysłana');
		}
	}
}
?>
<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Formularz kontaktowy</title>
	<link rel="stylesheet" href="style.css">
	<script>
		var onloadCaptcha = function() {
			grecaptcha.render('g-recaptcha', {
			  'sitekey' : '<SITE_KEY>',
			  'callback' : function() {
				document.getElementById('submit').disabled = false;
			  }
			});
		};
	</script>
	<script src='https://www.google.com/recaptcha/api.js?onload=onloadCaptcha'></script>
</head>
<body>

<form method="post">
    <label for="name">Imię i nazwisko</label>
    <input type="text" name="name" id="name" placeholder="Jan Kowalski" required>

    <label for="email">Email</label>
    <input type="email" name="email" id="email" placeholder="example@example.com" required>

    <label for="message">Wiadomość</label>
    <textarea name="message" id="message" placeholder="Wpisz swoją wiadomość" required></textarea>
	
	<div id="g-recaptcha"></div>

    <input type="submit" name="submit" value="Wyślij" disabled id="submit">
</form>

</body>
</html>