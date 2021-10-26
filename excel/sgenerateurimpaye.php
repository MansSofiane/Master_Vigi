<?php
error_reporting(E_ALL);
require_once dirname(__FILE__) . '/Classes/PHPExcel.php';


////////////////////////////////////--------------------------------------------------//////////////////////////////////////////////////////
$condition_ag='';
$condition_pr="";

if($prod !=100) {
    $condition_pr = " and p.cod_prod='$prod' ";

    $rqtp = $bdd->prepare("SELECT `lib_prod`,code_prod FROM `produit` WHERE cod_prod='$prod'");
    $rqtp->execute();
    $code_prod="";
    while ($row_p=$rqtp->fetch()){
        $produit=$row_p['lib_prod'];

    }


}
else
{
    $produit="Tous les produits";
    $condition_pr='';
}

if($user=='0')
{
    $condition_ag="";
}
else

{
    if($type =='user'){
        $condition_ag="  and u.agence='$user' ";

    }
    else {
        $condition_ag = " and   u.`id_par`='$id_ag' ";
    }
}

$lib_type = "ETAT  DES PRIMES IMPAYEES";



$rqtg = $bdd->prepare("SELECT p.cod_pol,''  as avenant,p.cod_prod,p.sequence sequence_police,'' as sequence_av,p.dat_val dat_pol,p.dat_val as dat_avis,'' dateavanant,p.dat_eff,p.dat_ech,p.pn,p.pt,s.nom_sous,s.pnom_sous,s.dnais_sous,p.mtt_reg,
                                        case p.mtt_reg when 0 then  p.mtt_solde-c.mtt_cpl-d.mtt_dt else p.mtt_solde end base_pn_commission,case p.mtt_reg when 0 then  c.mtt_cpl else 0 end mtt_cpl,
                                        case p.mtt_reg when 0 then  d.mtt_dt else 0 end mtt_dt,p.mtt_solde  mtt_avis,u.agence,d.mtt_dt as mtt_dtp,c.mtt_cpl as mtt_cplp
                                        , case when g.typ_agence='0' then 'Affaire Directe' when g.typ_agence='1' then 'Convention Voyage' when g.typ_agence='2' then 'Courtier' end type_apporteur,g.lib_agence
 ,pr.code_prod,pr.lib_prod

                                from policew as p,souscripteurw as s, cpolice as c, dtimbre as d,utilisateurs u,agence g,produit pr
                                where p.cod_sous=s.cod_sous
                                 and s.id_user=u.id_user
                                 and p.cod_cpl=c.cod_cpl
                                 and p.cod_dt=d.cod_dt
                                  and p.cod_prod=pr.cod_prod
                                  and p.cod_agence=g.cod_agence
                                  $condition_ag  $condition_pr
                                  and DATE_FORMAT(p.dat_val,'%Y-%m-%d')  between '$dated' and '$datef' and p.mtt_solde>0");

$rqtg->execute();

$tdr="SELECT p.cod_pol,''  as avenant,p.cod_prod,p.sequence sequence_police,v.sequence as sequence_av,v.dat_val dat_pol,v.dat_val as dat_avis,v.dat_val as dateavanant,v.dat_eff,v.dat_ech,v.pn,v.pt,s.nom_sous,s.pnom_sous,s.dnais_sous,
                                              v.mtt_reg,case v.mtt_reg when 0 then  v.mtt_solde-c.mtt_cpl-d.mtt_dt else v.mtt_solde end base_pn_commission,case v.mtt_reg when 0 then  c.mtt_cpl else 0 end mtt_cpl,
                                              case v.mtt_reg when 0 then  d.mtt_dt else 0 end mtt_dt,v.mtt_solde  mtt_avis,v.lib_mpay,u.agence,d.mtt_dt as mtt_dtp,c.mtt_cpl as mtt_cplp
                                              , case when g.typ_agence='0' then 'Affaire Directe' when g.typ_agence='1' then 'Convention Voyage' when g.typ_agence='2' then 'Courtier' end type_apporteur,g.lib_agence
,pr.code_prod,pr.lib_prod
                                  from policew as p,souscripteurw as s, cpolice as c, dtimbre as d, avenantw as v,utilisateurs u,agence g,produit pr
                                  where p.cod_sous=s.cod_sous
                                  and s.id_user=u.id_user
                                  and v.cod_cpl=c.cod_cpl
                                  and v.cod_dt=d.cod_dt
                                  and p.cod_pol=v.cod_pol
                                  and p.cod_prod=pr.cod_prod
                                  and v.cod_prod=pr.cod_prod
                                  and p.cod_agence=g.cod_agence
                                  $condition_ag  $condition_pr
                                 and DATE_FORMAT(v.dat_val,'%Y-%m-%d') between '$dated' and '$datef' and v.mtt_solde>0 and v.lib_mpay not in ('30','50')";

$rqtv = $bdd->prepare("SELECT p.cod_pol,''  as avenant,p.cod_prod,p.sequence sequence_police,v.sequence as sequence_av,v.dat_val dat_pol,v.dat_val as dat_avis,v.dat_val as dateavanant,v.dat_eff,v.dat_ech,v.pn,v.pt,s.nom_sous,s.pnom_sous,s.dnais_sous,
                                              v.mtt_reg,case v.mtt_reg when 0 then  v.mtt_solde-c.mtt_cpl-d.mtt_dt else v.mtt_solde end base_pn_commission,case v.mtt_reg when 0 then  c.mtt_cpl else 0 end mtt_cpl,
                                              case v.mtt_reg when 0 then  d.mtt_dt else 0 end mtt_dt,v.mtt_solde  mtt_avis,v.lib_mpay,u.agence,d.mtt_dt as mtt_dtp,c.mtt_cpl as mtt_cplp
                                              , case when g.typ_agence='0' then 'Affaire Directe' when g.typ_agence='1' then 'Convention Voyage' when g.typ_agence='2' then 'Courtier' end type_apporteur,g.lib_agence
,pr.code_prod,pr.lib_prod
                                  from policew as p,souscripteurw as s, cpolice as c, dtimbre as d, avenantw as v,utilisateurs u,agence g,produit pr
                                  where p.cod_sous=s.cod_sous
                                  and s.id_user=u.id_user
                                  and v.cod_cpl=c.cod_cpl
                                  and v.cod_dt=d.cod_dt
                                  and p.cod_pol=v.cod_pol
                                  and p.cod_prod=pr.cod_prod
                                  and v.cod_prod=pr.cod_prod
                                  and p.cod_agence=g.cod_agence
                                  $condition_ag  $condition_pr
                                 and DATE_FORMAT(v.dat_val,'%Y-%m-%d') between '$dated' and '$datef' and v.mtt_solde>0 and v.lib_mpay not in ('30','50')");


$rqtv->execute();



////////////////////////////////////////////////////////////////////////////////////////////////////---------------------------------------------/////////////////////////////


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
//fusioner les cellules de l'entete
$objPHPExcel->getActiveSheet()->mergeCells('A2:C3');
// Entete
$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('entete');
$objDrawing->setDescription('entete');
$objDrawing->setPath('../img/logo.png');
$objDrawing->setHeight(46);
$objDrawing->setCoordinates('A2');
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
//site Web
$objPHPExcel->getActiveSheet()->mergeCells('A4:C4');
$objPHPExcel->getActiveSheet()->getStyle('A4')->applyFromArray(
    array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
    )
);
$objPHPExcel->getActiveSheet()->setCellValue('A4', 'www.aglic.dz');
//entete
$objPHPExcel->getActiveSheet()->mergeCells('F2:H2');
$objPHPExcel->getActiveSheet()->setCellValue('F2', 'PRIMES IMPAYEES'.$tdr);
$objPHPExcel->getActiveSheet()->mergeCells('F3:H3');
$clt="Reseau CASH";
$objPHPExcel->getActiveSheet()->setCellValue('F3',''. $clt);
$objPHPExcel->getActiveSheet()->setCellValue('I2', 'Du: '.date("d/m/Y", strtotime($dated)));
$objPHPExcel->getActiveSheet()->setCellValue('I3', 'Au: '.date("d/m/Y", strtotime($datef)));
$objPHPExcel->getActiveSheet()->getStyle('F2')->getFont()->setColor(new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_WHITE ));
$objPHPExcel->getActiveSheet()->getStyle('F3')->getFont()->setColor(new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_WHITE ));
$objPHPExcel->getActiveSheet()->getStyle('I2')->getFont()->setColor(new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_WHITE ));
$objPHPExcel->getActiveSheet()->getStyle('I3')->getFont()->setColor(new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_WHITE ));
$objPHPExcel->getActiveSheet()->getStyle('F2')->getFont()->setName('Candara');
$objPHPExcel->getActiveSheet()->getStyle('F2')->getFont()->setSize(15);
$objPHPExcel->getActiveSheet()->getStyle('F2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('F3')->getFont()->setName('Candara');
$objPHPExcel->getActiveSheet()->getStyle('F3')->getFont()->setSize(15);
$objPHPExcel->getActiveSheet()->getStyle('F3')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('I2')->getFont()->setName('Candara');
$objPHPExcel->getActiveSheet()->getStyle('I2')->getFont()->setSize(20);
$objPHPExcel->getActiveSheet()->getStyle('I2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('I3')->getFont()->setName('Candara');
$objPHPExcel->getActiveSheet()->getStyle('I3')->getFont()->setSize(20);
$objPHPExcel->getActiveSheet()->getStyle('I3')->getFont()->setBold(true);
//Font entete
$objPHPExcel->getActiveSheet()->getStyle('D2:J3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('D2:J3')->getFill()->getStartColor()->setARGB('A25932');
// Ordre et numero
$objPHPExcel->getActiveSheet()->setCellValue('A6', 'CASH');
if($user=='0')
{
    $objPHPExcel->getActiveSheet()->setCellValue('A7', 'Direction Generale :'. '   Toutes les agences');
}
else {
    if($type =='user'){
        $objPHPExcel->getActiveSheet()->setCellValue('A7', 'Direction Generale :'. 'Agence :'  . $user);
    }
    else {
        $objPHPExcel->getActiveSheet()->setCellValue('A7',  'Direction Generale :'.'   DRE :' . $user);
    }
}
$objPHPExcel->getActiveSheet()->setCellValue('A8', 'Produit : '.$produit);
$objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setColor(new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_WHITE ));
$objPHPExcel->getActiveSheet()->getStyle('A7')->getFont()->setColor(new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_WHITE ));
$objPHPExcel->getActiveSheet()->getStyle('A6:F6')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A7:F7')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A6:F7')->getFill()->getStartColor()->setARGB('A25932');
$objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setName('Candara');
$objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setSize(15);
$objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A7')->getFont()->setName('Candara');
$objPHPExcel->getActiveSheet()->getStyle('A7')->getFont()->setSize(15);
$objPHPExcel->getActiveSheet()->getStyle('A7')->getFont()->setBold(true);
// Bordure de table
$objPHPExcel->getActiveSheet()->getStyle('A9:W9')->applyFromArray(
    array(
        'font'    => array(
            'bold'      => true
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
        ),
        'borders' => array(
            'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
            )
        ),
        'fill' => array(
            'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
            'rotation'   => 90,
            'startcolor' => array(
                'argb' => 'FFA0A0A0'
            ),
            'endcolor'   => array(
                'argb' => 'FFFFFFFF'
            )
        )
    )
);
for($lettr='A'; $lettr!='W';$lettr++){
    $objPHPExcel->getActiveSheet()->getStyle($lettr.'9')->applyFromArray(
        array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
                'right'    => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        )
    );
}
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(40);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(25);
//Premieres Colones du tableau
$objPHPExcel->getActiveSheet()->setCellValue('A9', 'N');
$objPHPExcel->getActiveSheet()->setCellValue('B9', 'Code-Agence');
$objPHPExcel->getActiveSheet()->setCellValue('C9', 'Type-acte');//avis de recette ou depense
$objPHPExcel->getActiveSheet()->setCellValue('D9', 'Police-Numero');
$objPHPExcel->getActiveSheet()->setCellValue('E9', 'Avenant-Numero');
$objPHPExcel->getActiveSheet()->setCellValue('F9', 'Souscripteur');
$objPHPExcel->getActiveSheet()->setCellValue('G9', 'D-Naissance');
$objPHPExcel->getActiveSheet()->setCellValue('H9', 'Produit');
$objPHPExcel->getActiveSheet()->setCellValue('I9', 'D.Souscription');
$objPHPExcel->getActiveSheet()->setCellValue('J9', 'D.Effet');
$objPHPExcel->getActiveSheet()->setCellValue('K9', 'D.Echeance');
$objPHPExcel->getActiveSheet()->setCellValue('L9', 'P.nette');
$objPHPExcel->getActiveSheet()->setCellValue('M9', 'C.Police');
$objPHPExcel->getActiveSheet()->setCellValue('N9', 'P.Commerciale');
$objPHPExcel->getActiveSheet()->setCellValue('O9', 'D.Timbre');
$objPHPExcel->getActiveSheet()->setCellValue('P9', 'P.Total');
$objPHPExcel->getActiveSheet()->getStyle('Q9:U9')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('Q9:U9')->getFill()->getStartColor()->setARGB('e4e5e3');
$objPHPExcel->getActiveSheet()->setCellValue('Q9', 'P.nette-impayee');
$objPHPExcel->getActiveSheet()->setCellValue('R9', 'C.Police-impaye');
$objPHPExcel->getActiveSheet()->setCellValue('S9', 'P.Commerciale-impayee');
$objPHPExcel->getActiveSheet()->setCellValue('T9', 'D.Timbre-impaye');
$objPHPExcel->getActiveSheet()->setCellValue('U9', 'P.Total-impayee');
$objPHPExcel->getActiveSheet()->setCellValue('V9', 'Type-affaire');
$objPHPExcel->getActiveSheet()->setCellValue('W9', 'Apporteur-Affaire');



