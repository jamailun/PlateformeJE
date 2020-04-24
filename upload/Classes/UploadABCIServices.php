<?php
class UploadABCIServices 
{
	private $version = '6.5';
	

	public function getUniqid()
	{ 
		return hash("sha256",openssl_random_pseudo_bytes("128", $cstrong));
	}


	public function returnOctets($val)
	{ // Retourne des octets depuis une chaine formatée comme 25ko ou 100 Mo ou 1 g
		$val = str_replace([',',' '],['.',''],$val);
		$val = rtrim($val, "oO");
	
		$last = strtolower(substr($val,-1));
		
		$val = floatval($val);
		
		switch($last)
		{
			case 't':  $val *= 1024;
			case 'g':  $val *= 1024;
			case 'm': $val *= 1024;
			case 'k':  $val *= 1024;
		}
		return $val;
	}

	public function verifExtensions($fichier,$extensions)
	{
		$filesExtensions = is_array($extensions) ? array_map('strtolower',$extensions) : [];
		$extension_fichier = strtolower(pathinfo($fichier, PATHINFO_EXTENSION));
		// Si le tableau des extensions autorisées est vide on accepte toutes les extensions					 
		return (count($filesExtensions) == 0 || in_array($extension_fichier,$filesExtensions));
	}


	/* A utiliser AVANT la fonction "Transfert()" (si utilisée avec le script serveur ajax) et de préférence après avoir vérifié que le fichier est complet afin de minimiser l'utilisation de cette fonction qui peut être assez gourmande en ressource (en fonction du nombre de fichiers du répertoire) si le deuxième paramètre est renseigné pour renommer les fichiers en mode incrémentiel.
	
	 Renomme le fichier téléchargé si un fichier de même nom existe déjà sur le serveur. 
	Par défaut la fonction ajoute un identifiant unique (uniqid) au nom des fichiers.
	- Avec un second argument optionnel quelconque (ex : $up->renameIdenticName($destination_fichier,'incr');) le nom des fichiers est incrémenté.  
	- Un troisième argument optionnel casse sensivitive est également disponible, mais à n'utiliser que sur les serveurs casse sensitive (NE PAS UTILISER AVEC LES SERVEURS WINDOWS).
	- Ne touchez pas aux paramètres 4 et 5.
	*/
	public function renameIdenticName($adresse_fichier, $incr = false, $unix = false, $stop = 0, $isfile = false)
	{
		if ($isfile || is_file($adresse_fichier))
		{
			$info = pathinfo($adresse_fichier);
			$extension = isset($info['extension']) && $info['extension'] != '' ? '.'.$info['extension'] : null;
			$dossier = $info['dirname'];
			$filename = $info['filename'];
			
			if (trim($incr) != false && $stop < 90)// le stop arbitrtaire est une mesure de sécurité au cas où...
			{
				$file = addcslashes($filename,'.');			
				$ext = isset($extension) ? addcslashes($extension,'.') : null;									
	
				$match = trim($unix) != false ? '#^'.$file.'-[0-9]+'.$ext.'$#' : '#^'.$file.'-[0-9]+'.$ext.'$#i';
				
				$tab_identique = [];
				
				$files = new RegexIterator(new DirectoryIterator($dossier),$match);
				foreach ($files as $fileinfo) $tab_identique[] = $fileinfo->getFilename();
	
				natsort($tab_identique);
				
				$dernier = array_pop($tab_identique);
				
				unset($tab_identique);
							
				$dernier = isset($dernier)? pathinfo($dernier, PATHINFO_FILENAME) : '';
				
				$file = preg_replace_callback('#([0-9]+$)#', create_function('$matches','return $matches[1]+1;'), $dernier, '1', $count);
	
				$filename = !empty($count)? $file : $filename.'-1';
			}
			else
			{
				$filename .= '-'.uniqid();
			}
																														
			$filename = isset($extension) ? $filename.$extension : $filename;												
																					 
			$adresse = $dossier.'/'.$filename;
			
			if (!is_file($adresse)) return $adresse;
			else																													
			return Rename_fich($adresse_fichier, $incr, $unix, ++$stop, true);                        
		}																				 
		else 
		{
			return $adresse_fichier;
		}
	}
	
	
	// La fonction "cleanFileName" est utilisée par défaut dans la fonction "GetPostFile" elle-même appelée par le constructeur de la classe
	public function cleanFileName($nom_fichier)
	{
		$info = pathinfo($nom_fichier);
		$extension = isset($info['extension']) && $info['extension'] != '' ? '.'.$info['extension'] : null;
		$dossier = $info['dirname'] != '.' ? $info['dirname'].'/' : null  ;
		$filename = $info['filename'];
		
		$cible = [
		'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ă', 'Ą',
		'Ç', 'Ć', 'Č', 'Œ',
		'Ď', 'Đ',
		'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ă', 'ą',
		'ç', 'ć', 'č', 'œ',
		'ď', 'đ',
		'È', 'É', 'Ê', 'Ë', 'Ę', 'Ě',
		'Ğ',
		'Ì', 'Í', 'Î', 'Ï', 'İ',
		'Ĺ', 'Ľ', 'Ł',
		'è', 'é', 'ê', 'ë', 'ę', 'ě',
		'ğ',
		'ì', 'í', 'î', 'ï', 'ı',
		'ĺ', 'ľ', 'ł',
		'Ñ', 'Ń', 'Ň',
		'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ő',
		'Ŕ', 'Ř',
		'Ś', 'Ş', 'Š',
		'ñ', 'ń', 'ň',
		'ò', 'ó', 'ô', 'ö', 'ø', 'ő',
		'ŕ', 'ř',
		'ś', 'ş', 'š',
		'Ţ', 'Ť',
		'Ù', 'Ú', 'Û', 'Ų', 'Ü', 'Ů', 'Ű',
		'Ý', 'ß',
		'Ź', 'Ż', 'Ž',
		'ţ', 'ť',
		'ù', 'ú', 'û', 'ų', 'ü', 'ů', 'ű',
		'ý', 'ÿ',
		'ź', 'ż', 'ž',
		'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р',
		'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'р',
		'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я',
		'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я'
		];
					 
		$rempl = [
		'A', 'A', 'A', 'A', 'A', 'A', 'AE', 'A', 'A',
		'C', 'C', 'C', 'CE',
		'D', 'D',
		'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'a', 'a',
		'c', 'c', 'c', 'ce',
		'd', 'd',
		'E', 'E', 'E', 'E', 'E', 'E',
		'G',
		'I', 'I', 'I', 'I', 'I',
		'L', 'L', 'L',
		'e', 'e', 'e', 'e', 'e', 'e',
		'g',
		'i', 'i', 'i', 'i', 'i',
		'l', 'l', 'l',
		'N', 'N', 'N',
		'O', 'O', 'O', 'O', 'O', 'O', 'O',
		'R', 'R',
		'S', 'S', 'S',
		'n', 'n', 'n',
		'o', 'o', 'o', 'o', 'o', 'o',
		'r', 'r',
		's', 's', 's',
		'T', 'T',
		'U', 'U', 'U', 'U', 'U', 'U', 'U',
		'Y', 'Y',
		'Z', 'Z', 'Z',
		't', 't',
		'u', 'u', 'u', 'u', 'u', 'u', 'u',
		'y', 'y',
		'z', 'z', 'z',
		'A', 'B', 'B', 'r', 'A', 'E', 'E', 'X', '3', 'N', 'N', 'K', 'N', 'M', 'H', 'O', 'N', 'P',
		'a', 'b', 'b', 'r', 'a', 'e', 'e', 'x', '3', 'n', 'n', 'k', 'n', 'm', 'h', 'o', 'p',
		'C', 'T', 'Y', 'O', 'X', 'U', 'u', 'W', 'W', 'b', 'b', 'b', 'E', 'O', 'R',
		'c', 't', 'y', 'o', 'x', 'u', 'u', 'w', 'w', 'b', 'b', 'b', 'e', 'o', 'r'
		];
			 
		$nom_fichier = str_replace($cible, $rempl, $filename);// préserve le maximum de caractères utiles

		$nom_fichier = preg_replace('#[^.a-z0-9_-]+#i', '-', $nom_fichier);// uniquement alphanumérique et . et _ et -
		$nom_fichier = preg_replace('#-{2,}#','-',$nom_fichier);// supprime les occurences successives de '-'
		
		// Supprime le dernier "-" de remplacement excepté si ce caractère existait déjà à la fin du nom original
		$nom_fichier = mb_substr($filename, -1) != "-" ? rtrim($nom_fichier,'-') : $nom_fichier;
		
		return $dossier."file".$extension;
	}
}
?>