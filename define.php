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
define('MEMBRE', ' FROM membres');
define('INFOADM', ' FROM infos_administrative');
define('JETON', ' FROM secure');
define('ACTIVATION', ' FROM activation');
define('JETONMAIL', ' FROM activationmail');
// les tables sans FROM
define('MEMBREZ', 'membres');
define('INFOADMZ', 'infos_administrative');
define('JETONZ', 'secure');
define('ACTIVATIONZ', 'activation');
define('JETONMAILZ', 'activationmail');
// les variables de recherche sur la table membres
define('ID', ' WHERE id_membre=:id');
define('EMAIL', ' WHERE email=:email');
define('PROFILP', ' (nom, prenom, email, password, adresse, codepostal, ville, telephone, niveau) VALUES (:nom, :prenom, :email, :password, :adresse, :codepostal, :ville, :telephone, :niveau)');
define('PROFILC', ' (nom, prenom, email, password, niveau) VALUES (:nom, :prenom, :email, :password, :niveau)');
define('PROFILFORM', ' (id_membre, societe, localcommercial) VALUES (:id, :societe, :localcommercial)');
define('ACTIVMEMBRE', ' SET activation=:activer');
define('MAJPASS', ' SET password=:newPass');
define('NIVEAU', ' SET niveau=:niveau');
// les variables de recherche sur la table methode d'activation
define('METHODEACTIV', ' WHERE activation=1');
define('CHANGEMETOD', ' SET activation=:activ');
// les variables de recherche sur la table jeton de connexion
define('JETONCONNEXION', ' WHERE id_membre=:id AND ip_connexion=:ip');
define('JETONSESSION', ' WHERE id_membre=:id AND jeton=:jeton');
define('JETONMEMBRE', ' WHERE id_membre=:id');
define('JETONDATE', ' SET date=:date');
define('JETONVALUES', ' (id_membre, jeton, ip_connexion, date) VALUES (:id, :jeton, :ip, :date)');
// les variables de recherche sur la table jeton d'activation par mail
define('JETONMAILVALUES', ' (id_membre, jeton) VALUES (:id, :jeton)');
define('JETONACTIVATION', ' WHERE jeton=:jeton');
// les variables de recherche sur la table message
?>