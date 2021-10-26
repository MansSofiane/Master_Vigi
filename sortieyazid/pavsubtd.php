<?php
session_start();
if ($_SESSION['login']){
//authentification acceptee !!!

}
else {
    header("Location:../index.html?erreur=login"); // redirection en cas d'echec
}
require_once("../../../data/conn4.php");
include("convert.php");
$a1 = new chiffreEnLettre();
$errone = false;
require('tfpdf.php');
if (isset($_REQUEST['warda'])) {$row = substr($_REQUEST['warda'],10);}
class PDF extends TFPDF { // En-t?te
    function Header()
    {
        $this->SetFont('Arial','B',10);
        $this->Image('../img/entete_bna.png',6,4,190);
        $this->Cell(150,5,'','O','0','L');
        $this->SetFont('Arial','B',12);
        // $this->Cell(60,5,'MAPFRE | Assistance','O','0','L');
        $this->SetFont('Arial','B',10);
        $this->Ln(8);
    }

    function Footer()
    {
        // Positionnement ? 1,5 cm du bas
        $this->SetY(-15);
        // Police Arial italique 8
        $this->SetFont('Arial','I',6);
        // Num?ro de page
        $this->Cell(0,8,'Page '.$this->PageNo().'/{nb}',0,0,'C');$this->Ln(3);
        $this->Cell(0,8,"Algerian Gulf Life Insurance Company, SPA au capital social de 1.000.000.000 de dinars alg�riens, 01 Rue Tripoli, Hussein Dey Alger,  ",0,0,'C');
        $this->Ln(2);
        $this->Cell(0,8,"RC : 16/00-1009727 B 15   NIF : 001516100972762-NIS :0015160900296000",0,0,'C');
        $this->Ln(2);
        $this->Cell(0,8,"Tel : +213 (0) 21 77 30 12/14/15 Fax : +213 (0) 21 77 29 56 Site Web : www.aglic.dz  ",0,0,'C');
    }
    function RotatedText($x,$y,$txt,$angle)
    {
        //Text rotated around its origin
        $this->Rotate($angle,$x,$y);
        $this->Text($x,$y,$txt);
        $this->Rotate(0);
    }

}
//Preparation du PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetFont('Arial','B',12);
//Requete generale
$rqtg=$bdd->prepare("SELECT d.*,t.`mtt_dt`,c.`mtt_cpl`,o.`lib_opt`,p.`code_prod`, s.`cod_sous`, s.`nom_sous`, s.`pnom_sous`, s.`mail_sous`, s.`tel_sous`, s.`adr_sous`, s.`rp_sous`,s.`dnais_sous`,s.`age`,s.id_user,z.sequence as seq2, z.dat_val as datev  ,b.`nom_benef`, b.`ag_benef`,b.`tel_benef`,b.`adr_benef`
FROM `avenantw` as d,`policew` as z, `dtimbre` as t , `cpolice` as c,`option` as o,`produit` as p,`souscripteurw` as s, `beneficiaire` as b
WHERE d.`cod_dt`=t.`cod_dt` AND d.`cod_cpl`=c.`cod_cpl` AND d.`cod_opt`=o.`cod_opt` AND d.`cod_prod`=p.`cod_prod` AND s.`cod_sous`=b.`cod_sous`
AND d.`cod_pol`=z.`cod_pol` AND z.`cod_sous`=s.`cod_sous` AND d.`cod_av`='$row'");
$rqtg->execute();

while ($row_g=$rqtg->fetch()){
//$pdf->Ln(2);
    $pdf->SetFillColor(205,205,205);
    $pdf->Cell(190,6,'Assurance Temporaire Au D�c�s','0','0','C');$pdf->Ln();
    $user_creat=$row_g['id_user'];
    $dat_eff="";$dat_ech="";
    $agence="";
    $adr_agence="";
    $tel_agence="";
    $cod_prod="";
    $annee="";
    $sequence_pol="";
    $nom_sous="";
    $pnom_sous="";
    $tel_sous="";
    $adr_sous="";
    $mail_sous="";
    $fax_sous="";
    $lib_mpay="";
    $rqtu = $bdd->prepare("select * from utilisateurs where  id_user ='$user_creat';");
    $rqtu->execute();
    while ($row_user=$rqtu->fetch()){
        $pdf->Cell(190,6,"Avenant de subrogation",'0','0','L');$pdf->Ln();
        $pdf->Cell(190,6,'Avenant N� '.$row_user['agence'].'.'.substr($row_g['dat_val'],0,4).'.'.$row_g['lib_mpay'].'.'.$row_g['code_prod'].'.'.str_pad((int) $row_g['sequence'],'5',"0",STR_PAD_LEFT).'','0','0','L');$pdf->Ln();
        $pdf->Cell(190,6,'Police N� '.$row_user['agence'].'.'.substr($row_g['datev'],0,4).'.10.'.$row_g['code_prod'].'.'.str_pad((int) $row_g['seq2'],'5',"0",STR_PAD_LEFT).'','0','0','L');$pdf->Ln();
        $pdf->SetFont('Arial','I',6);
        $pdf->MultiCell(190,3,"Le pr�sente avenant est r�gi tant par l�ordonnance N� 75-58 du 26 septembre 1975 portant code civil modifi�e et compl�t�e et par l�ordonnance N� 95-07 du 25 janvier 1995 relative aux assurances modifi�e et compl�t�e par la loi N� 06-04 du 20 f�vrier 2006 que par le d�cret ex�cutif N� 02-293 du 10 septembre 2002 modifiant et compl�tant le d�cret ex�cutif N� 95-338 du 30 octobre 1995 relatif � l��tablissement et � la codification des op�rations d�assurance",0,'J',false);$pdf->Ln(2);
        //$pdf->Cell(190,8,'Devis Gratuit','0','0','C');$pdf->Ln();$pdf->Ln();
        $pdf->SetFont('Arial','B',14);
        $pdf->SetFillColor(7,27,81);
        $pdf->SetTextColor(255,255,255);
//Le R�seau
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(190,5,"Agence",'1','1','C','1');
        $pdf->SetFillColor(255,255,255);
        $pdf->SetFont('Arial','B',8);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetFillColor(221,221,221);
        $adr=$row_user['adr_user'];
        $pdf->Cell(40,4,'Code','1','0','L','1');$pdf->Cell(55,4,"".$row_user['agence']."",'1','0','C');
        $pdf->Cell(40,4,'Adresse','1','0','L','1');$pdf->Cell(55,4,"".$row_user['adr_user']."",'1','0','C');$pdf->Ln();
        $pdf->Cell(40,4,'T�l�phone','1','0','L','1');$pdf->Cell(55,4,"".$row_user['tel_user']."",'1','0','C');
        $pdf->Cell(40,4,'E-mail','1','0','L','1');$pdf->Cell(55,4,"".$row_user['mail_user']."",'1','0','C');$pdf->Ln();
        $agence=$row_user['agence'];
        $adr_agence=$row_user['adr_user'];
        $tel_agence=$row_user['tel_user'];
        $cod_prod=$row_g['code_prod'];
        $annee=substr($row_g['dat_val'],0,4);
        $sequence_pol=str_pad((int) $row_g['sequence'],'5',"0",STR_PAD_LEFT);
        $lib_mpay=$row_g['lib_mpay'];

        $cout_police=$row_g['mtt_cpl'];
        $droit_timbre=$row_g['mtt_dt'];
    }
// debut du traitement de la requete generale
// Le Souscripteur
    $pdf->SetFillColor(199,139,85);
    $pdf->SetFont('Arial','B',10);
    $pdf->Ln(3);
    $pdf->Cell(190,5,'Souscripteur ','1','1','C','1');
    $pdf->SetFillColor(221,221,221);
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(40,4,'Nom et Pr�nom','1','0','L','1');
    $pdf->Cell(150,4,"".$row_g['nom_sous']." ".$row_g['pnom_sous']."",'1','0','C');$pdf->Ln();
    $pdf->Cell(40,4,'Adresse','1','0','L','1');$pdf->Cell(150,4,"".$row_g['adr_sous']."",'1','0','C');$pdf->Ln();
    $pdf->Cell(40,4,'T�l�phone','1','0','L','1');$pdf->Cell(55,4,"".$row_g['tel_sous']."",'1','0','C');
    $pdf->Cell(40,4,'E-mail','1','0','L','1');$pdf->Cell(55,4,"".$row_g['mail_sous']."",'1','0','C');$pdf->Ln();
    $nom_sous=$row_g['nom_sous'];
    $pnom_sous=$row_g['pnom_sous'];
    $tel_sous=$row_g['tel_sous'];
    $adr_sous=$row_g['adr_sous'];
    $mail_sous=$row_g['mail_sous'];
    $pdf->Ln(2);
// L'assur�
    $pdf->SetFillColor(7,27,81);
    $pdf->SetTextColor(255,255,255);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(190,5,'Assur� ','1','1','C','1');
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFillColor(221,221,221);
    $pdf->SetFont('Arial','B',8);
// la condition sur le souscripteur et l'assure
    if($row_g['rp_sous']==1){
        $pdf->Cell(40,4,'Nom et Pr�nom','1','0','L','1');$pdf->Cell(150,4,"".$row_g['nom_sous']." ".$row_g['pnom_sous']."",'1','0','C');$pdf->Ln();
        $pdf->Cell(40,4,'Adresse','1','0','L','1');$pdf->Cell(150,4,"".$row_g['adr_sous']."",'1','0','C');$pdf->Ln();
        $pdf->Cell(40,4,'T�l�phone','1','0','L','1');$pdf->Cell(55,4,"".$row_g['tel_sous']."",'1','0','C');
        $pdf->Cell(40,4,'E-mail','1','0','L','1');$pdf->Cell(55,4,"".$row_g['mail_sous']."",'1','0','C');$pdf->Ln();

    }else{
// le souscripteur n'est pas l'assur�
        $rowa=$row_g['cod_sous'];
        $rqta=$bdd->prepare("SELECT s.`nom_sous`, s.`pnom_sous`, s.`mail_sous`, s.`tel_sous`, s.`adr_sous`, s.`rp_sous`,s.`dnais_sous`,s.`age`  FROM `souscripteurw` as s  WHERE  s.`cod_par`='$rowa'");
        $rqta->execute();
        while ($row_a=$rqta->fetch()){
            $pdf->Cell(40,4,'Nom et Pr�nom','1','0','L','1');$pdf->Cell(150,4,"".$row_a['nom_sous']." ".$row_a['pnom_sous']."",'1','0','C');$pdf->Ln();
            $pdf->Cell(40,4,'Adresse','1','0','L','1');$pdf->Cell(150,4,"".$row_a['adr_sous']."",'1','0','C');$pdf->Ln();
            $pdf->Cell(40,4,'T�l�phone','1','0','L','1');$pdf->Cell(55,4,"".$row_a['tel_sous']."",'1','0','C');
            $pdf->Cell(40,4,'E-mail','1','0','L','1');$pdf->Cell(55,4,"".$row_a['mail_sous']."",'1','0','C');$pdf->Ln();
        }
//fin de la condition
    }
    $pdf->Ln();
    //CONTRAT
    $pdf->SetFillColor(7,27,81);
    $pdf->SetTextColor(255,255,255);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(190,5,' Avenant de subrogation','1','0','C','1');$pdf->Ln();
    $pdf->SetFillColor(221,221,221);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(40,4,"Branche",'1','0','L','1');$pdf->Cell(55,4,"D�c�s",'1','0','C');
    $pdf->Cell(40,4,'Nature du risque assur�','1','0','L','1');$pdf->Cell(55,4,"D�c�s et Invalidit� Absolue et D�finitive",'1','0','C');$pdf->Ln();
    $pdf->Cell(40,4,'Montant de cr�dit en chiffre','1','0','L','1');$pdf->Cell(150,4,"".number_format($row_g['cap1'], 2, ',', ' ')." DZD",'1','0','L');$pdf->Ln();
    $montant=$a1->ConvNumberLetter("".$row_g['cap1']."",1,0);
    $pdf->Cell(40,4,'Montant de cr�dit en lettre','1','0','L','1');$pdf->Cell(150,4,"".$montant."",'1','0','L');$pdf->Ln();
    $pdf->Cell(40,4,"Date d'effet de l'avenant",'1','0','L','1');$pdf->Cell(55,4,"".date("d/m/Y", strtotime($row_g['dat_val']))."",'1','0','C');
    $pdf->Cell(40,4,'Expire le','1','0','L','1');$pdf->Cell(55,4,"".date("d/m/Y", strtotime($row_g['ndat_ech']))."",'1','0','C');$pdf->Ln();
    $dat_eff=date("d/m/Y", strtotime($row_g['ndat_eff']));$dat_ech=date("d/m/Y", strtotime($row_g['ndat_ech']));
    $pdf->Ln(3);
// Beneficiaire (Organisme preteur)

    $pdf->SetFillColor(7,27,81);
    $pdf->SetTextColor(255,255,255);
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(190,5,' Etablissement pr�teur','1','0','C','1');$pdf->Ln();
    $pdf->SetFillColor(221,221,221);$pdf->SetTextColor(0,0,0);
    $pdf->Cell(35,4,'Organisme pr�teur','1','0','L','1');$pdf->Cell(155,4,"".$row_g['nom_benef']."",'1','0','L');$pdf->Ln();
    $pdf->Cell(35,4,'Code agence','1','0','L','1');$pdf->Cell(155,4,"".$row_g['ag_benef']."",'1','0','L');$pdf->Ln();
    $pdf->Cell(35,4,'T�l�phone','1','0','L','1');$pdf->Cell(155,4,"".$row_g['tel_benef']."",'1','0','L');$pdf->Ln();
    $pdf->Cell(35,4,'Adresse','1','0','L','1');$pdf->Cell(155,4,"".$row_g['adr_benef']."",'1','0','L');$pdf->Ln();
    $pdf->Ln(4);
    $pdf->SetFont('Arial','',8);
    $pdf->MultiCell(190,4,"A la demande de l�assur�, il est convenu d�un commun accord entre les parties au contrat d�assurance, qu�aucune somme ne sera vers�e au titre de l�indemnit� r�sultant d�un� risque� assur� par la� police d�assurance susmentionn�e� sans l�intervention et qu�entre les mains de l��tablissement ci-dessus d�sign� en tant que cr�ancier.
Cette renonciation � l�indemnit�est consentie par l�assur� emprunteur en vue de favoriser le cr�dit de ce dernier�et�elle ne saurait lui profiter personnellement.Par cons�quent, l��tablissement pr�teur�est subrog� dans les droits de l�assur� emprunteur, pour percevoir�l�indemnit� d�assurance en tant que b�n�ficiaire de celle-ci, l�indemnit� sera r�gl�e au cr�ancier susmentionn� qui ne peut faire valoir ses droits que sur l�indemnit� fix�e par AGLIC.
En cas de d�c�s de l�emprunteur, l�assureur versera � l��tablissement pr�teur le reliquat du cr�dit impay� par l�emprunteur. Le versement est forfaitaire et se fait donc en une seule fois.Si l�emprunteur est atteint d�invalidit� le r�gime d�indemnisation est le m�me qu�en mati�re de d�c�s et�le remboursement se fera �galement sous la forme d�un versement forfaitaire.�
Nonobstant cette renonciation consentie, le cr�ancier susmentionn� n�aura droit�� aucune indemnit�, si AGLIC��tablissait que celui-ci a eu connaissance, avant le sinistre, des circonstances aggravantes ou que ce dernier avait caus�intentionnellement le sinistre ou facilit� sa survenance.Le droit pour AGLIC�de r�silier le contrat, lors de toutes infractions constat�es, demeure entier.��
Il appartient au cr�ancier d��tablir l�ordre et le rang de l�attribution de sa cr�ance et ce, conform�ment � la l�gislation en vigueur.Il n�est rien chang�aux autres clauses et conditions tant g�n�rales que particuli�res de la police d�assurance � laquelle le pr�sent avenant est annex� pour en faire partie int�grante.",0,'J',false);

    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(40,4,'D�compte de la prime ','0','0','L'); $pdf->Ln(3);
    $pdf->Ln(3);

    $pdf->SetFillColor(199,139,85);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(45,4,' Prime Nette ','1','0','C','1');$pdf->Cell(45,4,' Cout de Police ','1','0','C','1');
    $pdf->Cell(50,4,' Droit de timbre ','1','0','C','1');$pdf->Cell(50,4,' Prime Totale (DZD) ','1','0','C','1');
    $pdf->Ln();$pdf->SetFont('Arial','B',8);
    $pdf->Cell(45,4,"".number_format($row_g['pn'], 2, ',', ' ')."",'1','0','C');
    $pdf->Cell(45,4,"".number_format($row_g['mtt_cpl'], 2, ',', ' ')."",'1','0','C');
    $pdf->Cell(50,4,"".number_format($row_g['mtt_dt'], 2, ',', ' ')."",'1','0','C');
    $pdf->Cell(50,4,"".number_format($row_g['pt'], 2, ',', ' ')."",'1','0','C');$pdf->Ln();
    $pdf->Ln(2);
    $pdf->SetFont('Arial','I',5);
    $pdf->MultiCell(190,3,"J�atteste de l'exactitude des informations communiqu�es et reconnais avoir �t� inform� des cons�quences qui pourraient r�sulter d'une omission ou d'une d�claration erron�e tel que pr�vues par l'article 19 de l'Ordonnance N� 95/07 du 25 Janvier 1995 relative aux assurances modifi�e et compl�t�e par la loi N� 06/04 du 20 F�vrier 2006.",0,'J',false);
//$pdf->Cell(0,6,"Le Souscripteur reconnait �galement avoir �t� inform� du contenu des Conditions Particuli�res et des Conditions G�n�rales et avoir �t� inform� du montant de la prime et des garanties d�es.",0,0,'C');



    $pdf->Cell(185,4,"".$adr." le ".date("d/m/Y", strtotime($row_g['dat_val']))."",'0','0','R');$pdf->Ln();

    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(60,4,"L'assur�",'0','0','L');$pdf->Cell(60,4,"Le b�n�ficiaire",'0','0','C');$pdf->Cell(60,4,"L'assureur",'0','0','R');$pdf->Ln();
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(60,4,"Pr�ced� de la mention �Bon pour subrogation�",'0','0','L');$pdf->Ln();

}



//////quittance

$cpt_q=0;
$rqtseq=$bdd->prepare(" SELECT `id_quit`, `cod_quit`, `mois`, `date_quit`, `agence`, `cod_ref`, `mtt_quit`, `solde_pol`, `cod_dt`, `cod_cpl`, `id_user` FROM `quittance` WHERE `cod_ref`='$row' AND type_quit=1;");
$rqtseq->execute();
while($rowq=$rqtseq->fetch())
{
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->Ln(8);
    $cpt_q++;
    $pdf->SetFont('Arial','B',10);
    $pdf->SetFillColor(255,255,255);
    $pdf->Cell(20,5,'AGENCE    ','0','0','L');
    $pdf->SetFillColor(231,229,231);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(100,5,' : '.$agence,'0','0','L');$pdf->Ln();
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(20,5,'ADRESSE ','0','0','L');
    $pdf->SetFillColor(231,229,231);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(150,5,' : '.$adr_agence,'0','0','L');$pdf->Ln();
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(20,5,'TEL ','0','0','L');
    $pdf->SetFillColor(231,229,231);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(100,5,' : '.$tel_agence,'0','0','L');
    $pdf->Ln();
    $pdf->Cell(190,5,'Le:   '.date("d/m/Y"),'0','0','R');$pdf->Ln();
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(190,20,'QUITTANCE DE PRIME N�:'.$agence.'/'.substr($rowq['date_quit'],0,4).'/'.$cod_prod.'/'.str_pad((int) $rowq['cod_quit'],'5',"0",STR_PAD_LEFT),'LTR','0','C');$pdf->Ln();

    $pdf->Cell(20,20,'Avenant N�: ','LB','0','L');
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(70,20,' '.$agence.'.'.$annee.'.'.$lib_mpay.'.1.'.$cod_prod.'.'.$sequence_pol,'B','0','L');
    $pdf->Cell(100,20,'     DU:'.$dat_eff.'                  AU:'.$dat_ech,'RB','0','R');$pdf->Ln();

    /////////
    $pdf->Ln(2);
    $pdf->Cell(190,6,"SOUSCIPTEUR:" ,'B','0','L');$pdf->Ln();
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(60,6,"Nom,Pr�nom/R.SOCIALE:" ,'L','0','L');
    $pdf->SetFillColor(231,229,231);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(130,6," ".$nom_sous.' '.$pnom_sous ,'R','0','L');$pdf->Ln();
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(60,6,"Adresse:" ,'L','0','L');
    // $pdf->SetXY(40,55);
    $pdf->SetFont('Arial','',10);
    $pdf->MultiCell(130,6,"".$adr_sous ,'R','L',false);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(60,6,"TEL :" ,'L','0','L');
    $pdf->Cell(130,6," ".$tel_sous."" ,'R','0','L');$pdf->Ln();
    $pdf->Cell(60,6,"E-mail:" ,'LB','0','L');
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(130,6," ".$mail_sous."" ,'RB','0','L');$pdf->Ln();$pdf->Ln();


    $pdf->Cell(40,5,'D�compte de la prime ','0','0','L','0');
    $pdf->Ln(6);
    $pdf->SetFillColor(199,139,85);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(45,6,' Prime Nette ','1','0','C','1');$pdf->Cell(45,6,' Cout de Police ','1','0','C','1');
    $pdf->Cell(50,6,' Droit de timbre ','1','0','C','1');$pdf->Cell(50,6,' Prime Totale (DZD) ','1','0','C','1');
    $pdf->Ln();$pdf->SetFont('Arial','B',8);
    $pdf->Cell(45,6,"".number_format($rowq['mtt_quit']-$cout_police-$droit_timbre, 2, ',', ' ')."",'1','0','C');
    $pdf->Cell(45,6,"".number_format($cout_police, 2, ',', ' ')."",'1','0','C');
    $pdf->Cell(50,6,"".number_format($droit_timbre, 2, ',', ' ')."",'1','0','C');
    $pdf->Cell(50,6,"".number_format($rowq['mtt_quit'], 2, ',', ' ')."",'1','0','C');$pdf->Ln(12);
    $sommeq=$a1->ConvNumberLetter("".$rowq['mtt_quit']."",1,0);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(30,4,"Soit en lettres",'0','0','L');$pdf->Ln(6);
    $pdf->SetFont('Arial','B',12);$pdf->SetFillColor(255,255,255);
    $pdf->MultiCell(190,4,"".$sommeq."",0,'L',true);
    $pdf->SetXY(110,$pdf->GetY());
    $pdf->MultiCell(80,18,"Cachet et signature",0,'R',true);
    $pdf->Ln(10);



}


$pdf->Output();



?>








