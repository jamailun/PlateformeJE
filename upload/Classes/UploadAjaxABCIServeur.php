<?php
class UploadAjaxABCIServeur extends UploadABCIServices
{
	private $version = '6.5';
	
	/* 
	- IMPORTANT : Vous pouvez modifier les valeurs de $this->reponse_upload['upabci_erreur'] qui sont dans Le fichier "Classes/Messages.php", MAIS PAS les valeurs de $this->reponse_upload['upabci_resultat'] qui sont des commandes ajax et qui de toutes façons n'apparaîtront jamais dans la réponse html.
	*/

	
	// Gestion des erreurs fatales du serveur cf. mode d'emploi
	public function cathErrorServeur($array_erreur)
	{
		is_array($array_erreur) && count($array_erreur) > 0 ? $this->config_erreur_serveur = $array_erreur : '';
	}

	// Fonction à utiliser juste après l'initialisation de la classe pour retourner les erreurs fatales non attrapées par la fonction "cathErrorServeur()" précédente. A n'utiliser qu'en phase de développement.
	public function setModeDebug()
	{
		ini_set('display_errors', 1);
		$this->mode_debug = true;
	}

	/*  --------------- Récupération des paramètres de la requête Ajax --------------------
	cf. mode d'emploi
	*/
	
	public function getParam($index)
	{
		if(isset($this->UpAbci_form[$index]))
		{
			return $this->UpAbci_form[$index];
		}
		else if(isset($this->UpAbci_formEnd[0][$index]))
		{
			switch ($index)
			{
				case 'id_form' :
				case 'uniqid_form' :
				case 'iteration_form' : return $this->UpAbci_formEnd[0][$index];break;
			} 
		}
		return false;
	}
	
	
	/* 
	getFileEnd() retourne true si c'est la fin du fichier sinon false. Retourne indéfini si aucun fragment de fichier n'est joint dans la requête. 
	A NOTER que cette fonction retourne la valeur $UpAbci_fileEnd (cité plus haut) envoyée par javascript avant tout traitement du fichier par le serveur. Utilisez la fonction "getTempAdressFileComplete()" après la fonction "Upload()" pour avoir confirmation que le fichier est correctement enregistré dans le dossier temporaire du serveur ou "getTransfertOk()" après la fonction "Transfert ()" pour avoir confirmation que le fichier est transféré correctement dans son emplacement définitif.
	*/
	public function getFileEnd()
	{
		return $this->UpAbci_fileEnd;
	}
	
	
	public function saveAll($value = true)
	{ // A utiliser impérativement AVANT la fonction "Upload()" sinon elle est inopérante. Cette fonction permet d'utiliser le répertoire temporaire pour stocker tous les fichiers y compris ceux dont la taille est inférieure à la taille des fragemnts (vous pouvez faire $up->saveAll(false) si vous souhaitez désactiver cette fonctionnalité après l'avoir activée).
		$this->save_all = $value;
	}
	
	
	public function getFragment()
	{ // Retourne true si un fichier est joint dans la requête ou si une sauvegarde complète a été trouvée pour le fichier.
		return isset($this->UpAbci_fragment);
	}


	public function getFragmentSize()
	{ // Retourne la taille du fragment. Ne pas utiliser pour tester la présence d'un fragment car si une sauvergarde complète est trouvée cette valeur sera définie à zéro. Seule l'utilisation de getFragment() doit être utilisée pour tester la présence d'un fichier joint dans la requête.
		return isset($this->UpAbci_fragment['size']) ? $this->UpAbci_fragment['size'] : 0;
	}


