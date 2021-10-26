<?php session_start();
require_once("../../../../data/conn4.php");
if ($_SESSION['login']){
$id_user=$_SESSION['id_user'];
}
else {
header("Location:login.php");
}
$tokwar4 = generer_token('devwar4');
if ( isset($_REQUEST['sous']) &&  isset($_REQUEST['opt']) &&  isset($_REQUEST['bool'])){
    $codsous= $_REQUEST['sous'];
    $opt= $_REQUEST['opt'];
	$bool= $_REQUEST['bool'];
    $vari=0;$rp=0;$codsous1=0;$prime=0;$codtar=0;
//on verifie l'assure
$rqtv=$bdd->prepare("SELECT `rp_sous` FROM `souscripteurw`  WHERE `cod_sous`='$codsous'");
$rqtv->execute();

while ($row=$rqtv->fetch()){
$rp=$row['rp_sous'];
}

//Requete pour le tarif sur souscripteur qui est l'assure
if($rp==1){	
$rqtu=$bdd->prepare("SELECT t.`cod_tar`,t.`pe`,d.`mtt_dt`,c.`mtt_cpl` FROM `tarif` as t, `souscripteurw` as s,`dtimbre` as d,`cpolice` as c WHERE t.`cod_prod`='5' AND t.`cod_seg`='5' AND t.`cod_cls`='1' AND t.`cod_zone`='1' AND t.`cod_formul`='1' AND t.`cod_dt`=d.`cod_dt` AND t.`cod_cpl`=c.`cod_cpl` AND t.`cod_opt`='$opt' AND t.`cod_per`='20' AND t.`agemin`<=s.`age` AND t.`agemax`>=s.`age` AND s.`cod_sous`='$codsous'");
$rqtu->execute();

while ($row_pu=$rqtu->fetch()){
$codtar=$row_pu['cod_tar'];
$prime=$row_pu['pe']+$row_pu['mtt_dt']+$row_pu['mtt_cpl'];
$vari++;
}	
}

//Requete pour le tarif sur souscripteur qui n'est pas l'assure
if($rp==2){	
// on recupere le code de l'assure
$rqtv1=$bdd->prepare("SELECT `cod_sous` FROM `souscripteurw`  WHERE `cod_par`='$codsous'");
$rqtv1->execute();
$rp=0;
while ($row=$rqtv1->fetch()){
$codsous1=$row['cod_sous'];
}
//**************

$rqtu=$bdd->prepare("SELECT t.`cod_tar`,t.`pe`,d.`mtt_dt`,c.`mtt_cpl` FROM `tarif` as t, `souscripteurw` as s,`dtimbre` as d,`cpolice` as c WHERE t.`cod_prod`='5' AND t.`cod_seg`='5' AND t.`cod_cls`='1' AND t.`cod_zone`='1' AND t.`cod_formul`='1' AND t.`cod_dt`=d.`cod_dt` AND t.`cod_cpl`=c.`cod_cpl` AND t.`cod_opt`='$opt' AND t.`cod_per`='20' AND t.`agemin`<=s.`age` AND t.`agemax`>=s.`age` AND s.`cod_sous`='$codsous1'");
$rqtu->execute();

while ($row_pu=$rqtu->fetch()){
$codtar=$row_pu['cod_tar'];
$prime=$row_pu['pe']+$row_pu['mtt_dt']+$row_pu['mtt_cpl'];
$vari++;
}	
}	
}
if($vari>0){
?>
<div id="content-header">
      <div id="breadcrumb"> <a class="tip-bottom"><i class="icon-home"></i> Produit</a> <a>Assurance-Warda</a> <a class="current">Nouveau-Devis</a> </div>
  </div>
  <div class="row-fluid">  
    <div class="span12">
      <div class="widget-box">
      <div id="breadcrumb"> <a><i></i>Souscripteur</a><a>Assure</a><a>Capital</a><a>Selection-Medical</a><a class="current">Validation</a></div>
        <div class="widget-content nopadding">
          <form class="form-horizontal">
			  <div class="control-group"> 
              <label class="control-label">Date-Effet:</label>
   
				 <div class="controls" data-date-format="dd/mm/yyyy">
				<input type="text" class="date-pick dp-applied"  id="deffwar" placeholder="Format date JJ/MM/AAAA"/> 
				 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				  <input type="text" class="span4" value="Prime a payer: <?php echo number_format($prime, 2, ',', ' '); ?> DA" disabled="disabled" />
              </div>
			  </div>	
            <div class="form-actions" align="right">
			  <input  type="button" class="btn btn-success" onClick="instdevwar('<?php echo $codsous; ?>','<?php echo $prime; ?>','<?php echo $codtar; ?>','<?php echo $vari; ?>','<?php echo $bool; ?>','<?php echo $tokwar4; ?>')" value="Enregistrer" />
			  <input  type="button" class="btn btn-danger"  onClick="Menu1('prod','assward.php')" value="Annuler" />
            </div>
          </form>
        </div>
      </div>
	 </div>
 
</div>
<?php }else{ ?>
<div id="content-header">
<div id="breadcrumb"> <a class="tip-bottom"><i class="icon-home"></i> Produit</a> <a>Assurance-Warda</a> <a class="current">Nouveau-Devis</a> </div>
</div>
<div id="breadcrumb"> <a><i></i><a class="current">Cas Non Assure !!</a></div>
<div class="form-actions" align="right">
<input  type="button" class="btn btn-danger"  onClick="Menu1('prod','assward.php')" value="Retour" />
</div>
<?php } ?>

