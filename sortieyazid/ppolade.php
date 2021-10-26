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
	$this->Cell(0,8,"Algerian Gulf Life Insurance Company, SPA au capital social de 1.000.000.000 de dinars alg�riens, 01 Rue Tripoli, Hussein Dey Alger,  ",0,0,'C');
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
$pdf->SetFont('Arial','B',12);
//Requete generale
$rqtg=$bdd->prepare("SELECT d.*,t.`mtt_dt`,c.`mtt_cpl`,o.`lib_opt`,p.`code_prod`, s.`nom_sous`, s.`pnom_sous`, s.`mail_sous`, s.`tel_sous`, s.`adr_sous`, s.`rp_sous`,s.`dnais_sous`,s.`age`,s.id_user, b.`nom_benef`, b.`ag_benef`,b.`tel_benef`,b.`adr_benef`  FROM `policew` as d, `dtimbre` as t , `cpolice` as c,`option` as o,`produit` as p,`souscripteurw` as s,`beneficiaire` as b  WHERE d.`cod_dt`=t.`cod_dt` AND d.`cod_cpl`=c.`cod_cpl` AND d.`cod_opt`=o.`cod_opt` AND d.`cod_prod`=p.`cod_prod` AND d.`cod_sous`=s.`cod_sous` AND b.`cod_sous`=s.`cod_sous` AND d.`cod_pol`='$row'");
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

