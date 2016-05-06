<?php
/**
 * Fichier application/helpers/outils_helper.php
 * 
 * Contient divers fonctions utilitaires utilisables dans toute l'application code igniter,
 * à condition de charger l'helper avec la ligne :
 * $this->load->helper('outils');
 */




/**
 * Charge le gabarit standard de l'interface + la vue demandée
 * 
 * @param type $obj
 * @param type $data
 * @param type $vue
 */
function modLoad($obj, $data, $vue)
{
    // Le paramètre objet doit être une instance d'une classe
    if(!is_object($obj))
    {
        die("Objet corrompu !");
    }
    
    $obj->load->view('v_entete', $data);
    
    // Charge le sommaire selon s'il s'agit d'un comptable ou d'un visiteur
    if(get_class($obj)=="Comptable")
    {
        $obj->load->view('v_sommaire_c', $data);
    }
    else if(get_class($obj)=="Visiteur")
    {
        $obj->load->view("v_sommaire", $data);
    }
    
    // On peut charger une ou plusieurs vues
    if(!is_array($vue))
    {
        $obj->load->view($vue, $data);
    }
    else
    {
        foreach($vue as $uneVue)
        {
            $obj->load->view($uneVue, $data);
        }
    }
    
    $obj->load->view('v_pied');
}


/** 
 * Formate une clef année mois de type AAAAMM, pour enregistrement dans la BDD, à partir de la date du jour
 * 
 * @param string : $date date("d/m/Y")
 * @return string : la clef au format AAAAMM
 */
function getMois($date)
{
    // Crée une liste pour contenir les 3 parties de la date
    @list($jour,$mois,$annee) = explode('/',$date);
    // Traite les mois à un seul caractère en leur rajoutant un zéro
    if(strlen($mois) == 1)
    {
        $mois = "0".$mois;
    }
    return $annee.$mois;
}


/**
 * Transforme un mois au format chiffre MM en sa version en lettre
 * 
 * @param string : $mois
 * @return string : la version en lettre du $mois
 */
function moisChiffresVersLettres($mois)
{
    $ret = "";
    if(estEntierPositif($mois) && $mois < 13 && $mois > 0)
    {
        switch($mois)
        {
            case 1 : $ret = "Janvier" ; break ;
            case 2 : $ret = "Février" ; break ;
            case 3 : $ret = "Mars" ; break ;
            case 4 : $ret = "Avril" ; break ;
            case 5 : $ret = "Mai" ; break ;
            case 6 : $ret = "Juin" ; break ;
            case 7 : $ret = "Juillet" ; break ;
            case 8 : $ret = "Août" ; break ;
            case 9 : $ret = "Septembre" ; break ;
            case 10 : $ret = "Octobre" ; break ;
            case 11 : $ret = "Novembre" ; break ;
            case 12 : $ret = "Décembre" ; break ;
        }
    }
    return $ret ;
}


/**
 * Transforme une date au format français jj/mm/aaaa vers le format anglais aaaa-mm-jj
 
 * @param string : $madate au format  jj/mm/aaaa
 * @return string : la date au format anglais aaaa-mm-jj
*/
function dateFrancaisVersAnglais($maDate)
{
    // Crée une liste pour contenir les 3 parties de la date
    @list($jour,$mois,$annee) = explode('/',$maDate);
    // Retourne la date au format anglais (compatible mysql) AAAA-MM-JJ
    return date('Y-m-d',mktime(0,0,0,$mois,$jour,$annee));
}


/**
 * Transforme une date au format format anglais aaaa-mm-jj vers le format français jj/mm/aaaa 
 
 * @param string : $madate au format  aaaa-mm-jj
 * @return string : la date au format format français jj/mm/aaaa
*/
function dateAnglaisVersFrancais($maDate)
{
    // Crée une liste pour contenir les 3 parties de la date
    @list($annee,$mois,$jour)=explode('-',$maDate);
    // Reforme la date format JJ/MM/AAAA
    $date="$jour"."/".$mois."/".$annee;
    return $date;
}


/**
 * Vérifie qu'une variable est bien un entier positif
 * 
 * @param integer : $valeur la valeur à vérifier
 * @return boolean : vrai ou faux selon si la valeur est un entier positif ou non
 */
function estEntierPositif($valeur) 
{
    // Utilisation des expressions régulière pour vérifier que la variable, 
    // traitée comme une chaîne, ne contienne que des caractère numériques
    return preg_match("/[^0-9]/", $valeur) == 0;	
}


/**
 * Indique si un tableau de valeurs est constitué d'entiers positifs ou nuls
 
 * @param array[string] : $tabEntiers : le tableau
 * @return boolean : vrai ou faux
*/
function estTableauEntiers($tabEntiers) 
{
    $ok = true;
    foreach($tabEntiers as $unEntier)
    {
        // Parcours le tableau et vérifie que chaque élément est bien un entier positif
    	if(!estEntierPositif($unEntier))
        {
            $ok=false; 
	}
    }
    return $ok;
}


/**
 * Vérifie si une date est inférieure d'un an à la date actuelle
 
 * @param string : $dateTestee 
 * @return boolean : vrai ou faux
*/
function estDateDepassee($dateTestee)
{
    // Récupére la date du jour
    $dateActuelle=date("d/m/Y");
    @list($jour,$mois,$annee) = explode('/',$dateActuelle);
    // Enlève un an et reforme la date
    $annee--;
    $AnPasse = $annee.$mois.$jour;
    // Compare les deux dates
    @list($jourTeste,$moisTeste,$anneeTeste) = explode('/',$dateTestee);
    return ($anneeTeste.$moisTeste.$jourTeste < $AnPasse); 
}


/**
 * Vérifie la validité du format d'une date française jj/mm/aaaa d'une chaine de caractère
 
 * @param string : $date 
 * @return boolean : vrai ou faux
*/
function estDateValide($date)
{
    // Sépare la date avec le séparateur '/' parties
    $tabDate = explode('/',$date);
    $dateOK = true;
    // S'il n'y a pas 3 parties => non valide
    if (count($tabDate) != 3) 
    {
        $dateOK = false;
    }
    else 
    {
        // Si les trois parties de la date ne sont pas tous des entiers => non valide
        if (!estTableauEntiers($tabDate)) 
        {
            $dateOK = false;
        }
        else 
        {
            if (!checkdate($tabDate[1], $tabDate[0], $tabDate[2])) 
            {
                $dateOK = false;
            }
        }
    }
    return $dateOK;
}


/**
 * Vérifie que le tableau de frais ne contient que des valeurs numériques 
 
 * @param array[?] : $lesFrais 
 * @return boolean : vrai ou faux
*/
function lesQteFraisValides($lesFrais)
{
    return estTableauEntiers($lesFrais);
}


/**
 * Vérifie la validité des trois arguments : la date, le libellé du frais et le montant 
 * des message d'erreurs sont ajoutés au tableau des erreurs
 * @param string : $dateFrais 
 * @param string : $libelle 
 * @param float : $montant
 */
function valideInfosFrais($dateFrais,$libelle,$montant)
{
    $noError = true ;
    if($dateFrais=="")
    {
	$noError = false ;
    }
    else
    {
	if(!estDatevalide($dateFrais))
        {
            $noError = false ;
	}	
        else
        {
            if(estDateDepassee($dateFrais))
            {
		$noError = false ;
            }			
	}
    }
    if($libelle == "")
    {
        $noError = false ;
    }
    if($montant == "")
    {
        $noError = false ;
    }
    else 
    {
        if( !is_numeric($montant) )
        {
            $noError = false ;
	}
    }
    
    return $noError ;
}
?>