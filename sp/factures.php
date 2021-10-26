<?php
session_start();
require_once("../../../data/conn4.php");
//$errone = false;
//Recuperation de la page demandee
if (isset($_REQUEST['page'])) {
    $page = $_REQUEST['page'];
}else{$page=0;}
$id_user = $_SESSION['id_user'];


$rqtag = $bdd->prepare("SELECT id_user,agence FROM `utilisateurs`  WHERE  type_user='dr' ORDER BY `type_user`");
$rqtag->execute();
$rech="";
if (isset($_REQUEST['rech'])) {
    $rech = $_REQUEST['rech'];
}
if($rech!=""){
//Calcule du nombre de page
    $rqtc=$bdd->prepare("select f.`id_facture`, f.`sequence`, f.`dat_facture`, f.`dat_deb`, f.`dat_fin`, f.`mtt_net`, f.`taux_tva`, f.`TVA`, f.`mtt_ttc`, f.`id_user`, f.`cod_agence`, f.`type_facture`,u.agence from facture f, utilisateurs u where f.id_user=u.id_user and  f.id_user ='$rech' ");
    $rqtc->execute();
    $nbe = $rqtc->rowCount();
    $nbpage=ceil($nbe/5);
//Pointeur de page
    $part=$page*5;
//requete à suivre
    $rqt=$bdd->prepare("select f.`id_facture`, f.`sequence`, f.`dat_facture`, f.`dat_deb`, f.`dat_fin`, f.`mtt_net`, f.`taux_tva`, f.`TVA`, f.`mtt_ttc`, f.`id_user`, f.`cod_agence`, CASE f.`type_facture` WHEN 0 THEN 'Direct' else'Via-courtier' end typ_facture,u.agence from facture f, utilisateurs u where f.id_user=u.id_user and  f.id_user='$rech' ORDER BY f.`dat_facture` DESC LIMIT $part ,5");
    $rqt->execute();

}else{

//Calcule du nombre de page
    $rqtc=$bdd->prepare("select f.`id_facture`, f.`sequence`, f.`dat_facture`, f.`dat_deb`, f.`dat_fin`, f.`mtt_net`, f.`taux_tva`, f.`TVA`, f.`mtt_ttc`, f.`id_user`, f.`cod_agence`, f.`type_facture`,u.agence from facture f, utilisateurs u where f.id_user=u.id_user  ");
    $rqtc->execute();
    $nbe = $rqtc->rowCount();
    $nbpage=ceil($nbe/5);
//Pointeur de page
    $part=$page*5;
//requete à suivre
    $rqt=$bdd->prepare("select f.`id_facture`, f.`sequence`, f.`dat_facture`, f.`dat_deb`, f.`dat_fin`, f.`mtt_net`, f.`taux_tva`, f.`TVA`, f.`mtt_ttc`, f.`id_user`, f.`cod_agence`, CASE f.`type_facture` WHEN 0 THEN 'Direct' else'Via-courtier' end typ_facture,u.agence from facture f, utilisateurs u where f.id_user=u.id_user  ORDER BY f.`dat_facture` DESC LIMIT $part ,5");
    $rqt->execute();
    $nb = $rqt->execute();
}
?>

<div id="content-header">
    <div id="breadcrumb"> <a class="tip-bottom"><i class="icon-home"></i> Produit</a><a class="current">Factures-Commissions</a></div>
</div>
<div class="widget-box">
    <ul class="quick-actions">
        <li class="bg_lo"> <a onClick="sMenu1('macc','../sdash.php')"> <i class="icon-home"></i>Acceuil </a> </li>


        <li class="bg_lg"> <a onClick="sMenu1('com','gfacture.php')"> <i class="icon-plus"></i>G&eacute;n&eacute;rer une facture</a> </li>

        <li class="bg_ls"> <a onClick="sMenu1('com','factures.php')"> <i class="icon-folder-open"></i>Factures de commissions</a> </li>
    </ul>
