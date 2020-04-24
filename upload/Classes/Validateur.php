<?php
/*
Classe utilisée pour l'exemple "UploadAjaxABCI_Inscription_Photo_Crop.php".

La classe "Validateur" est alimentée par le tableau d'erreurs défini dans la méthode "inscription()" de la classe "Messages". Le préfixe de l'index correspond au nom du champ html contrôlé. Le suffixe de l'index (après le '_') correspond au nom de la fonction de contrôle, complété par un second identifiant quand celle-ci peut retourner plusieurs messages d'erreurs différents (cf fichier "Classes/Messages.php").

Suivant que le contrôle est d'ordre général ou spécifique à un champ que doit remplir l'utilisateur, les messages d'erreurs sont enregistrés dans les tableaux $erreur_controle ou $erreur_valide qui seront exploités par Javascript au retour de la requête ajax de même que $post_controle qui liste les champs utilisateur contrôlés.

Pour ajouter un nouveau contrôle, vous devez donc :

- Ajouter une ligne dans le "switch ($controle)", 
- Définir la fonction correspondante, 
- Ainsi que le message correspondant dans la méthode "inscription()" de la classe "Messages". 

Et s'il s'agit d'un contrôle indépendant d'un champ que doit remplir le visiteur, faites précéder l'appel de la fonction par "array_pop(self::$post_controle)" tel que dans la case "verifToken". En effet $post_controle est utilisé au retour de la requête ajax pour lister les champs utilisateur contrôlés.
*/

class Validateur extends SetMessages
{
	protected static $post_controle = [];
	protected static $erreur_valide = [];
	private static $P;
	public static $cumul = false;
	
	
	// Fonction principale de distribution des contrôles qui initialise le contructeur de la classe
	public static function post($params=[], $option = null)
    {
		if(!is_array(self::$P)) {self::newSetMessages();}
		
		foreach ($params as $input => $controles) 
		{
			// $post_controle enregistre les champs utilisateur contrôlés (utilisé dans le retour de la requête ajax)
			self::$post_controle[] = $input;
			
			if(is_array($controles))
			{
				foreach($controles as $controle => $value)
				{
					$controle = is_numeric($controle)? $value : $controle;
					
					if((!isset(self::$erreur_valide[$input]) && count(self::$erreur_controle) == 0) || self::$cumul)
					// Pour rajouter des contrôles ajouter une case dans le switch, créez la fonction correspondante et renseignez le ou les messages correspondants dans la classe "Messages" 
					switch ($controle)
					{
						case 'maxChar' : 	self::maxChar($input, $value);break;
						case 'minChar' : 	self::minChar($input, $value);break;
						case 'requis' : 	self::requis($input);break;
						case 'validMail' : 	self::validMail($input);break;
						case 'existBdd' : 	self::existBdd($input, $value, $option);break;
						case 'equal' : 		self::equal($input, $value);break;
						
						// Avec array_pop, j'exclus du tableau "$post_controle" l'input qui utilise la fonction "verifToken()" car ce tableau est exploité uniquement côté Javascript pour lister spécifiquement les contrôles liés à l'enregistrement des champs à remplir par le visiteur.
						case 'verifToken' :	array_pop(self::$post_controle);
											self::verifToken($input, $value, $option);
											break;
					}
				}
			}
		}
	}


	// Fonction de retour générale
	public static function result ()
	{
		// Tableau des input contrôlés
		$retour = ['post_controle' => self::$post_controle];
	
		// Tableau des input contrôlés non valides
		if(!empty(self::$erreur_valide))
		{
			$retour += ['erreur_valide' => self::$erreur_valide];
		}

		// Tableau des erreurs générales (script mal configuré, erreur de connexion à la bdd, token du formulaire non valide, erreur de requête etc). $erreur_controle est hérité de la classe SetMessages
		if(!empty(self::$erreur_controle))
		{
			$retour += ['erreur_controle' => self::$erreur_controle];
		}
		
		return $retour;
    }
	
	
	// Fonction de retour des erreurs de validation des champs utilsateur du formulaire
	public static function erreurValide ()
	{		
		return !empty(self::$erreur_valide) ? self::$erreur_valide : false;
    }


	private static function setMessInput ($input, $controle)
	{
		return self::setMess($input.'_'.$controle);
	}

	
	private static function newSetMessages()
	{
		self::$P = $_POST;
		array_walk_recursive(self::$P, function(&$item){$item = urldecode($item);});
	}


	// Fonctions de contrôles
	private static function verifToken($input, $ses_index, $token)
	{
		$false = !isset(self::$P[$input], $_SESSION[$ses_index][self::$P[$input]][$token]);
		
		if($false) self::$erreur_controle[] = self::setMess('UpAbVerifToken');
	}


	private static function existBdd($input, $champ, $option)
	{
		if (isset(self::$P[$input])) 
		{
			try
			{
				$select = $option['connect']->prepare("SELECT count(*) FROM ".$option['table']." WHERE ".$champ." = ?");
			
				$select->execute([self::$P[$input]]);
				  
				$resultat = $select->fetchColumn();
				
				if ($resultat > 0)
				{
					self::$erreur_valide[$input][] = self::setMessInput($input, 'existBdd');	
				}
			}
			catch (PDOException  $e)
			{
				// Mode debug
				// self::$erreur_controle[] = $e->getMessage();
				
				// Mode production
				self::$erreur_controle[] = self::setMessInput($input, 'existBdd_fail');
			}
		}
	}


	private static function requis($input)
	{
		$false = !isset(self::$P[$input]) || trim(self::$P[$input]) == "";
		
		if($false) self::$erreur_valide[$input][] = self::setMessInput($input, 'requis');
	}


	private static function maxChar($input,$value)
	{
		$false = isset(self::$P[$input]) && mb_strlen(self::$P[$input]) > $value;
		
		if($false) self::$erreur_valide[$input][] = self::setMessInput($input, 'maxChar');
	}


	private static function minChar($input,$value)
	{
		$false = isset(self::$P[$input]) && mb_strlen(self::$P[$input]) < $value;
		
		if($false) self::$erreur_valide[$input][] = self::setMessInput($input, 'minChar');
	}


	private static function validMail($input)
	{
		$false = isset(self::$P[$input]) && filter_var(self::$P[$input], FILTER_VALIDATE_EMAIL) === false;
		
		if($false) self::$erreur_valide[$input][] = self::setMessInput($input, 'validMail');
	}
	
	
	private static function equal($input, $input2)
	{
		$false = isset(self::$P[$input],self::$P[$input2]) && self::$P[$input] != self::$P[$input2];
		
		if($false) self::$erreur_valide[$input][] = self::setMessInput($input, 'equal');
	}
}
?>