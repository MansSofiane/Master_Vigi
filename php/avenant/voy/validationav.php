<?php
session_start();
require_once("../../../../../data/conn4.php");
//on recupere le code du pays

$id_user = $_SESSION['id_user'];
$datesys=date("y-m-d H:i:s");
if ( isset($_REQUEST['code']) && isset($_REQUEST['av'])){
	$code = $_REQUEST['code'];
	$av = $_REQUEST['av'];
	$date1="2099-12-31";$date2="2099-12-31";
	//$datesys=date("y-m-d H:i:s");
	if($_REQUEST['date1']!= NULL ){$date1=$_REQUEST['date1'];}
	if($_REQUEST['date2']!= NULL ){$date2=$_REQUEST['date2'];}
	if ( isset($_REQUEST['pays'])){$pays=$_REQUEST['pays'];}else{$pays="";}//cas changement de la destination.

	if ( isset($_REQUEST['mode'])){$modeav=$_REQUEST['mode'];}
	if ( isset($_REQUEST['datop'])){$datopav=$_REQUEST['datop'];}
try {
//On récupere les infos de la police

	$rqtd = $bdd->prepare("SELECT * from `policew` WHERE `cod_pol`='$code'");
	$rqtd->execute();
	while ($row_res = $rqtd->fetch()) {
		$tar = $row_res['cod_tar'];
		$prod = $row_res['cod_prod'];
		$per = $row_res['cod_per'];
		$opt = $row_res['cod_opt'];
		$zone = $row_res['cod_zone'];
		$cod_pays = $row_res['cod_pays'];
		$formul = $row_res['cod_formul'];
		$dt = $row_res['cod_dt'];
		$cpl = $row_res['cod_cpl'];
		$deff = $row_res['dat_eff'];
		$dech = $row_res['dat_ech'];
		$cap1 = $row_res['cap1'];
		$cap2 = $row_res['cap2'];
		$cap3 = $row_res['cap3'];
		$p1 = $row_res['p1'];
		$p2 = $row_res['p2'];
		$p3 = $row_res['p3'];
		$pn = $row_res['pn'];
		$pt = $row_res['pt'];
		$cod_sous = $row_res['cod_sous'];

		$rqtuser = $bdd->prepare("select s.id_user  from policew as p , souscripteurw as s where p.cod_sous=s.cod_sous and p.cod_pol='$code'");
		$rqtuser->execute();
		$user_sous = $id_user;
		while ($rw = $rqtuser->fetch()) {
			$user_sous = $rw['id_user'];
		}

		//recuperer code de l'agence.
		$rqtagence = $bdd->prepare("SELECT agence FROM `utilisateurs` WHERE `id_user`='$user_sous'");
		$rqtagence->execute();
		while ($row_agence = $rqtagence->fetch()) {
			$agence_seq = $row_agence['agence'];
		}

//On récupere la sequence du produit
		$rqts = $bdd->prepare("SELECT  id_sequence,sequence2,sequence_quit,sequence_quit_dep FROM `sequence_ag` WHERE `cod_prod`='$prod' and cod_agence='$agence_seq'");
		$rqts->execute();
		while ($row_ress = $rqts->fetch()) {
			$seq = $row_ress['sequence2'];
			$id_sequence = $row_ress['id_sequence'];
			$seq_quit = $row_ress['sequence_quit'];
			$seq_quit_dep=$row_ress['sequence_quit_dep'];
		}
		$seq++;
		$seq_quit++;
		$seq_quit_dep++;


//selectionner les information de souscripteur. dans le cas d'avenant de changement de destination.
		if ($pays != "") {
			$rqtsous = $bdd->prepare("select * from souscripteurw where cod_sous='$cod_sous'");
			$rqtsous->execute();
			while ($rowsous = $rqtsous->fetch()) {
				$rp_sous = $rowsous['rp_sous'];
				$nb_assur = $rowsous['nb_assur'];
				$agesous = $rowsous['age'];

			}
			$rqtzone = $bdd->prepare("select cod_zone from pays where cod_pays='$pays'");
			$rqtzone->execute();
			while ($rowszone = $rqtzone->fetch()) {
				$cod_zonenew = $rowszone['cod_zone'];

			}
		}

		//INSERT INTO `avenantw`(`cod_av`, `dat_val`, `cod_tar`, `cod_prod`, `cod_per`, `cod_opt`, `cod_zone`, `cod_pays`, `cod_formul`, `cod_dt`, `cod_cpl`, `dat_eff`, `dat_ech`, `cap1`, `cap2`, `cap3`, `p1`, `p2`, `p3`, `pn`, `pt`, `ndat_eff`, `ndat_ech`, `mode`, `dat_op`, `lib_mpay`, `sequence`, `etat`, `cod_pol`, `cod_mot`)
		if ($av == 74) {
//Avenant de modification de date
//on insere dans la table policew
			$rqtiav = $bdd->prepare("INSERT INTO `avenantw`(`cod_av`, `dat_val`, `cod_tar`, `cod_prod`, `cod_per`, `cod_opt`, `cod_zone`, `cod_pays`, `cod_formul`, `cod_dt`, `cod_cpl`, `dat_eff`, `dat_ech`, `cap1`, `cap2`, `cap3`, `p1`, `p2`, `p3`, `pn`, `pt`, `ndat_eff`, `ndat_ech`, `mode`, `dat_op`, `lib_mpay`, `sequence`, `etat`, `cod_pol`, `cod_mot`,`mtt_reg`,`mtt_solde`)  VALUES ('', '$datesys', '$tar', '$prod', '$per', '$opt', '$zone','$cod_pays', '$formul', '$dt', '6', '$date1', '$date2','$cap1', '$cap2', '$cap3', '0', '0', '0', '0', '140', '$date1','$date2', '1','','$av','$seq', '0', '$code','','0','140')");
			$rqtiav->execute();
//on supprime de devis
			$rqtc = $bdd->prepare("UPDATE `policew` SET `ndat_eff`= '$date1',`ndat_ech`= '$date2' WHERE `cod_pol`='$code'");
			$rqtc->execute();
			$cpl = 6;

			$ptn=140;
		}
		if ($av == 70) {
//Avenant de precision
//on insere dans la table policew
			$rqtiav = $bdd->prepare("INSERT INTO `avenantw`(`cod_av`, `dat_val`, `cod_tar`, `cod_prod`, `cod_per`, `cod_opt`, `cod_zone`, `cod_pays`, `cod_formul`, `cod_dt`, `cod_cpl`, `dat_eff`, `dat_ech`, `cap1`, `cap2`, `cap3`, `p1`, `p2`, `p3`, `pn`, `pt`, `ndat_eff`, `ndat_ech`, `mode`, `dat_op`, `lib_mpay`, `sequence`, `etat`, `cod_pol`, `cod_mot`,`mtt_reg`,`mtt_solde`)  VALUES ('', '$datesys', '$tar', '$prod', '$per', '$opt', '$zone','$cod_pays', '$formul', '$dt', '6', '$date1', '$date2','$cap1', '$cap2', '$cap3', '0', '0', '0', '0', '140', '$deff','$dech', '$modeav','$datopav','$av','$seq', '0', '$code','','0','140')");
			$rqtiav->execute();
//On incremente la sequence
			//$rqtc = $bdd->prepare("UPDATE `sequence_ag` SET `sequence2`= '$seq' WHERE `id_sequence`='$id_sequence'");
			//$rqtc->execute();
//recupérer le max cod_av
			$rqtmav = $bdd->prepare("SELECT max(cod_av) as maxav FROM avenantw where cod_pol='$code' and lib_mpay='70' ");
			$rqtmav->execute();
			while ($rowav = $rqtmav->fetch()) {
				$cod_av = $rowav['maxav'];
			}
//mise a jour modif
			$rqtassur = $bdd->prepare("UPDATE modif set cod_av='$cod_av' where cod_pol='$code' and id_user='$user_sous' and cod_par='$cod_sous'");
			$rqtassur->execute();

			// inserer le resultat dans assure

			$rqtassur = $bdd->prepare("insert into assure (`nom_assu`, `pnom_assu`, `passport`, `datedpass`, `datefpass`, `mail_assu`, `tel_assu`, `adr_assu`, `age_assu`, `sexe`, `cod_sous`, `cod_pol`, `cod_av`, `id_user`, `cod_par` , `modif_sous`) (select `nom_assu`, `pnom_assu`, `passport`, `datedpass`, `datefpass`, `mail_assu`, `tel_assu`, `adr_assu`, `age_assu`, `sexe`, `cod_sous`, `cod_pol`, `cod_av`, `id_user`, `cod_par` , `modif_sous` from  modif where cod_pol='$code' and id_user='$user_sous' and cod_par='$cod_sous')");
			$rqtassur->execute();

			//supprimer modif

			$rqtassur = $bdd->prepare("delete from  modif  where cod_pol='$code' and id_user='$user_sous' and cod_par='$cod_sous'");
			$rqtassur->execute();
			$ptn=140;
			$cpl = 6;
		}

		if ($av == 14) {
			$rqtmx = $bdd->prepare("SELECT MAX(p.cod_av) as cod from avenantw as p  where p.cod_pol='$code' and p.lib_mpay='14'");
			$rqtmx->execute();
			$cod_avmaw = "";
			while ($rowwx = $rqtmx->fetch()) {
				$cod_avmaw = $rowwx['cod'];
			}

			if ($cod_avmaw != "") {
				$rqtzone = $bdd->prepare("select p.cod_av as cod,p.cod_zone as cod_zone,p.cod_pays as cod_pays,p.dat_val as dat_val ,y.lib_pays as lib_pays
                            from avenantw as p , pays as y where cod_pol='$code' and p.cod_pays=y.cod_pays and p.cod_zone=y.cod_zone and p.lib_mpay='14'  and p.cod_av ='$cod_avmaw'");

				$rqtzone->execute();
			} else {
				$rqtzone = $bdd->prepare("select p.cod_pol as cod,p.cod_zone as cod_zone,p.cod_pays as cod_pays,p.dat_val as dat_val ,y.lib_pays as lib_pays from policew as p , pays as y where cod_pol='$code' and p.cod_pays=y.cod_pays ");
				$rqtzone->execute();
			}
			while ($rowszone = $rqtzone->fetch()) {
				$cod_zoneinit = $rowszone['cod_zone'];
				$cod_pays = $rowszone['cod_pays'];
				$lib_pays = $rowszone['lib_pays'];

			}
			if ($cod_zonenew == $cod_zoneinit) {

				$rqtiav = $bdd->prepare("INSERT INTO `avenantw`(`cod_av`, `dat_val`, `cod_tar`, `cod_prod`, `cod_per`, `cod_opt`, `cod_zone`, `cod_pays`, `cod_formul`, `cod_dt`, `cod_cpl`, `dat_eff`, `dat_ech`, `cap1`, `cap2`, `cap3`, `p1`, `p2`, `p3`, `pn`, `pt`, `ndat_eff`, `ndat_ech`, `mode`, `dat_op`, `lib_mpay`, `sequence`, `etat`, `cod_pol`, `cod_mot`,`mtt_reg`,`mtt_solde`)  VALUES ('', '$datesys', '$tar', '$prod', '$per', '$opt', '$cod_zonenew','$pays', '$formul', '$dt', '6', '$date1', '$date2','$cap1', '$cap2', '$cap3', '0', '0', '0', '0', '140', '$date1','$date2', '1','','$av','$seq', '0', '$code','','0','140')");
				$rqtiav->execute();
//on supprime de devis
				//$rqtc=$bdd->prepare("UPDATE `policew` SET `ndat_eff`= '$date1',`ndat_ech`= '$date2' WHERE `cod_pol`='$code'");
				//$rqtc->execute();
//On incremente la sequence
				//$rqtc = $bdd->prepare("UPDATE `sequence_ag` SET `sequence2`= '$seq' WHERE `id_sequence`='$id_sequence'");
				//$rqtc->execute();
				$primtotal=140;
			} else {
				$pnga_global = 0;
				$pngb_global = 0;
				$prime_nette = 0;

				//CALCULER LA PRIME 2
				if ($formul == 2)//indeviduelle
				{
					$rqtaffich = $bdd->prepare("select * from souscripteurw where cod_sous = '$cod_sous' ");
					$rqtaffich->execute();
					while ($rowind = $rqtaffich->fetch()) {
						$ageind = $rowind['age'];
					}

					$rqtassur = $bdd->prepare("select * from souscripteurw where cod_par = '$cod_sous' ");
					$rqtassur->execute();
					//si le souscripteur est l'assuré la boucle suivante ne retourne rien d'ou on $age garde l'ancienne valeur. [le souscripteur est l'assuré]
					while ($rowas = $rqtassur->fetch()) {
						$ageind = $rowas['age'];
					}


					$rqtselect = $bdd->prepare("SELECT a.* FROM `tarif` as a , pays as b WHERE a.cod_prod='1' and a.cod_formul='1' and a.cod_opt= '$opt'
       and a.agemin <= '$ageind' and a.agemax >= '$ageind' and a.cod_per= '$per'  and b.cod_pays= '$pays'
       and b.cod_zone = a.cod_zone and a.cod_cpl ='$cpl' ");

					$rqtselect->execute();
					$i = 0;
					$paind = 0;
					$pbind = 0;
					while ($row1 = $rqtselect->fetch()) {


						$paind = $row1['pa'] + (($row1['maj_pa'] * $row1['pa']) / 100) - (($row1['rab_pa'] * $row1['pa']) / 100);
						$pbind = $row1['pe'] + (($row1['maj_pe'] * $row1['pe']) / 100) - (($row1['rab_pe'] * $row1['pe']) / 100);
					}
					$pnga_global = $paind;
					$pngb_global = $pbind;
					$prime_nette = $paind + $pbind;

					//fin individuelle

				}
				if ($formul == 3)//COUPLE
				{
					$reqtnb = $bdd->prepare("select * from souscripteurw where cod_par='$cod_sous'");
					$reqtnb->execute();
					$nbsous = $reqtnb->rowCount();

					// recuperation de l'age des assures. et le code de ouscripteur de chacun.
					$codsous1 = 0;
					$codsous2 = 0;
					$age1 = 0;
					$age2 = 0;
					if ($nbsous == 1)//le souscripteurs est lui assure
					{
						$codsous1 = $cod_sous;

						$reqt1 = $bdd->prepare("select *from souscripteurw where cod_sous='$cod_sous'");
						$reqt1->execute();

						while ($row1 = $reqt1->fetch()) {
							$age1 = $row1['age'];
						}

						$reqt2 = $bdd->prepare("select *from souscripteurw where cod_par='$cod_sous' LIMIT 1");
						$reqt2->execute();

						while ($row2 = $reqt2->fetch()) {
							$codsous2 = $row2['cod_sous'];
							$age2 = $row2['age'];

						}
					} else {
						$reqt1 = $bdd->prepare("select *from souscripteurw where cod_par='$cod_sous' LIMIT 1");
						$reqt1->execute();

						while ($row1 = $reqt1->fetch()) {
							$codsous1 = $row1['cod_sous'];
							$age1 = $row1['age'];

						}
						// SELECT * FROM `souscripteurw` WHERE `cod_par`='8250' LIMIT 1,1
						$reqt2 = $bdd->prepare("select *from souscripteurw where cod_par='$cod_sous' LIMIT 1,1");
						$reqt2->execute();

						while ($row2 = $reqt2->fetch()) {
							$codsous2 = $row2['cod_sous'];
							$age2 = $row2['age'];

						}

					}
					$reqtar1 = $bdd->prepare("SELECT a.* FROM `tarif` as a , pays as b WHERE cod_prod='1' and cod_formul='1' and cod_opt='$opt'  and agemin <= '$age1' and agemax >='$age1' and cod_per='$per' and b.cod_pays='$pays'  and b.cod_zone=a.cod_zone and cod_cpl='$cpl'");

					$reqtar1->execute();
					$trouve1 = 0;
					$trouve2 = 0;
					while ($row_a1 = $reqtar1->fetch()) {
						$pa1 = $row_a1['pa'] + (($row_a1['maj_pa'] * $row_a1['pa']) / 100) - (($row_a1['rab_pa'] * $row_a1['pa']) / 100);
						$pg1 = $row_a1['pe'] + (($row_a1['maj_pe'] * $row_a1['pe']) / 100) - (($row_a1['rab_pe'] * $row_a1['pe']) / 100);

						$trouve1 = 1;
					}

					$reqtar2 = $bdd->prepare("SELECT a.* FROM `tarif` as a , pays as b WHERE cod_prod='1' and cod_formul='1' and cod_opt='$opt'  and agemin <= '$age2' and agemax >='$age2' and cod_per='$per' and b.cod_pays='$pays'  and b.cod_zone=a.cod_zone and cod_cpl='$cpl'");

					$reqtar2->execute();
					while ($row_a2 = $reqtar2->fetch()) {
						$pa2 = $row_a2['pa'] + (($row_a2['maj_pa'] * $row_a2['pa']) / 100) - (($row_a2['rab_pa'] * $row_a2['pa']) / 100);
						$pg2 = $row_a2['pe'] + (($row_a2['maj_pe'] * $row_a2['pe']) / 100) - (($row_a2['rab_pe'] * $row_a2['pe']) / 100);
						$trouve2 = 1;
					}


					$pngb_global = $pg1 + $pg2;
					$pnga_global = (($pa1 + $pa2) / 2) * 1.75;

					//$pt = $pg + $pa + 40 + 250;
					$prime_nette = $pngb_global + $pnga_global;
					//FIN FORMULE COUPLE

				}

				if ($formul == 4)//famille
				{
					$rqtaffich = $bdd->prepare("select * from souscripteurw where cod_par = '$cod_sous' ");
					$rqtaffich->execute();
					while ($row = $rqtaffich->fetch()) {
						$age = $row['age'];
						$primei = 0;

						$rqtselect = $bdd->prepare("SELECT a.* FROM `tarif` as a , pays as b WHERE a.cod_prod='1' and a.cod_formul='1' and a.cod_opt= '$opt'
                                                 and a.agemin <= '$age' and a.agemax >= '$age' and a.cod_per= '$per'  and b.cod_pays= '$pays' and b.cod_zone = a.cod_zone and a.cod_cpl ='$cpl' ");
						$rqtselect->execute();

						while ($row1 = $rqtselect->fetch()) {

							$pngb_global = $pngb_global + ($row1['pe'] + (($row1['maj_pe'] * $row1['pe']) / 100) - (($row1['rab_pe'] * $row1['pe']) / 100));
							$pt1 = $row1['pa'] + (($row1['maj_pa'] * $row1['pa']) / 100) - (($row1['rab_pa'] * $row1['pa']) / 100);
							$pnga_global = $pnga_global + $pt1;
							$cpt++;
						}
					}
					$pnga_global = ($pnga_global / $cpt) * 2.5;
					$prime_nette = $pnga_global + $pngb_global;
				}

				if ($formul == 5)//Groupe
				{
					$rqtaffich = $bdd->prepare("select * from souscripteurw where cod_par = '$cod_sous' ");
					$rqtaffich->execute();
					$cpt = 0;
					while ($row = $rqtaffich->fetch()) {
						$age = $row['age'];
						$primei = 0;
						// pour chaque assuré on calcule la prime qui lui conevient
						$rqtselect = $bdd->prepare("SELECT a.* FROM `tarif` as a , pays as b WHERE a.cod_prod='1' and a.cod_formul='1' and a.cod_opt= '$opt'
       and a.agemin <= '$age' and a.agemax >= '$age' and a.cod_per= '$per'  and b.cod_pays= '$pays'
       and b.cod_zone = a.cod_zone and a.cod_cpl ='$cpl' ");
						$rqtselect->execute();

						while ($row1 = $rqtselect->fetch()) {

							$pngb_global = $pngb_global + ($row1['pe'] + (($row1['maj_pe'] * $row1['pe']) / 100) - (($row1['rab_pe'] * $row1['pe']) / 100));
							$pt1 = $row1['pa'] + (($row1['maj_pa'] * $row1['pa']) / 100) - (($row1['rab_pa'] * $row1['pa']) / 100);
							$pnga_global = $pnga_global + $pt1;
							$cpt++;
						}
					}
					if ($opt < 30) {
						if ($cpt >= 10 && $cpt <= 25) {
							$rabg = 0.95;
						}
						if ($cpt >= 26 && $cpt <= 100) {
							$rabg = 0.90;
						}
						if ($cpt >= 101 && $cpt <= 200) {
							$rabg = 0.85;
						}
						if ($cpt >= 201) {
							$rabg = 0.75;
						}
					} else {
						$rabg = 1;
					}

					$pnga_global = $pnga_global * $rabg;
					$prime_nette = $pnga_global + $pngb_global;
				}


				//FAIRE LA DIFFERENCE
				$diffprime = $prime_nette - $pn;
				$p1 = $pnga_global - $p1;
				$p2 = $pngb_global - $p2;
				$primtotal = $diffprime + 140;

				//AJOUTER 140 DINARS


//on insere dans la table policew
				$rqtiav = $bdd->prepare("INSERT INTO `avenantw`(`cod_av`, `dat_val`, `cod_tar`, `cod_prod`, `cod_per`, `cod_opt`, `cod_zone`, `cod_pays`, `cod_formul`, `cod_dt`, `cod_cpl`, `dat_eff`, `dat_ech`, `cap1`, `cap2`, `cap3`, `p1`, `p2`, `p3`, `pn`, `pt`, `ndat_eff`, `ndat_ech`, `mode`, `dat_op`, `lib_mpay`, `sequence`, `etat`, `cod_pol`, `cod_mot`,`mtt_reg`,`mtt_solde`)  VALUES ('', '$datesys', '$tar', '$prod', '$per', '$opt', '$cod_zonenew','$pays', '$formul', '$dt', '6', '$date1', '$date2','$cap1', '$cap2', '$cap3', '$p1', '$p2', '$p3', '$diffprime', '$primtotal', '$deff','$dech', '$modeav','$datopav','$av','$seq', '0', '$code','','0','$primtotal')");
				$rqtiav->execute();

			}
			$cpl = 6;
			$ptn=$primtotal;
		}
		if ($av == 30) {
//Avenant d'annulation Avec Ristourne
//recupérer la prime nette des avenants


			$rqt_av = $bdd->prepare("select sum(pn) as pnav,sum(p1) as p1av,sum(p2) as p2av from avenantw where cod_pol='$code'");
			$rqt_av->execute();
			$pn_av = 0;
			$p1_av = 0;
			$p2_av = 0;

			while ($row_av = $rqt_av->fetch()) {
				$pn_av = $row_av['pnav'];
				$p1_av = $row_av['p1av'];
				$p2_av =$row_av['p2av'];
			}
			$pnn = ($pn + $pn_av) * (-1);
			$ptn = $pnn + 140;
			$p1n = -($p1+$p1_av);
			$p2n = -($p2+$p2_av);
			$inst="INSERT INTO `avenantw`(`cod_av`, `dat_val`, `cod_tar`, `cod_prod`, `cod_per`, `cod_opt`, `cod_zone`, `cod_pays`, `cod_formul`, `cod_dt`, `cod_cpl`, `dat_eff`, `dat_ech`, `cap1`, `cap2`, `cap3`, `p1`, `p2`, `p3`, `pn`, `pt`, `ndat_eff`, `ndat_ech`, `mode`, `dat_op`, `lib_mpay`, `sequence`, `etat`, `cod_pol`, `cod_mot`,`mtt_reg`,`mtt_solde`)  VALUES (";
			$inst=$inst."'', '$datesys', '$tar', '$prod', '$per', '$opt', '$zone','$cod_pays', '$formul', '$dt', '6', '$deff', '$date2','$cap1', '$cap2', '$cap3', '$p1n', '$p2n', '$p3', '$pnn', '$ptn', '$deff','$dech', '1','','$av','$seq', '0', '$code','','0','$ptn')";

			$rqtiav = $bdd->prepare("INSERT INTO `avenantw`(`cod_av`, `dat_val`, `cod_tar`, `cod_prod`, `cod_per`, `cod_opt`, `cod_zone`, `cod_pays`, `cod_formul`, `cod_dt`, `cod_cpl`, `dat_eff`, `dat_ech`, `cap1`, `cap2`, `cap3`, `p1`, `p2`, `p3`, `pn`, `pt`, `ndat_eff`, `ndat_ech`, `mode`, `dat_op`, `lib_mpay`, `sequence`, `etat`, `cod_pol`, `cod_mot`,`mtt_reg`,`mtt_solde`)  VALUES ('', '$datesys', '$tar', '$prod', '$per', '$opt', '$zone','$cod_pays', '$formul', '$dt', '6', '$deff', '$date2','$cap1', '$cap2', '$cap3', '$p1n', '$p2n', '$p3', '$pnn', '$ptn', '$deff','$dech', '1','','$av','$seq', '0', '$code','','0','$ptn')");
			$rqtiav->execute();

//on supprime de devis
			$rqtc = $bdd->prepare("UPDATE `policew` SET `etat`= '2' WHERE `cod_pol`='$code'");
			$rqtc->execute();
			$cpl = 6;

		}
		if ($av == 50) {
//Avenant d'annulation Avec Ristourne
//
			$rqt_av = $bdd->prepare("select sum(pn) as pnav,sum(pt) as ptav,sum(p1) as p1av,sum(p2) as p2av from avenantw where cod_pol='$code'");
			$rqt_av->execute();
			$pn_av = 0;
			$pt_av = 0;
			$p1_av = 0;
			$p2_av = 0;
			$cod_cplav = 4;
			if ($cpl == 3) {
				$cod_cplav = 5;
			}
			while ($row_av = $rqt_av->fetch()) {
				$pn_av = $row_av['pnav'];
				$pt_av = $row_av['ptav'];
				$p1_av = $row_av['p1av'];
				$p2_av =$row_av['p2av'];
			}
			$pnn = ($pn + $pn_av) * (-1);
			$ptn = ($pt + $pt_av) * (-1);
			$p1n = ($p1+$p1_av ) * (-1);
			$p2n = ($p2+$p2_av ) * (-1);
			$rqtiav = $bdd->prepare("INSERT INTO `avenantw`(`cod_av`, `dat_val`, `cod_tar`, `cod_prod`, `cod_per`, `cod_opt`, `cod_zone`, `cod_pays`, `cod_formul`, `cod_dt`, `cod_cpl`, `dat_eff`, `dat_ech`, `cap1`, `cap2`, `cap3`, `p1`, `p2`, `p3`, `pn`, `pt`, `ndat_eff`, `ndat_ech`, `mode`, `dat_op`, `lib_mpay`, `sequence`, `etat`, `cod_pol`, `cod_mot`,`mtt_reg`,`mtt_solde`)  VALUES ('', '$datesys', '$tar', '$prod', '$per', '$opt', '$zone','$cod_pays', '$formul', '4', '$cod_cplav', '$deff', '$date2','$cap1', '$cap2', '$cap3', '$p1n', '$p2n', '$p3', '$pnn', '$ptn', '$deff','$dech', '1','','$av','$seq', '0', '$code','','0','$ptn')");
			$rqtiav->execute();
//on supprime de devis
			$rqtc = $bdd->prepare("UPDATE `policew` SET `etat`= '3' WHERE `cod_pol`='$code'");
			$rqtc->execute();
			$cpl = 4;
			$dt=4;

		}


		$rqtmax = $bdd->prepare("select max(cod_av) as code from avenantw where lib_mpay='$av' and cod_pol='$code' and dat_val='$datesys' ");
		$rqtmax->execute();
		$max_av = "";
		while ($rwx = $rqtmax->fetch()) {
			$max_av = $rwx['code'];

		}
try {
	$mois = (int)date('m');
	if($av!=30 && $av!=50) {


		//CREER UNE QUITTANCE DE PRIME
		$rqtip = $bdd->prepare("INSERT INTO `quittance`( `cod_quit`, `mois`, `date_quit`, `agence`, `cod_ref`,cod_sous, `mtt_quit`, `solde_pol`, `cod_dt`, `cod_cpl`, `id_user`,`type_quit`,`sens`) VALUES ('$seq_quit','$mois','$datesys','$agence_seq','$max_av','$cod_sous','$ptn','$ptn','$dt','$cpl','$id_user','1','0')");
		$rqtip->execute();

		$rqtc = $bdd->prepare("UPDATE `sequence_ag` SET `sequence2`= '$seq' WHERE `id_sequence`='$id_sequence'");
		$rqtc->execute();
		//incrementer la sequence quittance par agence.
		$rqtq = $bdd->prepare("UPDATE `sequence_ag` SET `sequence_quit`= '$seq_quit' WHERE `cod_agence`='$agence_seq'");
		$rqtq->execute();
	}
	else
	{
		//CREER UNE QUITTANCE DE PRIME
		$rqtip = $bdd->prepare("INSERT INTO `quittance`( `cod_quit`, `mois`, `date_quit`, `agence`, `cod_ref`,cod_sous, `mtt_quit`, `solde_pol`, `cod_dt`, `cod_cpl`, `id_user`,`type_quit`,`sens`) VALUES ('$seq_quit_dep','$mois','$datesys','$agence_seq','$max_av','$cod_sous','$ptn','$ptn','$dt','$cpl','$id_user','1','1')");
		$rqtip->execute();

		$rqtc = $bdd->prepare("UPDATE `sequence_ag` SET `sequence2`= '$seq' WHERE `id_sequence`='$id_sequence'");
		$rqtc->execute();
		//incrementer la sequence quittance par agence.
		$rqtq = $bdd->prepare("UPDATE `sequence_ag` SET `sequence_quit_dep`= '$seq_quit_dep' WHERE `cod_agence`='$agence_seq'");
		$rqtq->execute();

	}
}catch (Exception $ex1)
		{
			echo 'Erreur : ' . $ex1->getMessage() . '<br />';
			echo 'N° : ' . $ex1->getCode();
		}
	}

}
catch(Exception $ex)
{
	$ex->getMessage();
}
}
?>