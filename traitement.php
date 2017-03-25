<?php session_start();

include('function.php');

echo InscriptionPro::insertPro($_POST['email'], $_POST['password'], $_POST['conf_password']);
redirection('index.php', $time=3);
?>
			<center>
			<br />
			<img src="img/ajax-loader.gif"/>
			</center>';