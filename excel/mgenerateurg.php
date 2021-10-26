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



 // requete sur toute la base de données

    $rqtg = $bdd->prepare("
            SELECT s.rp_sous, s.cod_sous, s.passport,'CASH' as reseau, u.agence, s.nom_sous, s.pnom_sous, s.adr_sous ,'' as ville, p.lib_pays, s.dnais_sous,'10' as cod_av, d.sequence, d.dat_val, d.dat_eff, d.dat_ech, o.lib_opt, pr.code_prod
            FROM `policew`  as d, souscripteurw as s, utilisateurs  as u, pays as p, `option` as o, produit as pr
            WHERE d.cod_sous =s.cod_sous and s.id_user =u.id_user and d.cod_pays =p.cod_pays and d.cod_opt =o.cod_opt and d.cod_prod=1 and s.rp_sous=1 and d.cod_prod=pr.cod_prod AND DATE_FORMAT(d.`dat_val`,'%Y-%m-%d') >='$dated'
              AND DATE_FORMAT(d.`dat_val`,'%Y-%m-%d') <='$datef'
      union
            select t1.rp_sous, t1.cod_sous, t1.passport,'CASH' as reseau , tab.agence, t1.nom_sous, t1.pnom_sous, t1.adr_sous ,'' as ville, tab.lib_pays, t1.dnais_sous,'10' as cod_av, tab.sequence, tab.dat_val, tab.dat_eff, tab.dat_ech
     ,            tab.lib_opt, tab.code_prod
            from (select g.rp_sous,g.cod_sous,g.passport,g.cod_par,g.nom_sous,g.pnom_sous,g.adr_sous,g.dnais_sous
                  from souscripteurw  as g)as t1
                        inner join ( SELECT s.rp_sous, s.cod_sous , s.passport ,'CASH' as reseau , u.agence , s.nom_sous , s.pnom_sous , s.adr_sous ,'' as ville , p.lib_pays , s.dnais_sous ,'10' as cod_av , d.sequence , d.dat_val,
                                            d.dat_eff , d.dat_ech , o.lib_opt , pr.code_prod
                                      FROM `policew`as d, souscripteurw as s, utilisateurs  as u , pays  as p , `option`  as o, produit  as pr
                                      WHERE
       d.cod_sous                               =s.cod_sous
       and s.id_user                            =u.id_user
       and d.cod_pays                           =p.cod_pays
       and d.cod_opt                            =o.cod_opt
       and d.cod_prod                           =1
       and d.cod_prod                           =pr.cod_prod
       AND DATE_FORMAT(d.`dat_val`,'%Y-%m-%d') >='$dated'
       AND DATE_FORMAT(d.`dat_val`,'%Y-%m-%d') <='$datef') as tab
	   on t1.cod_par=tab.cod_sous ");
    $rqtg->execute();
    $rqtv = $bdd->prepare("

SELECT s.passport,'CASH' as reseau,u.agence,s.nom_sous,s.pnom_sous,s.adr_sous,'' as ville,y.lib_pays ,s.dnais_sous,a.lib_mpay as N_avenant,a.dat_val as d_av,p.dat_val,p.sequence,a.ndat_eff,a.ndat_ech,o.lib_opt
FROM `avenantw`  as a,policew as p, souscripteurw as s,utilisateurs as u,pays as y,`option` as o
WHERE a.cod_prod=1
AND p.cod_sous=s.cod_sous
AND a.cod_pol=p.cod_pol
AND s.id_user=u.id_user
AND y.cod_pays=a.cod_pays
AND a.cod_opt=o.cod_opt
 AND DATE_FORMAT(a.`dat_val`,'%Y-%m-%d') >='$dated'
       AND DATE_FORMAT(a.`dat_val`,'%Y-%m-%d') <='$datef'
	   AND s.rp_sous=1


UNION

select
t1.passport,'CASH' as reseau,tab.agence,t1.nom_sous,t1.pnom_sous,t1.adr_sous,'' as ville,tab.lib_pays,t1.dnais_sous,tab.N_avenant as N_avenant,tab.d_av, tab.dat_val,tab.sequence,tab.ndat_eff,tab.ndat_ech,tab.lib_opt




from


(select g.rp_sous,g.cod_sous,g.passport,g.cod_par,g.nom_sous,g.pnom_sous,g.adr_sous,g.dnais_sous
from souscripteurw  as g)as t1

INNER JOIN
(

SELECT s.cod_sous,s.passport,'CASH' as reseau,u.agence,s.nom_sous,s.pnom_sous,s.adr_sous,'' as ville,y.lib_pays ,s.dnais_sous,a.lib_mpay N_avenant,a.dat_val as d_av,p.dat_val,p.sequence,a.ndat_eff,a.ndat_ech,o.lib_opt
FROM `avenantw`  as a,policew as p, souscripteurw as s,utilisateurs as u,pays as y,`option` as o
WHERE a.cod_prod=1
AND p.cod_sous=s.cod_sous
AND a.cod_pol=p.cod_pol
AND s.id_user=u.id_user
AND y.cod_pays=a.cod_pays
AND a.cod_opt=o.cod_opt
 AND DATE_FORMAT(a.`dat_val`,'%Y-%m-%d') >='$dated'
       AND DATE_FORMAT(a.`dat_val`,'%Y-%m-%d') <='$datef'

)	 as  tab

on t1.cod_par=tab.cod_sous
	   ");
    $rqtv->execute();



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
$objPHPExcel->getActiveSheet()->setCellValue('F2', 'Export reseau CASH');
$objPHPExcel->getActiveSheet()->mergeCells('F3:H3');
$clt="Mapfre";
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
$objPHPExcel->getActiveSheet()->setCellValue('A7', 'Agence : CASH');
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
$objPHPExcel->getActiveSheet()->getStyle('A9:N9')->applyFromArray(
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
for($lettr='A'; $lettr!='N';$lettr++){
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
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
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

//Premieres Colones du tableau 
$objPHPExcel->getActiveSheet()->setCellValue('A9', 'N_Passport');
$objPHPExcel->getActiveSheet()->setCellValue('B9', 'Code-Agence');
$objPHPExcel->getActiveSheet()->setCellValue('C9', 'Nom');
$objPHPExcel->getActiveSheet()->setCellValue('D9', 'Prénom');
$objPHPExcel->getActiveSheet()->setCellValue('E9', 'Adresse');
$objPHPExcel->getActiveSheet()->setCellValue('F9', 'Ville');
$objPHPExcel->getActiveSheet()->setCellValue('G9', 'Zone/pays');
$objPHPExcel->getActiveSheet()->setCellValue('H9', 'Date de naissance');
$objPHPExcel->getActiveSheet()->setCellValue('I9', 'Cod_Avenant');
$objPHPExcel->getActiveSheet()->setCellValue('J9', 'N_Police');
$objPHPExcel->getActiveSheet()->setCellValue('K9', 'Date de souscription');
$objPHPExcel->getActiveSheet()->setCellValue('L9', "Date d'effet");
$objPHPExcel->getActiveSheet()->setCellValue('M9', 'Date echeance');
$objPHPExcel->getActiveSheet()->setCellValue('N9', 'Type contrat');

$styleThinBlackBorderOutline = array(
    'borders' => array(
        'outline' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb' => 'FF000000'),
        ),
    ),
);
$objPHPExcel->getActiveSheet()->getStyle('A9:N9')->applyFromArray($styleThinBlackBorderOutline);

//Recuperation des données de l'opération

//$connection->requete($query_ann);
$cpt='0';
$j=9;



//*************************************Debut de la boucle generation des polices*****************

//while ($row_ann2 =$connection->enr_actuel()){
while ($row_g=$rqtg->fetch()){

    $cpt++;
    $j=9+$cpt;
//boucle pour le style
  /*  for($lettre='A'; $lettre!='N';$lettre++){
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
    }*/
    $objPHPExcel->getActiveSheet()->setCellValue('A'.$j, $row_g['passport']);
    $objPHPExcel->getActiveSheet()->setCellValue('B'.$j, $row_g['agence']);
    $objPHPExcel->getActiveSheet()->setCellValue('C'.$j,  $row_g['nom_sous']);
    $objPHPExcel->getActiveSheet()->setCellValue('D'.$j,  $row_g['pnom_sous']);
    $objPHPExcel->getActiveSheet()->setCellValue('E'.$j,  $row_g['adr_sous']);

    $objPHPExcel->getActiveSheet()->setCellValue('F'.$j, '');
    $objPHPExcel->getActiveSheet()->setCellValue('G'.$j,  $row_g['lib_pays']);
    $objPHPExcel->getActiveSheet()->setCellValue('H'.$j, $row_g['dnais_sous']);
    $objPHPExcel->getActiveSheet()->setCellValue('I'.$j, '10');
    $objPHPExcel->getActiveSheet()->setCellValue('J'.$j, $row_g['agence'].'.'.substr($row_g['dat_val'],0,4).'.10.18.2'.'.'.str_pad((int) $row_g['sequence'],'5',"0",STR_PAD_LEFT));
    $objPHPExcel->getActiveSheet()->setCellValue('K'.$j, date("d/m/Y", strtotime($row_g['dat_val'])));
    $objPHPExcel->getActiveSheet()->setCellValue('L'.$j, date("d/m/Y", strtotime($row_g['dat_eff'])));
    $objPHPExcel->getActiveSheet()->setCellValue('M'.$j, date("d/m/Y", strtotime($row_g['dat_ech'])));
    $objPHPExcel->getActiveSheet()->setCellValue('N'.$j, $row_g['lib_opt']);


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
  /*  for($lettr='A'; $lettr!='N';$lettr++){
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
  */
//Libelle du type de l'avenant


    //$objPHPExcel->getActiveSheet()->getStyle('A'.$j.':N'.$j)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
   // $objPHPExcel->getActiveSheet()->getStyle('A'.$j.':N'.$j)->getFill()->getStartColor()->setARGB('FAFAD2');
    $objPHPExcel->getActiveSheet()->setCellValue('A'.$j, $row_v['passport']);
    $objPHPExcel->getActiveSheet()->setCellValue('B'.$j, $row_v['agence']);
    $objPHPExcel->getActiveSheet()->setCellValue('C'.$j,  $row_v['nom_sous']);
    $objPHPExcel->getActiveSheet()->setCellValue('D'.$j,  $row_v['pnom_sous']);
    $objPHPExcel->getActiveSheet()->setCellValue('E'.$j,  $row_v['adr_sous']);

    $objPHPExcel->getActiveSheet()->setCellValue('F'.$j, '');
    $objPHPExcel->getActiveSheet()->setCellValue('G'.$j,  $row_v['lib_pays']);
    $objPHPExcel->getActiveSheet()->setCellValue('H'.$j, $row_v['dnais_sous']);
    $objPHPExcel->getActiveSheet()->setCellValue('I'.$j, $row_v['N_avenant']);
    $objPHPExcel->getActiveSheet()->setCellValue('J'.$j, $row_v['agence'].'.'.substr($row_v['dat_val'],0,4).'.10.18.2'.'.'.str_pad((int) $row_v['sequence'],'5',"0",STR_PAD_LEFT));
    $objPHPExcel->getActiveSheet()->setCellValue('K'.$j, date("d/m/Y", strtotime($row_v['d_av'])));
    $objPHPExcel->getActiveSheet()->setCellValue('L'.$j, date("d/m/Y", strtotime($row_v['ndat_eff'])));
    $objPHPExcel->getActiveSheet()->setCellValue('M'.$j, date("d/m/Y", strtotime($row_v['ndat_ech'])));
    $objPHPExcel->getActiveSheet()->setCellValue('N'.$j, $row_v['lib_opt']);

// Fin de la boucle Avenants
}
//******************************************************fin de la boucle generation des avenants ************

//Fin de la boucle des Avenants
// Encadrement du tableau
/*
$styleThinBlackBorderOutline = array(
    'borders' => array(
        'outline' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb' => 'FF000000'),
        ),
    ),
);
*/

// Nom de la Feuille 
$objPHPExcel->getActiveSheet()->setTitle('Export-cash_'.date("Y", strtotime($dated)).'_'.date("m", strtotime($dated)));
// Protect par mot de passe du tableau ici
//$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);	
//$objPHPExcel->getActiveSheet()->protectCells('A1:K'.$sig3, 'aglic');

?>