while ($row_g=$rqtg->fetch()){
//$pdf->Ln(2);
$pdf->SetFillColor(205,205,205);
$pdf->Cell(190,8,'Assurance D�c�s Emprunteur','0','0','C');$pdf->Ln();
    $user_creat=$row_g['id_user'];

    $rqtu = $bdd->prepare("select * from utilisateurs where  id_user ='$user_creat';");
    $rqtu->execute();
while ($row_user=$rqtu->fetch()){
$pdf->Cell(190,8,'Police N� '.$row_user['agence'].'.'.substr($row_g['dat_val'],0,4).'.10.'.$row_g['code_prod'].'.'.str_pad((int) $row_g['sequence'],'5',"0",STR_PAD_LEFT).'','0','0','C');$pdf->Ln();
$pdf->SetFont('Arial','I',6);
$pdf->Cell(0,6,"Le pr�sent contrat est r�gi tant par les dispositions de l�ordonnance 95/07 du 25 janvier 1995 modifi�e et compl�t�e par la loi N� 06-04 du 20 F�vrier 2006 que part les conditions",0,0,'C');$pdf->Ln(2);
$pdf->Cell(0,6,"g�n�rales et les conditions particuli�res. En cas d�incompatibilit� entre les conditions g�n�rales et particuli�res, les conditions particuli�res pr�valent toujours sur les conditions g�n�rales. ",0,0,'C');
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
//Le R�seau
$pdf->SetFont('Arial','B',10);
$pdf->Cell(190,5,"Agence",'1','1','C','1');
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',8);
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(221,221,221);

$adr=$row_user['adr_user'];
$pdf->Cell(40,5,'Code','1','0','L','1');$pdf->Cell(55,5,"".$row_user['agence']."",'1','0','C');
$pdf->Cell(40,5,'Adresse','1','0','L','1');$pdf->Cell(55,5,"".$row_user['adr_user']."",'1','0','C');$pdf->Ln();
$pdf->Cell(40,5,'T�l�phone','1','0','L','1');$pdf->Cell(55,5,"".$row_user['tel_user']."",'1','0','C');
$pdf->Cell(40,5,'E-mail','1','0','L','1');$pdf->Cell(55,5,"".$row_user['mail_user']."",'1','0','C');$pdf->Ln();
}


// debut du traitement de la requete generale

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
$pdf->Cell(40,5,'Nom et Pr�nom','1','0','L','1');
$pdf->Cell(150,5,"".$row_g['nom_sous']." ".$row_g['pnom_sous']."",'1','0','C');$pdf->Ln();
$pdf->Cell(40,5,'Adresse','1','0','L','1');$pdf->Cell(150,5,"".$row_g['adr_sous']."",'1','0','C');$pdf->Ln();
$pdf->Cell(40,5,'T�l�phone','1','0','L','1');$pdf->Cell(55,5,"".$row_g['tel_sous']."",'1','0','C');
$pdf->Cell(40,5,'E-mail','1','0','L','1');$pdf->Cell(55,5,"".$row_g['mail_sous']."",'1','0','C');$pdf->Ln();
$pdf->Ln(3);
// L'assur�
$pdf->SetFillColor(7,27,81);
$pdf->SetTextColor(255,255,255);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(190,5,'Assur� ','1','1','C','1');
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(221,221,221);
$pdf->SetFont('Arial','B',8);
// la condition sur le souscripteur et l'assure
if($row_g['rp_sous']==1){
$pdf->Cell(40,5,'Nom et Pr�nom','1','0','L','1');$pdf->Cell(150,5,"".$row_g['nom_sous']." ".$row_g['pnom_sous']."",'1','0','C');$pdf->Ln();
$pdf->Cell(40,5,'Adresse','1','0','L','1');$pdf->Cell(150,5,"".$row_g['adr_sous']."",'1','0','C');$pdf->Ln();
$pdf->Cell(40,5,'T�l�phone','1','0','L','1');$pdf->Cell(55,5,"".$row_g['tel_sous']."",'1','0','C');
$pdf->Cell(40,5,'E-mail','1','0','L','1');$pdf->Cell(55,5,"".$row_g['mail_sous']."",'1','0','C');$pdf->Ln();
$pdf->Cell(40,5,'D.Naissance','1','0','L','1');$pdf->Cell(55,5,"".date("d/m/Y",strtotime($row_g['dnais_sous']))."",'1','0','C');
$pdf->Cell(40,5,'Age','1','0','L','1');$pdf->Cell(55,5,"".$row_g['age']."",'1','0','C');$pdf->Ln();
}else{
// le souscripteur n'est pas l'assur�
$rowa=$row_g['cod_sous'];
$rqta=$bdd->prepare("SELECT s.`nom_sous`, s.`pnom_sous`, s.`mail_sous`, s.`tel_sous`, s.`adr_sous`, s.`rp_sous`,s.`dnais_sous`,s.`age`  FROM `souscripteurw` as s  WHERE  s.`cod_par`='$rowa'");
$rqta->execute();
while ($row_a=$rqta->fetch()){
$pdf->Cell(40,5,'Nom et Pr�nom','1','0','L','1');$pdf->Cell(150,5,"".$row_a['nom_sous']." ".$row_a['pnom_sous']."",'1','0','C');$pdf->Ln();
$pdf->Cell(40,5,'Adresse','1','0','L','1');$pdf->Cell(150,5,"".$row_a['adr_sous']."",'1','0','C');$pdf->Ln();
$pdf->Cell(40,5,'T�l�phone','1','0','L','1');$pdf->Cell(55,5,"".$row_a['tel_sous']."",'1','0','C');
$pdf->Cell(40,5,'E-mail','1','0','L','1');$pdf->Cell(55,5,"".$row_a['mail_sous']."",'1','0','C');$pdf->Ln();
$pdf->Cell(40,5,'D.Naissance','1','0','L','1');$pdf->Cell(55,5,"".date("d/m/Y",strtotime($row_a['dnais_sous']))."",'1','0','C');
$pdf->Cell(40,5,'Age','1','0','L','1');$pdf->Cell(55,5,"".$row_a['age']."",'1','0','C');$pdf->Ln();
}
//fin de la condition
}

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
$pdf->Cell(50,5,'Effet le','1','0','L','1');$pdf->Cell(45,5,"".date("d/m/Y", strtotime($row_g['ndat_eff']))."",'1','0','C');
$pdf->Cell(50,5,'Ech�ance le','1','0','L','1');$pdf->Cell(45,5,"".date("d/m/Y", strtotime($row_g['ndat_ech']))."",'1','0','C');$pdf->Ln();
$pdf->Ln(3);
$pdf->SetFillColor(7,27,81);
$pdf->SetTextColor(255,255,255);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(190,5,' Garanties et Capital assur�','1','0','C','1');$pdf->Ln();
$pdf->SetFillColor(221,221,221);$pdf->SetTextColor(0,0,0);
$pdf->Cell(90,5,'Garanties','1','0','L','1');$pdf->Cell(100,5,'D�c�s et Invalidit� Absolue et D�finitive','1','0','C');$pdf->Ln();
$pdf->Cell(90,5,'Capital Assur�','1','0','L','1');$pdf->Cell(100,5,"".number_format($row_g['cap1'], 2, ',', ' ')." DZD",'1','0','C');$pdf->Ln();
// Beneficiaire (Organisme preteur)
$pdf->Ln(3);
$pdf->SetFillColor(7,27,81);
$pdf->SetTextColor(255,255,255);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(190,5,' B�n�ficiaires','1','0','C','1');$pdf->Ln();
$pdf->SetFillColor(221,221,221);$pdf->SetTextColor(0,0,0);
$pdf->Cell(35,5,'Organisme pr�teur','1','0','L','1');$pdf->Cell(155,5,"".$row_g['nom_benef']."",'1','0','L');$pdf->Ln();
$pdf->Cell(35,5,'Code agence','1','0','L','1');$pdf->Cell(155,5,"".$row_g['ag_benef']."",'1','0','L');$pdf->Ln();
$pdf->Cell(35,5,'T�l�phone','1','0','L','1');$pdf->Cell(155,5,"".$row_g['tel_benef']."",'1','0','L');$pdf->Ln();
$pdf->Cell(35,5,'Adresse','1','0','L','1');$pdf->Cell(155,5,"".$row_g['adr_benef']."",'1','0','L');$pdf->Ln();


$pdf->Ln(5);


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
//$pdf->Cell(0,6,"Le Souscripteur reconnait que les pr�sentes Conditions Particuli�res ont �t� �tablies conform�ment aux renseignements qu'il a donn� lors de la souscription du Contrat.",0,0,'C');$pdf->Ln(2);
//$pdf->Cell(0,6,"Le Souscripteur reconnait �galement avoir �t� inform� du contenu des Conditions Particuli�res et des Conditions G�n�rales et avoir �t� inform� du montant de la prime et des garanties d�es.",0,0,'C');
$pdf->Ln(7);
$somme=$a1->ConvNumberLetter("".$row_g['pt']."",1,0);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(30,5,"Le montant � payer en lettres",'0','0','L');$pdf->Ln();
$pdf->SetFont('Arial','B',12);$pdf->SetFillColor(255,255,255);
$pdf->MultiCell(190,12,"".$somme."",1,'C',true);

$pdf->Ln(7);


$pdf->Cell(185,5,"".$adr." le ".date("d/m/Y", strtotime($row_g['dat_val']))."",'0','0','R');$pdf->Ln();
$pdf->Ln(2);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(60,5,"Le souscripteur",'0','0','C');$pdf->Cell(120,5,"L'assureur",'0','0','R');$pdf->Ln();
$pdf->SetFont('Arial','B',8);
$pdf->Cell(60,5,"Pr�ced� de la mention �Lu et approuv�",'0','0','C');$pdf->Ln();
$pdf->Ln(35);$pdf->SetFont('Arial','B',6);
//$pdf->Cell(0,6,"Pour toute modification du contrat, le souscripteur est tenu d'aviser l'assureur avant la date de prise d'effet de son contrat, ou du dernier avenant",0,0,'C');$pdf->Ln(2);$pdf->Ln(2);
$pdf->SetFont('Arial','',100);
//$pdf->RotatedText(60,240,'Devis-Gratuit',60);

// Fin du traitement de la requete generale
}


