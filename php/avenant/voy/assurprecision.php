<?php
session_start();
require_once("../../../../../data/conn4.php");
//on recupere le code du pays
if ($_SESSION['login']){
}
else {
    header("Location:login.php");
}
$id_user = $_SESSION['id_user'];
$datesys=date("Y-m-d");
if ( isset($_REQUEST['code']))
{
    $code = $_REQUEST['code'];

    $rqtuser=$bdd-prepare("select s.id_user from policew as p,souscripteurw as s where p.cod_sous=s.cod_sous and p.cod_pol='$code'");
    $rqtuser->execute();
    $id_userag=$id_user;
    while ($rw=$rqtuser->fetch())
    {
        $id_userag=$rw['id_user'];
    }

    try
    {


    // $rqt=$bdd->prepare("DELETE FROM `assure` WHERE `cod_pol`='$code' AND `cod_av`='0' AND `id_user`='$id_user'");
        $rqt=$bdd->prepare("DELETE FROM `modif` WHERE `cod_pol`='$code'  AND `id_user`='$id_userag'");
    $rqt->execute();
    } catch (Exception $ex) {
        echo 'Erreur : ' . $ex->getMessage() . '<br />';
        echo 'N° : ' . $ex->getCode();
    }

}