</div>
<div class="widget-box">
    <div class="widget-title">

        <div class="controls">
            <span> Directions R&eacute;gionales:</span>
            <select id="nsousvcpl"  onchange="frechvcpld()">
                <option value="">Tout les DR</option>
                <?php while ($row_res=$rqtag->fetch()){  if ($rech== $row_res['id_user']){ ?>
                    <option value="<?php  echo $row_res['id_user']; ?>" selected><?php  echo $row_res['agence']; ?></option>
                    <?php }else {?>
                    <option value="<?php  echo $row_res['id_user']; ?>" ><?php  echo $row_res['agence']; ?></option>
                <?php }
                } ?>
            </select>
        </div>

    </div>
    <div class="widget-content nopadding">
        <table class="table table-bordered data-table">
            <thead>
            <tr>
                <th>DR</th>
                <th>N-Facture</th>
                <th>Date</th>
                <th>Date-debut</th>
                <th>Date-fin</th>
                <th>Montant-HT</th>
                <th>TVA</th>
                <th>Montant-TTC</th>
                <th>Mode-facturation</th>
                <th>Operation</th>

            </tr>
            </thead>
            <tbody>
            <?php
            $i = 0;
            while ($row_res=$rqt->fetch()){  ?>
                <!-- Ici les lignes du tableau zone-->
                <tr class="gradeX">
                    <td><?php  echo $row_res['agence']; ?></td>
                    <td><?php  echo $row_res['sequence']; ?></td>
                    <td><?php  echo $row_res['dat_facture']; ?></td>
                    <td><?php  echo $row_res['dat_deb']; ?></td>
                    <td><?php  echo $row_res['dat_fin']; ?></td>
                    <td><?php  echo $row_res['mtt_net']; ?></td>
                    <td><?php  echo $row_res['TVA']; ?></td>
                    <td><?php  echo $row_res['mtt_ttc']; ?></td>
                    <td><?php  echo $row_res['typ_facture']; ?></td>
                    <td>&nbsp;

                        <a href="sortie/facture/<?php echo crypte($row_res['id_facture']) ?>" onClick="window.open(this.href, 'Fcommission', 'height=600, width=800, toolbar=no, menubar=no, scrollbars=no, resizable=no, location=no, directories=no, status=no'); return(false);" title="Imprimer"><i CLASS="icon-print icon-2x" style="color:#0e90d2"/></a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <div class="widget-title" align="center">
        <h5>Visualiser- les factures de commissions</h5>
        <a href="javascript:;" title="Premiere page" onClick="fpagegav('0','<?php echo $nbpage; ?>')"><img  src="img/icons/fprec.png"/></a>
        <a href="javascript:;" title="Precedent" onClick="fpagegav('<?php echo $page-1; ?>','<?php echo $nbpage; ?>')"><img  src="img/icons/prec.png"/></a>
        <?php echo $page; ?>/<?php echo $nbpage; ?>
        <a href="javascript:;" title="Suivant" onClick="fpagegav('<?php echo $page+1; ?>','<?php echo $nbpage; ?>')"><img  src="img/icons/suiv.png"/></a>
        <a href="javascript:;" title="Derniere page" onClick="fpagegav('<?php echo $nbpage-1; ?>','<?php echo $nbpage; ?>')"><img  src="img/icons/fsuiv.png"/></a>
    </div>
</div>
<script language="JavaScript">
    function frechvcpld(){

        var rech=document.getElementById("nsousvcpl").value;
        $("#content").load('sp/factures.php?rech='+rech);
    }
    function fpagegav(page,nbpage){
        if(page >=0){
            if(page == nbpage){
                swal("Information !","Vous ete a la derniere page!","info");
            }else{$("#content").load('sp/factures.php?page='+page);}
        }else{swal("Information !","Vous ete en premiere page !","info");}
    }
</script>