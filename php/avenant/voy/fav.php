<?php session_start();
require_once("../../../../../data/conn4.php");
if ($_SESSION['login']){
}
else {
header("Location:login.php");
}
$id_user = $_SESSION['id_user'];
$datesys=date("Y-m-d");

if (isset($_REQUEST['code']) && isset($_REQUEST['page'])) {
	$codepol = $_REQUEST['code'];
	$page = $_REQUEST['page'];
	$pagem= $_REQUEST['pm'];
//On recupere le nombre de jour de la periode
	$rqtp = $bdd->prepare("SELECT j.min_jour as jour, p.cod_sous as csous,p.cod_prod as prod, p.ndat_eff,p.ndat_ech,p.cod_formul,p.cod_opt FROM `periode` as j,`policew` as p WHERE p.`cod_per`=j.`cod_per` AND p.`cod_pol`='$codepol'");
	$rqtp->execute();
	while ($row_res = $rqtp->fetch()) {
		$jour = $row_res['jour'];
		$dure=dure_en_jour($row_res['ndat_eff'],$row_res['ndat_ech']);
		$csous = $row_res['csous'];
		$cprod=$row_res['prod'];
		$dated=$row_res['ndat_eff'];
		$datef=$row_res['ndat_ech'];
		$cod_formul=$row_res['cod_formul'];
		$cod_opt=$row_res['cod_opt'];
	}
	$rqtsous=$bdd->prepare ("SELECT * FROM souscripteurw where cod_sous='$csous'");
	$rqtsous->execute();
	while($rowsous=$rqtsous->fetch())
	{
		$noma=$rowsous['nom_sous'];
		$pnoma=$rowsous['pnom_sous'];
		$maila=$rowsous['mail_sous'];
		$tela=$rowsous['tel_sous'];
		$adra=$rowsous['adr_sous'];
		$passporta=$rowsous['passport'];
		$datpassa=$rowsous['datedpass'];

	}
	$rqtassur=$bdd->prepare ("SELECT * FROM souscripteurw where cod_par='$csous'");
	$rqtassur->execute();
	$nbe = $rqtassur->rowCount();

	while($rowassu=$rqtassur->fetch())
	{
		$nom=$rowassu['nom_sous'];
		$pnom=$rowassu['pnom_sous'];
		$mail=$rowassu['mail_sous'];
		$tel=$rowassu['tel_sous'];
		$adr=$rowassu['adr_sous'];
		$passport=$rowassu['passport'];
		$datpass=$rowassu['datedpass'];
	}

	$rqtav=$bdd->prepare("select count(*) as nb_av from avenantw where cod_pol='$codepol'");
	$rqtav->execute();
	$nbav=0;
	while ($rowas=$rqtav->fetch())
	{
		$nbav= $rowas['nb_av'];

	}

}

?>


<div id="content-header" >
	<div id="breadcrumb"> <a class="tip-bottom"><i class="icon-home"></i> Voyage</a><a class="current">Avenant</a> </div>
