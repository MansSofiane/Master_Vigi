<?php session_start();

require_once("../../../../../data/conn4.php");
if ($_SESSION['login'])
{}

else
{
    header("Location:login.php");
}
$id_user = $_SESSION['id_user'];
$datesys=date("Y-m-d");

if (isset($_REQUEST['code']) && isset($_REQUEST['page'])) {
    $codedev = $_REQUEST['code'];$page = $_REQUEST['page'];
}

//Calcule du nombre de page 
$rqtp=$bdd->prepare("SELECT * FROM `mpay` WHERE 1 ");
$rqtp->execute();
$rqt=$bdd->prepare("SELECT cod_agence, lib_agence FROM `agence`  WHERE  id_user in (select id_user from utilisateurs where agence=(select agence from utilisateurs where id_user='$id_user')) and typ_agence='1'   ORDER BY `lib_agence`");
$rqt->execute();
//apporteur d'affaire  or typ_agence='2'
$rqtap=$bdd->prepare("SELECT cod_agence, lib_agence FROM `agence`  WHERE  typ_agence='2'   ORDER BY `lib_agence` ");
$rqtap->execute();
?>
<div id="content-header">
    <div id="breadcrumb"> <a class="tip-bottom"><i class="icon-home"></i> Validation</a><a class="current">Mode de Paiement</a> </div>
</div>
<div class="row-fluid">
    <div class="span12">
        <div class="widget-box">
            <div id="breadcrumb"> <a class="current"><i></i>Information-Paiement</a></div>
            <div class="widget-content nopadding">
                <form class="form-horizontal">
                    <div class="control-group">
                        <div class="controls">
                            <select id="mpay" onchange="cache()" >
                                <option value="">-- Mode de Paiement (*)</option>
                                <?php while ($row_res=$rqtp->fetch()){  ?>
                                    <option value="<?php  echo $row_res['cod_mpay']; ?>"><?php  echo $row_res['lib_mpay']; ?></option>
                                <?php } ?>
                            </select>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                           <select id="ag" onchange="affectation()" >
                                <option value="">-- Affaire Direct</option>
                                <option value="1">-- Convention</option>
                                <option value="2">-- Apporteur d'affaire</option>
                            </select>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <select id="ag1" >
                                <?php while ($row_res=$rqt->fetch()){  ?>
                                    <option value="<?php  echo $row_res['cod_agence']; ?>"><?php  echo $row_res['lib_agence']; ?></option>
                                <?php }

                                ?>
                            </select>
                            <select id="ag2"  >
                                <?php while ($row_resap=$rqtap->fetch()){  ?>
                                    <option value="<?php  echo $row_resap['cod_agence']; ?>"><?php  echo $row_resap['lib_agence']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="controls">
                            <div data-date-format="dd/mm/yyyy">
                                <input type="text" class="date-pick dp-applied"  id="datop" placeholder="Date-Virement 01/01/1970 (*)" disabled="disabled"/>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="text" id="libpay" class="span4" placeholder="Numero-Cheque ou Date du Virement" disabled="disabled"/>
                            </div>
                        </div>



                        <div class="form-actions" align="right">
                            <input id="btnsous" type="button" class="btn btn-success" onClick="validation('<?php echo $codedev; ?>','<?php echo $page; ?>')" value="Valider" />
                            <input  type="button" class="btn btn-danger"  onClick="Menu1('prod','<?php echo $page; ?>')" value="Annuler" />
                        </div>
                </form>
            </div>
        </div>
    </div>

</div>
<script language="JavaScript">initdate();</script>
<script language="JavaScript">

    function affectation()
    {
        var mode=document.getElementById("ag").value;

        if(mode==''){
            document.getElementById('ag1').style.visibility='hidden';
            document.getElementById('ag2').style.visibility='hidden';
        }
        if(mode=='1'){
            document.getElementById('ag1').style.visibility='visible';
            document.getElementById('ag2').style.visibility='hidden';
        }
        if(mode=='2'){
            document.getElementById('ag1').style.visibility='hidden';
            document.getElementById('ag2').style.visibility='visible';
        }
    }
    function cache(){
        var mode=document.getElementById("mpay").value;
        var lib=document.getElementById("libpay").value;
        if(mode==1 || mode==4){
            document.getElementById('datop').disabled=true;
            document.getElementById('libpay').disabled=true;
        }
        if(mode==2){
            document.getElementById('datop').disabled=false;
            document.getElementById('libpay').disabled=false;
        }
        if(mode==3){
            document.getElementById('datop').disabled=false;
            document.getElementById('libpay').disabled=false;
        }
    }
  function dfrtoen(date1)
        {
            var split_date=date1.split('/');
            var new_d=new Date(split_date[2], split_date[1]*1 - 1, split_date[0]*1);
            var new_day = new_d.getDate();
            new_day = ((new_day < 10) ? '0' : '') + new_day; // ajoute un zéro devant pour la forme
            var new_month = new_d.getMonth() + 1;
            new_month = ((new_month < 10) ? '0' : '') + new_month; // ajoute un zéro devant pour la forme
            var new_year = new_d.getYear();
            new_year = ((new_year < 200) ? 1900 : 0) + new_year; // necessaire car IE et FF retourne pas la meme chose
            var new_date_text = new_year + '-' + new_month + '-' + new_day;
            return new_date_text;
        }
    function validation(codedev,page) {

        var mode = document.getElementById("mpay").value;
        var choix=document.getElementById("ag").value;
        var agence = "";
        if(choix=="")
        {
            agence= "";
        }else
        {
            if (choix=="1")
            {
                agence=document.getElementById("ag1").value;
            }
            else
            {
                agence=document.getElementById("ag2").value;
            }

        }

        var dateop = "<?php echo $datesys;?>";
        var  libmpay = null;

        if (mode == 2 || mode == 3)
        {
            dateop = dfrtoen(document.getElementById("datop").value);
            libmpay = document.getElementById("libpay").value;
        }
        if (window.XMLHttpRequest) {
            xhr = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) {
            xhr = new ActiveXObject("Microsoft.XMLHTTP");
        }


        if (mode) {

            if (mode == 2 || mode == 3) {

                if (dateop && libmpay) {
                    if (verifdate1(document.getElementById("datop"))) {
                        document.getElementById("btnsous").disabled = true;
                        xhr.open("GET", "php/validation/voy/val.php?code=" + codedev + "&mode=" + mode + "&dateop=" + dateop + "&libmpay=" + libmpay + "&agence=" + agence, false);
                        xhr.send(null);
                        Menu1('prod', 'pol' + page);
                    }
                } else {
                    alert("Veuillez remplir les information du paiement");
                }
            } else {


                libmpay="";
                document.getElementById("btnsous").disabled = true;
                xhr.open("GET", "php/validation/voy/val.php?code=" + codedev + "&mode=" + mode + "&dateop=" + dateop + "&libmpay=" + libmpay + "&agence=" + agence, false);
                xhr.send(null);
                Menu1('prod', 'pol' + page);
            }

        } else {
            alert("Veuillez Choisir le Mode de Paiement (*) !");
        }


    }



</script>   