<?php 
session_start();
if ($_SESSION['login']){ 
//authentification acceptee !!!

}
else {
header("Location:../index.html?erreur=login"); // redirection en cas d'echec
}
require_once("../../../data/conn4.php");
include("convert.php");
$a1 = new chiffreEnLettre();
$errone = false;
require('tfpdf.php');
if (isset($_REQUEST['warda'])) {$row = substr($_REQUEST['warda'],10);}
class PDF extends TFPDF { // En-t?te
function Header()
{
 $this->SetFont('Arial','B',10);
    $this->Image('../img/entete_bna.png',6,4,190);
	 $this->Cell(150,5,'','O','0','L');
	 $this->SetFont('Arial','B',12);
	// $this->Cell(60,5,'MAPFRE | Assistance','O','0','L');
      $this->SetFont('Arial','B',10);
	  $this->Ln(8);
}
function Footer()
{
    // Positionnement ? 1,5 cm du bas
    $this->SetY(-15);
    // Police Arial italique 8
    $this->SetFont('Arial','I',6);
    // Num?ro de page
    $this->Cell(0,8,'Page '.$this->PageNo().'/{nb}',0,0,'C');$this->Ln(3);
	$this->Cell(0,8,"Algerian Gulf Life Insurance Company, SPA au capital social de 1.000.000.000 de dinars algériens, 01 Rue Tripoli, Hussein Dey Alger,  ",0,0,'C');
	$this->Ln(2);
	$this->Cell(0,8,"RC : 16/00-1009727 B 15   NIF : 001516100972762-NIS :0015160900296000",0,0,'C');
	$this->Ln(2);
	$this->Cell(0,8,"Tel : +213 (0) 21 77 30 12/14/15 Fax : +213 (0) 21 77 29 56 Site Web : www.aglic.dz  ",0,0,'C');
	}

function RotatedText($x,$y,$txt,$angle)
{
    //Text rotated around its origin
    $this->Rotate($angle,$x,$y);
    $this->Text($x,$y,$txt);
    $this->Rotate(0);
}	
		
}
//Preparation du PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

// Requete Agence 
$rqtu=$bdd->prepare("select * from utilisateurs where  id_user ='".$_SESSION['id_user']."'");
$rqtu->execute();
//Requete generale
$rqtg=$bdd->prepare("SELECT d.*,t.`mtt_dt`,c.`mtt_cpl`,o.`lib_opt`,p.`code_prod`, s.`cod_sous`, s.`nom_sous`, s.`pnom_sous`, s.`mail_sous`, s.`tel_sous`, s.`adr_sous`, s.`rp_sous`,s.`dnais_sous`,s.`age`,s.id_user  FROM `policew` as d, `dtimbre` as t , `cpolice` as c,`option` as o,`produit` as p,`souscripteurw` as s  WHERE d.`cod_dt`=t.`cod_dt` AND d.`cod_cpl`=c.`cod_cpl` AND d.`cod_opt`=o.`cod_opt` AND d.`cod_prod`=p.`cod_prod` AND d.`cod_sous`=s.`cod_sous` AND d.`cod_pol`='$row'");
$rqtg->execute();

$dat_eff="";$dat_ech="";
$agence="";
$adr_agence="";
$tel_agence="";
$cod_prod="";
$annee="";
$sequence_pol="";
$nom_sous="";
$pnom_sous="";
$tel_sous="";
$adr_sous="";
$mail_sous="";
$fax_sous="";


