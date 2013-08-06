<?php

	// Affichage de l'en-tête de la page.
	echo '<!DOCTYPE html><html><head><meta charset="utf-8" />';
	// Scripts
	echo '<script src="javascript/verification_formulaire.js.js"></script>';
	// CSS
	echo '<link rel="stylesheet" type="text/css" media="screen" href="css/creation.css" />';
	echo '</head><body>';

	// Inclusion des fichiers de configuration - creation.
	include_once 'config.php';
	include_once 'Exploitation.php';

	// Initalisation Constantes - creation
	$page_titre = "Création d'une fiche.";

	// Affichage de l'en-tête du menu et des liens de navigation - index.
	echo '<a href="index.php">Menu</a><label> - </label>';
	if($_SERVER["PHP_SELF"] == "/projet_bdd/creation.php") {
		// La page ne peut rediriger vers elle-même.
		echo '<label>Création d\'une fiche - </label>';		
	} else {
		echo '<a href="creation.php">Création d\'une fiche</a><label> - </label>';
	}
	echo '<a href="archives.php">Archives</a><br /><br />';
	echo '<fieldset><legend>'.strtoupper(substr($page_titre, 0, 1)).substr($page_titre, 1).'</legend>';

	// Formulaire  d'une fiche d'un objet.
	echo '<form action="enregistrement.php" method="post" enctype="multipart/form-data">
		<label for="nom_objet">Nom de l\'objet : </label><input type="text" name="nom_objet" id="nom_objet" class="nom_objet" /><br />
		<label for="lieu_objet">Lieu de l\'objet : </label><input type="text" name="lieu_objet" id="lieu_objet" class="lieu_objet" /><br />
		<label for="datation_objet">Datation de l\'objet : </label><input type="text" name="datation_objet" id="datation_objet" class="datation_objet" /><br />
		<label for="patrimoine_objet">Patrimoine de l\'objet : </label><input type="text" name="patrimoine_objet" id="patrimoine_objet" class="patrimoine_objet" /><br />
		<label for="type_objet">Type d\'objet : </label><input type="text" name="type_objet" id="type_objet" class="type_objet" /><br />
		<label for="image_objet">Image représentative de l\'objet : </label><input type="file" name="image_objet" id="image_objet" class="image_objet" /><br />
		<input type="submit" value="Valider" id="creation_submit" class="creation_submit" />
	</form>';

	// Affichage de la fin de la page - creation.
	echo '</fieldset></div>';
	echo '</body></html>';

?>