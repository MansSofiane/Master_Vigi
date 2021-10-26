<?php
session_start();
require_once("../../../data/connAGB.php");
//Recuperation de la page demandee 
if (isset($_REQUEST['page'])) {
    $page = $_REQUEST['page'];
}else{$page=0;}
$id_user = $_SESSION['id_user2'];

$rqtag=$bdd->prepare("select agence from utilisateurs where id_user='$id_user'");
$rqtag->execute();
$agence="";
while ($rowag=$rqtag->fetch())
{
    $agence= $rowag['agence'];
}
$rech='';$crit='';
if (isset($_REQUEST['rech'])) {
    $rech = $_REQUEST['rech'];
    $crit=$_REQUEST['crit'];
    $condition="";
    if($crit==1){$condition="d.sequence='".$rech."'";}//code devis
    if($crit==2){$condition="s.nom_sous like '%".$rech."%'";}//nom souscripteur
//Calcule du nombre de page 
    $rqtc=$bdd->prepare("SELECT d.`cod_pol`,d.`sequence`,d.`dat_eff`,d.`dat_ech`,d.`pn`,d.`pt`,d.`etat`,s.`nom_sous`,s.`pnom_sous` FROM `policew` as d,`souscripteurw` as s WHERE s.`cod_sous`=d.`cod_sous`  AND d.`cod_prod`='3' AND s.`id_user` in (select id_user from utilisateurs where agence='$agence')   AND $condition ORDER BY d.`cod_pol` DESC");
    $rqtc->execute();
    $nbe = $rqtc->rowCount();
    $nbpage=ceil($nbe/7);
//Pointeur de page
    $part=$page*7;
//requete � suivre
    $rqt=$bdd->prepare("SELECT d.`cod_pol`,d.`sequence`,d.`dat_eff`,d.`dat_ech`,d.`pn`,d.`pt`,d.`etat`,s.`nom_sous`,s.`pnom_sous` FROM `policew` as d,`souscripteurw` as s WHERE s.`cod_sous`=d.`cod_sous` AND d.`cod_prod`='3' AND s.`id_user` in (select id_user from utilisateurs where agence='$agence')   AND $condition ORDER BY d.`cod_pol` DESC LIMIT $part ,7");
    $rqt->execute();

}else{
//Calcule du nombre de page 
    $rqtc=$bdd->prepare("SELECT d.`cod_pol`,d.`sequence`,d.`dat_eff`,d.`dat_ech`,d.`pn`,d.`pt`,d.`etat`,s.`nom_sous`,s.`pnom_sous` FROM `policew` as d,`souscripteurw` as s WHERE s.`cod_sous`=d.`cod_sous` AND d.`cod_prod`='3' AND s.`id_user` in (select id_user from utilisateurs where agence='$agence')   ORDER BY d.`cod_pol` DESC");
    $rqtc->execute();
    $nbe = $rqtc->rowCount();
    $nbpage=ceil($nbe/7);
//Pointeur de page
    $part=$page*7;
//requete � suivre
    $rqt=$bdd->prepare("SELECT d.`cod_pol`,d.`sequence`,d.`dat_eff`,d.`dat_ech`,d.`pn`,d.`pt`,d.`etat`,s.`nom_sous`,s.`pnom_sous` FROM `policew` as d,`souscripteurw` as s WHERE s.`cod_sous`=d.`cod_sous` AND d.`cod_prod`='3' AND s.`id_user` in (select id_user from utilisateurs where agence='$agence')   ORDER BY d.`cod_pol` DESC LIMIT $part ,7");
    $rqt->execute();
    $nb = $rqt->execute();
}
?>

<div id="content-header">
    <div id="breadcrumb"> <a class="tip-bottom"><i class="icon-home"></i> Produit</a><a class="current">Assurance-Credit-Immobilier</a> </div>
