<?php session_start();
require_once("../../../data/conn4.php");
if ($_SESSION['login']){$user=$_SESSION['id_user'];}
else {
    header("Location:../index.html?erreur=login"); // redirection en cas d'echec
}
if (isset($_REQUEST['d1']) && isset($_REQUEST['p'])&& isset($_REQUEST['u']) && isset($_REQUEST['d2'])) {
    $date1 = $_REQUEST['d1'];
    $prod = $_REQUEST['p'];
    // $dre=	$_REQUEST['v'];
    $agence = $_REQUEST['u'];//cod agence
    $date2 = $_REQUEST['d2'];
    $datesys = date("Y/m/d");
    include("convert.php");
    require('tfpdf.php');

    class PDF extends TFPDF
    {
// En-t?te
        function Header()
        {
            $this->SetFont('Arial', 'B', 10);
            $this->Image('../img/entete_bna.png', 6, 4, 390);
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

    $rqtuser = $bdd->prepare("select * from utilisateurs where id_user='$user'");
    $rqtuser->execute();
    $nom_user = "";
    $pnom_user = "";
    while ($rsu = $rqtuser->fetch()) {
        $nom_user = $rsu['nom'];
        $pnom_user = $rsu['prenom'];
        $dre=$rsu['agence'];

    }



// Instanciation de la classe derivee
    $pdf = new PDF('L', 'mm', 'A3');
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetFillColor(199, 139, 85);
    $pdf->SetFont('Arial', 'B', 15);
    //requete


// Instanciation de la classe derivee
    $pdf->Cell(380, 10, 'Etat récapitulatif de la production ', '', '1', 'C');
    $pdf->Cell(380, 10, 'Période du:       ' . date("d/m/Y", strtotime($date1)) . '            au:     ' . date("d/m/Y", strtotime($date2)), '', '1', 'C');
    $pdf->Cell(380, 10, 'Document généré Par: ' . $nom_user . ' ' . $pnom_user, '', '1', 'R');
    if ($agence == '0') {
        $pdf->Cell(210, 10, 'Etat global:Direction Regionale N°' . $dre, '1', '0', 'L');
        $pdf->Cell(180, 10, 'Produit: Tous les produits ', '1', '0', 'L');
    } else {
            $pdf->Cell(210, 10, 'Etat global: Agence N° ' . $agence, '1', '0', 'L');
            $pdf->Cell(180, 10, 'Produit: Tous les produits ', '1', '0', 'L');

    }
    $pdf->Ln(15);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(40, 10, 'Agences', '1', '0', 'C');
    $pdf->Cell(50, 10, 'Production', '1', '0', 'C');
    $pdf->Cell(20, 10, 'Nombre', '1', '0', 'C');
    $pdf->Cell(60, 10, 'P.Nette', '1', '0', 'C');
    $pdf->Cell(40, 10, 'Accessoire', '1', '0', 'C');
    $pdf->Cell(60, 10, 'P.Commer', '1', '0', 'C');
    $pdf->Cell(40, 10, 'D.T', '1', '0', 'C');
    $pdf->Cell(80, 10, 'P.Total', '1', '0', 'C');
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 10);
    $pdf->SetFillColor(199, 139, 85);

    //declaration des variables
    //production positive
    $prime_nette_positive_pi = 0;
    $nb_act_positive_pi = 0;
    $Accessoire_positive_pi = 0;
    $prime_commerciale_positive_pi = 0;
    $dt_positifs_pi = 0;
    $prime_total_positive_pi = 0;
    $reglement_total_positif_pi = 0;
    $solde_total_positif_pi = 0;
    $solde_total_positif_pi2 = 0;
    //production avec ristourne
    $prime_nette_ar_pi = 0;
    $nb_act_ar_pi = 0;
    $Accessoire_ar_pi = 0;
    $prime_commerciale_ar_pi = 0;
    $dt_ar_pi = 0;
    $prime_total_ar_pi = 0;
    $reglement_total_ar_pi = 0;
    $solde_total_ar_pi = 0;
    $solde_total_ar_pi2 = 0;
    //production sans ristourne.
    $prime_nette_sr_pi = 0;
    $nb_act_sr_pi = 0;
    $Accessoire_sr_pi = 0;
    $prime_commerciale_sr_pi = 0;
    $dt_sr_pi = 0;
    $prime_total_sr_pi = 0;
    $reglement_total_sr_pi = 0;
    $solde_total_sr_pi = 0;
    $solde_total_sr_pi2 = 0;
    //total production produit pi
    $prime_nette_pi = 0;
    $nb_act_pi = 0;
    $Accessoire_pi = 0;
    $prime_commerciale_pi = 0;
    $dt_pi = 0;
    $prime_total_pi = 0;
    $reglement_total_pi = 0;
    $solde_total_pi = 0;
    $solde_total_pi2 = 0;
    //total general
    //total production produit pi
    $prime_nette = 0;
    $nb_act = 0;
    $Accessoire = 0;
    $prime_commerciale = 0;
    $dt = 0;
    $prime_total = 0;
    $reglement_total = 0;
    $solde_total = 0;
    $solde_total2 = 0;
    $ag='';

    if ($agence == '0') {
        $rqt_prod=$bdd->prepare("SELECT distinct agence FROM `utilisateurs` WHERE `type_user`='user' and  `id_par`='$user'order by agence; ");
        $rqt_prod->execute();

    }
    else {
            $rqt_prod=$bdd->prepare("SELECT distinct agence FROM `utilisateurs` WHERE `type_user`='user' and  `agence`='$agence'order by agence; ");
            $rqt_prod->execute();
        }

    while ($rowp= $rqt_prod->fetch()) {
        $ag=$rowp['agence'];
        $rqtpos = $bdd->prepare("select t.agence,case when t.nb is null  then 0 else t.nb end as nb,case when t.prime_nette is null  then 0 else t.prime_nette end as prime_nette,
			                          case when t.cout_police is null  then 0 else t.cout_police end as cout_police ,case when t.prime_com is null  then 0 else t.prime_com end as prime_com,
                                      case when t.droit_timbre is null  then 0 else t.droit_timbre end as droit_timbre,case when t.prime_totale is null  then 0 else t.prime_totale end  as prime_totale,
                                      case when t.Reglement is null  then 0 else t.Reglement end as Reglement,case when t.Soldep is null then 0 else t.Soldep end as Soldep,case when t.Solden is null  then 0 else t.Solden end as Solden

                                  from (select table1.agence as agence,count(cod_doc) as nb, sum(prime_nette) as prime_nette, sum(cout_police) as cout_police,sum(prime_com)as prime_com, sum(droit_timbre) as droit_timbre,
                                                sum(prime_totale) as prime_totale, sum(reglement) as Reglement,sum(soldep) as Soldep,sum(solden) as Solden
                                        from (select p.sequence as cod_doc, p.dat_val as dat_val,p.ndat_eff as ndat_eff,p.ndat_ech as ndat_ech, p.pn as prime_nette, c.mtt_cpl as cout_police,p.pn+c.mtt_cpl as prime_com,
                                                    d.mtt_dt as droit_timbre, p.pt as prime_totale,p.mtt_reg as reglement,p.mtt_solde as soldep,0 as solden ,u.agence as agence
                                              from policew as p, dtimbre as d,cpolice as c ,souscripteurw as s,utilisateurs as u
                                              where DATE_FORMAT(p.`dat_val`,'%Y-%m-%d') between '$date1' and '$date2'  and p.cod_dt=d.cod_dt and p.cod_cpl=c.cod_cpl
                                                    and p.cod_sous=s.cod_sous and s.id_user=u.id_user and u.agence='$ag'

                                              union

                                              select v.sequence as cod_doc,p.dat_val as dat_val,p.ndat_eff as ndat_eff,p.ndat_ech as ndat_ech,v.pn as prime_nette, c.mtt_cpl as cout_police,v.pn+c.mtt_cpl as prime_com,
                                                         d.mtt_dt as droit_timbre, v.pt as prime_totale,v.mtt_reg as reglement,v.mtt_solde as soldep,0 as solden,u.agence as agence
                                              from avenantw as v,policew as p, dtimbre as d,cpolice as c ,souscripteurw as s,utilisateurs as u
                                              where DATE_FORMAT(v.`dat_val`,'%Y-%m-%d') between '$date1' and '$date2'  and v.cod_dt=d.cod_dt and v.cod_cpl=c.cod_cpl
                                                    and p.cod_sous=s.cod_sous and s.id_user=u.id_user and u.agence='$ag' and v.cod_pol= p.cod_pol
                                                    and v.lib_mpay not in ('30','50')
                                            ) as table1
                                        ) as t");
        $rqtpos->execute();

        $rqtrist = $bdd->prepare("select t.agence,case when t.nb is null  then 0 else t.nb end as nb,case when t.prime_nette is null  then 0 else t.prime_nette end as prime_nette,
                                            case when t.cout_police is null  then 0 else t.cout_police end as cout_police ,case when t.prime_com is null  then 0 else t.prime_com end as prime_com,
                                            case when t.droit_timbre is null  then 0 else t.droit_timbre end as droit_timbre, case when t.prime_totale is null  then 0 else t.prime_totale end  as prime_totale,
                                            case when t.Reglement is null  then 0 else t.Reglement end as Reglement,case when t.Soldep is null then 0 else t.Soldep end as Soldep,
                                            case when t.Solden is null  then 0 else t.Solden end as Solden
                                  from (select table1.agence as agence,count(cod_doc) as nb, sum(prime_nette) as prime_nette, sum(cout_police) as cout_police,sum(prime_com)as prime_com, sum(droit_timbre) as droit_timbre,
                                                sum(prime_totale) as prime_totale, sum(reglement) as Reglement,sum(soldep) as Soldep,sum(solden) as Solden
                                        from (select v.sequence as cod_doc,p.dat_val as dat_val,p.ndat_eff as ndat_eff,p.ndat_ech as ndat_ech,v.pn as prime_nette, c.mtt_cpl as cout_police,v.pn+c.mtt_cpl as prime_com,
                                                    d.mtt_dt as droit_timbre, v.pt as prime_totale,v.mtt_reg as reglement,0 as soldep,v.mtt_solde as solden,u.agence as agence
                                              from avenantw as v,policew as p, dtimbre as d,cpolice as c ,souscripteurw as s,utilisateurs as u
                                              where DATE_FORMAT(v.`dat_val`,'%Y-%m-%d') between '$date1' and '$date2'  and v.cod_dt=d.cod_dt and v.cod_cpl=c.cod_cpl
                                                    and p.cod_sous=s.cod_sous and s.id_user=u.id_user and u.agence='$ag' and v.cod_pol= p.cod_pol
                                                    and v.lib_mpay  in ('30')
                                              ) as table1
                                        ) as t");
        $rqtrist->execute();

        $rqt_s_rist = $bdd->prepare("select t.agence,case when t.nb is null  then 0 else t.nb end as nb,case when t.prime_nette is null  then 0 else t.prime_nette end as prime_nette,
                                            case when t.cout_police is null  then 0 else t.cout_police end as cout_police ,case when t.prime_com is null  then 0 else t.prime_com end as prime_com,
                                            case when t.droit_timbre is null  then 0 else t.droit_timbre end as droit_timbre,case when t.prime_totale is null  then 0 else t.prime_totale end  as prime_totale,
                                            case when t.Reglement is null  then 0 else t.Reglement end as Reglement,case when t.Soldep is null then 0 else t.Soldep end as Soldep,
                                            case when t.Solden is null  then 0 else t.Solden end as Solden
                                      from (select table1.agence as agence,count(cod_doc) as nb, sum(prime_nette) as prime_nette, sum(cout_police) as cout_police,sum(prime_com)as prime_com, sum(droit_timbre) as droit_timbre,
                                                      sum(prime_totale) as prime_totale, sum(reglement) as Reglement,sum(soldep) as Soldep,sum(solden) as Solden
                                            from (select v.sequence as cod_doc,p.dat_val as dat_val,p.ndat_eff as ndat_eff,p.ndat_ech as ndat_ech,v.pn as prime_nette, c.mtt_cpl as cout_police,v.pn+c.mtt_cpl as prime_com,
                                                          d.mtt_dt as droit_timbre, v.pt as prime_totale,v.mtt_reg as reglement,0 as soldep,v.mtt_solde as solden,u.agence as agence
                                                  from avenantw as v,policew as p, dtimbre as d,cpolice as c ,souscripteurw as s,utilisateurs as u
                                                  where DATE_FORMAT(v.`dat_val`,'%Y-%m-%d') between '$date1' and '$date2'  and v.cod_dt=d.cod_dt and v.cod_cpl=c.cod_cpl
                                                        and p.cod_sous=s.cod_sous and s.id_user=u.id_user and u.agence='$ag' and v.cod_pol= p.cod_pol
                                                        and v.lib_mpay  in ('50')
                                                ) as table1) as t");
        $rqt_s_rist->execute();



        while($rows_p_pi=$rqtpos->fetch())
        {
            $prime_nette_positive_pi=$rows_p_pi['prime_nette'];
            $nb_act_positive_pi=$rows_p_pi['nb'];
            $Accessoire_positive_pi=$rows_p_pi['cout_police'];
            $prime_commerciale_positive_pi=$rows_p_pi['prime_com'];
            $dt_positifs_pi=$rows_p_pi['droit_timbre'];
            $prime_total_positive_pi=$rows_p_pi['prime_totale'];
            $reglement_total_positif_pi=$rows_p_pi['Reglement'];
            $solde_total_positif_pi=$rows_p_pi['Soldep'];
            $solde_total_positif_pi2=$rows_p_pi['Solden'];
        }

        while($rows_ar_pi=$rqtrist->fetch())
        {
            $prime_nette_ar_pi=$rows_ar_pi['prime_nette'];
            $nb_act_ar_pi=$rows_ar_pi['nb'];
            $Accessoire_ar_pi=$rows_ar_pi['cout_police'];
            $prime_commerciale_ar_pi=$rows_ar_pi['prime_com'];
            $dt_ar_pi=$rows_ar_pi['droit_timbre'];
            $prime_total_ar_pi=$rows_ar_pi['prime_totale'];
            $reglement_total_ar_pi=-$rows_ar_pi['Reglement'];
            $solde_total_ar_pi=$rows_ar_pi['Soldep'];
            $solde_total_ar_pi2=$rows_ar_pi['Solden'];
        }

        while($rows_sr_pi=$rqt_s_rist->fetch())
        {
            $prime_nette_sr_pi=$rows_sr_pi['prime_nette'];
            $nb_act_sr_pi=$rows_sr_pi['nb'];
            $Accessoire_sr_pi=$rows_sr_pi['cout_police'];
            $prime_commerciale_sr_pi=$rows_sr_pi['prime_com'];
            $dt_sr_pi=$rows_sr_pi['droit_timbre'];
            $prime_total_sr_pi=$rows_sr_pi['prime_totale'];
            $reglement_total_sr_pi=-$rows_sr_pi['Reglement'];
            $solde_total_sr_pi=$rows_sr_pi['Soldep'];
            $solde_total_sr_pi2=$rows_sr_pi['Solden'];
        }

        $prime_nette_pi=$prime_nette_positive_pi+$prime_nette_ar_pi+$prime_nette_sr_pi;
        $nb_act_pi=$nb_act_positive_pi+$nb_act_ar_pi+$nb_act_sr_pi;
        $Accessoire_pi=$Accessoire_positive_pi+$Accessoire_ar_pi+$Accessoire_sr_pi;
        $prime_commerciale_pi=$prime_commerciale_positive_pi+$prime_commerciale_ar_pi+$prime_commerciale_sr_pi;
        $dt_pi=$dt_positifs_pi+$dt_ar_pi+$dt_sr_pi;
        $prime_total_pi=$prime_total_positive_pi+$prime_total_ar_pi+$prime_total_sr_pi;
        $reglement_total_pi=$reglement_total_positif_pi+$reglement_total_ar_pi+$reglement_total_sr_pi;
        $solde_total_pi=$solde_total_positif_pi+$solde_total_ar_pi+$solde_total_sr_pi;
        $solde_total_pi2=$solde_total_positif_pi2+$solde_total_ar_pi2+$solde_total_sr_pi2;
        $prime_nette +=  $prime_nette_pi;
        $nb_act+= $nb_act_pi;
        $Accessoire+=  $Accessoire_pi;
        $prime_commerciale+= $prime_commerciale_pi;
        $dt+=$dt_pi;
        $prime_total+= $prime_total_pi;
        $reglement_total=$reglement_total+$reglement_total_pi;
        $solde_total=$solde_total+$solde_total_pi;
        $solde_total2=$solde_total2+$solde_total_pi2;


        $pdf->SetFont('Arial','',10);
        $pdf->SetFillColor(199,139,85);

        $pdf->Cell(40,24,''.$ag,'1','0','C');
        $pdf->Cell(50,6,'Production(+)','1','0','C');
        $pdf->Cell(20,6,''.$nb_act_positive_pi,'1','0','C');
        $pdf->Cell(60,6,''.number_format($prime_nette_positive_pi, 2,',',' ').'','1','0','R');
        $pdf->Cell(40,6,''.number_format($Accessoire_positive_pi, 2,',',' ').'','1','0','R');
        $pdf->Cell(60,6,''.number_format($prime_commerciale_positive_pi, 2,',',' ').'','1','0','R');
        $pdf->Cell(40,6,''.number_format($dt_positifs_pi, 2,',',' ').'','1','0','R');
        $pdf->Cell(80,6,''.number_format($prime_total_positive_pi, 2,',',' ').'','1','0','R');

        $pdf->Ln(6);

        $X=$pdf->GetX();
        $pdf->SetX($X+40);

        $pdf->Cell(50,6,'Avenant (-) Avec ristourne','1','0','C');
        $pdf->Cell(20,6,''.$nb_act_ar_pi,'1','0','C');
        $pdf->Cell(60,6,''.number_format($prime_nette_ar_pi, 2,',',' ').'','1','0','R');
        $pdf->Cell(40,6,''.number_format($Accessoire_ar_pi, 2,',',' ').'','1','0','R');
        $pdf->Cell(60,6,''.number_format($prime_commerciale_ar_pi, 2,',',' ').'','1','0','R');
        $pdf->Cell(40,6,''.number_format($dt_ar_pi, 2,',',' ').'','1','0','R');
        $pdf->Cell(80,6,''.number_format($prime_total_ar_pi, 2,',',' ').'','1','0','R');


        $pdf->Ln(6);
        $X=$pdf->GetX();
        $pdf->SetX($X+40);
        $pdf->Cell(50,6,'Avenant (-) sans ristourne','1','0','C');
        $pdf->Cell(20,6,''.$nb_act_sr_pi,'1','0','C');
        $pdf->Cell(60,6,''.number_format($prime_nette_sr_pi, 2,',',' ').'','1','0','R');
        $pdf->Cell(40,6,''.number_format($Accessoire_sr_pi, 2,',',' ').'','1','0','R');
        $pdf->Cell(60,6,''.number_format($prime_commerciale_sr_pi, 2,',',' ').'','1','0','R');
        $pdf->Cell(40,6,''.number_format($dt_sr_pi, 2,',',' ').'','1','0','R');
        $pdf->Cell(80,6,''.number_format($prime_total_sr_pi, 2,',',' ').'','1','0','R');


        $pdf->Ln(6);
        $X=$pdf->GetX();
        $pdf->SetX($X+40);
        $pdf->SetFont('Arial','B',12);
        $pdf->SetFillColor(199,139,85);
        $pdf->Cell(50,6,'Total','1','0','C');
        $pdf->Cell(20,6,'','1','0','C');
        $pdf->Cell(60,6,''.number_format($prime_nette_pi, 2,',',' ').'','1','0','R');
        $pdf->Cell(40,6,''.number_format($Accessoire_pi, 2,',',' ').'','1','0','R');
        $pdf->Cell(60,6,''.number_format($prime_commerciale_pi, 2,',',' ').'','1','0','R');
        $pdf->Cell(40,6,''.number_format($dt_pi, 2,',',' ').'','1','0','R');
        $pdf->Cell(80,6,''.number_format($prime_total_pi, 2,',',' ').'','1','0','R');



        $pdf->Ln(6);

        //production positive
        $prime_nette_positive_pi=0;
        $nb_act_positive_pi=0;
        $Accessoire_positive_pi=0;
        $prime_commerciale_positive_pi=0;
        $dt_positifs_pi=0;
        $prime_total_positive_pi=0;
        $reglement_total_positif_pi=0;
        $solde_total_positif_pi=0;
        $solde_total_positif_pi2=0;
        //production avec ristourne
        $prime_nette_ar_pi=0;
        $nb_act_ar_pi=0;
        $Accessoire_ar_pi=0;
        $prime_commerciale_ar_pi=0;
        $dt_ar_pi=0;
        $prime_total_ar_pi=0;
        $reglement_total_ar_pi=0;
        $solde_total_ar_pi=0;
        $solde_total_ar_pi2=0;
        //production sans ristourne.
        $prime_nette_sr_pi=0;
        $nb_act_sr_pi=0;
        $Accessoire_sr_pi=0;
        $prime_commerciale_sr_pi=0;
        $dt_sr_pi=0;
        $prime_total_sr_pi=0;
        $reglement_total_sr_pi=0;
        $solde_total_sr_pi=0;
        $solde_total_sr_pi2=0;
        //total production produit pi
        $prime_nette_pi=0;
        $nb_act_pi=0;
        $Accessoire_pi=0;
        $prime_commerciale_pi=0;
        $dt_pi=0;
        $prime_total_pi=0;
        $reglement_total_pi=0;
        $solde_total_pi=0;
        $solde_total_pi2=0;

    }
    $pdf->SetFont('Arial','B',12);
    $pdf->SetFillColor(199,139,85);
    $pdf->Cell(90,10,'Total','1','0','C');
    $pdf->Cell(20,10,'','1','0','C');
    $pdf->Cell(60,10,''.number_format($prime_nette, 2,',',' ').'','1','0','R');
    $pdf->Cell(40,10,''.number_format($Accessoire, 2,',',' ').'','1','0','R');
    $pdf->Cell(60,10,''.number_format($prime_commerciale, 2,',',' ').'','1','0','R');
    $pdf->Cell(40,10,''.number_format($dt, 2,',',' ').'','1','0','R');
    $pdf->Cell(80,10,''.number_format($prime_total, 2,',',' ').'','1','0','R');


    $pdf->Ln(20);

    $pdf->SetFont('Arial','B',14);
    $pdf->SetFillColor(199,139,85);
    $pdf->Cell(320,10,"Aglic Le:    ".date("d/m/Y", strtotime($datesys)) ,'','0','R');  $pdf->Ln(10);
    $pdf->Cell(390,10,"Cachet et signature " ,'','0','R');$pdf->Ln(10);
    $pdf->SetX(290);
    $pdf->Output();
}
?>