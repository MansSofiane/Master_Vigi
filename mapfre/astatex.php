<?php 
session_start();
$id_user=$_SESSION['id_user'];
require_once("../../../data/conn4.php");
$token1 = generer_token('stat');
if ($_SESSION['login']){
}else {
header("Location:index.html");
}
if ( isset($_REQUEST['tok']) ){
    $token = $_REQUEST['tok'];
}

$dat_syst=date('Y-m-d');
$annee=  date('Y', strtotime($dat_syst));
?>
<div id="content-header">
<div id="breadcrumb"> <a class="tip-bottom"><i class="icon-signal"></i> Statistiques</a><a class="current">Excel</a></div>
</div>   
  <div class="row-fluid">  
    <div class="span12">
      <div class="widget-box">
        <div class="widget-content nopadding">
          <form class="form-horizontal">
		   <div class="control-group">
			   <div class="controls">
                   <label class="control-label" ><FONT size="4">Excercice <?php echo  date('Y', strtotime($dat_syst));?>:</FONT></label>
				 <div class="controls">
				   <select id="sel">
					   <option value="0">Tout</option>
					   <option value="1">Janvier</option>
					   <option value="2">F&eacute;vrier</option>
					   <option value="3">Mars</option>
					   <option value="4">Avril</option>
					   <option value="5">Mai</option>
					   <option value="6">Juin</option>
					   <option value="7">Juillet</option>
					   <option value="8">Ao&ucirc;t</option>
					   <option value="9">Septembre</option>
					   <option value="10">Octobre</option>
					   <option value="11">Novembre</option>
					   <option value="12">D&eacute;cembre</option>
				   </select>

			   </div>

			   </div>

				<div class="form-actions" align="right">
				     <input  type="button" class="btn btn-success icon-2x"  onClick="aprod('1')" value="Editer" />
				</div>

          </div>

          </form>
        </div>
      </div>
	 </div>

<script language="JavaScript">initdate();</script>
<script language="JavaScript">
	function verifdatestat(dd)
	{
		v1=true;
		var regex = /^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/;
		var test = regex.test(dd.value);
		if(!test){
			v1=false;
			alert("Format date incorrect! jj/mm/aaaa");dd.value="";
		}
		return v1;
	}
function dfrtoenstat(date1)
	{
		var split_date=date1.split('/');
		var new_d=new Date(split_date[2], split_date[1]*1 - 1, split_date[0]*1);
		var new_day = new_d.getDate();
		new_day = ((new_day < 10) ? '0' : '') + new_day;
		var new_month = new_d.getMonth() + 1;
		new_month = ((new_month < 10) ? '0' : '') + new_month;
		var new_year = new_d.getYear();
		new_year = ((new_year < 200) ? 1900 : 0) + new_year;
		var new_date_text = new_year + '-' + new_month + '-' + new_day;
		return new_date_text;
	}
function aprod(code) {

	var annee = '<?php echo  $annee;?>';

  var mois = document.getElementById("sel").value;


		window.open('excel/Excel-M/' + annee + '/' + mois ,"Export Excel","menubar=no, status=no, scrollbars=yes, menubar=no, width=600, height=230");

				
	}

</script>