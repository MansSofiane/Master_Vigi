<?php
session_start();
require_once("../../../data/conn4.php");
if ($_SESSION['login']){
//authentification acceptee !!!


//header("Location: http://www.monsite.com");
}
else {
    header("Location:../index.html?erreur=login"); // redirection en cas d'echec
}
require('tfpdf.php');
class PDF extends TFPDF
{
// En-t?te
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
        $this->Cell(0,8,"Algerian Gulf Life Insurance Company, SPA au capital social de 1.000.000.000 de dinars algériens, 01 Rue Tripoli, Hussein Dey Alger,  ",0,0,'C');
        $this->Ln(2);
        $this->Cell(0,8,"RC : 16/00-1009727 B 15   NIF : 001516100972762-NIS :0015160900296000",0,0,'C');
        $this->Ln(2);
        $this->Cell(0,8,"Tel : +213 (0) 21 77 30 12/14/15 Fax : +213 (0) 21 77 29 56 Site Web : www.aglic.dz  ",0,0,'C');
    }
    /* function RotatedText($x,$y,$txt,$angle)
     {
         //Text rotated around its origin
         $this->Rotate($angle,$x,$y);
         $this->Text($x,$y,$txt);
         $this->Rotate(0);
         $this->Rotate(0);
     }*/

}
if (isset($_REQUEST['warda'])) {$row = substr($_REQUEST['warda'],10);
//on recupere le code du pays
$user = $_SESSION['id_user'];

    $rqtf=$bdd->prepare("SELECT `id_facture`, `sequence`, `dat_facture`, `dat_deb`, `dat_fin`, `mtt_net`, `taux_tva`, `TVA`, `mtt_ttc`, `id_user`, `cod_agence`, `type_facture` FROM `facture` WHERE id_facture='$row'");
    $rqtf->execute();
$sequence="";
    $datdeb="";
    $datfin="";
    $dat_fact="";
    $id_user_gener=$user;
    while ($r=$rqtf->fetch())
    {
        $sequence=$r["sequence"];
        $datdeb=$r["dat_deb"];
        $datfin=$r["dat_fin"];
        $dat_fact=$r["dat_facture"];
        $id_user_gener=$r["id_user"];


    }
   $rqtselect= $bdd->prepare("SELECT f.sequence,l.`id_ligne`, l.`id_facture`, l.`cod_prod`, l.`type_ligne` as agence, l.`dat_ligne`, l.`dat_deb`, l.`dat_fin`, l.`mtt_encaissement` as recouverement, l.`mtt_decaissement` as Ristourne, l.`taux_com` as Taux, l.`mtt_com_net`, l.`id_user`, l.`cod_agence`,p.code_prod,p.lib_prod FROM `ligne_facture` as l,`facture` as f,produit as p  WHERE l.cod_prod=p.cod_prod and  l.id_facture=f.id_facture and  l.id_facture='$row'");

    $rqtselect->execute();




    //_________________________________________________________________

    $datesys = date("Y/m/d");
    include("convert.php");
  //  include("entete.php");
    $a1 = new chiffreEnLettre();



    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',10);
    $pdf->SetFillColor(255,255,255);

    //requete
    $rqtut=$bdd->prepare("select adr_user,nom,prenom,reseau,wilaya,agence  from utilisateurs where id_user='$id_user_gener' LIMIT 0,1");
    $rqtut->execute();

    $adr_user="";
    $nom="";
    $nif="";
    $reseau="";
    $wilaya="";
    $agence="";
    while($rowsu=$rqtut->fetch())
    {
        $adr_user=$rowsu['adr_user'];
        $nom=$rowsu['nom']." ".$rowsu['prenom'];
        $reseau=$rowsu['reseau'];
        $wilaya=$rowsu['wilaya'];
        $agence=$rowsu['agence'];

    }

    $pdf->SetFillColor(231,229,231);
    $pdf->Cell(90,5,"Compagnie : CASH ASSURANCES " ,'0','0','L');
    $pdf->Cell(90,5,$wilaya." Le: ".date("d/m/Y", strtotime($datesys))."" ,'0','1','R');
    $pdf->Ln();
    $pdf->Cell(190,5,"Direction Commerciale & Animation Réseau :".$reseau ,'0','0','L');
    $pdf->Ln();
    $pdf->Cell(30,5,"Code D.R.: ".$agence ,'0','0','L');
    $pdf->Ln();
    $pdf->MultiCell(90,5,"Adresse: ".$adr_user ,0,'L',false);
    $pdf->Cell(30,5,"N.I.F.:" ,'0','0','L');
    $pdf->Ln();
    $pdf->Cell(30,5,"A.I.:" ,'0','0','L');
    $pdf->Ln();
    $pdf->Cell(30,5,"RIB:" ,'0','0','L');
    $pdf->SetXY(130,60);
    $pdf->MultiCell(90,5,"Doit a:
AGLIC Spa
01 Rue Tripoli Hussein Dey Alger " ,0,'L',false);
    $pdf->Ln(15);
    $pdf->SetFillColor(255,255,255);
    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(190,5,"Facture Consolidée  de Commission de Distribution  " ,'0','0','C'); $pdf->Ln(10);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(30,5,"N°:".$agence."/ER/".$sequence."/".substr($dat_fact,0,4) ,'0','0','L');
   // $pdf->SetFillColor(231,229,231);
    $pdf->Cell(60,5,"         Période:" ,'0','0','R');
   // $pdf->SetFillColor(231,229,231);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(100,5,'du         '.date("d/m/Y", strtotime($datdeb)).'             au        '.date("d/m/Y", strtotime($datfin)) ,'0','0','L','1');

    $pdf->Ln(10);
    $pdf->Cell(10,10,"Code" ,'1','0','C','1');$pdf->Cell(50,10,"Désignation" ,'1','0','C','1');$pdf->Cell(40,10,"Prime Commerciale" ,'1','0','C','1');$pdf->Cell(40,10,"Ristourne Commerciale" ,'1','0','L','1');
    $pdf->Cell(10,10,"Taux" ,'1','0','C','1');$pdf->Cell(40,10,"Mt commission" ,'1','0','C','1');
    $pdf->Ln(10);

    //declaration variables
    $montantHT=0;
    $tva09=9;
    $montantTTC=0;
    $montantcomi=0;
    $cod_produit="";
    //----------------------------------------------------------
    //-------corps--------------------------------------
    $pdf->SetFont('Arial','',8);
    while ($rw=$rqtselect->fetch())
    {

        $pdf->Cell(10, 10, $rw["code_prod"], '1', '0', 'C', '1');
        $pdf->Cell(50,10,$rw["lib_prod"]."     ".$rw["agence"] ,'1','0','L','1');
        $pdf->Cell(40,10,"".number_format($rw["recouverement"], 2,',',' ') ,'1','0','R','1');
        $pdf->Cell(40,10,"".number_format($rw["Ristourne"], 2,',',' '),'1','0','R','1');
        $pdf->Cell(10,10,"".number_format($rw["Taux"] , 0,',',' ')."%",'1','0','C','1');

        $montantcomi=($rw["recouverement"]-$rw["Ristourne"])*$rw["Taux" ]/100;

        $pdf->Cell(40,10,"".number_format($montantcomi, 2,',',' '),'1','0','R','1');
        $pdf->Ln(10);


        $montantHT+=$montantcomi;
    }

    //----------------------fin corps

    //-----------------------------------------------------------------------
    $y=$pdf->GetY();
    $pdf->SetXY(70,$y);
    $pdf->Cell(90,7,"Montant HT" ,'1','0','L','1'); $pdf->Cell(40,7,"".number_format($montantHT, 2,',',' ') ,'1','0','R');
    $pdf->Ln();
    $y=$pdf->GetY();
    $pdf->SetXY(70,$y);
    $pdf->Cell(90,7,"TVA 09% " ,'1','0','L','1'); $pdf->Cell(40,7,"".number_format(($montantHT*($tva09/100)), 2,',',' ') ,'1','0','R');
    $pdf->Ln();
    $y=$pdf->GetY();
    $pdf->SetXY(70,$y);
    //$MontantTTC=$montantHT+$MontantTVA;
    $MontantTTC=$montantHT+($montantHT*($tva09/100));
    $pdf->Cell(90,7,"Montant TTC" ,'1','0','L','1'); $pdf->Cell(40,7,"".number_format($MontantTTC, 2,',',' ') ,'1','0','R');
    $pdf->Ln(15);


    $somme=$a1->ConvNumberLetter("".$MontantTTC."",1,0);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(170,5,"Arretée la présente facture de commission à la somme de:",'0','0','L');$pdf->Ln();
    $pdf->SetFont('Arial','B',12);$pdf->SetFillColor(255,255,255);
    $pdf->MultiCell(190,12,"".$somme."",1,'C',true);
    $pdf->Ln(15);
    $y=$pdf->GetY();
    $pdf->SetXY(100,$y);
    $pdf->MultiCell(100,5,"Le Directeur ",0,'C',false);
    $pdf->Output();
}
?>


