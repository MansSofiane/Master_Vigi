<?php session_start();
require_once("../../../../../../data/conn4.php");
if ($_SESSION['login']){
}
else {
    header("Location:login.php");
}
$id_user = $_SESSION['id_user'];
$datesys=date("Y-m-d");

?>
<div id="content-header">
    <div id="breadcrumb"> <a class="tip-bottom"><i class="icon-home"></i> Produit</a> <a>Assurance-Voyage-Groupe</a> <a class="current">Nouveau-Devis</a> </div>
</div>
<div class="row-fluid">
    <div class="span12">
        <div class="widget-box">
            <div id="breadcrumb"> <a class="current"><i></i>Souscripteur</a><a>Assure</a>
                <div class="widget-content nopadding">
                    <form class="form-horizontal">

                        <div class="control-group">
                            <div class="controls">
                                <input type="text" id="nsous" class="span4" placeholder="Raison Social (*)" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="controls">
                                <input type="text" id="mailsous" class="span4" placeholder="E-mail" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="text" id="telsous" class="span4" placeholder="Tel: 213 XXX XX XX XX" />
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="controls">
                                <div data-date-format="dd/mm/yyyy">
                                    <input type="text" id="adrsous" class="span4" placeholder="Adresse (*)" />
                                </div>
                            </div>
                            <div class="form-actions" align="right">
                                <input  type="button" class="btn btn-success" onClick="instarsous('<?php echo $id_user; ?>')" value="Suivant" />
                                <input  type="button" class="btn btn-danger"  onClick="Menu1('prod','assvoygrp.php')" value="Annuler" />
                            </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <script language="JavaScript">initdate();</script>
    <script language="JavaScript">
        function initdate(){
            Date.firstDayOfWeek = 0;
            Date.format = 'dd/mm/yyyy';
            $(function()
            {$('.date-pick').datePicker({startDate:'01/01/1930'});});
        }
        function tarif(id,page) {
            document.getElementById('macc').setAttribute("class", "hover");
            document.getElementById('mstat').setAttribute("class", "hover");
            document.getElementById('mclt').setAttribute("class", "hover");
            document.getElementById('prod').setAttribute("class", "hover");
            document.getElementById(id).setAttribute("class", "active");
            $("#content").load('php/tarif/'+page);
        }
        function verifdate1(dd)
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
        function dfrtoen(date1)
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
        function instarsous(user){

            var civilite=0;
            var nom=document.getElementById("nsous").value;
            var prenom="";
            var adr=document.getElementById("adrsous").value;
            var nbassur=0;
            var datnais=null;
            var mail=null,tel=null,age=null;
            var datnaisen=null;
            mail=document.getElementById("mailsous").value;
            tel=document.getElementById("telsous").value;

            if (window.XMLHttpRequest) {
                xhr = new XMLHttpRequest();
            }
            else if (window.ActiveXObject)
            {
                xhr = new ActiveXObject("Microsoft.XMLHTTP");
            }

            if(nom &&  adr ){
                    age=0;
                    datnaisen='';
                        xhr.open("GET","produit/voyage/groupe/moral/new_sous.php?civ="+civilite+"&nom="+nom+"&prenom="+prenom+"&adr="+adr+"&mail="+mail+"&tel="+tel+"&nbassur="+nbassur+"&age="+age+"&dnais="+datnaisen,false);
                        xhr.send(null);

                        $("#content").load("produit/voyage/groupe/moral/charger_assur.php?nbassur="+nbassur);

            }else{swal("Attention !","Veuillez remplir tous les champs Obligatoire (*) !","warning");}
        }

    </script>