$pdf->SetFont('Arial','B',12);
//$pdf->Ln(2);
$pdf->SetFillColor(205,205,205);
while ($row_g=$rqtg->fetch()){
$pdf->Cell(190,8,'Assurance Individuelle Accident','0','0','C');$pdf->Ln();
    $user_creat=$row_g['id_user'];

    $rqtu = $bdd->prepare("select * from utilisateurs where  id_user ='$user_creat';");
    $rqtu->execute();
while ($row_user=$rqtu->fetch()){
$pdf->Cell(190,8,'Police N° '.$row_user['agence'].'.'.substr($row_g['dat_val'],0,4).'.10.'.$row_g['code_prod'].'.'.str_pad((int) $row_g['sequence'],'5',"0",STR_PAD_LEFT).'','0','0','C');$pdf->Ln();
$pdf->SetFont('Arial','I',6);
$pdf->Cell(0,6,"Le présent contrat est régi tant par les dispositions de lordonnance 95/07 du 25 janvier 1995 modifiée et complétée par la loi N° 06-04 du 20 Février 2006 que part les conditions",0,0,'C');$pdf->Ln(2);
$pdf->Cell(0,6,"générales et les conditions particuličres. En cas dincompatibilité entre les conditions générales et particuličres, les conditions particuličres prévalent toujours sur les conditions générales. ",0,0,'C');
$pdf->Ln(4);
//$pdf->Cell(190,8,'Devis Gratuit','0','0','C');$pdf->Ln();$pdf->Ln();
$pdf->SetFont('Arial','B',14);
//$pdf->Ln(2);
$pdf->SetFillColor(7,27,81);
$pdf->SetTextColor(255,255,255);


    $agence=$row_user['agence'];
    $adr_agence=$row_user['adr_user'];
    $tel_agence=$row_user['tel_user'];
    $cod_prod=$row_g['code_prod'];
    $annee=substr($row_g['dat_val'],0,4);
    $sequence_pol=str_pad((int) $row_g['sequence'],'5',"0",STR_PAD_LEFT);

    $cout_police=$row_g['mtt_cpl'];
    $droit_timbre=$row_g['mtt_dt'];

//Le Réseau
$pdf->SetFont('Arial','B',10);
$pdf->Cell(190,5,"Agence",'1','1','C','1');
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',8);
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(221,221,221);
$adr=$row_user['adr_user'];
$pdf->Cell(40,5,'Code','1','0','L','1');$pdf->Cell(55,5,"".$row_user['agence']."",'1','0','C');
$pdf->Cell(40,5,'Adresse','1','0','L','1');$pdf->Cell(55,5,"".$row_user['adr_user']."",'1','0','C');$pdf->Ln();
$pdf->Cell(40,5,'Téléphone','1','0','L','1');$pdf->Cell(55,5,"".$row_user['tel_user']."",'1','0','C');
$pdf->Cell(40,5,'E-mail','1','0','L','1');$pdf->Cell(55,5,"".$row_user['mail_user']."",'1','0','C');$pdf->Ln();
}
// debut du traitement de la requete generale
$codsous=$row_g['cod_sous'];
// Le Souscripteur
$pdf->SetFillColor(199,139,85);
$pdf->SetFont('Arial','B',10);
$pdf->Ln(3);
$pdf->Cell(190,5,'Souscripteur ','1','1','C','1');
    $nom_sous=$row_g['nom_sous'];
    $pnom_sous=$row_g['pnom_sous'];
    $tel_sous=$row_g['tel_sous'];
    $adr_sous=$row_g['adr_sous'];
    $mail_sous=$row_g['mail_sous'];

$pdf->SetFillColor(221,221,221);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(40,5,'Nom et Prénom','1','0','L','1');
$pdf->Cell(150,5,"".$row_g['nom_sous']." ".$row_g['pnom_sous']."",'1','0','C');$pdf->Ln();
$pdf->Cell(40,5,'Adresse','1','0','L','1');$pdf->Cell(150,5,"".$row_g['adr_sous']."",'1','0','C');$pdf->Ln();
$pdf->Cell(40,5,'Téléphone','1','0','L','1');$pdf->Cell(55,5,"".$row_g['tel_sous']."",'1','0','C');
$pdf->Cell(40,5,'E-mail','1','0','L','1');$pdf->Cell(55,5,"".$row_g['mail_sous']."",'1','0','C');$pdf->Ln();
$pdf->Ln(2);
// Contrat
$pdf->Ln(3);
$pdf->SetFillColor(7,27,81);
$pdf->SetTextColor(255,255,255);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(190,5,' Contrat ','1','0','C','1');$pdf->Ln();
$pdf->SetFillColor(221,221,221);
$pdf->SetTextColor(0,0,0);
$pdf->SetFont('Arial','B',8);
    $dat_eff=date("d/m/Y", strtotime($row_g['dat_eff']));$dat_ech=date("d/m/Y", strtotime($row_g['dat_ech']));
$pdf->Cell(50,5,'Effet le','1','0','L','1');$pdf->Cell(45,5,"".date("d/m/Y", strtotime($row_g['dat_eff']))."",'1','0','C');
$pdf->Cell(50,5,'Echéance le','1','0','L','1');$pdf->Cell(45,5,"".date("d/m/Y", strtotime($row_g['dat_ech']))."",'1','0','C');$pdf->Ln();
$pdf->Ln(3);
$pdf->SetFillColor(7,27,81);
$pdf->SetTextColor(255,255,255);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(190,5,' Garanties ','1','0','C','1');$pdf->Ln();
$pdf->SetFillColor(221,221,221);$pdf->SetTextColor(0,0,0);
$pdf->Cell(80,5,'Décčs Accidentel','1','0','L','1');$pdf->Cell(110,5,"".number_format($row_g['cap1'], 2, ',', ' ')."",'1','0','C');$pdf->Ln();
$pdf->Cell(80,5,'Incapacité Permanente Partielle','1','0','L','1');$pdf->Cell(110,5,"".number_format($row_g['cap2'], 2, ',', ' ')."",'1','0','C');$pdf->Ln();
$pdf->Cell(80,5,'Frais Médicaux et Pharmaceutiques','1','0','L','1');$pdf->Cell(110,5,"".number_format($row_g['cap3'], 2, ',', ' ')."",'1','0','C');$pdf->Ln();
$pdf->Ln(7);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(40,5,'Décompte de la prime ','0','0','L','0');
$pdf->Ln(7);
$pdf->SetFillColor(199,139,85);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(45,5,' Prime Nette ','1','0','C','1');$pdf->Cell(45,5,' Cout de Police ','1','0','C','1');
$pdf->Cell(50,5,' Droit de timbre ','1','0','C','1');$pdf->Cell(50,5,' Prime Totale (DZD) ','1','0','C','1');
$pdf->Ln();$pdf->SetFont('Arial','B',8);
$pdf->Cell(45,5,"".number_format($row_g['pn'], 2, ',', ' ')."",'1','0','C');
$pdf->Cell(45,5,"".number_format($row_g['mtt_cpl'], 2, ',', ' ')."",'1','0','C');
$pdf->Cell(50,5,"".number_format($row_g['mtt_dt'], 2, ',', ' ')."",'1','0','C');
$pdf->Cell(50,5,"".number_format($row_g['pt'], 2, ',', ' ')."",'1','0','C');$pdf->Ln();
$pdf->Ln(2);
$pdf->SetFont('Arial','I',6);
$pdf->Cell(0,6,"Le Souscripteur reconnait que les présentes Conditions Particuličres ont été établies conformément aux renseignements qu'il a donné lors de la souscription du Contrat.",0,0,'C');$pdf->Ln(2);
$pdf->Cell(0,6,"Le Souscripteur reconnait également avoir été informé du contenu des Conditions Particuličres et des Conditions Générales et avoir été informé du montant de la prime et des garanties dűes.",0,0,'C');
$pdf->Ln(9);
$somme=$a1->ConvNumberLetter("".$row_g['pt']."",1,0);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(30,5,"Le montant ŕ payer en lettres",'0','0','L');$pdf->Ln();
$pdf->SetFont('Arial','B',12);$pdf->SetFillColor(255,255,255);
$pdf->MultiCell(190,12,"".$somme."",1,'C',true);

$pdf->Ln(9);
$pdf->Cell(185,5,"".$adr." le ".date("d/m/Y", strtotime($row_g['dat_val']))."",'0','0','R');$pdf->Ln();
$pdf->Ln(2);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(60,5,"Le souscripteur",'0','0','C');$pdf->Cell(120,5,"L'assureur",'0','0','R');$pdf->Ln();
$pdf->SetFont('Arial','B',8);
$pdf->Cell(60,5,"Précedé de la mention ŤLu et approuvéť",'0','0','C');$pdf->Ln();
$pdf->Ln(35);$pdf->SetFont('Arial','B',6);
//$pdf->Cell(0,6,"Pour toute modification du contrat, le souscripteur est tenu d'aviser l'assureur avant la date de prise d'effet de son contrat, ou du dernier avenant",0,0,'C');$pdf->Ln(2);$pdf->Ln(2);


// Fin du traitement de la requete generale
}

