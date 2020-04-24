<?php
class UploadFormABCIServeur extends UploadABCIServices
{
	private $version = '6.5';

	private $files = [];
	private $result;
	private $input_file;
	private $id_form;
	private $defautFatalError = true;
	protected $config_erreur_serveur = [];
	protected $mode_debug = false;


	
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


	public function getFiles()
	{
		return $this->files;
	}


	public function deleteFiles()
	{
		$this->files = (object)[];
	}


	public function getResult()
	{
		$res = $this->result;
		if(!isset($res->Files)) $res->Files = (object)[];
		if(!isset($res->Error_server)) $res->Error_server = null;
		if(!isset($res->Infos_server)) $res->Infos_server = null;
		
		$this->result = null;		
		return $res;
	}
			
			
	// Redéfini la fonction getParam
	public function getParam($index)
	{
		switch($index)
		{
			case 'uniqid_form' 	: return filter_input(INPUT_POST, 'UpAbci_uniqidForm') ? filter_input(INPUT_POST, 'UpAbci_uniqidForm') : filter_input(INPUT_GET, 'UpAbci_uniqidForm');
								 	break;
									
			case 'id_form' 		: return $this->id_form;
								 	break;
									
			case 'input_name' 	: return $this->input_file;
								 	break;
			default : return false;
		}	
	}
	
	
	public function getPost($index)
	{
		return filter_input(INPUT_POST, $index);
	}


	public function addVerifTokenFalse($deleteFile = true)
	{
		// On utilise le message "UpAbVerifToken" du tableau "communs" pour similitude avec les navigateurs non obsolètes
		$this->result->Error_server = SetMessages::setMess('UpAbVerifToken');
		if(trim($deleteFile) != false) $this->files = (object)[];
	}


	public function addErrorServer($message)
	{
		$this->result->Error_server = $message;
	}
	
	
	public function addInfosServer($message)
	{
		$this->result->Infos_server = $message;
	}


	public function reloadPage()
	{
		$redirection = $_SERVER['REQUEST_URI'];
		// Si l'ancre n'est pas utilisée on s'en sert pour rediriger vers l'identifiant du formulaire
		$redirection = strrpos($redirection,'#') !== false ? $redirection : $redirection.'#'.ltrim($this->id_form,"#");
		header("Location:".$redirection);
		exit;  
	}
	
	
	public function __construct($input_file,$id_form=null,$dossier_destination=null,$sort_by_name=true, $defautFatalError=true)
	{
		error_reporting(E_ALL);
		ini_set('display_errors', 0);

		// Interception des erreurs fatales
		register_shutdown_function([$this, 'Shutdown']);

		if (!session_id()) session_start();
		
		// Initialise les tableaux de messages serveur "uploadAjaxServeur" et "uploadFormServeur" définis dans "Php_Upload/Classes/Messages.php"
		new SetMessages('uploadAjaxServeur','uploadFormServeur');
		
		$id_ses = md5($input_file.$id_form.$dossier_destination);
		
		$_SESSION['UploadAjaxABCI']['UploadFormABCIServeur-'.$id_ses] = isset($_SESSION['UploadAjaxABCI']['UploadFormABCIServeur-'.$id_ses]) ? $_SESSION['UploadAjaxABCI']['UploadFormABCIServeur-'.$id_ses] : (object)[];
		
		$this->result =& $_SESSION['UploadAjaxABCI']['UploadFormABCIServeur-'.$id_ses];
			
		$this->input_file = trim($input_file);
		$this->id_form = trim($id_form);
		$this->defautFatalError = trim($defautFatalError) != false;
	
		
		// Gestion du dépassement post_max_size
		$this->verifPostMaxSize();
		
		$dossier_destination = trim($dossier_destination);		
		$sort_by_name = trim($sort_by_name) != false;
		
		$this->GetPostFile($dossier_destination,$sort_by_name);		
	}
	
	
	private function GetPostFile($dossier_destination,$sort_by_name)
	{
		if (isset($_FILES[$this->input_file]))
		{
			$files_temp = [];
			
			$this->VerifMaxFileUploads();
			
			$localfiles = $_FILES[$this->input_file]['name'];
			
			if (is_array($localfiles))
			foreach ($localfiles as $index => $nom_fichier)
			{	
				$this->files[$index]['name'] = !empty($_FILES[$this->input_file]['name'][$index]) ? $_FILES[$this->input_file]['name'][$index] : null;
				$this->files[$index]['tmp_name'] = !empty($_FILES[$this->input_file]['tmp_name'][$index]) ? $_FILES[$this->input_file]['tmp_name'][$index] : null;
				$this->files[$index]['size'] = !empty($_FILES[$this->input_file]['size'][$index]) ? $_FILES[$this->input_file]['size'][$index] : null;
				$this->files[$index]['type'] = !empty($_FILES[$this->input_file]['type'][$index]) ? $_FILES[$this->input_file]['type'][$index] : null;

				$this->errorFile($index,$_FILES[$this->input_file]['error'][$index]);
				
				$this->files[$index]['ok'] = null;
				
				$errornull = !isset($this->files[$index]['error']);
				$this->files[$index]['clean_name'] = $errornull ? $this->cleanFileName($this->files[$index]['name']) : null;
				$this->files[$index]['destination'] = $errornull ? $dossier_destination.$this->files[$index]['clean_name'] : null;				
				
				
				if(!$sort_by_name) $files_temp[$index] = $this->files[$index];
				
				$this->files[$index] = (object) $this->files[$index];
			}           
			else                                      
			{				
				$this->files[0]['name'] = !empty($_FILES[$this->input_file]['name']) ? $_FILES[$this->input_file]['name'] : null;
				$this->files[0]['tmp_name'] = !empty($_FILES[$this->input_file]['tmp_name']) ? $_FILES[$this->input_file]['tmp_name'] : null;
				$this->files[0]['size'] = !empty($_FILES[$this->input_file]['size']) ? $_FILES[$this->input_file]['size'] : null;
				$this->files[0]['type'] = !empty($_FILES[$this->input_file]['type']) ? $_FILES[$this->input_file]['type'] : null;
				$this->files[0]['ok'] = null;
				
				$this->errorFile(0,$_FILES[$this->input_file]['error']);
				
				$errornull = !isset($this->files[0]['error']);
				$this->files[0]['clean_name'] = $errornull ? $this->cleanFileName($this->files[0]['name']) : null;
				$this->files[0]['destination'] = $errornull ? $dossier_destination.$this->files[0]['clean_name'] : null;
				
				if(!$sort_by_name) $files_temp[0] =  $this->files[0];
				
				$this->files[0] = (object) $this->files[0];	
			}
			
			$this->files = (object) $this->files;
			$this->result->Files =& $this->files;
			
			if(!$sort_by_name) $this->sortFilesBySize($files_temp);
		}
	}


