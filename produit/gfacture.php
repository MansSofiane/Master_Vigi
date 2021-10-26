<?php session_start();
require_once("../../../data/conn4.php");

if ($_SESSION['login']){
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
                                <input type="text" class="date-pick dp-applied"  id="date1" placeholder="Du 01/01/2000 (*)"/>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="text" class="date-pick dp-applied"  id="date2" placeholder="Au 01/01/2000 (*)"/>
                            </div>
                        </div>

                </form>
            </div>
        </div>
    </div>

    <div class="form-actions" align="right">
        <input  type="button" class="btn btn-success"  value="Suivant" />
        <input  type="button" class="btn btn-danger"  onClick="dMenu1('prod','assgroupe.php')" value="Annuler" />
    </div>
</div>
