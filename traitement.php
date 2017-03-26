<?php session_start();

include('function.php');
echo InscriptionPro::insertPro($_POST['email'], $_POST['password'], $_POST['conf_password']);
if (InscriptionPro::insertPro($_POST['email'], $_POST['password'], $_POST['conf_password'])){
	InscriptionPro::insertForm(Membre::recupId($_POST['email']), $_POST['societe'], $_POST['localcommercial']);
	redirection('index.php', $time=4);
}
?>
			<center>
			<br />
			<img src="img/ajax-loader.gif"/>
			</center>';