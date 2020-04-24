<?php
class C_PDO
{
	use ParamsDefautServeur;
	
	private static $connexion;
	
	private static function newC_PDO() 
	{
		new SetMessages('connexionBdd');

		try
		{
			// Paramètres de la connexion bdd défini par la fonction "getParamConnectBdd" dans le fichier 'Classes/ParamsDefautServeur.php'
			$params = self::getParamConnectBdd();
			
			// Options de configuration PDO utilisées dans les exemples
			$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;		
			$pdo_options[PDO::ATTR_EMULATE_PREPARES] = false;
			$pdo_options[PDO::ATTR_DEFAULT_FETCH_MODE] = PDO::FETCH_OBJ;
			
			self::$connexion  = new PDO('mysql:host='.$params->hostname.';dbname='.$params->database.';charset=utf8', $params->username, $params->password, $pdo_options);	
		}
		catch (PDOException  $e)
		{
			// Mode debug
			// SetMessages::$erreur_controle[] = $e->getMessage();
			
			// Mode production
			SetMessages::$erreur_controle[] = SetMessages::setMess('UpAbConnectBdd');
		}
	}

	public static function getC() 
	{
		if(self::$connexion == NULL) {self::newC_PDO();}
		return self::$connexion;
	}	 
}
?>