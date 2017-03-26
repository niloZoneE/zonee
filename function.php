<?php
include('define.php');

// La fonction de redirection de base
function redirection($url, $time=0) {
	if (!headers_sent()) {
		header("refresh: $time;url=$url");
		exit;
	}
	else {
		echo '<meta http-equiv="refresh" content="',$time,';url=',$url,'">';
	}
}

// La classe de connexion a la bdd
class Bdd {
	private static $connexion = NULL;

	public static function connectBdd() {
		if(!self::$connexion) {
			self::$connexion = new PDO(DNS, USER, PASS);
			self::$connexion -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		return self::$connexion;
	}

	public static function getCurrentConnexion()
	{
		return self::$connexion;
	}
	public static function check_email($email_post, $bdd_email){
	while ($comp = $bdd_email->fetch())
	{
		if ($comp['email'] === $email_post)
			return (0);
	}
	return (1);
}
} // Fin de la classe de connexion a la bdd

###########################################################################################

// La classe de recuperation de l'ip visiteur
class Ip {
	// function recuperation ip
	public static function get_ip() {
		if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		elseif(isset($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		}
		else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
} // Fin de la classe de recuperation de l'ip visiteur

###########################################################################################

// La classe de cryptage
class Cryptage {

	// Fonction de cryptage
	public static function crypter($var) {
		$sel = "48@tiOP";
		$Cript = md5($var);
		$crypt = sha1($Cript, $sel);
		return $crypt;
	}
	// creation d'une chaine aleatoire
	public static function chaine($nb_car, $chaine='AZERTYUIOPQSDFGHJKLMWXCVBNazertyuiopqsdfghjklmwxcvbn123456789') {
		$nb_lettres = strlen($chaine)-1;
		$generation = '';
		for($i=0; $i < $nb_car; $i++)
		{
			$pos = mt_rand(0, $nb_lettres);
			$car = $chaine[$pos];
			$generation .= $car;
		}
		return $generation;
	}

} // Fin de la classe de cryptage

###########################################################################################

class InscriptionPro {

	// Fonction d'inscription
	// Si l'identifiant, l'email le mot de passe un et le mot de passe deux sont poster
	//		Si les deux mot de passe sont identiques
	//			Si le pseudo n'existe pas dans la bdd
	//				Si l'email est valide
	//					Si l'email n'existe pas dans la bdd
	//						creation du profil
	//						creation de la protection des info du profil
	//						envoie du message de bienvenue
	//						Retourne Activation du profil
	//					Sinon
	//						Retourne email existe deja
	//				Sinon
	//					Retourne email non valide
	//			Sinon
	//				Retourne le pseudo existe
	//		Sinon
	//			Retourne les 2 mots de passe sont !=
	// Sinon
	// 		Retourne remplir tout les champs
	public static function insertPro($email, $passeUn, $passeDe) {
		if(!empty($email) AND !empty($passeUn) AND !empty($passeDe)) {
			if($passeUn === $passeDe) {
				$verifMail = Bdd::connectBdd()->prepare(SELECT.ALL.MEMBRE.EMAIL);
				$verifMail -> bindParam(':email', $email, PDO::PARAM_STR, 50);
				$verifMail -> execute();
				if($verifMail -> rowCount() != 1) {
					if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
						$verifMail = Bdd::connectBdd()->prepare(SELECT.ALL.MEMBRE.EMAIL);
						$verifMail -> bindParam(':email', $email);
						$verifMail -> execute();
						if($verifMail -> rowCount() != 1) {
							InscriptionPro::profil($_POST['nom'], $_POST['prenom'], $email, $_POST['password'],$_POST['adresse'],$_POST['codepostal'],$_POST['ville'],$_POST['telephone']);
							$resultat = InscriptionPro::activer($email);
						}
						else {
							$resultat = 'L\'adresse email '.$email.' existe d&eacute;j&agrave;,<br />veuillez saisir une autre adresse et recommencer l\'inscription..';
						}
					}
					else {
						$resultat = 'L\'adresse email saisie n\'est pas valide, <br />veuillez recommencer l\'inscription.';
					}
				}
				else {
					$resultat = 'L\'email saisi existe déjà';
				}
			}
			else {
				$resultat = 'Les deux mots de passe doivent être identiques';
			}
		}
		else {
			$resultat = 'Vous devez remplir tout les champs.';
		}
		return $resultat;
	}

	//creation espace gerant


	// creation du profil
	public static function profil($nom, $prenom, $email, $password, $adresse, $codepostal, $ville, $telephone) {
		$password = Cryptage::crypter($password);
		$niveau = 1;
		$resultat = Bdd::connectBdd()->prepare(INSERT.MEMBREZ.PROFILP);
		$resultat -> bindParam(':nom', $nom);
		$resultat -> bindParam(':prenom', $prenom);
		$resultat -> bindParam(':email', $email);
		$resultat -> bindParam(':password', $password);
		$resultat -> bindParam(':adresse', $adresse);
		$resultat -> bindParam(':codepostal', $codepostal);
		$resultat -> bindParam(':ville', $ville);
		$resultat -> bindParam(':telephone', $telephone);
 		$resultat -> bindParam(':niveau', $niveau);
		$resultat -> execute();
		$currenConn=Bdd::getCurrentConnexion();
		return $currenConn->lastInsertId();
	}

	// creation du profil
	public static function insertForm($id, $societe, $localcommercial) {
		$resultat = Bdd::connectBdd()->prepare(INSERT.INFOADMZ.PROFILFORM);
		$resultat -> bindParam(':id', $id);
 		$resultat -> bindParam(':societe', $societe);
 		$resultat -> bindParam(':localcommercial', $localcommercial);
 		$resultat -> execute();
	}

	// activation du membre
	// recuperation de la methode d'activation du site
	// puis activation du membre
	public static function activer($email) {
		$activation = Bdd::connectBdd()->prepare(SELECT.ALL.ACTIVATION.METHODEACTIV);
		$activation -> execute();
		$methode = $activation -> fetch(PDO::FETCH_ASSOC);
		switch($methode['id']) {
			case 1 :
			Activation::activationAuto($email);
			$resultat = 'Vous-pouvez d&egrave;s &agrave; pr&eacute;sent vous connecter!';
			break;

			case 2 :
			Activation::activationMailCandid($email);
			$resultat = 'Un email de confirmation vient de vous être envoyé!';
			break;

			case 3 :
			$resultat = 'Votre inscription est termin&eacute;e, elle est en cours de modération,<br />un email de confirmation vous sera envoy&eacute; une fois validée;,<br />pensez à v&eacute;rifier vos spams.';
			break;
		}
		$_SESSION["ActiverTempo"] = $resultat;
		return $resultat;
	}
}


class InscriptionClient {