$styleThinBlackBorderOutline = array(
    'borders' => array(
        'outline' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb' => 'FF000000'),
        ),
    ),
);
$objPHPExcel->getActiveSheet()->getStyle('A9:W9')->applyFromArray($styleThinBlackBorderOutline);
//Recuperation des données de l'opération

//$connection->requete($query_ann);
$cpt='0';
$j=9;
//$tp1=0;$tp2=0;$tp3=0;$tpn=0;$tcp=0;$tpc=0;$tdt=0;$tpt=0;$tmtt_reg=0;$tmtt_solde=0;
$tpn=0;$tcpl=0;$tdt=0;$tcom=0;$ttc=0;$caissepn=0;$caissecpl=0;$caissedt=0;$caissecom=0;$caissettc=0;





//*************************************Debut de la boucle generation des polices*****************

//while ($row_ann2 =$connection->enr_actuel()){
while ($row_g=$rqtg->fetch()){

    $cpt++;
    $j=9+$cpt;

    $objPHPExcel->getActiveSheet()->setCellValue('A'.$j, $cpt);
    $objPHPExcel->getActiveSheet()->setCellValue('B'.$j, $row_g['agence']);
    $objPHPExcel->getActiveSheet()->setCellValue('C'.$j, 'Nouvelle police');
    $objPHPExcel->getActiveSheet()->setCellValue('D'.$j, $row_g['agence'].'.'.substr($row_g['dat_pol'],0,4).'.10.'.$row_g['code_prod'].'.'.str_pad((int) $row_g['sequence_police'],'5',"0",STR_PAD_LEFT));
    $objPHPExcel->getActiveSheet()->setCellValue('E'.$j, '--');

    $objPHPExcel->getActiveSheet()->setCellValue('F'.$j, $row_g['nom_sous'].' '.$row_g['pnom_sous']);
    $objPHPExcel->getActiveSheet()->setCellValue('G'.$j, date("d/m/Y", strtotime($row_g['dnais_sous'])));
    $objPHPExcel->getActiveSheet()->setCellValue('H'.$j, $row_g['lib_prod']);
    $objPHPExcel->getActiveSheet()->setCellValue('I'.$j, date("d/m/Y", strtotime($row_g['dat_pol'])));
    $objPHPExcel->getActiveSheet()->setCellValue('J'.$j, date("d/m/Y", strtotime($row_g['dat_eff'])));
    $objPHPExcel->getActiveSheet()->setCellValue('K'.$j, date("d/m/Y", strtotime($row_g['dat_ech'])));
    $objPHPExcel->getActiveSheet()->setCellValue('L'.$j, $row_g['pn']);$tpn+=$row_g['pn'];
    $objPHPExcel->getActiveSheet()->setCellValue('M'.$j, $row_g['mtt_cplp']);$tcpl+=$row_g['mtt_cplp'];
    $pc1=$row_g['pt']-$row_g['mtt_dtp'];
    $objPHPExcel->getActiveSheet()->setCellValue('N'.$j, $pc1);$tcom+=$pc1;
    $objPHPExcel->getActiveSheet()->setCellValue('O'.$j, $row_g['mtt_dtp']);$tdt+=$row_g['mtt_dtp'];
    $objPHPExcel->getActiveSheet()->setCellValue('P'.$j, $row_g['pt']);$ttc+=$row_g['pt'];
    $objPHPExcel->getActiveSheet()->setCellValue('Q'.$j, $row_g['base_pn_commission']);$caissepn+= $row_g['base_pn_commission'];//code avis de recette/depense agence/ER/NUMERO
    $objPHPExcel->getActiveSheet()->setCellValue('R'.$j, $row_g['mtt_cpl']);$caissecpl+=$row_g['mtt_cpl'];
    $objPHPExcel->getActiveSheet()->setCellValue('S'.$j, $row_g['base_pn_commission']+$row_g['mtt_cpl']);$caissecom+=$row_g['base_pn_commission']+$row_g['mtt_cpl'];
    $objPHPExcel->getActiveSheet()->setCellValue('T'.$j, $row_g['mtt_dt']);$caissedt+=$row_g['mtt_dt'];
    $objPHPExcel->getActiveSheet()->setCellValue('U'.$j,  $row_g['mtt_avis']);$caissettc+=$row_g['mtt_avis'];
    $objPHPExcel->getActiveSheet()->setCellValue('V'.$j,  $row_g['type_apporteur']);
    $objPHPExcel->getActiveSheet()->setCellValue('W'.$j,  $row_g['lib_agence']);

}
// *******************************************Fin de la boucle Contrat*******************

