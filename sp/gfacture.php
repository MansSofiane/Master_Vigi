<?php session_start();
require_once("../../../data/conn4.php");

function datenfr($dat)
{
    $d=new DateTime($dat);
    $dd=$d->format('d/m/Y');
    return $dd;
}
if ($_SESSION['login']){
    $id_user=$_SESSION['id_user'];

    $rqtap=$bdd->prepare("SELECT cod_agence, lib_agence FROM `agence`  WHERE  typ_agence='2'   ORDER BY `lib_agence` ");
    $rqtap->execute();

    $rqt_dr=$bdd->prepare("select id_user,agence from utilisateurs where type_user='dr' and etat_user='A'");
    $rqt_dr->execute();
}
else {
    header("Location:index.html");
}

?>

<div id="content-header">
    <div id="breadcrumb"> <a class="tip-bottom"><i class="icon-signal"></i> Commissions</a><a class="current">Nouvelle-Facture</a></div>
</div>



    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">

                <div class="widget-content nopadding">
                    <form class="form-horizontal">

                        <div class="control-group">
                            <div class="controls">
                                <div data-date-format="dd/mm/yyyy">
                                    <input type="text" class="date-pick dp-applied"  id="date1"  placeholder="Du 01/01/2000 (*)"/>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="text" class="date-pick dp-applied"  id="date2" placeholder="Au 01/01/2000 (*)"/>
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="controls">
                                <span> Mode de facturation:</span>
                                <select id="civ" onchange="affectation()">
                                    <option value=""> Mode Facturation (*)</option>
                                    <option value="0"> Cash</option>
                                    <option value="2"> Courtier</option>

                                </select>
                                <span> Direction Regionale:</span>
                                <select id="dr" >
                                    <?php while ($row_resap=$rqt_dr->fetch()){  ?>
                                        <option value="<?php  echo $row_resap['id_user']; ?>"><?php  echo $row_resap['agence']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                           <div class="controls">
                             <select id="ag2"  style="display: none;">
                               <?php while ($row_resap=$rqtap->fetch()){  ?>
                                <option value="<?php  echo $row_resap['cod_agence']; ?>"><?php  echo $row_resap['lib_agence']; ?></option>
                                    <?php } ?>
                             </select>
                           </div>
                        </div>

                    </form>

            </div>
        </div>

        <div class="form-actions" align="right">
            <input  type="button" class="btn btn-success" id="btnsous" onclick="generer()"  value="G&eacute;n&eacute;rer" />
            <input  type="button" class="btn btn-danger"  onClick="sMenu1('com','factures.php')" value="Annuler" />
        </div>
    </div>
    </div>
<script language="JavaScript">initdate();</script>
<script>

    function affectation()
    {

        var mode=document.getElementById("civ").value;

        if(mode=='2'){
            document.getElementById('ag2').style.display='block';

        }
        else
        {
            document.getElementById('ag2').style.display='none';
        }

    }

    function generer()
    {


        var dat2=document.getElementById("date2");
        var dat1=document.getElementById("date1");

        var ndat1=dfrtoen(dat1.value);
        var ndat2=dfrtoen(dat2.value);
        var datcloture=document.getElementById("date1").value;
        var user=document.getElementById("dr").value;
var cree='<?php echo $id_user;?>';
        var civ=document.getElementById("civ").value;
        var ag2=document.getElementById("ag2").value;

        if(civ!='') {
                     if(civ=='0') ag2=0; // cash direct sinon on garde le code de courtier.

            if (!compdat(dat2) || !comp2dat(dat1, dat2)) {

               if (window.XMLHttpRequest) {
                    xhr = new XMLHttpRequest();
                }
                else if (window.ActiveXObject) {
                    xhr = new ActiveXObject("Microsoft.XMLHTTP");
                }

                xhr.onreadystatechange = function () {

                    if (this.readyState == 4 && this.status == 200) {

                        res = xhr.responseText.split(',');

                        sMenu1('com', 'factures.php');

                    }
                    else {
                        document.getElementById("content-header").innerHTML = '<div style="padding:20%; "><img src="img/spinner.gif" style="width:35px" alt=""/><H4>Validation en cours ...</H4></div>';
                    }

                };

                 document.getElementById("btnsous").disabled=true;
               xhr.open("GET", "php/validation/genererfact.php?c=" + civ + "&dat1=" + ndat1 + "&dat2=" + ndat2 + "&u=" + user+"&ag2="+ag2+"&cre="+cree, false);
                xhr.send(null);

              //  window.open('sortie/facture/' + civ + '/' + ndat1 + '/' + ndat2+ '/' + user+ '/' + ag2, 'commission', 'height=600, width=800, toolbar=no, menubar=no, scrollbars=no, resizable=no, location=no, directories=no, status=no');


            }
            else {
                alert('La nouvelle  date de cl\364ture doit \352tre inf\351rieure \340  la date du jour!.'+dat1.value);
            }
        }else
        {
            alert('veuillez selectionner le mode de facturation.');
        }

    }
</script>