	// Fonction d'inscription
	// Si l'identifiant, l'email le mot de passe un et le mot de passe deux sont poster
	//		Si les deux mot de passe sont identiques
	//			Si le pseudo n'existe pas dans la bdd
	//				Si l'email est valide
	//					Si l'email n'existe pas dans la bdd
	//						creation du profil
	//						creation de la protection des info du profil
	//						envoie du message de bienvenue
	//						Retourne Activation du profil
	//					Sinon
	//						Retourne email existe deja
	//				Sinon
	//					Retourne email non valide
	//			Sinon
	//				Retourne le pseudo existe
	//		Sinon
	//			Retourne les 2 mots de passe sont !=
	// Sinon
	// 		Retourne remplir tout les champs
	public static function insertClient($email, $passeUn, $passeDe) {
		if(!empty($email) AND !empty($passeUn) AND !empty($passeDe)) {
			if($passeUn === $passeDe) {
				$verifMail = Bdd::connectBdd()->prepare(SELECT.ALL.MEMBRE.EMAIL);
				$verifMail -> bindParam(':email', $email, PDO::PARAM_STR, 50);
				$verifMail -> execute();
				if($verifMail -> rowCount() != 1) {
					if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
						$verifMail = Bdd::connectBdd()->prepare(SELECT.ALL.MEMBRE.EMAIL);
						$verifMail -> bindParam(':email', $email);
						$verifMail -> execute();
						if($verifMail -> rowCount() != 1) {
							InscriptionClient::profil($_POST['nom'], $_POST['prenom'], $email, $_POST['password']);
							$resultat = InscriptionClient::activer($email);
						}
						else {
							$resultat = 'L\'adresse email '.$email.' existe d&eacute;j&agrave;,<br />veuillez saisir une autre adresse et recommencer l\'inscription..';
						}
					}
					else {
						$resultat = 'L\'adresse email saisie n\'est pas valide, <br />veuillez recommencer l\'inscription.';
					}
				}
				else {
					$resultat = 'L\'email saisi existe déjà';
				}
			}
			else {
				$resultat = 'Les deux mots de passe doivent être identiques';
			}
		}
		else {
			$resultat = 'Vous devez remplir tout les champs.';
		}
		return $resultat;
	}