$pdf->AddPage();
$pdf->SetFont('Arial','B',12);
//$pdf->Ln(2);
$pdf->SetFillColor(205,205,205);
$pdf->Cell(190,8,'Liste des assurés','0','0','C');$pdf->Ln();$pdf->Ln(4);
// L'assuré
$pdf->SetFillColor(7,27,81);
$pdf->SetTextColor(255,255,255);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(190,5,'Assuré ','1','1','C','1');
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(221,221,221);
$pdf->SetFont('Arial','B',8);
// le souscripteur n'est pas l'assuré
$rqta=$bdd->prepare("SELECT s.`nom_sous`, s.`pnom_sous`,s.`dnais_sous`,s.`autre_prof`,s.`quot_sous`,s.age FROM `souscripteurw` as s  WHERE  s.`cod_par`='$codsous'");
$rqta->execute();
$pdf->Cell(90,5,'Nom et Prénom','1','0','C','1');$pdf->Cell(20,5,'D.Naissance','1','0','C','1');$pdf->Cell(40,5,'Profession','1','0','C','1');$pdf->Cell(20,5,'Classe-risque','1','0','C','1');
$pdf->Cell(20,5,'Statut','1','0','C','1');
$pdf->Ln();
while ($row_a=$rqta->fetch()){
    $pdf->Cell(90,5,"".$row_a['nom_sous']." ".$row_a['pnom_sous']."",'1','0','C');
    $pdf->Cell(20,5,"".date("d/m/Y",strtotime($row_a['dnais_sous']))."",'1','0','C');
    $pdf->Cell(40,5,"".$row_a['autre_prof']."",'1','0','C');
    $class_rsq=$row_a['quot_sous'];
    if($class_rsq==2) {
        $pdf->Cell(20, 5, "Classe-1", '1', '0', 'C');
    }
    if($class_rsq==3) {
        $pdf->Cell(20, 5, "Classe-2", '1', '0', 'C');
    }
    if($class_rsq==4) {
        $pdf->Cell(20, 5, "Classe-3", '1', '0', 'C');
    }
    if($class_rsq==5) {
        $pdf->Cell(20, 5, "Risques-Spéciaux", '1', '0', 'C');
    }
    $age=$row_a['age'];
    if($age>65)
    {
        $pdf->Cell(20, 5, "Cas non assuré", '1', '0', 'C');
    }
    else
    {
        $pdf->Cell(20, 5, "", '1', '0', 'C');
    }
    $pdf->Ln();

}

