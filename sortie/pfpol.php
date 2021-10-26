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

if (isset($_REQUEST['famille'])) {
    $row = substr($_REQUEST['famille'],10);
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
//Les requetes *****************
// Requete Agence 
$query_ann =$bdd->prepare("select * from utilisateurs where  id_user ='".$_SESSION['id_user']."';");
$query_ann->execute();
//$row_user = $connection->enr_actuel();

//Requete Souscripteur
$query_sous =$bdd->prepare("SELECT s.*, d.*,p.lib_pays,p.cod_zone, o.lib_opt,t.`mtt_dt`,c.`mtt_cpl`,r.code_prod  FROM `souscripteurw` as s, `policew` as d, `pays` as p, `option` as o,dtimbre as t,cpolice as c,produit as r
                              WHERE s.cod_sous=d.cod_sous and d.cod_pays=p.cod_pays and d.cod_opt=o.cod_opt and d.cod_cpl=c.cod_cpl and d.cod_dt=t.cod_dt and d.cod_prod=r.cod_prod   and d.cod_pol='".$row."';");
$query_sous->execute();
//$row_sous = $connection->enr_actuel();

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
// Instanciation de la classe derivee
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',12);
//$pdf->Ln(2);
$pdf->SetFillColor(205,205,205);
while ($row_sous =$query_sous->fetch()) {
    $pdf->Cell(190, 8, 'Assurance Voyage et Assistance', '0', '0', 'C');
    $pdf->Ln();
    $user_creat=$row_sous['id_user'];

    $query_ann = $bdd->prepare("select * from utilisateurs where  id_user ='$user_creat';");
    $query_ann->execute();
    while ($row_user = $query_ann->fetch()) {
        $pdf->Cell(190, 8, 'Police N° ' . $row_user['agence'] . '.' . substr($row_sous['dat_val'], 0, 4) . '.10.18.2.1.' . str_pad((int)$row_sous['sequence'], '5', "0", STR_PAD_LEFT) . '', '0', '0', 'C');
        $pdf->Ln();
        $pdf->Ln();
        $pdf->SetFont('Arial', 'I', 9);
        $pdf->Cell(0, 6, "Contrat régi par l'ordonnance 95/07 du 25-O1-1995 relative aux assurances modifiée et complétée par la loi 06/04du 20-02-2006.", 0, 0, 'L');
        $pdf->Ln();
        $pdf->Cell(0, 6, "Que par l'ordonnance 75/58 du 26 septembre 1975 du code civil aux conditions générales qui précedent et celles particulières qui", 0, 0, 'L');
        $pdf->Ln();
        $pdf->Cell(0, 6, "suivent, l'Algérienne Vie garantit:", 0, 0, 'L');
        $pdf->Ln();
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 14);
//$pdf->Ln(2);
        $pdf->SetFillColor(7, 27, 81);
        $pdf->SetTextColor(255, 255, 255);

//Le Réseau
        $agence=$row_user['agence'];
        $adr_agence=$row_user['adr_user'];
        $tel_agence=$row_user['tel_user'];
        $cod_prod=$row_sous['code_prod'];
        $annee=substr($row_sous['dat_val'],0,4);
        $sequence_pol=str_pad((int) $row_sous['sequence'],'5',"0",STR_PAD_LEFT);
        $cout_police=$row_sous['mtt_cpl'];
        $droit_timbre=$row_sous['mtt_dt'];

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(190, 5, "Agence", '1', '1', 'C', '1');
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFillColor(221, 221, 221);
        $pdf->Cell(40, 5, 'Agence', '1', '0', 'L', '1');
        $pdf->Cell(55, 5, "" . $row_user['agence'] . "", '1', '0', 'C');
        $pdf->Cell(40, 5, 'Adresse', '1', '0', 'L', '1');
        $pdf->Cell(55, 5, "" . $row_user['adr_user'] . "", '1', '0', 'C');
        $pdf->Ln();
        $pdf->Cell(40, 5, 'Téléphone', '1', '0', 'L', '1');
        $pdf->Cell(55, 5, "" . $row_user['tel_user'] . "", '1', '0', 'C');
        $pdf->Cell(40, 5, 'E-mail', '1', '0', 'L', '1');
        $pdf->Cell(55, 5, "" . $row_user['mail_user'] . "", '1', '0', 'C');
        $pdf->Ln();


        $pdf->Ln(3);
// Le Souscripteur
        $pdf->SetFillColor(199, 139, 85);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(190, 5, 'Souscripteur ', '1', '1', 'C', '1');

        $nom_sous=$row_sous['nom_sous'];
        $pnom_sous=$row_sous['pnom_sous'];
        $tel_sous=$row_sous['tel_sous'];
        $adr_sous=$row_sous['adr_sous'];
        $mail_sous=$row_sous['mail_sous'];

        $pdf->SetFillColor(221, 221, 221);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(40, 5, 'Nom et Prénom/ R.Sociale', '1', '0', 'L', '1');
        if ($row_sous['rp_sous'] == 0) {
            $pdf->Cell(150, 5, "" . $row_sous['nom_sous'] . "", '1', '0', 'C');
            $pdf->Ln();
        } else {
            $pdf->Cell(150, 5, "" . $row_sous['nom_sous'] . " " . $row_sous['pnom_sous'] . "", '1', '0', 'C');
            $pdf->Ln();
        }
        $pdf->Cell(40, 5, 'Adresse', '1', '0', 'L', '1');
        $pdf->Cell(150, 5, "" . $row_sous['adr_sous'] . "", '1', '0', 'C');
        $pdf->Ln();
        $pdf->Cell(40, 5, 'Téléphone', '1', '0', 'L', '1');
        $pdf->Cell(55, 5, "" . $row_sous['tel_sous'] . "", '1', '0', 'C');
        $pdf->Cell(40, 5, 'E-mail', '1', '0', 'L', '1');
        $pdf->Cell(55, 5, "" . $row_sous['mail_sous'] . "", '1', '0', 'C');
        $pdf->Ln();
        $pdf->Ln(3);
// L'assuré
        $pdf->SetFillColor(7, 27, 81);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(190, 5, 'Voyage', '1', '1', 'C', '1');
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFillColor(221, 221, 221);
        $pdf->SetFont('Arial', 'B', 8);
// Voyage
        $dat_eff=date("d/m/Y", strtotime($row_sous['dat_eff']));$dat_ech=date("d/m/Y", strtotime($row_sous['dat_ech']));
        $pdf->SetFillColor(221, 221, 221);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(40, 5, 'Option', '1', '0', 'L', '1');
        $pdf->Cell(55, 5, "" . $row_sous['lib_opt'] . "", '1', '0', 'C');
        $pdf->Cell(40, 5, 'Formule', '1', '0', 'L', '1');
        $pdf->Cell(55, 5, "Famille", '1', '0', 'C');
        $pdf->Ln();
        $pdf->Cell(40, 5, 'Effet le', '1', '0', 'L', '1');
        $pdf->Cell(55, 5, "" . date("d/m/Y", strtotime($row_sous['dat_eff'])) . "", '1', '0', 'C');
        $pdf->Cell(40, 5, 'Echéance le', '1', '0', 'L', '1');
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
        $pn = $row_sous['pn'];

        $pdf->Cell(50, 5, 'Accident Corporel', '1', '0', 'C');
        $pdf->Cell(70, 5, "200 000.00 DA", '1', '0', 'C');
        $pdf->Cell(70, 5, "" . number_format($row_sous['p2'], 2, ',', ' ') . "", '1', '0', 'C');
        $pdf->Ln();
        if ($row_sous['cod_opt'] < 23) {
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

        $pdf->Ln(9);
// Le Tarif !!!!!

        $pdf->SetFillColor(199, 139, 85);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(45, 5, ' Prime Nette ', '1', '0', 'C', '1');
        $pdf->Cell(45, 5, ' Cout de Police ', '1', '0', 'C', '1');
        $pdf->Cell(50, 5, ' Droit de timbre ', '1', '0', 'C', '1');
        $pdf->Cell(50, 5, ' Montant à Payer (DA) ', '1', '0', 'C', '1');
        $pdf->Ln();
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 8);

        $pdf->Cell(45, 5, "" . number_format($pn, 2, ',', ' ') . "", '1', '0', 'C');
        $pdf->Cell(45, 5, "" . number_format($cout_police, 2, ',', ' ') . "", '1', '0', 'C');

        $pdf->Cell(50, 5, "" . number_format($droit_timbre, 2, ',', ' ') . "", '1', '0', 'C');
        $pdf->Cell(50, 5, "" . number_format($row_sous['pt'], 2, ',', ' ') . "", '1', '0', 'C');

      $pdf->Ln();



        $pdf->Ln(2);
        $pdf->SetFont('Arial', 'I', 6);
        $pdf->Cell(0, 6, "Le Souscripteur reconnait que les présentes Conditions Particulières ont été établies conformément aux renseignements qu'il a donné lors de la souscription du Contrat.", 0, 0, 'C');
        $pdf->Ln(2);
        $pdf->Cell(0, 6, "Le Souscripteur reconnait également avoir été informé du contenu des Conditions Particulières et des Conditions Générales et avoir été informé du montant de la prime et des garanties dûes.", 0, 0, 'C');
        $pdf->Ln(9);
        $somme = $a1->ConvNumberLetter("" . $row_sous['pt'] . "", 1, 0);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(30, 5, "Le montant à payer en lettres", '0', '0', 'L');
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->MultiCell(190, 12, "" . $somme . "", 0, 'C', true);
        $pdf->Cell(185, 5, "" . $row_user['adr_user'] . " le " . date("d/m/Y", strtotime($row_sous['dat_val'])) . "", '0', '0', 'R');
        $pdf->Ln();
        $pdf->Ln(2);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(60, 5, "Le souscripteur", '0', '0', 'C');
        $pdf->Cell(120, 5, "L'assureur", '0', '0', 'R');
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(60, 5, "Précedé de la mention «Lu et approuvé»", '0', '0', 'C');
        $pdf->Ln();
        $pdf->Ln(19);
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(0, 6, "Pour toute modification du contrat, le souscripteur est tenu d'aviser l'assureur 48 heures avant la date de prise d'effet de son contrat, ou du dernier avenant", 0, 0, 'C');
        $pdf->Ln(2);
        $pdf->Ln(2);
        $pdf->SetFont('Arial', '', 100);
      //  $pdf->RotatedText(60, 240, 'Plateforme-Test', 60);
// Annexe pour la liste des assuré Famille
        $pdf->AliasNbPages();
        $pdf->AddPage();
// **********************************************
        $pdf->Ln();
        $pdf->Ln(3);
        $pdf->SetFillColor(7, 27, 81);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 15);
        $pdf->Cell(190, 10, 'Liste des Assurés ', '1', '1', 'C', '1');
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFillColor(221, 221, 221);
        $pdf->SetFont('Arial', 'B', 8);
        $query_assu = $bdd->prepare("SELECT * FROM `souscripteurw` WHERE cod_par='" . $row_sous['cod_sous'] . "';");
        $query_assu->execute();
//$row_assu = $connection->enr_actuel();
        $pdf->Cell(100, 5, 'Nom et Prénom', '1', '0', 'C', '1');
        $pdf->Cell(45, 5, 'Passport N°', '1', '0', 'C', '1');
        $pdf->Cell(45, 5, 'Date-Naissance', '1', '0', 'C', '1');
        $pdf->Ln();
        while ($row_assu = $query_assu->fetch()) {
            $pdf->Cell(100, 5, "" . $row_assu['nom_sous'] . " " . $row_assu['pnom_sous'] . "", '1', '0', 'C');
            $pdf->Cell(45, 5, "" . $row_assu['passport'] . "", '1', '0', 'C');
            $pdf->Cell(45, 5, "" . date("d/m/Y", strtotime($row_assu['dnais_sous'])) . "", '1', '0', 'C');
            $pdf->Ln();
        }

        $pdf->SetFillColor(255, 255, 255);
//Deuxième page -- Notice d'information
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 15);
        $pdf->Cell(190, 8, "NOTICE D'INFORMATION", '0', '0', 'C');
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(120, 7, "Garanties", '1', '0', 'C');
        $pdf->Cell(70, 7, "Limites/Capital-(Franchises)", '1', '0', 'C');
        $pdf->Ln();
        $pdf->SetFont('Arial', 'I', 8);
        $pdf->Cell(190, 6, "Assurance", '1', '0', 'C');
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 7);
//Premiere Partie
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->MultiCell(120, 6, "Décés Accidentel (pour les personnes agées de plus de 13 ans) \n Incapacité Permanente Accidentelle", 1, 'L', true);
        $pdf->SetXY($x, $y);
        $pdf->SetXY($x + 120, $y);
        $pdf->MultiCell(70, 6, "200 000 DZD \n 200 000 DZD  ", 1, 'C', true);
