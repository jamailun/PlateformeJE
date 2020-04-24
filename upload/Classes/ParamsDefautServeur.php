<?php
trait ParamsDefautServeur 
{
	/*
	VALEURS PAR DEFAUT DE LA CLASSE "UploadAjaxABCIServeur.php" : 
	
	Les adresses des dossiers sont � d�finir par rapport � "Php_Upload" (dossier des scripts d'upload  ajax-serveur).
	*/
	public static function getParamUploadAjaxABCIServeur ()
	{
		// Je cr�e un tableau que je retourne en objet pour homog�neit� avec le reste du code
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
		La classe de connexion bdd peut �tre appel�e depuis diff�rents endroits du site, notamment si vous utilisez la classe "UploadFormABCIServeur.php" pour compatibilit� avec les navigateurs obsol�tes.
		
		SI VOUS SOUHAITEZ IMPORTER VOS VARIABLES DE CONNEXIONS DEPUIS UN FICHIER EXTERNE, pensez � d�finir l'adresse de ce fichier � partir d'une adresse absolue ou statique avec la constante magique __DIR__. Par exemple en utilisant : 
		
		a/ $_SERVER['DOCUMENT_ROOT'] pour d�finir l'adresse du fichier par rapport � la racine du serveur,		            
		ou 
		b/ "__DIR__" qui permettra de vous positionner par rapport au dossier "Php_Upload" en faisant "pathinfo(__DIR__,PATHINFO_DIRNAME)".
		
		Par exemple si vous avez un dossier "Connexions" situ� � la racine du site et contenant un fichier nomm� "connexion_bdd.php" 
		a/ :
		require $_SERVER['DOCUMENT_ROOT'].'Connexions/connexion_bdd.php';
		
		ou, en admettant que le dossier "Upload-Ajax-ABCI" est �galement situ� � la racine du site :
		b/ :
		require pathinfo(__DIR__,PATHINFO_DIRNAME).'/../../Connexions/connexion_bdd.php';
		
		NOTE :
		La syntaxe b/ est plus souple dans le sens ou elle vous permettra d'utiliser ce script indiff�remment sur un serveur en production ou en local. Mais il faudra penser � ajuster cette adresse si vous modifiez l'emplacement des dossiers "Upload-Ajax-ABCI" ou "Connexions". 
		
		Le probl�me d'utiliser $_SERVER['DOCUMENT_ROOT'] est que cela retourne l'adresse de la racine du serveur et qu'en local on a souvent plusieurs sites dans ce r�pertoire. Aussi pour tester en local l'adresse doit �tre contruite avec le nom du site local, soit :
		require $_SERVER['DOCUMENT_ROOT'].'nom_du_site_local/Connexions/connexion_bdd.php';
		
		(on pourrait aussi utiliser la variable serveur 'HTTP_HOST', mais elle n'est pas toujours d�finie)
		*/
				
		// Je cr�e un tableau que je retourne en objet pour homog�neit� avec le reste du code
		$tab['hostname'] = $hostname;
		$tab['database'] = $database;
		$tab['username'] = $username;
		$tab['password'] = $password;
	
		return (object) $tab;
	}
}
?>