//fin de la condition


$cpt_q=0;
$rqtseq=$bdd->prepare(" SELECT `id_quit`, `cod_quit`, `mois`, `date_quit`, `agence`, `cod_ref`, `mtt_quit`, `solde_pol`, `cod_dt`, `cod_cpl`, `id_user` FROM `quittance` WHERE `cod_ref`='$row' AND type_quit=0;");
$rqtseq->execute();
while($rowq=$rqtseq->fetch())
{
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->Ln(8);
    $cpt_q++;
    $pdf->SetFont('Arial','B',10);
    $pdf->SetFillColor(255,255,255);
    $pdf->Cell(20,5,'AGENCE    ','0','0','L');
    $pdf->SetFillColor(231,229,231);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(100,5,' : '.$agence,'0','0','L');$pdf->Ln();
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(20,5,'ADRESSE ','0','0','L');
    $pdf->SetFillColor(231,229,231);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(150,5,' : '.$adr_agence,'0','0','L');$pdf->Ln();
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(20,5,'TEL ','0','0','L');
    $pdf->SetFillColor(231,229,231);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(100,5,' : '.$tel_agence,'0','0','L');
    $pdf->Ln();
    $pdf->Cell(190,5,'Le:   '.date("d/m/Y"),'0','0','R');$pdf->Ln();
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(190,20,'QUITTANCE DE PRIME N°:'.$agence.'/'.substr($rowq['date_quit'],0,4).'/'.$cod_prod.'/'.str_pad((int) $rowq['cod_quit'],'5',"0",STR_PAD_LEFT),'LTR','0','C');$pdf->Ln();

    $pdf->Cell(20,20,'Police N°: ','LB','0','L');
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(70,20,' '.$agence.'.'.$annee.'.10.'.$cod_prod.'.'.$sequence_pol,'B','0','L');
    $pdf->Cell(100,20,'     DU:'.$dat_eff.'               AU'.$dat_ech.'    ','RB','0','R');$pdf->Ln();

    /////////
    $pdf->Ln(2);
    $pdf->Cell(190,6,"SOUSCIPTEUR:" ,'B','0','L');$pdf->Ln();
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(60,6,"Nom,Prénom/R.SOCIALE:" ,'L','0','L');
    $pdf->SetFillColor(231,229,231);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(130,6," ".$nom_sous.' '.$pnom_sous ,'R','0','L');$pdf->Ln();
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(60,6,"Adresse:" ,'L','0','L');
    // $pdf->SetXY(40,55);
    $pdf->SetFont('Arial','',10);
    $pdf->MultiCell(130,6,"".$adr_sous ,'R','L',false);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(60,6,"TEL :" ,'L','0','L');
    $pdf->Cell(130,6," ".$tel_sous."" ,'R','0','L');$pdf->Ln();
    $pdf->Cell(60,6,"E-mail:" ,'LB','0','L');
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(130,6," ".$mail_sous."" ,'RB','0','L');$pdf->Ln();$pdf->Ln();


    $pdf->Cell(40,5,'Décompte de la prime ','0','0','L','0');
    $pdf->Ln(6);
    $pdf->SetFillColor(199,139,85);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(45,6,' Prime Nette ','1','0','C','1');$pdf->Cell(45,6,' Cout de Police ','1','0','C','1');
    $pdf->Cell(50,6,' Droit de timbre ','1','0','C','1');$pdf->Cell(50,6,' Prime Totale (DZD) ','1','0','C','1');
    $pdf->Ln();$pdf->SetFont('Arial','B',8);
    $pdf->Cell(45,6,"".number_format($rowq['mtt_quit']-$cout_police-$droit_timbre, 2, ',', ' ')."",'1','0','C');
    $pdf->Cell(45,6,"".number_format($cout_police, 2, ',', ' ')."",'1','0','C');
    $pdf->Cell(50,6,"".number_format($droit_timbre, 2, ',', ' ')."",'1','0','C');
    $pdf->Cell(50,6,"".number_format($rowq['mtt_quit'], 2, ',', ' ')."",'1','0','C');$pdf->Ln(12);
    $sommeq=$a1->ConvNumberLetter("".$rowq['mtt_quit']."",1,0);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(30,4,"Soit en lettres",'0','0','L');$pdf->Ln(6);
    $pdf->SetFont('Arial','B',12);$pdf->SetFillColor(255,255,255);
    $pdf->MultiCell(190,4,"".$sommeq."",0,'L',true);
    $pdf->SetXY(110,$pdf->GetY());
    $pdf->MultiCell(80,18,"Cachet et signature",0,'R',true);
    $pdf->Ln(10);



}



$pdf->Output();	

				

?>








