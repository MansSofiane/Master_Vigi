<?php session_start();
require_once("../../../../data/conn4.php");
if ($_SESSION['login']){}
else {header("Location:login.php");}

$id_user = $_SESSION['id_user'];
$datesys=date("Y-m-d");
$tokt_sous = generer_token('typ_sousc');
if ( isset($_REQUEST['tok']) ) {
    $token = $_REQUEST['tok'];
}

$rqt=$bdd->prepare("SELECT * FROM `type_sous`");
$rqt->execute();

?><head>

    <style>
        .assuree {
            visibility:hidden;
            background-color: #ffffff;
            margin-left: 325px;
            position: absolute;
            z-index:0;
        }
    </style>

</head>

<div id="content-header">
    <div id="breadcrumb"> <a class="tip-bottom"><i class="icon-home"></i> Produit</a><a>Individuelle-Accident</a><a>Formule-Individuelle</a> <a class="current">Nouveau-Devis</a> </div>
</div>
<div class="row-fluid">
    <div class="span12">
        <div class="widget-box">
            <div id="breadcrumb"> <a class="current"><i></i>Souscripteur</a><a>Assure</a>
                <div class="widget-content nopadding">
                    <form class="form-horizontal">


                        <div id="step-holder">
                            <div class="controls">
                                <select   id="tysous"  >
                                    <option value="">--  Type Du Souscripteur(*)</option>
                                    <?php
                                    while ($row_tsous=$rqt->fetch()){ ?>
                                        <option value="<?php echo $row_tsous['cod_tsous']; ?> " ><?php echo $row_tsous['lib_tsous'];  ?></option>
                                    <?php } ?>
                                </select>

                            </div>
                        </div>


                        <div class="form-actions" align="right">
                            <input  type="button" class="btn btn-success" onClick="typesous('<?php echo $tokt_sous; ?>')" value="Suivant" />
                            <input  type="button" class="btn btn-danger"  onClick="Menu1('prod','assiaccind.php')" value="Annuler" />
                        </div>

                </div>
            </div>
        </div>

    </div>  </div>

<script language="JavaScript">

    function typesous(tok){
        var tsousc=document.getElementById("tysous").value;

        if (window.XMLHttpRequest) {
            xhr = new XMLHttpRequest();
        }
        else if (window.ActiveXObject)
        {
            xhr = new ActiveXObject("Microsoft.XMLHTTP");
        }

        if (tsousc){
            if(tsousc==2){

                $("#content").load("produit/iaccidg/phy/devindg.php?tok="+tok);
            }else{
                $("#content").load("produit/iaccidg/moral/devindg.php?tok="+tok);
            }

        }else{swal("Attention","Veuillez choisir le type de souscripteur !","warning");}

    }
</script>
