	//creation espace gerant


	// creation du profil
	public static function profil($nom, $prenom, $email, $password) {
		$password = Cryptage::crypter($password);
		$niveau = 2;
		$resultat = Bdd::connectBdd()->prepare(INSERT.MEMBREZ.PROFILC);
		$resultat -> bindParam(':nom', $nom);
		$resultat -> bindParam(':prenom', $prenom);
		$resultat -> bindParam(':email', $email);
		$resultat -> bindParam(':password', $password);
 		$resultat -> bindParam(':niveau', $niveau);
		$resultat -> execute();
		$currenConn=Bdd::getCurrentConnexion();
		return $currenConn->lastInsertId();
	}


	// activation du membre
	// recuperation de la methode d'activation du site
	// puis activation du membre
	public static function activer($email) {
		$activation = Bdd::connectBdd()->prepare(SELECT.ALL.ACTIVATION.METHODEACTIV);
		$activation -> execute();
		$methode = $activation -> fetch(PDO::FETCH_ASSOC);
		switch($methode['id']) {
			case 1 :
			Activation::activationAuto($email);
			$resultat = 'Vous-pouvez d&egrave;s &agrave; pr&eacute;sent vous connecter!';
			break;

			case 2 :
			Activation::activationMailCandid($email);
			$resultat = 'Un email de confirmation vient de vous être envoyé!';
			break;

			case 3 :
			$resultat = 'Votre inscription est termin&eacute;e, elle est en cours de modération,<br />un email de confirmation vous sera envoy&eacute; une fois validée;,<br />pensez à v&eacute;rifier vos spams.';
			break;
		}
		$_SESSION["ActiverTempo"] = $resultat;
		return $resultat;
	}
}
// La classe Membre
class Membre {
	//Fonction de recuperation de l'id d'un membre
	public static function recupId($email) {
		$resultat = Bdd::connectBdd()->prepare(SELECT.ALL.MEMBRE.EMAIL);
		$resultat -> bindParam(':email', $email, PDO::PARAM_STR, 50);
		$resultat -> execute();
		$donnee = $resultat -> fetch(PDO::FETCH_ASSOC);
		return $donnee['id_membre'];
	}

	// Fonction de recuperation des infos membre
	// $id => id du membre
	// $info => information qu l'on veux
	public static function info($id, $info) {
		$resultat = Bdd::connectBdd()->prepare(SELECT.ALL.MEMBRE.ID);
		$resultat -> bindParam(':id', $id, PDO::PARAM_INT, 11);
		$resultat -> execute();
		$infoMembre = $resultat -> fetch(PDO::FETCH_ASSOC);
		return $infoMembre[$info];
	}
}
// La classe activation
class Activation {

