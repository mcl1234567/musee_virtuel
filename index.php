<?php
	// Démarre une session UTILISATEUR - index.
	session_start();
	echo '<!DOCTYPE html><html><head><meta charset="utf-8" />';
	// Scripts
	echo '<script src="javascript/verification_formulaire.js.js"></script>';
	// CSS
	echo '<link rel="stylesheet" type="text/css" media="screen" href="css/menu.css" />';
	echo '</head><body>';

	// Inclusion des fichiers de configuration - index.
	include_once 'config.php';
	include_once 'Exploitation.php';

	// Initialisation des variables, arguments de lister() - index.
	$champs = array("ALL");
	$champs_condition = array();
	$valeur_condition = array();
	$page_titre = "Menu - Patrimoines des pays du Magreb";
	$action_selection = "patrimoine.php";
	$table = "objet";
	$bouton_selection = "Valider";
	$bouton_recherche = "Rechercher";
	$retour_page = "";
	$image = array();

	// Connexion à la BDD - index.
	$e = new Exploitation();
	$e->connexion();
	// Requête 'objets'$requete_objet = $e->lister($champs, $champs_condition, $valeur_condition, "objet");
	// Requête 'image'
	$requete_image = $e->lister($champs, $champs_condition, $valeur_condition, "image");

	for($i=0; $i<sizeof($requete_image); $i++) {
		// Affichage des infos de la requête. ( image )
		if($requete_image != false && $requete_image != null) {
			$nom_image = "";

			// Récupération de l'adresse de l'image.
			foreach($requete_image[$i] as $indice => $valeur) {
				// Récupération du nom de l'image.
				if(substr($indice, 0, 9) == 'nom_image') {
					$nom_image = $valeur;

				}
			}

			// Récupération de l'adresse de l'image.
			foreach($requete_image[$i] as $indice => $valeur) {
				// Champ de l'adresse d'une image : lecture du fichier image.
				if(substr($indice, 0, 7) == 'adresse') {
					$image[$i] = '<img title="'.$nom_image.'" alt="'.$nom_image.'" src="images/'.$valeur.'"/>';
				}
			}
		}
	}

	// Champ de recherche - index.
	echo '<form method="get" action="recherche.php">
			<label for="keyword_recherche" id="label_recherche">Recherche : </label>
			<input id="keyword_recherche" class="keyword_recherche" name="keyword_recherche" type="search" size="25" placeholder="Tapez votre recherche.." />
			<input id="submit_recherche" class="submit_recherche" value="'.$bouton_recherche.'" type="submit" /><br />
			<label>Nombre de résultats par page : </label>
			<input id="nombre_recherche" class="nombre_recherche" name="nombre_recherche" checked type="radio" value="5" title="Nombre de résultats par page" /> <span>5</span>
			<input id="nombre_recherche" class="nombre_recherche" name="nombre_recherche" type="radio" value="10" title="Nombre de résultats par page" /><span> 10 </span>
			<input type="hidden" name="page_recherche" value="0" />
		</form><br />';
	// Affichage d'une réponse prédéfinie lors d'une mauvaise recherche.
	if(isset($_GET["recherche_retour"])) {
		echo '<label>Mot clé trop court. </label>
		<ul> <li>Vérifiez l’orthographe des termes de recherche.</li>
		<li>Essayez d\'autres mots.</li>
		<li>Utilisez des mots clés plus généraux.</li>
		<li>Spécifiez un moins grand nombre de mots.</li></ul>';
	}

	// Abonnement Newsletter - index.
	echo '<form action="newsletter.php" method="post" class="form_newsletter">
				<input type="text" id="email_newsletter" placeholder="Email.." class="email_newsletter" name="email_newsletter" />
				<input type="submit" id="sumbit_newsletter" value="S\'abonner" class="sumbit_newsletter" name="sumbit_newsletter" />
				</form>';

	// Affichage de l'en-tête du menu et des liens de navigation - index.
	if($_SERVER["PHP_SELF"] == "/projet_bdd/index.php") {
		// La page ne peut rediriger vers elle-même.
		echo '<label>Menu - </label>';
	} else {
		echo '<a href="index.php">Menu</a><label> - </label>';
	}
	echo '<a href="creation.php">Création d\'une fiche</a><label> - </label><a href="archives.php">Archives</a><br /><br />';
	echo '<fieldset><legend>'.strtoupper(substr($page_titre, 0, 1)).substr($page_titre, 1).'</legend>';

	// Message d'accueil - index.
	echo '<h3 class="menu_message">Vous êtes sur le '.$page_titre.'</h3>';
	echo '<marquee>';
	for($i=0; $i<sizeof($image); $i++) {
		echo $image[$i];
	}
	echo '</marquee>';

	// Affichage de la fin de l'en-tête - index.
	echo '</fieldset></div>';
	echo '</body></html>';
?>