<?php session_start();
require_once("../../../data/conn4.php");
if ($_SESSION['login']){
}
else {
    header("Location:login.php");
}
$id_user = $_SESSION['id_user'];
$datesys=date("Y-m-d");

$type_agence='1';


$rqt = $bdd->prepare("SELECT id_user, agence FROM `utilisateurs`  WHERE type_user='user' and etat_user='A'");
$rqt->execute();
$redondance="Tout";

?>
<div id="content-header">
    <div id="breadcrumb"> <a class="current1">Conventions-Voyage</a> <a class="current">Nouvelle-Convention</a> </div>
</div>
<div class="row-fluid">
    <div class="span12">
        <div class="widget-box">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <div class="widget-content nopadding">
                    <form class="form-horizontal">


                            <div class="control-group">
                                <label class="control-label">Agence *:</label>
                                <div class="controls">
                                    <select id="user">
                                        <option value="">Selectionner</option>
                                        <?php while ($row_res=$rqt->fetch()){
                                            if($redondance!=$row_res['agence']){?>
                                                <option value="<?php  echo $row_res['id_user']; ?>"><?php  echo $row_res['agence']; ?></option>
                                                <?php $redondance=$row_res['agence'];}
                                        }?>
                                    </select>
                                </div>
                            </div>


                        <div class="control-group">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <div class="controls">
                                <input type="text" id="nomag" class="span3" placeholder="Raison social (*)" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;


                                <input type="text" id="nomrep" class="span3" placeholder= "Nom de Respresentant (*)" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="text" id="prenomrep" class="span3" placeholder= "Prenom de Respresentant (*)" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </div>
                        </div>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <div class="control-group">
                            <div class="controls">
                                <input type="text" id="adr" class="span3" placeholder="Adresse (*)" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="text" id="mail" class="span3" placeholder= "Email (*)" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="text" id="tel" class="span3" placeholder="Tel: 213 XXX XX XX XX" />
                            </div>
                        </div>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <div class="form-actions" align="right">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input  type="button" class="btn btn-success" onClick="instagence('<?php echo $id_user; ?>')" value="Enregistrer" />
                        <input  type="button" class="btn btn-danger"  onClick="Menu('avoy','produit/list_agence.php')" value="Annuler" />
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
                alert("Format date incorrect! jj/mm/aaaa");dd.value="";

            }
            return v1;
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
        function instagence(user){

            var nomag =document.getElementById("nomag").value;
            var u =document.getElementById("user").value;
            var nomrep =document.getElementById("nomrep").value;
            var prenomrep =document.getElementById("prenomrep").value;
            var adr =document.getElementById("adr").value;
            var  tel=null, mail=null ;
            tel =document.getElementById("tel").value;
            mail =document.getElementById("mail").value;
            var typ=<?php echo $type_agence;?>;
            if (window.XMLHttpRequest) {
                xhr = new XMLHttpRequest();
            }
            else if (window.ActiveXObject)
            {
                xhr = new ActiveXObject("Microsoft.XMLHTTP");
            }
            if(nomag  && nomrep && prenomrep && adr && u ){
                xhr.open("GET","produit/nagence.php?nomag="+nomag+"&nomrep="+nomrep+"&prenomrep="+prenomrep+"&adr="+adr+"&tel="+tel+"&mail="+mail+"&typ="+typ);

                xhr.send(null);
                alert("Agence de voyage Cree avec Succes!");
                $("#content").load("sp/list_agence.php");

            }else{alert("Veuillez remplir tous les champs Obligatoire (*) !");}




        }

    </script>