	public function getCleanFileName()
	{ // Retourne le nom du fichier nettoyé. 
		return $this->cleanFname;
	}
		
	
	public function getFileDestination()
	{ // Retourne la destination avec le nom du fichier nettoyé soit $dossier_destination.$nom_fichier_nettoye
		return $this->file_destination;
	}

	
	public function addInfosServer($value)
	{ // Ajoute un message texte ou html dans le retour d'information général du formulaire ayant la classe "UpAbci_infosServer". Pourra également être récupéré en second paramètre des fonctions javascript événementielles "config.func_FileEndEach" et "config.func_FormEnd"
		$this->reponse_upload = array_merge($this->reponse_upload,["upabci_infos_server" => $value]);
	}
	
	
	public function addMixteServer($mixte)
	{ // Envoie un contenu texte, html ou un tableau de données qui pourra être récupéré en troisième paramètre des fonctions javascript événementielles "config.func_FileEndEach" et "config.func_FormEnd". N'affiche rien dans le html. Vous devrez exploiter ces données comme bon vous semble.
		$this->reponse_upload = array_merge($this->reponse_upload,["upabci_mixte_server" => $mixte]);
	}


	/* Stoppe la soumission du formulaire. Cette commande ne sort pas du script php en cours, utilisez "exitReponseAjax()" par la suite.
	Si l'option de configuration javascript config.queryFormEnd = true l'éventuelle requête de confirmation de fin de formulaire sera envoyée uniquement si le paramètre $query_end = true. 
	*/
	public function stopForm($query_end = false)
	{	
		$this->reponse_upload['upabci_stop_form'] = trim($query_end) != false ? 1 : 0; // ne pas modifier
	}

	
	/* 
	Sort du script en ajoutant un message qui sera concaténé au statut "info.status.erreur" de la classe javascript et envoyé dans le bloc html du fichier ayant la classe "UpAbci_status". Ne transmet pas les éventuels messages précédemment ajoutés avec les fonctions addInfosServer() ou addMixteServer(), pour ce faire utilisez plutôt la fonction addStatusErreur($value). 
	*/
	public function exitStatusErreur($value)
	{ 
		exit(json_encode(['upabci_erreur' => $value]));
	}


	/* Ajoute un message qui sera concaténé au statut "info.status.erreur" de la classe javascript et envoyé dans le bloc html ayant la classe "UpAbci_status". Ne sort pas du script, utilisez "exitReponseAjax()" par la suite. 
	A utiliser après la fonction "Upload" si vous souhaitez préserver un fichier partiellement ou totalement téléchargé.
	*/
	public function addStatusErreur($value)
	{
		if(empty($this->reponse_upload['upabci_erreur'])) 
		{
			$this->reponse_upload['upabci_erreur'] = $value;
		}
		$this->reponse_upload['upabci_resultat'] = 'add_status_erreur';// ne pas modifier
		return false;
	}
	
	
	// A utiliser APRES la fonction "Upload"
	public function getTempAdressFileComplete()
	{ // Retourne l'adresse du fichier temporaire s'il est complet et valide sinon false
		return $this->fichier_verif ? $this->file_temp_address : false;
	}


	// A utiliser APRES la fonction "Upload"
	public function getTempAdressFile()
	{ // Retourne l'adresse du fichier temporaire. Peut être un fragment de fichier.
		return is_file($this->file_temp_address)? $this->file_temp_address : false;
	}
	

    /******* IMPORTANT  ******* 
	
	Les deux fonctions "deleteCookieSave()" et "setTransfertOk()" doivent être utilisées dans les cas particuliers où l'on utilise pas la fonction "Transfert()" qui se charge habituellement de ces tâches si tout le processus d'upload est terminé et ok. 
	
	En complément vous devrez également supprimer le fichier temporaire habituellement déplacé vers son emplacement définitif par la fonction "Transfert()". Vous pourrez récupérer son adresse avec "getTempAdressFileComplete()". Ce point est facultatif pour un fonctionnement correct des scripts mais permet de ne pas encombrer inutilement le dossier des fichiers temporaires. 
	
	Par contre l'effacement du cookie de sauvegarde avec "deleteCookieSave()" quand le fichier est complet, et la transmission du status ok avec "setTransfertOk()" quand tout le processus est ok, sont indispensables au bon fonctionnement de la classe javascript si l'on utilise pas la fonction "Transfert()".
	
	L'utilisation de ces deux fonctions conjointement à l'utilisation de la fonction "Tranfert()" se traduira le plus souvent par un bug côté php.
	*/

