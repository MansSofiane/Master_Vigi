<?php
error_reporting(E_ALL);
require_once dirname(__FILE__) . '/Classes/PHPExcel.php';
//Recap Globale
$rqtp = $bdd->prepare("SELECT  `lib_prod` FROM `produit`  WHERE cod_prod='$prod'");
$rqtp->execute();
while ($row_p=$rqtp->fetch()){
$agence="DG-AGLIC";
$produit=$row_p['lib_prod'];
}



if($user =='0'){ // requete sur toute la base de données

$rqtg = $bdd->prepare("SELECT d.`dat_val`,d.`sequence`,d.`dat_eff`,d.`dat_ech`,d.`cap1`,d.`cap2`,d.`cap3`,d.`pn`,d.`p1`,d.`p2`,d.`p3`,d.`pn`,d.`pt`,t.`mtt_dt`,c.`mtt_cpl`,p.`code_prod`,p.`lib_prod` ,s.`nom_sous`, s.`pnom_sous`, s.`dnais_sous`,m.`lib_mpay`,u.`agence`,y.`lib_pays` FROM `policew` as d, `dtimbre` as t , `cpolice` as c,`produit` as p,`souscripteurw` as s, `mpay` as m, `utilisateurs` as u, `pays` as y  WHERE d.`cod_dt`=t.`cod_dt` AND d.`cod_cpl`=c.`cod_cpl` AND d.`cod_prod`=p.`cod_prod` AND d.`cod_sous`=s.`cod_sous` AND d.`cod_pays`=y.`cod_pays` AND d.`mode`=m.`cod_mpay` AND s.`id_user`=u.`id_user` AND d.`cod_prod`='$prod' AND DATE_FORMAT(d.`dat_val`,'%Y-%m-%d') >='$dated' AND DATE_FORMAT(d.`dat_val`,'%Y-%m-%d') <='$datef'");
$rqtg->execute();
$rqtv = $bdd->prepare("SELECT d.`dat_val`,d.`pn`,d.`pn`,d.`pt`,d.`lib_mpay`,d.`sequence`,d.`dat_eff`,d.`dat_ech`,d.`cap1`,d.`cap2`,d.`cap3`,d.`pn`,d.`p1`,d.`p2`,d.`p3`,t.`mtt_dt`,c.`mtt_cpl`,p.`code_prod`,p.`lib_prod`, s.`cod_sous`,s.`nom_sous`, s.`pnom_sous`, s.`dnais_sous`,z.sequence as seq2, z.dat_val as datev,u.`agence`,y.`lib_pays`  FROM `avenantw` as d,`policew` as z, `dtimbre` as t , `cpolice` as c,`produit` as p,`souscripteurw` as s,`utilisateurs` as u, `pays` as y  WHERE d.`cod_dt`=t.`cod_dt` AND d.`cod_cpl`=c.`cod_cpl` AND d.`cod_prod`=p.`cod_prod` AND d.`cod_pol`=z.`cod_pol` AND z.`cod_sous`=s.`cod_sous` AND d.`cod_pays`=y.`cod_pays` AND s.`id_user`=u.`id_user` AND d.`cod_prod`='$prod' AND DATE_FORMAT(d.`dat_val`,'%Y-%m-%d') >='$dated' AND DATE_FORMAT(d.`dat_val`,'%Y-%m-%d') <='$datef'");
$rqtv->execute();
}else{

if($type =='user'){ //requete agence

$rqtg = $bdd->prepare("SELECT d.`dat_val`,d.`sequence`,d.`dat_eff`,d.`dat_ech`,d.`cap1`,d.`cap2`,d.`cap3`,d.`pn`,d.`p1`,d.`p2`,d.`p3`,d.`pn`,d.`pt`,t.`mtt_dt`,c.`mtt_cpl`,p.`code_prod`,p.`lib_prod` ,s.`nom_sous`, s.`pnom_sous`, s.`dnais_sous`,m.`lib_mpay`,u.`agence`,y.`lib_pays` FROM `policew` as d, `dtimbre` as t , `cpolice` as c,`produit` as p,`souscripteurw` as s, `mpay` as m, `utilisateurs` as u, `pays` as y  WHERE d.`cod_dt`=t.`cod_dt` AND d.`cod_cpl`=c.`cod_cpl` AND d.`cod_prod`=p.`cod_prod` AND d.`cod_sous`=s.`cod_sous` AND d.`cod_pays`=y.`cod_pays` AND d.`mode`=m.`cod_mpay` AND s.`id_user`=u.`id_user` AND u.`agence`='$user' AND d.`cod_prod`='$prod' AND DATE_FORMAT(d.`dat_val`,'%Y-%m-%d') >='$dated' AND DATE_FORMAT(d.`dat_val`,'%Y-%m-%d') <='$datef'");
$rqtg->execute();
$rqtv = $bdd->prepare("SELECT d.`dat_val`,d.`pn`,d.`pn`,d.`pt`,d.`lib_mpay`,d.`sequence`,d.`dat_eff`,d.`dat_ech`,d.`cap1`,d.`cap2`,d.`cap3`,d.`pn`,d.`p1`,d.`p2`,d.`p3`,t.`mtt_dt`,c.`mtt_cpl`,p.`code_prod`,p.`lib_prod`, s.`cod_sous`,s.`nom_sous`, s.`pnom_sous`, s.`dnais_sous`,z.sequence as seq2, z.dat_val as datev,u.`agence`,y.`lib_pays`  FROM `avenantw` as d,`policew` as z, `dtimbre` as t , `cpolice` as c,`produit` as p,`souscripteurw` as s,`utilisateurs` as u, `pays` as y  WHERE d.`cod_dt`=t.`cod_dt` AND d.`cod_cpl`=c.`cod_cpl` AND d.`cod_prod`=p.`cod_prod` AND d.`cod_pol`=z.`cod_pol` AND z.`cod_sous`=s.`cod_sous` AND d.`cod_pays`=y.`cod_pays` AND s.`id_user`=u.`id_user` AND u.`agence`='$user' AND d.`cod_prod`='$prod' AND DATE_FORMAT(d.`dat_val`,'%Y-%m-%d') >='$dated' AND DATE_FORMAT(d.`dat_val`,'%Y-%m-%d') <='$datef'");
$rqtv->execute();

}else{
//Requete DR $id_ag
$rqtg = $bdd->prepare("SELECT d.`dat_val`,d.`sequence`,d.`dat_eff`,d.`dat_ech`,d.`cap1`,d.`cap2`,d.`cap3`,d.`pn`,d.`p1`,d.`p2`,d.`p3`,d.`pn`,d.`pt`,t.`mtt_dt`,c.`mtt_cpl`,p.`code_prod`,p.`lib_prod` ,s.`nom_sous`, s.`pnom_sous`, s.`dnais_sous`,m.`lib_mpay`,u.`agence`,y.`lib_pays` FROM `policew` as d, `dtimbre` as t , `cpolice` as c,`produit` as p,`souscripteurw` as s, `mpay` as m, `utilisateurs` as u, `pays` as y  WHERE d.`cod_dt`=t.`cod_dt` AND d.`cod_cpl`=c.`cod_cpl` AND d.`cod_prod`=p.`cod_prod` AND d.`cod_sous`=s.`cod_sous` AND d.`cod_pays`=y.`cod_pays` AND d.`mode`=m.`cod_mpay` AND s.`id_user`=u.`id_user` AND u.`id_par`='$id_ag' AND d.`cod_prod`='$prod' AND DATE_FORMAT(d.`dat_val`,'%Y-%m-%d') >='$dated' AND DATE_FORMAT(d.`dat_val`,'%Y-%m-%d') <='$datef'");
$rqtg->execute();
$rqtv = $bdd->prepare("SELECT d.`dat_val`,d.`pn`,d.`pn`,d.`pt`,d.`lib_mpay`,d.`sequence`,d.`dat_eff`,d.`dat_ech`,d.`cap1`,d.`cap2`,d.`cap3`,d.`pn`,d.`p1`,d.`p2`,d.`p3`,t.`mtt_dt`,c.`mtt_cpl`,p.`code_prod`,p.`lib_prod`, s.`cod_sous`,s.`nom_sous`, s.`pnom_sous`, s.`dnais_sous`,z.sequence as seq2, z.dat_val as datev,u.`agence`,y.`lib_pays`  FROM `avenantw` as d,`policew` as z, `dtimbre` as t , `cpolice` as c,`produit` as p,`souscripteurw` as s,`utilisateurs` as u, `pays` as y  WHERE d.`cod_dt`=t.`cod_dt` AND d.`cod_cpl`=c.`cod_cpl` AND d.`cod_prod`=p.`cod_prod` AND d.`cod_pol`=z.`cod_pol` AND z.`cod_sous`=s.`cod_sous` AND d.`cod_pays`=y.`cod_pays` AND s.`id_user`=u.`id_user` AND u.`id_par`='$id_ag' AND d.`cod_prod`='$prod' AND DATE_FORMAT(d.`dat_val`,'%Y-%m-%d') >='$dated' AND DATE_FORMAT(d.`dat_val`,'%Y-%m-%d') <='$datef'");
$rqtv->execute();
}

}
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
$objPHPExcel->getActiveSheet()->setCellValue('F2', 'Recapitulatif de Production Intranet');
$objPHPExcel->getActiveSheet()->mergeCells('F3:H3');
$clt="Reseau AGLIC-DIRECT";
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
$objPHPExcel->getActiveSheet()->setCellValue('A6', 'AGLIC-Direction Technique');
$objPHPExcel->getActiveSheet()->setCellValue('A7', 'Agence : DG-AGLIC');
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
for($lettr='A'; $lettr!='X';$lettr++){
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
//Premieres Colones du tableau 
$objPHPExcel->getActiveSheet()->setCellValue('A9', 'N');
$objPHPExcel->getActiveSheet()->setCellValue('B9', 'Code-Agence');
$objPHPExcel->getActiveSheet()->setCellValue('C9', 'Type-Acte');
$objPHPExcel->getActiveSheet()->setCellValue('D9', 'Police-Numero');
$objPHPExcel->getActiveSheet()->setCellValue('E9', 'Avenant-Numero');
$objPHPExcel->getActiveSheet()->setCellValue('F9', 'Souscripteur');
$objPHPExcel->getActiveSheet()->setCellValue('G9', 'D-Naissance');
$objPHPExcel->getActiveSheet()->setCellValue('H9', 'Pays');
$objPHPExcel->getActiveSheet()->setCellValue('I9', 'D.Souscription');
$objPHPExcel->getActiveSheet()->setCellValue('J9', 'D.Effet');
$objPHPExcel->getActiveSheet()->setCellValue('K9', 'D.Echeance');
$objPHPExcel->getActiveSheet()->setCellValue('L9', 'C-Deces');
$objPHPExcel->getActiveSheet()->setCellValue('M9', 'C-IPP');
$objPHPExcel->getActiveSheet()->setCellValue('N9', 'C-FMP');
$objPHPExcel->getActiveSheet()->setCellValue('O9', 'Reglement');
$objPHPExcel->getActiveSheet()->setCellValue('P9', 'P-Deces');
$objPHPExcel->getActiveSheet()->setCellValue('Q9', 'P-IPP');
$objPHPExcel->getActiveSheet()->setCellValue('R9', 'P-FMP');
$objPHPExcel->getActiveSheet()->setCellValue('S9', 'P-Nette');
$objPHPExcel->getActiveSheet()->setCellValue('T9', 'C.Police');
$objPHPExcel->getActiveSheet()->setCellValue('U9', 'P.Commerciale');
$objPHPExcel->getActiveSheet()->setCellValue('V9', 'D.Timbre');
$objPHPExcel->getActiveSheet()->setCellValue('W9', 'P.Total');
$styleThinBlackBorderOutline = array(
	'borders' => array(
		'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => 'FF000000'),
		),
	),
);
$objPHPExcel->getActiveSheet()->getStyle('A9:W9')->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('L')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE);
$objPHPExcel->getActiveSheet()->getStyle('M')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE);
$objPHPExcel->getActiveSheet()->getStyle('N')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE);
$objPHPExcel->getActiveSheet()->getStyle('O')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE);
$objPHPExcel->getActiveSheet()->getStyle('P')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE);
$objPHPExcel->getActiveSheet()->getStyle('Q')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE);
$objPHPExcel->getActiveSheet()->getStyle('R')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE);
$objPHPExcel->getActiveSheet()->getStyle('S')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE);
$objPHPExcel->getActiveSheet()->getStyle('T')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE);
$objPHPExcel->getActiveSheet()->getStyle('U')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE);
$objPHPExcel->getActiveSheet()->getStyle('V')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE);
$objPHPExcel->getActiveSheet()->getStyle('W')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE);
//Recuperation des données de l'opération

