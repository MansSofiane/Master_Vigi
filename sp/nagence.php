<?php session_start();
require_once("../../../data/conn4.php");
if ($_SESSION['login']){
}
else {
    header("Location:login.php");
}
$id_user = $_SESSION['id_user'];
$datesys=date("Y-m-d");




if (isset($_REQUEST['nomag']) && isset($_REQUEST['nomrep']) && isset($_REQUEST['prenomrep']) && isset($_REQUEST['adr'])&& isset($_REQUEST['mail'])&& isset($_REQUEST['tel'])
    ) {
    $datesys=date("Y.m.d");
    $nomag = ($_REQUEST['nomag']);
    $nomrep = ($_REQUEST['nomrep']);
    $prenomrep = ($_REQUEST['prenomrep']);
    $adr = ($_REQUEST['adr']);
    $tel = ($_REQUEST['tel']);
    $mail = ($_REQUEST['mail']);
    $typ = ($_REQUEST['typ']);

    $rqt = $bdd->prepare(" INSERT INTO `agence` (`cod_agence`, `lib_agence`, `nom_rep`, `prenom_rep`, `mail`, `adr_agence`, `tel_agence`, `date`,`typ_agence`, `id_user`)  VALUES ('','$nomag','$nomrep','$prenomrep','$mail','$adr','$tel','$datesys','$typ','$id_user')");
    $rqt->execute();
}

?>