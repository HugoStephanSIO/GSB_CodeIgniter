<?php

/* 
 * Formate une clef année mois de type AAAAMM, pour enregistrement dans la BDD, à partir de la date du jour
 * 
 * @param $date date("d/m/Y")
 * @return la clef au format AAAAMM
 */
function getMois($date)
{
    @list($jour,$mois,$annee) = explode('/',$date);
    if(strlen($mois) == 1)
    {
        $mois = "0".$mois;
    }
    return $annee.$mois;
}

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
 
 * @param $madate au format  jj/mm/aaaa
 * @return la date au format anglais aaaa-mm-jj
*/
function dateFrancaisVersAnglais($maDate)
{
    @list($jour,$mois,$annee) = explode('/',$maDate);
    return date('Y-m-d',mktime(0,0,0,$mois,$jour,$annee));
}

/**
 * Transforme une date au format format anglais aaaa-mm-jj vers le format français jj/mm/aaaa 
 
 * @param $madate au format  aaaa-mm-jj
 * @return la date au format format français jj/mm/aaaa
*/
function dateAnglaisVersFrancais($maDate)
{
    @list($annee,$mois,$jour)=explode('-',$maDate);
    $date="$jour"."/".$mois."/".$annee;
    return $date;
}

/**
 * Vérifie qu'une variable est bien un entier positif
 * 
 * @param $valeur la valeur à vérifier
 * @return vrai ou faux selon si la valeur est un entier positif ou non
 */
function estEntierPositif($valeur) 
{
    return preg_match("/[^0-9]/", $valeur) == 0;	
}

/**
 * Indique si un tableau de valeurs est constitué d'entiers positifs ou nuls
 
 * @param $tabEntiers : le tableau
 * @return vrai ou faux
*/
function estTableauEntiers($tabEntiers) 
{
    $ok = true;
    foreach($tabEntiers as $unEntier)
    {
    	if(!estEntierPositif($unEntier))
        {
            $ok=false; 
	}
    }
    return $ok;
}

/**
 * Vérifie si une date est inférieure d'un an à la date actuelle
 
 * @param $dateTestee 
 * @return vrai ou faux
*/
function estDateDepassee($dateTestee)
{
    $dateActuelle=date("d/m/Y");
    @list($jour,$mois,$annee) = explode('/',$dateActuelle);
    $annee--;
    $AnPasse = $annee.$mois.$jour;
    @list($jourTeste,$moisTeste,$anneeTeste) = explode('/',$dateTestee);
    return ($anneeTeste.$moisTeste.$jourTeste < $AnPasse); 
}

/**
 * Vérifie la validité du format d'une date française jj/mm/aaaa 
 
 * @param $date 
 * @return vrai ou faux
*/
function estDateValide($date)
{
    $tabDate = explode('/',$date);
    $dateOK = true;
    if (count($tabDate) != 3) 
    {
        $dateOK = false;
    }
    else 
    {
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
 
 * @param $lesFrais 
 * @return vrai ou faux
*/
function lesQteFraisValides($lesFrais)
{
    return estTableauEntiers($lesFrais);
}

/**
 * Vérifie la validité des trois arguments : la date, le libellé du frais et le montant 
 
 * des message d'erreurs sont ajoutés au tableau des erreurs
 
 * @param $dateFrais 
 * @param $libelle 
 * @param $montant
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

/**
 * Retoune le nombre de lignes du tableau des erreurs 
 
 * @return le nombre d'erreurs
 */
function nbErreurs()
{
    if (!isset($_REQUEST['erreurs']))
    {
        return 0;
    }
    else
    {
       return count($_REQUEST['erreurs']);
    }
}
?>