//$connection->requete($query_ann);
$cpt='0';
$j=9;
$tp1=0;$tp2=0;$tp3=0;$tpn=0;$tcp=0;$tpc=0;$tdt=0;$tpt=0;




//*************************************Debut de la boucle generation des polices*****************

//while ($row_ann2 =$connection->enr_actuel()){
while ($row_g=$rqtg->fetch()){

$cpt++;
$j=9+$cpt;
//boucle pour le style
for($lettre='A'; $lettre!='X';$lettre++){
$objPHPExcel->getActiveSheet()->getStyle($lettre.$j)->applyFromArray(
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
$objPHPExcel->getActiveSheet()->getStyle($lettre.$j)->applyFromArray(
		array(
			'borders' => array(
				'right'     => array(
 					'style' => PHPExcel_Style_Border::BORDER_THIN
 				)
			)
		)
);
}
$objPHPExcel->getActiveSheet()->setCellValue('A'.$j, $cpt);
$objPHPExcel->getActiveSheet()->setCellValue('B'.$j, $row_g['agence']);
$objPHPExcel->getActiveSheet()->setCellValue('C'.$j, "Nouvelle-Police");
$objPHPExcel->getActiveSheet()->setCellValue('D'.$j, $row_g['agence'].'.'.substr($row_g['dat_val'],0,4).'.10.'.$row_g['code_prod'].'.'.str_pad((int) $row_g['sequence'],'5',"0",STR_PAD_LEFT));
$objPHPExcel->getActiveSheet()->setCellValue('E'.$j, '--');

$objPHPExcel->getActiveSheet()->setCellValue('F'.$j, $row_g['nom_sous'].' '.$row_g['pnom_sous']);
$objPHPExcel->getActiveSheet()->setCellValue('G'.$j, date("d/m/Y", strtotime($row_g['dnais_sous'])));
$objPHPExcel->getActiveSheet()->setCellValue('H'.$j, $row_g['lib_pays']);
$objPHPExcel->getActiveSheet()->setCellValue('I'.$j, date("d/m/Y", strtotime($row_g['dat_val'])));
$objPHPExcel->getActiveSheet()->setCellValue('J'.$j, date("d/m/Y", strtotime($row_g['dat_eff'])));
$objPHPExcel->getActiveSheet()->setCellValue('K'.$j, date("d/m/Y", strtotime($row_g['dat_ech'])));
$objPHPExcel->getActiveSheet()->setCellValue('L'.$j, $row_g['cap1']);
$objPHPExcel->getActiveSheet()->setCellValue('M'.$j, $row_g['cap2']);
$objPHPExcel->getActiveSheet()->setCellValue('N'.$j, $row_g['cap3']);
$objPHPExcel->getActiveSheet()->setCellValue('O'.$j, $row_g['lib_mpay']);
$objPHPExcel->getActiveSheet()->setCellValue('P'.$j, $row_g['p1']);$tp1=$tp1+$row_g['p1'];
$objPHPExcel->getActiveSheet()->setCellValue('Q'.$j, $row_g['p2']);$tp2=$tp2+$row_g['p2'];
$objPHPExcel->getActiveSheet()->setCellValue('R'.$j, $row_g['p3']);$tp3=$tp3+$row_g['p3'];
$objPHPExcel->getActiveSheet()->setCellValue('S'.$j, $row_g['pn']);$tpn=$tpn+$row_g['pn'];
$objPHPExcel->getActiveSheet()->setCellValue('T'.$j, $row_g['mtt_cpl']);$tcp=$tcp+$row_g['mtt_cpl'];
$pc1=$row_g['pt']-$row_g['mtt_dt'];
$objPHPExcel->getActiveSheet()->setCellValue('U'.$j, $pc1);$tpc=$tpc+$pc1;
$objPHPExcel->getActiveSheet()->setCellValue('V'.$j, $row_g['mtt_dt']);$tdt=$tdt+$row_g['mtt_dt'];
$objPHPExcel->getActiveSheet()->setCellValue('W'.$j, $row_g['pt']);$tpt=$tpt+$row_g['pt'];

}
// *******************************************Fin de la boucle Contrat*******************

// debut de la boucle Avenant


// **************************************Deuxieme Boucle des avenants***************************

//$connection->requete($query_av);

//while ($row_av=$connection->enr_actuel()){
while ($row_v=$rqtv->fetch()){
$cpt++;
$j=9+$cpt;
// boucle d'allignement centée
for($lettr='A'; $lettr!='X';$lettr++){
$objPHPExcel->getActiveSheet()->getStyle($lettr.$j)->applyFromArray(
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
}// Fin de la boucle d'alignement centree
//Libelle du type de l'avenant
$typav="";
if($row_v['lib_mpay']==74){$typav="Avenant-Modification-Date";}
if($row_v['lib_mpay']==50){$typav="Avenant-Annulation-Sans-Ristourne";}
if($row_v['lib_mpay']==30){$typav="Avenant-Annulation-Avec-Ristourne";}
if($row_v['lib_mpay']==70){$typav="Avenant-Precision";}
if($row_v['lib_mpay']==14){$typav="Avenant-Changement-Destination";}
if($row_v['lib_mpay']==73){$typav="Avenant-Subrogation";}

$objPHPExcel->getActiveSheet()->getStyle('A'.$j.':V'.$j)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A'.$j.':V'.$j)->getFill()->getStartColor()->setARGB('FAFAD2');
$objPHPExcel->getActiveSheet()->setCellValue('A'.$j, $cpt);
$objPHPExcel->getActiveSheet()->setCellValue('B'.$j, $row_v['agence']);
$objPHPExcel->getActiveSheet()->setCellValue('C'.$j, $typav);
$objPHPExcel->getActiveSheet()->setCellValue('D'.$j, $row_v['agence'].'.'.substr($row_v['datev'],0,4).'.10.'.$row_v['code_prod'].'.'.str_pad((int) $row_v['seq2'],'5',"0",STR_PAD_LEFT));
$objPHPExcel->getActiveSheet()->setCellValue('E'.$j, $row_v['agence'].'.'.substr($row_v['dat_val'],0,4).'.10.'.$row_v['lib_mpay'].'.'.str_pad((int) $row_v['sequence'],'5',"0",STR_PAD_LEFT));
$objPHPExcel->getActiveSheet()->setCellValue('F'.$j, $row_v['nom_sous'].' '.$row_v['pnom_sous']);
$objPHPExcel->getActiveSheet()->setCellValue('G'.$j, date("d/m/Y", strtotime($row_v['dnais_sous'])));
$objPHPExcel->getActiveSheet()->setCellValue('H'.$j, $row_v['lib_pays']);
$objPHPExcel->getActiveSheet()->setCellValue('I'.$j, date("d/m/Y", strtotime($row_v['dat_val'])));
$objPHPExcel->getActiveSheet()->setCellValue('J'.$j, date("d/m/Y", strtotime($row_v['dat_eff'])));
$objPHPExcel->getActiveSheet()->setCellValue('K'.$j, date("d/m/Y", strtotime($row_v['dat_ech'])));
$objPHPExcel->getActiveSheet()->setCellValue('L'.$j, $row_v['cap1']);
$objPHPExcel->getActiveSheet()->setCellValue('M'.$j, $row_v['cap2']);
$objPHPExcel->getActiveSheet()->setCellValue('N'.$j, $row_v['cap3']);
$objPHPExcel->getActiveSheet()->setCellValue('O'.$j, '--');
$objPHPExcel->getActiveSheet()->setCellValue('P'.$j, '0');$tp1=$tp1+0;
$objPHPExcel->getActiveSheet()->setCellValue('Q'.$j, '0');$tp2=$tp2+0;
$objPHPExcel->getActiveSheet()->setCellValue('R'.$j, '0');$tp3=$tp3+0;
$objPHPExcel->getActiveSheet()->setCellValue('S'.$j, $row_v['pn']);$tpn=$tpn+$row_v['pn'];
$objPHPExcel->getActiveSheet()->setCellValue('T'.$j, $row_v['mtt_cpl']);$tcp=$tcp+$row_v['mtt_cpl'];
$pc1=$row_v['pt']-$row_v['mtt_dt'];
$objPHPExcel->getActiveSheet()->setCellValue('U'.$j, $pc1);$tpc=$tpc+$pc1;
$objPHPExcel->getActiveSheet()->setCellValue('V'.$j, $row_v['mtt_dt']);$tdt=$tdt+$row_v['mtt_dt'];
$objPHPExcel->getActiveSheet()->setCellValue('W'.$j, $row_v['pt']);$tpt=$tpt+$row_v['pt'];
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
for($lettre2='P'; $lettre2!='X';$lettre2++){
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
$objPHPExcel->getActiveSheet()->setCellValue('P'.$i, $tp1);
$objPHPExcel->getActiveSheet()->setCellValue('Q'.$i, $tp2);
$objPHPExcel->getActiveSheet()->setCellValue('R'.$i, $tp3);
$objPHPExcel->getActiveSheet()->setCellValue('S'.$i, $tpn);
$objPHPExcel->getActiveSheet()->setCellValue('T'.$i, $tcp);
$objPHPExcel->getActiveSheet()->setCellValue('U'.$i, $tpc);
$objPHPExcel->getActiveSheet()->setCellValue('V'.$i, $tdt);
$objPHPExcel->getActiveSheet()->setCellValue('W'.$i, $tpt);
$objPHPExcel->getActiveSheet()->getStyle('P'.$i.':W'.$i)->applyFromArray($styleThinBlackBorderOutline);
// Nom de la Feuille 
$objPHPExcel->getActiveSheet()->setTitle('Production-Intranet');
// Protect par mot de passe du tableau ici
//$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);	
//$objPHPExcel->getActiveSheet()->protectCells('A1:K'.$sig3, 'aglic');

?>