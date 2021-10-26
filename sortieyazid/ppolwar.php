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
        $this->Ln(14);
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

$pdf->SetFont('Arial','B',12);
//Requete generale
$rqtg=$bdd->prepare("SELECT d.*,t.`mtt_dt`,c.`mtt_cpl`,o.`lib_opt`,p.`code_prod`, s.`nom_sous`, s.`pnom_sous`, s.`mail_sous`, s.`tel_sous`, s.`adr_sous`, s.`rp_sous`,s.`dnais_sous`,s.`age`,s.id_user  FROM `policew` as d, `dtimbre` as t , `cpolice` as c,`option` as o,`produit` as p,`souscripteurw` as s  WHERE d.`cod_dt`=t.`cod_dt` AND d.`cod_cpl`=c.`cod_cpl` AND d.`cod_opt`=o.`cod_opt` AND d.`cod_prod`=p.`cod_prod` AND d.`cod_sous`=s.`cod_sous` AND d.`cod_pol`='$row'");
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
    $pdf->Cell(190,8,'Assurance Cancer du Sein','0','0','C');$pdf->Ln();
    $user_creat=$row_g['id_user'];

    $rqtu = $bdd->prepare("select * from utilisateurs where  id_user ='$user_creat';");
    $rqtu->execute();
    while ($row_user=$rqtu->fetch()){
        $pdf->Cell(190,8,'Police N� '.$row_user['agence'].'.'.substr($row_g['dat_val'],0,4).'.10.'.$row_g['code_prod'].'.'.str_pad((int) $row_g['sequence'],'5',"0",STR_PAD_LEFT).'','0','0','C');$pdf->Ln();$pdf->Ln();
        $pdf->Ln(9);
//$pdf->Cell(190,8,'Devis Gratuit','0','0','C');$pdf->Ln();$pdf->Ln();

        $pdf->SetFont('Arial','',9);
        $pdf->MultiCell(190,3,"Le pr�sent Contrat est r�gi tant par les dispositions de l�ordonnance 95/07 du 25 Janvier 1995 modifi�e et compl�t�e par la Loi N� 06 - 04 du 20 F�vrier 2006 que part les Conditions G�n�rales et les Conditions Particuli�res. En cas d�incompatibilit� entre les Conditions G�n�rales et  Particuli�res,  les Conditions Particuli�res  Pr�valent  toujours  sur   les   Conditions   G�n�rales.  ",0,"J",false);
        $pdf->SetFont('Arial','B',14);
        $pdf->Ln(3);

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
    $pdf->Cell(40,5,'Capital','1','0','L','1');$pdf->Cell(150,5,"".$row_g['lib_opt']."",'1','0','C');$pdf->Ln();
    $pdf->Cell(40,5,'Effet le','1','0','L','1');$pdf->Cell(55,5,"".date("d/m/Y", strtotime($row_g['dat_eff']))."",'1','0','C');
    $pdf->Cell(40,5,'Ech�ance le','1','0','L','1');$pdf->Cell(55,5,"".date("d/m/Y", strtotime($row_g['dat_ech']))."",'1','0','C');$pdf->Ln();
    $pdf->Cell(40,5,'Garantie','1','0','L','1');$pdf->Cell(150,5,"cancer du sein invasif ",'1','0','C');$pdf->Ln();

    $pdf->Ln(7);
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(40,5,'D�compte de la prime ','0','0','L','0');
//
    $pdf->Ln(7);
    $pdf->SetFont('Arial','B',8);
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
    $pdf->Cell(0,6,"Le Souscripteur reconnait que les pr�sentes Conditions Particuli�res ont �t� �tablies conform�ment aux renseignements qu'il a donn� lors de la souscription du Contrat.",0,0,'C');$pdf->Ln(2);
    $pdf->Cell(0,6,"Le Souscripteur reconnait �galement avoir �t� inform� du contenu des Conditions Particuli�res et des Conditions G�n�rales et avoir �t� inform� du montant de la prime et des garanties d�es.",0,0,'C');
    $pdf->Ln(9);
    $somme=$a1->ConvNumberLetter("".$row_g['pt']."",1,0);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(30,5,"Le montant � payer en lettres",'0','0','L');$pdf->Ln();
    $pdf->SetFont('Arial','B',12);$pdf->SetFillColor(255,255,255);
    $pdf->MultiCell(190,12,"".$somme."",1,'C',true);

    $pdf->Ln(9);


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


$pdf->AddPage();

$pdf->SetFont('Arial','B',12);
$pdf->SetFillColor(205,205,205);
//$pdf->Ln(2);Notice d'information
//$pdf->Image('../img/Notice_information.png',0,0,210,297);


$pdf->Cell(190,8,"CONDITION GENERALE",'0','0','C');
$pdf->Ln();
$pdf->SetFont('Arial','B',7);
$pdf->Cell(190,3,"Visa N� 01 du 22 Octore 2017/Direction des assurances DS/MF",'0','0','C');
$pdf->Ln();
$pdf->SetFont('Arial','B',6);
$pdf->Cell(10,3,"I. CADRE JURIDIQUE :",0,0,"J");$pdf->Ln();
$pdf->SetFont('Arial','',6);
$pdf->MultiCell(90,3,"Les pr�sentes conditions g�n�rales sont r�gies tant par l�ordonnance N� 75-58 du 26 Septembre 1975 portant code civile modifi�e et compl�t�e et par l�ordonnance N� 95-07 du 25 janvier 1995 relative aux assurances modifi�e et compl�t�e par la loi N� 06-04 du 20 f�vrier 2006 que par le d�cret ex�cutif N� 02-293 du 10 Septembre 2002 modifiant et compl�tant le d�cret ex�cutif N� 95-338 du 30 Octobre 1995 relatif � l��tablissement et � la codification des op�rations d�assurance.",0,"J",false);
//$pdf->Ln();
$pdf->SetFont('Arial','B',6);
$pdf->Cell(10,3,"TITRE I : DISPOSITIONS GENERALES ET GARANTIES",0,0,"J");$pdf->Ln();
$pdf->Cell(10,3,"ARTICLE 1 : DEFINITIONS",0,0,"J");$pdf->Ln();
$pdf->SetFont('Arial','B',6);
$pdf->Cell(10,3,"Assur�e :",0,0,"J");
$pdf->SetFont('Arial','',6);
$pdf->Cell(80,3," On entend par � Assur�e � toute femme alg�rienne r�sidente en Alg�rie,",0,0,"J");$pdf->Ln();
$pdf->SetFont('Arial','',6);
$pdf->MultiCell(90,3," d�sign�e sous ce nom aux conditions particuli�res, �g�e entre 18 et 60 ans au jour d�entr� en couverture et ne d�passant pas 65 ans � la sortie de couverture.",0,"J",false);
//$pdf->Ln();
$pdf->SetFont('Arial','B',6);
$pdf->Cell(10,3,"Assureur :",0,0,"J");
$pdf->SetFont('Arial','',6);
$pdf->Cell(75,3,"Par � Assureur �, on entend, la compagnie d�assurances de personnes � Algerian ",0,0,"J");$pdf->Ln();
$pdf->MultiCell(90,3," Gulf Life Insurance Company � dont le nom commercial est �L�ALGERIENNE VIE� d�tenant un capital social de 1 000 000 000 DA, sise � 01, Rue tripoli Hussein dey � Alger.",0,"J",false);
//$pdf->Ln();
$pdf->SetFont('Arial','B',6);
$pdf->Cell(15,3,"Souscripteur : ",0,0,"J");
$pdf->SetFont('Arial','',6);
$pdf->Cell(75,3,"la personne d�sign�e sous ce nom aux conditions particuli�res, ou toute",0,0,"J");$pdf->Ln();
$pdf->SetFont('Arial','',6);
$pdf->MultiCell(90,3," personne qui lui serait substitu�e par accord des parties, qui souscrit le contrat pour le compte de l�assur�.",0,"J",false);
//$pdf->Ln();
$pdf->SetFont('Arial','B',6);
$pdf->Cell(15,3,"B�n�ficiaires :",0,0,"J");
$pdf->SetFont('Arial','',6);
$pdf->MultiCell(65,3,"l�assur�e lui-m�me.",0,"J",false);
//$pdf->Ln();
$pdf->SetFont('Arial','B',6);
$pdf->Cell(10,3,"Diagnostic : ",0,0,"J");
$pdf->SetFont('Arial','',6);
$pdf->Cell(70,3,"regroupe l'ensemble des examens pratiqu�s par un professionnel de sant�",0,0,"J");$pdf->Ln();
$pdf->SetFont('Arial','',6);
$pdf->MultiCell(90,3," pour comprendre la pathologie dont souffre un patient.",0,"J",false);
//$pdf->Ln();
$pdf->SetFont('Arial','B',6);
$pdf->Cell(20,3,"P�riode de carence : ",0,0,"J");
$pdf->SetFont('Arial','',6);
$pdf->Cell(70,3,"P�riode de 90 jours qui suit la date d�effet pendant laquelle l�assur�",0,0,"J");$pdf->Ln();
$pdf->SetFont('Arial','',6);
$pdf->MultiCell(90,3," n�est pas couvert et qui ne donne pas droit au paiement de tout ou partie du capital.",0,"J",false);
//$pdf->Ln();
$pdf->SetFont('Arial','B',6);
$pdf->Cell(17,3,"Dur�e de survie: ",0,0,"J");
$pdf->SetFont('Arial','',6);
$pdf->Cell(73,3,"la p�riode allant de la date du premier diagnostic du cancer, jusqu�au",0,0,"J");$pdf->Ln();
$pdf->SetFont('Arial','',6);
$pdf->MultiCell(90,3," 28�me jour. La personne assur�e doit �tre en vie � la fin de cette p�riode et ne doit pas avoir subi la cessation irr�versible de toutes les fonctions du cerveau.",0,"J",false);
//$pdf->Ln();
$pdf->SetFont('Arial','B',6);
$pdf->Cell(10,3,"ARTICLE 2 : OBJET DU CONTRAT",0,0,"J");$pdf->Ln();
$pdf->SetFont('Arial','',5);
$pdf->MultiCell(90,3,"Le pr�sent contrat a pour objet de garantir aux assur�es, le versement d�un capital forfaitaire, indiqu� dans les conditions particuli�res, en cas de diagnostic d�un cancer du sein avant le terme du contrat. ",0,"J",false);
//$pdf->Ln();
$pdf->SetFont('Arial','B',6);
$pdf->Cell(10,3,"ARTICLE 3 : GARANTIE",0,0,"J");$pdf->Ln();
$pdf->SetFont('Arial','',5);
$pdf->MultiCell(90,3,"Cancer du sein Toute affection maligne des seins caract�ris�e par une croissance et une propagation incontr�l�es de cellules malignes envahissant un tissu se pr�sentant sous un aspect histologique diff�rent.Le diagnostic  doit �tre confirm� de mani�re histologique.Cette d�finition ne couvre pas e qui suit :
- Cancer in situ ;
- Toutes affections malignes de la peau ;
- Le t�moignage de cellules canc�reuses ou mat�riel g�n�tique de cancer d�tecter par des investigations (sondes) mol�culaires ou biochimiques (incluant mais sans se limiter au prot�omique  ou ADN/ ARN, base technique) sans l�sions susceptible aux tissus.Par souci de clarification, toute l�sion d�crite ou classifi�e comme suit n'est pas consid�r�e comme un cancer dans le cadre de la d�finition pr�cit�e :
- pr� maligne (suivant la d�claration du m�decin)
- non invasive (suivant la d�claration du m�decin)
Aucune prestation ne sera due lorsque la date d�apparition des sympt�mes ou la date de survenance ou de diagnostic de la maladie a lieu durant la p�riode de carence. La p�riode de carence se renouvelle apr�s chaque remise en vigueur de la garantie due � une cessation ou non-renouvellement de la police.La souscription est conditionn�e par la r�ponse � un questionnaire m�dical et soumise �ventuellement � une s�lection m�dicale.",0,"J",false);
//$pdf->Ln();
$pdf->SetFont('Arial','B',6);
$pdf->Cell(10,3,"TITRE II : EXCLUSIONS",0,0,"J");$pdf->Ln();
$pdf->Cell(10,3,"ARTICLE 1 : EXCLUSIONS GENERALES",0,0,"J");$pdf->Ln();
$pdf->SetFont('Arial','',5);
$pdf->MultiCell(90,3,"L�assureur ne sera pas responsable et ne versera aucune indemnisation sur le fondement de la pr�sente police en relation directe ou indirecte ou r�sultant de l�une des circonstances suivantes :
Les suites et cons�quences de l'usage de stup�fiants ou de drogues non prescrits m�dicalement ;
Les cons�quences du VIH, HTVL et du SIDA.",0,"J",false);
//$pdf->Ln();
$pdf->SetFont('Arial','B',6);
$pdf->Cell(10,3,"ARTICLE 2 : EXCLUSIONS SPECIFIQUES, AFFECTATIONS PREEXISTANTES",0,0,"J");$pdf->Ln();
$pdf->SetFont('Arial','',6);
$pdf->MultiCell(90,3,"Le capital n�est vers� que lors du premier diagnostic d�un cancer Le capital n�est pas vers� si la premi�re constatation m�dicale est ant�rieure � la date de prise d�effet de la garantie et dont l�assur� a eu connaissance.Cette garantie ne couvre pas ce qui suit :
Cancer in situ ;
Toutes affections malignes de la peau ;
Le t�moignage de cellules canc�reuses ou mat�riel g�n�tique de cancer d�tect� par des investigations (sondes) mol�culaires ou biochimiques (incluant mais sans se limiter au prot�omique ou ADN/ARN, base technique) sans l�sions susceptible aux tissus.Par souci de clarification, toute l�sion d�crite ou classifi�e comme suit n�est pas consid�r�e comme un cancer dans le cadre de la d�finition pr�cit�e :
- pr� maligne (suivant la d�claration du m�decin)
- non invasive (suivant la d�claration du m�decin)
Aucune prestation ne sera due lorsque la date d�apparition des sympt�mes � la date de survenance ou de diagnostic de la maladie a lieu durant la p�riode de carence.La p�riode de carence se renouvelle apr�s chaque remise en vigueur de la garantie due � une cessation ou non-renouvellement de la police.",0,"J",false);
//$pdf->Ln();
$pdf->SetFont('Arial','B',6);
$pdf->Cell(10,3,"TITRE III : FONCTIONNEMENT DES GARANTIES",0,0,"J");$pdf->Ln();
$pdf->Cell(10,3,"ARTICLE 1 : DELAI DE CARENCE",0,0,"J");$pdf->Ln();
$pdf->SetFont('Arial','',6);
$pdf->MultiCell(90,3,"Aucune prestation n�est vers�e si les premiers sympt�mes   apparaissent ou si la maladie cancer du sein survient ou est diagnostiqu�e pour la premi�re fois dans les 90 jours suivant la date d��tablissement de la couverture ou la date d�une �ventuelle remise en vigueur.",0,"J",false);
//$pdf->Ln();
$pdf->SetFont('Arial','',6);
$pdf->MultiCell(90,3,"Dans l��ventualit� d�une augmentation du montant de la prestation cancer du sein,  le m�me d�lai de carence s�applique pour ce qui est du montant accru, sauf mention contraire dans les conditions particuli�res.",0,"J",false);
//$pdf->Ln();
$pdf->SetFont('Arial','B',6);
$pdf->Cell(30,3,"ARTICLE 2 : DUREE DE SURVIE",0,0,"J");$pdf->Ln();
$pdf->SetFont('Arial','',6);
$pdf->MultiCell(90,3,"Aucune prestation n�est vers�e en cas de d�c�s dans les 28 jours suivant la date � laquelle l�assur� r�pond � la d�finition d�un cancer du sein.  ",0,"J",false);
//$pdf->Ln();
//DEUXIEMME COLONNE
$pdf->SetXY(105,35);
$pdf->SetFont('Arial','B',6);
$pdf->Cell(50,3,"ARTICLE 3 : DECLARATION DE RISQUE",0,0,"J");$pdf->Ln();
$pdf->SetXY(105,38);
$pdf->SetFont('Arial','',6);
$pdf->MultiCell(90,3,"La police est r�dig�e et la prime est fix�e exclusivement d'apr�s les d�clarations de l�assur�e et du souscripteur qui doivent en cons�quence, faire connaitre � la compagnie toutes les circonstances connues de l�assur�e notamment son l��tat de sant� et ses ant�c�dents familiaux, qui sont de nature � faire appr�cier les risques qu'elle prend � sa charge.Le contrat est incontestable d�s qu�il a pris existence, sous r�serves des dispositions des articles 21, 75 et 88 de l�ordonnance N� 95-07 du 25 janvier 1995 modifi�e et compl�t�e par la loi 06-04 du 20 f�vrier 2006.",0,"J",false);
//$pdf->Ln();
$pdf->SetXY(105,59);
$pdf->SetFont('Arial','B',6);
$pdf->Cell(30,3,"ARTICLE 4 : EFFET DU CONTRAT",0,0,"J");$pdf->Ln();
$pdf->SetFont('Arial','',6);
$pdf->SetXY(105,62);
$pdf->MultiCell(90,3,"Le pr�sent contrat n�a d�existence et d�effet  qu�apr�s sa signature par les parties contractantes.Cependant, il ne produira r�ellement ses effets qu�aux dates et heures indiqu�s aux conditions particuli�res ou le lendemain � midi de la date de paiement de la premi�re prime.Ces m�mes dispositions s�appliqueront � tout avenant intervenant au cours de contrat.
La garantie cesse de plein droit :
- A 0h00 le jour ou l�assur� atteint son soixantaine cinqui�me (65�me) anniversaire ;
- Au premier diagnostic d�un cancer du sein ;
- Au d�c�s de l�assur� ;
- L�assur� annule la police ;
- A la fin de la p�riode pour laquelle les primes ont �t� vers�es pour les prestations couvertes.",0,"J",false);
$pdf->Ln();
$pdf->SetXY(105,92);
$pdf->SetFont('Arial','B',5);
$pdf->Cell(50,3,"ARTICLE 5 : TERRITOARIALITE",0,0,"J");$pdf->Ln();
$pdf->SetFont('Arial','',6);
$pdf->SetXY(105,94);
$pdf->MultiCell(90,3,"La couverture est valide dans le monde entier � condition que l�assur�e ait sa r�sidence permanente en Alg�rie.",0,"J",false);
//$pdf->Ln();
$pdf->SetXY(105,100);
$pdf->SetFont('Arial','B',6);
$pdf->Cell(50,3,"ARTICLE 6 : PAIEMENT DES PRIMES",0,0,"J");$pdf->Ln();
$pdf->SetFont('Arial','',6);
$pdf->SetXY(105,103);
$pdf->MultiCell(90,3,"A l�exception de la prime, les primes sont payables au domicile du contractant ou � tout autre lieu convenu. Conform�ment aux articles 16 et  84 de l�ordonnance n� 95?07 du 25 janvier 1995 relative aux assurances, modifi�e et compl�t�e par la loi 06?04 du 20 f�vrier 2006, � d�faut de paiement d'une prime dans les quinze jours  qui suivent  son �ch�ance  (article 16), la garantie est sus? pendue quarante?cinq jours apr�s l�envoi d'une lettre recommand�e de mise en demeure adress�e au contractant.Dix (10)  jours  apr�s l�expiration   de ce d�lai, si la prime et les frais de mise en demeure n�ont pas �t� acquitt�s, le contrat sera r�sili� et les primes pay�es restent acquises � la compagnie.",0,"J",false);
//$pdf->Ln();
$pdf->SetXY(105,127);
$pdf->SetFont('Arial','B',6);
$pdf->Cell(50,3,"ARTICLE 7 : RESILIATION DU CONTRAT",0,0,"J");$pdf->Ln();
$pdf->SetFont('Arial','',6);
$pdf->SetXY(105,130);
$pdf->MultiCell(90,3,"L�assur�e a la facult� de de r�silier de plein droit son contrat par lettre recommand�e avec accus� de r�ception adress�e deux (02) mois avant la date d��ch�ance du contrat.",0,"J",false);
$pdf->Ln();
$pdf->SetXY(105,136);
$pdf->SetFont('Arial','B',6);
$pdf->Cell(50,3,"TITRE IV : REGLEMENT DES SINISTRES",0,0,"J");$pdf->Ln();
$pdf->SetXY(105,139);
$pdf->SetFont('Arial','B',6);
$pdf->Cell(50,3,"ARTICLE 1 : DECLARATION DE SINISTRE",0,0,"J");$pdf->Ln();
$pdf->SetXY(105,142);
$pdf->SetFont('Arial','',6);
$pdf->MultiCell(90,3,"L�assur�e, ou toute autre personne mandat�e par cette derni�re  devront aviser par tout moyen �crit l�assureur d�s diagnostic du cancer du sein garanti.",0,"J",false);
$pdf->Ln();
$pdf->SetXY(105,147);
$pdf->SetFont('Arial','B',6);
$pdf->Cell(50,3,"ARTICLE 2 : DELAI DE DECLARATION",0,0,"J");$pdf->Ln();
$pdf->SetXY(105,149);
$pdf->SetFont('Arial','',6);
$pdf->MultiCell(90,3,"Toute d�claration d�un cancer du sein invasif doit �tre transmise � l�assureur au maximum trente (30) jours � compter de la date � laquelle l�assur� a �t� diagnostiqu� malade.Les informations n�cessaires � la d�claration :
- Date d�apparition de la maladie
- Date de prise de connaissance de l�assur� de la maladie.
- Ant�c�dent familial et l�ant�c�dent m�dical
- Facteur de risque
- Police avec d�autre compagnie",0,"J",false);
//$pdf->Ln();
$pdf->SetXY(105,173);
$pdf->SetFont('Arial','B',6);
$pdf->Cell(90,3,"ARTICLE 3 : DOSSIER A FOURNIR EN CAS DE DIAGNOSTIC D�UN CANCER DU SEIN",0,0,"J");$pdf->Ln();
$pdf->SetXY(105,175);
$pdf->SetFont('Arial','',6);
$pdf->MultiCell(90,3,"En cas de sinistre, le dossier doit comprendre les pi�ces suivantes :
- D�claration du sinistre ;
- L�original du contrat pr�cisant les garanties ;
- Acte de naissance de l�assur� ou pi�ce d�identit� ;
- Rapport du m�decin traitant donnant le pronostique ;
- Compte rendu dat� et sign� de l�examen ayant permis de confirmer le diagnostic ;
- Rapport des examens m�dicaux ;
- Rapports m�dicaux compl�mentaires et toute autre document que l�assureur jugera utile ;
- Ainsi que tous autres documents jug�s n�cessaire par l�assureur.Il est important de noter que le nom de l�assur�, le pr�nom et la date d��tablissement de l�examen doivent apparaitre sur tous les rapports et comptes rendus m�dicaux.",0,"J",false);

$pdf->SetXY(105,208);
$pdf->SetFont('Arial','B',6);
$pdf->Cell(50,3,"ARTICLE 4 : PAIEMENT DES GARANTIES",0,0,"J");$pdf->Ln();
$pdf->SetXY(105,210);
$pdf->SetFont('Arial','',6);
$pdf->MultiCell(90,3,"Les prestations seront vers�es lorsque le risque objet de la garantie se r�alise, le montant de la prestation est indiqu� aux conditions particuli�res.Le r�glement des prestations sera effectu� dans un d�lai de trente (30) jours, � partir de la date de la remise de la derni�re pi�ce justificative n�cessaire.",0,"J",false);
//$pdf->Ln();
$pdf->SetXY(105,222);
$pdf->SetFont('Arial','B',5);
$pdf->Cell(50,3,"TITRE V : PRESCRIPTION ET REGLEMENT DE LITIGE",0,0,"J");$pdf->Ln();
$pdf->SetXY(105,224);
$pdf->SetFont('Arial','B',5);
$pdf->Cell(50,3,"ARTICLE 1 : PRESCRIPTION",0,0,"J");$pdf->Ln();
$pdf->SetXY(105,226);
$pdf->SetFont('Arial','',5);
$pdf->MultiCell(90,3,"Conform�rent aux  dispositions de l'article 27 de l�ordonnance n�95?07 du 25 janvier 1995 relative aux assurances, modifi�e et compl�t�e par la loi 06?04 du 20 f�vrier 2006,le d�lai de prescription,pour toute action de l'assure ou de l�assureur n�e du pr�sent contrat d'assurance, est de trois (03) ans, � partir de l��v�nement qui lui donne naissance.Toutefois, ce d�lai cesse de courir en cas de r�ticence ou de d�claration fausse ou inexacte sur le risque assur�, que du jour ou l�assureur en a eu connaissance.La dur�e de la prescription  ne peut �tre  abr�g�e par accord des deux parties et peut �tre interrompue  par:
- Les causes ordinaires d�interruption, telles que d�finies par la loi,
- La d�signation d�experts,
- L�envoi d�une lettre recommand�e  par L�assureur � l�assure, en mati�re de paiement de prime,
- L�envoi d�une lettre recommand�e par l�'assur� � L�assureur, en ce qui concerne le r�glement de l'indemnit�.",0,"J",false);

$pdf->SetXY(105,255);
$pdf->SetFont('Arial','B',5);
$pdf->Cell(60,3,"ARTICLE 2 : REGLEMENT DE LITIGE, LOI ET TRIBUNAL COMPETANT",0,0,"J");$pdf->Ln();
$pdf->SetXY(105,257);
$pdf->SetFont('Arial','',5);
$pdf->MultiCell(90,3,"Les litiges entre assur� ou ses ayants droit et assureur seront tranch�s par voie aimable.A d�faut, le d�faut le recours � la voie judiciaire aura lieu conform�ment � la l�gislation alg�rienne.La comp�tence   reviendra au tribunal   de la circonscription  territoriale    duquel la police a �t� conclue en ce qui concerne   les litiges opposant   les parties autres que ceux concernant  la contestation relative � la fixation et au r�glement des indemnit�s dues.Ceux inh�rent � ladite contestation sont de la comp�tence de tribunal du domicile de l�assur� qui peut assigner l�assureur devant le tribunal du lieu du fait g�n�rateur de la prestation .",0,"J",false);


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