	// fonction activation automatique
	public static function activationAuto($email) {
		$activ = '1';
		$resultat = Bdd::connectBdd()->prepare(UPDATE.MEMBREZ.ACTIVMEMBRE.EMAIL);
		$resultat -> bindParam(':email', $email);
		$resultat -> bindParam(':activer', $activ);
		$resultat -> execute();
	}
	// fonction activation par email
	// Si un mail d'activation a deja ete envoye
	// 		recuperation du jeton d'activation
	// Sinon
	//		creation d'un jeton d'activation
	//		et enregistrement dans la bdd
	// Si le mail est envoye
	// 		retourne vrai
	// Sinon
	//		retourne faux
	public static function activationMail($email) {
		$id = Membre::recupId($email);
		$resultat = Bdd::connectBdd()->prepare(SELECT.ALL.JETONMAIL.ID);
		$resultat -> bindParam(':id', $id, PDO::PARAM_INT, 11);
		$resultat -> execute();
		if($resultat -> rowCount() === 1) {
			$donnee = $resultat->fetch(PDO::FETCH_ASSOC);
			$jeton = $donnee['jeton'];
		}
		else {
			$nb_min = 1;
			$nb_max = 100000;
			$jeton = mt_rand($nb_min,$nb_max);
			// $jeton = Cryptage::crypter(Cryptage::chaine(10));
			$insert = Bdd::connectBdd()->prepare(INSERT.JETONMAILZ.JETONMAILVALUES);
			$insert -> bindParam(':id', $id, PDO::PARAM_INT, 11);
			$insert -> bindParam(':jeton', $jeton);
			$insert -> execute();
		}
		if(Activation::activationEnvoiMail($email, $jeton)) {
			return true;
		}
		else {
			return false;
		}
	}
	public static function activationMailCandid($email) {
		$id = Membre::recupId($email);
		$resultat = Bdd::connectBdd()->prepare(SELECT.ALL.JETONMAIL.ID);
		$resultat -> bindParam(':id', $id, PDO::PARAM_INT, 11);
		$resultat -> execute();
		if($resultat -> rowCount() === 1) {
			$donnee = $resultat->fetch(PDO::FETCH_ASSOC);
			$jeton = $donnee['jeton'];
		}
		else {
			$nb_min = 1;
			$nb_max = 100000;
			$jeton = mt_rand($nb_min,$nb_max);
			// $jeton = Cryptage::crypter(Cryptage::chaine(10));
			$insert = Bdd::connectBdd()->prepare(INSERT.JETONMAILZ.JETONMAILVALUES);
			$insert -> bindParam(':id', $id, PDO::PARAM_INT, 11);
			$insert -> bindParam(':jeton', $jeton);
			$insert -> execute();
		}
		if(Activation::activationEnvoiMailCandid($email, $jeton)) {
			return true;
		}
		else {
			return false;
		}
	}
	// verification du jeton d'activation
	public static function activationVerife($jeton) {
		$resultat = Bdd::connectBdd()->prepare(SELECT.ALL.JETONMAIL.JETONACTIVATION);
		$resultat -> bindParam(':jeton', $jeton);
		$resultat -> execute();
		if($resultat -> rowCount() === 1) {
			$donnee = $resultat -> fetch(PDO::FETCH_ASSOC);
			Activation::activationAuto($donnee['mail']);
			return true;
		}
		else {
			return false;
		}
	}
	public static function activ($id){
		$reqs = Bdd::connectBdd()->prepare('UPDATE membre_pro SET activation=1 WHERE id_membre=:id_membre');
		$reqs -> bindParam(':id_membre', $id, PDO::PARAM_INT, 25);
		$reqs -> execute();
	}
	public static function deleteActiv($id){
		$reqs = Bdd::connectBdd()->prepare('DELETE FROM activationmail WHERE id_membre=:id_membre');
		$reqs -> bindParam(':id_membre', $id, PDO::PARAM_INT, 25);
		$reqs -> execute();
	}

		public static function majActiv($securite){

			$resultat = Bdd::connectBdd()->prepare('SELECT  id, id_membre, jeton FROM activationmail WHERE jeton=:jeton');
			$resultat -> bindParam(':jeton', $securite, PDO::PARAM_STR, 25);
			$resultat -> execute();
			while($Activ = $resultat -> fetch(PDO::FETCH_ASSOC)){
				if($securite == $Activ['jeton']) {
					Activation::activ($Activ['id_membre']);
					Activation::deleteActiv($Activ['id_membre']);
					return 1;
				}
				else{
					return 0;
				}
			}
		}

} // Fin de la classe d'activation

// La classe connexion membre
class Connexion {

