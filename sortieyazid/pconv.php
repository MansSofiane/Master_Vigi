<?php 
session_start();
require_once("../../../data/conn4.php");
if ($_SESSION['login']){ 
//authentification acceptee !!!


//header("Location: http://www.monsite.com");
}
else {
header("Location:../index.html?erreur=login"); // redirection en cas d'echec
}
include("convert.php");
$a1 = new chiffreEnLettre();
//$errone = false;
require('tfpdf.php');
if (isset($_REQUEST['conv'])) {
$row = substr($_REQUEST['conv'],10);
}
class PDF extends TFPDF { // En-t?te
function Header()
{
 $this->SetFont('Arial','B',10);
    $this->Image('../img/entete_bna.png',6,4,190);
	 $this->Cell(150,5,'','O','0','L');
	 $this->SetFont('Arial','B',12);
     $this->SetFont('Arial','B',10);
	 $this->Ln(8);
}

// Pied de page
function Footer()
{
    // Positionnement ? 1,5 cm du bas
    $this->SetY(-15);
    // Police Arial italique 8
    $this->SetFont('Arial','I',6);
    // Num?ro de page
    $this->Cell(0,8,'Page '.$this->PageNo().'/{nb}',0,0,'C');$this->Ln(3);
	$this->Cell(0,8,"Algerian Gulf Life Insurance Company, SPA au capital social de 1.000.000.000 de dinars alg�riens, 01 Rue Tripoli, hussein Dey Alger,  ",0,0,'C');
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
//Les requetes *****************
// Requete Agence 



//Requete Agence
$rqts=$bdd->prepare("SELECT *  FROM `agence` WHERE cod_agence='".$row."'");
$rqts->execute();

// Instanciation de la classe derivee
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Ln(20);
$pdf->SetFont('Arial','B',24);
//$pdf->Ln(2);
$pdf->SetFillColor(205,205,205);
$pdf->Cell(190,8,"CONVENTION D�ASSURANCE",'0','0','C');$pdf->Ln();
$pdf->Ln(5);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(190,8,"ASSURANCE VOYAGE ET ASSITANCE -AVA",'0','0','C');$pdf->Ln();
$pdf->Ln(30);
$pdf->SetFont('Arial','B',16);
$pdf->Cell(190,8,"N� de Contrat",'0','0','C');$pdf->Ln();
while ($row_ag=$rqts->fetch()){
$pdf->Cell(190,8,substr($row_ag['date'],0,4).'.'.str_pad((int) $row,'5',"0",STR_PAD_LEFT).'','0','0','C');$pdf->Ln();
$pdf->Ln(30);
$pdf->Cell(190,8,"CANAL ASSURANCE",'0','0','C');$pdf->Ln();$pdf->Ln(3);
$pdf->Cell(190,8,"CASH ASSURANCE",'0','0','C');$pdf->Ln();$pdf->Ln(100);
$pdf->Cell(190,8,"L�Alg�rienne Vie  -".substr($row_ag['date'],0,4),'0','0','R');
    $rqtu=$bdd->prepare("select * from utilisateurs where  id_user ='".$row_ag['id_user']."'");
    $rqtu->execute();
// Fin de la premiere page
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Ln(20);
$pdf->SetFont('Arial','B',24);
//$pdf->Ln(2);
$pdf->SetFillColor(205,205,205);
$pdf->Cell(190,8,"SOMMAIRE",'0','0','C');$pdf->Ln(); $pdf->Ln(15);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(190,8,"ARTICLE 1 :     OBJET DE LA CONVENTION",'0','0','L');$pdf->Ln();$pdf->Ln(2);
$pdf->Cell(190,8,"ARTICLE 2 :     ENGAGEMENT DU SOUSCRIPTEUR",'0','0','L');$pdf->Ln();$pdf->Ln(2);
$pdf->Cell(190,8,"ARTICLE 3 :     GARANTIES ACCORDEES",'0','0','L');$pdf->Ln();$pdf->Ln(2);
$pdf->Cell(190,8,"ARTICLE 4 :     MODALITES DE GARANTIE ",'0','0','L');$pdf->Ln();$pdf->Ln(2);
$pdf->Cell(190,8,"ARTICLE 5 :     PRIME D�ASSURANCE",'0','0','L');$pdf->Ln();$pdf->Ln(2);
$pdf->Cell(190,8,"ARTICLE 6 :     PAIEMENT DE LA PRIME",'0','0','L');$pdf->Ln();$pdf->Ln(2);
$pdf->Cell(190,8,"ARTICLE 7 :     PARTICIPATION BENEFICIAIRE",'0','0','L');$pdf->Ln();$pdf->Ln(2);
$pdf->Cell(190,8,"ARTICLE 8 :     SINISTRE",'0','0','L');$pdf->Ln();$pdf->Ln(2);
$pdf->Cell(190,8,"ARTICLE 9 :     REGLEMENT DES LITIGES",'0','0','L');$pdf->Ln();$pdf->Ln(2);
$pdf->Cell(190,8,"ARTICLE 10 :    DUREE DE LA CONVENTION",'0','0','L');$pdf->Ln();$pdf->Ln(30);
$pdf->SetFont('Arial','B',18);
$pdf->Cell(190,8,"ANNEXES",'0','0','L');$pdf->Ln();$pdf->Ln(10);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(190,8,"ANNEXE 1 :      LES TARIFS APPLIQUES ",'0','0','L');$pdf->Ln();$pdf->Ln(2);
$pdf->Cell(190,8,"ANNEXE 2 :      LIMITES DE GARANTIES EN ASSURANCE VOYAGE ",'0','0','L');$pdf->Ln();$pdf->Ln(2);
$pdf->Cell(190,8,"ANNEXE 3 :      CANEVAS LISTING A ETABLIR PAR LE SOUSCRIPTEUR  ",'0','0','L');$pdf->Ln();$pdf->Ln(2);
// Fin de la deuxieme page
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Ln(20);
$pdf->SetFont('');
$pdf->Cell(190,8,"Dans la pr�sente convention, on entend par :",'0','0','L');$pdf->Ln();$pdf->Ln(20);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(35,8,"�L�Assureur : ",'0','0','L');$pdf->SetFont('');
$pdf->Cell(155,8,"La compagnie d�assurance de personnes � L�Alg�rienne Vie- AGLIC �.",'0','0','L');$pdf->Ln();$pdf->Ln(10);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(40,8,"�Le Mandataire : ",'0','0','L');$pdf->SetFont('');
$pdf->Cell(150,8,"l�agence d'assurance de la CASH-ASSURANCE.",'0','0','L');$pdf->Ln();$pdf->Ln(10);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(43,8,"�Le Souscripteur : ",'0','0','L');$pdf->SetFont('');
$pdf->Cell(147,8,"L�agence de voyage et de tourisme.",'0','0','L');$pdf->Ln();$pdf->Ln(10);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(30,8,"�L�Assur� : ",'0','0','L');$pdf->SetFont('');
$pdf->Cell(160,8,"Client de l�agence de voyage et de tourisme.",'0','0','L');$pdf->Ln();$pdf->Ln(10);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(33,8,"�L�Assisteur : ",'0','0','L');$pdf->SetFont('');
$pdf->Cell(157,8,"La compagnie sp�cialis�e dans la couverture des garanties d�assistance",'0','0','L');$pdf->Ln();
$pdf->Cell(33,8,"",'0','0','L');
$pdf->Cell(157,8,"aux personnes � l��tranger � MapFre Assistance �.",'0','0','L');
// Fin de la troisieme page
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Ln(20);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(190,8,"Entre les soussign�s,",'0','0','L');$pdf->Ln();$pdf->SetFont('');$pdf->Ln(5);
$pdf->Cell(190,8,"La soci�t� d�assurance de personnes � Algerian Gulf Life Insurance Company �",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"sous la d�nomination commerciale � L�Alg�rienne Vie � sise � 01, Rue TRIPOLI,",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"Hussein dey, Alger, repr�sent�e par Monsieur Boualem GOUMEZIANE en qualit� de ",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"Directeur Commercial.",'0','0','L');$pdf->Ln();$pdf->Ln(5);
$pdf->Cell(50,8,"D�sign�e par le terme",'0','0','L');$pdf->SetFont('Arial','B',14);$pdf->Cell(60,8,"� L�Assureur �",'0','0','L');$pdf->Ln();$pdf->Ln(5);
$pdf->Cell(190,8,"ET",'0','0','L');$pdf->Ln();$pdf->Ln(5);$pdf->SetFont('');
while ($row_u=$rqtu->fetch()){
$pdf->Cell(190,8,"La CASH-ASSURANCE, agence   ".$row_u['agence']."  sise � ",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,$row_u['adr_user']." repr�sent�e par  M(me) ".$row_u['nom']." ".$row_u['prenom'],'0','0','L');$pdf->Ln(5);
$pdf->Cell(50,8,"D�sign�e par le terme",'0','0','L');$pdf->SetFont('Arial','B',14);$pdf->Cell(60,8,"� Mandataire�",'0','0','L');$pdf->Ln();$pdf->Ln(5);
$pdf->Cell(190,8,"ET",'0','0','L');$pdf->Ln();$pdf->Ln(5);$pdf->SetFont('');
$pdf->Cell(190,8,"L�agence de voyage et de tourisme, ".$row_ag['lib_agence']." sise � ".$row_ag['adr_agence'],'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"repr�sent�e par ".$row_ag['nom_rep']." ".$row_ag['prenom_rep']." en qualit�(e) de Responsable d�Agence.",'0','0','L');$pdf->Ln();$pdf->Ln(5);
//********************************************************************
$pdf->Cell(50,8,"D�sign�e par le terme",'0','0','L');$pdf->SetFont('Arial','B',14);$pdf->Cell(60,8,"� Souscripteur�",'0','0','L');$pdf->Ln();$pdf->Ln(40);
$pdf->Cell(190,8,"Il a �t� arr�t� et convenu comme suit :",'0','0','L');$pdf->Ln();
//Fin de la Quatrieme Page
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Ln(20);
$pdf->SetFont('Arial','B',10);
$pdf->SetFont('');
$pdf->Cell(190,8,"La pr�sente convention est r�gie par l�ordonnance N� 95-07 du 25/01/1995, relative aux assurances, modifi�e ",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"et compl�t�e par la loi N� 06-04 du 20/02/2006, ainsi que les conditions g�n�rales et particuli�res aff�rentes",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"�  l�assurance voyage et assistance",'0','0','L');$pdf->Ln();
$pdf->SetFont('Arial','B',14);
$pdf->Cell(190,8,"Article 1: OBJET DE LA CONVENTION",'0','0','L');$pdf->Ln();
$pdf->SetFont('Arial','I',10);$pdf->SetFont('');
$pdf->Cell(190,8,"La pr�sente convention a pour objet de d�finir les garanties et conditions de l�assurance voyage et d�assistance",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"pour la couverture des clients du Souscripteur en cas, de survenance de sinistre durant leur s�jour � l��tranger  ",'0','0','L');$pdf->Ln();
$pdf->SetFont('Arial','B',14);
$pdf->Cell(190,8,"Article 2: ENGAGEMENT DU SOUSCRIPTEUR",'0','0','L');$pdf->Ln();
$pdf->SetFont('Arial','I',10);$pdf->SetFont('');
$pdf->Cell(190,8,"Le Souscripteur  s�engage � souscrire aupr�s de l�Assureur  via le Mandataire des contrats d�assurance voyage et ",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"assistance � l��tranger au profit de ses clients.",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"Le Souscripteur n�ouvre droit � aucune commission de distribution � l�assureur.",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"(Article 03 de l�arr�t� du 06 aout 2007, JORA n� 59 du 23/09/2007).",'0','0','L');$pdf->Ln();
$pdf->SetFont('Arial','B',14);
$pdf->Cell(190,8,"Article 3: GARANTIES ACCORDEES",'0','0','L');$pdf->Ln();
$pdf->SetFont('Arial','I',10);$pdf->SetFont('');
$pdf->Cell(190,8,"L�Assureur propose au titre de cette assurance, des garanties sp�cifiques qui permettront de couvrir les clients ",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"de l�agence de voyage � l�occasion de leurs d�placements (missions/vacances) � l��tranger, aux conditions ci-apr�s:",'0','0','L');$pdf->Ln();
$pdf->Ln(3);$pdf->Cell(190,8,"L�Assureur propose trois cat�gories de couverture:",'0','0','L');$pdf->Ln();
$pdf->SetFont('Arial','B',12);
$pdf->Cell(30,8,"a. Assurance : ",'0','0','L');$pdf->SetFont('Arial','I',10);$pdf->SetFont('');
$pdf->Cell(160,8,"Garanties de base qui couvre l�assur� en cas de :",'0','0','L');$pdf->Ln();$pdf->Ln(2);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(50,8,"1.	D�c�s suite � un Accident : ",'0','0','L');$pdf->SetFont('Arial','I',10);$pdf->SetFont('');
$pdf->Cell(140,8,"en cas de d�c�s suite � un accident, l�Assureur garantit le paiement du capital pr�d�fini ",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"dans les conditions particuli�res.",'0','0','L');$pdf->Ln();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(55,8,"2.	Invalidit� Permanente Totale : ",'0','0','L');$pdf->SetFont('Arial','I',10);$pdf->SetFont('');
$pdf->Cell(135,8,"Si � la suite d�un accident garanti, l�assur� reste atteint d�une invalidit� permanente ",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"totale, l�Assureur  verse un capital �gal au capital garanti en cas de d�c�s. ",'0','0','L');$pdf->Ln();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(58,8,"3.	Invalidit� Permanente Partielle : ",'0','0','L');$pdf->SetFont('Arial','I',10);$pdf->SetFont('');
$pdf->Cell(132,8,"Si � la suite d�un accident garanti, l�assur� reste atteint d�une invalidit� permanente",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"partielle, l�Assureur verse  une indemnit� �gale au taux d�incapacit�  (x) capital garanti en cas de d�c�s.",'0','0','L');$pdf->Ln();
$pdf->Ln(3);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(30,8,"b.	Assistance : ",'0','0','L');$pdf->SetFont('Arial','I',10);$pdf->SetFont('');
$pdf->Cell(160,8,"Garanties compl�mentaires qui couvrent l�assur� se trouvant en difficult� durant son voyage,",'0','0','L');$pdf->Ln();
$pdf->Cell(160,8,"la prise en charge est assur�e par l�Assisteur en cas de :",'0','0','L');$pdf->Ln();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(105,8,"1.	Transport ou rapatriement en cas de Maladie ou de L�sion : ",'0','0','L');$pdf->SetFont('Arial','I',10);$pdf->SetFont('');
$pdf->Cell(85,8,"En cas  de maladie ou l�sion corporelle de l�assur�,",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"survenue durant son s�jour � l'�tranger et selon l'urgence ou la gravit� du cas et l'avis du m�decin traitant, ",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"l�Assisteur prend en charge le transport de l�assur�, sous surveillance m�dicale si son �tat l'exige, jusqu'� son admission",'0','0','L');
$pdf->Ln();
$pdf->Cell(190,8,"dans un centre hospitalier convenablement �quip� ou jusqu'� son domicile habituel.",'0','0','L');$pdf->Ln();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Ln(20);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(70,8,"2.	  Frais m�dicaux et pharmaceutiques :  ",'0','0','L');$pdf->SetFont('Arial','I',10);$pdf->SetFont('');
$pdf->Cell(120,8,"En cas de maladie ou l�sion de l�assur� durant son s�jour � l'�tranger,",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"l�Assisteur prend en charge les frais  n�cessaires d'hospitalisation, des interventions chirurgicales, les honoraires des",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"m�decins et les produits pharmaceutiques prescrits par son m�decin traitant, dans un centre hospitalier ad�quat.",'0','0','L');$pdf->Ln();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(53,8,"3.	  Soins dentaires d'urgence : ",'0','0','L');$pdf->SetFont('Arial','I',10);$pdf->SetFont('');
$pdf->Cell(137,8,"En cas d�infection de la gencive ou d'une dent n�cessitant des soins d'urgence.",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"Le co�t de la premi�re visite est garanti. Aucune prise en charge ne sera donn�e pour les soins de 'reconstruction', ",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"orthodontie, proth�ses dentaires, plombage, couronnes, r�paration d'une couronne ou tout autre traitement, ",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"non n�cessaires pour soulager la douleur. ",'0','0','L');$pdf->Ln();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(80,8,"4. 	Prolongation de s�jour pour convalescence : ",'0','0','L');$pdf->SetFont('Arial','I',10);$pdf->SetFont('');
$pdf->Cell(110,8,"L�Assisteur prend en charge l�assur� pour payer les frais d'h�tel",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"pour cause de maladie ou l�sion survenue lors du voyage et par prescription m�dicale, il doit prolonger son s�jour",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"� l'endroit o� il s'est d�plac�.",'0','0','L');$pdf->Ln();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(49,8,"5. 	Visite d'un proche parent: ",'0','0','L');$pdf->SetFont('Arial','I',10);$pdf->SetFont('');
$pdf->Cell(141,8,"Au cas o� l'hospitalisation de l�assur� d�passerait les dix jours, selon la prescription",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"du m�decin traitant, l�Assisteur prend en charge le billet aller-retour, et l�h�bergement d'un membre de la famille, et ceci",'0','0','L');
$pdf->Ln();
$pdf->Cell(190,8,"jusqu'au lieu o� l�assur� a �t� hospitalis� pour une dur�e maximum de 4 jours.",'0','0','L');$pdf->Ln();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(70,8,"6. 	Rapatriement de corps suite au d�c�s : ",'0','0','L');$pdf->SetFont('Arial','I',10);$pdf->SetFont('');
$pdf->Cell(120,8,"En cas de d�c�s de l�assur� durant le voyage, l�Assisteur effectuera ",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"les d�marches n�cessaires et prendra en charge les frais de transport ou le rapatriement du corps au pays de r�sidence.",'0','0','L');$pdf->Ln();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(55,8,"7.	 Exp�dition de m�dicament : ",'0','0','L');$pdf->SetFont('Arial','I',10);$pdf->SetFont('');
$pdf->Cell(135,8," L�Assisteur prendra � sa charge les frais d'envoi de m�dicaments qui,",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"par caract�re d'urgence, sont prescrits m�dicalement � l�assur�, m�me si cette prescription est ant�rieure au voyage, ",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"et ne sont pas disponibles � l'endroit o� il s'est d�plac�. ",'0','0','L');$pdf->Ln();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(105,8,"8.	Retour pr�matur� de l�assur� (d�c�s/sinistre au domicile) : ",'0','0','L');$pdf->SetFont('Arial','I',10);$pdf->SetFont('');
$pdf->Cell(85,8,"L�Assisteur supportera les frais de d�placement",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"urgent de l�assur� jusqu'� son domicile, suite � un vol par effraction, incendie ou explosion dans sa r�sidence habituelle,",'0','0','L');
$pdf->Ln();
$pdf->Cell(190,8,"pour autant qu'il ne puisse pas faire le dit d�placement avec le moyen ou titre de transport utilis� pour faire le voyage. ",'0','0','L');
$pdf->Ln();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(75,8,"9.	Interruption du voyage suite � un d�c�s :  ",'0','0','L');$pdf->SetFont('Arial','I',10);$pdf->SetFont('');
$pdf->Cell(115,8,"L�Assisteur supportera les frais de d�placement urgent de l�assur�",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"jusqu'� son domicile, � la suite du d�c�s d'un membre de sa famille proche. Pour autant qu'il ne puisse ne pas effectuer",'0','0','L');
$pdf->Ln();
$pdf->Cell(190,8,"le dit d�placement avec le moyen ou titre de transport utilis� pour faire le voyage. ",'0','0','L');
$pdf->Ln();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(75,8,"10.	Accompagnement d�un enfant mineur : ",'0','0','L');$pdf->SetFont('Arial','I',10);$pdf->SetFont('');
$pdf->Cell(115,8,"Si l�assur� est mineur et ne dispose pas de personne pouvant ",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"l�accompagner, l�Assisteur d�signera un accompagnateur pour le conduire jusqu�� son domicile. ",'0','0','L');
$pdf->Ln();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(110,8,"11. Localisation et transport des bagages et effets personnels :  ",'0','0','L');$pdf->SetFont('Arial','I',10);$pdf->SetFont('');
$pdf->Cell(80,8,"L�Assisteur aidera l�assur� � localiser ses bagages",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"et effets personnels disparus et  prendra en charge leur exp�dition jusqu�au lieu o� se trouve l�assur�.",'0','0','L');
$pdf->Ln();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Ln(20);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(65,8,"12. Manquement de correspondance : ",'0','0','L');$pdf->SetFont('Arial','I',10);$pdf->SetFont('');
$pdf->Cell(125,8,"Si l�assur� manque le d�part d�un vol par suite de l�arriv�e tardive de son vol ",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"pr�c�dant et qu�aucun moyen de transport n�est mis � sa disposition dans un d�lai de 4 heures, les achats de premi�res ",'0','0','L');
$pdf->Ln();
$pdf->Cell(190,8,"n�cessit�s, seront rembours�s sur facture.",'0','0','L');
$pdf->Ln();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(50,8,"13. Retard d�un vol r�gulier :  ",'0','0','L');$pdf->SetFont('Arial','I',10);$pdf->SetFont('');
$pdf->Cell(140,8,"L�Assisteur paiera sur pr�sentation des factures originales correspondantes,",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"les frais de transport et d�h�bergement r�alis�s � la suite du retard du vol d�passant 6h.",'0','0','L');
$pdf->Ln();
$pdf->Cell(190,8,"Le retard doit �tre d� � l'annulation des services de transport, provoqu�s par accident, gr�ve, d�tournement, ",'0','0','L');
$pdf->Ln();
$pdf->Cell(190,8,"acte de terroriste, acte criminel, alerte � la bombe, �meute, agitation civile, feu, inondation, tremblement de terre, ",'0','0','L');
$pdf->Ln();
$pdf->Cell(190,8,"avalanche, conditions atmosph�riques d�favorables ou panne m�canique. ",'0','0','L');
$pdf->Ln();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(45,8,"14. Annulation de voyage :  ",'0','0','L');$pdf->SetFont('Arial','I',10);$pdf->SetFont('');
$pdf->Cell(145,8,"L�Assisteur remboursera les frais de voyage, non r�cup�rable pr�vus contractuellement ",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"aux conditions de vente de l�agence de voyage. L�assur� est indemnis� en cas de maladie grave, accident corporel ",'0','0','L');
$pdf->Ln();
$pdf->Cell(190,8,"grave, d�c�s du b�n�ficiaire, ou d�un membre de sa famille.",'0','0','L');
$pdf->Ln();
$pdf->Cell(190,8,"L�assur� doit fournir le document qui justifie le remboursement des frais de voyage (certificat m�dicale, certificat de d�c�s).",'0','0','L');
$pdf->Ln();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(60,8,"15. Frais de secours et sauvetage : ",'0','0','L');$pdf->SetFont('Arial','I',10);$pdf->SetFont('');
$pdf->Cell(130,8,"L�Assisteur prend en charge les frais de recherche et de secours � concurrence ",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"des montants fix�s aux conditions particuli�res, ces frais correspondent aux op�rations organis�es par des ",'0','0','L');
$pdf->Ln();
$pdf->Cell(190,8,"sauveteurs civils ou militaires ou des organismes sp�cialis�s publics ou priv�s mis en place en vue de sauvegarder la vie ",'0','0','L');
$pdf->Ln();
$pdf->Cell(190,8,"ou l�int�grit� physique de l�assur�.",'0','0','L');
$pdf->Ln();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(40,8,"16. Retard de bagages : ",'0','0','L');$pdf->SetFont('Arial','I',10);$pdf->SetFont('');
$pdf->Cell(150,8,"Si les bagages, enregistr�s � une compagnie de ligne a�rienne filiale � l'I.A.T.A,",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"ont plus de 6 heures de retard, les d�penses de premi�re n�cessit� seront rembours�es sur facture.",'0','0','L');
$pdf->Ln();
$pdf->Cell(190,8,"Le certificat original du transporteur ou la plainte se rapportant au retard doit �tre fourni.",'0','0','L');
$pdf->Ln();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(55,8,"17. Perte de bagage enregistr�: ",'0','0','L');$pdf->SetFont('Arial','I',10);$pdf->SetFont('');
$pdf->Cell(145,8,"Si les bagages confi�s � la compagnie a�rienne sont perdus,",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"l�Assisteur compl�tera, les remboursements garantis par la compagnie a�rienne jusqu�� concurrence de 210 �.",'0','0','L');
$pdf->Ln();
$pdf->Cell(190,8,"La p�riode minimum qui doit s'�couler pour que les bagages soient consid�r�s comme avoir �t� perdus d�finitivement",'0','0','L');
$pdf->Ln();
$pdf->Cell(190,8,"sera celle stipul� par la compagnie de transport, avec 21 jours au minimum.",'0','0','L');
$pdf->Ln();
$pdf->Cell(190,8,"Le certificat original du transporteur ou la plainte se rapportant � la perte de bagages doit �tre fourni.",'0','0','L');
$pdf->Ln();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(40,8,"18. D�fense Juridique : ",'0','0','L');$pdf->SetFont('Arial','I',10);$pdf->SetFont('');
$pdf->Cell(150,8,"L'Assisteur supportera les frais de d�fense juridique � l'�tranger dans les proc�dures ",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"p�nales ou civiles qui sont engendr�es contre l�assur� en cas d'accident de la circulation. ",'0','0','L');
$pdf->Ln();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Ln(20);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(75,8,"19. Caution dues � des proc�dures p�nales : ",'0','0','L');$pdf->SetFont('Arial','I',10);$pdf->SetFont('');
$pdf->Cell(115,8,"L�Assisteur avancera � l�assur� la caution que les tribunaux",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"�trangers exigent pour garantir sa libert� provisoire, dans la proc�dure p�nale engag�e contre lui suite � un accident de",'0','0','L');
$pdf->Ln();
$pdf->Cell(190,8,"la circulation dans lequel l�assur� conduisait personnellement le v�hicule.",'0','0','L');
$pdf->Ln();
$pdf->Cell(190,8,"L�assur� est tenu de rembourser la somme avanc�e dans les 45 jours. ",'0','0','L');
$pdf->Ln();
$pdf->Cell(190,8,"L�Assisteur, fournira ce service, contre un d�p�t pr�alable d�une garantie �quivalente � la somme. ",'0','0','L');
$pdf->Ln();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(70,8,"20. Transmission de messages urgents : ",'0','0','L');$pdf->SetFont('Arial','I',10);$pdf->SetFont('');
$pdf->Cell(120,8,"L�Assisteur se chargera de transmettre les messages urgents.",'0','0','L');$pdf->Ln();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(30,8,"21. Informations :  ",'0','0','L');$pdf->SetFont('Arial','I',10);$pdf->SetFont('');
$pdf->Cell(160,8,"L�Assisteur, donnera toutes informations sur le pays de destination concernant les vaccins, le climat,",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"la monnaie, ambassades, fuseaux horaires �etc. ",'0','0','L');
$pdf->Ln();$pdf->Ln(5);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(60,8,"c.	Assurance HADJ et OMRA : ",'0','0','L');$pdf->SetFont('Arial','I',10);$pdf->SetFont('');
$pdf->Cell(130,8,"L�Assureur s�engage � couvrir l�assur� durant son s�jour au Royaume d�Arabie",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"Saoudite dans le cadre exclusif du HADJ et OMRA. ",'0','0','L');
$pdf->Ln();$pdf->Ln(5);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(60,8,"Article 4: MODALITE DE GARANTIE",'0','0','L');$pdf->SetFont('Arial','I',10);$pdf->SetFont('');$pdf->Ln();
$pdf->Cell(190,8,"Afin de proc�der � la souscription des contrats d�assurance, le souscripteur est tenu de transmettre � l�agence  BNA",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"le canevas pr��tabli (annexe N� 03), comprenant les informations suivantes:",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"-	Nom et  pr�nom ;",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"-	Date de naissance ;",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"-	Num�ro et  date de d�livrance du passeport ;",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"-	Num�ro de t�l�phone ainsi que l�adresse e-mail de chaque client ;",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"-	Adresse.",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"Le souscripteur doit joindre les copies des titres de voyage (passeports des clients cit�s dans le canevas).",'0','0','L');$pdf->Ln();
$pdf->Ln(3);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(60,8,"Article 5: PRIME D�ASSURANCE ",'0','0','L');$pdf->SetFont('Arial','I',10);$pdf->SetFont('');$pdf->Ln();
$pdf->Cell(190,8,"La prime � payer par le souscripteur est d�termin�e en fonction de la dur�e de voyage, la zone de couverture,",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"l��ge de l�assur� et la formule choisie. ",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"Des r�ductions sont applicables sur la prime nette pour :",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"-	Les enfants de moins de 16 ans ;",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"-	Les groupes � partir de 10 personnes, voyageant ensemble pour la m�me dur�e et la m�me destination ;",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"-	Formule couple et famille, voyageant ensemble pour la m�me dur�e et la m�me destination.",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"Des majorations sont applicables sur la prime nette pour les personnes d�passant l��ge de 65 ans.",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"Les primes de base appliqu�es  sont d�taill�es en annexe 1. ",'0','0','L');$pdf->Ln();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Ln(20);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(60,8,"Article 6 : PAIEMENT DE LA PRIME",'0','0','L');$pdf->SetFont('Arial','I',10);$pdf->SetFont('');$pdf->Ln();
$pdf->Cell(190,8,"Les primes sont payables par le Souscripteur  ayant contract� l�assurance voyage et assistance pour",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"le compte de ses clients.",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"Le paiement est effectu� au niveau des guichets de l�agence bancaire soit, par :",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"-	Versement esp�ces ;",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"-	Ch�ques ;",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"-	Virement.",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"Le contrat d�assurance ne peut �tre valid� qu�apr�s encaissement  de la prime.",'0','0','L');$pdf->Ln();$pdf->Ln(5);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(60,8,"Article 7: PARTICIPATION BENEFICIAIRE",'0','0','L');$pdf->SetFont('Arial','I',10);$pdf->SetFont('');$pdf->Ln();
$pdf->Cell(190,8,"Une participation b�n�ficiaire est c�d�e annuellement par l�Assureur au Souscripteur au titre de",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"la pr�sente convention d�assurance.",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"Le taux de participation aux b�n�fices est de l�ordre de  5% appliqu�s sur le montant des primes nettes.",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"La participation b�n�ficiaire est  calcul�e � la fin de chaque exercice.",'0','0','L');$pdf->Ln();$pdf->Ln(5);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(60,8,"Article 8: SINISTRES",'0','0','L');$pdf->Ln();$pdf->Ln(3);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(190,8,"a- En cas de sinistre pr�voyance (d�c�s accidentel, IPP/IPT) :",'0','0','L');$pdf->SetFont('Arial','I',10);$pdf->SetFont('');$pdf->Ln();
$pdf->Cell(190,8,"En cas de sinistre, le Souscripteur ou l�assur� devra d�clarer tout sinistre dans un d�lai de sept (07) jours, sauf cas",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"fortuit ou de force majeure.",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"L�Assureur se r�serve le droit � d�signer un expert pour �valuer l�incapacit� permanente partielle ou totale de",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"l�assur� apr�s sa consolidation.",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"L�Assureur proc�dera au r�glement de sinistre dans un d�lai d�un mois apr�s r�ception du rapport d�expertise ",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"et les documents indispensables au paiement du sinistre. ",'0','0','L');$pdf->Ln();$pdf->Ln(3);
$pdf->SetFont('Arial','B',12);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Ln(20);
$pdf->Cell(190,8,"b- En cas de sinistre assistance:",'0','0','L');$pdf->SetFont('Arial','I',10);$pdf->SetFont('');$pdf->Ln();
$pdf->Cell(190,8,"Le souscripteur ou l�assur� devra obligatoirement contacter l�Assiteur, avant tout engagement, dans les 48h qui ",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"suivent la date � laquelle l�assur� a eu connaissance  de l��v�nement,  sous  peine de d�ch�ance de garantie ",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"ou, � minima, de se voir r�clamer les frais suppl�mentaires,  engag�s par l�Assisteur et  qui n�auraient  ",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"normalement pas �t� encourus, si la demande avait �t� d�clar�e dans le d�lai indiqu�.",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"Il recevra un num�ro de dossier et les indications concernant la d�marche � suivre afin de b�n�ficier des prestations",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"prestations li�es aux garanties. Il devra indiquer : ",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"-	Le nom et pr�nom ;",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"-	Le num�ro et les dates de validit� de la police d�assurance ; ",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"-	La date d�entr�e dans le pays de s�jour ; ",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"-	Le num�ro de t�l�phone sur lequel les services de l�Assisteur peuvent le joindre ;",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"-	Le nom, l�adresse et le num�ro de t�l�phone de l�h�pital o� l�assur� a �t� admis ;",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"-	Le nom et l�adresse du m�decin traitant ou du m�decin de famille ;",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"-	Une br�ve description du probl�me.",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"Un   m�decin   expert   commis   par l�Assisteur devra   avoir   libre   acc�s   aupr�s   de l�assur� et du dossier m�dical",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"pour constater le bien-fond� de la demande.",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"L�assur� ne pourra donc pr�tendre � aucun remboursement de frais s�il n�a pas, au pr�alable, re�u l�accord ",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"EXPRESS de l�Assisteur (communication d�un num�ro de dossier).",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"En cas de transport sanitaire, celui-ci s�effectuera par ambulance, chemin de fer ou avion de ligne r�guli�re. ",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"Les transports   par   avion   ambulance sont   limit�s   aux transports intracontinentaux, et cela si la gravit�",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"de l��tat de l�assur�(e) ne permet pas un transport par un autre moyen.",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"Dans tous les cas, le choix du moyen de transport est du seul ressort de l��quipe m�dicale de l�Assisteur.",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"Lorsque le transport sanitaire de l�assur� est  pris en charge, celui-ci est  tenu de restituer � l�Assisteur le billet",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"de retour initialement pr�vu, ou son remboursement. D�s la survenance d�un sinistre, l�assur� doit ",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"user de tous les moyens en son pouvoir pour arr�ter les effets dommageables.",'0','0','L');$pdf->Ln();
//derniere partie
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Ln(20);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(60,8,"Article 9 : REGLEMENT DES LITIGES",'0','0','L');$pdf->SetFont('Arial','I',10);$pdf->SetFont('');$pdf->Ln();
$pdf->Cell(190,8,"Pour toute contestation d�coulant du pr�sent contrat, l�Assureur et le Souscripteur s�engagent avant d�avoir recours �",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"l�arbitrage pr�vu ci-apr�s � formuler par �crit leur point de vue et � se rencontrer pour tenter de r�soudre le litige � l�amiable.",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"Tous les diff�rends qui n�auraient pu �tre r�gl�s par accord amiable dans un d�lai de trois (03) mois � compter du jour ",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"o� la partie la plus diligente aura notifi� son point de vue par �crit, seront r�solus par un tribunal comp�tent.",'0','0','L');$pdf->Ln();$pdf->Ln(5);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(60,8,"Article 10: DUREE DE LA CONVENTION",'0','0','L');$pdf->SetFont('Arial','I',10);$pdf->SetFont('');$pdf->Ln();
$pdf->Cell(190,8,"La pr�sente convention est conclue pour une dur�e d�une (01) ann�e, elle prend effet d�s signature par",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"les parties contractantes.",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"Elle est renouvelable par tacite reconduction annuellement, sauf d�nonciation par l�une des parties par lettre ",'0','0','L');$pdf->Ln();
$pdf->Cell(190,8,"recommand�e trois (03) mois avant chaque �ch�ance.",'0','0','L');$pdf->Ln();$pdf->Ln(40);
$pdf->SetFont('Arial','B',13);
$pdf->Cell(190,8,"Fait �   ".$row_u['adr_user']."   Le    ".date("d/m/Y", strtotime($row_ag['date'])),'0','0','R');$pdf->Ln();$pdf->Ln(30);
$pdf->SetFont('Arial','B',12);
}
}
$pdf->Cell(60,8,"L�Assureur",'0','0','C');$pdf->Cell(65,8,"Le mandataire",'0','0','C');$pdf->Cell(65,8,"Le Souscripteur",'0','0','C');$pdf->Ln();
$pdf->Cell(60,8,"L�Alg�rienne  Vie",'0','0','C');$pdf->Cell(65,8,"L�agence CASH ",'0','0','C');$pdf->Cell(65,8,"L�Agence de voyage",'0','0','C');$pdf->Ln();
$pdf->Image('../img/gg.png',10,218,60);
// Les Annexes
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Ln(20);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(190,8,"ANNEXES",'0','0','C');$pdf->Ln();
$pdf->SetFont('Arial','B',12);
$pdf->Cell(190,8,"ANNEXE 1 : Les tarifs appliqu�s ",'0','0','L');$pdf->Ln();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(190,8,"a. Assurance: ",'0','0','L');$pdf->Ln();$pdf->SetFont('Arial','I',10);$pdf->SetFont('');
$pdf->Cell(60,8,"Capital assur� ",'1','0','C');$pdf->Cell(65,8,"S�jour inf�rieur � 30 jours",'1','0','C');$pdf->Cell(65,8,"S�jour sup�rieur � 30 jours ",'1','0','C');$pdf->Ln();
$pdf->Cell(60,8,"200 000 DZD",'1','0','C');$pdf->Cell(65,8,"164,00 DZD",'1','0','C');$pdf->Cell(65,8,"491,00 DZD",'1','0','C');$pdf->Ln();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(190,8,"b. Assistance : ",'0','0','L');$pdf->Ln();$pdf->SetFont('Arial','I',10);$pdf->SetFont('');
$pdf->Cell(190,8,"Tarif individuel : ",'0','0','L');$pdf->Ln();
$pdf->Cell(60,8,"Dur�e du S�jour (jours)",'1','0','C');$pdf->Cell(65,8,"Prime nette (en DZD) Zone 1",'1','0','C');$pdf->Cell(65,8,"Prime nette (en DZD) Zone 2",'1','0','C');$pdf->Ln();
$pdf->Cell(60,8,"3",'1','0','C');$pdf->Cell(65,8,"471,43",'1','0','C');$pdf->Cell(65,8,"864,29",'1','0','C');$pdf->Ln();
$pdf->Cell(60,8,"8",'1','0','C');$pdf->Cell(65,8,"1 100,00",'1','0','C');$pdf->Cell(65,8,"2 121,43",'1','0','C');$pdf->Ln();
$pdf->Cell(60,8,"10",'1','0','C');$pdf->Cell(65,8,"1 257,14",'1','0','C');$pdf->Cell(65,8,"2 278,57",'1','0','C');$pdf->Ln();
$pdf->Cell(60,8,"15",'1','0','C');$pdf->Cell(65,8,"1 414,29",'1','0','C');$pdf->Cell(65,8,"2 435,71",'1','0','C');$pdf->Ln();
$pdf->Cell(60,8,"21",'1','0','C');$pdf->Cell(65,8,"1 807,14",'1','0','C');$pdf->Cell(65,8,"3 457,14",'1','0','C');$pdf->Ln();
$pdf->Cell(60,8,"30",'1','0','C');$pdf->Cell(65,8,"1 885,71",'1','0','C');$pdf->Cell(65,8,"3 771,43",'1','0','C');$pdf->Ln();
$pdf->Cell(60,8,"60",'1','0','C');$pdf->Cell(65,8,"2 985,71",'1','0','C');$pdf->Cell(65,8,"6 678,57",'1','0','C');$pdf->Ln();
$pdf->Cell(60,8,"90",'1','0','C');$pdf->Cell(65,8,"4 714,29",'1','0','C');$pdf->Cell(65,8,"7 621,43",'1','0','C');$pdf->Ln();
$pdf->Cell(60,8,"180",'1','0','C');$pdf->Cell(65,8,"1 0135,71",'1','0','C');$pdf->Cell(65,8,"10 371,43",'1','0','C');$pdf->Ln();
$pdf->Cell(60,8,"365",'1','0','C');$pdf->Cell(65,8,"1 0921,43",'1','0','C');$pdf->Cell(65,8,"11 628,57",'1','0','C');$pdf->Ln();$pdf->Ln(5);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(20,8,"Zone 1 :",'0','0','L');$pdf->SetFont('Arial','I',10);$pdf->SetFont('');
$pdf->MultiCell(170,8,"Tous les pays du monde , sauf le pays de r�sidence ou d'origine, les USA, le Canada, le Japon et l'Australie. (FMP : 30 000 Euros).",'0','L','');
$pdf->SetFont('Arial','B',10);
$pdf->Cell(20,8,"Zone 2 :",'0','0','L');$pdf->SetFont('Arial','I',10);$pdf->SetFont('');
$pdf->Cell(170,8,"Tous les pays du monde sauf le pays de r�sidence ou d'origine. FMP : (50 000 Euros).",'0','0','L');$pdf->Ln();$pdf->Ln(5);
$pdf->Cell(190,8,"Tarif famille : ",'0','0','L');$pdf->Ln();
$pdf->Cell(40,8,"Nombre de personnes",'1','0','C');$pdf->Cell(150,8,"TARIF (Prime d�assistance)",'1','0','C');$pdf->Ln();
$pdf->Cell(40,8,"Couple",'1','0','C');$pdf->Cell(150,8,"(SOMME[I=1-->I=2](tarif individuel_I x majoration ou r�duction assur�_I)/2) x 1,75",'1','0','C');$pdf->Ln();
$pdf->Cell(40,8,"[3,9] personnes ",'1','0','C');$pdf->Cell(150,8,"(SOMME[I=3-->I=N](tarif individuel_I x majoration ou r�duction assur�_I)/N) x 2,5",'1','0','C');$pdf->Ln();

$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Ln(20);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(190,8,"R�duction et majorations :",'0','0','L');$pdf->Ln();$pdf->SetFont('Arial','I',10);$pdf->SetFont('');
$pdf->Cell(190,8,"Des r�ductions ainsi que des majorations sont calcul�es selon l��ge du client.",'0','0','L');$pdf->Ln();$pdf->Ln(3);
$pdf->Cell(95,8,"Age de l�assur�",'1','0','C');$pdf->Cell(95,8,"Taux (sur prime d�assistance)",'1','0','C');$pdf->Ln();
$pdf->Cell(95,8,"Moins de 12 ans",'1','0','C');$pdf->Cell(95,8,"- 50 %",'1','0','C');$pdf->Ln();
$pdf->Cell(95,8,"De 65 � 79 ans",'1','0','C');$pdf->Cell(95,8,"+ 200 %",'1','0','C');$pdf->Ln();
$pdf->Cell(95,8,"De 80 � 89 ans",'1','0','C');$pdf->Cell(95,8,"+ 300 %",'1','0','C');$pdf->Ln();
$pdf->Ln(3);
$pdf->Cell(190,8,"Des r�ductions pour les groupes voyageant ensemble pour la m�me dur�e et la m�me destination.",'0','0','L');$pdf->Ln();$pdf->Ln(3);
$pdf->Cell(95,8,"Nombre de personnes (groupe)",'1','0','C');$pdf->Cell(95,8,"Taux (sur prime d�assistance)",'1','0','C');$pdf->Ln();
$pdf->Cell(95,8,"De 10 � 25 personnes",'1','0','C');$pdf->Cell(95,8,"- 5 %",'1','0','C');$pdf->Ln();
$pdf->Cell(95,8,"De 26 � 100 personnes ",'1','0','C');$pdf->Cell(95,8,"- 10 %",'1','0','C');$pdf->Ln();
$pdf->Cell(95,8,"De 101 � 200 personnes",'1','0','C');$pdf->Cell(95,8,"- 15 %",'1','0','C');$pdf->Ln();
$pdf->Cell(95,8,"Plus de 200 personnes ",'1','0','C');$pdf->Cell(95,8,"- 25 %",'1','0','C');$pdf->Ln();$pdf->Ln(5);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Ln(20);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(190,8,"ANNEXE 2: limites de garanties en assurance voyage et assistance � l��tranger",'0','0','L');$pdf->Ln();$pdf->SetFont('Arial','I',10);$pdf->SetFont('');$pdf->Ln(3);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(120,8,"Garanties",'1','0','C');$pdf->Cell(70,8,"Limites/Capital-(Franchises)",'1','0','C');$pdf->Ln();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(190,6,"Assurance",'1','0','C');$pdf->Ln();
$pdf->SetFont('Arial','B',8);
//Premiere Partie
$x=$pdf->GetX();
$y=$pdf->GetY();
$pdf->MultiCell(120,6,"D�c�s Accidentel (pour les personnes ag�es de plus de 13 ans) \n Incapacit� Permanente Accidentelle",1,'L',true);
$pdf->SetXY($x,$y);$pdf->SetXY($x+120,$y);
$pdf->MultiCell(70,6,"200 000 DZD \n 200 000 DZD  ",1,'C',true);
//Deuxieme partie
$pdf->SetFont('Arial','B',10);
$pdf->Cell(190,6,"Assistance",'1','0','C');$pdf->Ln();
$pdf->SetFont('Arial','B',8);
$x=$pdf->GetX();
$y=$pdf->GetY();
$pdf->MultiCell(120,6,"Transport sanitaire   \n  Prise en charge des frais m�dicaux, pharmaceutiques, d�hospitalisation et chirurgicaux \n Prise en charge des soins dentaires d'urgence \n Prolongation de s�jour \n Frais de secours et sauvetage \n Visite d'un proche parent \n Rapatriement de corps en cas de d�c�s \n Retour pr�matur� du B�n�ficiaire \n Rapatriement des autres B�n�ficiaires \n Retard de vol et de livraison de bagages \n Perte de bagage \n Assistance juridique \n Avance de caution p�nale \n Transmission de messages urgents \n Manquement de correspondance \n Annulation de voyage \n Informations",1,'L',true);
$pdf->SetXY($x,$y);$pdf->SetXY($x+120,$y);
$pdf->MultiCell(70,6,"Frais r�els  \n Zone 1 : 30 000 EU (40 EU) / Zone 2 : 50 000 EU (40 EU) \n 1 000.00 EU (30 EU) \n 100 EU /Jour (8 Jours Max) \n 1500 EU /b�n�ficiaire/�v�nement \n 100 EU /jour (4 jours Max) \n Frais r�els \n 1 000.00 EU \n Frais r�els \n 1 80 EU \n 20 EU /Kg, 40Kg Max \n 4 000.00 EU \n 10 000.00 EU \n Illimit� \n 100 EU \n Frais de voyage non r�cup�rables \n  Illimit�",1,'C',true);
$pdf->Ln();$pdf->Ln(3);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(190,8,"ANNEXE 3 : Canevas listing � �tablir par le Souscripteur  ",'0','0','L');$pdf->Ln();$pdf->SetFont('Arial','I',10);$pdf->SetFont('');
$pdf->Cell(30,8,"Nom & Pr�nom",'1','0','C');$pdf->Cell(30,8,"Date de naissance",'1','0','C');$pdf->Cell(30,8,"N� Passeport",'1','0','C');
$pdf->Cell(50,8,"Date d�livrance Passeport",'1','0','C');$pdf->Cell(20,8,"Mail",'1','0','C');$pdf->Cell(10,8,"Tel",'1','0','C');
$pdf->Cell(20,8,"Adresse",'1','0','C');$pdf->Ln();
$pdf->Cell(30,8,"XXXXXX",'1','0','C');$pdf->Cell(30,8,"XX/XX/XXX",'1','0','C');$pdf->Cell(30,8," XXXXXX",'1','0','C');
$pdf->Cell(50,8,"XX/XX/XXXX",'1','0','C');$pdf->Cell(20,8,"@",'1','0','C');$pdf->Cell(10,8,"XXX",'1','0','C');
$pdf->Cell(20,8,"XXXXXX",'1','0','C');$pdf->Ln();
$pdf->Cell(30,8,"XXXXXX",'1','0','C');$pdf->Cell(30,8,"XX/XX/XXX",'1','0','C');$pdf->Cell(30,8," XXXXXX",'1','0','C');
$pdf->Cell(50,8,"XX/XX/XXXX",'1','0','C');$pdf->Cell(20,8,"@",'1','0','C');$pdf->Cell(10,8,"XXX",'1','0','C');
$pdf->Cell(20,8,"XXXXXX",'1','0','C');$pdf->Ln();
$pdf->Cell(30,8,"XXXXXX",'1','0','C');$pdf->Cell(30,8,"XX/XX/XXX",'1','0','C');$pdf->Cell(30,8," XXXXXX",'1','0','C');
$pdf->Cell(50,8,"XX/XX/XXXX",'1','0','C');$pdf->Cell(20,8,"@",'1','0','C');$pdf->Cell(10,8,"XXX",'1','0','C');
$pdf->Cell(20,8,"XXXXXX",'1','0','C');$pdf->Ln();
$pdf->Cell(30,8,"XXXXXX",'1','0','C');$pdf->Cell(30,8,"XX/XX/XXX",'1','0','C');$pdf->Cell(30,8," XXXXXX",'1','0','C');
$pdf->Cell(50,8,"XX/XX/XXXX",'1','0','C');$pdf->Cell(20,8,"@",'1','0','C');$pdf->Cell(10,8,"XXX",'1','0','C');
$pdf->Cell(20,8,"XXXXXX",'1','0','C');$pdf->Ln();


$pdf->Output();	
?>








