<?php
/**
 * Fichier application/models/Model.php
 * 
 * Contient la classe Model
 */


defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Classe Model : gère la lecture et l'écriture dans la base de données gsbv2
 * voir le fichier application/config/database.php pour plus de détails
 */
class Model extends CI_Model 
{
    // PROPRIETES :
    // ------------
    var $T_visiteur = "visiteur";
    var $T_comptable = "comptable";
    var $T_fiches = "fichefrais";
    var $T_etat = "etat";
    var $T_LFF = "lignefraisforfait";
    var $T_LFHF = "lignefraishorsforfait";
    var $T_FF = "fraisforfait";

    
    
    // CONSTRUCTEUR :
    // --------------
    function __construct () 
    {
        parent::__construct();
        $this->load->database();
    }
    
    
    
    
    // TABLE VISITEUR :
    // ----------------
    /**
     * Permet de voir s'il existe bien un couple login et mdp correspondant dans la table visiteur
     * 
     * @param type $loginveri
     * @param type $mdpveri
     * @return boolean 
     */
    function verifierVisiteur ($loginveri, $mdpveri) 
    {
        $query = $this->db->where(array('login'=> $loginveri, 'mdp'=>$mdpveri))->limit(1)->get($this->T_visiteur);
        if(count($query->result_array()))
        {
            return $query->result()[0]->id;
        }
        return false ;
    }
    /**
     * Récupére les données du visiteur $id
     * 
     * @param type $id
     * @return type 
     */
    function getInformationsVisiteur ($id)
    {
        $this->db->select("*");
        $this->db->from($this->T_visiteur);
        $this->db->where(array("id"=>$id ))->limit(1);
        $query = $this->db->get();
        return $query->result()[0];
    }
    /**
     * Fonction getLesVisiteurs: Permet de récupérer tous les visiteurs de la table visiteur
     * 
     * @return type 
     */
    function getLesVisiteurs() 
    {
        $query = $this->db->get($this->T_visiteur);
        return $query->result();
    }
    
    
    
    
    // TABLE COMPTABLE :
    // -----------------
    /**
     * Permet de voir s'il existe bien un couple login et mdp correspondant dans la table comptable
     * 
     * @param type $login
     * @param type $mdp
     * @return boolean 
     */
    function verifierComptable ($login, $mdp)
    {
        $query = $this->db->where(array('login'=>$login, 'mdp'=>$mdp))->limit(1)->get($this->T_comptable);
        if(count($query->result_array()))
        {
            return $query->result()[0]->id;
        }
        return false ;
    }
    /**
     * Récupére les données du comptable $id
     * 
     * @param type $id
     * @return type 
     */
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
    /**
     * Permet de vérifier si l'utilisateur dispose déjà d'une fiche de frais ouverte pour le mois et l'utilisateur en paramètres
     * @param type $idVisiteur
     * @param type $mois
     * @return boolean 
     */
    public function estPremierFraisMois($idVisiteur,$mois)
    {
        $ok = false;
	$req = "select count(*) as nblignesfrais from $this->T_fiches 
                where $this->T_fiches.mois = '$mois' and $this->T_fiches.idvisiteur = '$idVisiteur'";
	$res = $this->db->query($req);
	$laLigne = $res->result_array()[0];
	if($laLigne['nblignesfrais'] == 0)
        {
            $ok = true;
	}
	return $ok;
    }  
    /**
     * Retourne le dernier mois en cours d'un visiteur 
     * 
     * @param type $idVisiteur
     * @return type 
     */
    public function dernierMoisSaisi($idVisiteur)
    {
	$req = "select max(mois) as dernierMois from $this->T_fiches where $this->T_fiches.idvisiteur = '$idVisiteur'";
	$res = $this->db->query($req);
	$laLigne = $res->result_array()[0];
	$dernierMois = $laLigne['dernierMois'];
	return $dernierMois;
    }
    /**
     * Récupére les informations d'une fiche de frais
     * 
     * @param type $idVisiteur
     * @param type $mois
     * @return type 
     */
    public function getLesInfosFicheFrais($idVisiteur,$mois)
    {
	$req = "select $this->T_fiches.idEtat as idEtat, $this->T_fiches.dateModif as dateModif, $this->T_fiches.nbJustificatifs as nbJustificatifs, 
		$this->T_fiches.montantValide as montantValide, $this->T_etat.libelle as libEtat from  $this->T_fiches inner join $this->T_etat on $this->T_fiches.idEtat = $this->T_etat.id 
		where $this->T_fiches.idvisiteur ='$idVisiteur' and $this->T_fiches.mois = '$mois'";
	$res = $this->db->query($req);
        //die($req);
	$laLigne = $res->result_array()[0];
	return $laLigne;
    }
    /**
     * Recupére le nombre de justificatif d'une fiche
     * 
     * @param type $idVisiteur
     * @param type $mois
     * @return type 
     */
    public function getNbjustificatifs($idVisiteur, $mois)
    {
	$req = "select $this->T_fiches.nbjustificatifs as nb from  $this->T_fiches where $this->T_fiches.idvisiteur ='$idVisiteur' and $this->T_fiches.mois = '$mois'";
	$res = $this->db->query($req);
	$laLigne = $res->result_array()[0];
	return $laLigne['nb'];
    }
    /**
     * Met à jour le nombre de justificatifs
     * 
     * @param type $idVisiteur
     * @param type $mois
     * @param type $nbJustificatifs 
     */
    public function majNbJustificatifs($idVisiteur, $mois, $nbJustificatifs)
    {
	$req = "update $this->T_fiches set nbjustificatifs = $nbJustificatifs 
        	where $this->T_fiches.idvisiteur = '$idVisiteur' and $this->T_fiches.mois = '$mois'";
	$this->db->simple_query($req);	
    }
    /**
     * Récupére la liste des mois dont les fiches de frais sont consultables pour un utilisateur précis
     * 
     * @param type $idVisiteur
     * @return type 
     */
    public function getLesMoisDisponibles($idVisiteur)
    {
	$req = "select $this->T_fiches.mois as mois from  $this->T_fiches where $this->T_fiches.idvisiteur ='$idVisiteur' 
		order by $this->T_fiches.mois desc ";
	$res = $this->db->query($req);
	$lesMois = array();
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
    /**
     * Fonction qui met à jour l'état d'une fiche de frais d'un visiteur pour un mois précis
     * 
     * @param type $idVisiteur
     * @param type $mois
     * @param type $etat 
     */
    public function majEtatFicheFrais($idVisiteur,$mois,$etat)
    {
	$req = "update $this->T_fiches set idEtat = '$etat', dateModif = now() 
	where $this->T_fiches.idvisiteur ='$idVisiteur' and $this->T_fiches.mois = '$mois'";
	$this->db->simple_query($req);
    }
    /**
     * Fonction permettant de récupérer toutes les fiches dans un certain état
     * 
     * @param type $idEtat
     * @return type 
     */
    public function getLesFichesEnEtat($idEtat)
    {
        $req = "select * from $this->T_fiches join visiteur on $this->T_fiches.idVisiteur = visiteur.id where idEtat ='" . $idEtat . "' order by mois desc" ;
        $res = $this->db->query($req);
        $res = $res->result_array();
        return $res;
    }
    /**
     * Fonction permettant de récupérer le mois pour lesquels les fiches frais restent encore à valider
     * 
     * @param type $id
     * @return type 
     */
    public function recupMoisNoVa($id)
    {
        $query = $this->db->query("SELECT FF.mois FROM $this->T_fiches FF where FF.idVisiteur = '".$id."' and (FF.idEtat='CR' or  FF.idEtat='CL') ");
        return $query->result();
    }
    /**
     * Met à jour le montantValidé d'une fiche de frais à partir de la somme des montant de ses frais hors forfait
     * 
     * @param type $idVisiteur
     * @param type $mois
     */
    public function majTotalRembourse($idVisiteur, $mois)
    {
        $query = "update `$this->T_fiches` set montantValide = "
                . " (select sum(montant) from $this->T_LFHF where idVisiteur = '".$idVisiteur."' and mois = '".$mois."' ) "
                . "where idVisiteur = '".$idVisiteur."' and mois = '".$mois."'" ;
        $this->db->simple_query($query);
    }
    
    
            
            
    // TABLE FRAIS_FORFAIT :
    // --------------------
    /**
     * Récupére les id des frais
     */
    public function getLesIdFrais()
    {
	$req = "select $this->T_FF.id as idfrais from $this->T_FF order by $this->T_FF.id";
	$res = $this->db->query($req);
	$lesLignes = $res->result_array();
	return $lesLignes;
    }
    /**
     * Récupére les frais forfaitisés du $mois pour le visiteur $idVisiteur
     * 
     * @param type $idVisiteur
     * @param type $mois
     * @return type 
     */
    public function getLesFraisForfait($idVisiteur, $mois)
    {
	$req = "select $this->T_FF.id as idfrais, $this->T_FF.libelle as libelle, 
		$this->T_LFF.quantite as quantite from $this->T_LFF inner join $this->T_FF 
		on $this->T_FF.id = $this->T_LFF.idfraisforfait
		where $this->T_LFF.idvisiteur ='$idVisiteur' and $this->T_LFF.mois='$mois' 
		order by $this->T_LFF.idfraisforfait";	
	$res = $this->db->query($req);
	$lesLignes = $res->result_array();
	return $lesLignes; 
    }
    
    
    
    
    // TABLE LIGNE_FRAIS_HORS_FORFAIT :
    // --------------------------------
    /**
     * Récupére les frais hors forfait du $mois pour le visiteur $idVisiteur
     * 
     * @param type $idVisiteur
     * @param type $mois
     * @return type
     */
    public function getLesFraisHorsForfait($idVisiteur,$mois)
    {
        $req = "select * from $this->T_LFHF where $this->T_LFHF.idvisiteur ='$idVisiteur' 
        	and $this->T_LFHF.mois = '$mois' ";
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
    /**
     * Crée un nouveau frais hors forfait 
     * 
     * @param type $idVisiteur
     * @param type $mois
     * @param type $libelle
     * @param type $date
     * @param type $montant
     */
    public function creeNouveauFraisHorsForfait($idVisiteur,$mois,$libelle,$date,$montant)
    {
	$dateFr = dateFrancaisVersAnglais($date);
	$req = "insert into $this->T_LFHF 
	values('','$idVisiteur','$mois','".htmlspecialchars($libelle)."','$dateFr','$montant')";
        $this->db->simple_query($req);
    }
    /**
     * Supprime un frais hors rofait de la base de données
     * @param type $idFrais
     */
    public function supprimerFraisHorsForfait($idFrais)
    {
	$req = "delete from $this->T_LFHF where $this->T_LFHF.id =$idFrais ";
	$this->db->simple_query($req);
    }
    
    
    
    
    // TABLE LIGNE_FRAIS_FORFAIT :
    // ---------------------------
    /**
     * Met à jours les frais forfait
     * 
     * @param type $idVisiteur
     * @param type $mois
     * @param type $lesFrais tableau associatif contenant la quantité de chaque type de frais forfait
     */
    public function majFraisForfait($idVisiteur, $mois, $lesFrais)
    {
	$lesCles = array_keys($lesFrais);
	foreach($lesCles as $unIdFrais)
        {
            $qte = $lesFrais[$unIdFrais];
            $req = "update $this->T_LFF set $this->T_LFF.quantite = $qte
                    where $this->T_LFF.idvisiteur = '$idVisiteur' and $this->T_LFF.mois = '$mois'
                    and $this->T_LFF.idfraisforfait = '$unIdFrais'";
            $this->db->simple_query($req);
        }		
    }
    
    
    
    
    // ENREGISTREMENT MULTI TABLES :
    // -----------------------------
    /**
     * Crée un nouveau set d'enregistrement de frais forfaitisés pour le visiteur $idVisiteur et le $mois
     * 
     * @param type $idVisiteur
     * @param type $mois 
     */
    public function creeNouvellesLignesFrais($idVisiteur,$mois)
    {
	$dernierMois = $this->dernierMoisSaisi($idVisiteur);
	$laDerniereFiche = $this->getLesInfosFicheFrais($idVisiteur,$dernierMois);
        if($laDerniereFiche['idEtat']=='CR')
        {
            $this->majEtatFicheFrais($idVisiteur, $dernierMois,'CL');			
	}
        
        // Crée une nouvelle fiche de frais
        $req = "insert into $this->T_fiches(idvisiteur,mois,nbJustificatifs,montantValide,dateModif,idEtat) 
		values('$idVisiteur','$mois',0,0,now(),'CR')";
	$this->db->simple_query($req);
        
	$lesIdFrais = $this->getLesIdFrais();
        foreach($lesIdFrais as $uneLigneIdFrais)
        {
            $unIdFrais = $uneLigneIdFrais['idfrais'];
            // Crée une nouvelle ligne de frais forfait pour chaque type avec la quantité à 0
            $req = "insert into $this->T_LFF(idvisiteur,mois,idFraisForfait,quantite) 
                    values('$idVisiteur','$mois','$unIdFrais',0)";
            $this->db->simple_query($req);
	}
    }
    /**
     * Fonction permettant de supprimer une fiche frais
     * 
     * @param type $idVisiteur
     * @param type $mois
     * @return type 
     */
    public function supprimerFiche($idVisiteur, $mois)
    {
        $ret = true ;
        // On doit commencer par supprimer toutes les lignes de frais forfait concernant cette fiche de frais
        $query = "delete from $this->T_LFF where idVisiteur = '".$idVisiteur."' and mois = '".$mois."'" ;
        $ret = $this->db->simple_query($query);
        // Puis toutes les lignes de frais hors forfait concernant cette fiche de frais
        $query = "delete from $this->T_LFHF where idVisiteur = '".$idVisiteur."' and mois = '".$mois."'";
        $ret = $this->db->simple_query($query);
        // Enfin on peut supprimer la fiche de frais elle même
        $query = "delete from $this->T_fiches where idVisiteur = '".$idVisiteur."' and mois = '".$mois."' ";
        $ret = $this->db->simple_query($query);
        return $ret ;
    }
}