	private function errorFile($index,$erreur)
	{
		switch ($erreur)
		{
			// Attention de ne pas renseigner les erreurs ci-dessous avec des messages null car le résultat est testé pour faire la différence avec un transfert réussi (erreur = null)
			case "1" : 
			case "2" : 	$this->files[$index]['error'] = SetMessages::setMess('UpAbMaxSizeFichierPhpForm');
						break;
						
			case "3" :  $this->files[$index]['error'] = SetMessages::setMess('UpAbErrPartialPhpForm');
						break;
						
			case "4" :  $this->files[$index]['error'] = SetMessages::setMess('UpAbChampVidePhpForm');
						break;
						
			case "6" :  $this->files[$index]['error'] = SetMessages::setMess('UpAbErrNoTmpDirPhpForm');
						break;
						
			case "7" :  $this->files[$index]['error'] = SetMessages::setMess('UpAbErrCantWritePhpForm');
						break;
			
			case "8" :  $this->files[$index]['error'] = SetMessages::setMess('UpAbErrExtPhpForm');
						break;
						
			default :  $this->files[$index]['error'] = null;			
		}
	}


	private function verifPostMaxSize()
	{
		$derniere_erreur = error_get_last();
		if(isset($derniere_erreur) && $derniere_erreur['type'] == 2 && strpos($derniere_erreur['message'],'POST Content-Length') !== false)
		{
			$this->result->Error_server = SetMessages::setMess('UpAbPostMaxSizePhpForm');
			$this->reloadPage();                      
		}
	}	
	
	
	private function VerifMaxFileUploads()
	{
		$derniere_erreur = error_get_last();
		if(isset($derniere_erreur) && $derniere_erreur['type'] == 2 && strpos($derniere_erreur['message'],'Maximum number of allowable file uploads has been exceeded') !== false)
		{
			$this->result->Error_server = SetMessages::setMess('UpAbMaxFileUploadsPhpForm');
		}
	}


	private function sortFilesBySize(&$files_temp)
	{
		// On trie également sur l'erreur éventuelle, plus pratique visuellement en retour d'information.
		$erreur_temp = [];	
		$taille_temp = [];
		$nom_temp = [];		
		foreach ($files_temp as $key => $value)
		{
			$erreur_temp[$key] = $value['error'];
			$taille_temp[$key] = $value['size'];
			$nom_temp[$key] = $value['name'];
		}	
		array_multisort($erreur_temp, SORT_NATURAL|SORT_FLAG_CASE, $taille_temp, SORT_NUMERIC, $nom_temp, SORT_NATURAL|SORT_FLAG_CASE, $files_temp);
		
		$this->files = [];
		foreach ($files_temp as $key => $value) $this->files[$key] = (object) $files_temp[$key];
		
		$this->files = (object) $this->files;
		unset($files_temp,$erreur_temp,$taille_temp,$nom_temp);
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
					}
					else
					{
						$message = $value;
					}
				}
			}
			
			if(!isset($message))
			{
				if($this->mode_debug)
				{
					$message = $error['message'];
				}
				else if($this->defautFatalError)
				{
					$message = SetMessages::setMess('UpAbdefautFatalErrorPhpForm');
				}
			}
			
			if(count($this->files) > 0)
			{
				$file_error = false;
				foreach($this->files as $key => $file)
				{
					if($file_error)
					{
						$file->error = SetMessages::setMess('UpAbFatalErrorSuitePhpForm');
					}
					else if(empty($file->ok) && empty($file->error)) // condition pour n'envoyer le message que dans les fichiers non déjà renseignés.
					{
						$file_error = true;
						$file->error = $message;
					}
				}
			}
			else
			{
				$this->result->Error_server = $message;
			}
			
			$this->reloadPage();
		}
	}	
}
?>