<?php
session_start();
require_once("../../../../data/conn4.php");
//on recupere le code du pays
if ($_SESSION['login']){
}
else {
    header("Location:login.php");
}
$id_user = $_SESSION['id_user'];
$datesys=date("Y-m-d");
// xhr.open("GET", "php/avenant/voy/nmodif.php?code=" + cod_police +"&nom="+nnom+"&pnom="+nprenom+"&adr="+nadr+"&mail="+nmail+"&mail="+ntel+"&pass="+npass+"&dpas="+ndatpass2, false);
// xhr.open("GET", "php/avenant/msous.php?sous="+sous+"&nom="+noms+"&pnom="+pnoms+"&adr="+adrs+"&tel="+tels+"&mail="+mails, false);

if ( isset($_REQUEST['code']))
{
    $code = $_REQUEST['code'];
    $nom = addslashes($_REQUEST['nom']);
    $pnom = addslashes($_REQUEST['pnom']);
    $adr = addslashes($_REQUEST['adr']);
    $mail = $_REQUEST['mail'];
    $tel = $_REQUEST['tel'];
    $cod_sous = $_REQUEST['cod_sous'];

    $rqt_sous=$bdd->prepare("select id_user from souscripteurw where cod_sous='$cod_sous'");
    $rqt_sous->execute();
    $user_sous=$id_user;
    while($rw=$rqt_sous->fetch())
    {
        $user_sous=$rw['id_user'];
    }



    try
    {
        $rqtch=$bdd->prepare("select * from assure where cod_pol='$code' and cod_sous='$cod_sous' and cod_av='0' and id_user='$user_sous'");
        $rqtch ->execute();
        $nb=$rqtch->rowCount();
        if($nb>0)
        {

            while($rw=$rqtch->fetch())
            {
                $cod_ligne = $rw['cod_assu'];
            }

            $rqtupd=$bdd->prepare("UPDATE `assure` SET `nom_assu`='$nom',`pnom_assu`='$pnom',`mail_assu`=' $mail',`tel_assu`=' $tel',`adr_assu`='$adr'  WHERE `cod_assu`='$cod_ligne' ");
            $rqtupd->execute();
        }
        else
        {

            $rqt=$bdd->prepare("INSERT INTO `assure`( `nom_assu`, `pnom_assu`, `mail_assu`, `tel_assu`, `adr_assu`, `cod_sous`, `cod_pol`, `cod_av`, `id_user`) VALUES ('$nom','$pnom','$mail','$tel','$adr','$cod_sous','$code','','$user_sous')");
            $rqt->execute();
        }


    } catch (Exception $ex) {
        echo 'Erreur : ' . $ex->getMessage() . '<br />';
        echo 'N� : ' . $ex->getCode();
    }

}