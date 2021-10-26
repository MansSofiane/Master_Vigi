<?php
session_start();
require_once("../../../../data/conn4.php");
//on insere l'assure
if ( isset($_REQUEST['nom']) && isset($_REQUEST['pnom']) && isset($_REQUEST['part']) && isset($_REQUEST['sous']) ){
	
	$nom =utf8_decode( addslashes($_REQUEST['nom']));
	$prenom =utf8_decode(  addslashes($_REQUEST['pnom']));
	$part = $_REQUEST['part'];
	$sous = $_REQUEST['sous'];
$rqtib=$bdd->prepare("INSERT INTO `beneficiaire2` VALUES ('', '$nom', '$prenom', '$part','$sous')");
$rqtib->execute();

}

?>