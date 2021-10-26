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
$rqtc=$bdd->prepare("SELECT d.`cod_dev`,d.`dat_eff`,d.`dat_ech`,d.`pn`,d.`pt`,d.`bool`,s.`nom_sous`,s.`pnom_sous`,u.`agence` FROM `devisw` as d,`souscripteurw` as s,`utilisateurs` as u WHERE s.`cod_sous`=d.`cod_sous` AND d.`etat`='0' AND d.`bool`='1' AND d.`cod_prod`='2' AND d.`cod_formul`='2' AND s.`id_user`=u.`id_user` AND s.`nom_sous` LIKE '%$rech%' ORDER BY d.`cod_dev` DESC");
$rqtc->execute();
$nbe = $rqtc->rowCount();
$nbpage=ceil($nbe/7);
//Pointeur de page
$part=$page*7;
//requete � suivre
$rqt=$bdd->prepare("SELECT d.`cod_dev`,d.`dat_eff`,d.`dat_ech`,d.`pn`,d.`pt`,d.`bool`,s.`nom_sous`,s.`pnom_sous`,u.`agence` FROM `devisw` as d,`souscripteurw` as s,`utilisateurs` as u WHERE s.`cod_sous`=d.`cod_sous` AND d.`etat`='0' AND d.`bool`='1' AND d.`cod_prod`='2' AND d.`cod_formul`='2' AND s.`id_user`=u.`id_user` AND s.`nom_sous` LIKE '%$rech%' ORDER BY d.`cod_dev` DESC LIMIT $part ,7");
$rqt->execute();	
	
}else{
//Calcule du nombre de page 
$rqtc=$bdd->prepare("SELECT d.`cod_dev`,d.`dat_eff`,d.`dat_ech`,d.`pn`,d.`pt`,d.`bool`,s.`nom_sous`,s.`pnom_sous`,u.`agence` FROM `devisw` as d,`souscripteurw` as s,`utilisateurs` as u WHERE s.`cod_sous`=d.`cod_sous` AND d.`etat`='0' AND d.`bool`='1' AND d.`cod_prod`='2' AND d.`cod_formul`='2' AND s.`id_user`=u.`id_user` ORDER BY d.`cod_dev` DESC");
$rqtc->execute();
$nbe = $rqtc->rowCount();
$nbpage=ceil($nbe/7);
//Pointeur de page
$part=$page*7;
//requete � suivre
$rqt=$bdd->prepare("SELECT d.`cod_dev`,d.`dat_eff`,d.`dat_ech`,d.`pn`,d.`pt`,d.`bool`,s.`nom_sous`,s.`pnom_sous`,u.`agence` FROM `devisw` as d,`souscripteurw` as s,`utilisateurs` as u WHERE s.`cod_sous`=d.`cod_sous` AND d.`etat`='0' AND d.`bool`='1' AND d.`cod_prod`='2' AND d.`cod_formul`='2' AND s.`id_user`=u.`id_user` ORDER BY d.`cod_dev` DESC LIMIT $part ,7");
$rqt->execute();
$nb = $rqt->execute();
}
?>  
  
 <div id="content-header">
    <div id="breadcrumb"> <a class="tip-bottom"><i class="icon-home"></i> Produit</a><a> Assurance-Individuelle-Accident</a><a class="current">Formule-Groupe</a> </div>
  </div>
  <div class="widget-box">
            <ul class="quick-actions">
			  <li class="bg_lo"> <a onClick="aMenu1('macc','../adash.php')"> <i class="icon-home"></i>Acceuil </a> </li>
	          <li class="bg_lb"> <a onClick="aMenu1('prod','assiaccgrp.php')"> <i class="icon-folder-open"></i>Devis-En-Attente</a></li>
	          <li class="bg_lg"> <a onClick="aMenu1('prod','polassiaccgrp.php')"> <i class="icon-folder-open"></i>Visualiser-Contrats</a></li> 
            </ul>
  </div>
   <div class="widget-box">
          <div class="widget-title"> 
		   <div><input type="text" id="ansousdiaccg" onchange="afrechdiaccg()" class="span4" placeholder="Rechercher par Nom-Souscripteur..."/></div>
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
				 
				  <a href="sortie/devis5/<?php echo crypte($row_res['cod_dev']) ?>" onClick="window.open(this.href, 'Devis', 'height=600, width=800, toolbar=no, menubar=no, scrollbars=no, resizable=no, location=no, directories=no, status=no'); return(false);" title="Imprimer"><i CLASS="icon-print icon-2x" style="color:#0e90d2"/></a>&nbsp;&nbsp;&nbsp;
			      <a onClick="surprime('prod','assiaccgrp.php','<?php echo $row_res['cod_dev'];?>','<?php echo $row_res['pn'];?>')" title="Accorder"><img  src="img/icons/ok.png"/></a>&nbsp;&nbsp;&nbsp;
				  <a onClick="rdev('prod','assiaccgrp.php','<?php echo $row_res['cod_dev'];?>')" title="Rejeter"><img  src="img/icons/supp.png"/></a>
				  </td>
                </tr>
			<?php } ?>
              </tbody>
            </table>
          </div>
		  <div class="widget-title" align="center">
            <h5>Visualisation-Devis-Individuelle-Accident</h5>
		     <a href="javascript:;" title="Premiere page" onClick="afpagediaccg('0','<?php echo $nbpage; ?>')"><img  src="img/icons/fprec.png"/></a>
			 <a href="javascript:;" title="Precedent" onClick="afpagediaccg('<?php echo $page-1; ?>','<?php echo $nbpage; ?>')"><img  src="img/icons/prec.png"/></a>
				  <?php echo $page; ?>/<?php echo $nbpage; ?>
			 <a href="javascript:;" title="Suivant" onClick="afpagediaccg('<?php echo $page+1; ?>','<?php echo $nbpage; ?>')"><img  src="img/icons/suiv.png"/></a>
			 <a href="javascript:;" title="Derniere page" onClick="afpagediaccg('<?php echo $nbpage-1; ?>','<?php echo $nbpage; ?>')"><img  src="img/icons/fsuiv.png"/></a>
          </div>
        </div>		
<script language="JavaScript">
	function afrechdiaccg(){
		var rech=document.getElementById("ansousdiaccg").value;
        $("#content").load('adm/assiaccgrp.php?rech='+rech);
	}
	function afpagediaccg(page,nbpage){
		if(page >=0){
			if(page == nbpage){
				swal("Information !","Vous ete a la derniere page!","info");
			}else{$("#content").load('adm/assiaccgrp.php?page='+page);}
		}else{swal("Information !","Vous ete en premiere page !","info");}
	}		
</script>		