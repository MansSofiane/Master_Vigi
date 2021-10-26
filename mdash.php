<?php
require_once("../../data/conn4.php");
session_start();
$id_user=$_SESSION['id_user'];


?>

<div id="content-header">
    <div id="breadcrumb"> <a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Acceuil</a></div>
</div>
<div class="widget-box">

    <ul class="quick-actions">
        <li class="bg_lh"> <a onClick="mMenu1('prod','assvoy.php')"> <i class="icon-folder-open"></i>A-Voyage</a></li>
        <li class="bg_lb"> <a onClick="mMenu1('mstat','stat.php')"> <i class="icon-bar-chart"></i>E-Production</a> </li>
    </ul>
</div>
