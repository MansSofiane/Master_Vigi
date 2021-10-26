
<?php
session_start();
require_once("../../../../data/conn4.php");
//on recupere le code du pays
if ( isset($_REQUEST['c'])  && isset($_REQUEST['dat1'])  && isset($_REQUEST['dat2'])  && isset($_REQUEST['u']) ) {
    $mode_facture = $_REQUEST['c'];
    $datdeb = $_REQUEST['dat1'];
    $datfin = $_REQUEST['dat2'];
    $user = $_REQUEST['u'];
    $ag2 = $_REQUEST['ag2'];
    $datesys = date("y-m-d H:i:s");
    $cre=$_REQUEST['cre'];
    if ($ag2 != '0') {
        $condition_police = " and p.cod_agence='$ag2 ' and p.cod_prod='9' ";// facture de commission via courtier uniquement produit groupe.


    } else
        $condition_police = "";

    try {
        $bdd->beginTransaction();

        $requete ="select L.cod_prod,L.code_prod,L.lib_prod,L.agence,L.Taux,L.recouverement,case when M.Ristourne is null then 0 else M.Ristourne end Ristourne from
(
select t.cod_prod,pr.code_prod,pr.lib_prod,t.agence,t.sens,t.Taux,sum(base_primcom_commission) as recouverement ,sum(com_agence_direct),sum(com_cash_via_courtier)

from (
SELECT  a.id_avis,  a.`cod_ref`,  a.`cod_av`,  a.`dat_avis`,  a.`mtt_avis`,  a.`mtt_solde`,  a.`id_user`,
  a.`type_avis`,  a.`sens_avis` as sens,  a.`id_facture`,

a.base_primcom_commission,
p.pn as prime_nette,
p.sequence,
p.cod_prod,
case when ('$ag2'!='0') then p.taux_com_agence else p.taux_com_agence+p.taux_com_courtier end as Taux,
p.taux_com_agence,
p.taux_com_courtier,
case when (p.cod_agence=0 || typ_agence='1')  then  'Affaire direct' else 'Affaire via courtier' end as agence,


 a.base_primcom_commission*p.taux_com_agence/100 as com_agence_direct,
a.base_primcom_commission*(p.taux_com_agence+p.taux_com_courtier)/100 as com_cash_via_courtier



FROM `avis_recette` as a,policew as p, agence g WHERE

a.dat_avis  >='$datdeb'  AND a.dat_avis<='$datfin'
and p.cod_agence=g.cod_agence
and a.sens_avis=0
and a.id_user in (select id_user from utilisateurs where id_par='$user')
$condition_police
and id_facture=0

and a.cod_ref=p.cod_pol) t


left join produit as pr  on t.cod_prod=pr.cod_prod

group by t.cod_prod,t.agence) L

left join (
select t.cod_prod,pr.code_prod,pr.lib_prod,t.agence,t.sens,t.Taux,sum(base_rest_commerciale) Ristourne,sum(com_agence_direct_aristourne),sum(com_cash_via_courtier_aristourne)

from (
SELECT  a.id_avis,  a.`cod_ref`,  a.`cod_av`,  a.`dat_avis`,  a.`mtt_avis`,  a.`mtt_solde`,  a.`id_user`,
  a.`type_avis`,  a.`sens_avis` as sens,  a.`id_facture`,

a.base_primcom_commission base_rest_commerciale,
p.pn as prime_nette_ristourne,
p.sequence,
p.cod_prod,
case when ('$ag2'!='0') then p.taux_com_agence else p.taux_com_agence+p.taux_com_courtier end as Taux,
p.taux_com_agence,
p.taux_com_courtier,
case when (p.cod_agence=0 || typ_agence='1')  then  'Affaire direct' else 'Affaire via courtier' end as agence,


 a.base_primcom_commission*p.taux_com_agence/100 as com_agence_direct_aristourne,
a.base_primcom_commission*(p.taux_com_agence+p.taux_com_courtier)/100 as com_cash_via_courtier_aristourne



FROM `avis_recette` as a,policew as p,agence as g WHERE

a.dat_avis  >='$datdeb'  AND a.dat_avis<='$datfin'

and p.cod_agence=g.cod_agence
and a.sens_avis=1
and a.id_user in (select id_user from utilisateurs where id_par='$user')
$condition_police
and id_facture=0

and a.cod_ref=p.cod_pol
) t


left join produit as pr  on t.cod_prod=pr.cod_prod

group by t.cod_prod,t.agence ) M

ON L.cod_prod=M.cod_prod and L.agence=M.agence";


        $rqtselect=$bdd->prepare($requete);
        $rqtselect->execute();

        $nb=$rqtselect->rowCount();//la requete n'est pas vide

        if($nb>0)
        {
            $selectseqf=$bdd->prepare ("select distinct sequence_fact from sequence_ag where cod_agence in (select agence from utilisateurs where id_user='$user') ");
            $selectseqf->execute();
            $seqfact=0;
            while ($rwag=$selectseqf->fetch())
            {
                $seqfact=$rwag["sequence_fact"];
            }
            $seqfact++;
            echo ''.$seqfact;

            $requete="INSERT INTO `facture`( `sequence`, `dat_facture`, `dat_deb`, `dat_fin`, `mtt_net`, `taux_tva`, `TVA`, `mtt_ttc`, `id_user`, `cod_agence`, `type_facture`,`cree_par`)
                                                        VALUES ('$seqfact','$datesys','$datdeb','$datfin','0','0','0','0','$user','$ag2','$mode_facture','$cre')";

            $reqtinsert_fact=$bdd->prepare($requete);
            $reqtinsert_fact->execute();

            $rqtidf=$bdd->prepare("select id_facture from facture where sequence='$seqfact'  and id_user='$user'");
            $rqtidf->execute();
            $id_f="";
            $mtt_net=0;
            $taux_tva=0.09;
            $TVA=0;
            $mtt_ttc=0;
            while ($rw=$rqtidf->fetch())
            {
                $id_f=$rw['id_facture'];

            }

                 if($id_f!="")
                 {

                      while ($r = $rqtselect->fetch())
                      {
                          $cod_prod=$r['cod_prod'];
                          $type_ligne=$r['agence'];
                         $mtt_encaissement=$r['recouverement'];
                          $mtt_decaissement=$r['Ristourne'];
                          $taux_com=$r['Taux'];
                          $mtt_com_net=($mtt_encaissement-$mtt_decaissement)*$taux_com/100;
                          $mtt_net+=$mtt_com_net;


                           $requete = "INSERT INTO `ligne_facture`( `id_facture`, `cod_prod`, `type_ligne`, `dat_ligne`, `dat_deb`, `dat_fin`, `mtt_encaissement`, `mtt_decaissement`, `taux_com`, `mtt_com_net`, `id_user`, `cod_agence`)
                                                                 VALUES ('$id_f','$cod_prod','$type_ligne','$datesys','$datdeb','$datfin','$mtt_encaissement','$mtt_decaissement','$taux_com','$mtt_com_net','$user','$ag2')";


                          $rqtinsert_line=$bdd->prepare($requete);
                          $rqtinsert_line->execute();

                      }
                     $TVA=$mtt_net*$taux_tva;
                     $mtt_ttc=$mtt_net+$TVA;
                     $requete="UPDATE `facture` SET mtt_net='$mtt_net',taux_tva='$taux_tva',TVA='$TVA',mtt_ttc='$mtt_ttc' where `id_facture`='$id_f'";

                     $rqtupdate=$bdd->prepare($requete);
                     $rqtupdate->execute();

                     //mise a jour avis de recette


                     $requete="UPDATE `avis_recette` as b inner join    (SELECT distinct a.`id_avis`  FROM `avis_recette` as a,policew as p, agence as g
                                                           WHERE a.dat_avis >='$datdeb'
                                                          AND a.dat_avis<='$datfin'
                                                         and p.cod_agence=g.cod_agence
                                                        and a.id_user in (select u.id_user
                                                                          from utilisateurs as u
                                                                          where u.id_par='$user')
                                                       $condition_police
                                                       and a.id_facture=0
                                                       and a.cod_ref=p.cod_pol) t

                               on b.id_avis=t.id_avis

                               set b.`id_facture`='$id_f'; ";

                     echo $requete;

                     $rqtupd_avis=$bdd->prepare($requete);
                     $rqtupd_avis->execute();

                    $requete="update sequence_ag set   sequence_fact='$seqfact' where cod_agence in (select agence from utilisateurs where id_user='$user') ";
                     $selectseqf=$bdd->prepare($requete);
                     $selectseqf->execute();

                     echo $requete;

                 }

        }
        else
        {
            echo "aucune facture n' a été générée";
        }
        $bdd->commit();

    }catch (Exception $ex)
    {
        $ex->getmessage();

        $bdd->rollBack();
    }

}
?>