	// à utiliser APRES la fonction "Upload" et après avoir testé que le fichier est complet : supprime le cookie de sauvegarde (cf note IMPORTANT ci-dessus)
	public function deleteCookieSave()
	{
		setcookie($this->cookie_name,"",time()-3600,$this->cookie_path);
	}


	// à utiliser APRES la fonction "Upload" et après avoir testé que le fichier est complet : donne le status ok à la requête ajax (cf note IMPORTANT ci-dessus)
	public function setTransfertOk()
	{
		$this->reponse_upload['upabci_resultat'] = 'upload_ok'; // ne pas modifier
	}

	/***** Fin de la remarque IMPORTANT *****/
	
	
	// à utiliser normalement APRES la fonction "Transfert" 
	public function addStatusOk($value)
	{ // ajoute un message qui sera concaténé au statut "info.status.ok" de la classe javascript et envoyé dans le bloc html ayant la classe "UpAbci_status"
		$this->reponse_upload = array_merge($this->reponse_upload,['upabci_ok' => $value]);
	}
	
	
	// à utiliser APRES la fonction "Transfert". A le même effet que de tester le retour de la fonction Transfert().
	public function getTransfertOk()
	{ // Retourne true si le fichier est complet et a été déplacé avec succès vers son emplacement définitif.
		return isset($this->reponse_upload['upabci_resultat']) && $this->reponse_upload['upabci_resultat'] == 'upload_ok';
	}
	
	
	// Retour d'information INDISPENSABLE pour le script ajax excepté si la fonction "exitStatusErreur()" a été utilisée (on peut alternativement utiliser la fonction "getReponseAjax" dans d'autres contextes).
	public function exitReponseAjax()
	{
		exit(json_encode($this->reponse_upload));
	}


	//  Alternative à la fonction exitReponseAjax. Retourne les informations nécessaires pour le retour ajax mais ne sort pas du script php.
	public function getReponseAjax()
	{
		return $this->reponse_upload;
	}

	// ------------------------------------------------------------------------------- 
	// -------------------------------------------------------------------------------
	
	// Paramètres par défaut : classe (trait) défini dans le fichier "ParamsDefautServeur.php"
	use ParamsDefautServeur;


	// Variables ajax 
	public $UpAbci_form = [];
	public $UpAbci_formEnd = [];
	protected $UpAbci_fragment;
	protected $UpAbci_blobSlice;
	protected $UpAbci_fileEnd;

	
	// Variables php
	protected $config_erreur_serveur = [];
	protected $mode_debug = false;
	protected $dossier_destination;
	protected $dossier_temporaire;
	protected $cleanFname;
	protected $cookie_time;
	protected $cookie_path;
	protected $verif_filesize_sup2Go;
	protected $cookie_name;
	protected $cookie_filesize;
	protected $file_temp_address;
	protected $file_destination;
	protected $fichier_verif = false;
	protected $reponse_upload = [];
	protected $save_all = false;


	public function __construct($dossier_destination = null, $dossier_temporaire = null, $cookie_heures = null, $cookie_path = null, $verif_filesize_sup2Go = null)
	{
		error_reporting(E_ALL);
		ini_set('display_errors', 0);

		// maximise les possibilités de reprise d'upload en cas d'arrêt intempestif
		ignore_user_abort(true);
		
		// Interception des erreurs fatales
		register_shutdown_function([$this, 'Shutdown']);

		// Initialise les messages serveur contenus dans "Classes/Messages.php"
		new SetMessages('uploadAjaxServeur');
		
		// Paramètres par défaut provenant de "ParamsDefautServeur"
		$param = self::getParamUploadAjaxABCIServeur();
		
		$this->dossier_destination = $dossier_destination !== null ? trim($dossier_destination) : $param->dossier_destination;
		
		$this->dossier_temporaire = $dossier_temporaire !== null ? trim($dossier_temporaire) : $param->dossier_temporaire;
		
		$this->cookie_time = is_numeric($cookie_heures) && $cookie_heures > 0 ? time()+3600*$cookie_heures : time()+3600*$param->cookie_heures;
		
		$this->cookie_path = $cookie_path !== null ? trim($cookie_path) : $param->cookie_path;
		
		$this->verif_filesize_sup2Go = $verif_filesize_sup2Go !== null ? trim($verif_filesize_sup2Go) != false : $param->verif_filesize_sup2Go;
		
		$this->GetPostFile();
	}


