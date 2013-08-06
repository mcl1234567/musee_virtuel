<?php

	// Démarre une session UTILISATEUR - patrimoine.
	session_start();
	echo '<!DOCTYPE html><html><head><meta charset="utf-8" />';
	// Scripts
	echo '<script src="javascript/verification_formulaire.js.js"></script>';
	echo '</head><body>';

	// Inclusion des fichiers de configuration - patrimoine.
	include_once 'config.php';
	include_once 'Exploitation.php';

	// Initialisation des variables, arguments de lister() - patrimoine.
	$numeroObjet = $_POST["selection_objet"];
	$arguments_page = $_SERVER['QUERY_STRING'];
	$champs = array("ALL");
	$champs_condition = array("numero_objet");
	$valeur_condition = array($numeroObjet);
	$table_titre = "Suppression d'un objet";
	$action_selection = "suppression.php";
	$table = "objet";
	$bouton_selection = "Supprimer";
	$bouton_recherche = "Rechercher";
	$retour_page = "index.php";

	// Connexion à la BDD - patrimoine.
	$e = new Exploitation();
	$e->connexion();
	// Requête 'objets'
	$requete_objet = $e->lister($champs, $champs_condition, $valeur_condition, "objet");

	// Constantes requête 'image' - patrimoine.
	$champs = array("ALL");
	$champs_condition = array("numero_objet");
	$valeur_condition = array($numeroObjet);
	// Requête 'image'.
	$requete_image = $e->lister($champs, $champs_condition, $valeur_condition, "image");

	// Affichage des résultats - patrimoine.
	echo '<a href="index.php">Menu</a><label> - </label><a href="archives.php">Archives</a><br /><br />';
	echo '<fieldset><legend>'.strtoupper(substr($table_titre, 0, 1)).substr($table_titre, 1).'</legend><form id="form_list" action="'.$action_selection.'"" method="post">';
	// Affichage des infos de la requête. ( objet )
	if($requete_objet != false && $requete_objet != null) {
		// Variable intitulé de l'image
		$valeur_objet = "";
		// Affichage de chaque ligne du tableau d'objets.
		for($i=0; $i<sizeof($requete_objet); $i++) {
			// Interface de sélection d'un élément.
		 	echo '<div id="'.$table.'_'.$i.'" class="'.$table.'_'.$i.'"><input id="selection_'.$table.'_'.$i.'" class="selection_'.$table.'" name="selection_'.$table.'" value="'.$requete_objet[$i]['numero_'.$table.''].'" type="radio" />';

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
	echo '<input id="submit_list" class="submit_list" value="'.$bouton_selection.'" type="submit" /><label> - </label><a href="'.$retour_page.'">Retour</a></form></fieldset></div>';
	echo '</body</html>';
?>