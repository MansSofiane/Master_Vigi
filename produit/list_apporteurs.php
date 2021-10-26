<?php
session_start();
require_once("../../../data/conn4.php");

$id_user = $_SESSION['id_user'];

if ($_SESSION['login']){}
else {
    header("Location:../index.html?erreur=login"); // redirection en cas d'echec
}
if (isset($_REQUEST['page'])) {
    $page = $_REQUEST['page'];}else{$page=0;}
if (isset($_REQUEST['rech'])) {
    $rech = $_REQUEST['rech'];


//Calcule du nombre de page
    $rqtc = $bdd->prepare("SELECT * FROM `agence`  WHERE  lib_agence LIKE '%$rech%' and typ_agence='2' ORDER BY `cod_agence` DESC");
    $rqtc->execute();
    $nbe = $rqtc->rowCount();
    $nbpage = ceil($nbe / 8);
//Pointeur de page
    $part = $page * 8;
//requete à suivre

    $rqt = $bdd->prepare("SELECT * FROM `agence`  WHERE typ_agence='2'  and lib_agence LIKE '%$rech%' ORDER BY `cod_agence` DESC  LIMIT $part ,8");
    $rqt->execute();


}else{

    $rqtc = $bdd->prepare("SELECT * FROM `agence`  WHERE typ_agence='2'  ORDER BY `cod_agence` DESC");
    $rqtc->execute();
    $nbe = $rqtc->rowCount();
    $nbpage = ceil($nbe / 8);
//Pointeur de page
    $part = $page * 8;
//requete à suivre

    $rqt = $bdd->prepare("SELECT * FROM `agence`  WHERE typ_agence='2'   ORDER BY `cod_agence` DESC  LIMIT $part ,8");
    $rqt->execute();
}


?>


<div id="content-header">
    <div id="breadcrumb">  <a class="current1">Apporteurs-Affaires</a><a class="current">Liste-Apporteurs</a> </div>
</div>
<div class="widget-box">
    <ul class="quick-actions">
        <li class="bg_lo"> <a onClick="Menu('macc','dash.php')"> <i class="icon-home"></i>Acceuil </a> </li>
        <li class="bg_ly"> <a onClick="Menu('avoy','produit/list_apporteurs.php')"> <i class="icon-folder-open"></i> Liste Apporteurs</a> </li>
        <li class="bg_lg"> <a onClick="list_aport()"> <i class="icon-folder-open"></i> Imprimer</a> </li>

    </ul>
</div>
<div class="widget-box">
    <div class="widget-title">
        <div><input type="text" id="nag" onchange="frechag()" class="span4" placeholder="Rechercher par Agence..."/></div>
    </div>
    <div class="widget-content nopadding">

        <table class="table table-bordered data-table">
            <thead>
            <tr>
                <th></th>
                <th>Apporteur</th>
                <th>Nom </th>
                <th>Prenom</th>
                <th>mail</th>
                <th>Adresse</th>
                <th>Phone</th>
                <th>Date de creation </th>
               <!-- <th></th>-->
            </tr>
            </thead>
            <tbody>
            <?php

            while ($row_res=$rqt->fetch()){  ?>
                <!-- Ici les lignes du tableau zone-->


                    <td><a title="Police-Valide"><img  src="img/icons/icon_3.png"/></a></td>


                    <td><?php  echo $row_res['lib_agence']; ?></td>
                     <td><?php  echo $row_res['nom_rep']; ?></td>
                      <td><?php  echo $row_res['prenom_rep']; ?></td>
                       <td><?php  echo $row_res['mail']; ?></td>
                        <td><?php  echo $row_res['adr_agence']; ?></td>
                         <td><?php  echo $row_res['tel_agence']; ?></td>
                          <td><?php  echo $row_res['date']; ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <div class="form-actions" align="right">
            <a href="sortie/apporteurs" onClick="window.open(this.href, 'Apporteurs', 'height=600, width=800, toolbar=no, menubar=no, scrollbars=no, resizable=no, location=no, directories=no, status=no'); return(false);" title="Imprimer"><i CLASS="icon-print icon-2x" style="color:#0e90d2"/></a>&nbsp;&nbsp;&nbsp;

        </div>
    </div>
    <div class="widget-title" align="center">
        <h5>Visualisation-Liste-Apporteurs</h5>
        <a href="javascript:;" title="Premiere page" onClick="fpageag('0','<?php echo $nbpage; ?>')"><img  src="img/icons/fprec.png"/></a>
        <a href="javascript:;" title="Precedent" onClick="fpageag('<?php echo $page-1; ?>','<?php echo $nbpage; ?>')"><img  src="img/icons/prec.png"/></a>
        <?php echo $page; ?>/<?php echo $nbpage; ?>
        <a href="javascript:;" title="Suivant" onClick="fpageag('<?php echo $page+1; ?>','<?php echo $nbpage; ?>')"><img  src="img/icons/suiv.png"/></a>
        <a href="javascript:;" title="Derniere page" onClick="fpageag('<?php echo $nbpage-1; ?>','<?php echo $nbpage; ?>')"><img  src="img/icons/fsuiv.png"/></a>
    </div>
</div>






<script language="JavaScript">
    function fpageag(page,nbpage){
        if(page >=0){
            if(page == nbpage){
                swal("Information !","Vous ete a la derniere page!","info");
            }else{$("#content").load('produit/list_apporteurs.php?page='+page);}
        }else{swal("Information !","Vous ete en premiere page !","info");}
    }

    function frechag(){
        var rech=document.getElementById("nag").value;
        $("#content").load('produit/list_apporteurs.php?rech='+rech);
    }

</script>
</body>
</html>