	private function GetPostFile()
	{
		$UpAbci_form = isset($_POST['UpAbci_form']) ? $_POST['UpAbci_form'] : null;
		if(!empty($UpAbci_form)) 
		{
			parse_str($UpAbci_form, $this->UpAbci_form);	
			$this->UpAbci_form['join_file'] = isset($_POST['UpAbci_joinFile']) ? $_POST['UpAbci_joinFile'] : null;
		}

		$UpAbci_formEnd = isset($_POST['UpAbci_formEnd']) && is_array($_POST['UpAbci_formEnd']) ? $_POST['UpAbci_formEnd'] : [] ;
		if(count($UpAbci_formEnd) > 0)
		{
			$output = [];
			foreach($UpAbci_formEnd as $key => $value)
			{
				parse_str($value, $output);

				$output['join_file'] = isset($_POST['UpAbci_joinFile'][$key]) ? $_POST['UpAbci_joinFile'][$key] : null;
				
				$this->UpAbci_formEnd[] = $output;
			}
		}
		
		
		$this->UpAbci_fragment = isset($_FILES['UpAbci_fragment']) ? $_FILES['UpAbci_fragment'] : null;
		$this->UpAbci_fragment = !isset($this->UpAbci_fragment) && filter_input(INPUT_POST, 'UpAbci_fragment') ? 1 : $this->UpAbci_fragment;// si post UpAbci_fragment existe c'est que le script javascript à trouvé une sauvegarde complète et a remplacé file UpAbci_fragment par post UpAbci_fragment.
		
		if(isset($this->UpAbci_fragment)) 
		{
			$this->cleanFname = $this->cleanFileName($this->getParam("name"));				
			
			$this->UpAbci_blobSlice = urldecode(filter_input(INPUT_POST, 'UpAbci_blobSlice'));
			$this->UpAbci_blobSlice = $this->UpAbci_blobSlice == 1;
			
			$this->UpAbci_fileEnd = urldecode(filter_input(INPUT_POST, 'UpAbci_fileEnd'));
			$this->UpAbci_fileEnd = $this->UpAbci_fileEnd == 1;
			
			$this->cookie_name = $this->getParam('cook_name');
			
			$cook_save = isset($_COOKIE[$this->cookie_name]) ? urldecode($_COOKIE[$this->cookie_name]) : null;
			$cook_save = explode('|',$cook_save);
			$cook_temp_adresse = !empty($cook_save[0]) && ctype_alnum($cook_save[0]) ? $cook_save[0] : null;
			
			$this->cookie_filesize = isset($cook_save[1]) ? intval($cook_save[1]) : 0; 
			
			
			$this->file_temp_address = isset($cook_temp_adresse) ? $this->dossier_temporaire.$cook_temp_adresse : $this->dossier_temporaire.hash("sha256",(uniqid($this->getParam("uniqid_form"),true).$this->getUniqid()));	

			$this->file_destination = $this->dossier_destination.$this->cleanFname;	
		}
	}
		
	
	