	// fonction de deconnexion
	// ecrasement des session dans un tableau
	// destruction du tableau
	// 		Si une page de redirection est choisi
	//			redirection vers la page
	public static function deconnexion($redirection) {
		$_SESSION = array();
		session_destroy();
		if(!empty($redirection)) {
			redirection($redirection);
			return 'echec connexion';
		}
	}
	// mot de passe oublier
	// Si l'email est valide
	// 		Si l'email existe dans la bdd
	// 			creation d'un nouveau mot de passe
	// 			enregistrement du nouveau mot de passe
	// 			Si l'envoie de l'email avec nouveau mot de passe est ok
	//				retourne message d'information
	//			Sinon
	//				retourne erreur de l'envoie
	//		Sinon
	//			retourne email existe pas dans la bdd
	// Sinon
	// Retourne email nn valide
	public static function passOubli($email) {
		if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$verifMail = Bdd::connectBdd()->prepare(SELECT.ALL.MEMBRE.EMAIL);
			$verifMail -> bindParam(':email', $email);
			$verifMail -> execute();
			if($verifMail -> rowCount() !== 1) {
				$donnee = $verifMail -> fetch(PDO::FETCH_ASSOC);
				$newPass = Cryptage::chaine(8);
				$cryptPass = Cryptage::crypter($newPass);
				$enregistrePass = Bdd::connectBdd()->prepare(UPDATE.MEMBREZ.MAJPASS.EMAIL);
				$enregistrePass -> bindParam(':newPass', $cryptPass);
				$enregistrePass -> bindParam(':email', $email);
				$enregistrePass -> execute();
				//               *************************                //
				$headers ='From: "'.$donnee['nom'].' '.$donnee['prenom'].'"'.$email.''."\n";
				$headers .='Reply-To: '.MAILSITE.''."\n";
				$headers .='Content-Type: text/plain; charset="iso-8859-1"'."\n";
				$headers .='Content-Transfer-Encoding: 8bit';
				$sujet = "Nouveau mot de passe pour ".NOMSITE;
				$message = 'Bonjour '.$donnee['pseudo'].','."\n\n";
				$message .= "Voici votre nouveau mot de passe : ".$newPass."\n\n";
				$message .= 'Cordialement,'."\n";
				$message .= NOM_SITE.'.'."\n";
				if(mail(MAIL_SITE, $sujet, $message, $headers)) {
					return 'Un nouveau mot de passe viens de vous &ecirc;tre envoy&eacute;,<br />pensez &agrave; v&eacute;rifiez vos spams.';
				}
				else {
					return 'Erreur lors de l\'envoie de votre mot de passe.';
				}
			}
			else {
				return 'L\'adresse email '.$email.' n\'existe pas,<br />veuillez en saisir une autre et recommencer.';
			}
		}
		else {
			return 'L\'adresse email saisi n\'est pas valide.';
		}
	}
	// fonction de connexion des membres
	// Si verification du captcha => ok, et que l'identifiant et le mot de passe sont postes
	// 		Si login existe
	//			Si mot de passe est ok
	//				Creation de la session
	//				Enregistrement du jeton de connexion
	//				Redirection vers page au choix
	//					-> membre, moderateur, administrateur
	//			Si mot de passe faux => retourne faux
	// 		Si login existe pas => retourne faux
	// Si le captcha est faux => retourne faux
	public static function connexionCreate() {
		if(!empty($_POST['login']) AND !empty($_POST['pass'])) {
			if(Connexion::verifLogin($_POST['login'])) {
				if(Connexion::verifPass($_POST['pass'], $_POST['login'])) {
					$_SESSION['id'] = Membre::recupId($_POST['login']);
					$_SESSION['jeton'] = Connexion::jeton($_POST['login']);
					Connexion::niveau($_POST['login']);
				}
				else {
					return false;
				}
			}
			else {
				return false;
			}
		}
	}
	// Fonction de verification que l'identifiant existe dans la bdd
	public static function verifLogin($email) {
		$resultat = Bdd::connectBdd()->prepare(SELECT.ALL.MEMBRE.EMAIL);
		$resultat -> bindParam(':email', $email, PDO::PARAM_STR, 50);
		$resultat -> execute();
		if($resultat -> rowCount() === 1) {
			return true;
		}
		else {
			return false;
		}
	}
	// Function de verification du mot de passe
	public static function verifPass($pass, $email) {
		$resultat = Bdd::connectBdd()->prepare(SELECT.ALL.MEMBRE.EMAIL);
		$resultat -> bindParam(':email', $email, PDO::PARAM_STR, 50);
		$resultat -> execute();
		$donnee = $resultat -> fetch(PDO::FETCH_ASSOC);
		if(Cryptage::crypter($pass) === $donnee['password']) {
			return true;
		}
		else {
			return false;
		}
	}
	// La fonction de gestion des jetons de connexion lors de la connexion d'un membre
	// Si il esiste un jeton de connexion appertenant au membre qui se connecte avec la meme adresse ip
	// 	-> mise a jour de la date de connexion dans la table des jetons de connexion
	//  -> retourne le jeton
	// Si il n'existe pas
	// 	-> creation d'un jeton de connexion
	// 	-> enregistrement du jeton
	//  -> retourne le jeton
	public static function jeton($email) {
		$id = Membre::recupId($email);
		$ip = Ip::get_ip();
		$date = time();
		$resultat = Bdd::connectBdd()->prepare(SELECT.ALL.JETON.JETONCONNEXION);
		$resultat -> bindParam(':id', $id, PDO::PARAM_INT, 11);
		$resultat -> bindParam(':ip', $ip);
		$resultat -> execute();
		if($resultat -> rowCount() === 1) {
			$donnee = $resultat->fetch(PDO::FETCH_ASSOC);
			$id = Membre::recupId($email);
			$maj = Bdd::connectBdd()->prepare(UPDATE.JETONZ.JETONDATE.JETONMEMBRE);
			$maj -> bindParam(':id', $id);
			$maj -> bindParam(':date', $date);
			$maj -> execute();
			return $donnee['jeton'];
		}
		else {
			$jeton = Cryptage::crypter(Cryptage::chaine(10));
			$insert = Bdd::connectBdd()->prepare(INSERT.JETONZ.JETONVALUES);
			$insert -> bindParam(':id', $id) ;
			$insert -> bindParam(':jeton', $jeton) ;
			$insert -> bindParam(':ip', $ip);
			$insert -> bindParam(':date', $date);
			$insert -> execute();
			return $jeton;
		}
	}
	// Fonction de recuperation du niveau du membre
	// 	3 possibilite -> Membre, moderateur, administrateur
	//                ****************
	// Verification que le membre est actif
	// Si actif -> verification du niveau du membre
	// 		Redirection -> Membre, moderateur, administrateur
	// Si banni
	// 		-> redirection vers page d'information
	// Si pas actif
	// 		Recherche de la methode d'activation des membres
	//		-> activation auto -> retourne la fonction au debut
	// 		-> activation par mail -> envoie le mail d'activation puis redirige vers une page d'information
	// 		-> activation maunel -> redirige vers une page d'information
	public static function niveau($email) {
		$resultat = Bdd::connectBdd()->prepare(SELECT.ALL.MEMBRE.EMAIL);
		$resultat -> bindParam(':email', $email, PDO::PARAM_STR, 50);
		$resultat -> execute();
		$donnee = $resultat->fetch(PDO::FETCH_ASSOC);
		if($donnee['activation'] === '1') {
			switch($donnee['niveau']) {
				case 1 :
				$_SESSION['niveau'] = '1';
				$redirect = redirection('zone-pro/index.php');
				break;

				case 2 :
				$_SESSION['niveau'] = '2';
				$redirect = redirection('zone-membres/index.php');
				break;

				case 3 :
				$_SESSION['niveau'] = '3';
				$redirect = redirection('zone-admin/index.php');
				break;

				case 4 :
				$_SESSION['niveau'] = '4';
				 $redirect = redirection('zone-modo/index.php');
				break;
			}
		}
		elseif($donnee['activation'] === '5') {
			$redirect = redirection('banni.php');
		}
		else {
			$activation = Bdd::connectBdd()->prepare(SELECT.ALL.ACTIVATION.METHODEACTIV);
			$activation -> execute();
			$methode = $activation->fetch(PDO::FETCH_ASSOC);
			switch($methode['id']) {
				case 1 :
				Activation::activationAuto($email);
				return Connexion::niveau($email);
				break;

				case 2 :
				Activation::activationMail($email);
				$redirect = redirection('activationMail.php');
				break;

				case 3 :
				$redirect = redirection('activationAdmin.php');
				break;
			}
		}
	}

} // Fin de la classe de connexion membre

