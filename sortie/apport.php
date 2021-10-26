<?php session_start();
require_once("../../../data/conn4.php");
if ($_SESSION['login']){$user=$_SESSION['id_user'];}
else {
    header("Location:../index.html?erreur=login"); // redirection en cas d'echec
}


    include("convert.php");
    require('tfpdf.php');
    class PDF extends TFPDF
    {
// En-t?te
        function Header()
        {
            $this->SetFont('Arial','B',10);
            $this->Image('../img/entete_bna.png',6,4,420);
            $this->Cell(150,5,'','O','0','L');
            $this->SetFont('Arial','B',12);
            // $this->Cell(60,5,'MAPFRE | Assistance','O','0','L');
            $this->SetFont('Arial','B',10);
            $this->Ln(48);
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
            $this->Cell(0,8,"Algerian Gulf Life Insurance Company, SPA au capital social de 1.000.000.000 de dinars algériens, 01 Rue Tripoli, hussein Dey Alger,  ",0,0,'C');
            $this->Ln(2);
            $this->Cell(0,8,"RC : 16/00-1009727 B 15   NIF : 001516100972762-NIS :0015160900296000",0,0,'C');
            $this->Ln(2);
            $this->Cell(0,8,"Tel : +213 (0) 21 77 30 12/14/15 Fax : +213 (0) 21 77 29 56 Site Web : www.aglic.dz  ",0,0,'C');
        }
    }


// Instanciation de la classe derivee
$pdf = new PDF('L','mm',array(297,420));
$pdf->AliasNbPages();
$pdf->AddPage();

$rqtp = $bdd->prepare("SELECT * from agence where typ_agence='2'");
$rqtp->execute();

$pdf->SetFont('Arial','B',24);
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(255,255,255);
$pdf->Cell(420, 8, "Liste des apporteurs d'affaires", '0', '0', 'C');$pdf->Ln(38);


$pdf->SetFont('Arial','B',12);
$pdf->SetFillColor(199,139,85);

$pdf->Cell(150,8,'Nom Apporteur','1','0','L','1');
$pdf->Cell(70,8,'Adresse  ','1','0','L','1');
$pdf->Cell(30,8,'Téléphone  ','1','0','L','1');
$pdf->Cell(40,8,'E-mail  ','1','0','L','1');
$pdf->Cell(50,8,'Nom de responsable  ','1','0','L','1');
$pdf->Cell(50,8,'Prénom de responsable  ','1','0','L','1');$pdf->Ln();

$pdf->SetFont('Arial','',12);
$pdf->SetFillColor(255,255,255);

while ($row=$rqtp->fetch())
{

    $pdf->Cell(150,8,''.$row['lib_agence'],'1','0','L','1');
    $pdf->Cell(70,8,''.$row['adr_agence'],'1','0','L','1');
    $pdf->Cell(30,8,''.$row['tel_agence'],'1','0','L','1');
    $pdf->Cell(40,8,''.$row['mail'],'1','0','L','1');
    $pdf->Cell(50,8,''.$row['nom_rep'],'1','0','L','1');
    $pdf->Cell(50,8,''.$row['prenom_rep'],'1','0','L','1');$pdf->Ln();
}

$pdf->Output();

?>