<?php

	// Affichage de l'en-tête de la page.
	echo '<!DOCTYPE html><html><head><meta charset="utf-8" />';
	// Scripts
	echo '<script src="javascript/verification_formulaire.js.js"></script>';
	// CSS
	echo '<link rel="stylesheet" type="text/css" media="screen" href="css/creation.css" />';
	echo '</head><body>';

	// Inclusion des fichiers de configuration - enregistrement.
	include_once 'config.php';
	include_once 'Exploitation.php';
	include_once 'Image.php';

	// Vérification des champs et valeurs.
	$validation = 0;
	if($_POST['nom_objet'] != "" && $_POST['lieu_objet'] != "" && $_POST['datation_objet'] != "" && $_POST['patrimoine_objet'] != "" && $_POST['type_objet'] != "" && $_FILES['image_objet']['error'] == 0) {
		$validation = 1;
	} else {
		switch($_FILES['image_objet']['error']) {
			case 1: echo 'L\'image téléchargée excède la taille de upload_max_filesize, configurée dans le php.ini.'; break;
			case 2: echo 'L\'image téléchargée excède la taille de MAX_FILE_SIZE, qui a été spécifiée dans le formulaire HTML.'; break;
			case 3: echo 'L\'image n\'a été que partiellement téléchargée.'; break;
			case 4: echo 'Aucune image n\'a été téléchargée.'; break;
			case 6: echo 'Un dossier temporaire est manquant.'; break;
			case 7: echo 'Échec de l\'écriture de l\'image sur le disque.'; break;
			case 8: echo 'Une extension PHP a arrêté l\'envoi de l\'image. PHP ne propose aucun moyen de déterminer quelle extension est en cause.'; break;
		}
		echo '<br /><a href="creation.php">Retour à la création de la fiche.</a></body></html>';
		exit;
		
	}

	// Initialisation variables - enregistrement.
	$champs = array("nom_objet", "lieu_objet", "datation_objet", "patrimoine_objet", "type_objet");
	$valeurs = array($_POST['nom_objet'], $_POST['lieu_objet'], $_POST['datation_objet'], $_POST['patrimoine_objet'], $_POST['type_objet']);
	$page_titre = "Création d'une fiche.";
	$index = strripos($_POST['nom_objet'], ' ');
	if($index == 0) { $index = strlen($_POST['nom_objet']); }
	// Modification à faire : modification du nom de l'objet si il y a présence d'accents !!
	$nom_image = substr($_POST['nom_objet'], 0, $index);
	$retour_page = "creation.php";

	// Enregistrement d'un objet - enregistrement.
	$c = new Exploitation();
	$c->connexion();
	$numero_objet = $c->insertion($champs, $valeurs, "objet");

	// Création d'une image - enregistrement.
	$image = new Image();
	$image->ajouterImage($_FILES, $numero_objet, $nom_image);

	// Affichage de l'en-tête du menu et des liens de navigation - enregistrement.
	echo '<a href="index.php">Menu</a><label> - </label>';
	echo '<a href="archives.php">Archives</a><br /><br />';
	echo '<fieldset><legend>'.strtoupper(substr($page_titre, 0, 1)).substr($page_titre, 1).'</legend>';

	// Affichage d'une réponse - enregistrement.
	echo 'Merci d\'avoir ajouter un objet au patrimoine '.strtoupper(substr($_POST['patrimoine_objet'], 0, 1)).substr($_POST['patrimoine_objet'], 1).'!';

	// Affichage de la fin de l'en-tête - enregistrement.
	echo '<br /><br /><a href="'.$retour_page.'">Retour</a></fieldset></div>';
	echo '</body></html>';

?>