<?php
session_start();
require_once("../../../../data/conn4.php");

$id_user = $_SESSION['id_user'];
$datesys=date("y-m-d H:i:s");
//on recupere le code du pays
// xhr.open("GET", "php/avenant/validationav.php?code=" + codedev + "&date1=" + datedeb + "&date2=" + datfin + "&av=" + av+"&mode="+mode+"&datop="+dateop, false);

if ( isset($_REQUEST['code']) && isset($_REQUEST['av'])){
	$code = $_REQUEST['code'];
	$av = $_REQUEST['av'];

	//$datesys=date("y-m-d H:i:s");
	//if($_REQUEST['date1']!= NULL && $_REQUEST['date1']!= '' ){$date1=$_REQUEST['date1'];}
	//if($_REQUEST['date2']!= NULL && $_REQUEST['date2']!= ''  ){$date2=$_REQUEST['date2'];}
	if ( isset($_REQUEST['pays'])){$pays=$_REQUEST['pays'];}else{$pays="";}//cas changement de la destination.

	if ( isset($_REQUEST['mode'])){$modeav=$_REQUEST['mode'];}
	if ( isset($_REQUEST['datop'])){$datopav=$_REQUEST['datop'];}

//On récupere les infos de la police
	try {
		$bdd->beginTransaction();
$rqtd=$bdd->prepare("SELECT * from `policew` WHERE `cod_pol`='$code'");
$rqtd->execute();
while ($row_res=$rqtd->fetch()){
$tar=$row_res['cod_tar'];
$prod=$row_res['cod_prod'];
$per=$row_res['cod_per'];
$opt=$row_res['cod_opt'];
$zone=$row_res['cod_zone'];
$formul=$row_res['cod_formul'];
$dt=$row_res['cod_dt'];
$cpl=$row_res['cod_cpl'];
$deff=$row_res['dat_eff'];
$dech=$row_res['dat_ech'];
$cap1=$row_res['cap1'];
$cap2=$row_res['cap2'];
$cap3=$row_res['cap3'];
$p1=$row_res['p1'];
$p2=$row_res['p2'];
$p3=$row_res['p3'];
$pn=$row_res['pn'];
$pt=$row_res['pt'];
$codesous=$row_res['cod_sous'];
	$date1=$row_res['ndat_eff'];
	$date2=$row_res['ndat_ech'];

	$rqtuser=$bdd->prepare("select s.id_user  from policew as p , souscripteurw as s where p.cod_sous=s.cod_sous and p.cod_pol='$code'");
	$rqtuser->execute();
	$user_sous=$id_user;
	while ($rw=$rqtuser->fetch())
	{
		$user_sous=$rw['id_user'];
	}

	//recuperer code de l'agence.
	$rqtagence=$bdd->prepare("SELECT agence FROM `utilisateurs` WHERE `id_user`='$user_sous'");
	$rqtagence->execute();
	while ($row_agence=$rqtagence->fetch()){
		$agence_seq=$row_agence['agence'];
	}


//On récupere la sequence du produit
	$rqts=$bdd->prepare("SELECT  id_sequence,sequence2,sequence_quit,sequence_quit_dep FROM `sequence_ag` WHERE `cod_prod`='$prod' and cod_agence='$agence_seq'");
	$rqts->execute();
	while ($row_ress=$rqts->fetch()){
		$seq=$row_ress['sequence2'];
		$id_sequence=$row_ress['id_sequence'];
		$seq_quit = $row_ress['sequence_quit'];
		$seq_quit_dep = $row_ress['sequence_quit_dep'];
	}
	$seq++;
	$seq_quit++;
	$seq_quit_dep++;


if($av==74){
//Avenant de modification de date
//on insere dans la table policew
$rqtiav=$bdd->prepare("INSERT INTO `avenantw`(`cod_av`, `dat_val`, `cod_tar`, `cod_prod`, `cod_per`, `cod_opt`, `cod_zone`, `cod_pays`, `cod_formul`, `cod_dt`, `cod_cpl`, `dat_eff`, `dat_ech`, `cap1`, `cap2`, `cap3`, `p1`, `p2`, `p3`, `pn`, `pt`, `ndat_eff`, `ndat_ech`, `mode`, `dat_op`, `lib_mpay`, `sequence`, `etat`, `cod_pol`, `cod_mot`,`mtt_reg`,`mtt_solde`)  VALUES ('', '$datesys', '$tar', '$prod', '$per', '$opt', '$zone','DZ', '$formul', '$dt', '6', '$date1', '$date2','$cap1', '$cap2', '$cap3', '0', '0', '0', '0', '140', '$date1','$date2', '1','','$av','$seq', '0', '$code','','0','140')");
$rqtiav->execute();
//on supprime de devis
$rqtc=$bdd->prepare("UPDATE `policew` SET `ndat_eff`= '$date1',`ndat_ech`= '$date2' WHERE `cod_pol`='$code'");

$rqtc->execute();
	$cpl=6;
	$ptn=140;
	$dt=2;


}
if($av==70){
//Avenant de PRECISION
//on insere dans la table policew
$rqtiav=$bdd->prepare("INSERT INTO `avenantw`(`cod_av`, `dat_val`, `cod_tar`, `cod_prod`, `cod_per`, `cod_opt`, `cod_zone`, `cod_pays`, `cod_formul`, `cod_dt`, `cod_cpl`, `dat_eff`, `dat_ech`, `cap1`, `cap2`, `cap3`, `p1`, `p2`, `p3`, `pn`, `pt`, `ndat_eff`, `ndat_ech`, `mode`, `dat_op`, `lib_mpay`, `sequence`, `etat`, `cod_pol`, `cod_mot`,`mtt_reg`,`mtt_solde`)  VALUES ('', '$datesys', '$tar', '$prod', '$per', '$opt', '$zone','DZ', '$formul', '$dt', '6', '$date1', '$date2','$cap1', '$cap2', '$cap3', '0', '0', '0', '0', '140', '$date1','$date2', '$modeav','$datopav','$av','$seq', '0', '$code','','0','140')");
$rqtiav->execute();
//On incremente la sequence
	//$rqtc=$bdd->prepare("UPDATE `sequence_ag` SET `sequence2`= '$seq' WHERE `id_sequence`='$id_sequence'");
   //$rqtc->execute();

	//recupérer le max cod_av
	$rqtmav=$bdd->prepare("SELECT max(cod_av) as maxav FROM avenantw where cod_pol='$code' and lib_mpay='70' ");
	$rqtmav->execute();
	while ($rowav=$rqtmav->fetch())
	{
		$cod_av=$rowav['maxav'];
	}
//mise a jour assure
	$rqtassur=$bdd->prepare("UPDATE assure set cod_av='$cod_av' where cod_pol='$code' and id_user='$user_sous' and cod_av='0'");
	$rqtassur->execute();
	//mise a jour au niveau de souscripteurw
	$rqtsous=$bdd->prepare ("SELECT * FROM assure where cod_pol='$code' and id_user='$user_sous' and cod_av='$cod_av'");
	$rqtsous->execute();
	while($rowsous=$rqtsous->fetch())
	{
		$nom_assu=$rowsous['nom_assu'];
		$pnom_assu=$rowsous['pnom_assu'];
		$cod_assu=$rowsous['cod_sous'];
		$adr_assu=$rowsous['adr_assu'];
		$mail_assu=$rowsous['mail_assu'];
		$tel_assu=$rowsous['tel_assu'];
		$rqtma=$bdd->prepare("UPDATE `souscripteurw` SET `nom_sous`='$nom_assu',`pnom_sous`='$pnom_assu',`adr_sous`='$adr_assu',`tel_sous`='$tel_assu',`mail_sous`='$mail_assu' WHERE `cod_sous`='$cod_assu'");
		$rqtma->execute();

	}

	$cpl=6;
	$ptn=140;
	$dt=2;
}
if($av==73){
//Avenant de subrogation
//on insere dans la table avenant
		$rqtiav=$bdd->prepare("INSERT INTO `avenantw`(`cod_av`, `dat_val`, `cod_tar`, `cod_prod`, `cod_per`, `cod_opt`, `cod_zone`, `cod_pays`, `cod_formul`, `cod_dt`, `cod_cpl`, `dat_eff`, `dat_ech`, `cap1`, `cap2`, `cap3`, `p1`, `p2`, `p3`, `pn`, `pt`, `ndat_eff`, `ndat_ech`, `mode`, `dat_op`, `lib_mpay`, `sequence`, `etat`, `cod_pol`, `cod_mot`,`mtt_reg`,`mtt_solde`)  VALUES ('', '$datesys', '$tar', '$prod', '$per', '$opt', '$zone','DZ', '$formul', '$dt', '6', '$datesys', '$dech','$cap1', '$cap2', '$cap3', '$p1', '$p2', '$p3', '0', '140', '$datesys','$dech', '$modeav','$datopav','$av','$seq', '0', '$code','', '0', '140')");
		$rqtiav->execute();
	$cpl=6;
	$ptn=140;
	$dt=2;
	}
if($av==30){
//Avenant d'annulation Avec Ristourne
//on insere dans la table policew
$pnn=$pn*(-1);
$ptn=$pnn+140;
$rqtiav=$bdd->prepare("INSERT INTO `avenantw`(`cod_av`, `dat_val`, `cod_tar`, `cod_prod`, `cod_per`, `cod_opt`, `cod_zone`, `cod_pays`, `cod_formul`, `cod_dt`, `cod_cpl`, `dat_eff`, `dat_ech`, `cap1`, `cap2`, `cap3`, `p1`, `p2`, `p3`, `pn`, `pt`, `ndat_eff`, `ndat_ech`, `mode`, `dat_op`, `lib_mpay`, `sequence`, `etat`, `cod_pol`, `cod_mot`,`mtt_reg`,`mtt_solde`)  VALUES ('', '$datesys', '$tar', '$prod', '$per', '$opt', '$zone','DZ', '$formul', '$dt', '6', '$date1', '$date2','$cap1', '$cap2', '$cap3', '$p1', '$p2', '$p3', '$pnn', '$ptn', '$date1','$date2', '1','','$av','$seq', '0', '$code','','0', '$ptn')");
$rqtiav->execute();
//on supprime de devis
$rqtc=$bdd->prepare("UPDATE `policew` SET `etat`= '2' WHERE `cod_pol`='$code'");
$rqtc->execute();
	$cpl=6;
	$dt=2;
}
if($av==50){
//Avenant d'annulation Avec Ristourne
	$cod_cplav=4;
	if ($cpl==3)
	{
		$cod_cplav=5;
	}
//on insere dans la table policew
$ptn=$pt*(-1);$pnn=$pn*(-1);
$rqtiav=$bdd->prepare("INSERT INTO `avenantw`(`cod_av`, `dat_val`, `cod_tar`, `cod_prod`, `cod_per`, `cod_opt`, `cod_zone`, `cod_pays`, `cod_formul`, `cod_dt`, `cod_cpl`, `dat_eff`, `dat_ech`, `cap1`, `cap2`, `cap3`, `p1`, `p2`, `p3`, `pn`, `pt`, `ndat_eff`, `ndat_ech`, `mode`, `dat_op`, `lib_mpay`, `sequence`, `etat`, `cod_pol`, `cod_mot`,`mtt_reg`,`mtt_solde`)  VALUES ('', '$datesys', '$tar', '$prod', '$per', '$opt', '$zone','DZ', '$formul', '4', '$cod_cplav', '$date1', '$date2','$cap1', '$cap2', '$cap3', '$p1', '$p2', '$p3', '$pnn', '$ptn', '$date1','$date2', '1','','$av','$seq', '0', '$code','','0', '$ptn')");
$rqtiav->execute();

//on supprime de devis
$rqtc=$bdd->prepare("UPDATE `policew` SET `etat`= '3' WHERE `cod_pol`='$code'");
$rqtc->execute();
	$cpl=4;
	$dt=4;
}


	$rqtmax = $bdd->prepare("select max(cod_av) as code from avenantw where lib_mpay='$av' and cod_pol='$code' and dat_val='$datesys' ");
	$rqtmax->execute();
	$max_av = "";
	while ($rwx = $rqtmax->fetch()) {
		$max_av = $rwx['code'];
	}

	try {
	$mois=(int)date('m');
		if($av!=30 && $av!=50) {
	//CREER UNE QUITTANCE DE PRIME
	      $rqtip = $bdd->prepare("INSERT INTO `quittance`( `cod_quit`, `mois`, `date_quit`, `agence`, `cod_ref`,cod_sous, `mtt_quit`, `solde_pol`, `cod_dt`, `cod_cpl`, `id_user`,`type_quit`,`sens`) VALUES ('$seq_quit','$mois','$datesys','$agence_seq','$max_av','$codesous','$ptn','$ptn','$dt','$cpl','$id_user','1','0')");
	      $rqtip->execute();

	//On incremente la sequence //par produit
	      $rqtc=$bdd->prepare("UPDATE `sequence_ag` SET `sequence2`= '$seq' WHERE `id_sequence`='$id_sequence'");
	      $rqtc->execute();
	//incrementer la sequence quittance par agence.
	      $rqtq = $bdd->prepare("UPDATE `sequence_ag` SET `sequence_quit`= '$seq_quit' WHERE `cod_agence`='$agence_seq'");
	      $rqtq->execute();
		}
		else
		{
			$rqtip = $bdd->prepare("INSERT INTO `quittance`( `cod_quit`, `mois`, `date_quit`, `agence`, `cod_ref`,cod_sous, `mtt_quit`, `solde_pol`, `cod_dt`, `cod_cpl`, `id_user`,`type_quit`,`sens`) VALUES ('$seq_quit_dep','$mois','$datesys','$agence_seq','$max_av','$codesous','$ptn','$ptn','$dt','$cpl','$id_user','1','1')");
			$rqtip->execute();

			//On incremente la sequence //par produit
			$rqtc=$bdd->prepare("UPDATE `sequence_ag` SET `sequence2`= '$seq' WHERE `id_sequence`='$id_sequence'");
			$rqtc->execute();
			//incrementer la sequence quittance par agence.
			$rqtq = $bdd->prepare("UPDATE `sequence_ag` SET `sequence_quit_dep`= '$seq_quit_dep' WHERE `cod_agence`='$agence_seq'");
			$rqtq->execute();

		}
	}catch (Exception $ex)
	{
		//echo 'Erreur : ' . $ex->getMessage() . '<br />';
		//echo 'N° : ' . $ex->getCode();
	}

}

		$bdd->commit();
	} catch(Exception $e){
		//An exception has occured, which means that one of our database queries
		//failed.
		//Print out the error message.
		echo $e->getMessage();
		//Rollback the transaction.
		$bdd->rollBack();
	}
}
?>