//Deuxieme partie
        $pdf->SetFont('Arial', 'I', 8);
        $pdf->Cell(190, 6, "Assistance", '1', '0', 'C');
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 7);
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        if($row_sous['dat_val']>'2021-06-16')
        {
            if ($row_sous['cod_opt'] <23) {
                $pdf->MultiCell(120, 5, "Transport sanitaire   \n  Prise en charge des frais médicaux, pharmaceutiques, d'hospitalisation et chirurgicaux \n Prise en charge des soins dentaires d'urgence \n Prolongation de séjour \n Frais de secours et sauvetage \n Visite d'un proche parent \n Rapatriement de corps en cas de décès \n Retour prématuré du Bénéficiaire \n Rapatriement des autres Bénéficiaires \n Retard de vol et de livraison de bagages \n Perte de bagage \n Assistance juridique \n Avance de caution pénale \n Transmission de messages urgents \n Manquement de correspondance \n Annulation de voyage \n Informations \n Couverture COVID-19\n\n\n\n\n\n\n\n\n\n", 1, 'L', true);
                $pdf->SetXY($x, $y);
                $pdf->SetXY($x + 120, $y);
                $pdf->MultiCell(70, 5, "Frais réels  \n Zone 1 : 30 000 EU (40 EU) / Zone 2 : 50 000 EU (40 EU) \n 1 000.00 EU (30 EU) \n 100 EU /Jour (8 Jours Max) \n 1500 EU /bénéficiaire/évènement \n 100 EU /jour (4 jours Max) \n Frais réels \n 1 000.00 EU \n Frais réels \n 1 80 EU \n 20 EU /Kg, 40Kg Max \n 4 000.00 EU \n 10 000.00 EU \n Illimité \n 100 EU \n Frais de voyage non récupérables \n  Illimité \nFrais médicaux suite à une infection au COVID-19 (pour l’hospitalisation seulement) / max € 10,000.
Rapatriement en cas de décès due au COVID-19 / max € 5,000.
Retour en cas de manquement du vol d'origine en raison d’une infection au COVID-19 / max € 1,000 ", 1, 'C', true);
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(190, 6, "NOTE AUX CLIENTS", '1', '0', 'C');
                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 7);
                $pdf->MultiCell(190, 5, "  -Les souscriptions des contrats assurance voyage ou des avenants à distance « de l'étranger » sont formellement interdites. \n  -En cas de sinistre, l'assuré ou un membre de sa famille doit impérativement contacter au préalable l'Assisteur avant d'engager toute dépenses, dans le cas échéant, il ne pourra prétendre à aucun remboursement. \n -Le remboursement des contrats assurance voyage se fait uniquement dans les  cas suivants: \n   +Le refus de VISA; \n   +Le décès d?un proche ; ascendant, descendant, conjoint ; \n   +Incapacité de l?assuré à voyager pour cause d'état de santé ; \n -Le souscripteur est tenu de constituer le dossier suivant : \n   + L'original du contrat  \n   + Justificatifs sus-cités \n   + Copie des cinq premières pages du passeport  \n  + Refus de visa", 1, 'L', true);
                $pdf->Ln();
            }
            else {
                if ($row_sous['cod_opt'] == 25) {
                    if ($row_sous['cod_pays'] == 'TN') {
                        // Option Tunisie ici ***************************
                        $pdf->MultiCell(120, 5, "Transport sanitaire   \n  Frais médicaux d'urgence \n Prolongation de séjour pour convalescence \n Défense et recours \n Avance de caution pénale \n Rapatriement de corps en cas de décès \n Expédition de médicament \n Transmission de messages urgents \n Conseil médical par téléphone  \n Informations \n Couverture COVID-19\n\n\n\n\n\n\n\n\n\n", 1, 'L', true);
                        $pdf->SetXY($x, $y);
                        $pdf->SetXY($x + 120, $y);
                        $pdf->MultiCell(70, 5, "Frais réels  \n Plafond de 10000 EU (20 EU) \n 100 EU /Jour (5 Jours Max) \n 1000 EU \n 1000 EU  \n Cercueil minimum + transport au lieu d'inhumation \n Frais réels \n Illimité \n Illimité  \n  Illimité\nFrais médicaux suite à une infection au COVID-19 (pour l’hospitalisation seulement) / max € 1,000.
Rapatriement en cas de décès due au COVID-19 / max € 1,000.
Retour en cas de manquement du vol d'origine en raison d’une infection au COVID-19 / max € 500", 1, 'C', true);
                        $pdf->SetFont('Arial', 'B', 10);
                        $pdf->Cell(190, 6, "NOTE AUX CLIENTS", '1', '0', 'C');
                        $pdf->Ln();
                        $pdf->SetFont('Arial', 'B', 7);
                        $pdf->MultiCell(190, 5, "  -Les souscriptions des contrats assurance voyage ou des avenants à distance « de l'étranger » sont formellement interdites. \n  -En cas de sinistre, l'assuré ou un membre de sa famille doit impérativement contacter au préalable l'Assisteur avant d'engager toute dépenses, dans le cas échéant, il ne pourra prétendre à aucun remboursement. \n -Le remboursement des contrats assurance voyage se fait uniquement dans les  cas suivants: \n   +Le décès d?un proche ; ascendant, descendant, conjoint ; \n   +Incapacité de l?assuré à voyager pour cause d'état de santé ; \n -Le souscripteur est tenu de constituer le dossier suivant : \n   + L'original du contrat  \n   + Justificatifs sus-cités \n   + Copie des cinq premières pages du passeport  \n  + Refus de visa ", 1, 'L', true);
                        $pdf->Ln();
//***********************************************


                    } else {
                        if ($row_sous['cod_pays'] == 'TR') {
                            // Option Turquie ici ***************************
                            $pdf->MultiCell(120, 5, "Transport sanitaire   \n  Frais médicaux d'urgence \n Prolongation de séjour pour convalescence \n Défense et recours \n Avance de caution pénale \n Rapatriement de corps en cas de décès \n Expédition de médicament \n Transmission de messages urgents \n Conseil médical par téléphone  \n Informations \n Couverture COVID-19\n\n\n\n\n\n\n\n\n\n", 1, 'L', true);
                            $pdf->SetXY($x, $y);
                            $pdf->SetXY($x + 120, $y);
                            $pdf->MultiCell(70, 5, "Frais réels  \n Plafond de 10000 EU (20 EU) \n 100 EU /Jour (5 Jours Max) \n 1000 EU \n 1000 EU  \n Cercueil minimum + transport au lieu d'inhumation \n Frais réels \n Illimité \n Illimité  \n  Illimité\nFrais médicaux suite à une infection au COVID-19 (pour l’hospitalisation seulement) / max € 5,000.
Rapatriement en cas de décès due au COVID-19 / max € 2,500.
Retour en cas de manquement du vol d'origine en raison d’une infection au COVID-19 / max € 500", 1, 'C', true);
                            $pdf->SetFont('Arial', 'B', 10);
                            $pdf->Cell(190, 6, "NOTE AUX CLIENTS", '1', '0', 'C');
                            $pdf->Ln();
                            $pdf->SetFont('Arial', 'B', 7);
                            $pdf->MultiCell(190, 5, "  -Les souscriptions des contrats assurance voyage ou des avenants à distance « de l'étranger » sont formellement interdites. \n  -En cas de sinistre, l'assuré ou un membre de sa famille doit impérativement contacter au préalable l'Assisteur avant d'engager toute dépenses, dans le cas échéant, il ne pourra prétendre à aucun remboursement. \n -Le remboursement des contrats assurance voyage se fait uniquement dans les  cas suivants: \n   +Le décès d?un proche ; ascendant, descendant, conjoint ; \n   +Incapacité de l?assuré à voyager pour cause d'état de santé ; \n -Le souscripteur est tenu de constituer le dossier suivant : \n   + L'original du contrat  \n   + Justificatifs sus-cités \n   + Copie des cinq premières pages du passeport  \n  + Refus de visa ", 1, 'L', true);
                            $pdf->Ln();
//***********************************************
                        }

                    }


                } else {
// Option Tunisie ici ***************************
                    $pdf->MultiCell(120, 5, "Transport sanitaire   \n  Frais médicaux d'urgence \n Prolongation de séjour pour convalescence \n Défense et recours \n Avance de caution pénale \n Rapatriement de corps en cas de décès \n Expédition de médicament \n Transmission de messages urgents \n Conseil médical par téléphone  \n Informations \n Couverture COVID-19\n\n\n\n\n\n\n\n\n\n", 1, 'L', true);
                    $pdf->SetXY($x, $y);
                    $pdf->SetXY($x + 120, $y);
                    $pdf->MultiCell(70, 5, "Frais réels  \n Plafond de 10000 EU (20 EU) \n 100 EU /Jour (5 Jours Max) \n 1000 EU \n 1000 EU  \n Cercueil minimum + transport au lieu d'inhumation \n Frais réels \n Illimité \n Illimité  \n  Illimité\nFrais médicaux suite à une infection au COVID-19 (pour l’hospitalisation seulement) / max € 10,000.
Rapatriement en cas de décès due au COVID-19 / max € 5,000.
Retour en cas de manquement du vol d'origine en raison d’une infection au COVID-19 / max € 1,000", 1, 'C', true);
                    $pdf->SetFont('Arial', 'B', 10);
                    $pdf->Cell(190, 6, "NOTE AUX CLIENTS", '1', '0', 'C');
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', 7);
                    $pdf->MultiCell(190, 5, "  -Les souscriptions des contrats assurance voyage ou des avenants à distance « de l'étranger » sont formellement interdites. \n  -En cas de sinistre, l'assuré ou un membre de sa famille doit impérativement contacter au préalable l'Assisteur avant d'engager toute dépenses, dans le cas échéant, il ne pourra prétendre à aucun remboursement. \n -Le remboursement des contrats assurance voyage se fait uniquement dans les  cas suivants: \n   +Le décès d?un proche ; ascendant, descendant, conjoint ; \n   +Incapacité de l?assuré à voyager pour cause d'état de santé ; \n -Le souscripteur est tenu de constituer le dossier suivant : \n   + L'original du contrat  \n   + Justificatifs sus-cités \n   + Copie des cinq premières pages du passeport  \n  + Refus de visa ", 1, 'L', true);
                    $pdf->Ln();
//***********************************************
                }
            }
        }
        else
        {
            if ($row_sous['cod_opt'] <23) {
                $pdf->MultiCell(120, 5, "Transport sanitaire   \n  Prise en charge des frais médicaux, pharmaceutiques, d'hospitalisation et chirurgicaux \n Prise en charge des soins dentaires d'urgence \n Prolongation de séjour \n Frais de secours et sauvetage \n Visite d'un proche parent \n Rapatriement de corps en cas de décès \n Retour prématuré du Bénéficiaire \n Rapatriement des autres Bénéficiaires \n Retard de vol et de livraison de bagages \n Perte de bagage \n Assistance juridique \n Avance de caution pénale \n Transmission de messages urgents \n Manquement de correspondance \n Annulation de voyage \n Informations", 1, 'L', true);
                $pdf->SetXY($x, $y);
                $pdf->SetXY($x + 120, $y);
                $pdf->MultiCell(70, 5, "Frais réels  \n Zone 1 : 30 000 EU (40 EU) / Zone 2 : 50 000 EU (40 EU) \n 1 000.00 EU (30 EU) \n 100 EU /Jour (8 Jours Max) \n 1500 EU /bénéficiaire/évènement \n 100 EU /jour (4 jours Max) \n Frais réels \n 1 000.00 EU \n Frais réels \n 1 80 EU \n 20 EU /Kg, 40Kg Max \n 4 000.00 EU \n 10 000.00 EU \n Illimité \n 100 EU \n Frais de voyage non récupérables \n  Illimité", 1, 'C', true);
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(190, 6, "NOTE AUX CLIENTS", '1', '0', 'C');
                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 7);
                $pdf->MultiCell(190, 5, "  -Les souscriptions des contrats assurance voyage ou des avenants à distance « de l'étranger » sont formellement interdites. \n  -En cas de sinistre, l'assuré ou un membre de sa famille doit impérativement contacter au préalable l'Assisteur avant d'engager toute dépenses, dans le cas échéant, il ne pourra prétendre à aucun remboursement. \n -Le remboursement des contrats assurance voyage se fait uniquement dans les  cas suivants: \n   +Le refus de VISA; \n   +Le décès d?un proche ; ascendant, descendant, conjoint ; \n   +Incapacité de l?assuré à voyager pour cause d'état de santé ; \n -Le souscripteur est tenu de constituer le dossier suivant : \n   + L'original du contrat  \n   + Justificatifs sus-cités \n   + Copie des cinq premières pages du passeport  \n  + Refus de visa", 1, 'L', true);
                $pdf->Ln();
            } else {
// Option Tunisie ici ***************************
                $pdf->MultiCell(120, 5, "Transport sanitaire   \n  Frais médicaux d'urgence \n Prolongation de séjour pour convalescence \n Défense et recours \n Avance de caution pénale \n Rapatriement de corps en cas de décès \n Expédition de médicament \n Transmission de messages urgents \n Conseil médical par téléphone  \n Informations", 1, 'L', true);
                $pdf->SetXY($x, $y);
                $pdf->SetXY($x + 120, $y);
                $pdf->MultiCell(70, 5, "Frais réels  \n Plafond de 10000 EU (20 EU) \n 100 EU /Jour (5 Jours Max) \n 1000 EU \n 1000 EU  \n Cercueil minimum + transport au lieu d'inhumation \n Frais réels \n Illimité \n Illimité  \n  Illimité", 1, 'C', true);
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(190, 6, "NOTE AUX CLIENTS", '1', '0', 'C');
                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 7);
                $pdf->MultiCell(190, 5, "  -Les souscriptions des contrats assurance voyage ou des avenants à distance « de l'étranger » sont formellement interdites. \n  -En cas de sinistre, l'assuré ou un membre de sa famille doit impérativement contacter au préalable l'Assisteur avant d'engager toute dépenses, dans le cas échéant, il ne pourra prétendre à aucun remboursement. \n -Le remboursement des contrats assurance voyage se fait uniquement dans les  cas suivants: \n   +Le décès d?un proche ; ascendant, descendant, conjoint ; \n   +Incapacité de l?assuré à voyager pour cause d'état de santé ; \n -Le souscripteur est tenu de constituer le dossier suivant : \n   + L'original du contrat  \n   + Justificatifs sus-cités \n   + Copie des cinq premières pages du passeport  \n  + Refus de visa ", 1, 'L', true);
                $pdf->Ln();
//***********************************************
            }
        }

    }
}

//////quittance

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








