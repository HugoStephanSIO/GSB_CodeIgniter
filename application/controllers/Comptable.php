<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Comptable extends CI_Controller 
{
    // CONSTRUCTEUR :
    // --------------
    function __construct()
    {  
        parent:: __construct();

        $this->load->library('session'); // le gestionnaire de session
        $this->load->model('Model'); // charge le modèle
        $this->load->helper('security');
        $this->load->helper('outils');
        $this->load->library('form_validation'); // le système de validation de formulaire
        $this->load->helper('url'); 
        
    }
    
    // Connexion d'un comptable
    public function chargerComptable ()
    {
        $id = $this->session->flashdata("id");
        $this->session->set_flashdata("id", $id);
        $data['id'] = $id;
        $data['infos'] = $this->Model->getInformationsComptable($id);
        $this->load->view('v_entete', $data);
        $this->load->view('v_sommaire_c', $data);
        $this->load->view("v_accueil", $data);
        $this->load->view('v_pied');
    }  
    
    // Charge la liste des visiteurs
    public function chargerListeVisiteur($idComptable)
    {
        $id = $idComptable;
        $this->session->set_flashdata("id", $id);
        $data['idComptable'] = $id;
        $data['infos'] = $this->Model->getInformationsComptable($id);
        
        // Charge le modèle: getLesVisiteurs
        $unVisiteur = $this->Model->getLesVisiteurs();
        $data['visiteur'] = $unVisiteur;
        $data['titre'] = 'Liste des visiteurs';
        // Chargement des vues
        $this->load->view('v_entete', $data);
        $this->load->view('v_sommaire_c', $data);
        $this->load->view('v_listevisiteurs', $data);
        $this->load->view('v_pied');
    }
    
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
        $this->load->view('v_entete', $data);
        $this->load->view("v_sommaire_c", $data);
        $this->load->view('v_fichesNoVa', $data);
        $this->load->view("v_pied");
    }
    
    // Charge les fiches en état CR
    public function chargerFichesEnEtat($etat, $idComptable)
    {
        $this->session->set_flashdata("id", $idComptable);
        $data["action"] = $etat;
        $data["idComptable"] = $idComptable ;
        $data["infos"] = $this->Model->getInformationsComptable($idComptable);
        $data["lesFiches"] = $this->Model->getLesFichesEnEtat($etat) ;
        
        $data["titre"] = "Les fiches crées";
        
        $this->load->view("v_entete", $data);
        $this->load->view("v_sommaire_c", $data);
        $this->load->view("v_liste_fiches", $data);
        $this->load->view("v_pied");
    }
    
    public function supprimerFiche($idVisiteur, $clef, $idComptable, $action)
    {
        $this->Model->supprimerFiche($idVisiteur, $clef);
        $this->chargerFichesEnEtat($action, $idComptable);
    }
    public function cloturerFiche($idVisiteur, $clef, $idComptable)
    {
        $this->Model->majEtatFicheFrais($idVisiteur, $clef, "CL");
        $this->chargerFichesEnEtat("CR", $idComptable);
    }
    public function validerFiche($idVisiteur, $clef, $idComptable)
    {
        $this->Model->majEtatFicheFrais($idVisiteur, $clef, "VA");
        $this->Model->majTotalRembourse($idVisiteur, $clef);
        $this->chargerFichesEnEtat("CL", $idComptable);
    }
    public function rembourserFiche($idVisiteur, $clef, $idComptable)
    {
        $this->Model->majEtatFicheFrais($idVisiteur, $clef, "RB");
        $this->chargerFichesEnEtat("VA", $idComptable);
    }
    public function consulterFiche($idVisiteur, $clef, $idComptable, $action)
    {
        $leMois = $clef; 
        
        $data["action"] = $action;
        $data["idVisiteur"] = $idVisiteur;
        $data["infos"] = $this->Model->getInformationsComptable($idComptable);
        $data["lesMois"] = $this->Model->getLesMoisDisponibles($idVisiteur);
	$data["moisASelectionner"] = $leMois;
        $data["lesFraisHorsForfait"] = $this->Model->getLesFraisHorsForfait($idVisiteur,$leMois);
        $data["lesFraisForfait"] = $this->Model->getLesFraisForfait($idVisiteur,$leMois);
        $data["lesInfosFicheFrais"] = $this->Model->getLesInfosFicheFrais($idVisiteur,$leMois);
        $data["numAnnee"] = substr($leMois,0,4);
        $data["numMois"] = substr($leMois,4,2);
        $data["libEtat"] = $data["lesInfosFicheFrais"]['libEtat'];
        $data["montantValide"] = $data["lesInfosFicheFrais"]['montantValide'];
        $data["nbJustificatifs"] = $data["lesInfosFicheFrais"]['nbJustificatifs'];
        $dateModif =  $data["lesInfosFicheFrais"]['dateModif'];
        $data["dateModif"] =  dateAnglaisVersFrancais($dateModif);
                
        $this->load->view('v_entete', $data);
        $this->load->view('v_sommaire_c', $data);
        $this->load->view('v_etatFrais', $data);
        $this->load->view('v_pied');
    }
}