</div>
<div class="row-fluid">
	<div class="span12">
		<div class="widget-box">
			<div id="breadcrumb"> <a class="current"><i></i>Avenants</a><a>Type-Avenant</a>
				<div class="widget-content nopadding">
					<form class="form-horizontal">
						<div id="step-holder">
							<div class="controls">
								<select   id="tav"  >
									<option value="">--  Type Avenants</option>
									<?php
									 switch ($cod_formul)
									 {

										 case 2:
										 {?>
											 <option value='1'>Modification de date</option>
											 <?php if ($cod_opt<>30 && $cod_opt<>31) {?>
		                                     					<option value='2'>Changement de destination </option>
												 <?php }?>
											 <!--<option value='4'>Prorogation de delais</option> -->
											 <option value='3'>Precision</option>
											<option value='5'>Annulation avec ristourne</option>
									<?php if ($nbav==0){?>
											<option value='4'>Annulation Sans ristourne</option>
										<?php }?>

										<?php  break;}

										 case 3:
										 {?>
											 <option value='1'>Modification de date</option>
									<?php if ($cod_opt<>30 && $cod_opt<>31) {?>
											 <option value='2'>Changement de destination </option>
										 <?php }?>
                                           <!-- <option value='4'>Prorogation de delais</option> -->
											 <option value='3'>Precision</option>
											 <option value='5'>Annulation avec ristourne</option>
									<?php if ($nbav==0){?>
											 <option value='4'>Annulation Sans ristourne</option>
										 <?php }?>
										 <?php  break;}
										 case 4:
										 {?>
											 <option value='1'>Modification de date</option>
											 <?php if ($cod_opt<>30 && $cod_opt<>31) {?>
											 	<option value='2'>Changement de destination </option>
										 <?php }?>
                                          						 <!-- <option value='4'>Prorogation de delais</option> -->
											 <option value='3'>Precision</option>
											 <option value='5'>Annulation avec ristourne</option>
											 <?php if ($nbav==0){?>
											 <option value='4'>Annulation Sans ristourne</option>
										 <?php }?>
										 <?php  break; }

										 case 5:
										 {?>
											 <option value='1'>Modification de date</option>
											 <?php if ($cod_opt<>30 && $cod_opt<>31) {?>
											 	<option value='2'>Changement de destination </option>
										 <?php }?>
                                        						   <!-- <option value='4'>Prorogation de delais</option>-->
											 <option value='3'>Precision</option>
											 <option value='5'>Annulation avec ristourne</option>
											 <?php if ($nbav==0){?>
											 <option value='4'>Annulation Sans ristourne</option>
										 <?php }?>
											<!-- <option value='6'>Adjonction</option>-->

										 <?php  break;}
									 }
									?>
								</select>
							</div>
						</div>

						<div class="form-actions" align="right">
							<input  type="button" id="btnav" class="btn btn-success" onClick="choix_avenant('<?php echo $codepol; ?>','<?php echo $page; ?>','<?php echo $pagem; ?>')" value="Suivant" />
							<input  type="button" class="btn btn-danger"  onClick="Menu1('prod','<?php echo $pagem; ?>')" value="Annuler" />
						</div>

				</div>
			</div>
		</div>

	</div>
</div>
<script language="JavaScript">
	function choix_avenant(cod_police,page,pagem)
	{
	var type_av=document.getElementById('tav').value;

		var cod_formul='<?php echo $cod_formul;?>';
		var dateff='<?php echo $dated;?>';
			var datech='<?php echo $datef;?>';
		var av_sans_r='50';
		var av_avec_r='30';
		var av_prec='70';
		if (window.XMLHttpRequest) {
			xhr = new XMLHttpRequest();
		}
		else if (window.ActiveXObject)
		{
			xhr = new ActiveXObject("Microsoft.XMLHTTP");
		}

		if(type_av==1)
		{
			$("#content").load('php/avenant/voy/report_date.php?page='+page+"&cod_pol="+cod_police+"&formul="+cod_formul+"&tav="+type_av+'&pm='+pagem);

		}
		if(type_av==2)
		{
			$("#content").load('php/avenant/voy/destination.php?page='+page+"&cod_pol="+cod_police+"&formul="+cod_formul+"&tav="+type_av+'&pm='+pagem);

		}
		if(type_av==3)
		{
			//suuprimer depuis la talble assur les lignes de cette police dans la condition est av=0
			xhr.open("GET", "php/avenant/voy/assurprecision.php?code=" + cod_police , false);
			xhr.send(null);
			$("#content").load('php/avenant/voy/precision.php?page='+page+"&cod_pol="+cod_police+"&formul="+cod_formul+"&tav="+type_av+'&pagepres=0'+'&pm='+pagem);

		}
		if(type_av==4)//avenant sans ristourne
		{
			document.getElementById("btnav").disabled = true;
			xhr.open("GET", "php/avenant/voy/validationav.php?code=" + cod_police + "&date1=" + dateff + "&date2=" + datech + "&av=" + av_sans_r, false);
			xhr.send(null);
			$("#content").load('produit/'+page+'?code='+cod_police);
			//Menu1('prod', page);
		}
		if(type_av==5)//avenant Avec ristourne
		{
			document.getElementById("btnav").disabled = true;
			//$("#content").load("php/avenant/voy/mpaiement.php?code="+cod_police+"&page="+page+"&av="+av_avec_r+"&datdebut="+dateff+"&datfin="+datech);
			xhr.open("GET", "php/avenant/voy/validationav.php?code=" + cod_police + "&date1=" + dateff + "&date2=" + datech + "&av=" + av_avec_r, false);
			xhr.send(null);
			$("#content").load('produit/'+page+'?code='+cod_police);
			//Menu1('prod', page);
		}
		if(type_av==6)
		{
			$("#content").load('php/avenant/voy/adjonction.php?page='+page+"&cod_pol="+cod_police+"&formul="+cod_formul+"&tav="+type_av);

		}





	}
</script>