	public function Upload()
	{			
		if(isset($this->UpAbci_fragment)) 
		{
			// Permet de récupérer le fichier temporaire s'il existe, s'il est complet et s'il n'est pas corrompu. Peut-être utile au cas où une erreur php se serait produite lors d'un traitement après l'upload complet (crop etc.). Evite d'attendre à nouveau pour le téléchargement lors des essais ultérieurs.
			if ($this->cookie_filesize == $this->getParam("size") && $this->UpAbci_fileEnd && $this->UpAbci_fragment === 1)
			{
				$size_upload = @filesize($this->file_temp_address);
				
				if($size_upload == $this->getParam("size"))
				{
					$this->fichier_verif = true;
					return true;
				}
			}
			
			// Si $this->UpAbci_fragment === 1 => pas de fichier joint mais uniquement ses coordonnées pour récupérer la sauvegarde. Si l'on a passé la condition précédente c'est que la sauvegarde est non valide et l'on sort.
			if ($this->UpAbci_fragment === 1) 
			{
				$this->reponse_upload['upabci_erreur'] = SetMessages::setMess('UpAbServerFichierTemp');
				setcookie($this->cookie_name,"",time()-3600,$this->cookie_path);
				@unlink($this->file_temp_address);
				return false;
			}

			// Vérifs	
			// Le dossier temporaire doit être défini en cas de fragmentation du fichier ou si la méthode save_all est utilisée.
			if(($this->UpAbci_blobSlice || $this->save_all) && ($this->dossier_temporaire == '' || mb_substr($this->dossier_temporaire,-1,1,"UTF-8") != '/'))
			{
				$this->reponse_upload['upabci_erreur'] = SetMessages::setMess('UpAbServerDossierTemp');
				return false;
			}
			
			if($this->getParam("name") == '')
			{
				$this->reponse_upload['upabci_erreur'] = SetMessages::setMess('UpAbServerNomInvalide');
				return false;
			}
				
			if($this->getParam("size") == '')
			{
				$this->reponse_upload['upabci_erreur'] = SetMessages::setMess('UpAbServerTailleInvalide');
				return false;
			}
			
			if(!is_uploaded_file($this->UpAbci_fragment['tmp_name']))
			{
				$this->reponse_upload['upabci_erreur'] = SetMessages::setMess('UpAbServerSourceInvalide');
				return false;					
			}
			
			// uploads
			if(!$this->UpAbci_blobSlice && !$this->save_all) // si le fichier est d'un seul morceau et que saveAll() n'a pas été configuré
			{
				$this->file_temp_address = $this->UpAbci_fragment['tmp_name'];
			}
			else
			{
				 // On ouvre ou on crée le fichier
				$fichier_cible = @fopen($this->file_temp_address, 'a+');
				if($fichier_cible === false)
				{
					$this->reponse_upload['upabci_erreur'] = SetMessages::setMess('UpAbServerOuvertureTemp');
					return false;
				}
							
				// On ouvre le contenu téléchargé
				$upload_file = @fopen($this->UpAbci_fragment['tmp_name'], 'rb');
				if($upload_file === false)
				{
					$this->reponse_upload['upabci_erreur'] = SetMessages::setMess('UpAbServerOuvertureContent');
					return false;
				}
				
				// On lit son contenu dans une variable
				$upload_size = $this->UpAbci_fragment['size'];
				$upload_content = @fread($upload_file, $upload_size);
				if($upload_content === false)
				{
					$this->reponse_upload['upabci_erreur'] = SetMessages::setMess('UpAbServerLectureContent');
					setcookie($this->cookie_name,"",time()-3600,$this->cookie_path);
					@unlink($this->file_temp_address);
					return false;
				}	
				
				fclose($upload_file);
				
				// On l'écrit dans le fichier temporaire
				if(@fwrite($fichier_cible, $upload_content) === false)
				{
					$this->reponse_upload['upabci_erreur'] = SetMessages::setMess('UpAbServerEcritureContent');
					return false;
				}	
				
				unset($upload_content);
				
				fclose($fichier_cible);
				
				$new_file_size = $this->cookie_filesize + $upload_size;
						
				setcookie($this->cookie_name,pathinfo($this->file_temp_address,PATHINFO_FILENAME).'|'.$new_file_size,$this->cookie_time,$this->cookie_path);
			
				if (!$this->UpAbci_fileEnd)
				{
					if( !((isset($this->reponse_upload['upabci_resultat']) && $this->reponse_upload['upabci_resultat'] == 'add_status_erreur') || isset($this->reponse_upload['upabci_stop_form'])))
					{
						$this->reponse_upload['upabci_resultat'] = 'continu'; // ne pas modifier
						return true;
					}
				}
			}
			
			if ($this->UpAbci_fileEnd)
			{
				$this->fichier_verif = true;

				// vérification de l'intégrité du fichier (automatique pour les fichiers de moins de 2 Go)
				if ($this->verif_filesize_sup2Go || $this->getParam("size") < $this->returnOctets('2 Go'))
				{
					$size_upload = @filesize($this->file_temp_address);
					
					if($size_upload != $this->getParam("size"))
					{
						$this->fichier_verif = false;
						
						if($this->UpAbci_blobSlice || $this->save_all)
						{
							setcookie($this->cookie_name,"",time()-3600,$this->cookie_path);
							
							$this->reponse_upload['upabci_erreur'] = SetMessages::setMess('UpAbServerVerifSauv');
							return false;
						}
						else
						{
							$this->reponse_upload['upabci_erreur'] = SetMessages::setMess('UpAbServerVerifFile');
							return false;
						}
					}
				}
			}
			
			return true;
		}
		
		return false;
	}
	
	
	
