<?php

	// Affichage de l'en-tête de la page - newsletter.
	echo '<!DOCTYPE html><html><head><meta charset="utf-8" />';
	// Scripts
	echo '<script src="javascript/verification_formulaire.js.js"></script>';
	echo '</head><body>';

	// Inclusion des fichiers de configuration - newsletter.
	include_once 'config.php';
	include_once 'Exploitation.php';
	include_once 'Image.php';

	// Initialisation des variables - newsletter.
	$email = $_POST["email_newsletter"];
	$champs = array("email_utilisateur");
	$valeurs = array($email);
	$destinataire = "morvan.calmel@gmail.com";
	$sujet = "test";
	$message = "test";
	$headers = "localhost:25";

	// Connexion à la BDD - newsletter.
	$c = new Exploitation();
	$c->connexion();
	$c->insertion($champs, $valeurs, "utilisateur");

	// Teste le mail.
    /*if(mail($destinataire, $sujet, $message, $headers)) {
		echo 'Mail envoyé avec succès';
	} else {
     	echo 'Erreur';  
	}*/

	echo '<br /><br /><a href="index.php">Retour</a></fieldset></div>';
	echo '</body</html>';
?>