// La classe de protection des espaces -> membre, moderateur et administrateur
class ProtectEspace {

	// protection de l'espace membre
	// Verification que les parametres de sessions existent
	// $id => $_session['id']
	// $captcha => $_session['captcha'] => annulation captcha transferer à l'inscription
	// $jeton=> $_session['jeton']
	// $niveau => $_session['niveau']
	// verification que le niveau n'est pas different de 1
	// Si il y a une erreur
	// 		redirection vers la page de deconnexion
	// Sinon
	//		Verification que le jeton de connexion appartient bien au membre connecte
	//		Si ce n'est pas le cas
	//			redirection vers la deconnexion
	// 		Sinon
	//			Si le membre est banni
	//				Redirection vers la page d'information de bannissement
	//			Sinon
	//				Retourne Vrai
	public static function pro($id, $jeton, $niveau) {
		if(empty($id) OR empty($jeton)) {
			redirection('../deconnexion.php');
		}
		else {
			if($niveau !== '1') {
				redirection('../deconnexion.php');
			}

			$resultat = Bdd::connectBdd()->prepare(SELECT.ALL.JETON.JETONSESSION);
			$resultat -> bindParam(':id', $id, PDO::PARAM_INT, 11);
			$resultat -> bindParam(':jeton', $jeton);
			$resultat -> execute();
			if($resultat -> rowCount() !== 1) {
				redirection('../deconnexion.php');
			}
			else {
				if(Membre::info($id, 'activation') === '5') {
					redirection('../banni.php');
				}
				return true;
			}

		}

	}
			//  Integration de la partie gerant dans l'espace membre
			// Recuperation du niveau membre et redirection
		// protection de l'espace membre
	// Verification que les parametres de sessions existent
	// $id => $_session['id']
	// $captcha => $_session['captcha']
	// $jeton=> $_session['jeton']
	// $niveau => $_session['niveau']
	// verification que le niveau n'est pas different de 1
	// Si il y a une erreur
	// 		redirection vers la page de deconnexion
	// Sinon
	//		Verification que le jeton de connexion appartient bien au membre connecte
	//		Si ce n'est pas le cas
	//			redirection vers la deconnexion
	// 		Sinon
	//			Si le membre est banni
	//				Redirection vers la page d'information de bannissement
	//			Sinon
	//				Retourne Vrai
	public static function membre($id, $jeton, $niveau) {
		if(empty($id) OR empty($jeton)) {
			redirection('../deconnexion.php');
		}
		else {
			if($niveau !== '2') {
				redirection('../deconnexion.php');
			}

			$resultat = Bdd::connectBdd()->prepare(SELECT.ALL.JETON.JETONSESSION);
			$resultat -> bindParam(':id', $id, PDO::PARAM_INT, 11);
			$resultat -> bindParam(':jeton', $jeton);
			$resultat -> execute();
			if($resultat -> rowCount() !== 1) {
				redirection('../deconnexion.php');
			}
			else {
				if(Membre::info($id, 'activation') === '5') {
					redirection('../banni.php');
				}
				return true;
			}
		}

	}
	// protection de l'espace moderateur
	// Verification que les parametres de sessions existent
	// $id => $_session['id']
	// $captcha => $_session['captcha']
	// $jeton=> $_session['jeton']
	// $niveau => $_session['niveau']
	// verification que le niveau n'est pas different de 2
	// Si il y a une erreur
	// 		redirection vers la page de deconnexion
	// Sinon
	//		Verification que le jeton de connexion appartient bien au moderateur connecte
	//		Si ce n'est pas le cas
	//			redirection vers la deconnexion
	// 		Sinon
	//			Si le membre est banni
	//				Redirection vers la page d'information de bannissement
	//			Sinon
	//				Retourne Vrai
	public static function moderateur($id, $jeton, $niveau) {
		if(empty($id) OR empty($jeton)) {
			redirection('../deconnexion.php');
		}
		else {
			if($niveau !== '4') {
				redirection('../deconnexion.php');
			}
			$resultat = Bdd::connectBdd()->prepare(SELECT.ALL.JETON.JETONSESSION);
			$resultat -> bindParam(':id', $id, PDO::PARAM_INT, 11);
			$resultat -> bindParam(':jeton', $jeton);
			$resultat -> execute();
			if($resultat -> rowCount() !== 1) {
				redirection('../deconnexion.php');
			}
			else {
				if(Membre::info($id, 'activation') === '5') {
					redirection('../banni.php');
				}
				return true;
			}
		}
	}
	// protection de l'espace administrateur
	// Verification que les parametres de sessions existent
	// $id => $_session['id']
	// $captcha => $_session['captcha']
	// $jeton=> $_session['jeton']
	// $niveau => $_session['niveau']
	// verification que le niveau n'est pas different de 3
	// Si il y a une erreur
	// 		redirection vers la page de deconnexion
	// Sinon
	//		Verification que le jeton de connexion appartient bien a l'administrateur connecte
	//		Si ce n'est pas le cas
	//			redirection vers la deconnexion
	// 		Sinon
	//			Si le membre est banni
	//				Redirection vers la page d'information de bannissement
	//			Sinon
	//				Retourne Vrai
	public static function administrateur($id, $jeton, $niveau) {
		if(empty($id) OR empty($jeton)) {
			redirection('../deconnexion.php');
		}
		else {
			if($niveau !== '3') {
				redirection('../deconnexion.php');
			}
			$resultat = Bdd::connectBdd()->prepare(SELECT.ALL.JETON.JETONSESSION);
			$resultat -> bindParam(':id', $id, PDO::PARAM_INT, 11);
			$resultat -> bindParam(':jeton', $jeton);
			$resultat -> execute();
			if($resultat -> rowCount() !== 1) {
				redirection('../deconnexion.php');
			}
			else {
				if(Membre::info($id, 'activation') === '5') {
					redirection('../banni.php');
				}
				return true;
			}
		}
	}
	// compte le nombre de jeton de connexion pour le membre
	public static function compteJeton($id) {
		$resultat = Bdd::connectBdd()->prepare(SELECT.ALL.JETON.JETONMEMBRE);
		$resultat -> bindParam(':id', $id, PDO::PARAM_INT, 11);
		$resultat -> execute();
		return '<a href="#">Il y a '.$resultat -> rowCount().' adresse(s) ip qui se connecte(nt) &agrave; votre espace membre.</a>';
	}
	// Liste des jeton de connexion du membre
	public static function listeJeton($id) {
		$liste = '';
		$resultat = Bdd::connectBdd()->prepare(SELECT.ALL.JETON.JETONMEMBRE);
		$resultat -> bindParam(':id', $id, PDO::PARAM_INT, 11);
		$resultat -> execute();
		while($jeton = $resultat -> fetch(PDO::FETCH_ASSOC)) {
			$liste .= '<tr>
					<td align="center">Le '.date('Y-m-d H:i:s', $jeton['date']).'</td>
					<td align="center">'.$jeton['ip_connexion'].'</td>
					<td align="center">
					<form method="post" action="">
					<input type="hidden" value="'.$jeton['id'].'" name="id_jeton">
					<input type="submit" value="Supprimer" name="supprime_connexion" class="input" />
					</form>
					</td>
				</tr>';
		}
		return $liste;
	}
	// effacer un jeton de connexion
	public static function deleteJeton($id) {
		$resultat = Bdd::connectBdd()->prepare(DELETE.JETON.ID);
		$resultat -> bindParam(':id', $id, PDO::PARAM_INT, 11);
		$resultat -> execute();
	}

}
?>