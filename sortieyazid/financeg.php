<?php session_start();
require_once("../../../data/conn4.php");
if ($_SESSION['login']){$user=$_SESSION['id_user'];}
else {
    header("Location:../index.html?erreur=login"); // redirection en cas d'echec
}

if (isset($_REQUEST['d1']) && isset($_REQUEST['p']) && isset($_REQUEST['d2'])&& isset($_REQUEST['s'])) {
    $date1 = $_REQUEST['d1'];
    $prod = $_REQUEST['p'];
    $date2 = $_REQUEST['d2'];
    $type=$_REQUEST['s'];
    $lib_type="";

    $datesys = date("Y/m/d");
    include("convert.php");
    require('tfpdf.php');

    class PDF extends TFPDF
    {
// En-t?te
        function Header()
        {
            $this->SetFont('Arial', 'B', 10);
            $this->Image('../img/entete_bna.png', 6, 4, 290);
            $this->Cell(150, 5, '', 'O', '0', 'L');
            $this->SetFont('Arial', 'B', 12);
            // $this->Cell(60,5,'MAPFRE | Assistance','O','0','L');
            $this->SetFont('Arial', 'B', 10);
            $this->Ln(30);
        }

// Pied de page
        function Footer()
        {
            // Positionnement ? 1,5 cm du bas
            $this->SetY(-15);
            // Police Arial italique 8
            $this->SetFont('Arial', 'I', 6);
            // Num?ro de page
            $this->Cell(0, 8, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
            $this->Ln(3);
            $this->Cell(0, 8, "Algerian Gulf Life Insurance Company, SPA au capital social de 1.000.000.000 de dinars algériens, 01 Rue Tripoli, hussein Dey Alger,  ", 0, 0, 'C');
            $this->Ln(2);
            $this->Cell(0, 8, "RC : 16/00-1009727 B 15   NIF : 001516100972762-NIS :0015160900296000", 0, 0, 'C');
            $this->Ln(2);
            $this->Cell(0, 8, "Tel : +213 (0) 21 77 30 12/14/15 Fax : +213 (0) 21 77 29 56 Site Web : www.aglic.dz  ", 0, 0, 'C');
        }
    }

// Instanciation de la classe derivee
    $pdf = new PDF('L', 'mm', 'A3');
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetFillColor(199, 139, 85);
    $pdf->SetFont('Arial', 'B', 15);


    if($type==1) {
        $lib_type = "ETAT  DES PRIMES ENCAISSEES";
    }elseif($type==2) {
        $lib_type = "ETAT  DES PRIMES IMPAYEES";
    }else {
        $lib_type = "ETAT  DES PRIMES RECOUVREES";
    }


    $rqtp = $bdd->prepare("SELECT a.`agence`,nom,prenom FROM `utilisateurs` as a where a.id_user='$user'");
    $rqtp->execute();
    $agence="";
    $code_prod="";
    while ($row_p=$rqtp->fetch()){
    $pdf->Cell(405,10,$lib_type.' '.date("d/m/Y", strtotime($date1)).' au '.date("d/m/Y", strtotime($date2)).'  --Document généré le-- '.date("d/m/Y", strtotime($datesys)).'           par :'.$row_p['nom'].' '.$row_p['prenom'] ,'1','1','L','1');

        $pdf->Cell(405,10,'AgenceN°: '.$row_p['agence'],'1','0','L');
        $agence=$row_p['agence'];

    }

    $pdf->Ln(16);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(67, 10, 'Produit', '1', '0', 'C');
   /* $pdf->Cell(44, 5, 'Police N°', '1', '0', 'C');
    $pdf->Cell(44, 5, 'Avenant N°', '1', '0', 'C');
    $pdf->Cell(50, 5, 'Nom&Prénom-R.Sociale', '1', '0', 'C');
    $pdf->Cell(20, 5, 'Emmision', '1', '0', 'C');
    $pdf->Cell(20, 5, 'Effet', '1', '0', 'C');
    $pdf->Cell(20, 5, 'Echéance', '1', '0', 'C');*/

    $pdf->Cell(65, 10, 'P.Nette', '1', '0', 'C');
    $pdf->Cell(55, 10, 'C.Police', '1', '0', 'C');
    $pdf->Cell(70, 10, 'P.Commer', '1', '0', 'C');
    $pdf->Cell(55, 10, 'D.Timbre', '1', '0', 'C');
    $pdf->Cell(93, 10, 'P.Total', '1', '0', 'C');

    $pdf->Ln();

    //entete
    $totalg_pn = 0;
    $totalg_cpl = 0;
    $totalg_commerciale = 0;
    $totalg_droit_timbre = 0;
    $ttcg = 0;
    $rqtp=$bdd->prepare("select cod_prod,lib_prod,code_prod from produit WHERE `cod_prod` in (1,2,5,6,7,9,10);");
    $rqtp->execute();

    while ($rp=$rqtp->fetch()) {
        $prod = $rp['cod_prod'];
$lib_prod=$rp['lib_prod'];
        $code_prod=$rp['code_prod'];



        if($type==1) {
            // $lib_type = "ETAT  DES PRIMES ENCAISSEES";
            $rqt=$bdd->prepare("select  p.cod_pol police,'0' as avenant,p.cod_prod,p.sequence sequence_police,'' sequence_av,p.dat_val dat_pol,'' dateavanant,a.id_avis,a.dat_avis,p.dat_eff,p.dat_ech,s.nom_sous,s.pnom_sous,a.mtt_avis,a.cod_ref,a.cod_av,a.base_pn_commission,a.mtt_cpl,a.mtt_dt from avis_recette  a, policew as p ,souscripteurw as s,utilisateurs u

where a.sens_avis=0 and a.type_avis=0

and p.cod_prod='$prod'

      and DATE_FORMAT(a.dat_avis,'%Y-%m-%d') between '$date1' and '$date2'
      and (DATE_FORMAT(p.dat_val,'%Y-%m-%d') between '$date1' and '$date2' and a.cod_ref=p.cod_pol )
      and p.cod_sous=s.cod_sous
      and s.id_user=u.id_user and u.agence='$agence'
");
            $rqt->execute();
            $rqtav=$bdd->prepare("select  v.cod_pol police,v.cod_av as avenant,v.cod_prod,p.sequence sequence_police,v.sequence sequence_av,p.dat_val dat_pol ,v.dat_val dateavanant,a.id_avis,a.dat_avis,v.dat_eff,v.dat_ech,s.nom_sous,s.pnom_sous,a.mtt_avis,a.cod_ref,a.cod_av,a.base_pn_commission,a.mtt_cpl,a.mtt_dt,v.lib_mpay from avis_recette  a, policew as p ,souscripteurw as s,avenantw as v,utilisateurs u

where a.sens_avis=0 and a.type_avis=1
     and p.cod_prod='$prod'
      and DATE_FORMAT(a.dat_avis,'%Y-%m-%d') between '$date1' and '$date2'
      and (DATE_FORMAT(v.dat_val,'%Y-%m-%d') between '$date1' and '$date2' and a.cod_ref=v.cod_pol and a.cod_av=v.cod_av )
      and p.cod_sous=s.cod_sous
      and s.id_user=u.id_user and u.agence='$agence'
      and p.cod_pol=v.cod_pol");
            $rqtav->execute();

        }elseif($type==2) {
            //  $lib_type = "ETAT  DES PRIMES IMPAYEES";

            $rqt=$bdd->prepare("
SELECT p.cod_pol,''  as avenant,p.cod_prod,p.sequence sequence_police,'' as sequence_av,p.dat_val dat_pol,p.dat_val as dat_avis,'' dateavanant,p.dat_eff,p.dat_ech,s.nom_sous,s.pnom_sous,p.mtt_reg,case p.mtt_reg when 0 then  p.mtt_solde-c.mtt_cpl-d.mtt_dt else p.mtt_solde end base_pn_commission,case p.mtt_reg when 0 then  c.mtt_cpl else 0 end mtt_cpl, case p.mtt_reg when 0 then  d.mtt_dt else 0 end mtt_dt
,p.mtt_solde  mtt_avis
from policew as p,souscripteurw as s, cpolice as c, dtimbre as d,utilisateurs as u
where p.cod_sous=s.cod_sous
and p.cod_cpl=c.cod_cpl
and p.cod_dt=d.cod_dt
and p.cod_prod='$prod'
and s.id_user=u.id_user and u.agence='$agence'
and DATE_FORMAT(p.dat_val,'%Y-%m-%d') between '$date1' and '$date2'
and p.mtt_solde>0
");
            $rqt->execute();
            $rqtav=$bdd->prepare("
SELECT p.cod_pol,''  as avenant,p.cod_prod,p.sequence sequence_police,v.sequence as sequence_av,v.dat_val dat_pol,v.dat_val as dat_avis,'' dateavanant,v.dat_eff,v.dat_ech,s.nom_sous,s.pnom_sous,v.mtt_reg,case v.mtt_reg when 0 then  v.mtt_solde-c.mtt_cpl-d.mtt_dt else v.mtt_solde end base_pn_commission,case v.mtt_reg when 0 then  c.mtt_cpl else 0 end mtt_cpl, case v.mtt_reg when 0 then  d.mtt_dt else 0 end mtt_dt
,v.mtt_solde  mtt_avis,v.lib_mpay
from policew as p,souscripteurw as s, cpolice as c, dtimbre as d, avenantw as v,utilisateurs u
where p.cod_sous=s.cod_sous
and s.id_user=u.id_user and u.agence='$agence'
and v.cod_cpl=c.cod_cpl
and v.cod_dt=d.cod_dt
and p.cod_pol=v.cod_pol
and p.cod_prod='$prod'
and DATE_FORMAT(v.dat_val,'%Y-%m-%d') between '$date1' and '$date2'
and v.mtt_solde>0
and v.lib_mpay not in ('30','50')");
            $rqtav->execute();
        }elseif($type==3) {

            //  $lib_type = "ETAT  DES PRIMES RECOUVREES";
            $rqt=$bdd->prepare("select  p.cod_pol police,'0' as avenant,p.cod_prod,p.sequence sequence_police,'' sequence_av,p.dat_val dat_pol,'' dateavanant,a.id_avis,a.dat_avis,p.dat_eff,p.dat_ech,s.nom_sous,s.pnom_sous,a.mtt_avis,a.cod_ref,a.cod_av,a.base_pn_commission,a.mtt_cpl,a.mtt_dt from avis_recette  a, policew as p ,souscripteurw as s,utilisateurs u

where a.sens_avis=0 and a.type_avis=0

and p.cod_prod='$prod'

      and DATE_FORMAT(a.dat_avis,'%Y-%m-%d') between '$date1' and '$date2'
      and (DATE_FORMAT(p.dat_val,'%Y-%m-%d') < '$date1' and a.cod_ref=p.cod_pol )
      and p.cod_sous=s.cod_sous
      and s.id_user=u.id_user and u.agence='$agence'
");
            $rqt->execute();
            $rqtav=$bdd->prepare("select  v.cod_pol police,v.cod_av as avenant,v.cod_prod,p.sequence sequence_police,v.sequence sequence_av,p.dat_val dat_pol ,v.dat_val dateavanant,a.id_avis,a.dat_avis,v.dat_eff,v.dat_ech,s.nom_sous,s.pnom_sous,a.mtt_avis,a.cod_ref,a.cod_av,a.base_pn_commission,a.mtt_cpl,a.mtt_dt,v.lib_mpay from avis_recette  a, policew as p ,souscripteurw as s,avenantw as v,utilisateurs u

where a.sens_avis=0 and a.type_avis=1
     and p.cod_prod='$prod'
      and DATE_FORMAT(a.dat_avis,'%Y-%m-%d') between '$date1' and '$date2'
      and (DATE_FORMAT(v.dat_val,'%Y-%m-%d') < '$date1'  and a.cod_ref=v.cod_pol and a.cod_av=v.cod_av )
      and p.cod_sous=s.cod_sous
      and s.id_user=u.id_user and u.agence='$agence'
      and p.cod_pol=v.cod_pol");
            $rqtav->execute();
        }
        else
        {
            // $lib_type = "ETAT  DES PRIMES ENCAISSEES";
            $rqt=$bdd->prepare("select  p.cod_pol police,'0' as avenant,p.cod_prod,p.sequence sequence_police,'' sequence_av,p.dat_val dat_pol,'' dateavanant,a.id_avis,a.dat_avis,p.dat_eff,p.dat_ech,s.nom_sous,s.pnom_sous,a.mtt_avis,a.cod_ref,a.cod_av,a.base_pn_commission,a.mtt_cpl,a.mtt_dt from avis_recette  a, policew as p ,souscripteurw as s,utilisateurs u

where a.sens_avis=1 and a.type_avis=0

and p.cod_prod='$prod'

      and DATE_FORMAT(a.dat_avis,'%Y-%m-%d') between '$date1' and '$date2'
      and ( a.cod_ref=p.cod_pol )
      and p.cod_sous=s.cod_sous
      and s.id_user=u.id_user and u.agence='$agence'
");
            $rqt->execute();
            $rqtav=$bdd->prepare("select  v.cod_pol police,v.cod_av as avenant,v.cod_prod,p.sequence sequence_police,v.sequence sequence_av,p.dat_val dat_pol ,v.dat_val dateavanant,a.id_avis,a.dat_avis,v.dat_eff,v.dat_ech,s.nom_sous,s.pnom_sous,a.mtt_avis,a.cod_ref,a.cod_av,a.base_pn_commission,a.mtt_cpl,a.mtt_dt,v.lib_mpay from avis_recette  a, policew as p ,souscripteurw as s,avenantw as v,utilisateurs u

where a.sens_avis=1 and a.type_avis=1
     and p.cod_prod='$prod'
      and DATE_FORMAT(a.dat_avis,'%Y-%m-%d') between '$date1' and '$date2'
      and ( a.cod_ref=v.cod_pol and a.cod_av=v.cod_av )
      and p.cod_sous=s.cod_sous
      and s.id_user=u.id_user and u.agence='$agence'
      and p.cod_pol=v.cod_pol");
            $rqtav->execute();

        }


        $total_pn = 0;
        $total_cpl = 0;
        $total_commerciale = 0;
        $total_droit_timbre = 0;
        $ttc = 0;
        while ($row_g = $rqt->fetch()) {
           // $pdf->Cell(35, 5, $lib_prod, '1', '0', 'L');
           // $pdf->Cell(44, 5, '' . $agence . '.' . substr($row_g['dat_pol'], 0, 4) . '.10.' . $code_prod . '.' . str_pad((int)$row_g['sequence_police'], '5', "0", STR_PAD_LEFT) . '', '1', '0', 'C');
          //  $pdf->Cell(44, 5, '--', '1', '0', 'C');
          //  $pdf->Cell(50, 5, $row_g['nom_sous'] . $row_g['pnom_sous'], '1', '0', 'L');
           // $pdf->Cell(20, 5, '' . date("d/m/Y", strtotime($row_g['dat_avis'])), '1', '0', 'C');
           // $pdf->Cell(20, 5, '' . date("d/m/Y", strtotime($row_g['dat_eff'])), '1', '0', 'C');
           // $pdf->Cell(20, 5, '' . date("d/m/Y", strtotime($row_g['dat_ech'])), '1', '0', 'C');

           // $pdf->Cell(35, 5, '' . number_format($row_g['base_pn_commission'], 2, ',', ' ') . '', '1', '0', 'R');
            $total_pn += $row_g['base_pn_commission'];
          //  $pdf->Cell(25, 5, '' . number_format($row_g['mtt_cpl'], 2, ',', ' ') . '', '1', '0', 'R');
            $total_cpl += $row_g['mtt_cpl'];
           // $pdf->Cell(40, 5, '' . number_format($row_g['base_pn_commission'] + $row_g['mtt_cpl'], 2, ',', ' ') . '', '1', '0', 'R');
            $total_commerciale += $row_g['base_pn_commission'] + $row_g['mtt_cpl'];
          //  $pdf->Cell(25, 5, '' . number_format($row_g['mtt_dt'], 2, ',', ' ') . '', '1', '0', 'R');
            $total_droit_timbre += $row_g['mtt_dt'];
          //  $pdf->Cell(47, 5, '' . number_format($row_g['mtt_avis'], 2, ',', ' ') . '', '1', '0', 'R');
            $ttc += $row_g['mtt_avis'];
           // $pdf->Ln();

        }
        while ($row_g = $rqtav->fetch()) {
          //  $pdf->Cell(35, 5, $lib_prod, '1', '0', 'L');
          //  $pdf->Cell(44, 5, '' . $agence . '.' . substr($row_g['dat_pol'], 0, 4) . '.10.' . $code_prod . '.' . str_pad((int)$row_g['sequence_police'], '5', "0", STR_PAD_LEFT) . '', '1', '0', 'C');
          //  $pdf->Cell(44, 5, '' . $agence . '.' . substr($row_g['dateavanant'], 0, 4) . '.' . $row_g['lib_mpay'] . '.' . $code_prod . '.' . str_pad((int)$row_g['sequence_av'], '5', "0", STR_PAD_LEFT) . '', '1', '0', 'C');

          //  $pdf->Cell(50, 5, $row_g['nom_sous'] . $row_g['pnom_sous'], '1', '0', 'L');
          //  $pdf->Cell(20, 5, '' . date("d/m/Y", strtotime($row_g['dat_avis'])), '1', '0', 'C');
           // $pdf->Cell(20, 5, '' . date("d/m/Y", strtotime($row_g['dat_eff'])), '1', '0', 'C');
          //  $pdf->Cell(20, 5, '' . date("d/m/Y", strtotime($row_g['dat_ech'])), '1', '0', 'C');

          //  $pdf->Cell(35, 5, '' . number_format($row_g['base_pn_commission'], 2, ',', ' ') . '', '1', '0', 'R');
            $total_pn += $row_g['base_pn_commission'];
           // $pdf->Cell(25, 5, '' . number_format($row_g['mtt_cpl'], 2, ',', ' ') . '', '1', '0', 'R');
            $total_cpl += $row_g['mtt_cpl'];
          //  $pdf->Cell(40, 5, '' . number_format($row_g['base_pn_commission'] + $row_g['mtt_cpl'], 2, ',', ' ') . '', '1', '0', 'R');
            $total_commerciale += $row_g['base_pn_commission'] + $row_g['mtt_cpl'];
          //  $pdf->Cell(25, 5, '' . number_format($row_g['mtt_dt'], 2, ',', ' ') . '', '1', '0', 'R');
            $total_droit_timbre += $row_g['mtt_dt'];
          //  $pdf->Cell(47, 5, '' . number_format($row_g['mtt_avis'], 2, ',', ' ') . '', '1', '0', 'R');
            $ttc += $row_g['mtt_avis'];
           // $pdf->Ln();

        }
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetTextColor(0, 0, 0);
        //$pdf->SetFillColor(192,195,198);
        $pdf->Cell(67, 7, ''.$lib_prod, '1', '0', 'L');

        $pdf->Cell(65, 7, '' . number_format($total_pn, 2, ',', ' ') . '', '1', '0', 'R');
        $pdf->Cell(55, 7, '' . number_format($total_cpl, 2, ',', ' ') . '', '1', '0', 'R');
        $pdf->Cell(70, 7, '' . number_format($total_commerciale, 2, ',', ' ') . '', '1', '0', 'R');
        $pdf->Cell(55, 7, '' . number_format($total_droit_timbre, 2, ',', ' ') . '', '1', '0', 'R');
        $pdf->Cell(93, 7, '' . number_format($ttc, 2, ',', ' ') . '', '1', '0', 'R');
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 10);
        $totalg_pn += $total_pn;
        $totalg_cpl += $total_cpl;
        $totalg_commerciale += $total_commerciale;
        $totalg_droit_timbre += $total_droit_timbre;
        $ttcg += $ttc;

    }

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetFillColor(10,226,225);
    $pdf->Cell(67, 10, 'Total  général', '1', '0', 'L','1');

    $pdf->Cell(65, 10, '' . number_format($totalg_pn, 2, ',', ' ') . '', '1', '0', 'R','1');
    $pdf->Cell(55, 10, '' . number_format($totalg_cpl, 2, ',', ' ') . '', '1', '0', 'R','1');
    $pdf->Cell(70, 10, '' . number_format($totalg_commerciale, 2, ',', ' ') . '', '1', '0', 'R','1');
    $pdf->Cell(55, 10, '' . number_format($totalg_droit_timbre, 2, ',', ' ') . '', '1', '0', 'R','1');
    $pdf->Cell(93, 10, '' . number_format($ttcg, 2, ',', ' ') . '', '1', '0', 'R','1');
    $pdf->Ln();



    $pdf->Output();
}
?>