<?php
	// Démarre une session UTILISATEUR - recherche.
	session_start();

	// Inclusion des fichiers de configuration - recherche.
	include_once 'config.php';
	include_once 'Exploitation.php';

	// Initialisation des variables, arguments de lister() - recherche.
	if($_GET["keyword_recherche"] == "") {
		// Vérification cohérence des pages envoyées.
		header('Location: index.php');
		exit(0);
	}
	if(strlen($_GET["keyword_recherche"]) <= 3) {
		// Vérification cohérence des pages envoyées.
		header('Location: index.php?recherche_retour=0');
		exit(0);
	}
	$keyword_recherche = $_GET["keyword_recherche"];
	$nombre_recherche = $_GET["nombre_recherche"];
	if($_GET["page_recherche"] > 0) {
		// Vérification cohérence des pages envoyées.
		$page_recherche = $_GET["page_recherche"]; 
	} else { $page_recherche = 1; }
	$champs = array("ALL");
	$champs_condition = array();
	$valeur_condition = array();
	$table_titre = "Recherche - Antiquités";
	$action_selection = "patrimoine.php";
	$table = "objet";
	$bouton_selection = "Valider";
	$bouton_recherche = "Rechercher";
	$retour_page = "index.php";
	$checked = "";
	$requete_objet_filtred = array();
	$requete_objet_i = array();
	$requete_image_filtred = array();
	$requete_image_i = array();
	$nombre_resultats = 0;

	// Connexion à la BDD - recherche.
	$e = new Exploitation();
	$e->connexion();
	// Requête 'objets' - recherche.
	$requete_objet = $e->lister($champs, $champs_condition, $valeur_condition, "objet");
	// Requête 'image' - recherche.
	$requete_image = $e->lister($champs, $champs_condition, $valeur_condition, "image");

	// Filtrage des enregistrements en fonction de la recherche.
	if($requete_objet != false && $requete_objet != null) {
		// Filtrage de chaque ligne.
		for($i=0; $i<sizeof($requete_objet); $i++) {
			$confirm_ligne = 0;
			// Filtrage de chaque élément du tableau d'objets.
			foreach($requete_objet[$i] as $indice => $valeur) {
				if(strstr($valeur, $keyword_recherche)) {
					// Ligne demandée.
					$confirm_ligne = 1;
				}
			}
			// Supression des éléments sans correspondance avec la recherche.
			if(!$confirm_ligne) {
				foreach($requete_objet[$i] as $indice => $valeur) {
					unset($requete_objet[$i][$indice]);
				}
				foreach($requete_image[$i] as $indice => $valeur) {
					unset($requete_image[$i][$indice]);
				}
			} else {	
				// Stockage du numéro de l'élément pour le conserver dans les prochains tableaux.
				$requete_objet_i[$nombre_resultats] = $i;
				$requete_image_i[$nombre_resultats] = $i;
				$nombre_resultats++;
			}
		}

		// Changement de tableau des objets ( pour supprimer les éléments vides ).
		for($i=0; $i<sizeof($requete_objet); $i++) {
			for($j=0; $j<sizeof($requete_objet_i); $j++) {
				if($requete_objet_i[$j] == $i) {
					$requete_objet_filtred[$j] = $requete_objet[$i];
				}
			}
		}
		// Changement de tableau des images ( pour supprimer les éléments vides ).
		for($i=0; $i<sizeof($requete_image); $i++) {
			for($j=0; $j<sizeof($requete_image_i); $j++) {
				if($requete_image_i[$j] == $i) {
					$requete_image_filtred[$j] = $requete_image[$i];
				}
			}
		}
	}

	// Assignation des tableaux filtrés en fonction de la recherche.
	$requete_objet = $requete_objet_filtred;
	$requete_image = $requete_image_filtred;

	// Affichage de l'en-tête de la page.
	echo '<!DOCTYPE html><html><head><meta charset="utf-8" />';
	// Scripts
	echo '<script src="javascript/verification_formulaire.js"></script>';
	echo '</head><body>';

	// Champ de recherche - recherche.
	echo '<form method="get" action="recherche.php"> 
			<label for="keyword_recherche" id="label_recherche">Recherche : </label>
			<input id="keyword_recherche" class="keyword_recherche" name="keyword_recherche" type="search" size="25" placeholder="Tapez votre recherche.." />
			<input id="submit_recherche" class="submit_recherche" value="'.$bouton_recherche.'" type="submit" /><br />
			<label>Nombre de résultats par page : </label>
			<input id="nombre_recherche" class="nombre_recherche" name="nombre_recherche" checked type="radio" value="5" title="Nombre de résultats par page" /> <span>5</span>
			<input id="nombre_recherche" class="nombre_recherche" name="nombre_recherche" type="radio" value="10" title="Nombre de résultats par page" /><span> 10 </span>
			<input type="hidden" name="page_recherche" value="0" />
		</form><br />';
	
	// Affichage des liens et de la navigation.
	echo '<a href="index.php">Menu</a><label> - </label><a href="archives.php">Archives</a><br /><br />';
	echo '<fieldset><legend>'.strtoupper(substr($table_titre, 0, 1)).substr($table_titre, 1).'</legend><form id="form_list" action="'.$action_selection.'"" method="post">';

	// Affichage des infos de la requête ( objet ) - recherche.
	if($requete_objet != false && $requete_objet != null) {
		// Variable intitulé de l'image
		$valeur_objet = "";

		// Calcul du nombre de résultats par pages.
		if(sizeof($requete_objet) > $nombre_recherche) {
			// Contient la dernière page à ne pas dépasser. ( 1 = page non atteinte )
			$derniere_page = 1;

			// Calcul du nombre de pages.
			$page_maximum = sizeof($requete_objet) % $nombre_recherche;
			$reste = sizeof($requete_objet) - $page_maximum * $nombre_recherche;
			if($reste) {
				// Ajout d'une dernière page avec les derniers résultats.
				$page_maximum++;
			}

			// (page1 - 1) * nombre ( 10 ) => ' de 0 à .. '
			$debut_resultat = $nombre_recherche * ($page_recherche-1);
			// page1 * nombre ( 10 ) => ' .. à 10 '
			$fin_resultat = $nombre_recherche * $page_recherche;

			// Vérification limites des résultats.
			if($fin_resultat > sizeof($requete_objet)) {
				$fin_resultat =	sizeof($requete_objet);
				$derniere_page = 0;
			}

			// Fonction d'affichage (arguments : envoi des tableaux ).
			affichageResultat($debut_resultat, $fin_resultat, $requete_objet, $requete_image);

			// Récupération des mots-clés et arguments pour la prochaine page. Suppression de la page actuelle.
			$index = strripos($_SERVER['QUERY_STRING'], '&page_recherche');
			$keywords_get = substr($_SERVER['QUERY_STRING'], 0, $index);

			// Affichage des pages et de la navigation entre les résultats.
			if($page_recherche != 1) {
				// Affichage de la page précédente si elle existe.
				echo '<a href="recherche.php?'.$keywords_get.'&page_recherche='.($page_recherche-1).'">Page '.($page_recherche-1).'</a><label> - </label>';
			} else {
				// Affichage de la page actuelle.
				echo '<label> Page '.$page_recherche.' </label><label> - </label>';
			}
			if($derniere_page) {
				// Affichage de la page suivante si elle existe.
				echo '<a href="recherche.php?'.$keywords_get.'&page_recherche='.($page_recherche+1).'">Page '.($page_recherche+1).'</a><label> - </label>';
			} else {
				// Affichage de la page actuelle. ( si les résultats sont faibles, il n'y a pas de pages )
				echo '<label> Page '.$page_recherche.' </label><label> - </label>';
			}
		} else {
			affichageResultat(0, sizeof($requete_objet), $requete_objet, $requete_image);
		}
	} else {
		// Pas de résultats. Affichage d'une réponse prédéfinie.
		echo '<label>Pas de résultats. </label>
		<ul> <li>Vérifiez l’orthographe des termes de recherche.</li>
		<li>Essayez d\'autres mots.</li>
		<li>Utilisez des mots clés plus généraux.</li>
		<li>Spécifiez un moins grand nombre de mots.</li></ul>';
	}
	echo '<input id="submit_list" class="submit_list" value="'.$bouton_selection.'" type="submit" /><label> - </label><a href="'.$retour_page.'">Retour</a></form></fieldset></div>';
	echo '</body></html>';

	function affichageResultat($debut, $fin, $requete_objet, $requete_image) {
		// Copie des variables utiles.
		global $table;
		global $checked;

		// Affichage des lignes du tableau d'objets par page - recherche.
		for($i=$debut; $i<$fin; $i++) {
			if($i == 0) { $checked = "checked"; }
			else 		{ $checked = ""; }

			// Interface de sélection d'un élément.
			 echo '<div id="'.$table.'_'.$i.'" class="'.$table.'_'.$i.'">
				<input '.$checked.' id="selection_'.$table.'_'.$i.'" class="selection_'.$table.'" name="selection_'.$table.'" value="'.$requete_objet[$i]["numero_".$table].'" type="radio" />';

			// Filtrage du tableau.
			foreach($requete_objet[$i] as $indice => $a) {
				if(is_int($indice) || substr($indice, 0, 7) == 'numero_' || substr($indice, 0, 7) == 'adresse') {
					unset($requete_objet[$i][$indice]);
				}
			}
			// Affichage des objets.
			foreach($requete_objet[$i] as $indice => $valeur) {
				// Mise en place de la typographie ( Nom ).
				$indice_titre = strtoupper(substr($indice, 0, 1)).substr($indice, 1).' : ';
				// Séparation de 'nom' et '_table'
				$indice_titre = str_ireplace('_', ' ', str_ireplace('_'.$table, '', $indice_titre));
				if(substr($indice_titre, 0, 6) == 'Titre_') {
					$indice_titre = "";
					$valeur = ' '.strtoupper(substr($valeur, 0, 1)).strtolower(substr($valeur, 1));
				}
				// Envoi du nom de l'objet sur l'image.
				if(substr($indice_titre, 0, 3) == 'Nom') {
					$valeur_objet = $valeur;
				}
				// Affichage d'un élément.
				echo '<label for="selection_'.$table.'_'.$i.'" id="'.$indice.'" class="'.$indice.'"name="'.$indice.'">'.$indice_titre.$valeur.'</label><br />';
			}

			// Affichage des infos de la requête. ( image )
			if($requete_image != false && $requete_image != null) {
				// Filtrage du tableau.
				foreach($requete_image[$i] as $indice => $a) {
					if(is_int($indice) || substr($indice, 0, 7) == 'numero_') {
						unset($requete_image[$i][$indice]);
					}
				}
				// Affichage d'une image.
				foreach($requete_image[$i] as $indice => $valeur) {
					// Mise en place de la typographie ( Nom ).
					$indice_titre = strtoupper(substr($indice, 0, 1)).substr($indice, 1).' : ';
					// Séparation de 'nom' et '_table'
					$indice_titre = str_ireplace('_', ' ', str_ireplace('_'."image", '', $indice_titre));
					// Champ adresse d'une image ( $indice_titre modifié précédemment ) : lecture du fichier image.
					if(substr($indice_titre, 0, 7) == 'Adresse') {
						$indice_titre = "Image : ";
						$alt_item = $valeur_objet;
						$valeur = '<img title="'.$alt_item.'" alt="'.$alt_item.'" src="images/'.$valeur.'"/>';
						// Affichage d'un élément.
						echo '<label for="selection_'."image".'_'.$i.'" id="'.$indice.'" class="'.$indice.'"name="'.$indice.'">'.$indice_titre.'</label>'.$valeur.'<br />';
					}
				}
			}
			echo '</div><br /><br />';
		}
	}

?>