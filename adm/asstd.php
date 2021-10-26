<?php 
session_start();
require_once("../../../data/conn4.php");
//Recuperation de la page demandee 
if (isset($_REQUEST['page'])) {
	$page = $_REQUEST['page'];
}else{$page=0;}
if (isset($_REQUEST['rech'])) {
	$rech = $_REQUEST['rech'];
	
//Calcule du nombre de page 
$rqtc=$bdd->prepare("SELECT d.`cod_dev`,d.`dat_eff`,d.`dat_ech`,d.`pn`,d.`pt`,d.`bool`,s.`nom_sous`,s.`pnom_sous`,s.`cod_sous`,u.`agence` FROM `devisw` as d,`souscripteurw` as s,`utilisateurs` as u WHERE s.`cod_sous`=d.`cod_sous` AND d.`etat`='0' AND d.`bool`='1' AND d.`cod_prod`='6' AND s.`id_user`=u.`id_user` AND s.`nom_sous` LIKE '%$rech%' ORDER BY d.`cod_dev` DESC");
$rqtc->execute();
$nbe = $rqtc->rowCount();
$nbpage=ceil($nbe/7);
//Pointeur de page
$part=$page*7;
//requete à suivre
$rqt=$bdd->prepare("SELECT d.`cod_dev`,d.`dat_eff`,d.`dat_ech`,d.`pn`,d.`pt`,d.`bool`,s.`nom_sous`,s.`pnom_sous`,s.`cod_sous`,u.`agence` FROM `devisw` as d,`souscripteurw` as s,`utilisateurs` as u WHERE s.`cod_sous`=d.`cod_sous` AND d.`etat`='0' AND d.`bool`='1' AND d.`cod_prod`='6' AND s.`id_user`=u.`id_user` AND s.`nom_sous` LIKE '%$rech%' ORDER BY d.`cod_dev` DESC LIMIT $part ,7");
$rqt->execute();	
	
}else{
//Calcule du nombre de page 
$rqtc=$bdd->prepare("SELECT d.`cod_dev`,d.`dat_eff`,d.`dat_ech`,d.`pn`,d.`pt`,d.`bool`,s.`nom_sous`,s.`pnom_sous`,s.`cod_sous`,u.`agence` FROM `devisw` as d,`souscripteurw` as s,`utilisateurs` as u WHERE s.`cod_sous`=d.`cod_sous` AND d.`etat`='0' AND d.`bool`='1' AND d.`cod_prod`='6' AND s.`id_user`=u.`id_user` ORDER BY d.`cod_dev` DESC");
$rqtc->execute();
$nbe = $rqtc->rowCount();
$nbpage=ceil($nbe/7);
//Pointeur de page
$part=$page*7;
//requete à suivre
$rqt=$bdd->prepare("SELECT d.`cod_dev`,d.`dat_eff`,d.`dat_ech`,d.`pn`,d.`pt`,d.`bool`,s.`nom_sous`,s.`pnom_sous`,s.`cod_sous`,u.`agence` FROM `devisw` as d,`souscripteurw` as s,`utilisateurs` as u WHERE s.`cod_sous`=d.`cod_sous` AND d.`etat`='0' AND d.`bool`='1' AND d.`cod_prod`='6' AND s.`id_user`=u.`id_user` ORDER BY d.`cod_dev` DESC LIMIT $part ,7");
$rqt->execute();
$nb = $rqt->execute();
}
?>  
  
   <div id="content-header">
    <div id="breadcrumb"> <a class="tip-bottom"><i class="icon-home"></i> Produit</a><a class="current">Temporaire-Au-Deces</a> </div>
  </div>
  <div class="widget-box">
            <ul class="quick-actions">
			  <li class="bg_lo"> <a onClick="aMenu1('macc','../adash.php')"> <i class="icon-home"></i>Acceuil </a> </li>
	          <li class="bg_lb"> <a onClick="aMenu1('prod','asstd.php')"> <i class="icon-folder-open"></i>Devis-En-Attente</a></li>
	          <li class="bg_lg"> <a onClick="aMenu1('prod','polasstd.php')"> <i class="icon-folder-open"></i>Visualiser-Contrats</a></li>
            </ul>
  </div>
   <div class="widget-box">
          <div class="widget-title"> 
		   <div><input type="text" id="ansousdtd" onchange="afrechdtd()" class="span4" placeholder="Rechercher par Nom-Souscripteur..."/></div>
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered data-table">
              <thead>
                <tr>
				  <th></th>
				  <th>Agence</th>
                  <th>Nom/Prenom</th>
				  <th>D-Effet</th>
                  <th>D-Echeance</th>
				  <th>P-Nette</th>
				  <th>P-Totale</th>
				  <th>Operations</th>
                </tr>
              </thead>
              <tbody>    
			<?php
				$i = 0;
				while ($row_res=$rqt->fetch()){  ?>
			<!-- Ici les lignes du tableau zone-->
			<tr class="gradeX">
			      <?php if($row_res['bool']==0){ ?>
			      <td><a title="Validation permise"><img  src="img/icons/icon_2.png"/></a></td>
				  <?php }
				  if($row_res['bool']==1){
				  ?>
				  <td><a title="En attente Accord"><img  src="img/icons/icon_4.png"/></a></td>
				  <?php }
				  if($row_res['bool']==2){
				  ?>
				  <td><a title="En attente Accord"><img  src="img/icons/icon_1.png"/></a></td>
				  <?php } ?>
				  <td><?php  echo $row_res['agence']; ?></td>
                  <td><?php  echo $row_res['nom_sous']."  ".$row_res['pnom_sous']; ?></td>
				  <td><?php  echo date("d/m/Y",strtotime($row_res['dat_eff'])); ?></td>
                  <td><?php  echo date("d/m/Y",strtotime($row_res['dat_ech'])); ?></td>
				  <td><?php  echo number_format($row_res['pn'], 2, ',', ' ')." DZD"; ?></td>
				  <td><?php  echo number_format($row_res['pt'], 2, ',', ' ')." DZD"; ?></td>
				  <td>&nbsp;
				 
				  <a href="sortie/devis3/<?php echo crypte($row_res['cod_dev']) ?>" onClick="window.open(this.href, 'Devis', 'height=600, width=800, toolbar=no, menubar=no, scrollbars=no, resizable=no, location=no, directories=no, status=no'); return(false);" title="Imprimer"><i CLASS="icon-print icon-2x" style="color:#0e90d2"/></a>&nbsp;&nbsp;&nbsp;
				   <a href="sortie/R-Q/<?php echo crypte($row_res['cod_sous']) ?>" onClick="window.open(this.href, 'Devis', 'height=600, width=800, toolbar=no, menubar=no, scrollbars=no, resizable=no, location=no, directories=no, status=no'); return(false);" title="Questionnaire"><img  src="img/icons/inf.png"/></a>&nbsp;&nbsp;&nbsp;
			       <a onClick="surprime('prod','asstd.php','<?php echo $row_res['cod_dev'];?>','<?php echo $row_res['pn'];?>')" title="Accorder"><img  src="img/icons/ok.png"/></a>&nbsp;&nbsp;&nbsp;
				  <a onClick="rdev('prod','asstd.php','<?php echo $row_res['cod_dev'];?>')" title="Rejeter"><img  src="img/icons/supp.png"/></a>
				  </td>
                </tr>
			<?php } ?>
              </tbody>
            </table>
          </div>
		  <div class="widget-title" align="center">
            <h5>Visualisation-Devis-Temporaire-Au-Deces</h5>
		     <a href="javascript:;" title="Premiere page" onClick="afpagedtd('0','<?php echo $nbpage; ?>')"><img  src="img/icons/fprec.png"/></a>
			 <a href="javascript:;" title="Precedent" onClick="afpagedtd('<?php echo $page-1; ?>','<?php echo $nbpage; ?>')"><img  src="img/icons/prec.png"/></a>
				  <?php echo $page; ?>/<?php echo $nbpage; ?>
			 <a href="javascript:;" title="Suivant" onClick="afpagedtd('<?php echo $page+1; ?>','<?php echo $nbpage; ?>')"><img  src="img/icons/suiv.png"/></a>
			 <a href="javascript:;" title="Derniere page" onClick="afpagedtd('<?php echo $nbpage-1; ?>','<?php echo $nbpage; ?>')"><img  src="img/icons/fsuiv.png"/></a>
          </div>
        </div>		
<script language="JavaScript">
	function afrechdtd(){
		var rech=document.getElementById("ansousdtd").value;
        $("#content").load('adm/asstd.php?rech='+rech);
	}
	function afpagedtd(page,nbpage){
		if(page >=0){
			if(page == nbpage){
				swal("Information !","Vous ete a la derniere page!","info");
			}else{$("#content").load('adm/asstd.php?page='+page);}
		}else{swal("Information !","Vous ete en premiere page !","info");}
	}	
</script>		