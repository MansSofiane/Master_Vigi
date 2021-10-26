<?php session_start();
require_once("../../../data/connAGB.php");
if ($_SESSION['login2']){$user=$_SESSION['id_user2'];}
else {
    header("Location:../index.html?erreur=login"); // redirection en cas d'echec
}

//if (isset($_REQUEST['d1']) && isset($_REQUEST['p']) && isset($_REQUEST['d2'])) {
 //  $date1 = $_REQUEST['d1'];
// $date2 = $_REQUEST['d2'];

if (isset($_REQUEST['warda'])) {$row = substr($_REQUEST['warda'],10);}

    $datesys=date("Y/m/d");
    include("convert.php");
    require('tfpdf.php');
    class PDF extends TFPDF
    {
// En-t?te
        function Header()
        {
            $this->SetFont('Arial','B',10);
            $this->Image('../img/entete_bna.png',6,4,400);
            $this->Cell(150,5,'','O','0','L');
            $this->SetFont('Arial','B',12);
            // $this->Cell(60,5,'MAPFRE | Assistance','O','0','L');
            $this->SetFont('Arial','B',10);
            $this->Ln(50);
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
    $pdf = new PDF('L','mm','A3');
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',12);
    $pdf->SetFillColor(199,139,85);
    $pdf->SetFont('Arial','B',15);

    $tpn=0;$tcp=0;$tpc=0;$tdt=0;$tpt=0;
//Parametres

  //  $rqtp = $bdd->prepare("SELECT a.`agence`, p.`lib_prod`,p.`code_prod` FROM `utilisateurs` as a,`produit` as p WHERE p.cod_prod='$prod' and a.id_user='$user'");
  //  $rqtp->execute();
//requete pour les contrats
   // $rqtg = $bdd->prepare("SELECT d.`dat_val`,d.`dat_eff`,d.`dat_ech`,d.dat_op,d.`sequence`,d.cap1,d.p3,d.`pn`,d.`pt`,d.prorata,d.`mensu_pay`,d.type_credit,t.`mtt_dt`,c.`mtt_cpl`,p.`code_prod`,p.`lib_prod` ,s.`nom_sous`, s.`pnom_sous`,u.`agence` FROM `policew` as d, `dtimbre` as t , `cpolice` as c,`produit` as p,`souscripteurw` as s,  `utilisateurs` as u  WHERE d.`cod_dt`=t.`cod_dt` AND d.`cod_cpl`=c.`cod_cpl` AND d.`cod_prod`=p.`cod_prod` AND d.`cod_sous`=s.`cod_sous`  AND s.`id_user`=u.`id_user` AND d.`cod_prod`='$prod' AND DATE_FORMAT(d.`dat_val`,'%Y-%m-%d')>='$date1' AND DATE_FORMAT(d.`dat_val`,'%Y-%m-%d')<='$date2'");
   // $rqtg->execute();

    $rqtg = $bdd->prepare("SELECT d.`dat_val`,d.`dat_eff`,d.`dat_ech`,d.dat_op,d.`sequence`,d.cap1,d.p3,d.`pn`,d.`pt`,d.prorata,d.`mensu_pay`,d.type_credit,t.`mtt_dt`,c.`mtt_cpl`,p.`code_prod`,p.`lib_prod` ,
                                  s.`nom_sous`, s.`pnom_sous`,u.`agence`
                           FROM `policew` as d, `dtimbre` as t , `cpolice` as c,`produit` as p,`souscripteurw` as s,  `utilisateurs` as u
                           WHERE d.`cod_dt`=t.`cod_dt` AND d.`cod_cpl`=c.`cod_cpl` AND d.`cod_prod`=p.`cod_prod` AND d.`cod_sous`=s.`cod_sous`  AND s.`id_user`=u.`id_user` AND d.`cod_pol`='$row'");
    $rqtg->execute();
//requete pour les avenants positifs
   // $rqtv = $bdd->prepare("SELECT  d.`dat_val`,d.`dat_eff`,d.`dat_ech`,d.dat_op,z.cap1,d.p3,d.`pn`,d.`pn`,d.`pt`,d.prorata,d.`mensu_pay`,d.`lib_mpay`,d.`sequence`
	//	,z.type_credit,t.`mtt_dt`,c.`mtt_cpl`,p.`code_prod`
	//	,p.`lib_prod`, s.`cod_sous`,s.`nom_sous`, s.`pnom_sous`,z.sequence as seq2, z.dat_val as datev,u.`agence`  FROM `avenantw` as d,`policew` as z, `dtimbre` as t , `cpolice` as c,`produit` as p,`souscripteurw` as s,`utilisateurs` as u  WHERE d.`cod_dt`=t.`cod_dt` AND d.`cod_cpl`=c.`cod_cpl` AND d.`cod_prod`=p.`cod_prod` AND d.`cod_pol`=z.`cod_pol` AND z.`cod_sous`=s.`cod_sous` AND s.`id_user`=u.`id_user` AND d.`cod_prod`='$prod' AND DATE_FORMAT(d.`dat_val`,'%Y-%m-%d')>='$date1' AND DATE_FORMAT(d.`dat_val`,'%Y-%m-%d')<='$date2'  and d.`lib_mpay` not in ('30','50') order by d.`lib_mpay`");

    $rqtv = $bdd->prepare("SELECT  d.`dat_val`,d.`dat_eff`,d.`dat_ech`,d.dat_op,z.cap1,d.p3,d.`pn`,d.`pn`,d.`pt`,d.prorata,d.`mensu_pay`,d.`lib_mpay`,d.`sequence` ,z.type_credit,t.`mtt_dt`,c.`mtt_cpl`,
	                           p.`code_prod`,p.`lib_prod`, s.`cod_sous`,s.`nom_sous`, s.`pnom_sous`,z.sequence as seq2, z.dat_val as datev,u.`agence`
                       FROM `avenantw` as d,`policew` as z, `dtimbre` as t , `cpolice` as c,`produit` as p,`souscripteurw` as s,`utilisateurs` as u
                       WHERE d.`cod_dt`=t.`cod_dt` AND d.`cod_cpl`=c.`cod_cpl` AND d.`cod_prod`=p.`cod_prod` AND d.`cod_pol`=z.`cod_pol` AND z.`cod_sous`=s.`cod_sous` AND s.`id_user`=u.`id_user`
                              AND d.`cod_pol`='$row' and d.`lib_mpay`= 20 ");

    $rqtv->execute();

    //requete pour les avenants sans ristourne
   // $rqtvsr = $bdd->prepare("SELECT d.`dat_val`,z.cap1,d.p3,d.`pn`,d.`pn`,d.`pt`,d.prorata,d.`mensu_pay`,d.`lib_mpay`,d.`sequence`,z.type_credit,t.`mtt_dt`,c.`mtt_cpl`,p.`code_prod`,p.`lib_prod`, s.`cod_sous`,s.`nom_sous`, s.`pnom_sous`,z.sequence as seq2, z.dat_val as datev,u.`agence`  FROM `avenantw` as d,`policew` as z, `dtimbre` as t , `cpolice` as c,`produit` as p,`souscripteurw` as s,`utilisateurs` as u  WHERE d.`cod_dt`=t.`cod_dt` AND d.`cod_cpl`=c.`cod_cpl` AND d.`cod_prod`=p.`cod_prod` AND d.`cod_pol`=z.`cod_pol` AND z.`cod_sous`=s.`cod_sous` AND s.`id_user`=u.`id_user` AND d.`cod_prod`='$prod' AND DATE_FORMAT(d.`dat_val`,'%Y-%m-%d')>='$date1' AND DATE_FORMAT(d.`dat_val`,'%Y-%m-%d')<='$date2'  and d.`lib_mpay`  in ('50')");
   // $rqtvsr->execute();

    //requete pour les avenants avec ristourne
   // $rqtvar = $bdd->prepare("SELECT d.`dat_val`,z.cap1,d.p3,d.`pn`,d.`pn`,d.`pt`,d.prorata,d.`mensu_pay`,d.`lib_mpay`,d.`sequence`,z.type_credit,t.`mtt_dt`,c.`mtt_cpl`,p.`code_prod`,p.`lib_prod`, s.`cod_sous`,s.`nom_sous`, s.`pnom_sous`,z.sequence as seq2, z.dat_val as datev,u.`agence`  FROM `avenantw` as d,`policew` as z, `dtimbre` as t , `cpolice` as c,`produit` as p,`souscripteurw` as s,`utilisateurs` as u  WHERE d.`cod_dt`=t.`cod_dt` AND d.`cod_cpl`=c.`cod_cpl` AND d.`cod_prod`=p.`cod_prod` AND d.`cod_pol`=z.`cod_pol` AND z.`cod_sous`=s.`cod_sous` AND s.`id_user`=u.`id_user` AND d.`cod_prod`='$prod' AND DATE_FORMAT(d.`dat_val`,'%Y-%m-%d')>='$date1' AND DATE_FORMAT(d.`dat_val`,'%Y-%m-%d')<='$date2'  and d.`lib_mpay`  in ('30')");
   // $rqtvar->execute();


    $pdf->Cell(400,10,'Avenants de Ressortie de Prime                                                                                       --Document généré le-- '.date("d/m/Y", strtotime($datesys)) ,'1','1','L','1');
   /* while ($row_p=$rqtp->fetch()){
        $pdf->Cell(130,10,'Reseau°: '.$row_p['agence'],'1','0','C');$pdf->Cell(130,10,'Produit: '.$row_p['lib_prod'],'1','0','C');$pdf->Cell(140,10,'Code produit: '.$row_p['code_prod'],'1','1','C');
    }*/
    $pdf->Ln();
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(30,5,'Adhesion N°','1','0','C');$pdf->Cell(30,5,'Avenant N°','1','0','C');$pdf->Cell(70,5,'Nom&Prénom','1','0','C');
    $pdf->Cell(30,5,'D-Emmision','1','0','C');$pdf->Cell(30,5,'D-Effet','1','0','C');$pdf->Cell(30,5,'D-Echéance','1','0','C');$pdf->Cell(30,5,'D-prélèvement','1','0','C');
    ;$pdf->Cell(30,5,'Capital','1','0','C');
    $pdf->Cell(30,5,'P-Mensuelle','1','0','C');$pdf->Cell(30,5,'Reg-Prorata','1','0','C');$pdf->Cell(30,5,'Reg-Mensualité','1','0','C');$pdf->Cell(30,5,'Règlement global','1','0','C');
//Boucle police
    while ($row_g=$rqtg->fetch()){
        $date_pre=$row_g['dat_op'];
        $pdf->SetFillColor(221,221,221);
        $pdf->Ln();
        $pdf->SetFont('Arial','B',10);
        $pdf->SetFillColor(255,255,255);
        $pdf->SetFont('Arial','B',8);
//Reporting Polices
        $pdf->Cell(30,5,''.str_pad((int) $row_g['sequence'],'5',"0",STR_PAD_LEFT).'','1','0','C');
        $pdf->Cell(30,5,'--','1','0','C');
        $pdf->Cell(70,5,"".$row_g['nom_sous'].' '.$row_g['pnom_sous']."",'1','0','C');
        $pdf->Cell(30,5,''.date("d/m/Y", strtotime($row_g['dat_val'])).'','1','0','C');$pdf->Cell(30,5,''.date("d/m/Y", strtotime($row_g['dat_eff'])).'','1','0','C');$pdf->Cell(30,5,''.date("d/m/Y", strtotime($row_g['dat_ech'])).'','1','0','C');;$pdf->Cell(30,5,''.date("d/m/Y", strtotime($row_g['dat_op'])).'','1','0','C');
        $pdf->Cell(30,5,''.number_format($row_g['cap1'], 2,',',' ').'','1','0','R');
        $pdf->Cell(30,5,''.number_format($row_g['p3'], 2,',',' ').'','1','0','R');$tpn=$tpn+$row_g['p3'];
        $pdf->Cell(30,5,''.number_format($row_g['prorata'], 2,',',' ').'','1','0','R');$tcp=$tcp+$row_g['prorata'];
        $pdf->Cell(30,5,''.number_format($row_g['mensu_pay'], 2,',',' ').'','1','0','R');$tpt=$tpt+$row_g['mensu_pay'];
        $pdf->Cell(30,5,''.number_format($row_g['mensu_pay']+$row_g['prorata'], 2,',',' ').'','1','0','R');$tpc=$tpc+$row_g['mensu_pay']+$row_g['prorata'];


    }
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial','IB',10);
    $pdf->SetFillColor(192,195,198);

   // $pdf->Cell(280,5,'TOTAL, Polices  ','1','0','L','1');

   // $pdf->Cell(30,5,''.number_format($tpn, 2,',',' ').'','1','0','R','1');
   // $pdf->Cell(30,5,''.number_format($tcp, 2,',',' ').'','1','0','R','1');
  //  $pdf->Cell(30,5,''.number_format($tpt, 2,',',' ').'','1','0','R','1');
   // $pdf->Cell(30,5,''.number_format($tpc, 2,',',' ').'','1','0','R','1');



    $ppn_av=0;$ppt_av=0;$pcpol_av=0;$pccom_av=0;$pdtim_av=0;//1
//boucle Avenants POSITIFS
    while ($row_v=$rqtv->fetch()){
        $pdf->SetFillColor(221,221,221);
        $pdf->Ln();
        $pdf->SetFont('Arial','B',10);
        $pdf->SetFillColor(255,255,255);
        $pdf->SetFont('Arial','B',8);
//Reporting Polices
        $pdf->Cell(30,5,''.str_pad((int) $row_v['seq2'],'5',"0",STR_PAD_LEFT).'','1','0','C');
        $pdf->Cell(30,5,''.str_pad((int) $row_v['sequence'],'5',"0",STR_PAD_LEFT).'','1','0','C');
        $pdf->Cell(70,5,"".$row_v['nom_sous'].' '.$row_v['pnom_sous']."",'1','0','C');
        $pdf->Cell(30,5,''.date("d/m/Y", strtotime($row_v['dat_val'])).'','1','0','C');$pdf->Cell(30,5,''.date("d/m/Y", strtotime($row_v['dat_eff'])).'','1','0','C');$pdf->Cell(30,5,''.date("d/m/Y", strtotime($row_v['dat_ech'])).'','1','0','C');;$pdf->Cell(30,5,''.date("d/m/Y", strtotime($row_v['dat_op'])).'','1','0','C');
        $pdf->Cell(30,5,''.number_format($row_v['cap1'], 2,',',' ').'','1','0','R');
        $pdf->Cell(30,5,''.number_format($row_v['p3'], 2,',',' ').'','1','0','R');$tpn=$tpn+$row_v['p3'];/* pn avenant */$ppn_av=$ppn_av+$row_v['p3'];
        $pdf->Cell(30,5,''.number_format($row_v['prorata'], 2,',',' ').'','1','0','R');$tcp=$tcp+$row_v['prorata'];/* cout police avenant*/$pcpol_av=$pcpol_av+$row_v['prorata'];
        $pdf->Cell(30,5,''.number_format($row_v['mensu_pay'], 2,',',' ').'','1','0','R');$tpt=$tpt+$row_v['mensu_pay'];/*prime commerciale avenant*/$ppt_av=$ppt_av+$row_v['mensu_pay'];
        $pdf->Cell(30,5,''.number_format($row_v['mensu_pay']+$row_v['prorata'], 2,',',' ').'','1','0','R');$tpc=$tpc+$row_v['mensu_pay']+$row_v['prorata'];/*prime commerciale avenant*/$pccom_av=$pccom_av+$row_v['mensu_pay']+$row_v['prorata'];

    }
    $pdf->Ln();
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial','IB',10);
    $pdf->SetFillColor(192,195,198);


  //  $pdf->Cell(280,5,'TOTAL, Avenants positifs  ','1','0','L','1');
  //  $pdf->Cell(30,5,''.number_format($ppn_av, 2,',',' ').'','1','0','R','1');
  //  $pdf->Cell(30,5,''.number_format($pcpol_av, 2,',',' ').'','1','0','R','1');
  //  $pdf->Cell(30,5,''.number_format($ppt_av, 2,',',' ').'','1','0','R','1');
  //  $pdf->Cell(30,5,''.number_format($pccom_av, 2,',',' ').'','1','0','R','1');


   // $pdf->Ln();
   // $pdf->SetFillColor(128,126,125);
  //  $pdf->Cell(280,5,'TOTAL, Production positive  ','1','0','L','1');
  //  $pdf->Cell(30,5,''.number_format($tpn, 2,',',' ').'','1','0','R','1');
  //  $pdf->Cell(30,5,''.number_format($tcp, 2,',',' ').'','1','0','R','1');
  //  $pdf->Cell(30,5,''.number_format($tpt, 2,',',' ').'','1','0','R','1');
  //  $pdf->Cell(30,5,''.number_format($tpc, 2,',',' ').'','1','0','R','1');




//boucle Avenants SANS RISTOURNE
  //  $ppn_sr=0;$ppt_sr=0;$pcpol_sr=0;$pccom_sr=0;$pdtim_sr=0;//1
    //boucle Avenants sans ristourne
   // while ($row_vsr=$rqtvsr->fetch()){
   //     $pdf->SetFillColor(221,221,221);
   //     $pdf->Ln();
  //      $pdf->SetFont('Arial','B',10);
  //      $pdf->SetFillColor(255,255,255);
  //      $pdf->SetFont('Arial','B',8);
//Reporting Polices
    //    $pdf->Cell(30,5,''.str_pad((int) $row_vsr['seq2'],'5',"0",STR_PAD_LEFT).'','1','0','C');
  //      $pdf->Cell(30,5,''.str_pad((int) $row_vsr['sequence'],'5',"0",STR_PAD_LEFT).'','1','0','C');
  //      $pdf->Cell(70,5,"".$row_vsr['nom_sous'].' '.$row_vsr['pnom_sous']."",'1','0','C');
  //      $pdf->Cell(30,5,''.date("d/m/Y", strtotime($row_vsr['dat_val'])).'','1','0','C');$pdf->Cell(30,5,'----','1','0','C');$pdf->Cell(30,5,'----','1','0','C');$pdf->Cell(30,5,'----','1','0','C');
  //      $pdf->Cell(30,5,''.number_format($row_vsr['cap1'], 2,',',' ').'','1','0','R');
  //      $pdf->Cell(30,5,''.number_format($row_vsr['p3'], 2,',',' ').'','1','0','R');$tpn=$tpn+$row_vsr['p3'];/* pn avenant */

   //     $ppn_sr=$ppn_sr+$row_vsr['p3'];
   //     $pdf->Cell(30,5,''.number_format($row_vsr['prorata'], 2,',',' ').'','1','0','R');$tcp=$tcp+$row_vsr['prorata'];/* cout police avenant*/$pcpol_sr=$pcpol_sr+$row_vsr['prorata'];
   //     $pdf->Cell(30,5,''.number_format($row_vsr['mensu_pay'], 2,',',' ').'','1','0','R');$tpt=$tpt+($row_vsr['mensu_pay']);/*prime commerciale avenant*/$ppt_sr=$ppt_sr+($row_vsr['mensu_pay']);
   //     $pdf->Cell(30,5,''.number_format($row_vsr['mensu_pay']+$row_vsr['prorata'], 2,',',' ').'','1','0','R');$tpc=$tpc+($row_vsr['mensu_pay']+$row_vsr['prorata']);/*prime commerciale avenant*/$pccom_sr=$pccom_sr+($row_vsr['mensu_pay']+$row_vsr['prorata']);


   // }
  //  $pdf->Ln();
   // $pdf->SetTextColor(0, 0, 0);
   // $pdf->SetFont('Arial','IB',10);
  //  $pdf->SetFillColor(128,126,125);

  //  $pdf->Cell(280,5,'TOTAL, Avenants sans ristourne  ','1','0','L','1');

   // $pdf->Cell(30,5,''.number_format($ppn_sr, 2,',',' ').'','1','0','R','1');
  //  $pdf->Cell(30,5,''.number_format($pcpol_sr, 2,',',' ').'','1','0','R','1');
 //   $pdf->Cell(30,5,''.number_format($ppt_sr, 2,',',' ').'','1','0','R','1');
 //   $pdf->Cell(30,5,''.number_format($pccom_sr, 2,',',' ').'','1','0','R','1');



//boucle Avenants AVEC RISTOURNE
 //   $ppn_ar=0;$ppt_ar=0;$pcpol_ar=0;$pccom_ar=0;$pdtim_ar=0;//1
    //boucle Avenants sans ristourne
 //   while ($row_var=$rqtvar->fetch()){
//        $pdf->SetFillColor(221,221,221);
  //      $pdf->Ln();
  //      $pdf->SetFont('Arial','B',10);
   //     $pdf->SetFillColor(255,255,255);
   //     $pdf->SetFont('Arial','B',8);
//Reporting Polices
   //     $pdf->Cell(30,5,''.str_pad((int) $row_var['seq2'],'5',"0",STR_PAD_LEFT).'','1','0','C');
    //    $pdf->Cell(30,5,''.str_pad((int) $row_var['sequence'],'5',"0",STR_PAD_LEFT).'','1','0','C');
   //     $pdf->Cell(70,5,"".$row_var['nom_sous'].' '.$row_var['pnom_sous']."",'1','0','C');
   //     $pdf->Cell(30,5,''.date("d/m/Y", strtotime($row_var['dat_val'])).'','1','0','C');$pdf->Cell(30,5,'----','1','0','C');$pdf->Cell(30,5,'----','1','0','C');;$pdf->Cell(30,5,'----','1','0','C');
   //     $pdf->Cell(30,5,''.number_format($row_var['cap1'], 2,',',' ').'','1','0','R');
   //     $pdf->Cell(30,5,''.number_format($row_var['p3'], 2,',',' ').'','1','0','R');$tpn=$tpn+$row_var['p3'];/* pn avenant */$ppn_ar=$ppn_ar+$row_var['p3'];
   //     $pdf->Cell(30,5,''.number_format($row_var['prorata'], 2,',',' ').'','1','0','R');$tcp=$tcp+$row_var['prorata'];/* cout police avenant*/$pcpol_ar=$pcpol_ar+$row_var['prorata'];
   //     $pdf->Cell(30,5,''.number_format($row_var['mensu_pay'], 2,',',' ').'','1','0','R');$tpt=$tpt+($row_var['mensu_pay']);/*prime commerciale avenant*/$ppt_ar=$ppt_ar+($row_var['mensu_pay']);
   //     $pdf->Cell(30,5,''.number_format($row_var['mensu_pay']+$row_var['prorata'], 2,',',' ').'','1','0','R');$tpc=$tpc+($row_var['mensu_pay']+$row_var['prorata']);/*prime commerciale avenant*/$pccom_ar=$pccom_ar+($row_var['mensu_pay']+$row_var['prorata']);


    //}
  //  $pdf->Ln();
  //  $pdf->SetTextColor(0, 0, 0);
 //   $pdf->SetFont('Arial','IB',10);
 //   $pdf->SetFillColor(128,126,125);


 //   $pdf->Cell(280,5,'TOTAL, Avenants Avec ristourne  ','1','0','L','1');

 //   $pdf->Cell(30,5,''.number_format($ppn_ar, 2,',',' ').'','1','0','R','1');
  //  $pdf->Cell(30,5,''.number_format($pcpol_ar, 2,',',' ').'','1','0','R','1');
  //  $pdf->Cell(30,5,''.number_format($ppt_ar, 2,',',' ').'','1','0','R','1');
  //  $pdf->Cell(30,5,''.number_format($pccom_ar, 2,',',' ').'','1','0','R','1');


  //  $pdf->Ln();
    $pdf->SetFillColor(180,203,106);
  $pdf->Cell(280,5,'TOTAL','1','0','L','1');$pdf->Cell(30,5,''.number_format($tpn, 2,',',' ').'','1','0','R','1');$pdf->Cell(30,5,''.number_format($tcp, 2,',',' ').'','1','0','R','1');$pdf->Cell(30,5,''.number_format($tpt, 2,',',' ').'','1','0','R','1');$pdf->Cell(30,5,''.number_format($tpc, 2,',',' ').'','1','0','R','1');

    $pdf->Output();

?>