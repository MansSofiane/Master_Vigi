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
    require('tfpdf.php');
    class PDF extends TFPDF     {
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
    $rqtp=$bdd->prepare("SELECT a.`agence`, p.`lib_prod`,p.`code_prod` FROM `utilisateurs` as a,`produit` as p WHERE p.cod_prod='$prod' and a.id_user='$user'");
    $rqtp->execute();
    $agence="";
    $pdf->Cell(405,10,'Bordereau de production n�gatif sans Ristourne du '.date("d/m/Y", strtotime($date1)).' au '.date("d/m/Y", strtotime($date2)).'  --Document g�n�r� le-- '.date("d/m/Y", strtotime($datesys)) ,'1','1','L','1');
    while ($row_p=$rqtp->fetch()){
        $pdf->Cell(136,10,'AgenceN�: '.$row_p['agence'],'1','0','C');$pdf->Cell(135,10,'Produit: '.$row_p['lib_prod'],'1','0','C');$pdf->Cell(134,10,'Code produit: '.$row_p['code_prod'],'1','1','C');
        $agence=$row_p['agence'];
    }
//requete pour les contrats
    /*$rqtg=$bdd->prepare("SELECT d.`dat_val`,d.`dat_eff`,d.`dat_ech`,d.`sequence`,d.`pn`,d.`pt`,t.`mtt_dt`,c.`mtt_cpl`,p.`code_prod`,p.`lib_prod` ,s.`nom_sous`, s.`pnom_sous`,m.`lib_mpay`,u.`agence` FROM `policew` as d, `dtimbre` as t , `cpolice` as c,`produit` as p,`souscripteurw` as s, `mpay` as m, `utilisateurs` as u  WHERE d.`cod_dt`=t.`cod_dt` AND d.`cod_cpl`=c.`cod_cpl` AND d.`cod_prod`=p.`cod_prod` AND d.`cod_sous`=s.`cod_sous` AND d.`mode`=m.`cod_mpay` AND s.`id_user`='$user' AND d.`cod_prod`='$prod' AND DATE_FORMAT(d.`dat_val`,'%Y-%m-%d')>='$date1' AND DATE_FORMAT(d.`dat_val`,'%Y-%m-%d')<='$date2' AND u.`id_user`='$user'");
    $rqtg->execute();*/
//requete pour les avenants
    $rqtv=$bdd->prepare("SELECT d.`dat_val`,d.`pn`,d.`pn`,d.`pt`,d.mtt_reg,d.mtt_solde,d.`lib_mpay`,d.`sequence`,t.`mtt_dt`,c.`mtt_cpl`,p.`code_prod`,p.`lib_prod`, s.`cod_sous`,s.`nom_sous`, s.`pnom_sous`,z.sequence as seq2, z.dat_val as datev,u.`agence`  FROM `avenantw` as d,`policew` as z, `dtimbre` as t , `cpolice` as c,`produit` as p,`souscripteurw` as s,`utilisateurs` as u  WHERE d.`cod_dt`=t.`cod_dt` AND d.`cod_cpl`=c.`cod_cpl` AND d.`cod_prod`=p.`cod_prod` AND d.`cod_pol`=z.`cod_pol` AND z.`cod_sous`=s.`cod_sous` AND s.`id_user`= u.`id_user` AND d.`cod_prod`='$prod' AND DATE_FORMAT(d.`dat_val`,'%Y-%m-%d')>='$date1' AND DATE_FORMAT(d.`dat_val`,'%Y-%m-%d')<='$date2' AND u.`agence`='$agence' AND d.`lib_mpay` in('50')");
    $rqtv->execute();

    $pdf->Ln();
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(40,5,'Police N�','1','0','C');$pdf->Cell(40,5,'Avenant N�','1','0','C');$pdf->Cell(60,5,'Nom&Pr�nom-R.Sociale','1','0','C');
    $pdf->Cell(18,5,'Emmision','1','0','C');$pdf->Cell(16,5,'Effet','1','0','C');$pdf->Cell(16,5,'Ech�ance','1','0','C');

    $pdf->Cell(35,5,'P.Nette','1','0','C');$pdf->Cell(30,5,'C.Police','1','0','C');$pdf->Cell(35,5,'P.Commer','1','0','C');$pdf->Cell(20,5,'D.Timbre','1','0','C');$pdf->Cell(35,5,'P.Total','1','0','C');$pdf->Cell(30,5,'Reglement','1','0','C');$pdf->Cell(30,5,'Solde','1','0','C');

//boucle Avenants
    while ($row_v=$rqtv->fetch()){
        $pdf->SetFillColor(221,221,221);
        $pdf->Ln();
        $pdf->SetFont('Arial','B',10);
        $pdf->SetFillColor(255,255,255);
        $pdf->SetFont('Arial','B',8);
//Reporting Polices
        $pdf->Cell(40,5,''.$row_v['agence'].'.'.substr($row_v['datev'],0,4).'.10.'.$row_v['code_prod'].'.'.str_pad((int) $row_v['seq2'],'5',"0",STR_PAD_LEFT).'','1','0','C');
        $pdf->Cell(40,5,''.$row_v['agence'].'.'.substr($row_v['dat_val'],0,4).'.'.$row_v['lib_mpay'].'.'.$row_v['code_prod'].'.'.str_pad((int) $row_v['sequence'],'5',"0",STR_PAD_LEFT).'','1','0','C');
        $pdf->Cell(60,5,"".$row_v['nom_sous'].' '.$row_v['pnom_sous']."",'1','0','L');
        $pdf->Cell(18,5,''.date("d/m/Y", strtotime($row_v['dat_val'])).'','1','0','C');$pdf->Cell(16,5,'----','1','0','C');$pdf->Cell(16,5,'----','1','0','C');

        $pdf->Cell(35,5,''.number_format($row_v['pn'], 2,',',' ').'','1','0','R');$tpn=$tpn+$row_v['pn'];
        $pdf->Cell(30,5,''.number_format($row_v['mtt_cpl'], 2,',',' ').'','1','0','R');$tcp=$tcp+$row_v['mtt_cpl'];
        $pdf->Cell(35,5,''.number_format($row_v['pn']+$row_v['mtt_cpl'], 2,',',' ').'','1','0','R');$tpc=$tpc+($row_v['pn']+$row_v['mtt_cpl']);
        $pdf->Cell(20,5,''.number_format($row_v['mtt_dt'], 2,',',' ').'','1','0','R');$tdt=$tdt+$row_v['mtt_dt'];
        $pdf->Cell(35,5,''.number_format($row_v['pt'], 2,',',' ').'','1','0','R');$tpt=$tpt+$row_v['pt'];
        $pdf->Cell(30,5,''.number_format($row_v['mtt_reg'], 2,',',' ').'','1','0','R');$treg=$treg+$row_v['mtt_reg'];
        $pdf->Cell(30,5,''.number_format($row_v['mtt_solde'], 2,',',' ').'','1','0','R');$tsolde=$tsolde+$row_v['mtt_solde'];
    }
    $pdf->Ln();
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(190,5,'TOTAUX','1','0','L');$pdf->Cell(35,5,''.number_format($tpn, 2,',',' ').'','1','0','R');$pdf->Cell(30,5,''.number_format($tcp, 2,',',' ').'','1','0','R');$pdf->Cell(35,5,''.number_format($tpc, 2,',',' ').'','1','0','R');$pdf->Cell(20,5,''.number_format($tdt, 2,',',' ').'','1','0','R');$pdf->Cell(35,5,''.number_format($tpt, 2,',',' ').'','1','0','R');$pdf->Cell(30,5,''.number_format($treg, 2,',',' ').'','1','0','R');$pdf->Cell(30,5,''.number_format($tsolde, 2,',',' ').'','1','0','R');

    $pdf->Output();


















}
?>