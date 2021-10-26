<?php
error_reporting(E_ALL);
require_once dirname(__FILE__) . '/Classes/PHPExcel.php';


////////////////////////////////////--------------------------------------------------//////////////////////////////////////////////////////
$condition_ag='';
if($prod !=100) {
    $condition_ag = "and p.cod_prod='$prod' ";

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
    $condition_ag='';
}

$lib_type = "ETAT  DES MOUVEMENTS DE CAISSE";

$rqtg = $bdd->prepare("select case when  a.sens_avis=0 then 'AVIS-RECETTE' else 'AVIS-DEPENSE' end as etatcaisse,p.cod_pol police,'0' as avenant,p.cod_prod,p.sequence sequence_police,'' sequence_av,p.dat_val dat_pol,p.pn,p.pt,c.mtt_cpl mtt_cplp,d.mtt_dt mtt_dtp,'' dateavanant,a.cod_avis,a.id_avis,a.dat_avis,p.dat_eff,p.dat_ech,s.nom_sous,s.pnom_sous,s.dnais_sous,a.mtt_avis,a.cod_ref,a.cod_av,a.base_pn_commission,a.mtt_cpl,a.mtt_dt,case when a.cod_mpay ='1' then 'espece' when a.cod_mpay='2' then 'Cheque' when a.cod_mpay='3' then 'Virement' end mode_encaiss,a.lib_mpay libmpay_caisse ,a.dat_mpay  dat_mpay_caisse
 , case when g.typ_agence='0' then 'Affaire Directe' when g.typ_agence='1' then 'Convention Voyage' when g.typ_agence='2' then 'Courtier' end type_apporteur,g.lib_agence
 ,pr.code_prod,pr.lib_prod
 from avis_recette  a, policew as p ,souscripteurw as s,utilisateurs u ,cpolice c,dtimbre d,agence g,produit pr

where a.sens_avis=0 and a.type_avis=0
and p.cod_cpl=c.cod_cpl and p.cod_dt=d.cod_dt
and p.cod_prod=pr.cod_prod
$condition_ag
and p.cod_agence=g.cod_agence
      and DATE_FORMAT(a.dat_avis,'%Y-%m-%d')  between '$dated' and '$datef'
        and a.cod_ref=p.cod_pol
      and p.cod_sous=s.cod_sous
      and s.id_user=u.id_user
      and u.agence='$user'
");
$rqtg->execute();
$rqtv = $bdd->prepare("select  case when  a.sens_avis=0 then 'AVIS-RECETTE' else 'AVIS-DEPENSE' end as etatcaisse,v.cod_pol police,v.cod_av as avenant,v.cod_prod,p.sequence sequence_police,v.sequence sequence_av,p.dat_val dat_pol ,v.dat_val dateavanant,v.pn,v.pt,c.mtt_cpl mtt_cplv,d.mtt_dt mtt_dtv,a.cod_avis,a.id_avis,a.dat_avis,v.dat_eff,v.dat_ech,s.nom_sous,s.pnom_sous,s.dnais_sous,a.mtt_avis,a.cod_ref,a.cod_av,a.base_pn_commission,a.mtt_cpl,a.mtt_dt,v.lib_mpay ,case when a.cod_mpay ='1' then 'espece' when a.cod_mpay='2' then 'Cheque' when a.cod_mpay='3' then 'Virement' end mode_encaiss,a.lib_mpay libmpay_caisse,a.dat_mpay dat_mpay_caisse
, case when g.typ_agence='0' then 'Affaire Directe' when g.typ_agence='1' then 'Convention Voyage' when g.typ_agence='2' then 'Courtier' end type_apporteur,g.lib_agence
,pr.code_prod,pr.lib_prod
from avis_recette  a, policew as p ,souscripteurw as s,avenantw as v,utilisateurs u ,cpolice c,dtimbre d,agence g,produit pr

where  a.type_avis=1
and v.cod_cpl=c.cod_cpl and v.cod_dt=d.cod_dt
and p.cod_prod=pr.cod_prod and v.cod_prod=pr.cod_prod
$condition_ag
     and p.cod_agence=g.cod_agence
      and DATE_FORMAT(a.dat_avis,'%Y-%m-%d') between '$dated' and '$datef'
      and  a.cod_ref=v.cod_pol and a.cod_av=v.cod_av
      and p.cod_sous=s.cod_sous
       and s.id_user=u.id_user
      and u.agence='$user'
      and p.cod_pol=v.cod_pol
      order by a.sens_avis ");
$rqtv->execute();
/*a.sens_avis=0 and
$rqtvneg = $bdd->prepare("select  case when  a.sens_avis=0 then 'AVIS-RECETTE' else 'AVIS-DEPENSE' end as etatcaisse,v.cod_pol police,v.cod_av as avenant,v.cod_prod,p.sequence sequence_police,v.sequence sequence_av,p.dat_val dat_pol ,v.dat_val dateavanant,v.pn,v.pt,c.mtt_cpl mttcplc,v.mtt_dt mtt_dtv,a.id_avis,a.dat_avis,v.dat_eff,v.dat_ech,s.nom_sous,s.pnom_sous,s.dnais_sous,a.mtt_avis,a.cod_ref,a.cod_av,a.base_pn_commission,a.mtt_cpl,a.mtt_dt,v.lib_mpay,case when a.cod_mpay ='1' then 'espece' when a.cod_mpay='2' then 'Cheque' when a.cod_mpay='3' then 'Virement' end mode_encaiss,a.lib_mpay libmpay_caisse,a.dat_mpay dat_mpay_caisse from avis_recette  a, policew as p ,souscripteurw as s,avenantw as v,utilisateurs u ,cpolice c,dtimbre d

where a.sens_avis=1 and a.type_avis=1
and v.cod_cpl=c.cod_cpl and v.cod_dt=d.cod_dt
     and p.cod_prod='$prod'
      and DATE_FORMAT(a.dat_avis,'%Y-%m-%d') between '$dated' and '$datef'
      and  a.cod_ref=v.cod_pol and a.cod_av=v.cod_av
      and p.cod_sous=s.cod_sous
       and s.id_user=u.id_user
      and u.agence='$user'
      and p.cod_pol=v.cod_pol
      order by a.sens_avis ");
$rqtvneg->execute();

*/


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
$objPHPExcel->getActiveSheet()->setCellValue('F2', 'Mouvement de caisse');
$objPHPExcel->getActiveSheet()->mergeCells('F3:H3');
$clt="Réseau CASH";
$objPHPExcel->getActiveSheet()->setCellValue('F3', $clt);
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
$objPHPExcel->getActiveSheet()->setCellValue('A7', 'Agence :'.$user);
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
$objPHPExcel->getActiveSheet()->getStyle('A9:AC9')->applyFromArray(
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
for($lettr='A'; $lettr!='AC';$lettr++){
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
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setWidth(45);
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
$objPHPExcel->getActiveSheet()->setCellValue('Q9', 'Code-avis');
$objPHPExcel->getActiveSheet()->setCellValue('R9', 'Type-Avis');
$objPHPExcel->getActiveSheet()->setCellValue('S9', 'Date-souscription-Avis');
$objPHPExcel->getActiveSheet()->setCellValue('T9', 'Mode-Reglement');
$objPHPExcel->getActiveSheet()->setCellValue('U9', 'Date-Reglement');
$objPHPExcel->getActiveSheet()->setCellValue('V9', 'Libelle');
$objPHPExcel->getActiveSheet()->setCellValue('W9', 'P-nette-reglement');
$objPHPExcel->getActiveSheet()->setCellValue('X9', 'Cout-police-regle');
$objPHPExcel->getActiveSheet()->setCellValue('Y9', 'P-COmmerciale-regle');
$objPHPExcel->getActiveSheet()->setCellValue('Z9', 'D-timbre-regle');
$objPHPExcel->getActiveSheet()->setCellValue('AA9', 'Total-reglement');
$objPHPExcel->getActiveSheet()->setCellValue('AB9', 'Type-affaire');
$objPHPExcel->getActiveSheet()->setCellValue('AC9', 'Apporteur-Affaire');



$styleThinBlackBorderOutline = array(
    'borders' => array(
        'outline' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb' => 'FF000000'),
        ),
    ),
);
$objPHPExcel->getActiveSheet()->getStyle('A9:AC9')->applyFromArray($styleThinBlackBorderOutline);
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
    $objPHPExcel->getActiveSheet()->setCellValue('B'.$j, $user);
    $objPHPExcel->getActiveSheet()->setCellValue('C'.$j, 'Nouvelle police');
    $objPHPExcel->getActiveSheet()->setCellValue('D'.$j, $user.'.'.substr($row_g['dat_pol'],0,4).'.10.'.$row_g['code_prod'].'.'.str_pad((int) $row_g['sequence_police'],'5',"0",STR_PAD_LEFT));
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
    $objPHPExcel->getActiveSheet()->setCellValue('Q'.$j, $user.'/'.substr($row_g['dat_avis'],0,4).'/'.$row_g['cod_avis']);//code avis de recette/depense agence/ER/NUMERO
    $objPHPExcel->getActiveSheet()->setCellValue('R'.$j, $row_g['etatcaisse']);
    $objPHPExcel->getActiveSheet()->setCellValue('S'.$j, date("d/m/Y", strtotime($row_g['dat_avis'])));

     $objPHPExcel->getActiveSheet()->setCellValue('T'.$j, $row_g['mode_encaiss']);
    $objPHPExcel->getActiveSheet()->setCellValue('U'.$j, date("d/m/Y", strtotime($row_g['dat_mpay_caisse'])));
    $objPHPExcel->getActiveSheet()->setCellValue('V'.$j, $row_g['libmpay_caisse']);


    $objPHPExcel->getActiveSheet()->setCellValue('W'.$j, $row_g['base_pn_commission']);$caissepn+=$row_g['base_pn_commission'];
    $objPHPExcel->getActiveSheet()->setCellValue('X'.$j, $row_g['mtt_cpl']);$caissecpl+=$row_g['mtt_cpl'];
    $cais_commercial=$row_g['base_pn_commission']+ $row_g['mtt_cpl'];
    $objPHPExcel->getActiveSheet()->setCellValue('Y'.$j, $cais_commercial);$caissecom+=$cais_commercial;
    $objPHPExcel->getActiveSheet()->setCellValue('Z'.$j, $row_g['mtt_dt']);+$caissedt+=$row_g['mtt_dt'];
    //$cais_commercial+= $row_g['mtt_dt'];
    $objPHPExcel->getActiveSheet()->setCellValue('AA'.$j,  $row_g['mtt_avis']);$caissettc+=$row_g['mtt_avis'];
    $objPHPExcel->getActiveSheet()->setCellValue('AB'.$j,  $row_g['type_apporteur']);
    $objPHPExcel->getActiveSheet()->setCellValue('AC'.$j,  $row_g['lib_agence']);

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
    $objPHPExcel->getActiveSheet()->setCellValue('B'.$j, $user);
    $objPHPExcel->getActiveSheet()->setCellValue('C'.$j, $typav);
    $objPHPExcel->getActiveSheet()->setCellValue('D'.$j, $user.'.'.substr($row_v['dat_pol'],0,4).'.10.'.$row_v['code_prod'].'.'.str_pad((int) $row_v['sequence_police'],'5',"0",STR_PAD_LEFT));
    $objPHPExcel->getActiveSheet()->setCellValue('E'.$j, $user.'.'.substr($row_v['dateavanant'],0,4).'.10.'.$row_v['lib_mpay'].'.'.str_pad((int) $row_v['sequence_av'],'5',"0",STR_PAD_LEFT));
    $objPHPExcel->getActiveSheet()->setCellValue('F'.$j, $row_v['nom_sous'].' '.$row_v['pnom_sous']);
    $objPHPExcel->getActiveSheet()->setCellValue('G'.$j, date("d/m/Y", strtotime($row_v['dnais_sous'])));
    $objPHPExcel->getActiveSheet()->setCellValue('H'.$j, $row_v['lib_prod']);
    $objPHPExcel->getActiveSheet()->setCellValue('I'.$j, date("d/m/Y", strtotime($row_v['dateavanant'])));
    $objPHPExcel->getActiveSheet()->setCellValue('J'.$j, date("d/m/Y", strtotime($row_v['dat_eff'])));
    $objPHPExcel->getActiveSheet()->setCellValue('K'.$j, date("d/m/Y", strtotime($row_v['dat_ech'])));
    $objPHPExcel->getActiveSheet()->setCellValue('L'.$j, $row_v['pn']);$tpn+=$row_v['pn'];
    $objPHPExcel->getActiveSheet()->setCellValue('M'.$j, $row_v['mtt_cplv']);$tcpl+=$row_v['mtt_cplv'];
    $pc1=$row_v['pt']-$row_v['mtt_dtv'];
    $objPHPExcel->getActiveSheet()->setCellValue('N'.$j, $pc1);$tcom+=$pc1;
    $objPHPExcel->getActiveSheet()->setCellValue('O'.$j, $row_v['mtt_dtv']);$tdt+=$row_v['mtt_dtv'];
    $objPHPExcel->getActiveSheet()->setCellValue('P'.$j, $row_v['pt']);$ttc+=$row_v['pt'];
    $objPHPExcel->getActiveSheet()->setCellValue('Q'.$j, $user.'/'.substr($row_v['dat_avis'],0,4).'/'.$row_v['cod_avis']);//code avis de recette/depense agence/ER/NUMERO
    $objPHPExcel->getActiveSheet()->setCellValue('R'.$j, $row_v['etatcaisse']);
    $objPHPExcel->getActiveSheet()->setCellValue('S'.$j, date("d/m/Y", strtotime($row_v['dat_avis'])));

    $objPHPExcel->getActiveSheet()->setCellValue('T'.$j, $row_v['mode_encaiss']);
    $objPHPExcel->getActiveSheet()->setCellValue('U'.$j, date("d/m/Y", strtotime($row_v['dat_mpay_caisse'])));
    $objPHPExcel->getActiveSheet()->setCellValue('V'.$j, $row_v['libmpay_caisse']);


    $objPHPExcel->getActiveSheet()->setCellValue('W'.$j, $row_v['base_pn_commission']);$caissepn+=$row_v['base_pn_commission'];
    $objPHPExcel->getActiveSheet()->setCellValue('X'.$j, $row_v['mtt_cpl']);$caissecpl+=$row_v['mtt_cpl'];
    $cais_commercial=$row_v['base_pn_commission']+ $row_v['mtt_cpl'];
    $objPHPExcel->getActiveSheet()->setCellValue('Y'.$j, $cais_commercial);$caissecom+=$cais_commercial;
    $objPHPExcel->getActiveSheet()->setCellValue('Z'.$j, $row_v['mtt_dt']);$caissedt+= $row_v['mtt_dt'];
    $cais_commercial+= $row_v['mtt_dt'];
    $objPHPExcel->getActiveSheet()->setCellValue('AA'.$j,  $row_v['mtt_avis']);$caissettc+=$row_v['mtt_avis'];
    $objPHPExcel->getActiveSheet()->setCellValue('AB'.$j,  $row_v['type_apporteur']);
    $objPHPExcel->getActiveSheet()->setCellValue('AC'.$j,  $row_v['lib_agence']);
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

$objPHPExcel->getActiveSheet()->getStyle('A9:Y'.$j)->applyFromArray($styleThinBlackBorderOutline);
// la derniere ligne des totaux
$i=$j+1;

//**************
for($lettre2='O'; $lettre2!='Z';$lettre2++){
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
$objPHPExcel->getActiveSheet()->setCellValue('W'.$i, $caissepn);
$objPHPExcel->getActiveSheet()->setCellValue('X'.$i, $caissecpl);
$objPHPExcel->getActiveSheet()->setCellValue('Y'.$i, $caissecom);
$objPHPExcel->getActiveSheet()->setCellValue('Z'.$i, $caissedt);
$objPHPExcel->getActiveSheet()->setCellValue('AA'.$i, $caissettc);
$objPHPExcel->getActiveSheet()->getStyle('L'.$i.':AA'.$i)->applyFromArray($styleThinBlackBorderOutline);
// Nom de la Feuille
$objPHPExcel->getActiveSheet()->setTitle('SIGMA-CAISSE');
// Protect par mot de passe du tableau ici
//$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
//$objPHPExcel->getActiveSheet()->protectCells('A1:K'.$sig3, 'aglic');

?>