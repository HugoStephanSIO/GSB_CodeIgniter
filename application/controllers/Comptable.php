<?php
/**
 * Fichier application/controllers/Comptable.php
 * 
 * Contient la classe Comptable
 */


defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Classe Comptable : contrôleur gérant les actions et les vues des comptables en utilisant le modèle
 */
class Comptable extends CI_Controller 
{
    // CONSTRUCTEUR :
    // --------------
    function __construct()
    {  
        parent:: __construct();

        $this->load->library('session'); // le gestionnaire de session
        $this->load->model('Model'); // charge le modèle
        $this->load->helper('outils');
        $this->load->helper('url'); 
    }
    
    
    
    
    // FONCTIONS APPELANT DES VUES :
    // -----------------------------
    /**
     *  Connexion d'un comptable
     */
    public function chargerComptable ()
    {
        // Remet l'id en session et en variable transmis aux vues
        $id = $this->session->flashdata("id");
        $this->session->set_flashdata("id", $id);
        $data['id'] = $id;
        $data['infos'] = $this->Model->getInformationsComptable($id);
        modLoad($this, $data, "v_accueil");
    }  
    /**
     * Charge la liste des visiteurs
     * 
     * @param type $idComptable 
     */
    public function chargerListeVisiteur($idComptable)
    {
        // Remet l'id en session et en variable transmis aux vues
        $id = $idComptable;
        $this->session->set_flashdata("id", $id);
        $data['idComptable'] = $id;
        $data['infos'] = $this->Model->getInformationsComptable($id);
        
        // Récupére les visisteurs dans la bdd
        $unVisiteur = $this->Model->getLesVisiteurs();
        $data['visiteur'] = $unVisiteur;
        $data['titre'] = 'Liste des visiteurs';
        
        // Chargement des vues
        modLoad($this, $data, 'v_listevisiteurs');
    }
    /**
     * Récupére les fiches de frais non validées d'un visiteur
     * 
     * @param type $idVisiteur
     * @param type $idComptable 
     */
    public function ficheNoVa($idVisiteur, $idComptable)
    {           
        $data['idVisiteur'] = $idVisiteur;
        $data['infos'] = $this->Model->getInformationsComptable($idComptable);
        $this->session->set_flashdata("id", $idComptable); 
        
        // Charge le modèle : getLeVisiteur
        $unV = $this->Model->getInformationsVisiteur($idVisiteur);
        $data['visiteur'] = $unV;
        // Charge le modèle : recupMoisNoVa 
        $uneFiche = $this->Model->recupMoisNoVa($idVisiteur); 
        $data['ficheNoVa'] = $uneFiche; 
        // Titre de la page concerné
        $data['titre'] = "Informations des fiches frais non validées";
        // Chargement des vues
        modLoad($this,$data,'v_fichesNoVa');
    }
    /**
     * Charge les fiches dans un certain état
     * 
     * @param type $etat l'état des fiches que l'ont veut afficher
     * @param type $idComptable 
     */
    public function chargerFichesEnEtat($etat, $idComptable)
    {
        // Remet l'id en variable de session
        $this->session->set_flashdata("id", $idComptable);
        
        // Transmet à la vue l'état des fiches que l'on affiche
        $data["action"] = $etat;
        // Et les fiches récupérées par le model
        $data["lesFiches"] = $this->Model->getLesFichesEnEtat($etat) ;
        $data["idComptable"] = $idComptable ;
        $data["infos"] = $this->Model->getInformationsComptable($idComptable);
        
        $data["titre"] = "Liste des fiches en état ". $etat;
        
        modLoad($this, $data, "v_liste_fiches");
    }
    /**
     * Affiche les détails d'une fiche dans une vue
     * 
     * @param type $idVisiteur
     * @param type $clef
     * @param type $idComptable
     * @param type $action
     */
    public function consulterFiche($idVisiteur, $clef, $idComptable, $action)
    {
        $leMois = $clef; 
        
        $data["action"] = $action;
        $data["idVisiteur"] = $idVisiteur;
        $data["moisASelectionner"] = $leMois;
        
        // Données récupérées à partir du model, à transmettre aux vues
        $data["infos"] = $this->Model->getInformationsComptable($idComptable);  
        $data["lesMois"] = $this->Model->getLesMoisDisponibles($idVisiteur);
        $data["lesFraisHorsForfait"] = $this->Model->getLesFraisHorsForfait($idVisiteur,$leMois);
        $data["lesFraisForfait"] = $this->Model->getLesFraisForfait($idVisiteur,$leMois);
        $data["lesInfosFicheFrais"] = $this->Model->getLesInfosFicheFrais($idVisiteur,$leMois);
        
        // Découpage de la clef AAAAMM
        $data["numAnnee"] = substr($leMois,0,4);
        $data["numMois"] = substr($leMois,4,2);
        
        // Découpage de la ligne d'informations sur la fiche
        $data["libEtat"] = $data["lesInfosFicheFrais"]['libEtat'];
        $data["montantValide"] = $data["lesInfosFicheFrais"]['montantValide'];
        $data["nbJustificatifs"] = $data["lesInfosFicheFrais"]['nbJustificatifs'];
        $dateModif =  $data["lesInfosFicheFrais"]['dateModif'];
        $data["dateModif"] =  dateAnglaisVersFrancais($dateModif);
                
        // Chargement des vues
        modLoad($this, $data, 'v_etatFrais');
    }
    
    
    
    
    // FONCTIONS GESTION DES FICHES :
    // ------------------------------
    /**
     * Supprime une fiche précise
     * 
     * @param type $idVisiteur le visiteur de la fiche à supprimer
     * @param type $clef la date de la fiche à supprimer
     * @param type $idComptable 
     * @param type $action l'état de la fiche pour savoir à quel liste de fiche retourner
     */
    public function supprimerFiche($idVisiteur, $clef, $idComptable, $action)
    {
        $this->Model->supprimerFiche($idVisiteur, $clef);
        $this->chargerFichesEnEtat($action, $idComptable);
    } 
    /**
     * Cloture une fiche précise
     * 
     * @param type $idVisiteur le visiteur de la fiche à clôturer
     * @param type $clef la date de la fiche à cloturer
     * @param type $idComptable
     */
    public function cloturerFiche($idVisiteur, $clef, $idComptable)
    {
        $this->Model->majEtatFicheFrais($idVisiteur, $clef, "CL");
        $this->chargerFichesEnEtat("CR", $idComptable);
    }
    /**
     * Valide une fiche précise
     * 
     * @param type $idVisiteur le visiteur de la fiche à valider
     * @param type $clef la date de la fiche à valider
     * @param type $idComptable
     */
    public function validerFiche($idVisiteur, $clef, $idComptable)
    {
        $this->Model->majEtatFicheFrais($idVisiteur, $clef, "VA");
        $this->Model->majTotalRembourse($idVisiteur, $clef);
        $this->chargerFichesEnEtat("CL", $idComptable);
    }
    /**
     * Rembourse une fiche précise
     * 
     * @param type $idVisiteur le visiteur de la fiche à rembourser
     * @param type $clef la date de la fiche à rembourser
     * @param type $idComptable
     */
    public function rembourserFiche($idVisiteur, $clef, $idComptable)
    {
        $this->Model->majEtatFicheFrais($idVisiteur, $clef, "RB");
        $this->chargerFichesEnEtat("VA", $idComptable);
    }
}
