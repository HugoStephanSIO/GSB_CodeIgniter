<?php
/**
 * Fichier application/controllers/Connect.php
 * 
 * Contient la classe Connect
 */


defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Classe Connect : contrôleur gérant la connexion des visiteus et des comptables en aiguillant sur le bon sous controleur
 */
class Connect extends CI_Controller 
{
    // CONSTRUCTEUR :
    // --------------
    function __construct()
    {  
        parent:: __construct();

        $this->load->library('session'); // le gestionnaire de session
        $this->load->model('Model'); // charge le modèle
        $this->load->helper('security');
        $this->load->library('form_validation'); // le système de validation de formulaire
        $this->load->helper('url');
    }
    
    
    /**
     * Affiche le formulaire de connexion
     */
    public function index()
    {
        // Si le chargement s'est mal passé, renvoie un message d'erreur
        if ($this->session->flashdata("erreur")!="")
        {
            $data["erreur"] = $this->session->flashdata("erreur");
        }
        // Si le chargement a bien fonctionné
        if ($this->session->flashdata("success")!="")
        {
            $data["success"] = $this->session->flashdata("success");
        }
        
        // Chargement des vues
	$this->load->view('v_entete');
        $this->load->view('v_connexion');
        $this->load->view('v_pied');
    }
        
    
    /**
     * Validation des identifiants visiteur ou comptable selon l'option choisie
     */
    public function connecter ()
    {
        // Lorsqu'on clique sur valider sur le formulaire
        if($this->input->post("valider")) 
        {            
            // Récupére le login et le mdp rentrés par l'utilisateur
            $login = $this->input->post('login');
            $mdp = $this->input->post('mdp');
            if($this->input->post('type_co')=="Visiteur")
            {
                $this->connecterVisiteur($login, $mdp);
            }
            else if($this->input->post('type_co')=="Comptable")
            {
                $this->connecterComptable($login, $mdp);
            }
        } 
    }
    
    
    /**
     * Connexion d'un visiteur, appel du controleur Visiteur
     * 
     * @param type $login
     * @param type $mdp
     */
    public function connecterVisiteur ($login, $mdp)
    {
        $id = $this->Model->verifierVisiteur($login, $mdp);
        if($id!=false)
        {
            // On enregistre en session flash l'id du membre 
            $this->session->set_flashdata("id",$this->Model->verifierVisiteur($login, $mdp));
            // On charge la page d'accueil visiteur
            redirect(site_url("Visiteur/chargerVisiteur/"));
        }
        else
        {
            // Retour à la page de connexion avec un message d'erreur  
            $this->session->set_flashdata('erreur', "Identifiant et/ou mot de passe incorrects ! Veuillez réessayer.");
            redirect('Connect', 'refresh');
        }
    }
    
    
    /**
     * Connexion d'un comptable, appel du controleur Comptable
     * 
     * @param type $login
     * @param type $mdp
     */
    public function connecterComptable ($login, $mdp)
    {
        $id = $this->Model->verifierComptable($login, $mdp);
        if($id!=false)
        {
            // On enregistre en session flash l'id du membre 
            $this->session->set_flashdata("id", $this->Model->verifierComptable($login, $mdp));
            // On charge la page d'accueil comptable
            redirect(site_url("Comptable/chargerComptable/"));
        }
        else
        {
            $this->session->set_flashdata('erreur', 'Identifiant et/ou mot de passe incorrects ! Veuillez réessayer.');
            redirect("Connect", 'refresh');
        }
    }
}

?>