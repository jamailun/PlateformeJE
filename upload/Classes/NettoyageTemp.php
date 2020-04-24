<?php
// Exemple de classe qui efface tous les fichiers de $repertoire dont la dernière date de modification a plus de $validite heures, à l'exception du fichier .htaccess et des dossiers (require 'ParamsDefautServeur.php')

// Pour charger la classe (trait) "ParamsDefautServeur" utilisée dans la classe "NettoyageTemp"
spl_autoload_register(function ($class) {require $class . '.php';});


class NettoyageTemp
{
	// Paramètres par défaut
	use ParamsDefautServeur;

	public function __construct()
	{
		$this->maintenant = time();
	}


	public function nettoyage ($repertoire, $heures)
	{
		// En secondes
		$validite = $heures*3600; 

		try
		{
			$iterator = new DirectoryIterator($repertoire);
			foreach ($iterator as $fileinfo) 
			{
				if ($fileinfo->isFile() && $fileinfo->getFilename() != '.htaccess' && ($this->maintenant - $fileinfo->getMTime()) > $validite) 
				{
					unlink($fileinfo->getPathname());
				}
			}
		}
		catch (Exception $e) 
		{
			// echo $e->getMessage();
			return false;
		}
		
		return true;
	}
}



$nettoyage = new NettoyageTemp;

// Le répertoire temporaire à nettoyer et la durée de vie de validité du cookie sont récupérés ici avec la méthode getParamUploadAjaxABCIServeur() définie dans "ParamsDefautServeur.php" (si vous avez défini des valeurs différentes dans l'initialisation de la classe "UploadAjaxABCIServeur" il faudra modifier le code en conséquence).


// Récupération des paramètres définis dans le fichier "ParamsDefautServeur.php"
$param = $nettoyage->getParamUploadAjaxABCIServeur();

$repertoire = $param->dossier_temporaire;

// Répertoire parent du parent = "Php_Upload"
$php_upload = pathinfo(__DIR__,PATHINFO_DIRNAME);

$adresse_repertoire = $php_upload.'/'.$repertoire;

// Récupération de la durée de validité du cookie avec la méthode getDefautValideCookieServeur 
$valide_cookie = $param->cookie_heures;

return $nettoyage->nettoyage($adresse_repertoire, $valide_cookie);
?>