// debut de la boucle Avenant


// **************************************Deuxieme Boucle des avenants***************************

//$connection->requete($query_av);

//while ($row_av=$connection->enr_actuel()){
while ($row_v=$rqtv->fetch()){
    $cpt++;
    $j=9+$cpt;


//Libelle du type de l'avenant
    $typav="";
    if($row_v['lib_mpay']==74){$typav="Avenant-Modification-Date";}
    if($row_v['lib_mpay']==50){$typav="Avenant-Annulation-Sans-Ristourne";}
    if($row_v['lib_mpay']==30){$typav="Avenant-Annulation-Avec-Ristourne";}
    if($row_v['lib_mpay']==70){$typav="Avenant-Precision";}
    if($row_v['lib_mpay']==14){$typav="Avenant-Changement-Destination";}
    if($row_v['lib_mpay']==73){$typav="Avenant-Subrogation";}

    $objPHPExcel->getActiveSheet()->getStyle('A'.$j.':Y'.$j)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $objPHPExcel->getActiveSheet()->getStyle('A'.$j.':Y'.$j)->getFill()->getStartColor()->setARGB('FAFAD2');
    $objPHPExcel->getActiveSheet()->setCellValue('A'.$j, $cpt);
    $objPHPExcel->getActiveSheet()->setCellValue('B'.$j, $row_v['agence']);
    $objPHPExcel->getActiveSheet()->setCellValue('C'.$j, $typav);
    $objPHPExcel->getActiveSheet()->setCellValue('D'.$j, $row_v['agence'].'.'.substr($row_v['dat_pol'],0,4).'.10.'.$row_v['code_prod'].'.'.str_pad((int) $row_v['sequence_police'],'5',"0",STR_PAD_LEFT));
    $objPHPExcel->getActiveSheet()->setCellValue('E'.$j, $row_v['agence'].'.'.substr($row_v['dateavanant'],0,4).'.10.'.$row_v['lib_mpay'].'.'.str_pad((int) $row_v['sequence_av'],'5',"0",STR_PAD_LEFT));
    $objPHPExcel->getActiveSheet()->setCellValue('F'.$j, $row_v['nom_sous'].' '.$row_v['pnom_sous']);
    $objPHPExcel->getActiveSheet()->setCellValue('G'.$j, date("d/m/Y", strtotime($row_v['dnais_sous'])));
    $objPHPExcel->getActiveSheet()->setCellValue('H'.$j, $row_v['lib_prod']);
    $objPHPExcel->getActiveSheet()->setCellValue('I'.$j, date("d/m/Y", strtotime($row_v['dateavanant'])));
    $objPHPExcel->getActiveSheet()->setCellValue('J'.$j, date("d/m/Y", strtotime($row_v['dat_eff'])));
    $objPHPExcel->getActiveSheet()->setCellValue('K'.$j, date("d/m/Y", strtotime($row_v['dat_ech'])));
    $objPHPExcel->getActiveSheet()->setCellValue('L'.$j, $row_v['pn']);$tpn+=$row_v['pn'];
    $objPHPExcel->getActiveSheet()->setCellValue('M'.$j, $row_v['mtt_cplp']);$tcpl+=$row_v['mtt_cplp'];
    $pc1=$row_v['pt']-$row_v['mtt_dtp'];
    $objPHPExcel->getActiveSheet()->setCellValue('N'.$j, $pc1);$tcom+=$pc1;
    $objPHPExcel->getActiveSheet()->setCellValue('O'.$j, $row_v['mtt_dtp']);$tdt+=$row_v['mtt_dtp'];
    $objPHPExcel->getActiveSheet()->setCellValue('P'.$j, $row_v['pt']);$ttc+=$row_v['pt'];

    $objPHPExcel->getActiveSheet()->setCellValue('Q'.$j, $row_v['base_pn_commission']);$caissepn+= $row_v['base_pn_commission'];//code avis de recette/depense agence/ER/NUMERO
    $objPHPExcel->getActiveSheet()->setCellValue('R'.$j, $row_v['mtt_cpl']);$caissecpl+=$row_v['mtt_cpl'];
    $objPHPExcel->getActiveSheet()->setCellValue('S'.$j, $row_v['base_pn_commission']+$row_v['mtt_cpl']);$caissecom+=$row_v['base_pn_commission']+$row_v['mtt_cpl'];
    $objPHPExcel->getActiveSheet()->setCellValue('T'.$j, $row_v['mtt_dt']);$caissedt+=$row_v['mtt_dt'];
    $objPHPExcel->getActiveSheet()->setCellValue('U'.$j,  $row_v['mtt_avis']);$caissettc+=$row_v['mtt_avis'];
    $objPHPExcel->getActiveSheet()->setCellValue('V'.$j,  $row_v['type_apporteur']);
    $objPHPExcel->getActiveSheet()->setCellValue('W'.$j,  $row_v['lib_agence']);
// Fin de la boucle Avenants
}
//******************************************************fin de la boucle generation des avenants ************

