<?php
session_start();
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../app/vendor/autoload.php';

$mail = new PHPMailer(true); // create a new object
$mail->isSMTP(); // enable SMTP
$mail->setLanguage('fr');
$mail->CharSet = 'UTF-8';
$mail->Host = gethostbyname('smtp.gmail.com');        // Specify main and backup SMTP 
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'je.ouest.insa@gmail.com';          // SMTP username
$mail->Password = '12sewif12';                        // SMTP password
$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, ssl also 
$mail->Port = 25;
$mail->isHTML(true);
$mail->setFrom("je.ouest.insa@gmail.com");
$mail->AddAddress("contact@ouest-insa.fr");
$mail->AddAddress("prospection@ouest-insa.fr");
$mail->Subject = "Ouest INSA nouveau contact";
$mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => false
    )
);
//$mail->SMTPDebug = 3;


$name = $_POST['name'];
$societe = $_POST['society'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$projet = $_POST['project'];
$budget =  $_POST['budget'];
$comment =  $_POST['comment'];

// Sauvegarder les champs pour les remettre en cas de problème à l'envoi. L'utilisateur ne perd pas ce qu'il nous a écris.
$_SESSION["name"] = $_POST['name'];
$_SESSION["society"] = $_POST['society'];
$_SESSION["phone"] = $_POST['phone'];
$_SESSION["email"] = $_POST['email'];
$_SESSION["project"] = $_POST['project'];
$_SESSION["budget"] =  $_POST['budget'];
$_SESSION["comment"] =  $_POST['comment'];


//récupérer nom fichier
chdir('upload');
chdir('files');
$avoir="file" ."*";

$file_name = NULL;
foreach (glob($avoir) as $file){
	$file_name = $file;
}


/*// Headers
$headers = 'From: OuestINSA.fr <contact@ouestinsa.fr>'."\r\n";
$headers .= 'Mime-Version: 1.0'."\r\n";
$headers .= 'Content-Type: multipart/mixed;boundary='.$boundary."\r\n";
$headers .= "\r\n";
 */
 
// Message
 
// Body

$msg = 'Nouveau contact: <br>';
$msg.='Nom: '.$name.'<br>';
$msg.='Numero: '.$phone.'<br>';
$msg.='Societe: '.$societe.'<br>';
$msg.='Email: '.$email.'<br>';
$msg.='Projet: '.$projet.'<br>';
$msg.='Budget: '. $budget.'<br>';
$msg.='Commentaire: '.$comment.'<br>';
// Prendre l'IP du client
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}
$msg.='<br><br>IP: '.$ip.'<br>';

/*
// Pièce jointe

if (file_exists($file_name))
{
	$file_type = filetype($file_name);
	$file_size = filesize($file_name);
 
	$handle = fopen($file_name, 'r') or die('File '.$file_name.'can t be open');
	$content = fread($handle, $file_size);
	$content = chunk_split(base64_encode($content));
	$f = fclose($handle);
 
	$msg .= '--'.$boundary."\r\n";
	$msg .= 'Content-type:'.$file_type.';name='.$file_name."\r\n";
	$msg .= 'Content-transfer-encoding:base64'."\r\n";
	$msg .= $content."\r\n";
}
 
// Fin
$msg .= '--'.$boundary."\r\n";
 
// Function mail()
mail($to, $subject, $msg, $headers);
unlink($file_name);
header('Location: success.php');    
*/  


/* Vérification que le champ nom soit bien passé, afin d'éviter les mails vides */
if (isset($_POST['name']) && isset($_POST['project'])) {
	$mail->Body = $msg;
	if($file_name != NULL){
		$mail->AddAttachment($file_name);
	}
	 if(!$mail->Send()) {
		if($file_name != NULL){
			unlink($file_name);
		}
		//echo "Mailer Error: " . $mail->ErrorInfo;
		$_SESSION["error"] = 'Une erreur lors de l\'envoi est survenue. Veuillez copier votre message et nous le transmettre directement par mail à <a href="mailto:contact@ouest-insa.fr">contact@ouest-insa.fr</a>. Nous nous excusons pour la gène occasionnée.';
		header('Location: devis.php');
	 } else {
		if($file_name != NULL){
			unlink($file_name);
		}
		// Suppression des variables pour qu'elles ne réapparaissent pas sur la demande de devis.
		unset($_SESSION["name"]);
		unset($_SESSION["society"]);
		unset($_SESSION["phone"]);
		unset($_SESSION["email"]);
		unset($_SESSION["project"]);
		unset($_SESSION["budget"]);
		unset($_SESSION["comment"]);

  		header('Location: confirmation.php');
	 }
} else {
	$_SESSION["error"] = "Formulaire incorrect";
	header('Location: devis.php');
	// Faire une redirection dans le futur
}
?>
