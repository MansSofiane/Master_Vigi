<?php session_start();
require_once("../../../data/conn4.php");
$token1 = generer_token('stat');
if ($_SESSION['login']){
}
else {
    header("Location:index.html");
}
if ( isset($_REQUEST['tok']) ) {
    $token = $_REQUEST['tok'];
}
?>

<div id="content-header">
    <div id="breadcrumb"> <a class="tip-bottom"><i class="icon-signal"></i> Etats</a><a class="current">Caisse-PDF</a></div>
</div>



<div class="row-fluid">
    <div class="span12">
        <div class="widget-box">

            <div class="widget-content nopadding">
                <form class="form-horizontal">
                    <div class="control-group">
                        <label class="control-label">Type-Etat:</label>
                        <div class="controls">
                            <select id="te">
                                <option value="1">Encaissements</option>
                                <option value="2">Primes-Impay&eacute;es</option>
                                <option value="3">Primes-Recouver&eacute;es</option>
                                <option value="4">Primes-ristourn&eacute;es</option>
                                <option value="5">Mouvement-caisse</option>

                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <div class="controls">
                            <div data-date-format="dd/mm/yyyy">
                                <input type="text" class="date-pick dp-applied"  id="date1" placeholder="Du 01/01/2000 (*)"/>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="text" class="date-pick dp-applied"  id="date2" placeholder="Au 01/01/2000 (*)"/>
                            </div>
                        </div>

                </form>
            </div>
        </div>
    </div>

    <div class="widget-box">

        <ul class="quick-actions">
            <li class="bg_lh"> <a onClick="prod('1')"> <i class="icon-bar-chart"></i>A-Voyage</a> </li>
            <li class="bg_lb"> <a onClick="prod('6')"> <i class="icon-bar-chart"></i>TD</a> </li>
            <li class="bg_ly"> <a onClick="prod('7')"> <i class="icon-bar-chart"></i>ADE</a> </li>
            <li class="bg_lg"> <a onClick="prod('2')"> <i class="icon-bar-chart"></i>I-Accident</a> </li>
            <li class="bg_lw"> <a onClick="prod('5')"> <i class="icon-bar-chart"></i>C-Sein (Warda)</a> </li>
            <li class="bg_lo"> <a onClick="prod('9')"> <i class="icon-bar-chart"></i>Groupe</a> </li>
            <li class="bg_lm"> <a onClick="prod('10')"> <i class="icon-bar-chart"></i>PTA</a> </li>
            <li class="bg_lv"> <a onClick="prod('100')"> <i class="icon-bar-chart"></i>Etat Consolid&eacute;</a> </li>
        </ul>
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
            new_day = ((new_day < 10) ? '0' : '') + new_day; // ajoute un zéro devant pour la forme
            var new_month = new_d.getMonth() + 1;
            new_month = ((new_month < 10) ? '0' : '') + new_month; // ajoute un zéro devant pour la forme
            var new_year = new_d.getYear();
            new_year = ((new_year < 200) ? 1900 : 0) + new_year; // necessaire car IE et FF retourne pas la meme chose
            var new_date_text = new_year + '-' + new_month + '-' + new_day;
            return new_date_text;
        }
        function prod(code) {

            var type = document.getElementById("te").value;
            var date1 = document.getElementById("date1");
            var date2 = document.getElementById("date2");

            if (verifdatestat(date1) && verifdatestat(date2)) {
                var d1 = dfrtoenstat(date1.value);
                var d2 = dfrtoenstat(date2.value);
                if (type == 5) {
                    if (code != 100) {

                        window.open('sortie/caissem/' + d1 + '/' + code + '/' + d2 + '/' + type, 'Finances', 'height=600, width=800, toolbar=no, menubar=no, scrollbars=no, resizable=no, location=no, directories=no, status=no');
                    }
                    else {
                        window.open('sortie/caissegm/' + d1 + '/' + code + '/' + d2 + '/' + type, 'Financesconsolide', 'height=600, width=800, toolbar=no, menubar=no, scrollbars=no, resizable=no, location=no, directories=no, status=no');
                    }
                } else {
                    if (code != 100) {

                        window.open('sortie/caisse/' + d1 + '/' + code + '/' + d2 + '/' + type, 'Finances', 'height=600, width=800, toolbar=no, menubar=no, scrollbars=no, resizable=no, location=no, directories=no, status=no');
                    }
                    else {
                        window.open('sortie/caisseg/' + d1 + '/' + code + '/' + d2 + '/' + type, 'Financesconsolide', 'height=600, width=800, toolbar=no, menubar=no, scrollbars=no, resizable=no, location=no, directories=no, status=no');
                    }
                }
            }
        }

    </script>