<script language="JavaScript">initdate();</script>
<script language="JavaScript">
	function verifdate_war(dd)
	{
		v1=true;
		var regex = /^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/;
		var test = regex.test(dd.value);
		if(!test){
			v1=false;
			swal("Erreur !","Format date incorrect! jj/mm/aaaa", "error");dd.value="";

		}
		return v1;
	}

	function dfrtoen_war(date1)
	{
		var split_date=date1.split('/');
		var new_d=new Date(split_date[2], split_date[1]*1 - 1, split_date[0]*1);
		var new_day = new_d.getDate();
		new_day = ((new_day < 10) ? '0' : '') + new_day; // ajoute un z�ro devant pour la forme
		var new_month = new_d.getMonth() + 1;
		new_month = ((new_month < 10) ? '0' : '') + new_month; // ajoute un z�ro devant pour la forme
		var new_year = new_d.getYear();
		new_year = ((new_year < 200) ? 1900 : 0) + new_year; // necessaire car IE et FF retourne pas la meme chose
		var new_date_text = new_year + '-' + new_month + '-' + new_day;
		return new_date_text;
	}
	function calage_war(dd)
	{
		var bb1=document.getElementById("datsys");
		var aa=new Date(dfrtoen_war(dd.value));
		var bb=new Date(bb1.value);
		var sec1=bb.getTime();
		var sec2=aa.getTime();
		var sec=(sec1-sec2)/(365.24*24*3600*1000);
		age=Math.floor(sec);
		return age;

	}

	function compdat_war(dd)
	{
		var rcomp=false;
		var bb1=document.getElementById("datsys");
		var aa=new Date(dfrtoen_war(dd.value));
		var bb=new Date(bb1.value);
		var sec1=bb.getTime();
		var sec2=aa.getTime();
		if(sec2>=sec1){rcomp=true;}
		return rcomp;

	}

	function addDays_war(dd,xx) {
		// Date plus plus quelques jours
		var split_date = dd.split('/');
		var new_date = new Date(split_date[2], split_date[1]*1 - 1, split_date[0]*1 + parseInt(xx)-1);
		var dd= new Date(split_date[2], split_date[1]*1 - 1, split_date[0]*1);
		var new_day = new_date.getDate();
		new_day = ((new_day < 10) ? '0' : '') + new_day; // ajoute un z�ro devant pour la forme
		var new_month = new_date.getMonth() + 1;
		new_month = ((new_month < 10) ? '0' : '') + new_month; // ajoute un z�ro devant pour la forme
		var new_year = new_date.getYear();
		new_year = ((new_year < 200) ? 1900 : 0) + new_year; // necessaire car IE et FF retourne pas la meme chose
		var new_date_text = new_day + '/' + new_month + '/' + new_year;
		return new_date_text;
	}
function instdevwar(codsous,prime,tar,vari,bool,tok){

var dateff=document.getElementById("deffwar");
var datech=null;
	   if (window.XMLHttpRequest) { 
        xhr = new XMLHttpRequest();
     }
     else if (window.ActiveXObject) 
     {
        xhr = new ActiveXObject("Microsoft.XMLHTTP");
     }

	if(vari==0){swal("Erreur !","Cas Non-Assure !", "error");}else{
	  if(verifdate_war(dateff)){
	 
          	if(compdat_war(dateff)){
			   datech=dfrtoen_war(addDays_war(dateff.value,365));
			   dateff=dfrtoen_war(dateff.value)
			   if(bool==0){
			   xhr.open("GET", "produit/warda/ndev.php?tar="+tar+"&pt="+prime+"&d1="+dateff+"&d2="+datech+"&sous="+codsous+"&bool="+bool+"&tok="+tok, false);
               xhr.send(null);
	           // alert(xhr.responseText);
				   swal("F\351licitation !","Devis Enregistre !", "success");
			   Menu1('prod','assward.php');
			  
			   }else{
			   //alert("Devis soumis au traitement de la DG-AGLIC");
			   xhr.open("GET", "produit/warda/ndev.php?tar="+tar+"&pt="+prime+"&d1="+dateff+"&d2="+datech+"&sous="+codsous+"&bool="+bool+"&tok="+tok, false);
               xhr.send(null);
				   swal("F\351licitation !","Devis Enregistre !", "success");
			   Menu1('prod','assward.php');
	                 }
			   
	        }else{
				swal("Alerte !","la date d'effet doit pas etre anterieure !", "warning");

	             }
				 }else{swal("Erreur !","Cas Non-Assure !", "error");}
				 
	
	}
	
	}	
			
</script>	