//Conditions g�n�rales la derni�re page
$pdf->AddPage();

$pdf->SetFont('Arial','B',12);
$pdf->SetFillColor(205,205,205);
//$pdf->Ln(2);Notice d'information
//$pdf->Image('../img/Notice_information.png',0,0,210,297);

$pdf->Cell(190,8,"Assurance D�c�s Emprunteur",'0','0','C');$pdf->Ln();
$pdf->Cell(190,8,"CONDITIONS GENERALES",'0','0','C');$pdf->Ln();
$pdf->Ln(5);
$pdf->SetFont('Arial','B',9);
$pdf->Cell(10,3,"Base juridique",0,0,"J");$pdf->Ln();
$pdf->SetFont('Arial','',9);
$pdf->MultiCell(190,3,"Les presentes conditions generales sont regies tant par I'ordonnance N� 15-58 du 26 septembre 1915 portant code civile rnodifiee et cornpletee et par I'ordonnance N� 95-01 du 25 janvier 1995 relative aux assurances rnodlfiee et cornpletee par la loi N� 06-04 du
20 fevrier 2006 que par Ie decret executif N� 02-293 du 10 septembre 2002 modifiant et cornpletant Ie decret executlf N� 95-338 du 30 octobre 1995 relatif a l'etabllssement et a la codification des operations d'assurance.",0,"J",false);$pdf->Ln();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(10,5,"ARTICLE 1�: OBJET DU CONTRAT",0,0,"J");$pdf->Ln();
$pdf->SetFont('Arial','',9);
$pdf->MultiCell(90,3,"Le pr�sent contrat a pour objet de garantir au pr�teur, durant la p�riode de validit� des garanties, le r�glement du capital restant d� en cas de D�c�s ou de Perte Totale et Irr�versible d�autonomie (PTIA) de l�assur� emprunteur.",0,"J",false);
$pdf->Ln();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(10,5,"ARTICLE 2�: DEFINITIONS ",0,0,"J");$pdf->Ln();
$pdf->SetFont('Arial','B',9);
$pdf->Cell(10,3,"Assureur:",0,0,"J");
$pdf->SetFont('Arial','',9);
$pdf->MultiCell(80,3,"       Par ��Assureur��, on entend, la compagnie ",0,"J",false);
$pdf->MultiCell(90,3,"d�assurances de personnes ��Algerian Gulf Life Insurance Company�� par abr�viation ��AGLIC�� dont le nom commercial est �L�ALGERIENNE VIE d�tenant un capital social de 1�000�000�000 DA, sise Centre des affaires El QODS -Esplanade - 4�me Etage Ch�raga � Alger",0,"J",false);

$pdf->SetFont('Arial','B',9);
$pdf->Cell(10,3,"Souscripteur : ",0,0,"J");
$pdf->SetFont('Arial','',9);
$pdf->MultiCell(80,3,"                 Par  �Souscripteur�, on entend, la personne ",0,"J",false);
$pdf->MultiCell(90,3,"d�sign�e sous ce nom aux conditions particuli�res, ou toute personne qui lui serait substitu�e par accord des parties, qui souscrit le contrat pour le compte de l�assur�. ",0,"J",false);

$pdf->SetFont('Arial','B',9);
$pdf->Cell(10,3,"Beneficiaire Du Contrat :",0,0,"J");
$pdf->SetFont('Arial','',9);
$pdf->MultiCell(80,3,"                               Le b�n�ficiaire est toute personne ",0,"J",false);
$pdf->MultiCell(90,3,"� qui les prestations sont dues en vertu du contrat. C'est la personne � laquelle revient tout ou partie du capital en cas de d�c�s de la t�te (personne) assur�e.",0,"J",false);

$pdf->SetFont('Arial','B',9);
$pdf->Cell(10,3,"Sinistres:",0,0,"J");
$pdf->SetFont('Arial','',9);
$pdf->MultiCell(80,3,"      C�est la survenance de l��v�nement, s�il est assur�,",0,"J",false);
$pdf->MultiCell(90,3,"qui d�clenche la garantie de l�assureur.",0,"J",false);



$pdf->SetFont('Arial','B',9);
$pdf->Cell(10,3,"Provision math�matique:",0,0,"J");
$pdf->SetFont('Arial','',9);
$pdf->MultiCell(80,3,"                               Ceux sont les r�serves constitu�es",0,"J",false);
$pdf->MultiCell(90,3,"par l�assureur afin de garantir le paiement des prestations durant toute la vie du contrat.",0,"J",false);

$pdf->Ln();

$pdf->SetFont('Arial','B',10);
$pdf->Cell(10,5,"ARTICLE 3�: GARANTIES",0,0,"J");$pdf->Ln();
$pdf->SetFont('Arial','B',9);
$pdf->Cell(10,3,"a) D�c�s (code�: 20.2)",0,0,"J");$pdf->Ln();
$pdf->SetFont('Arial','',9);
$pdf->MultiCell(90,3,"Sauf exclusion formelle, l�Assureur couvre le remboursement du montant de capital emprunt� restant d� suite au d�c�s de l�assur�.",0,"J",false);
$pdf->SetFont('Arial','B',9);
$pdf->Cell(10,3,"b)Perte Totale et Irr�versible d�Autonomie (PTIA)(code:20.2)",0,0,"J");$pdf->Ln();
$pdf->SetFont('Arial','',9);
$pdf->MultiCell(90,3,"Sauf exclusion formelle, l�Assureur couvre le remboursement du montant de capital emprunt� restant d� en cas de perte totale et irr�versible d�autonomie (PTIA) de l�assur�, en cours de validit� du pr�sent contrat, quelle qu�en soit la cause.",0,"J",false);
$pdf->MultiCell(90,3,"D�finition de la �� Perte Totale et Irr�versible d�Autonomie (PTIA)���:Un Assur� est consid�r� atteint de Perte Totale et Irr�versible d�Autonomie lorsqu�� la suite d�un accident ou d�une maladie, il est dans l�impossibilit� pr�sente et future de se livrer � une occupation quelconque lui procurant gain ou profit et est dans l�obligation absolue et pr�sum�e d�finitive d�avoir recours � l�assistance d�une tierce personne pour effectuer les actes ordinaires de la vie.",0,"J",false);
$pdf->MultiCell(90,3,"La Perte Totale et Irr�versible d�Autonomie est r�put�e consolid�e :",0,"J",false);
$pdf->MultiCell(90,3,"- si elle est cons�cutive � un accident : � la date � partir de laquelle l��tat de sant� de l�Assur� correspondant � la Perte Totale et Irr�versible  d�Autonomie est reconnu, compte tenu des connaissances scientifiques et m�dicales, comme ne pouvant plus �tre am�lior�.",0,"J",false);
$pdf->MultiCell(90,3,"- si elle est cons�cutive � une maladie : � l�expiration d�un d�lai de deux ans de dur�e continue de l��tat de Perte Totale et Irr�versible d�Autonomie.
La r�alisation de la Perte Totale et Irr�versible d�Autonomie doit �tre �tablie avant le dernier jour du mois au cours duquel l�Assur� atteint son 60�me anniversaire de naissance. Le risque PTIA �tant assimil� au d�c�s, l�Assureur versera par anticipation le montant du capital pr�vu en cas de d�c�s, l�Assur� cessant alors de b�n�ficier de toutes les autres garanties.",0,"J",false);
$pdf->Ln();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(10,5,"ARTICLE 4�: TERRITORIALITE",0,0,"J");$pdf->Ln();
$pdf->SetFont('Arial','',9);
$pdf->MultiCell(90,3,"L'Assureur couvre tous les risques de d�c�s et de  PTIA monde entier et quelle qu'en soit la cause, sous r�serves des exclusions ci-apr�s :.",0,"J",false);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(10,5,"ARTICLE 5�: EXCLUSION",0,0,"J");$pdf->Ln();
$pdf->SetFont('Arial','',9);
$pdf->MultiCell(90,3,"a) Exclusions relatives au risque \"D�c�s\"",0,"J",false);


// Deuxiemme colonne
$pdf->SetXY(105,59);
$pdf->MultiCell(90,3,"- Le suicide conscient et volontaire de l�assur�, au cours des deux premi�res ann�es qui suivent la date d�effet du contrat ou sa remise en vigueur s�il a �t� interrompu. En cas d�augmentation des garanties, le suicide volontaire et conscient est exclu pour le suppl�ment de garanties pendant les deux premi�res ann�es suivant la prise d�effet de cette augmentation,",0,"J",false);
$pdf->SetXY(105,81);
$pdf->MultiCell(90,3,"- Le meurtre par le b�n�ficiaire,",0,"J",false);
$pdf->SetXY(105,84);
$pdf->MultiCell(90,3,"- L�accident a�rien survenu au cours de vols acrobatiques ou d�exhibitions, de comp�titions ou tentatives de record, de vols d�essai ou de vols sur un appareil autre qu�un avion ou un h�licopt�re,",0,"J",false);

$pdf->SetXY(105,96);
$pdf->MultiCell(90,3,"- En cas de guerre �trang�re.",0,"J",false);

$pdf->SetXY(105,99);
$pdf->MultiCell(90,3,"b) Autres �v�nements non garantis",0,"J",false);

$pdf->SetXY(105,102);
$pdf->MultiCell(90,3,"- Fait intentionnel de l�assur� ou du b�n�ficiaire,",0,"J",false);

$pdf->SetXY(105,105);
$pdf->MultiCell(90,3,"- Ivresse manifeste ou alcool�mie, lorsque le taux d�alcool dans le sang est �gal ou sup�rieur � un gramme par litre de sang,",0,"J",false);


$pdf->SetXY(105,114);
$pdf->MultiCell(90,3,"- Usage par l�assur� de drogues ou de stup�fiants non ordonn�s m�dicalement,",0,"J",false);


$pdf->SetXY(105,120);
$pdf->MultiCell(90,3,"- Guerre civile, �meutes ou mouvements populaires, actes de terrorisme ou de sabotage, participation de l�assur� � un duel ou � une rixe (sauf cas de l�gitime d�fense),",0,"J",false);


$pdf->SetXY(105,129);
$pdf->MultiCell(90,3,"- D�sint�gration du noyau atomique ou radiations ionisantes,",0,"J",false);


$pdf->SetXY(105,132);
$pdf->MultiCell(90,3,"- Accident d� � la participation de l�assur�, en qualit� de conducteur ou de passager, � des comp�titions de toute nature entre v�hicules � moteur, et � leurs essais pr�paratoires,",0,"J",false);



$pdf->SetXY(105,144);
$pdf->MultiCell(90,3,"- Des vols sur aile volante, ULM, deltaplane, parachute ascensionnel et parapente",0,"J",false);


$pdf->SetXY(105,150);
$pdf->MultiCell(90,3,"- Pratique par l�assur� d�un sport quelconque, � titre professionnel,",0,"J",false);


$pdf->SetXY(105,156);
$pdf->MultiCell(90,3,"- Les invalidit�s r�sultant de grossesse, fausse-couche, de l�accouchement normal ou pr�matur� ou de ses suites ne seront garanties qu�en cas de complications pathologiques,",0,"J",false);


$pdf->SetXY(105,165);
$pdf->MultiCell(90,3,"Les invalidit�s r�sultant d�affections neuro psychiques (sous toutes leurs formes) ne sont garanties qu�apr�s six mois d�arr�t de travail.",0,"J",false);

$pdf->SetFont('Arial','B',10);
$pdf->SetXY(105,174);
$pdf->Cell(10,5,"ARTICLE 6�: DELAI DE DECLARATION POUR PTIA�",0,0,"J");$pdf->Ln();
$pdf->SetFont('Arial','',9);
$pdf->SetXY(105,179);
$pdf->MultiCell(90,3,"La garantie ne jouera pas, si l�accident ou la maladie ayant caus� la Perte Totale et Irr�versible d�Autonomie n�est pas d�clar�e dans un d�lai de deux (02 mois � compter du jour o� elle aura provoqu� l�invalidit� compl�te.",0,"J",false);

$pdf->SetXY(105,191);
$pdf->MultiCell(90,3,"Lorsque l�invalidit� ne satisfait pas � la d�finition et aux conditions �nonc�es pr�c�demment, elle est r�put�e non avenue et le contrat suit son cours normal.",0,"J",false);



$pdf->SetFont('Arial','B',10);
$pdf->SetXY(105,200);
$pdf->Cell(10,5,"ARTICLE 7�: FONCTIONNEMENT DES GARANTIES",0,0,"J");$pdf->Ln();
$pdf->SetFont('Arial','',9);
$pdf->SetXY(105,205);
$pdf->MultiCell(90,3,"1) Date d�effet des garanties�;",0,"J",false);

$pdf->SetXY(105,208);
$pdf->MultiCell(90,3,"Le pr�sent contrat n�a d�existence qu�apr�s sa signature par les parties contractantes.",0,"J",false);


$pdf->SetXY(105,215);
$pdf->MultiCell(90,3,"Cependant, il ne produira r�ellement ses effets qu�� compter du lendemain � midi du paiement de la premi�re prime et, au plus t�t aux dates et heures indiqu�es aux Conditions Particuli�res.
Ces m�mes dispositions s�appliqueront � tout avenant intervenant en cours de contrat.",0,"J",false);


$pdf->SetXY(105,233);
$pdf->MultiCell(90,3,"2) D�claration de l�assur� et du contractant",0,"J",false);

$pdf->SetXY(105,236);
$pdf->MultiCell(90,3,"La police est r�dig�e et la prime est fix�e exclusivement d�apr�s les d�clarations de l�Assur� et du contractant qui doivent en cons�quence, faire conna�tre � la compagnie toutes les circonstances connues d�eux, qui sont de nature � faire appr�cier les risques qu�elle prend � sa charge.",0,"J",false);

$pdf->SetXY(105,251);
$pdf->MultiCell(90,3,"Le contrat est incontestable d�s qu�il a pris existence, sous r�serves des dispositions des articles 21, 75 et 88 de l�ordonnance n� 95-07 du 25 Janvier 1995 modifi�e et compl�t�e par la loi 06-04 du 20 f�vrier 2006.",0,"J",false);

//3) Expertise�

$pdf->SetXY(105,264);
$pdf->MultiCell(90,3,"3) Expertise�",0,"J",false);
//

$pdf->SetXY(105,267);
$pdf->MultiCell(90,3,"La preuve de Perte Totale et Irr�versible d�Autonomie incombe � ",0,"J",false);

$pdf->AddPage();
$pdf->Ln(8);

$pdf->SetFont('Arial','',9);
$pdf->MultiCell(90,3,"l�Assur� qui doit faire parvenir � la compagnie les pi�ces indiquant si l�invalidit� est cons�cutive � une maladie ou � un accident. La compagnie se r�serve le droit de faire contr�ler l��tat de sant� de l�Assur� par ses m�decins ou par toute autre personne qu�elle d�signera.
En cas de contestation d�ordre purement m�dical, une expertise � frais communs devra intervenir avant tout recours � la voie judiciaire. Chacune des parties d�signera un m�decin, en cas de d�saccord entre eux, ceux-ci s�adjoindront un confr�re de leur choix pour les d�partager, et � d�faut d�entente, la d�signation en sera faite � la requ�te de la partie la plus diligente par le pr�sident du tribunal du domicile de l�Assur�.",0,"J",false);

$pdf->MultiCell(90,3,"Chaque partie r�glera les honoraires de son m�decin, ceux du troisi�me m�decin ainsi que les frais relatifs � sa nomination seront support�s en commun accord et par parts �gales par les deux parties.",0,"J",false);
$pdf->MultiCell(90,3,"4) Changement dans la situation de l�assur� ",0,"J",false);
$pdf->MultiCell(90,3,"Si l�Assur� change de profession en cours de contrat, il est tenu d�en informer la compagnie imm�diatement qui lui donne acte de sa d�claration.",0,"J",false);
$pdf->MultiCell(90,3,"En cas d�aggravation du risque d�invalidit� ou de d�c�s la compagnie d�assurance, peut, proposer un nouveau taux de prime. L'assur� est tenu de s'acquitter de la diff�rence de la prime r�clam�e par l'assureur.
En cas de non-paiement, l'assureur a le droit de r�silier le contrat (Article 18 de l�ordonnance n� 95-07 du 25 Janvier 1995 modifi�e et compl�t�e par la loi 06-04 du 20 f�vrier 2006.)",0,"J",false);
$pdf->MultiCell(90,3,"5) D�claration en cas de changement de domicile",0,"J",false);
$pdf->MultiCell(90,3,"L�assur� devra informer l�assureur par lettre recommand�e de ses changements de domicile. Les lettres adress�es au dernier domicile dont la soci�t� a eu connaissance produiront tous leurs effets.",0,"J",false);


$pdf->SetFont('Arial','B',10);

$pdf->Cell(10,5,"ARTICLE 8�: PRIME",0,0,"J");$pdf->Ln();
$pdf->SetFont('Arial','',9);
$pdf->MultiCell(90,3,"A l�exception de la premi�re, les primes sont payables au domicile du contractant ou � tout autre lieu convenu.
Conform�ment aux articles 16 et 84 de l�ordonnance n� 95-07 du 25 janvier 1995 relative aux assurances, modifi�e et compl�t�e par la loi 06-04 du 20 f�vrier 2006, � d�faut de paiement d�une prime dans les quinze jours qui suivent son �ch�ance (article 16), la garantie est suspendue quarante-cinq jours apr�s l�envoi d�une lettre recommand�e de mise en demeure adress�e au contractant.",0,"J",false);

$pdf->MultiCell(90,3,"Dix jours apr�s l�expiration de ce d�lai, si la prime et les frais de mise en demeure n�ont pas �t� acquitt�s, le contrat sera r�sili� et les primes pay�es restent acquises � la compagnie.",0,"J",false);


$pdf->SetFont('Arial','B',10);

$pdf->Cell(10,5,"ARTICLE 9 : PAIEMENT DES SOMMES DUES ",0,0,"J");$pdf->Ln();
$pdf->SetFont('Arial','',9);
$pdf->MultiCell(90,3,"Le d�c�s de l�Assur� doit �tre notifi� � la compagnie par les ayants droit dans le plus bref d�lai possible. ",0,"J",false);
$pdf->MultiCell(90,3,"Le paiement des sommes assur�es dues est indivisible � l��gard de la compagnie qui r�gle sur la quittance conjointe au niveau de ses structures habilit�es, dans les trente (30) jours qui suivent la remise des pi�ces justificatives, lesquelles comprennent notamment :",0,"J",false);

$pdf->MultiCell(90,3,"1) En cas de D�c�s :",0,"J",false);
$pdf->MultiCell(90,3,"En vue du r�glement, les pi�ces � remettre � l�Assureur doivent notamment comprendre�:�",0,"J",false);
$pdf->MultiCell(90,3,"- L�acte  de naissance et de d�c�s de l�Assur�,",0,"J",false);
$pdf->MultiCell(90,3,"- Un certificat m�dical du m�decin traitant  apportant les pr�cisions sur la maladie ou l�accident � la suite duquel l�Assur� a succomb�,",0,"J",false);
$pdf->MultiCell(90,3,"-Tout document officiel �tabli � la suite du d�c�s,
- Le tableau d�amortissement ou l��ch�ancier initial certifi� conforme � la date du d�c�s par l�organisme pr�teur aupr�s duquel l�op�ration financi�re a �t� souscrite,
- Un courrier de l�organisme pr�teur attestant que l�op�ration financi�re avait normalement cours au jour du d�c�s et qu�il n�est intervenu aucun �v�nement juridique de nature � modifier l�engagement initial de l�Assur�.",0,"J",false);
$pdf->MultiCell(90,3,"2) En cas de PTIA",0,"J",false);
$pdf->MultiCell(90,3,"L�Assur�, ou en cas de force majeure son mandataire autoris�, doit apporter la preuve de son �tat � l�Assureur.
Les pi�ces � remettre en vue du r�glement doivent notamment comprendre�:",0,"J",false);
$pdf->MultiCell(90,3,"- Un certificat m�dical du m�decin traitant apportant les pr�cisions n�cessaires sur la maladie ou l�accident qui est � l�origine de la perte totale et irr�versible d�autonomie, et attestant incapacit� de l�assur� d�exercer la moindre activit�.
- La date � laquelle s�est d�clar�e cette invalidit� absolue et d�finitive,",0,"J",false);


// Deuxiemme colonne
$pdf->SetXY(105,26);
$pdf->MultiCell(90,3,"- Le tableau d�amortissement ou l��ch�ancier certifi� conforme par l�organisme pr�teur aupr�s duquel l�op�ration financi�re a �t� souscrite, � la date � laquelle l�Assur� d�clare son �tat de perte totale et irr�versible d�autonomie � l�Assureur,
- Un courrier de l�organisme pr�teur attestant que l�op�ration financi�re avait normalement cours au jour de l��v�nement et qu�il n�est intervenu aucun fait juridique de nature � modifier l�engagement initial de l�Assur�,
L�Assureur se r�serve le droit de demander toute pi�ce compl�mentaire qu�il juge n�cessaire � l�appr�ciation de l��tat de sant� de l�Assur� et de le soumettre � une expertise m�dicale.
",0,"J",false);
$pdf->SetFont('Arial','B',10);
$pdf->SetXY(105,63);
$pdf->Cell(10,5,"ARTICLE 10 : PRESCRIPTION",0,0,"J");$pdf->Ln();
$pdf->SetFont('Arial','',9);
$pdf->SetXY(105,68);
$pdf->MultiCell(90,3,"Conform�ment aux dispositions de l�article 27 de l�ordonnance n�95-07 du 25 janvier 1995 relative aux assurances, modifi�e et compl�t�e par la loi 06-04 du 20 f�vrier 2006, le d�lai de prescription, pour toute action de l�assur� ou de l�assureur n�e du pr�sent contrat d�assurance, est de trois (03) ans, � partir de l��v�nement qui lui donne naissance.
Toutefois, ce d�lai cesse de courir en cas de r�ticence ou de d�claration fausse ou inexacte sur le risque assur�, que du jour o� l�assureur en a eu connaissance.
La dur�e de la prescription ne peut �tre abr�g�e par accord des deux parties et peut �tre interrompue par:
1) les causes ordinaires d�interruption, telles que d�finies par la loi,
2) la d�signation d�experts,
3) l�envoi d�une lettre recommand�e par l�assureur � l�assur�, en mati�re    de paiement de prime,
4) l�envoi d�une lettre recommand�e par l�assur� � l�assureur, en ce qui concerne le r�glement de l�indemnit�.",0,"J",false);


$pdf->SetFont('Arial','B',8);
$pdf->SetXY(105,122);

//

$pdf->Cell(10,5,"ARTICLE 11:REGLEMENT DES LITIGES,LOI ET TRIBUNAL COMPETENT",0,0,"J");$pdf->Ln();
$pdf->SetFont('Arial','',9);
$pdf->SetXY(105,127);
$pdf->MultiCell(90,3,"Les litiges entre Assur� ou ses ayants-droit et Assureur, seront tranch�s par voie amiable. A d�faut, le recours � la voie judiciaire aura lieu conform�ment � la l�gislation alg�rienne.
La comp�tence reviendra au tribunal de la circonscription territoriale duquel la police a �t� conclue en ce qui concerne les litiges opposant les parties autres que ceux concernant la contestation relative � la fixation et au r�glement des indemnit�s dues.

Ceux inh�rents � ladite contestation sont de la comp�tence du tribunal du domicile de l�Assur� qui peut, toutefois, tout comme les ayants-droit assigner l�Assureur devant le tribunal du lieu du fait g�n�rateur de la prestation.
",0,"J",false);



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
    $pdf->Cell(190,20,'QUITTANCE DE PRIME N�:'.$agence.'/'.substr($rowq['date_quit'],0,4).'/'.$cod_prod.'/'.str_pad((int) $rowq['cod_quit'],'5',"0",STR_PAD_LEFT),'LTR','0','C');$pdf->Ln();

    $pdf->Cell(20,20,'Police N�: ','LB','0','L');
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(70,20,' '.$agence.'.'.$annee.'.10.'.$cod_prod.'.'.$sequence_pol,'B','0','L');
    $pdf->Cell(100,20,'     DU:'.$dat_eff.'               AU'.$dat_ech.'    ','RB','0','R');$pdf->Ln();

    /////////
    $pdf->Ln(2);
    $pdf->Cell(190,6,"SOUSCIPTEUR:" ,'B','0','L');$pdf->Ln();
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(60,6,"Nom,Pr�nom/R.SOCIALE:" ,'L','0','L');
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


    $pdf->Cell(40,5,'D�compte de la prime ','0','0','L','0');
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








