<?php session_start();
require_once("../../../../data/conn4.php");
$user=$_SESSION['id_user'];
// recupération du code du dernier souscripteur de l'agence	
$rqtu=$bdd->prepare("SELECT agence  FROM `utilisateurs` WHERE id_user='$user'");
$rqtu->execute();
$agence=0;
while ($rowu=$rqtu->fetch()){ 
$agence=$rowu['agence']; 
}
$i = 0;
if(isset($_REQUEST['row'])){$row=$_REQUEST['row'];}
$folder = "../indacc/documents/";
$dossier = opendir($folder);
while (false !== ($Fichier = readdir($dossier))) {
     if ($Fichier != "." && $Fichier != "..") {
	 
		$row_file[$i] = $Fichier;	
		$i++;
	 }
}
closedir($dossier);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body>
<div id="content">
<br />
<br />

<?php if($i>=1){
?>
<div id="page-heading">
		<h1><b>Fichiers en attente de traitement</b></h1>
	</div>
<div>
<br />
<div> <p>Veuillez cliquer sur le fichier pour le telecharger</p></div>
<ul>
<br />
<?php 
for($index=0;$index<$i; $index++){  
      $test = substr($row_file[$index],0,6);
  $test2 = substr($row_file[$index],0,5);

       if($test==$agence || $test2==$agence ){

?>
<li> <a href="../indacc/documents/<?php echo $row_file[$index]?>">

<?php echo $row_file[$index]?></a></li>
<?php }

 } 
 }else{
 ?>
<div id="page-heading">
		<h1><b>Aucun fichier en attente !</b></h1>
	</div>
<?php } ?>
 
</ul>
</div>
</body>
</html>

