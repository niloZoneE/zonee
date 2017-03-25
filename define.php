<?php
include('config.php');
// variables de connexion
define('DNS', 'mysql:host='.$PARAM_hote.';dbname='.$PARAM_nom_bd);
define('USER', $PARAM_utilisateur);
define('PASS', $PARAM_mot_passe);
// base site
define('NOMSITE', $PARAM_nom_site);
define('MAILSITE', $PARAM_mail_site);
define('URLSITE', $PARAM_url_site);
// base sql
define('SELECT', 'SELECT ');
define('UPDATE', 'UPDATE ');
define('INSERT', 'INSERT INTO ');
define('DELETE', 'DELETE ');
define('ALL', '*');
// les tables
define('MEMBRE', ' FROM membres_pro');
define('JETON', ' FROM secure');
define('ACTIVATION', ' FROM activation');
define('JETONMAIL', ' FROM activationmail');
// les tables sans FROM
define('MEMBREZ', 'membres_pro');
define('JETONZ', 'secure');
define('ACTIVATIONZ', 'activation');
define('JETONMAILZ', 'activationmail');
// les variables de recherche sur la table membres
define('ID', ' WHERE id_membre_pro=:id');
define('EMAIL', ' WHERE email=:email');
define('PROFILC', ' (civilite, nom, prenom, email, password, naissance, societe, localcommercial, adresse, codepostal, ville, pays, telephone, niveau) VALUES (:civilite, :nom, :prenom, :email, :password, :naissance, :societe, :localcommercial, :adresse, :codepostal, :ville, :pays, :telephone, :niveau)');
define('ACTIVMEMBRE', ' SET activation=:activer');
define('MAJPASS', ' SET password=:newPass');
define('NIVEAU', ' SET niveau=:niveau');
// les variables de recherche sur la table methode d'activation
define('METHODEACTIV', ' WHERE activation=1');
define('CHANGEMETOD', ' SET activation=:activ');
// les variables de recherche sur la table jeton de connexion
define('JETONCONNEXION', ' WHERE id_membre_pro=:id AND ip_connexion=:ip');
define('JETONSESSION', ' WHERE id_membre_pro=:id AND jeton=:jeton');
define('JETONMEMBRE', ' WHERE id_membre_pro=:id');
define('JETONDATE', ' SET date=:date');
define('JETONVALUES', ' (id_membre_pro, jeton, ip_connexion, date) VALUES (:id, :jeton, :ip, :date)');
// les variables de recherche sur la table jeton d'activation par mail
define('JETONMAILVALUES', ' (id_membre_pro, jeton) VALUES (:id, :jeton)');
define('JETONACTIVATION', ' WHERE jeton=:jeton');
// les variables de recherche sur la table message

?>