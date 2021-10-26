<?php
error_reporting(E_ALL);
require_once dirname(__FILE__) . '/Classes/PHPExcel.php';
// Requete extraction des polices
$rqtt = $bdd->prepare("SELECT  DISTINCT id_user,type_user,agence FROM `utilisateurs`  WHERE  id_user='$user' ");
$rqtt->execute();

$agencesel='Tous le réseau';
while ($row_t=$rqtt->fetch()){
$type=$row_t['type_user'];
$id_ag=$row_t['id_user'];
	$agencesel=$row_t['agence'];
}

if($user!='0'){



if($type=='user'){
//Requete par agence

$rqtg = $bdd->prepare("SELECT d.`dat_val`,d.`sequence`,d.`dat_eff`,d.`dat_ech`,d.`cap1`,d.`cap2`,d.`cap3`,d.`pn`,d.`p1`,d.`p2`,d.`p3`,d.`pn`,d.`pt`,t.`mtt_dt`,c.`mtt_cpl`,p.`code_prod`,p.`lib_prod` ,s.`cod_sous`,s.`nom_sous`, s.`pnom_sous`, s.`adr_sous`,m.`lib_mpay`,u.`agence`,y.`lib_pays`,a.lib_agence as apporteur FROM `policew` as d, `dtimbre` as t , `cpolice` as c,`produit` as p,`souscripteurw` as s, `mpay` as m, `utilisateurs` as u, `pays` as y, `agence` as a  WHERE d.`cod_dt`=t.`cod_dt` AND d.`cod_cpl`=c.`cod_cpl` AND d.`cod_prod`=p.`cod_prod` AND d.`cod_sous`=s.`cod_sous` AND d.`cod_pays`=y.`cod_pays` AND d.`mode`=m.`cod_mpay` AND s.`id_user`=u.`id_user` AND u.`agence`='$agencesel' AND DATE_FORMAT(d.`dat_val`,'%Y-%m-%d') >='$dated' AND DATE_FORMAT(d.`dat_val`,'%Y-%m-%d') <='$datef' AND d.cod_agence=a.cod_agence
");
$rqtg->execute();

//Requete extraction des avenants

$rqtv = $bdd->prepare("SELECT d.`dat_val`,d.`pn`,d.`pn`,d.`pt`,d.`lib_mpay`,d.`sequence`,d.`dat_eff`,d.`dat_ech`,d.`cap1`,d.`cap2`,d.`cap3`,d.`pn`,d.`p1`,d.`p2`,d.`p3`,t.`mtt_dt`,c.`mtt_cpl`,p.`code_prod`,p.`lib_prod`, s.`cod_sous`,s.`nom_sous`, s.`pnom_sous`, s.`adr_sous`,z.sequence as seq2, z.dat_val as datev,u.`agence`,y.`lib_pays`,a.`lib_agence` as apporteur  FROM `avenantw` as d,`policew` as z, `dtimbre` as t , `cpolice` as c,`produit` as p,`souscripteurw` as s,`utilisateurs` as u, `pays` as y,`agence` as a  WHERE d.`cod_dt`=t.`cod_dt` AND d.`cod_cpl`=c.`cod_cpl` AND d.`cod_prod`=p.`cod_prod` AND d.`cod_pol`=z.`cod_pol` AND z.`cod_sous`=s.`cod_sous` AND d.`cod_pays`=y.`cod_pays` AND s.`id_user`=u.`id_user` AND u.`agence`='$agencesel' AND DATE_FORMAT(d.`dat_val`,'%Y-%m-%d') >='$dated' AND DATE_FORMAT(d.`dat_val`,'%Y-%m-%d') <='$datef' AND z.cod_agence=a.cod_agence
");

$rqtv->execute();
}else{

//requete par DR

$rqtg = $bdd->prepare("SELECT d.`dat_val`,d.`sequence`,d.`dat_eff`,d.`dat_ech`,d.`cap1`,d.`cap2`,d.`cap3`,d.`pn`,d.`p1`,d.`p2`,d.`p3`,d.`pn`,d.`pt`,t.`mtt_dt`,c.`mtt_cpl`,p.`code_prod`,p.`lib_prod` ,s.`cod_sous`,s.`nom_sous`, s.`pnom_sous`, s.`adr_sous`,m.`lib_mpay`,u.`agence`,y.`lib_pays`,a.lib_agence as apporteur FROM `policew` as d, `dtimbre` as t , `cpolice` as c,`produit` as p,`souscripteurw` as s, `mpay` as m, `utilisateurs` as u, `pays` as y, `agence` as a  WHERE d.`cod_dt`=t.`cod_dt` AND d.`cod_cpl`=c.`cod_cpl` AND d.`cod_prod`=p.`cod_prod` AND d.`cod_sous`=s.`cod_sous` AND d.`cod_pays`=y.`cod_pays` AND d.`mode`=m.`cod_mpay` AND s.`id_user`=u.`id_user` AND u.`id_par`='$user' AND DATE_FORMAT(d.`dat_val`,'%Y-%m-%d') >='$dated' AND DATE_FORMAT(d.`dat_val`,'%Y-%m-%d') <='$datef' AND d.cod_agence=a.cod_agence
");
$rqtg->execute();

//Requete extraction des avenants

$rqtv = $bdd->prepare("SELECT d.`dat_val`,d.`pn`,d.`pn`,d.`pt`,d.`lib_mpay`,d.`sequence`,d.`dat_eff`,d.`dat_ech`,d.`cap1`,d.`cap2`,d.`cap3`,d.`pn`,d.`p1`,d.`p2`,d.`p3`,t.`mtt_dt`,c.`mtt_cpl`,p.`code_prod`,p.`lib_prod`, s.`cod_sous`,s.`nom_sous`, s.`pnom_sous`, s.`adr_sous`,z.sequence as seq2, z.dat_val as datev,u.`agence`,y.`lib_pays`,a.`lib_agence` as apporteur  FROM `avenantw` as d,`policew` as z, `dtimbre` as t , `cpolice` as c,`produit` as p,`souscripteurw` as s,`utilisateurs` as u, `pays` as y,`agence` as a  WHERE d.`cod_dt`=t.`cod_dt` AND d.`cod_cpl`=c.`cod_cpl` AND d.`cod_prod`=p.`cod_prod` AND d.`cod_pol`=z.`cod_pol` AND z.`cod_sous`=s.`cod_sous` AND d.`cod_pays`=y.`cod_pays` AND s.`id_user`=u.`id_user` AND u.`id_par`='$user' AND DATE_FORMAT(d.`dat_val`,'%Y-%m-%d') >='$dated' AND DATE_FORMAT(d.`dat_val`,'%Y-%m-%d') <='$datef' AND z.cod_agence=a.cod_agence
");

$rqtv->execute();
}

}else{
//Requete generale

$rqtg = $bdd->prepare("SELECT d.`dat_val`,d.`sequence`,d.`dat_eff`,d.`dat_ech`,d.`cap1`,d.`cap2`,d.`cap3`,d.`pn`,d.`p1`,d.`p2`,d.`p3`,d.`pn`,d.`pt`,t.`mtt_dt`,c.`mtt_cpl`,p.`code_prod`,p.`lib_prod` ,s.`cod_sous`,s.`nom_sous`, s.`pnom_sous`, s.`adr_sous`,m.`lib_mpay`,u.`agence`,y.`lib_pays`,a.lib_agence as apporteur FROM `policew` as d, `dtimbre` as t , `cpolice` as c,`produit` as p,`souscripteurw` as s, `mpay` as m, `utilisateurs` as u, `pays` as y, `agence` as a  WHERE d.`cod_dt`=t.`cod_dt` AND d.`cod_cpl`=c.`cod_cpl` AND d.`cod_prod`=p.`cod_prod` AND d.`cod_sous`=s.`cod_sous` AND d.`cod_pays`=y.`cod_pays` AND d.`mode`=m.`cod_mpay` AND s.`id_user`=u.`id_user` AND DATE_FORMAT(d.`dat_val`,'%Y-%m-%d') >='$dated' AND DATE_FORMAT(d.`dat_val`,'%Y-%m-%d') <='$datef' AND d.cod_agence=a.cod_agence
");
$rqtg->execute();

//Requete extraction des avenants

$rqtv = $bdd->prepare("SELECT d.`dat_val`,d.`pn`,d.`pn`,d.`pt`,d.`lib_mpay`,d.`sequence`,d.`dat_eff`,d.`dat_ech`,d.`cap1`,d.`cap2`,d.`cap3`,d.`pn`,d.`p1`,d.`p2`,d.`p3`,t.`mtt_dt`,c.`mtt_cpl`,p.`code_prod`,p.`lib_prod`, s.`cod_sous`,s.`nom_sous`, s.`pnom_sous`, s.`adr_sous`,z.sequence as seq2, z.dat_val as datev,u.`agence`,y.`lib_pays`,a.`lib_agence` as apporteur  FROM `avenantw` as d,`policew` as z, `dtimbre` as t , `cpolice` as c,`produit` as p,`souscripteurw` as s,`utilisateurs` as u, `pays` as y,`agence` as a  WHERE d.`cod_dt`=t.`cod_dt` AND d.`cod_cpl`=c.`cod_cpl` AND d.`cod_prod`=p.`cod_prod` AND d.`cod_pol`=z.`cod_pol` AND z.`cod_sous`=s.`cod_sous` AND d.`cod_pays`=y.`cod_pays` AND s.`id_user`=u.`id_user` AND DATE_FORMAT(d.`dat_val`,'%Y-%m-%d') >='$dated' AND DATE_FORMAT(d.`dat_val`,'%Y-%m-%d') <='$datef' AND z.cod_agence=a.cod_agence
");

$rqtv->execute();





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
$clt="Reseau CASH";
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
$objPHPExcel->getActiveSheet()->setCellValue('A7', 'Agence :'.$agencesel);
$objPHPExcel->getActiveSheet()->setCellValue('A8', 'Produit : Tout-Les-Produits');
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
$objPHPExcel->getActiveSheet()->getStyle('A9:X9')->applyFromArray(
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
for($lettr='A'; $lettr!='Y';$lettr++){
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
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(25);
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
$objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(25);
//Premieres Colones du tableau
$objPHPExcel->getActiveSheet()->setCellValue('A9', 'Numero');
$objPHPExcel->getActiveSheet()->setCellValue('B9', 'Libelle produit');
$objPHPExcel->getActiveSheet()->setCellValue('C9', 'Designation Souscription');
$objPHPExcel->getActiveSheet()->setCellValue('D9', 'Agence');
$objPHPExcel->getActiveSheet()->setCellValue('E9', 'Exercice');
$objPHPExcel->getActiveSheet()->setCellValue('F9', 'Type');
$objPHPExcel->getActiveSheet()->setCellValue('G9', 'Produit');
$objPHPExcel->getActiveSheet()->setCellValue('H9', 'Code');
$objPHPExcel->getActiveSheet()->setCellValue('I9', 'Agence_Police');
$objPHPExcel->getActiveSheet()->setCellValue('J9', 'Exercice_Police');
$objPHPExcel->getActiveSheet()->setCellValue('K9', 'Type_Police');
$objPHPExcel->getActiveSheet()->setCellValue('L9', 'Produit_Police');
$objPHPExcel->getActiveSheet()->setCellValue('M9', 'Code__Police');
$objPHPExcel->getActiveSheet()->setCellValue('N9', 'Date Souscription');
$objPHPExcel->getActiveSheet()->setCellValue('O9', 'Date Effet');
$objPHPExcel->getActiveSheet()->setCellValue('P9', 'Date Echeance');
$objPHPExcel->getActiveSheet()->setCellValue('Q9', 'Prime Nette');
$objPHPExcel->getActiveSheet()->setCellValue('R9', 'Cout Police/Avenant');
$objPHPExcel->getActiveSheet()->setCellValue('S9', 'Timbre Dimension');
$objPHPExcel->getActiveSheet()->setCellValue('T9', 'Nom /Rs Souscripteur');
$objPHPExcel->getActiveSheet()->setCellValue('U9', 'Prenom Souscripteur');
$objPHPExcel->getActiveSheet()->setCellValue('V9', 'Adresse Souscripteur');
$objPHPExcel->getActiveSheet()->setCellValue('W9', 'Nb personne');
$objPHPExcel->getActiveSheet()->setCellValue('X9', 'Apporteur');
$styleThinBlackBorderOutline = array(
	'borders' => array(
		'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => 'FF000000'),
		),
	),
);
$objPHPExcel->getActiveSheet()->getStyle('A9:X9')->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('Q')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE);
$objPHPExcel->getActiveSheet()->getStyle('R')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE);
$objPHPExcel->getActiveSheet()->getStyle('S')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE);

//Recuperation des données de l'opération

//$connection->requete($query_ann);
$cpt='0';
$j=9;
$tpn=0;$tcp=0;$tdt=0;




//*************************************Debut de la boucle generation des polices*****************

while ($row_g=$rqtg->fetch()){

	$cpt++;
	$j=9+$cpt;
//boucle pour le style
	for($lettre='A'; $lettre!='Y';$lettre++){
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
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$j, $row_g['lib_prod']);
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$j, "Nouvelle-Police");
	$objPHPExcel->getActiveSheet()->setCellValue('D'.$j, $agencesel);
	$objPHPExcel->getActiveSheet()->setCellValue('E'.$j, substr($row_g['dat_val'],0,4));
	$objPHPExcel->getActiveSheet()->setCellValue('F'.$j, '10');
	$objPHPExcel->getActiveSheet()->setCellValue('G'.$j, $row_g['code_prod']);
	$objPHPExcel->getActiveSheet()->setCellValue('H'.$j, $row_g['sequence']);
	$objPHPExcel->getActiveSheet()->setCellValue('I'.$j, $user);
	$objPHPExcel->getActiveSheet()->setCellValue('J'.$j, substr($row_g['dat_val'],0,4));
	$objPHPExcel->getActiveSheet()->setCellValue('K'.$j, '10');
	$objPHPExcel->getActiveSheet()->setCellValue('L'.$j, $row_g['code_prod']);
	$objPHPExcel->getActiveSheet()->setCellValue('M'.$j, $row_g['sequence']);
	$objPHPExcel->getActiveSheet()->setCellValue('N'.$j, date("d/m/Y", strtotime($row_g['dat_val'])));
	$objPHPExcel->getActiveSheet()->setCellValue('O'.$j, date("d/m/Y", strtotime($row_g['dat_eff'])));
	$objPHPExcel->getActiveSheet()->setCellValue('P'.$j, date("d/m/Y", strtotime($row_g['dat_ech'])));
	$objPHPExcel->getActiveSheet()->setCellValue('Q'.$j, $row_g['pn']);$tpn=$tpn+$row_g['pn'];
	$objPHPExcel->getActiveSheet()->setCellValue('R'.$j, $row_g['mtt_cpl']);$tcp=$tcp+$row_g['mtt_cpl'];
	$objPHPExcel->getActiveSheet()->setCellValue('S'.$j, $row_g['mtt_dt']);$tdt=$tdt+$row_g['mtt_dt'];
	$objPHPExcel->getActiveSheet()->setCellValue('T'.$j, $row_g['nom_sous']);
	$objPHPExcel->getActiveSheet()->setCellValue('U'.$j, $row_g['pnom_sous']);
	$objPHPExcel->getActiveSheet()->setCellValue('V'.$j, $row_g['adr_sous']);
	$objPHPExcel->getActiveSheet()->setCellValue('W'.$j, nbassu($row_g['cod_sous']));
	$objPHPExcel->getActiveSheet()->setCellValue('X'.$j, $row_g['apporteur']);

}

// *******************************************Fin de la boucle Contrat*******************

// debut de la boucle Avenant


// **************************************Deuxieme Boucle des avenants***************************


while ($row_v=$rqtv->fetch()){
	$cpt++;
	$j=9+$cpt;
// boucle d'allignement centée
	for($lettr='A'; $lettr!='Y';$lettr++){
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


	$objPHPExcel->getActiveSheet()->setCellValue('A'.$j, $cpt);
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$j, $row_v['lib_prod']);
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$j, $typav);
	$objPHPExcel->getActiveSheet()->setCellValue('D'.$j, $agencesel);
	$objPHPExcel->getActiveSheet()->setCellValue('E'.$j, substr($row_v['dat_val'],0,4));
	$objPHPExcel->getActiveSheet()->setCellValue('F'.$j, $row_v['lib_mpay']);
	$objPHPExcel->getActiveSheet()->setCellValue('G'.$j, $row_v['code_prod']);
	$objPHPExcel->getActiveSheet()->setCellValue('H'.$j, $row_v['seq2']);
	$objPHPExcel->getActiveSheet()->setCellValue('I'.$j, $user);
	$objPHPExcel->getActiveSheet()->setCellValue('J'.$j, substr($row_v['datev'],0,4));
	$objPHPExcel->getActiveSheet()->setCellValue('K'.$j, $row_v['lib_mpay']);
	$objPHPExcel->getActiveSheet()->setCellValue('L'.$j, $row_v['code_prod']);
	$objPHPExcel->getActiveSheet()->setCellValue('M'.$j, $row_v['sequence']);
	$objPHPExcel->getActiveSheet()->setCellValue('N'.$j, date("d/m/Y", strtotime($row_v['dat_val'])));
	$objPHPExcel->getActiveSheet()->setCellValue('O'.$j, date("d/m/Y", strtotime($row_v['dat_eff'])));
	$objPHPExcel->getActiveSheet()->setCellValue('P'.$j, date("d/m/Y", strtotime($row_v['dat_ech'])));
	$objPHPExcel->getActiveSheet()->setCellValue('Q'.$j, $row_v['pn']);$tpn=$tpn+$row_v['pn'];
	$objPHPExcel->getActiveSheet()->setCellValue('R'.$j, $row_v['mtt_cpl']);$tcp=$tcp+$row_v['mtt_cpl'];
	$objPHPExcel->getActiveSheet()->setCellValue('S'.$j, $row_v['mtt_dt']);$tdt=$tdt+$row_v['mtt_dt'];
	$objPHPExcel->getActiveSheet()->setCellValue('T'.$j, $row_v['nom_sous']);
	$objPHPExcel->getActiveSheet()->setCellValue('U'.$j, $row_v['pnom_sous']);
	$objPHPExcel->getActiveSheet()->setCellValue('V'.$j, $row_v['adr_sous']);
	$objPHPExcel->getActiveSheet()->setCellValue('W'.$j, nbassu($row_v['cod_sous']));
	$objPHPExcel->getActiveSheet()->setCellValue('X'.$j, $row_v['apporteur']);

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

$objPHPExcel->getActiveSheet()->getStyle('A9:X'.$j)->applyFromArray($styleThinBlackBorderOutline);
// la derniere ligne des totaux 

$i=$j+1;

//**************
for($lettre2='Q'; $lettre2!='T';$lettre2++){
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
$objPHPExcel->getActiveSheet()->setCellValue('Q'.$i, $tpn);
$objPHPExcel->getActiveSheet()->setCellValue('R'.$i, $tcp);
$objPHPExcel->getActiveSheet()->setCellValue('S'.$i, $tdt);
$objPHPExcel->getActiveSheet()->getStyle('Q'.$i.':S'.$i)->applyFromArray($styleThinBlackBorderOutline);

// La fin du tableau



// Nom de la Feuille 
$objPHPExcel->getActiveSheet()->setTitle('Production-SIGMA');

// Deuxième Feuille Liste des Assurés

$objPHPExcel->createSheet(1);
$objPHPExcel->setActiveSheetIndex(1);
$objPHPExcel->getActiveSheet()->setTitle('Liste-Assures');
$objPHPExcel->getActiveSheet()->mergeCells('A2:C2');
$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setName('Candara');
$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setSize(20);
$objPHPExcel->getActiveSheet()->setCellValue('A2', 'Liste des assures');


// Bordure de table
$objPHPExcel->getActiveSheet()->getStyle('A6:K6')->applyFromArray(
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
for($lettr='A'; $lettr!='L';$lettr++){
	$objPHPExcel->getActiveSheet()->getStyle($lettr.'6')->applyFromArray(
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

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(25);

$objPHPExcel->getActiveSheet()->setCellValue('A6', 'Numero');
$objPHPExcel->getActiveSheet()->setCellValue('B6', 'Agence');
$objPHPExcel->getActiveSheet()->setCellValue('C6', 'Exercice');
$objPHPExcel->getActiveSheet()->setCellValue('D6', 'Type');
$objPHPExcel->getActiveSheet()->setCellValue('E6', 'Produit');
$objPHPExcel->getActiveSheet()->setCellValue('F6', 'Code');
$objPHPExcel->getActiveSheet()->setCellValue('G6', 'N°Assure');
$objPHPExcel->getActiveSheet()->setCellValue('H6', 'nom');
$objPHPExcel->getActiveSheet()->setCellValue('I6', 'Prenom');
$objPHPExcel->getActiveSheet()->setCellValue('J6', 'Date de Naissance');
$objPHPExcel->getActiveSheet()->setCellValue('K6', 'Adressse');
$styleThinBlackBorderOutline = array(
	'borders' => array(
		'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => 'FF000000'),
		),
	),
);
$objPHPExcel->getActiveSheet()->getStyle('A6:K6')->applyFromArray($styleThinBlackBorderOutline);

// Requete des assuré
if($user!=0){

if($type=='user'){
//Agence
$rqtass = $bdd->prepare("SELECT d.`dat_val`,d.`sequence`,p.`code_prod`,p.`lib_prod` ,s.`cod_sous`,s.`nom_sous`,s.`pnom_sous`,s.`adr_sous`,s.`dnais_sous`,s.`rp_sous`,s.`nb_assu`,u.`agence` FROM `policew` as d, `dtimbre` as t , `cpolice` as c,`produit` as p,`souscripteurw` as s, `utilisateurs` as u  WHERE d.`cod_dt`=t.`cod_dt` AND d.`cod_cpl`=c.`cod_cpl` AND d.`cod_prod`=p.`cod_prod` AND d.`cod_sous`=s.`cod_sous` AND s.`id_user`=u.`id_user` AND u.`agence`='$agencesel' AND DATE_FORMAT(d.`dat_val`,'%Y-%m-%d') >='$dated' AND DATE_FORMAT(d.`dat_val`,'%Y-%m-%d') <='$datef'");
$rqtass->execute();
}else{
//DR
$rqtass = $bdd->prepare("SELECT d.`dat_val`,d.`sequence`,p.`code_prod`,p.`lib_prod` ,s.`cod_sous`,s.`nom_sous`,s.`pnom_sous`,s.`adr_sous`,s.`dnais_sous`,s.`rp_sous`,s.`nb_assu`,u.`agence` FROM `policew` as d, `dtimbre` as t , `cpolice` as c,`produit` as p,`souscripteurw` as s, `utilisateurs` as u  WHERE d.`cod_dt`=t.`cod_dt` AND d.`cod_cpl`=c.`cod_cpl` AND d.`cod_prod`=p.`cod_prod` AND d.`cod_sous`=s.`cod_sous` AND s.`id_user`=u.`id_user` AND u.`id_par`='$user' AND DATE_FORMAT(d.`dat_val`,'%Y-%m-%d') >='$dated' AND DATE_FORMAT(d.`dat_val`,'%Y-%m-%d') <='$datef'");
$rqtass->execute();


}



}else{
//Generale
$rqtass = $bdd->prepare("SELECT d.`dat_val`,d.`sequence`,p.`code_prod`,p.`lib_prod` ,s.`cod_sous`,s.`nom_sous`,s.`pnom_sous`,s.`adr_sous`,s.`dnais_sous`,s.`rp_sous`,s.`nb_assu`,u.`agence` FROM `policew` as d, `dtimbre` as t , `cpolice` as c,`produit` as p,`souscripteurw` as s, `utilisateurs` as u  WHERE d.`cod_dt`=t.`cod_dt` AND d.`cod_cpl`=c.`cod_cpl` AND d.`cod_prod`=p.`cod_prod` AND d.`cod_sous`=s.`cod_sous` AND s.`id_user`=u.`id_user` AND DATE_FORMAT(d.`dat_val`,'%Y-%m-%d') >='$dated' AND DATE_FORMAT(d.`dat_val`,'%Y-%m-%d') <='$datef'");
$rqtass->execute();


}




$cpt2=0;
while ($row_ass=$rqtass->fetch()){

$cpt2++;
$j=6+$cpt2;
//boucle pour le style
for($lettre='A'; $lettre!='L';$lettre++){
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
$cods=$row_ass['cod_sous'];
//Cas ou le souscripteur est l'assuré
if($row_ass['rp_sous']==1 && $row_ass['nb_assu']==1 ){
$objPHPExcel->getActiveSheet()->setCellValue('A'.$j, $cpt2);
$objPHPExcel->getActiveSheet()->setCellValue('B'.$j, $user);
$objPHPExcel->getActiveSheet()->setCellValue('C'.$j, substr($row_ass['dat_val'],0,4));
$objPHPExcel->getActiveSheet()->setCellValue('D'.$j, '10');
$objPHPExcel->getActiveSheet()->setCellValue('E'.$j, $row_ass['code_prod']);
$objPHPExcel->getActiveSheet()->setCellValue('F'.$j, $row_ass['sequence']);
$objPHPExcel->getActiveSheet()->setCellValue('G'.$j, '1');
$objPHPExcel->getActiveSheet()->setCellValue('H'.$j, $row_ass['nom_sous']);
$objPHPExcel->getActiveSheet()->setCellValue('I'.$j, $row_ass['pnom_sous']);
$objPHPExcel->getActiveSheet()->setCellValue('J'.$j, date("d/m/Y", strtotime($row_ass['dnais_sous'])));
$objPHPExcel->getActiveSheet()->setCellValue('K'.$j, $row_ass['adr_sous']);

// La dernière ligne du tableau
$styleThinBlackBorderOutline = array(
	'borders' => array(
		'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => 'FF000000'),
		),
	),
);

$objPHPExcel->getActiveSheet()->getStyle('A6:K'.$j)->applyFromArray($styleThinBlackBorderOutline);



}else{
//Cas ou le souscripteur n'est pas l'assuré
$rqtass2 = $bdd->prepare("SELECT `nom_sous`,`pnom_sous`,`adr_sous`,`dnais_sous` FROM `souscripteurw`  WHERE  `cod_par`='$cods'");
$rqtass2->execute();
$i=1;
while ($row_ass2=$rqtass2->fetch()){

for($lettre='A'; $lettre!='L';$lettre++){
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

$objPHPExcel->getActiveSheet()->setCellValue('A'.$j, $cpt2);
$objPHPExcel->getActiveSheet()->setCellValue('B'.$j, $user);
$objPHPExcel->getActiveSheet()->setCellValue('C'.$j, substr($row_ass['dat_val'],0,4));
$objPHPExcel->getActiveSheet()->setCellValue('D'.$j, '10');
$objPHPExcel->getActiveSheet()->setCellValue('E'.$j, $row_ass['code_prod']);
$objPHPExcel->getActiveSheet()->setCellValue('F'.$j, $row_ass['sequence']);
$objPHPExcel->getActiveSheet()->setCellValue('G'.$j, $i);
$objPHPExcel->getActiveSheet()->setCellValue('H'.$j, $row_ass2['nom_sous']);
$objPHPExcel->getActiveSheet()->setCellValue('I'.$j, $row_ass2['pnom_sous']);
$objPHPExcel->getActiveSheet()->setCellValue('J'.$j, date("d/m/Y", strtotime($row_ass2['dnais_sous'])));
$objPHPExcel->getActiveSheet()->setCellValue('K'.$j, $row_ass['adr_sous']);
$i++;

$objPHPExcel->getActiveSheet()->getStyle('A6:K'.$j)->applyFromArray($styleThinBlackBorderOutline);

$cpt2++;$j++;


}//Fin de la boucle imbriquée





}










}








?>