	public function Transfert($file_destination = null)
	{		
		$this->file_destination = trim($file_destination) != '' ? $file_destination : $this->file_destination;
				
		if($this->UpAbci_fileEnd)
		{
			if(isset($_COOKIE[$this->cookie_name]) || $this->save_all)
			{
				setcookie($this->cookie_name,"",time()-3600,$this->cookie_path);
			}		
			
			if(trim($this->file_destination) == '')
			{
				$this->reponse_upload['upabci_erreur'] = SetMessages::setMess('UpAbServerDestFile'); 
				return false;
			}
			
			if(!is_file($this->file_temp_address) || !$this->fichier_verif)
			{
				$this->reponse_upload['upabci_erreur'] = SetMessages::setMess('UpAbServerFichierTemp');
				return false;
			}
			
			if(($this->file_temp_address == $this->UpAbci_fragment['tmp_name'] && @move_uploaded_file ($this->UpAbci_fragment['tmp_name'], $this->file_destination)) || ($this->file_temp_address != $this->UpAbci_fragment['tmp_name'] && @rename($this->file_temp_address,$this->file_destination)))
			{
				$this->reponse_upload['upabci_resultat'] = 'upload_ok'; // ne pas modifier
				return true;
			}
			else
			{
				$this->reponse_upload['upabci_resultat'] = false; // ne pas modifier
				$this->reponse_upload['upabci_erreur'] = SetMessages::setMess('UpAbServerFichierTransfert');
				return false;
			}
		}
		
		return false;
	}
	
	
	
	public function Shutdown()
	{
		$fatal_error = false;
		if ($error = error_get_last())
		{
			switch($error['type'])
			{
				case E_ERROR:
				case E_CORE_ERROR:
				case E_COMPILE_ERROR:
				case E_USER_ERROR:
				$fatal_error = true;
				break;
			}
		}
	
		if ($fatal_error)
		{
			$message = null;
			foreach($this->config_erreur_serveur as $key => $value)
			{
				if (strpos($error['message'],$key) !== false)
				{
					if(is_array($value))
					{
						$message = isset($value[0])? $value[0] : '';
						if(isset($value[1]) && trim($value[1]) != false)
						{
							setcookie($this->cookie_name,"",time()-3600,$this->cookie_path);
							@unlink($this->file_temp_address);
						}
					}
					else
					{
						$message = $value;
					}
				}
			}

			if(!isset($message) && $this->mode_debug)
			{
				$message = $error['message'];
			}
			
			exit($message);
		}
	}
}
?>