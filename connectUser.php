<?php session_start();
print_r($_SESSION);
$connect = 'config.php';
if(!file_exists($connect)) {
  header('Location: index.php');
}
include('function.php');
?>
<?php
	if(!Connexion::connexionCreate()) {
		echo '<style>#tempo{display:none;}</style>
			<center>
			<br />
			<img src="img/ajax-loader.gif"/>
			</center>';
			redirection('index.php', $time=2);
	}
?>