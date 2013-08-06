<?php

	// Affichage de l'en-tête de la page - suppression.
	echo '<!DOCTYPE html><html><head><meta charset="utf-8" />';
	// Scripts
	echo '<script src="javascript/verification_formulaire.js.js"></script>';
	echo '</head><body>';

	// Inclusion des fichiers de configuration - suppression.
	include_once 'config.php';
	include_once 'Exploitation.php';
	include_once 'Image.php';

	// Initialisation des variables - suppression.
	$champs = array("ALL");
	$table_titre = "Suppression en cours";
	$action_selection = "suppression.php";
	$adresse = "C:/wamp/www/projet_bdd/images/";
	$champs_condition = array("numero_objet");
	$valeurs_condition = array($_POST['selection_objet']);
	$champs = array("numero_image");

	// Connexion à la BDD et suppression.
	$c = new Exploitation();
	$c->connexion();
	$requete = $c->lister($champs, $champs_condition, $valeurs_condition, 'image');

	// Suppression préalable  de l'image puis de l'objet.
	$image = new Image();
	$image->supprimerImage($requete[0]['numero_image'], $adresse);

	// Requête de supression de l'objet.
	$c->suppression($champs_condition, $valeurs_condition, "objet");

	// Affichage d'une réponse - suppression.
	echo '<a href="index.php">Menu</a><label> - </label><a href="archives.php">Archives</a><br /><br />';
	echo '<fieldset><legend>'.strtoupper(substr($table_titre, 0, 1)).substr($table_titre, 1).'</legend><form id="form_list" action="'.$action_selection.'"" method="post">';

	echo 'L\'objet du patrimoine a été supprimé. <br /><br /><a href="archives.php">Retour aux archives.</a></fieldset></div>';
	echo '</body</html>';
?>