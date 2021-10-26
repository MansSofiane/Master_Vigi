<?php session_start();
require_once("../../../../../../data/conn4.php");
$folder = "../file/documents/";
if ($_SESSION['login']){
}
else {
    header("Location:login.php");
}
$id_user = $_SESSION['id_user'];
$datesys=date("Y-m-d");

if (  isset ($_REQUEST['codsous'])){$codsous = $_REQUEST['codsous'];}

require_once '../file/Classes/PHPExcel/IOFactory.php';

$rqtf=$bdd->prepare("select * from document where id_user='$id_user' order by id_doc DESC LIMIT 0,1");
$rqtf->execute();
$file="";$id_doc="";
while ($row=$rqtf->fetch())
{
    $file=$row["chemin"];
    $id_doc=$row["id_doc"];
}


$objPHPExcel = PHPExcel_IOFactory::load($folder. $file.".xlsx");

/**
 * récupération de la première feuille du fichier Excel
 * @var PHPExcel_Worksheet $sheet
 */
$sheet = $objPHPExcel->getSheet(0);

//echo '<table border="1">';

// On boucle sur les lignes
$i=0;
foreach($sheet->getRowIterator() as $row)
{
    $j=0;

    foreach ($row->getCellIterator() as $cell) {
        $j++;

        if($j==1 and ($cell->getValue()=="M" OR $cell->getValue()=="Mme" OR $cell->getValue()=="Mlle"  ))
        {
            $i++;
        }
    }
}

if($i>=10)
{
    echo  "0" ;
}
else
{
    echo "".$id_doc;
}

?>