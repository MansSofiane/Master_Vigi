<?php
session_start();
require_once("../../../../data/conn4.php");
$rqtc=$bdd->prepare("UPDATE `pays` SET `sel_pays`='1' WHERE 1");
$rqtc->execute();



?>