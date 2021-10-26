<?php session_start();
require_once("../../../data/conn4.php");
if ($_SESSION['login']){$user=$_SESSION['id_user'];}
else {
header("Location:../index.html?erreur=login"); // redirection en cas d'echec
}

if (isset($_REQUEST['d1']) && isset($_REQUEST['p']) && isset($_REQUEST['d2'])) {
$date1 = $_REQUEST['d1'];
$prod = $_REQUEST['p'];
$date2 = $_REQUEST['d2'];
$datesys=date("Y/m/d");
include("convert.php");
require('tfpdf.php'); class PDF extends TFPDF
{
// En-t?te
function Header()
{
 $this->SetFont('Arial','B',10);
	$this->Image('../img/entete_bna.png',6,4,380);
	 $this->Cell(150,5,'','O','0','L');
	 $this->SetFont('Arial','B',12);
	// $this->Cell(60,5,'MAPFRE | Assistance','O','0','L');
      $this->SetFont('Arial','B',10);
	  $this->Ln(30);
}

// Pied de page
function Footer()
{
    // Positionnement ? 1,5 cm du bas
    $this->SetY(-15);
    // Police Arial italique 8
    $this->SetFont('Arial','I',6);
    // Num?ro de page
    $this->Cell(0,8,'Page '.$this->PageNo().'/{nb}',0,0,'C');$this->Ln(3);
	$this->Cell(0,8,"Algerian Gulf Life Insurance Company, SPA au capital social de 1.000.000.000 de dinars alg�riens, 01 Rue Tripoli, hussein Dey Alger,  ",0,0,'C');
	$this->Ln(2);
	$this->Cell(0,8,"RC : 16/00-1009727 B 15   NIF : 001516100972762-NIS :0015160900296000",0,0,'C');
	$this->Ln(2);
	$this->Cell(0,8,"Tel : +213 (0) 21 77 30 12/14/15 Fax : +213 (0) 21 77 29 56 Site Web : www.aglic.dz  ",0,0,'C');
	}
}

// Instanciation de la classe derivee
	$pdf = new PDF('L','mm','A3');
$pdf->AliasNbPages();
$pdf->AddPage(); 
$pdf->SetFont('Arial','B',12);
$pdf->SetFillColor(199,139,85);
$pdf->SetFont('Arial','B',15);

$tpn=0;$tcp=0;$tpc=0;$tdt=0;$tpt=0;$treg=0;$tsolde=0;
//Parametres

		$rqtp = $bdd->prepare("SELECT a.`agence`, p.`lib_prod`,p.`code_prod` FROM `utilisateurs` as a,`produit` as p WHERE p.cod_prod='$prod' and a.id_user='$user'");
		$rqtp->execute();
	$agence="";
	$pdf->Cell(405,10,'Bordereau de production global du '.date("d/m/Y", strtotime($date1)).' au '.date("d/m/Y", strtotime($date2)).'  --Document g�n�r� le-- '.date("d/m/Y", strtotime($datesys)) ,'1','1','L','1');
	while ($row_p=$rqtp->fetch()){
		$pdf->Cell(136,10,'AgenceN�: '.$row_p['agence'],'1','0','C');$pdf->Cell(135,10,'Produit: '.$row_p['lib_prod'],'1','0','C');$pdf->Cell(134,10,'Code produit: '.$row_p['code_prod'],'1','1','C');
		$agence=$row_p['agence'];
	}
//requete pour les contrats
		$rqtg = $bdd->prepare("SELECT d.`dat_val`,d.`dat_eff`,d.`dat_ech`,d.`sequence`,d.`pn`,d.`pt`,d.mtt_reg,d.mtt_solde,t.`mtt_dt`,c.`mtt_cpl`,p.`code_prod`,p.`lib_prod` ,s.`nom_sous`, s.`pnom_sous`,m.`lib_mpay`,u.`agence` FROM `policew` as d, `dtimbre` as t , `cpolice` as c,`produit` as p,`souscripteurw` as s, `mpay` as m, `utilisateurs` as u  WHERE d.`cod_dt`=t.`cod_dt` AND d.`cod_cpl`=c.`cod_cpl` AND d.`cod_prod`=p.`cod_prod` AND d.`cod_sous`=s.`cod_sous` AND d.`mode`=m.`cod_mpay` AND s.`id_user`=u.`id_user` AND d.`cod_prod`='$prod' AND DATE_FORMAT(d.`dat_val`,'%Y-%m-%d')>='$date1' AND DATE_FORMAT(d.`dat_val`,'%Y-%m-%d')<='$date2' AND u.`agence`='$agence'");
		$rqtg->execute();
//requete pour les avenants positifs
		$rqtv = $bdd->prepare("SELECT d.`dat_val`,d.`pn`,d.`pn`,d.`pt`,d.mtt_reg,d.mtt_solde,d.`lib_mpay`,d.`sequence`,t.`mtt_dt`,c.`mtt_cpl`,p.`code_prod`,p.`lib_prod`, s.`cod_sous`,s.`nom_sous`, s.`pnom_sous`,z.sequence as seq2, z.dat_val as datev,u.`agence`  FROM `avenantw` as d,`policew` as z, `dtimbre` as t , `cpolice` as c,`produit` as p,`souscripteurw` as s,`utilisateurs` as u  WHERE d.`cod_dt`=t.`cod_dt` AND d.`cod_cpl`=c.`cod_cpl` AND d.`cod_prod`=p.`cod_prod` AND d.`cod_pol`=z.`cod_pol` AND z.`cod_sous`=s.`cod_sous` AND s.`id_user`=u.`id_user` AND d.`cod_prod`='$prod' AND DATE_FORMAT(d.`dat_val`,'%Y-%m-%d')>='$date1' AND DATE_FORMAT(d.`dat_val`,'%Y-%m-%d')<='$date2' AND u.`agence`='$agence' and d.`lib_mpay` not in ('30','50') order by d.`lib_mpay`");
		$rqtv->execute();

	//requete pour les avenants sans ristourne
	$rqtvsr = $bdd->prepare("SELECT d.`dat_val`,d.`pn`,d.`pn`,d.`pt`,d.mtt_reg,d.mtt_solde,d.`lib_mpay`,d.`sequence`,t.`mtt_dt`,c.`mtt_cpl`,p.`code_prod`,p.`lib_prod`, s.`cod_sous`,s.`nom_sous`, s.`pnom_sous`,z.sequence as seq2, z.dat_val as datev,u.`agence`  FROM `avenantw` as d,`policew` as z, `dtimbre` as t , `cpolice` as c,`produit` as p,`souscripteurw` as s,`utilisateurs` as u  WHERE d.`cod_dt`=t.`cod_dt` AND d.`cod_cpl`=c.`cod_cpl` AND d.`cod_prod`=p.`cod_prod` AND d.`cod_pol`=z.`cod_pol` AND z.`cod_sous`=s.`cod_sous` AND s.`id_user`=u.`id_user` AND d.`cod_prod`='$prod' AND DATE_FORMAT(d.`dat_val`,'%Y-%m-%d')>='$date1' AND DATE_FORMAT(d.`dat_val`,'%Y-%m-%d')<='$date2' AND u.`agence`='$agence' and d.`lib_mpay`  in ('50')");
	$rqtvsr->execute();

	//requete pour les avenants avec ristourne
	$rqtvar = $bdd->prepare("SELECT d.`dat_val`,d.`pn`,d.`pn`,d.`pt`,d.mtt_reg,d.mtt_solde,d.`lib_mpay`,d.`sequence`,t.`mtt_dt`,c.`mtt_cpl`,p.`code_prod`,p.`lib_prod`, s.`cod_sous`,s.`nom_sous`, s.`pnom_sous`,z.sequence as seq2, z.dat_val as datev,u.`agence`  FROM `avenantw` as d,`policew` as z, `dtimbre` as t , `cpolice` as c,`produit` as p,`souscripteurw` as s,`utilisateurs` as u  WHERE d.`cod_dt`=t.`cod_dt` AND d.`cod_cpl`=c.`cod_cpl` AND d.`cod_prod`=p.`cod_prod` AND d.`cod_pol`=z.`cod_pol` AND z.`cod_sous`=s.`cod_sous` AND s.`id_user`=u.`id_user` AND d.`cod_prod`='$prod' AND DATE_FORMAT(d.`dat_val`,'%Y-%m-%d')>='$date1' AND DATE_FORMAT(d.`dat_val`,'%Y-%m-%d')<='$date2' AND u.`agence`='$agence' and d.`lib_mpay`  in ('30')");
	$rqtvar->execute();



$pdf->Ln();
$pdf->SetFont('Arial','B',10);

$pdf->Cell(40,5,'Police N�','1','0','C');$pdf->Cell(40,5,'Avenant N�','1','0','C');$pdf->Cell(60,5,'Nom&Pr�nom-R.Sociale','1','0','C');
$pdf->Cell(18,5,'Emmision','1','0','C');$pdf->Cell(16,5,'Effet','1','0','C');$pdf->Cell(16,5,'Ech�ance','1','0','C');

$pdf->Cell(35,5,'P.Nette','1','0','C');$pdf->Cell(20,5,'C.Police','1','0','C');$pdf->Cell(40,5,'P.Commer','1','0','C');$pdf->Cell(20,5,'D.Timbre','1','0','C');$pdf->Cell(40,5,'P.Total','1','0','C');
	$pdf->Cell(30,5,'Reglement','1','0','C');$pdf->Cell(30,5,'Solde','1','0','C');
//Boucle police
while ($row_g=$rqtg->fetch()){
$pdf->SetFillColor(221,221,221);
$pdf->Ln();
$pdf->SetFont('Arial','B',10);
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',8);
//Reporting Polices
$pdf->Cell(40,5,''.$row_g['agence'].'.'.substr($row_g['dat_val'],0,4).'.10.'.$row_g['code_prod'].'.'.str_pad((int) $row_g['sequence'],'5',"0",STR_PAD_LEFT).'','1','0','C');
$pdf->Cell(40,5,'--','1','0','C');
$pdf->Cell(60,5,"".$row_g['nom_sous'].' '.$row_g['pnom_sous']."",'1','0','C');
$pdf->Cell(18,5,''.date("d/m/Y", strtotime($row_g['dat_val'])).'','1','0','C');$pdf->Cell(16,5,''.date("d/m/Y", strtotime($row_g['dat_eff'])).'','1','0','C');$pdf->Cell(16,5,''.date("d/m/Y", strtotime($row_g['dat_ech'])).'','1','0','C');

$pdf->Cell(35,5,''.number_format($row_g['pn'], 2,',',' ').'','1','0','R');$tpn=$tpn+$row_g['pn'];
$pdf->Cell(20,5,''.number_format($row_g['mtt_cpl'], 2,',',' ').'','1','0','R');$tcp=$tcp+$row_g['mtt_cpl'];
$pdf->Cell(40,5,''.number_format($row_g['pn']+$row_g['mtt_cpl'], 2,',',' ').'','1','0','R');$tpc=$tpc+($row_g['pn']+$row_g['mtt_cpl']);
$pdf->Cell(20,5,''.number_format($row_g['mtt_dt'], 2,',',' ').'','1','0','R');$tdt=$tdt+$row_g['mtt_dt'];
$pdf->Cell(40,5,''.number_format($row_g['pt'], 2,',',' ').'','1','0','R');$tpt=$tpt+$row_g['pt'];
$pdf->Cell(30,5,''.number_format($row_g['mtt_reg'], 2,',',' ').'','1','0','R');$treg=$treg+$row_g['mtt_reg'];
$pdf->Cell(30,5,''.number_format($row_g['mtt_solde'], 2,',',' ').'','1','0','R');$tsolde=$tsolde+$row_g['mtt_solde'];
}
	$pdf->Ln();
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial','IB',10);
	$pdf->SetFillColor(192,195,198);

	$pdf->Cell(190,5,'TOTAL, POLICES  ','1','0','L','1');

	$pdf->Cell(35,5,''.number_format($tpn, 2,',',' ').'','1','0','R','1');
	$pdf->Cell(20,5,''.number_format($tcp, 2,',',' ').'','1','0','R','1');
	$pdf->Cell(40,5,''.number_format($tpc, 2,',',' ').'','1','0','R','1');
	$pdf->Cell(20,5,''.number_format($tdt, 2,',',' ').'','1','0','R','1');
	$pdf->Cell(40,5,''.number_format($tpt, 2,',',' ').'','1','0','R','1');
	$pdf->Cell(30,5,''.number_format($treg, 2,',',' ').'','1','0','R','1');
	$pdf->Cell(30,5,''.number_format($tsolde, 2,',',' ').'','1','0','R','1');

	$ppn_av=0;$ppt_av=0;$pcpol_av=0;$pccom_av=0;$pdtim_av=0;$preg_av=0;$psolde_av=0;//1
//boucle Avenants POSITIFS
while ($row_v=$rqtv->fetch()){
$pdf->SetFillColor(221,221,221);
$pdf->Ln();
$pdf->SetFont('Arial','B',10);
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',8);
//Reporting Polices
$pdf->Cell(40,5,''.$row_v['agence'].'.'.substr($row_v['datev'],0,4).'.10.'.$row_v['code_prod'].'.'.str_pad((int) $row_v['seq2'],'5',"0",STR_PAD_LEFT).'','1','0','C');
$pdf->Cell(40,5,''.$row_v['agence'].'.'.substr($row_v['dat_val'],0,4).'.'.$row_v['lib_mpay'].'.'.$row_v['code_prod'].'.'.str_pad((int) $row_v['sequence'],'5',"0",STR_PAD_LEFT).'','1','0','C');
$pdf->Cell(60,5,"".$row_v['nom_sous'].' '.$row_v['pnom_sous']."",'1','0','C');
$pdf->Cell(18,5,''.date("d/m/Y", strtotime($row_v['dat_val'])).'','1','0','C');$pdf->Cell(16,5,'----','1','0','C');$pdf->Cell(16,5,'----','1','0','C');

$pdf->Cell(35,5,''.number_format($row_v['pn'], 2,',',' ').'','1','0','R');$tpn=$tpn+$row_v['pn'];/* pn avenant */$ppn_av=$ppn_av+$row_v['pn'];
$pdf->Cell(20,5,''.number_format($row_v['mtt_cpl'], 2,',',' ').'','1','0','R');$tcp=$tcp+$row_v['mtt_cpl'];/* cout police avenant*/$pcpol_av=$pcpol_av+$row_v['mtt_cpl'];
$pdf->Cell(40,5,''.number_format($row_v['pn']+$row_v['mtt_cpl'], 2,',',' ').'','1','0','R');$tpc=$tpc+($row_v['pn']+$row_v['mtt_cpl']);/*prime commerciale avenant*/$pccom_av=$pccom_av+($row_v['pn']+$row_v['mtt_cpl']);
$pdf->Cell(20,5,''.number_format($row_v['mtt_dt'], 2,',',' ').'','1','0','R');$tdt=$tdt+$row_v['mtt_dt'];/*droit timbre avenant*/$pdtim_av=$pdtim_av+$row_v['mtt_dt'];
$pdf->Cell(40,5,''.number_format($row_v['pt'], 2,',',' ').'','1','0','R');$tpt=$tpt+$row_v['pt'];/*pt avenant*/$ppt_av=$ppt_av+$row_v['pt'];
$pdf->Cell(20,5,''.number_format($row_v['mtt_reg'], 2,',',' ').'','1','0','R');$treg=$treg+$row_v['mtt_reg'];/*droit timbre avenant*/$preg_av=$preg_av+$row_v['mtt_reg'];
$pdf->Cell(40,5,''.number_format($row_v['mtt_solde'], 2,',',' ').'','1','0','R');$tsolde=$tsolde+$row_v['mtt_solde'];/*pt avenant*/$psolde_av=$psolde_av+$row_v['mtt_solde'];

}
$pdf->Ln();
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial','IB',10);
	$pdf->SetFillColor(192,195,198);


	$pdf->Cell(190,5,'TOTAL, Avenants positifs  ','1','0','L','1');
	$pdf->Cell(35,5,''.number_format($ppn_av, 2,',',' ').'','1','0','R','1');
	$pdf->Cell(20,5,''.number_format($pcpol_av, 2,',',' ').'','1','0','R','1');
	$pdf->Cell(40,5,''.number_format($pccom_av, 2,',',' ').'','1','0','R','1');
	$pdf->Cell(20,5,''.number_format($pdtim_av, 2,',',' ').'','1','0','R','1');
	$pdf->Cell(40,5,''.number_format($ppt_av, 2,',',' ').'','1','0','R','1');
	$pdf->Cell(30,5,''.number_format($preg_av, 2,',',' ').'','1','0','R','1');
	$pdf->Cell(30,5,''.number_format($psolde_av, 2,',',' ').'','1','0','R','1');
	$pdf->Ln();
	$pdf->SetFillColor(128,126,125);
	$pdf->Cell(190,5,'TOTAL, Production positive  ','1','0','L','1');
	$pdf->Cell(35,5,''.number_format($tpn, 2,',',' ').'','1','0','R','1');
	$pdf->Cell(20,5,''.number_format($tcp, 2,',',' ').'','1','0','R','1');
	$pdf->Cell(40,5,''.number_format($tpc, 2,',',' ').'','1','0','R','1');
	$pdf->Cell(20,5,''.number_format($tdt, 2,',',' ').'','1','0','R','1');
	$pdf->Cell(40,5,''.number_format($tpt, 2,',',' ').'','1','0','R','1');
	$pdf->Cell(30,5,''.number_format($treg, 2,',',' ').'','1','0','R','1');
	$pdf->Cell(30,5,''.number_format($tsolde, 2,',',' ').'','1','0','R','1');


//boucle Avenants SANS RISTOURNE
	$ppn_sr=0;$ppt_sr=0;$pcpol_sr=0;$pccom_sr=0;$pdtim_sr=0;$preg_sr=0;$psolde_sr=0;//1
	//boucle Avenants sans ristourne
	while ($row_vsr=$rqtvsr->fetch()){
		$pdf->SetFillColor(221,221,221);
		$pdf->Ln();
		$pdf->SetFont('Arial','B',10);
		$pdf->SetFillColor(255,255,255);
		$pdf->SetFont('Arial','B',8);
//Reporting Polices
		$pdf->Cell(40,5,''.$row_vsr['agence'].'.'.substr($row_vsr['datev'],0,4).'.10.'.$row_vsr['code_prod'].'.'.str_pad((int) $row_vsr['seq2'],'5',"0",STR_PAD_LEFT).'','1','0','C');
		$pdf->Cell(40,5,''.$row_vsr['agence'].'.'.substr($row_vsr['dat_val'],0,4).'.'.$row_vsr['lib_mpay'].'.'.$row_vsr['code_prod'].'.'.str_pad((int) $row_vsr['sequence'],'5',"0",STR_PAD_LEFT).'','1','0','C');
		$pdf->Cell(60,5,"".$row_vsr['nom_sous'].' '.$row_vsr['pnom_sous']."",'1','0','C');
		$pdf->Cell(18,5,''.date("d/m/Y", strtotime($row_vsr['dat_val'])).'','1','0','C');$pdf->Cell(16,5,'----','1','0','C');$pdf->Cell(16,5,'----','1','0','C');

		$pdf->Cell(35,5,''.number_format($row_vsr['pn'], 2,',',' ').'','1','0','R');$tpn=$tpn+$row_vsr['pn'];/* pn avenant */$ppn_sr=$ppn_sr+$row_vsr['pn'];
		$pdf->Cell(20,5,''.number_format($row_vsr['mtt_cpl'], 2,',',' ').'','1','0','R');$tcp=$tcp+$row_vsr['mtt_cpl'];/* cout police avenant*/$pcpol_sr=$pcpol_sr+$row_vsr['mtt_cpl'];
		$pdf->Cell(40,5,''.number_format($row_vsr['pn']+$row_vsr['mtt_cpl'], 2,',',' ').'','1','0','R');$tpc=$tpc+($row_vsr['pn']+$row_vsr['mtt_cpl']);/*prime commerciale avenant*/$pccom_sr=$pccom_sr+($row_vsr['pn']+$row_vsr['mtt_cpl']);
		$pdf->Cell(20,5,''.number_format($row_vsr['mtt_dt'], 2,',',' ').'','1','0','R');$tdt=$tdt+$row_vsr['mtt_dt'];/*droit timbre avenant*/$pdtim_sr=$pdtim_sr+$row_vsr['mtt_dt'];
		$pdf->Cell(40,5,''.number_format($row_vsr['pt'], 2,',',' ').'','1','0','R');$tpt=$tpt+$row_vsr['pt'];/*pt avenant*/$ppt_sr=$ppt_sr+$row_vsr['pt'];
		$pdf->Cell(30,5,''.number_format($row_vsr['mtt_reg'], 2,',',' ').'','1','0','R');$treg=$treg+$row_vsr['mtt_reg'];/*droit timbre avenant*/$preg_sr=$preg_sr+$row_vsr['mtt_reg'];
		$pdf->Cell(30,5,''.number_format($row_vsr['mtt_solde'], 2,',',' ').'','1','0','R');$tsolde=$tsolde+$row_vsr['mtt_solde'];/*pt avenant*/$psolde_sr=$psolde_sr+$row_vsr['mtt_solde'];

	}
	$pdf->Ln();
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial','IB',10);
	$pdf->SetFillColor(128,126,125);

	$pdf->Cell(190,5,'TOTAL, Avenants sans ristourne  ','1','0','L','1');

	$pdf->Cell(35,5,''.number_format($ppn_sr, 2,',',' ').'','1','0','R','1');
	$pdf->Cell(20,5,''.number_format($pcpol_sr, 2,',',' ').'','1','0','R','1');
	$pdf->Cell(40,5,''.number_format($pccom_sr, 2,',',' ').'','1','0','R','1');
	$pdf->Cell(20,5,''.number_format($pdtim_sr, 2,',',' ').'','1','0','R','1');
	$pdf->Cell(40,5,''.number_format($ppt_sr, 2,',',' ').'','1','0','R','1');
	$pdf->Cell(30,5,''.number_format($preg_sr, 2,',',' ').'','1','0','R','1');
	$pdf->Cell(30,5,''.number_format($psolde_sr, 2,',',' ').'','1','0','R','1');
//boucle Avenants AVEC RISTOURNE
	$ppn_ar=0;$ppt_ar=0;$pcpol_ar=0;$pccom_ar=0;$pdtim_ar=0;$preg_ar=0;$psolde_ar=0;//1
	//boucle Avenants sans ristourne
	while ($row_var=$rqtvar->fetch()){
		$pdf->SetFillColor(221,221,221);
		$pdf->Ln();
		$pdf->SetFont('Arial','B',10);
		$pdf->SetFillColor(255,255,255);
		$pdf->SetFont('Arial','B',8);
//Reporting Polices
		$pdf->Cell(40,5,''.$row_var['agence'].'.'.substr($row_var['datev'],0,4).'.10.'.$row_var['code_prod'].'.'.str_pad((int) $row_var['seq2'],'5',"0",STR_PAD_LEFT).'','1','0','C');
		$pdf->Cell(40,5,''.$row_var['agence'].'.'.substr($row_var['dat_val'],0,4).'.'.$row_var['lib_mpay'].'.'.$row_var['code_prod'].'.'.str_pad((int) $row_var['sequence'],'5',"0",STR_PAD_LEFT).'','1','0','C');
		$pdf->Cell(60,5,"".$row_var['nom_sous'].' '.$row_var['pnom_sous']."",'1','0','C');
		$pdf->Cell(18,5,''.date("d/m/Y", strtotime($row_var['dat_val'])).'','1','0','C');$pdf->Cell(16,5,'----','1','0','C');$pdf->Cell(16,5,'----','1','0','C');

		$pdf->Cell(35,5,''.number_format($row_var['pn'], 2,',',' ').'','1','0','R');$tpn=$tpn+$row_var['pn'];/* pn avenant */$ppn_ar=$ppn_ar+$row_var['pn'];
		$pdf->Cell(20,5,''.number_format($row_var['mtt_cpl'], 2,',',' ').'','1','0','R');$tcp=$tcp+$row_var['mtt_cpl'];/* cout police avenant*/$pcpol_ar=$pcpol_ar+$row_var['mtt_cpl'];
		$pdf->Cell(40,5,''.number_format($row_var['pn']+$row_var['mtt_cpl'], 2,',',' ').'','1','0','R');$tpc=$tpc+($row_var['pn']+$row_var['mtt_cpl']);/*prime commerciale avenant*/$pccom_ar=$pccom_ar+($row_var['pn']+$row_var['mtt_cpl']);
		$pdf->Cell(20,5,''.number_format($row_var['mtt_dt'], 2,',',' ').'','1','0','R');$tdt=$tdt+$row_var['mtt_dt'];/*droit timbre avenant*/$pdtim_ar=$pdtim_ar+$row_var['mtt_dt'];
		$pdf->Cell(40,5,''.number_format($row_var['pt'], 2,',',' ').'','1','0','R');$tpt=$tpt+$row_var['pt'];/*pt avenant*/$ppt_ar=$ppt_ar+$row_var['pt'];
		$pdf->Cell(30,5,''.number_format($row_var['mtt_reg'], 2,',',' ').'','1','0','R');$treg=$treg+$row_var['mtt_reg'];/*droit timbre avenant*/$preg_ar=$preg_ar+$row_var['mtt_reg'];
		$pdf->Cell(30,5,''.number_format($row_var['mtt_solde'], 2,',',' ').'','1','0','R');$tsolde=$tsolde+$row_var['mtt_solde'];/*pt avenant*/$psolde_ar=$psolde_ar+$row_var['mtt_solde'];

	}
	$pdf->Ln();
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial','IB',10);
	$pdf->SetFillColor(128,126,125);


	$pdf->Cell(190,5,'TOTAL, Avenants Avec ristourne  ','1','0','L','1');

	$pdf->Cell(35,5,''.number_format($ppn_ar, 2,',',' ').'','1','0','R','1');
	$pdf->Cell(20,5,''.number_format($pcpol_ar, 2,',',' ').'','1','0','R','1');
	$pdf->Cell(40,5,''.number_format($pccom_ar, 2,',',' ').'','1','0','R','1');
	$pdf->Cell(20,5,''.number_format($pdtim_ar, 2,',',' ').'','1','0','R','1');
	$pdf->Cell(40,5,''.number_format($ppt_ar, 2,',',' ').'','1','0','R','1');
	$pdf->Cell(30,5,''.number_format($preg_ar, 2,',',' ').'','1','0','R','1');
	$pdf->Cell(30,5,''.number_format($psolde_ar, 2,',',' ').'','1','0','R','1');
	$pdf->Ln();
	$pdf->SetFillColor(180,203,106);
$pdf->Cell(190,5,'TOTAL GENERAL','1','0','L','1');$pdf->Cell(35,5,''.number_format($tpn, 2,',',' ').'','1','0','R','1');$pdf->Cell(20,5,''.number_format($tcp, 2,',',' ').'','1','0','R','1');$pdf->Cell(40,5,''.number_format($tpc, 2,',',' ').'','1','0','R','1');$pdf->Cell(20,5,''.number_format($tdt, 2,',',' ').'','1','0','R','1');$pdf->Cell(40,5,''.number_format($tpt, 2,',',' ').'','1','0','R','1');$pdf->Cell(30,5,''.number_format($treg, 2,',',' ').'','1','0','R','1');$pdf->Cell(30,5,''.number_format($tsolde, 2,',',' ').'','1','0','R','1');

$pdf->Output();

}
?>