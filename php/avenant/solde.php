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
    $codepol = $_REQUEST['code'];
    //recupérer le code souscripteur
    $rqtsous=$bdd->prepare("select cod_sous from policew where cod_pol='$codepol'");
    $rqtsous->execute();
    $cod_sous="";
    while($rwsous=$rqtsous->fetch())
    {
        $cod_sous=$rwsous['cod_sous'];
    }
$solde=0;//concederer  la quittance nest pas soldée dans mon cas  j'ai pris solde =1 comme exemple.
    if($cod_sous!="")
    {

        $rqtsolde=$bdd->prepare("select sum(solde_pol) as solde from quittance where cod_sous='$cod_sous'");
        $rqtsolde->execute();
        while($w=$rqtsolde->fetch())
        {
            $solde=$w['solde'];


        }
        if(is_null($solde))
        {
            echo 0;
        }
        if( $solde>0)
        {
            echo 1;
        }
        else
        {
            // si solde =0 alors les quittance de souscripteur cod_sous sont tous payés.
            echo 0;
        }

    }
    else
    {
        echo 1;
    }


}
?>