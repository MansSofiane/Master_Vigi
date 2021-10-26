 <?php


 // Ajout de la classe PHP Excel
require('Classes/PHPExcel.php');

// Cr�ation de l'objet PHPExcel
$objPHPExcel = new PHPExcel(); 

 // D�finition d'une propri�t� par instruction
$objPHPExcel->getProperties()->setCreator('Infoject\'');
$objPHPExcel->getProperties()->setLastModifiedBy('Infoject\'');

// D�finition de plusieurs propri�t�s
$objPHPExcel->getProperties()->setTitle('Test de PHP Excel')
                             ->setSubject('Test de PHP Excel')
                             ->setDescription('Fichier de test de PHP Excel.')
                             ->setKeywords('phpexcel test')
                             ->setCategory('Fichier de test'); 

 // D�finition de la feuille active
$objPHPExcel->setActiveSheetIndex(0);
// Titre de la feuille
$objPHPExcel->getActiveSheet()->setTitle('Feuille de test');
// Donn�es de la cellule A1
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Fichier de test');

// Saisie de plusieurs cellules en une instruction
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A2', '5')
            ->setCellValue('B2', '37')
            ->setCellValue('A3', 'R�sultat')
            ->setCellValue('B3', '=A2+B2');

// Utilisation de la m�thode setCellValueByColumnAndRow() pour �crire dans la cellule A4
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'A4 => 21*29,7'); 
 // Modification de la couleur du texte
$objPHPExcel->getActiveSheet()->getStyle('B3')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLUE);

// Aligner le texte au milieu � droite
$objPHPExcel->getActiveSheet()->getStyle('B3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

// Modification de la bordure inf�rieure de la cellule
$objPHPExcel->getActiveSheet()->getStyle('B3')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

// Modification de la largeur de la colonne
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15); 
 // Enregistrement de l'objet $objPHPExcel dans le fichier Fichier de test.xlsx
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->save('Fichier de test.xlsx'); 


?> 