//Fin de la boucle des Avenants
// Encadrement du tableau
$styleThinBlackBorderOutline = array(
    'borders' => array(
        'outline' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb' => 'FF000000'),
        ),
    ),
);

$objPHPExcel->getActiveSheet()->getStyle('A9:W'.$j)->applyFromArray($styleThinBlackBorderOutline);
// la derniere ligne des totaux

$i=$j+1;

//**************
for($lettre2='L'; $lettre2!='U';$lettre2++){
    $objPHPExcel->getActiveSheet()->getStyle($lettre2.$i)->applyFromArray(
        array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
                'right'    => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        )
    );
    $objPHPExcel->getActiveSheet()->getStyle($lettre2.$i)->applyFromArray(
        array(
            'borders' => array(
                'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        )
    );
    $objPHPExcel->getActiveSheet()->getStyle($lettre2.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE);
}



$objPHPExcel->getActiveSheet()->getStyle('A8')->getFont()->setColor(new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_WHITE ));
$objPHPExcel->getActiveSheet()->getStyle('A8:F8')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A8:F8')->getFill()->getStartColor()->setARGB('A25932');
$objPHPExcel->getActiveSheet()->getStyle('A8')->getFont()->setName('Candara');
$objPHPExcel->getActiveSheet()->getStyle('A8')->getFont()->setSize(15);
$objPHPExcel->getActiveSheet()->getStyle('A8')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->setCellValue('L'.$i, $tpn);
$objPHPExcel->getActiveSheet()->setCellValue('M'.$i, $tcpl);
$objPHPExcel->getActiveSheet()->setCellValue('N'.$i, $tcom);
$objPHPExcel->getActiveSheet()->setCellValue('O'.$i, $tdt);
$objPHPExcel->getActiveSheet()->setCellValue('P'.$i, $ttc);
$objPHPExcel->getActiveSheet()->setCellValue('Q'.$i, $caissepn);
$objPHPExcel->getActiveSheet()->setCellValue('R'.$i, $caissecpl);
$objPHPExcel->getActiveSheet()->setCellValue('S'.$i, $caissecom);
$objPHPExcel->getActiveSheet()->setCellValue('T'.$i, $caissedt);
$objPHPExcel->getActiveSheet()->setCellValue('U'.$i, $caissettc);
$objPHPExcel->getActiveSheet()->getStyle('L'.$i.':W'.$i)->applyFromArray($styleThinBlackBorderOutline);


// Nom de la Feuille
$objPHPExcel->getActiveSheet()->setTitle('SIGMA-PRIMES-IMPAYEES');
// Protect par mot de passe du tableau ici
//$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
//$objPHPExcel->getActiveSheet()->protectCells('A1:K'.$sig3, 'aglic');

?>