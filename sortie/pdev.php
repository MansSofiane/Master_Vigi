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

if (isset($_REQUEST['individuel'])) {
$row = $row = substr($_REQUEST['individuel'],10);


}
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

    /* function Footer()
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
         }*/
    function RotatedText($x,$y,$txt,$angle)
    {
        //Text rotated around its origin
        $this->Rotate($angle,$x,$y);
        $this->Text($x,$y,$txt);
        $this->Rotate(0);
        $this->Rotate(0);
    }

}
//Preparation du PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
//Les requetes *****************

//Requete Souscripteur
$query_sous = $bdd->prepare("SELECT s.*, d.*,p.lib_pays,p.cod_zone,o.cod_opt,o.lib_opt  FROM `souscripteurw` as s, `devisw` as d, `pays` as p, `option` as o WHERE s.cod_sous=d.cod_sous and d.cod_pays=p.cod_pays  and d.cod_opt=o.cod_opt and d.cod_dev='".$row."';");
$query_sous->execute();


$pdf->SetFont('Arial','B',12);
//$pdf->Ln(2);
$pdf->SetFillColor(205,205,205);
while($row_sous=$query_sous->fetch()) {
    if ($row_sous['cod_opt'] == 30 ||$row_sous['cod_opt']==31 ) {

        $pdf->Cell(190, 8, 'Assurance Voyage HADJ-OMRA', '0', '0', 'C');
        $pdf->Ln();
    } else {
        $pdf->Cell(190, 8, 'Assurance Voyage et Assistance', '0', '0', 'C');
        $pdf->Ln();
    }
    $pdf->Cell(190, 8, "Devis Gratuit N�:".$row."", '0', '0', 'C');
    $pdf->Ln();
    $pdf->Ln();
    $pdf->SetFont('Arial', 'B', 14);
//$pdf->Ln(2);
    $pdf->SetFillColor(7, 27, 81);
    $pdf->SetTextColor(255, 255, 255);
    $user_creat=$row_sous['id_user'];

    $query_ann = $bdd->prepare("select * from utilisateurs where  id_user ='$user_creat';");
    $query_ann->execute();
//Le R�seau
    while($row_user=$query_ann->fetch()) {
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(190, 5, "Agence", '1', '1', 'C', '1');
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFillColor(221, 221, 221);
        $pdf->Cell(40, 5, 'Code', '1', '0', 'L', '1');
        $pdf->Cell(55, 5, "" . $row_user['agence'] . "", '1', '0', 'C');
        $pdf->Cell(40, 5, 'Adresse', '1', '0', 'L', '1');
        $pdf->Cell(55, 5, "" . $row_user['adr_user'] . "", '1', '0', 'C');
        $pdf->Ln();
        $pdf->Cell(40, 5, 'T�l�phone', '1', '0', 'L', '1');
        $pdf->Cell(55, 5, "" . $row_user['tel_user'] . "", '1', '0', 'C');
        $pdf->Cell(40, 5, 'E-mail', '1', '0', 'L', '1');
        $pdf->Cell(55, 5, "" . $row_user['mail_user'] . "", '1', '0', 'C');
        $pdf->Ln();

        $pdf->Ln(3);
    }
// Le Souscripteur
    $pdf->SetFillColor(199, 139, 85);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(190, 5, 'Souscripteur ', '1', '1', 'C', '1');
    $pdf->SetFillColor(221, 221, 221);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(40, 5, 'Nom et Pr�nom/ R.Sociale', '1', '0', 'L', '1');

        $pdf->Cell(150, 5, "" . $row_sous['nom_sous'] . " " . $row_sous['pnom_sous'] . "", '1', '0', 'C');
        $pdf->Ln();

    $pdf->Cell(40, 5, 'Adresse', '1', '0', 'L', '1');
    $pdf->Cell(150, 5, "" . $row_sous['adr_sous'] . "", '1', '0', 'C');
    $pdf->Ln();
    $pdf->Cell(40, 5, 'T�l�phone', '1', '0', 'L', '1');
    $pdf->Cell(55, 5, "" . $row_sous['tel_sous'] . "", '1', '0', 'C');
    $pdf->Cell(40, 5, 'E-mail', '1', '0', 'L', '1');
    $pdf->Cell(55, 5, "" . $row_sous['mail_sous'] . "", '1', '0', 'C');
    $pdf->Ln();
    $pdf->Ln(3);
// L'assur�
    $pdf->SetFillColor(7, 27, 81);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(190, 5, 'Assur� ', '1', '1', 'C', '1');
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFillColor(221, 221, 221);
    $pdf->SetFont('Arial', 'B', 8);
// la condition sur le souscripteur est l'assure
    if ($row_sous['rp_sous'] == '1') {
        $pdf->Cell(40, 5, 'Nom et Pr�nom', '1', '0', 'L', '1');
        $pdf->Cell(150, 5, "" . $row_sous['nom_sous'] . " " . $row_sous['pnom_sous'] . "", '1', '0', 'C');
        $pdf->Ln();
        $pdf->Cell(40, 5, 'Adresse', '1', '0', 'L', '1');
        $pdf->Cell(150, 5, "" . $row_sous['adr_sous'] . "", '1', '0', 'C');
        $pdf->Ln();
        $pdf->Cell(40, 5, 'T�l�phone', '1', '0', 'L', '1');
        $pdf->Cell(55, 5, "" . $row_sous['tel_sous'] . "", '1', '0', 'C');
        $pdf->Cell(40, 5, 'E-mail', '1', '0', 'L', '1');
        $pdf->Cell(55, 5, "" . $row_sous['mail_sous'] . "", '1', '0', 'C');
        $pdf->Ln();
        $pdf->Cell(40, 5, 'D.Naissance', '1', '0', 'L', '1');
        $pdf->Cell(55, 5, "" . date("d/m/Y", strtotime($row_sous['dnais_sous'])) . "", '1', '0', 'C');
        $pdf->Cell(40, 5, 'N� Passeport', '1', '0', 'L', '1');
        $pdf->Cell(55, 5, "" . $row_sous['passport'] . "", '1', '0', 'C');
        $pdf->Ln();
    }
    else {
        $query_assu = $bdd->prepare("SELECT * FROM `souscripteurw` WHERE cod_par='" . $row_sous['cod_sous'] . "';");
        $query_assu->execute();
        while ($row_assu = $query_assu->fetch()) {
            $pdf->Cell(40, 5, 'Nom et Pr�nom', '1', '0', 'L', '1');
            $pdf->Cell(150, 5, "" . $row_assu['nom_sous'] . " " . $row_assu['pnom_sous'] . "", '1', '0', 'C');
            $pdf->Ln();
            $pdf->Cell(40, 5, 'Adresse', '1', '0', 'L', '1');
            $pdf->Cell(150, 5, "" . $row_assu['adr_sous'] . "", '1', '0', 'C');
            $pdf->Ln();
            $pdf->Cell(40, 5, 'T�l�phone', '1', '0', 'L', '1');
            $pdf->Cell(55, 5, "" . $row_assu['tel_sous'] . "", '1', '0', 'C');
            $pdf->Cell(40, 5, 'E-mail', '1', '0', 'L', '1');
            $pdf->Cell(55, 5, "" . $row_assu['mail_sous'] . "", '1', '0', 'C');
            $pdf->Ln();
            $pdf->Cell(40, 5, 'D.Naissance', '1', '0', 'L', '1');
            $pdf->Cell(55, 5, "" . date("d/m/Y", strtotime($row_assu['dnais_sous'])) . "", '1', '0', 'C');
            $pdf->Cell(40, 5, 'N� Passeport', '1', '0', 'L', '1');
            $pdf->Cell(55, 5, "" . $row_assu['passport'] . "", '1', '0', 'C');
            $pdf->Ln();

        }
    }



// Voyage
    $pdf->SetFillColor(221, 221, 221);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(40, 5, 'Option', '1', '0', 'L', '1');
    $pdf->Cell(55, 5, "" . $row_sous['lib_opt'] . "", '1', '0', 'C');
    $pdf->Cell(40, 5, 'Formule', '1', '0', 'L', '1');
    $pdf->Cell(55, 5, "Individuelle", '1', '0', 'C');
    $pdf->Ln();
    $pdf->Cell(40, 5, 'Effet le', '1', '0', 'L', '1');
    $pdf->Cell(55, 5, "" . date("d/m/Y", strtotime($row_sous['dat_eff'])) . "", '1', '0', 'C');
    $pdf->Cell(40, 5, 'Ech�ance le', '1', '0', 'L', '1');
    $pdf->Cell(55, 5, "" . date("d/m/Y", strtotime($row_sous['dat_ech'])) . "", '1', '0', 'C');
    $pdf->Ln();
    $pdf->Cell(40, 5, 'Zone de Couverture', '1', '0', 'L', '1');
    $pdf->Cell(150, 5, "" . $row_sous['lib_pays'] . "", '1', '0', 'C');


    $pdf->Ln(3);
    $pdf->Ln(9);
// Garanties
    $pdf->SetFillColor(7, 27, 81);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(50, 5, ' Garanties ', '1', '0', 'C', '1');
    $pdf->Cell(70, 5, ' Capitaux-Limites ', '1', '0', 'C', '1');
    $pdf->Cell(70, 5, ' Prime Nette (DA) ', '1', '0', 'C', '1');

    $pdf->SetTextColor(0, 0, 0);
    $pdf->Ln();
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetFont('Arial', 'B', 8);

    if ($row_sous['cod_opt'] ==30 || $row_sous['cod_opt']==31) {
        $pdf->Cell(50, 5, 'D�c�s/IP (Accidentel)', '1', '0', 'C');
        $pdf->Cell(70, 5, "150 000.00 DA", '1', '0', 'C');
        $pdf->Cell(70, 5, "" . number_format($row_sous['p2'], 2, ',', ' ') . "", '1', '0', 'C');
        $pdf->Ln();}
    else{
        $pdf->Cell(50, 5, 'D�c�s/IP (Accidentel', '1', '0', 'C');
        $pdf->Cell(70, 5, "200 000.00 DA", '1', '0', 'C');
        $pdf->Cell(70, 5, "" . number_format($row_sous['p2'], 2, ',', ' ') . "", '1', '0', 'C');
        $pdf->Ln();
        if ($row_sous['cod_opt'] <23) {
            if ($row_sous['cod_zone'] == 2) {
                $pdf->Cell(50, 5, 'Assistance', '1', '0', 'C');
                $pdf->Cell(70, 5, "30 000.00 EU", '1', '0', 'C');
                $pdf->Cell(70, 5, "" . number_format($row_sous['p1'], 2, ',', ' ') . "", '1', '0', 'C');
                $pdf->Ln();
            }
            if ($row_sous['cod_zone'] == 3) {
                $pdf->Cell(50, 5, 'Assistance', '1', '0', 'C');
                $pdf->Cell(70, 5, "50 000.00 EU", '1', '0', 'C');
                $pdf->Cell(70, 5, "" . number_format($row_sous['p1'], 2, ',', ' ') . "", '1', '0', 'C');
                $pdf->Ln();
            }
        } else {
            $pdf->Cell(50, 5, 'Assistance', '1', '0', 'C');
            $pdf->Cell(70, 5, "10 000.00 EU", '1', '0', 'C');
            $pdf->Cell(70, 5, "" . number_format($row_sous['p1'], 2, ',', ' ') . "", '1', '0', 'C');
            $pdf->Ln();
        }
    }


    $pdf->Ln(9);
// Le Tarif !!!!!

    $pdf->SetFillColor(199, 139, 85);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(45, 5, ' Prime Nette ', '1', '0', 'C', '1');
    $pdf->Cell(45, 5, ' Cout de Police ', '1', '0', 'C', '1');
    $pdf->Cell(50, 5, ' Droit de timbre ', '1', '0', 'C', '1');
    $pdf->Cell(50, 5, ' Prime Totale (DZD) ', '1', '0', 'C', '1');
    $pdf->Ln();
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetFont('Arial', 'B', 8);

    $pdf->Cell(45, 5, "" . number_format($row_sous['pn'], 2, ',', ' ') . "", '1', '0', 'C');
    if ($row_sous['cod_cpl'] == 2) {

        $pdf->Cell(45, 5, "" . number_format('250', 2, ',', ' ') . "", '1', '0', 'C');
    }
    if ($row_sous['cod_cpl'] == 3) {
        $pdf->Cell(45, 5, "" . number_format('500', 2, ',', ' ') . "", '1', '0', 'C');
    }
    $pdf->Cell(50, 5, "" . number_format('40', 2, ',', ' ') . "", '1', '0', 'C');
    $pdf->Cell(50, 5, "" . number_format($row_sous['pt'], 2, ',', ' ') . "", '1', '0', 'C');
    $pdf->Ln();


    $pdf->Ln(2);
    $pdf->SetFont('Arial', 'I', 6);
    $pdf->Cell(0, 6, "Le Souscripteur reconnait que les pr�sentes Conditions Particuli�res ont �t� �tablies conform�ment aux renseignements qu'il a donn� lors de la souscription du Contrat.", 0, 0, 'C');
    $pdf->Ln(2);
    $pdf->Cell(0, 6, "Le Souscripteur reconnait �galement avoir �t� inform� du contenu des Conditions Particuli�res et des Conditions G�n�rales et avoir �t� inform� du montant de la prime et des garanties d�es.", 0, 0, 'C');
    $pdf->Ln(9);
    $somme = $a1->ConvNumberLetter("" . $row_sous['pt'] . "", 1, 0);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(30, 5, "Le montant � payer en lettres", '0', '0', 'L');
    $pdf->Ln();
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->MultiCell(190, 12, "" . $somme . "", 0, 'C', true);
    $pdf->Cell(185, 5, "" . $row_user['adr_user'] . " le " . date("d/m/Y", strtotime($row_sous['dat_dev'])) . "", '0', '0', 'R');
    $pdf->Ln();
    $pdf->Ln(2);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(60, 5, "Le souscripteur", '0', '0', 'C');
    $pdf->Cell(120, 5, "L'assureur", '0', '0', 'R');
    $pdf->Ln();
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(60, 5, "Pr�ced� de la mention �Lu et approuv�", '0', '0', 'C');
    $pdf->Ln();
    $pdf->Ln(35);
    $pdf->SetFont('Arial', 'B', 6);
    $pdf->Cell(0, 6, "Pour toute modification du contrat, le souscripteur est tenu d'aviser l'assureur 48 heures avant la date de prise d'effet de son contrat, ou du dernier avenant", 0, 0, 'C');
    $pdf->Ln(2);
    $pdf->Ln(2);
    $pdf->SetFont('Arial', '', 100);
    $pdf->RotatedText(60, 240, 'Devis-Gratuit', 60);
}
$pdf->Output();	

				

?>








