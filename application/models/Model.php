<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Model extends CI_Model 
{
    // PROPRIETES :
    // ------------
    var $T_visiteur = "visiteur";
    var $T_comptable = "comptable";
    
    
    
    // CONSTRUCTEUR :
    // --------------
    function __construct () 
    {
        parent::__construct();
        $this->load->database();
    }
    
    
    
    // TABLE VISITEUR :
    // ----------------
    // Permet de voir s'il existe bien un couple login et mdp correspondant dans la table visiteur
    function verifierVisiteur ($loginveri, $mdpveri) 
    {
        $query = $this->db->where(array('login'=> $loginveri, 'mdp'=>$mdpveri))->limit(1)->get($this->T_visiteur);
        if(count($query->result_array()))
        {
            return $query->result()[0]->id;
        }
        return false ;
    }
    // Récupére les données du visiteur $id
    function getInformationsVisiteur ($id)
    {
        $this->db->select("*");
        $this->db->from($this->T_visiteur);
        $this->db->where(array("id"=>$id ))->limit(1);
        $query = $this->db->get();
        return $query->result()[0];
    }
    // Fonction getLesVisiteurs: Permet de récupérer tous les visiteurs de la table visiteur
    function getLesVisiteurs() 
    {
        $query = $this->db->get($this->T_visiteur);
        return $query->result();
    }
    
    
    
    // TABLE COMPTABLE :
    // -----------------
    // Permet de voir s'il existe bien un couple login et mdp correspondant dans la table comptable
    function verifierComptable ($login, $mdp)
    {
        $query = $this->db->where(array('login'=>$login, 'mdp'=>$mdp))->limit(1)->get($this->T_comptable);
        if(count($query->result_array()))
        {
            return $query->result()[0]->id;
        }
        return false ;
    }
    // Récupére les données du comptable $id
    function getInformationsComptable ($id = 0)
    {
        if($id==0)
        {
            die("id = 0");
        }
        $this->db->select("*");
        $this->db->from($this->T_comptable);
        $this->db->where(array("id"=>$id))->limit(1);
        $query = $this->db->get();
        return $query->result()[0];
    }
    
    
    
    // TABLE FICHE_FRAIS :
    // -------------------
    // Permet de vérifier si l'utilisateur dispose déjà d'une fiche de frais ouverte pour le mois et l'utilisateur en paramètres
    public function estPremierFraisMois($idVisiteur,$mois)
    {
        $ok = false;
	$req = "select count(*) as nblignesfrais from fichefrais 
                where fichefrais.mois = '$mois' and fichefrais.idvisiteur = '$idVisiteur'";
	$res = $this->db->query($req);
	$laLigne = $res->result_array()[0];
	if($laLigne['nblignesfrais'] == 0)
        {
            $ok = true;
	}
	return $ok;
    }  
    // Retourne le dernier mois en cours d'un visiteur 
    public function dernierMoisSaisi($idVisiteur)
    {
	$req = "select max(mois) as dernierMois from fichefrais where fichefrais.idvisiteur = '$idVisiteur'";
	$res = $this->db->query($req);
	$laLigne = $res->result_array()[0];
	$dernierMois = $laLigne['dernierMois'];
	return $dernierMois;
    }
    // Récupére les informations d'une fiche de frais
    public function getLesInfosFicheFrais($idVisiteur,$mois)
    {
	$req = "select fichefrais.idEtat as idEtat, fichefrais.dateModif as dateModif, fichefrais.nbJustificatifs as nbJustificatifs, 
		fichefrais.montantValide as montantValide, etat.libelle as libEtat from  fichefrais inner join etat on fichefrais.idEtat = etat.id 
		where fichefrais.idvisiteur ='$idVisiteur' and fichefrais.mois = '$mois'";
	$res = $this->db->query($req);
        //die($req);
	$laLigne = $res->result_array()[0];
	return $laLigne;
    }
    // Recupére le nombre de justificatif d'une fiche
    public function getNbjustificatifs($idVisiteur, $mois)
    {
	$req = "select fichefrais.nbjustificatifs as nb from  fichefrais where fichefrais.idvisiteur ='$idVisiteur' and fichefrais.mois = '$mois'";
	$res = $this->db->query($req);
	$laLigne = $res->result_array()[0];
	return $laLigne['nb'];
    }
    // Met à jour le nombre de justificatifs
    public function majNbJustificatifs($idVisiteur, $mois, $nbJustificatifs)
    {
	$req = "update fichefrais set nbjustificatifs = $nbJustificatifs 
        	where fichefrais.idvisiteur = '$idVisiteur' and fichefrais.mois = '$mois'";
	$this->db->simple_query($req);	
    }
    // Récupére la liste des mois dont les fiches de frais sont consultables pour un utilisateur précis
    public function getLesMoisDisponibles($idVisiteur)
    {
	$req = "select fichefrais.mois as mois from  fichefrais where fichefrais.idvisiteur ='$idVisiteur' 
		order by fichefrais.mois desc ";
	$res = $this->db->query($req);
	$lesMois = array();
        //$lesLignes = $res->result();
	//$laLigne = $lesLignes->fetch();
	foreach($res->result_array() as $laLigne)	
        {
            $mois = $laLigne['mois'];
            $numAnnee =substr( $mois,0,4);
            $numMois =substr( $mois,4,2);
            $lesMois["$mois"] = array
            (
                "mois"=>"$mois",
                "numAnnee"  => "$numAnnee",
            	"numMois"  => "$numMois"
            );		
	}
	
        return $lesMois;
    }
    // Fonction qui met à jour l'état d'une fiche de frais d'un visiteur pour un mois précis
    public function majEtatFicheFrais($idVisiteur,$mois,$etat)
    {
	$req = "update ficheFrais set idEtat = '$etat', dateModif = now() 
	where fichefrais.idvisiteur ='$idVisiteur' and fichefrais.mois = '$mois'";
	$this->db->simple_query($req);
    }
    // Fonction permettant de récupérer toutes les fiches dans un certain état
    public function getLesFichesEnEtat($idEtat)
    {
        $req = "select * from fichefrais join visiteur on fichefrais.idVisiteur = visiteur.id where idEtat ='" . $idEtat . "' order by mois desc" ;
        $res = $this->db->query($req);
        $res = $res->result_array();
        return $res;
    }
    // Fonction permettant de récupérer le mois pour lesquels les fiches frais restent encore à valider
    public function recupMoisNoVa($id)
    {
        $query = $this->db->query('SELECT FF.mois FROM fichefrais FF where FF.idVisiteur = "'.$id.'" and (FF.idEtat="CR" or  FF.idEtat="CL") ');
        return $query->result();
    }
    // Fonction permettant de supprimer une fiche frais
    public function supprimerFiche($idVisiteur, $mois)
    {
        $ret = true ;
        $query = "delete from lignefraisforfait where idVisiteur = '".$idVisiteur."' and mois = '".$mois."'" ;
        $ret = $this->db->simple_query($query);
        $query = "delete from lignefraishorsforfait where idVisiteur = '".$idVisiteur."' and mois = '".$mois."'";
        $ret = $this->db->simple_query($query);
        $query = "delete from fichefrais where idVisiteur = '".$idVisiteur."' and mois = '".$mois."' ";
        $ret = $this->db->simple_query($query);
        return $ret ;
    }
    // 
    public function majTotalRembourse($idVisiteur, $mois)
    {
        $query = "update `fichefrais` set montantValide = "
                . " (select sum(montant) from lignefraishorsforfait where idVisiteur = '".$idVisiteur."' and mois = '".$mois."' ) "
                . "where idVisiteur = '".$idVisiteur."' and mois = '".$mois."'" ;
        $this->db->simple_query($query);
    }
    
            
            
    // TABLE FRAIS_FORFAIT :
    // --------------------
    // Récupére les id des frais
    public function getLesIdFrais()
    {
	$req = "select fraisforfait.id as idfrais from fraisforfait order by fraisforfait.id";
        //die($req);
	$res = $this->db->query($req);
	$lesLignes = $res->result_array();
	return $lesLignes;
    }
    
    // Récupére les frais forfaitisés du $mois pour le visiteur $idVisiteur
    public function getLesFraisForfait($idVisiteur, $mois)
    {
	$req = "select fraisforfait.id as idfrais, fraisforfait.libelle as libelle, 
		lignefraisforfait.quantite as quantite from lignefraisforfait inner join fraisforfait 
		on fraisforfait.id = lignefraisforfait.idfraisforfait
		where lignefraisforfait.idvisiteur ='$idVisiteur' and lignefraisforfait.mois='$mois' 
		order by lignefraisforfait.idfraisforfait";	
	$res = $this->db->query($req);
	$lesLignes = $res->result_array();
	return $lesLignes; 
    }
    
    
    
    // TABLE LIGNE_FRAIS_HORS_FORFAIT :
    // --------------------------------
    // Récupére les frais hors forfait du $mois pour le visiteur $idVisiteur
    public function getLesFraisHorsForfait($idVisiteur,$mois)
    {
        $req = "select * from lignefraishorsforfait where lignefraishorsforfait.idvisiteur ='$idVisiteur' 
        	and lignefraishorsforfait.mois = '$mois' ";
        $res = $this->db->query($req);
	$lesLignes = $res->result_array();
	$nbLignes = count($lesLignes);
	for ($i=0; $i<$nbLignes; $i++)
        {
            $date = $lesLignes[$i]['date'];
            $lesLignes[$i]['date'] =  dateAnglaisVersFrancais($date);
	}
	return $lesLignes; 
    }
	
    public function creeNouveauFraisHorsForfait($idVisiteur,$mois,$libelle,$date,$montant)
    {
	$dateFr = dateFrancaisVersAnglais($date);
	$req = "insert into lignefraishorsforfait 
	values('','$idVisiteur','$mois','".htmlspecialchars($libelle)."','$dateFr','$montant')";
        $this->db->simple_query($req);
    }
	
    public function supprimerFraisHorsForfait($idFrais)
    {
	$req = "delete from lignefraishorsforfait where lignefraishorsforfait.id =$idFrais ";
	$this->db->simple_query($req);
    }
    
    
    
    // TABLE LIGNE_FRAIS_FORFAIT :
    // ---------------------------
    // Met à jours les frais forfait
    public function majFraisForfait($idVisiteur, $mois, $lesFrais)
    {
	$lesCles = array_keys($lesFrais);
	foreach($lesCles as $unIdFrais)
        {
            $qte = $lesFrais[$unIdFrais];
            $req = "update lignefraisforfait set lignefraisforfait.quantite = $qte
                    where lignefraisforfait.idvisiteur = '$idVisiteur' and lignefraisforfait.mois = '$mois'
                    and lignefraisforfait.idfraisforfait = '$unIdFrais'";
            $this->db->simple_query($req);
        }		
    }
    
    
    
    // ENREGISTREMENT MULTI TABLES :
    // -----------------------------
    // Créer un nouveau set d'enregistrement de frais forfaitisés pour le visiteur $idVisiteur et le $mois
    public function creeNouvellesLignesFrais($idVisiteur,$mois)
    {
	$dernierMois = $this->dernierMoisSaisi($idVisiteur);
	$laDerniereFiche = $this->getLesInfosFicheFrais($idVisiteur,$dernierMois);
        if($laDerniereFiche['idEtat']=='CR')
        {
            $this->majEtatFicheFrais($idVisiteur, $dernierMois,'CL');			
	}
        $req = "insert into fichefrais(idvisiteur,mois,nbJustificatifs,montantValide,dateModif,idEtat) 
		values('$idVisiteur','$mois',0,0,now(),'CR')";
	$this->db->simple_query($req);
        
	$lesIdFrais = $this->getLesIdFrais();
        foreach($lesIdFrais as $uneLigneIdFrais)
        {
            $unIdFrais = $uneLigneIdFrais['idfrais'];
            $req = "insert into lignefraisforfait(idvisiteur,mois,idFraisForfait,quantite) 
                    values('$idVisiteur','$mois','$unIdFrais',0)";
            $this->db->simple_query($req);
	}
    }
}
