<?php

// Constantes d'adresses relatives des pages du projet.
define('HOST', 'http://'.$_SERVER["HTTP_HOST"]); 	// DNS ou ( 'localhost' en local )
define('RACINE', $_SERVER["DOCUMENT_ROOT"]);		// @ du répertoire des sources ( workspace )
define('PHP_SELF', $_SERVER['PHP_SELF']);			// URL sans le DNS

// Identifiants SQL.
define('SQL_SERVER', 'localhost');
define('SQL_BDD', 'pti_magreb');
define('SQL_USER', 'root');
define('SQL_PWD', 'root');
define('BDD', 'mysql:host='.SQL_SERVER.';dbname='.SQL_BDD);

// Chargement des Class.
function __autoload($class) {
	$cheminClass = RACINE.$class.'.php'; 
	if(file_exists($cheminClass)) {
		require_once($cheminClass);
	}
}

?>