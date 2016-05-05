<div class = "container">
    <div class="col-md-8 col-md-push-2">   
        <h2 class="h2titre">Fiche de frais du mois <?php echo $numMois."-".$numAnnee?> : </h2>
            <p class="infos">
                <b>Etat</b> : <?php echo $libEtat?> depuis le <?php echo $dateModif?> <br/> 
                <b>Montant validé</b> : <?php echo $montantValide?>                 
            </p>
				
            <table class="table table-striped table-hover table-bordered">
                <div class="table-responsive"> 
                    <legend class="soustitrepage">Eléments forfaitisés </legend>
                        <tr class="panel-heading" style="background:#4f6185; color: #ffffff;">
                            <?php
                                // Un type de frais forfait = une cellule titre
                                foreach ( $lesFraisForfait as $unFraisForfait ) 
                                {
                                    $libelle = $unFraisForfait['libelle'];
                            ?>	
                                <th> 	<?php echo $libelle?></th>
                            <?php
                                }
                            ?>
                        </tr>
                        <tr>
                            <?php
                                // Un type frais hors forfait = une cellule quantité
                                foreach (  $lesFraisForfait as $unFraisForfait  ) 
                                {
                                    $quantite = $unFraisForfait['quantite'];
                            ?>
                                <td class="qteForfait"><?php echo $quantite?> </td>
                            <?php
                                }
                            ?>
                        </tr>
                </div>
            </table>
		
            <table class="table table-striped table-hover table-bordered">
                <div class="table-responsive"> 
                    <legend class="soustitrepage">Descriptif des éléments hors forfait -<?php echo $nbJustificatifs ?> justificatifs reçus</legend>
                        <tr>
                            <th class="panel-heading" style="background:#4f6185; color: #ffffff;">Date</th>
                            <th class="panel-heading" style="background:#4f6185; color: #ffffff;">Libellé</th>
                            <th class="panel-heading" style="background:#4f6185; color: #ffffff;">Montant</th>                
                        </tr>
                            <?php      
                                    // Un frais hors forfait = une ligne
                                    foreach ( $lesFraisHorsForfait as $unFraisHorsForfait ) 
                                    {
                                        $date = $unFraisHorsForfait['date'];
                                        $libelle = $unFraisHorsForfait['libelle'];
                                        $montant = $unFraisHorsForfait['montant'];
                            ?>
                                    <tr>
                                        <td><?php echo $date ?></td>
                                        <td><?php echo $libelle ?></td>
                                        <td><?php echo $montant ?></td>
                                    </tr>
                            <?php 
                                    }
                            ?>
		</div>
            </table>

            <?php
                // On affiche des boutons proposants des actions différentes selon le type d'état de la liste dont vient l'utilisateur
                if (isset($action))
                {
                    switch($action)
                    {
                        case "CR":
                            echo "<a class='btn btn-primary' href='".site_url("Comptable/cloturerFiche/".$idVisiteur."/".$numAnnee.$numMois."/".$infos->id)."' onclick=\"return confirm('Voulez vous vraiment cloturer cette fiche de frais ?');\">Cloturer</a>&nbsp;&nbsp;" ;
                            echo "&nbsp;&nbsp;&nbsp;<a class='btn btn-primary' href='".site_url("Comptable/chargerFichesEnEtat/CR/".$infos->id)."'>Retour</a>";
                            break ;
                        case "CL":
                            echo "<a class='btn btn-primary' href='".site_url("Comptable/validerFiche/".$idVisiteur."/".$numAnnee.$numMois."/".$infos->id)."' onclick=\"return confirm('Voulez vous vraiment valider cette fiche de frais ?');\">Valider</a>&nbsp;&nbsp;" ;
                            echo "&nbsp;&nbsp;&nbsp;<a class='btn btn-primary' href='".site_url("Comptable/chargerFichesEnEtat/CL/".$infos->id)."'>Retour</a>";
                            break ;
                        case "VA":
                            echo "<a class='btn btn-primary' href='".site_url("Comptable/rembourserFiche/".$idVisiteur."/".$numAnnee.$numMois."/".$infos->id)."' onclick=\"return confirm('Voulez vous vraiment mettre en remboursement cette fiche de frais ?');\">Rembourser</a>&nbsp;&nbsp;";
                            echo "&nbsp;&nbsp;&nbsp;<a class='btn btn-primary' href='".site_url("Comptable/chargerFichesEnEtat/VA/".$infos->id)."'>Retour</a>";
                            break ;
                        case "RB":
                            echo "<a class='btn btn-primary' href='".site_url("Comptable/chargerFichesEnEtat/RB/".$infos->id)."'>Retour</a>";
                            break ;
                    }
                }
            ?>
   
    </div>
</div>

<br/>