</div>
<div class="widget-box">
    <ul class="quick-actions">
        <li class="bg_lo"> <a onClick="Menu1('macc','../dash.php')"> <i class="icon-home"></i>Acceuil </a> </li>
         <li class="bg_ly"> <a onClick="tarif('prod','vsimulationimmo.php')"> <i class="icon-dashboard"></i> Simulation</a> </li>
	    <li class="bg_ls"> <a onClick="ndev()"> <i class="icon-folder-open"></i>Nouveau-Devis</a> </li>
        <li class="bg_lb"> <a onClick="Menu1('prod','devisIMMO.php')"> <i class="icon-folder-open"></i>Visualiser-Devis</a></li>
        <li class="bg_lg"> <a onClick="Menu1('prod','poldevisIMMO.php')"> <i class="icon-folder-open"></i>Visualiser-Contrats</a> </li>
       
    </ul>
</div>
<div class="widget-box">
    <div class="widget-title">
        <div><input type="text" id="nsouspade"   class="span4" placeholder="Recherche"/>
            &nbsp;&nbsp;

            <select   id="critere"  >
                <option value="1">N Adhesion</option>
                <option value="2">Nom de souscripteur</option>


            </select>
            &nbsp;&nbsp;  &nbsp;&nbsp;  &nbsp;&nbsp;
            <input  type="button" class="btn btn-success" onClick="frechpade()" value="Rechercher" />
            &nbsp;&nbsp;  &nbsp;&nbsp;  &nbsp;&nbsp;
            <?php if ($rech!=''){?>
                <input  type="button" class="btn btn-danger" onClick="frechpade2()" value="Annuler"  />
            <?php } else {?>
                <input  type="button" class="btn btn-danger" onClick="frechpade2()" value="Annuler" disabled="disabled" />
            <?php }?>
        </div>
        &nbsp;&nbsp;  &nbsp;&nbsp;  &nbsp;&nbsp;
        &nbsp;&nbsp;  &nbsp;&nbsp;  &nbsp;&nbsp;
    </div>
    <div class="widget-content nopadding">
        <table class="table table-bordered data-table">
            <thead>
            <tr>
                <th></th>
                <th>N Adhesion</th>
                <th>Nom/Prenom</th>
                <th>D-Effet</th>
                <th>D-Echeance</th>
                <th>P-Nette</th>
                <th>P-Totale</th>
                <th>Etat</th>
                <th>Operations</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $i = 0;
            while ($row_res=$rqt->fetch()){  ?>
                <!-- Ici les lignes du tableau zone-->
                <tr class="gradeX">
                    <?php if($row_res['etat']==0){ ?>
                        <td><a title="Police-Validee"><img  src="img/icons/icon_2.png"/></a></td>
                    <?php }
                    if($row_res['etat']==2){
                        ?>
                        <td><a title="Police-Ristournee"><img  src="img/icons/icon_3.png"/></a></td>
                    <?php }
                    if($row_res['etat']==3){
                        ?>
                        <td><a title="Police-Annulee"><img  src="img/icons/icon_3.png"/></a></td>
                    <?php } ?>
                    <td><?php echo $row_res['sequence']; ?></td>
                    <td><?php  echo $row_res['nom_sous']."  ".$row_res['pnom_sous']; ?></td>
                    <td><?php  echo date("d/m/Y",strtotime($row_res['dat_eff'])); ?></td>
                    <td><?php  echo date("d/m/Y",strtotime($row_res['dat_ech'])); ?></td>
                    <td><?php  echo number_format($row_res['pn'], 2, ',', ' ')." DZD"; ?></td>
                    <td><?php  echo number_format($row_res['pt'], 2, ',', ' ')." DZD"; ?></td>
                    <?php if($row_res['etat']==0){ ?>
                        <td><?php echo "Police-Validee"?></td>
                    <?php }
                    if($row_res['etat']==2){
                        ?>
                        <td><?php echo "Police-Ristournee"?></td>
                    <?php }
                    if($row_res['etat']==3){
                        ?>
                        <td><?php echo"Police-Annulee";?></td>
                    <?php } ?>
                    <td>&nbsp;

                        <a href="sortie/contrat7/<?php echo crypte($row_res['cod_pol']) ?>" onClick="window.open(this.href, 'Devis', 'height=600, width=800, toolbar=no, menubar=no, scrollbars=no, resizable=no, location=no, directories=no, status=no'); return(false);" title="Imprimer"><i CLASS="icon-print icon-2x" style="color:#0e90d2"/></a>
                        <?php if($row_res['etat']==0){ ?>
                            &nbsp;&nbsp;&nbsp;
                            <a onClick="vav1('<?php echo $row_res['cod_pol'];?>','<?php echo $row_res['dat_eff'];?>','poldevisIMMO.php')" title="Retrait"><i CLASS="icon-edit icon-2x " style="color:#BA1E20" /></a>
                        <?php } ?>
                        &nbsp;&nbsp;&nbsp;
                        <a onClick="lav('<?php echo $row_res['cod_pol'];?>','avpolimmo.php')" title="Liste-Retraits"><i CLASS="icon-list-alt icon-2x" style="color:black"/></a>
                        &nbsp;&nbsp;&nbsp;
                         <a onClick="Export3('<?php echo $row_res['cod_pol'];?>')" title="Exporter-Excel"><i CLASS="icon-download icon-2x" style="color:green"/></a>
                        <a href="sortie/AvenantR-P/<?php echo crypte($row_res['cod_pol']) ?>" onClick="window.open(this.href, 'Avenants', 'height=600, width=800, toolbar=no, menubar=no, scrollbars=no, resizable=no, location=no, directories=no, status=no'); return(false);" title="Ressortie-De-Prime"><i CLASS="icon-list icon-2x" style="color:purple"/></a>

                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <div class="widget-title" align="center">
        <h5>Visualisation-Contrats-ADE consommation</h5>
        <a href="javascript:;" title="Premiere page" onClick="fpagepade('0','<?php echo $nbpage; ?>')"><img  src="img/icons/fprec.png"/></a>
        <a href="javascript:;" title="Precedent" onClick="fpagepade('<?php echo $page-1; ?>','<?php echo $nbpage; ?>')"><img  src="img/icons/prec.png"/></a>
        <?php echo $page+1; ?>/<?php echo $nbpage; ?>
        <a href="javascript:;" title="Suivant" onClick="fpagepade('<?php echo $page+1; ?>','<?php echo $nbpage; ?>')"><img  src="img/icons/suiv.png"/></a>
        <a href="javascript:;" title="Derniere page" onClick="fpagepade('<?php echo $nbpage-1; ?>','<?php echo $nbpage; ?>')"><img  src="img/icons/fsuiv.png"/></a>
    </div>
</div>
<script language="JavaScript">
    function frechpade(){
        var rech=document.getElementById("nsouspade").value;

        var crit=document.getElementById("critere").value;
        $("#content").load('produit/poldevisIMMO.php?rech='+rech+'&crit='+crit);
    }
    function frechpade2(){
        var rech='';
        var crit=2;

        $("#content").load('produit/poldevisIMMO.php?rech='+rech+'&crit='+crit);
    }
    function fpagepade(page,nbpage){
        if(page >=0){
            if(page == nbpage){
                swal("Vous etes a la derniere page!");
            }else{
                var rech='<?php echo $rech;?>';
                var crit='<?php echo $crit;?>';
                if(rech!='')
                    $("#content").load('produit/poldevisIMMO.php?page='+page+'&rech='+rech+'&crit='+crit);
                else
                    $("#content").load('produit/poldevisIMMO.php?page='+page);
            }
        }else{swal("Vous etes en premiere page !");}
    }

    function ndev(){
        $("#content").load('produit/ade/immo/adherent.php');
    }
      function Export3(code)
    {
        window.open('excel/Excel-line/' + code+'/3' ,"Generation Excel","menubar=no, status=no, scrollbars=yes, menubar=no, width=600, height=230");

    }





</script>		