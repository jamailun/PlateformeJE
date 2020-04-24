<?php
/*
require "Classes/ParamsDefautServeur.php"
require 'Classes/UploadAjaxABCIServeur.php';
require 'Classes/UploadABCIServices.php'
require 'Classes/SetMessages.php'
require 'Classes/Messages.php'

(Gardez bien à l'esprit que ce script sera appelé plusieurs fois pour le traitement du même post, excepté en cas d'upload simple si la taille du fichier est inférieure à la taille d'un fragment ou si aucun fichier n'est joint)
*/

// Vous devez vérifier l'existence du dossier ou changer cette adresse 
/*------------------------------------------------------*/
$dossier_destination = '../upload/files/';
/*------------------------------------------------------*/

// Voir le fichier "UploadAjaxABCI_Php_Load.php" pour plus d'exemples détaillés.

// Pour récupérer la variable de session "$_SESSION['UploadAjaxABCI'][$uniqid_form]" qui fait office de token
session_start();

header('Content-type: text/html; charset=UTF-8');// Inutile depuis php 5.6

// Charge les classes  php avec spl_autoload_register
spl_autoload_register(function ($class) {require 'Classes/' . $class . '.php';});

// Initialisation de la classe php (dossier de destination)
$up = new UploadAjaxABCIServeur($dossier_destination);

// Décommenter la ligne ci-dessous en phase de développement pour faire afficher les erreurs php dans le formulaire.
/*
$up->setModeDebug ();
*/

// Pour gérer le retour des erreurs fatales, utiliser la fonction "cathErrorServeur()". Cf le mode d'emploi et le fichier "UploadAjaxABCI_Php_Load_Redimensions.php" pour exemple.

// getParam("uniqid_form") renvoie l'identifiant de formulaire pour la vérification ci-dessous
$uniqid_form = $up->getParam("uniqid_form");

// Il est conseillé de ne pas supprimer cette ligne car c'est le token qui assure que ce script est appelé depuis le formulaire. Permet également de renvoyer un message en cas de timeout du serveur, connexion perdue ou non valide.
if(!(isset($uniqid_form,$_SESSION['UploadAjaxABCI'][$uniqid_form]['token'])))
{
	$up->exitStatusErreur(SetMessages::setMess('UpAbVerifToken')); 
}


$up->Upload();
// Le processus d'upload est dissocié de celui du transfert, pour permettre de traiter le fichier complet (par exemple pour faire des redimensionnements d'images en php etc.) avant de le déplacer vers son emplacement définitif (c.f "UploadAjaxABCI_Php_Load.php" pour des exemples). 
$up->Transfert();

// INDISPENSABLE en fin de script, "exitReponseAjax()" retourne les informations nécessaires pour la requête ajax.
$up->exitReponseAjax();
?>