<?php
session_start();
require_once("../../../../data/conn4.php");
//on recupere le code du pays
if ( isset($_REQUEST['code'])){
	$code = $_REQUEST['code'];
$rqtc=$bdd->prepare("DELETE FROM `dtimbre` WHERE `cod_dt`='$code'");
$rqtc->execute();
}

?>