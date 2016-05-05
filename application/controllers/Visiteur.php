<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Visiteur extends CI_Controller 
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

    // Connexion d'un visiteur
    public function chargerVisiteur () 
    {
        // On enregistre l'id en session flash
        $id = $this->session->flashdata("id");
        $this->session->set_flashdata("id", $id);
        if ($this->input->post('login')!="") 
        {
            // Si c'est le premier chargement depuis le formulaire de connexion, on dit bonjour
            $success = "Connexion réussi! Bonjour ".$this->input->post('login')." !";
            $data['success'] = $success;
        }
        // On renvoie l'id du visiteur connecté
        $data['id'] = $id;
        $data["infos"] = $this->Model->getInformationsVisiteur($id);
        // Chargement des vues
        $this->load->view('v_entete', $data);
        $this->load->view('v_sommaire');
        $this->load->view("v_accueil", $data);
        $this->load->view('v_pied');
    }  
    
    
    // Page de modification de la fiche du mois en cours
    public function saisirFiche($id)
    {
        // On renvoie l'id du visiteur connecté
        $data['idVisiteur'] = $id;
        // On récupère les informations du visiteur connecté
        $data["infos"] = $this->Model->getInformationsVisiteur($id);
        $data["mois"] = getMois(date("d/m/Y"));
        $data["numAnnee"] = substr($data["mois"],0,4);
        $data["numMois"] = substr($data["mois"],4,2);
        
        // S'il n'y a pas encore de frais pour ce mois on créé les lignes dans la table
        if($this->Model->estPremierFraisMois($id, $data["mois"]))
        {
            $this->Model->creeNouvellesLignesFrais($id,$data["mois"]);
        }
        
        // Charge les frais dans des variables pour les transmettre à la vue
        $data["lesFraisHorsForfait"] = $this->Model->getLesFraisHorsForfait($id, $data["mois"]);
        $data["lesFraisForfait"] = $this->Model->getLesFraisForfait($id, $data["mois"]);
        
        // On charge les vues
        $this->load->view('v_entete', $data);
        $this->load->view('v_sommaire', $data);
        $this->load->view('v_saisieFicheFrais');
        $this->load->view('v_pied');
    }
    
    
    // Validation de la modification des frais forfait d'un visiteur pour un mois précis
    public function modifierFraisForfait()
    {
        $lesFrais = $_REQUEST['lesFrais'];
        // 
        if(lesQteFraisValides($lesFrais))
        {
            $this->Model->majFraisForfait($_POST["idVisiteur"], $_POST["mois"], $lesFrais);
        }
        else
        {
            $this->session->set_flashdata("erreur", "Veuillez ne saisir que des nombres de frais forfaits positifs");
        }
        $this->saisirFiche($_POST["idVisiteur"]);
    }
    
    
    // Création d'un frais hors forfait pour un visiteur et un mois précis
    public function creerFraisHorsForfait()
    {
        // Récupère le contenu des variables
        $idVisiteur = $_REQUEST["idVisiteur"];
        $mois = $_REQUEST["mois"];
        $dateFrais = $_REQUEST['dateFrais'];
        $libelle = $_REQUEST['libelle'];
	$montant = $_REQUEST['montant'];

        // Si on veut ajouter une fiche frais hors forfait, on récupère les variables en paramètre 
        if(valideInfosFrais($dateFrais, $libelle, $montant))
        {
            // On charge la vue qui va permettre de créer une nouvelle fiche hors forfait
            $this->Model->creeNouveauFraisHorsForfait($idVisiteur,$mois,$libelle,$dateFrais,$montant);
        }
        else
        {
            // Sinon erreur: champ mal renseigné, année trop ancienne...
            $this->session->set_flashdata("erreur", "Veuillez remplir correctement les champs afin d'ajouter un nouveau frais hors forfait");
        }
        
        $this->saisirFiche($idVisiteur);
    }
    
    
    // Supprimer un frais hors forfait
    public function supprimerFraisHorsForfait($idFrais, $idVisiteur)
    {
	$this->Model->supprimerFraisHorsForfait($idFrais);
        $this->saisirFiche($idVisiteur);
    }
    
    
    // Affiche la liste des mois dont le visiteur peut consulter les fiches de frais
    public function selectionnerMois($idVisiteur)
    {
        $data["idVisiteur"] = $idVisiteur;
        $data["infos"] = $this->Model->getInformationsVisiteur($idVisiteur);
        $data["lesMois"] = $this->Model->getLesMoisDisponibles($idVisiteur);
        $data["lesCles"] = array_keys( $data["lesMois"] );
        $data["moisASelectionner"] = $data["lesCles"][0];
        // On charge les vues
        $this->load->view('v_entete', $data);
        $this->load->view('v_sommaire', $data);
        $this->load->view('v_listeMois', $data);
        $this->load->view('v_pied');
    }
    
    
    // Affiche l'état des frais du mois sélectionné
    public function voirEtatFraisMois()
    {
        $leMois = $_REQUEST['lstMois']; 
        $idVisiteur = $_POST["idVisiteur"];
        
        $data["idVisiteur"] = $idVisiteur;
        $data["infos"] = $this->Model->getInformationsVisiteur($idVisiteur);
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
        $this->load->view('v_sommaire', $data);
        $this->load->view('v_listeMois', $data);
        $this->load->view('v_etatFrais', $data);
        $this->load->view('v_pied');
    }
}
