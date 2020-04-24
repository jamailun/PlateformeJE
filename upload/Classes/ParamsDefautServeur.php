<?php
trait ParamsDefautServeur 
{
	/*
	VALEURS PAR DEFAUT DE LA CLASSE "UploadAjaxABCIServeur.php" : 
	
	Les adresses des dossiers sont à définir par rapport à "Php_Upload" (dossier des scripts d'upload  ajax-serveur).
	*/
	public static function getParamUploadAjaxABCIServeur ()
	{
		// Je crée un tableau que je retourne en objet pour homogéneité avec le reste du code
		$tab['dossier_destination'] = '../Destination_Upload1/';
		$tab['dossier_temporaire'] = 'Upload_Temp/';
		$tab['cookie_heures'] = 24;// en heures
		$tab['cookie_path'] = '/';
		$tab['verif_filesize_sup2Go'] = false;
	
		return (object) $tab;	
	}
	

	// VARIABLES DE CONNEXION BDD A DEFINNIR AVEC VOS VALEURS :
	protected static function getParamConnectBdd ()
	{
		$hostname = "localhost";
		$database = "alainbweb";
		$username = "root";
		$password = "";
		
		/* REMARQUE IMPORTANTE :
		La classe de connexion bdd peut être appelée depuis différents endroits du site, notamment si vous utilisez la classe "UploadFormABCIServeur.php" pour compatibilité avec les navigateurs obsolètes.
		
		SI VOUS SOUHAITEZ IMPORTER VOS VARIABLES DE CONNEXIONS DEPUIS UN FICHIER EXTERNE, pensez à définir l'adresse de ce fichier à partir d'une adresse absolue ou statique avec la constante magique __DIR__. Par exemple en utilisant : 
		
		a/ $_SERVER['DOCUMENT_ROOT'] pour définir l'adresse du fichier par rapport à la racine du serveur,		            
		ou 
		b/ "__DIR__" qui permettra de vous positionner par rapport au dossier "Php_Upload" en faisant "pathinfo(__DIR__,PATHINFO_DIRNAME)".
		
		Par exemple si vous avez un dossier "Connexions" situé à la racine du site et contenant un fichier nommé "connexion_bdd.php" 
		a/ :
		require $_SERVER['DOCUMENT_ROOT'].'Connexions/connexion_bdd.php';
		
		ou, en admettant que le dossier "Upload-Ajax-ABCI" est également situé à la racine du site :
		b/ :
		require pathinfo(__DIR__,PATHINFO_DIRNAME).'/../../Connexions/connexion_bdd.php';
		
		NOTE :
		La syntaxe b/ est plus souple dans le sens ou elle vous permettra d'utiliser ce script indifféremment sur un serveur en production ou en local. Mais il faudra penser à ajuster cette adresse si vous modifiez l'emplacement des dossiers "Upload-Ajax-ABCI" ou "Connexions". 
		
		Le problème d'utiliser $_SERVER['DOCUMENT_ROOT'] est que cela retourne l'adresse de la racine du serveur et qu'en local on a souvent plusieurs sites dans ce répertoire. Aussi pour tester en local l'adresse doit être contruite avec le nom du site local, soit :
		require $_SERVER['DOCUMENT_ROOT'].'nom_du_site_local/Connexions/connexion_bdd.php';
		
		(on pourrait aussi utiliser la variable serveur 'HTTP_HOST', mais elle n'est pas toujours définie)
		*/
				
		// Je crée un tableau que je retourne en objet pour homogéneité avec le reste du code
		$tab['hostname'] = $hostname;
		$tab['database'] = $database;
		$tab['username'] = $username;
		$tab['password'] = $password;
	
		return (object) $tab;
	}
}
?>