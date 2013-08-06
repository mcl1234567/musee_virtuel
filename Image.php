<?php 

class Image {
	private $largeur;
	private $hauteur;
	private $extension;
	private $champs;
	private $valeurs;

	function __construct() {
		$this->champs = array("numero_image", "numero_objet", "nom_image", "adresse_image", "hauteur_image", "largeur_image");
		$this->extension = "png";	// extension par défaut.
		$this->hauteur = 120;		// hauteur par défaut.
		$this->largeur = 150;		// largeur par défaut.
	}

	/**		
	 * Upload d'une image en JPEG.
	 * @param int $numero = numéro de l'image
	 * @param unknown_type $_FILES = fichier image
	 * @return string : liens de l'image
	 */
	public function uploaderImageJPG($nom_image, $_FILES) {
		// Initilaisation des variables.
		// $_FILES['image_objet'] : image_objet est modifié si le formulaire change !!
		$origine_image = imagecreatefromjpeg($_FILES['image_objet']['tmp_name']);
		$taille_image = getimagesize($_FILES['image_objet']['tmp_name']);
		$lien_image = 'images/'.$nom_image;

		imagejpeg($origine_image, $lien_image, 100);
		return $lien_image;
	}

	/**		
	 * Upload d'une image en PNG.
	 * @param int $numero = numéro de l'image
	 * @param unknown_type $_FILES = fichier image
	 * @return multitype:string : liens de l'image
	 */
	public function uploaderImagePNG($nom_image, $_FILES) {
		// Initilaisation des variables.
		// $_FILES['image_objet'] : image_objet est modifié si le formulaire change !!
		$origine_image = imagecreatefrompng($_FILES['image_objet']['tmp_name']);
		$lien_image = 'images/'.$nom_image;

		imagepng($origine_image, $lien_image, 100);
		return $lien_image;
	}

	/**
	 * Enregistrement -> bdd - des informations d'une image & appel de la fonction d'upload.
	 * @param unknown_type $conditions
	 * @param unknown_type $valeur
	 * @param unknown_type $fichier_upload
	 */
	public function ajouterImage($fichier_image, $numero_objet, $nom_image) {
		// Initilaisation des variables.
		// $_FILES['image_objet'] : image_objet est modifié si le formulaire change !!
		// Adresse absolue.
		$adresse_absolue = "C:\wamp\www\projet_bdd\images\\nom_image".$this->extension;
		// Adresse relative.
		$adresse_relative = $nom_image.'.'.$this->extension;
		$dimension_origine = getimagesize($fichier_image['image_objet']['tmp_name']);
		$this->champs = 	 array("numero_objet", "nom_image", "adresse_image", "hauteur_image", "largeur_image");
		$this->valeurs = 	 array($numero_objet, $nom_image, $adresse_relative, $dimension_origine[1], $dimension_origine[0]);

		$c = new Exploitation();
		$c->connexion();
		// Insertion dans la BDD.
		$c->insertion($this->champs, $this->valeurs, "image");
		// Appel de la méthode d'upload.
		$liens = self::uploaderImageJPG($adresse_relative, $fichier_image);
	}

	/**
	 * Afficher les informations d'une image.
	 * @param unknown_type $conditions
	 * @param unknown_type $valeur
	 * @return Ambigous <tableau, PDOStatement>
	*/
	public function afficherImage($conditions, $valeurs) {
		$c = new Exploitation();
		$c->connexion();
		return $c->lister($this->champs, $conditions, $valeurs, "image");
	}

	/**
	 * Affichage des champs pour la création d'une image.
	 * @return Ambigous <tableau, PDOStatement>
	 */
	public function afficherTablesImage() {
		// Initilaisation des variables.
		$valeurs_condition = array();
		$champs_condition = array("columns");

		// Affichage des informations de la BDD.
		$c = new Exploitation();
		$c->connexion();
		return $c->lister($this->champs, $champs_condition, $valeurs_condition, "image");
	}

	/**
	 * Modifier une image.
	 * @param integer $numero_image
	 * @param unknown_type $libelle
	 * @param unknown_type $description
	 * @param unknown_type $qualite
	 */
	public function modifierImage($numero_image, $nom) {
		// Initilaisation des variables.
		$this->champs = array("nom_image");
		$this->valeurs = array($nom);

		// Affichage des informations de la BDD.
		$c = new Exploitation();
		$c->connexion();
		$c->modification($this->champs, "numero_image", $numero_image, $this->valeurs, "image");
	}

	/**
	 * Modifie le(s) image(s) du diaporama modifié - ne prends pas en compte l'extension - à tester.
	 * @param integer $numero
	 * @param integer $hauteur
	 * @param integer $largeur
	 */
	function changerDimension($numero, $hauteur, $largeur) {
		// Initilaisation des variables.
		$champs_condition = array('numero_carousel');
		$valeurs_condition = array($numero);
		$champs = array("numero_image", "lien_origine_image", "lien_diaporama_image");

		// Récupération des informations de la BDD.
		$c = new Exploitation();
		$c->connexion();
		$requete = $c->lister($champs, $champs_condition, $valeurs_condition, "image");

		for($i=0; $i<sizeof($requete); $i++) {
			// Suppression & Upload de l'image de diaporama
			$origine = $requete[$i]['lien_origine_image'];
			$nom_image = $requete[$i]['numero_image'].'_'.time();
			$origine_image = imagecreatefromjpeg($origine);
			$taille_image = getimagesize($origine);
			$diaporama_image = imagecreatetruecolor($largeur , $hauteur);
			imagecopyresampled($diaporama_image, $origine_image, 0, 0, 0, 0, $largeur, $hauteur, $taille_image[0], $taille_image[1]);
			$lien_diaporama_image = 'album/diaporama_image/'.$nom_image.'.'.$this->extension;
			imagejpeg($diaporama_image , $lien_diaporama_image, 100);

			// Nouveau lien de l'image du diaporama modifié
			$valeur = array($lien_diaporama_image);
			$champs = array("lien_diaporama_image");
			$c->modification($champs, 'numero_image', $requete[$i]['numero_image'], $valeur, "image");
			unlink($requete[$i]['lien_diaporama_image']);
		}
	}

	/**
	 * Suppression d'une image.
	 * @param integer $numero_image
	 */
	public function supprimerImage($numero_image, $adresse) {
		// Initialisation des variables.
		$champs_condition = array("numero_image");
		$valeurs_condition = array($numero_image);

		// Récupération des informations et suppression dans la BDD.
		$c = new Exploitation();
		$c->connexion();
		$requete = $c->lister($this->champs, $champs_condition, $valeurs_condition, "image");

		// Supression dans la BDD.
		$c->suppression($champs_condition, $valeurs_condition, 'image');

		// Supression dans la mémoire.
		unlink($adresse.$requete[0]['adresse_image']);
	}

}

?>