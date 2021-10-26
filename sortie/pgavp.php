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

if (isset($_REQUEST['groupe'])) {
    $row = substr($_REQUEST['groupe'],10);
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
// Instanciation de la classe derivee
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',12);
// Requete Agence 


//Requete Souscripteur

$query_sous =$bdd->prepare("SELECT s.*,v.*,o.lib_opt, p.lib_pays,d.sequence as sequence2,d.dat_val as dat_valp,t.`mtt_dt`,c.`mtt_cpl`,r.code_prod FROM `souscripteurw` as s, `policew` as d,`avenantw` as v, `option` as o, `pays` as p,produit as r,cpolice as c,dtimbre as t WHERE s.cod_sous=d.cod_sous and d.cod_pol=v.cod_pol and v.cod_opt=o.cod_opt and p.cod_pays=v.cod_pays and v.cod_av='".$row."'   and d.cod_prod=r.cod_prod and r.cod_prod=v.cod_prod and v.cod_cpl=c.cod_cpl and v.cod_dt=t.cod_dt;");
$query_sous->execute();


while ($row_sous=$query_sous->fetch()) {
    if ($row_sous['cod_opt'] < 30) {
        $pdf->Cell(190, 8, 'Assurance Voyage et Assistance', '0', '0', 'C');
        $pdf->Ln();
    } else {
        $pdf->Cell(190, 8, 'Assurance Voyage HADJ-OMRA', '0', '0', 'C');
        $pdf->Ln();
    }
    $user_creat=$row_sous['id_user'];
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
    $lib_mpay="";
    $cod_sous="";

    $query_ann = $bdd->prepare("select * from utilisateurs where  id_user ='$user_creat';");
    $query_ann->execute();
    while ($row_user = $query_ann->fetch()) {
        $pdf->Cell(190, 8, "Avenant de précision", '0', '0', 'L');
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(190, 8, 'Police N° ' . $row_user['agence'] . '.' . substr($row_sous['dat_valp'], 0, 4) . '.10.18.2.1.' . str_pad((int)$row_sous['sequence2'], '5', "0", STR_PAD_LEFT) . '', '0', '0', 'L');
        $pdf->Ln();
        $pdf->Cell(190, 8, 'Avenant N° ' . $row_user['agence'] . '.' . substr($row_sous['dat_val'], 0, 4) . '.' . $row_sous['lib_mpay'] . '.18.2.1.' . str_pad((int)$row_sous['sequence'], '5', "0", STR_PAD_LEFT) . '', '0', '0', 'L');
        $pdf->Ln();
        $pdf->SetFont('Arial', 'I', 9);


        $pdf->SetFont('Arial', 'B', 14);
//$pdf->Ln(2);
        $pdf->SetFillColor(7, 27, 81);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Ln();
        $pdf->Ln();
//Le Réseau
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(190, 5, "" . $row_user['reseau'] . "", '1', '1', 'C', '1');
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
        $agence=$row_user['agence'];
        $adr_agence=$row_user['adr_user'];
        $tel_agence=$row_user['tel_user'];
        $cod_prod=$row_sous['code_prod'];
        $annee=substr($row_sous['dat_val'],0,4);
        $sequence_pol=str_pad((int) $row_sous['sequence'],'5',"0",STR_PAD_LEFT);
        $lib_mpay=$row_sous['lib_mpay'];
        $cout_police=$row_sous['mtt_cpl'];
        $droit_timbre=$row_sous['mtt_dt'];
        $cod_sous=$row_sous['cod_sous'];

// Le Souscripteur
        $pdf->SetFillColor(199, 139, 85);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(190, 5, 'Souscripteur ', '1', '1', 'C', '1');
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
        $nom_sous=$row_sous['nom_sous'];
        $pnom_sous=$row_sous['pnom_sous'];
        $tel_sous=$row_sous['tel_sous'];
        $adr_sous=$row_sous['adr_sous'];
        $mail_sous=$row_sous['mail_sous'];
// L'assuré
        $pdf->SetFillColor(7, 27, 81);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(190, 5, 'Voyage', '1', '1', 'C', '1');
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFillColor(221, 221, 221);
        $pdf->SetFont('Arial', 'B', 8);
// Voyage
        $pdf->SetFillColor(221, 221, 221);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(40, 5, 'Option', '1', '0', 'L', '1');
        $pdf->Cell(55, 5, "" . $row_sous['lib_opt'] . "", '1', '0', 'C');
        $pdf->Cell(40, 5, 'Formule', '1', '0', 'L', '1');
        $pdf->Cell(55, 5, "Groupe", '1', '0', 'C');
        $pdf->Ln();
        $pdf->Cell(40, 5, 'Effet le', '1', '0', 'L', '1');
        $pdf->Cell(55, 5, "" . date("d/m/Y", strtotime($row_sous['ndat_eff'])) . "", '1', '0', 'C');
        $pdf->Cell(40, 5, 'Echéance le', '1', '0', 'L', '1');
        $pdf->Cell(55, 5, "" . date("d/m/Y", strtotime($row_sous['ndat_ech'])) . "", '1', '0', 'C');
        $pdf->Ln();
        $dat_eff=date("d/m/Y", strtotime($row_sous['dat_eff']));$dat_ech=date("d/m/Y", strtotime($row_sous['dat_ech']));
        $pdf->Cell(40, 5, 'Zone de Couverture', '1', '0', 'L', '1');
        $pdf->Cell(150, 5, "" . $row_sous['lib_pays'] . "", '1', '0', 'C');
        $pdf->Ln(3);

        $pdf-> Ln(5);
        $pdf->SetFont('Arial','B',11);
        $pdf->Cell(40,5,'Décompte de la prime ','0','0','L','0');
// Le Tarif !!!!!
        $pdf->Ln(6);
        $pdf->SetFillColor(199, 139, 85);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(45, 5, ' Prime Nette ', '1', '0', 'C', '1');
        $pdf->Cell(45, 5, " Cout d'avenant ", '1', '0', 'C', '1');
        $pdf->Cell(50, 5, ' Droit de timbre ', '1', '0', 'C', '1');
        $pdf->Cell(50, 5, ' Montant à Payer (DA) ', '1', '0', 'C', '1');
        $pdf->Ln();
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(45, 5, "" . number_format('0', 2, ',', ' ') . "", '1', '0', 'C');
        $pdf->Cell(45, 5, "" . number_format($cout_police, 2, ',', ' ') . "", '1', '0', 'C');
        $pdf->Cell(50, 5, "" . number_format($droit_timbre, 2, ',', ' ') . "", '1', '0', 'C');
        $pdf->Cell(50, 5, "" . number_format($row_sous['pt'], 2, ',', ' ') . "", '1', '0', 'C');
        $pdf->Ln();


        $pdf->Ln(10);
        $somme = $a1->ConvNumberLetter("" . $row_sous['pt'] . "", 1, 0);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(30, 5, "La Montant à payer en lettres", '0', '0', 'L');
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->MultiCell(190, 12, "" . $somme . "", 1, 'C', true);
        $pdf->Ln(5);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(0, 6, "Il n'est pas autrement dérogé aux autres clauses et conditions de la police de base à laquelle le présent avenant sera annexé pour en faire partie intégrante.", 0, 0, 'C');
        $pdf->Ln(2);
        $pdf->Ln(2);
        $pdf->Ln(5);
        $pdf->Cell(185, 5, "" . $row_user['adr_user'] . " le " . date("d/m/Y", strtotime($row_sous['dat_val'])) . "", '0', '0', 'R');
        $pdf->Ln();
        $pdf->Ln(3);
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(60, 5, "Le souscripteur", '0', '0', 'C');
        $pdf->Cell(120, 5, "L'assureur", '0', '0', 'R');
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(60, 5, "Précedée de la mention «Lu et approuvé»", '0', '0', 'C');
        $pdf->Ln();
        $pdf->Ln(20);


// Annexe pour la liste des assuré Famille
        $pdf->AliasNbPages();
        $pdf->AddPage();
// **********************************************
        $pdf->Ln();
        $pdf->Ln(3);
        $pdf->SetFillColor(7, 27, 81);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 15);
        $pdf->Cell(190, 10, 'Précision ', '1', '1', 'C', '1');
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFillColor(221, 221, 221);
        $pdf->SetFont('Arial', 'B', 8);
        $query_assu = $bdd->prepare("SELECT * FROM `assure` WHERE cod_av='" . $row . "';");
        $query_assu->execute();
        $cpt=0;
        while($row_assu=$query_assu->fetch()) {
            $cpt++;
            $pdf->Cell(5,5, "".$cpt, '1', '0', 'C', '1');
            $pdf->Cell(35, 5, 'Nom et Prénom', '1', '0', 'L', '1');
            $pdf->Cell(150, 5,utf8_decode ("" . $row_assu['nom_assu'] . " " . $row_assu['pnom_assu'] . ""), '1', '0', 'C');
            $pdf->Ln();
            $pdf->Cell(40, 5, 'Adresse', '1', '0', 'L', '1');
            $pdf->Cell(150, 5, utf8_decode ("" . $row_assu['adr_assu'] . ""), '1', '0', 'C');
            $pdf->Ln();
            $pdf->Cell(40, 5, 'Téléphone', '1', '0', 'L', '1');
            $pdf->Cell(55, 5, "" . $row_assu['tel_assu'] . "", '1', '0', 'C');
            $pdf->Cell(40, 5, 'E-mail', '1', '0', 'L', '1');
            $pdf->Cell(55, 5, "" . $row_assu['mail_assu'] . "", '1', '0', 'C');
            $pdf->Ln();
            $pdf->Cell(40, 5, 'N.Passport', '1', '0', 'L', '1');
            $pdf->Cell(55, 5, "" . $row_assu['passport'] . "", '1', '0', 'C');
            $pdf->Cell(40, 5, 'Delevré le:', '1', '0', 'L', '1');
            $pdf->Cell(55, 5, "" . date("d/m/Y", strtotime($row_assu['datedpass'])) . "", '1', '0', 'C');
            $pdf->Ln();

        }


    }
}


//////quittance

$cpt_q=0;
$rqtseq=$bdd->prepare(" SELECT `id_quit`, `cod_quit`, `mois`, `date_quit`, `agence`, `cod_ref`, `mtt_quit`, `solde_pol`, `cod_dt`, `cod_cpl`, `id_user` FROM `quittance` WHERE `cod_ref`='$row' AND type_quit=1;");
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

    $pdf->Cell(20,20,'Avenant N°: ','LB','0','L');
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(70,20,' '.$agence.'.'.$annee.'.'.$lib_mpay.'.1.'.$cod_prod.'.'.$sequence_pol,'B','0','L');
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








