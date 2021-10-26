<?php
session_start();
require_once("../../../data/conn4.php");
ini_set('memory_limit', '1024M');
if ($_SESSION['login']){}
else {
    header("Location:../index.html?erreur=login");
}
if (isset($_REQUEST['row']) && isset($_REQUEST['row1'])  ) {
    $annee = $_REQUEST['row'];
    $mois = $_REQUEST['row1'];
    function obtenirLibelleMois($mois) {
        switch($mois) {
            case '01': $mois = 'Janvier'; break;
            case '02': $mois = 'Février'; break;
            case '03': $mois = 'Mars'; break;
            case '04': $mois = 'Avril'; break;
            case '05': $mois = 'Mai'; break;
            case '06': $mois = 'Juin'; break;
            case '07': $mois = 'Juillet'; break;
            case '08': $mois = 'Août'; break;
            case '09': $mois = 'Septembre'; break;
            case '10': $mois = 'Octobre'; break;
            case '11': $mois = 'Novembre'; break;
            case '12': $mois = 'Decembre'; break;
            case 'January': $mois = 'Janvier'; break;
            case 'February': $mois = 'Février'; break;
            case 'March': $mois = 'Mars'; break;
            case 'April': $mois = 'Avril'; break;
            case 'May': $mois = 'Mai'; break;
            case 'June': $mois = 'Juin'; break;
            case 'July': $mois = 'Juillet'; break;
            case 'August': $mois = 'Août'; break;
            case 'September': $mois = 'Septembre'; break;
            case 'October': $mois = 'Octobre'; break;
            case 'November': $mois = 'Novembre'; break;
            case 'December': $mois = 'Decembre'; break;
            default: $mois =''; break;
        }
        return $mois;
    }
$mois_lettre=obtenirLibelleMois($mois);
    $dated = date("Y-m-d", mktime(0, 0, 0, $mois, 1 ,$annee));
    $datef = date("Y-m-d", mktime(0, 0, 0, $mois +1, 0, $annee));

   // $dated = date('Y-m-d', strtotime($dated));
  //  $dated = date('Y-m-d', '01/'.$mois."/".$annee);
  //  $datef=date('Y-m-d', strtotime($datef));
    $prod = '1';


}
?>
<link rel="stylesheet" href="css/css1.css" type="text/css" media="screen" title="default" />

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Validation</title>

</head>

<body>
<?php
$datesys=date("y-m-d H:i:s");
//nom de la recap excel
$excel="Export-Excel";
//$excel = str_replace('/', '-', $excel);
$excel=$excel."_".$mois_lettre."_".$annee.".xls";
//creation du fichier excel
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
include "mgenerateurg.php";
$callStartTime = microtime(true);
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$chemin="../doc/file/excel/".$excel;
$objWriter->save($chemin);
$callEndTime = microtime(true);
$callTime = $callEndTime - $callStartTime;
$i = 0;
$folder = "../doc/file/excel/";
$dossier = opendir($folder);
while (false !== ($Fichier = readdir($dossier))) {
    if ($Fichier != "." && $Fichier != "..") {

        $row_file[$i] = $Fichier;
        $i++;
    }
}
closedir($dossier);
if($i>0){
?>
<div id="page-heading">
    <h1><b>Export Excel <?php echo $dated;?></b></h1>
</div>
<div>
    <br />
    <div> <p>Veuillez cliquer pour telecharger le fichier!</p></div>
    <ul>
        <br />

                <li> <a href="../../../doc/file/excel/<?php echo $excel?>">Export-Excel</a></li>




        <?php } ?>

</body>
</html>

