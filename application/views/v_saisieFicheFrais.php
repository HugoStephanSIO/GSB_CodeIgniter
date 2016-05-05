<!-- Partie visiteur: Ajouter un un élement forfaitisé -->
<div class = "container">
    <div class="col-md-8"> 
	<h2 class="h2titre">Renseigner la fiche de frais du mois <?php echo $numMois."-".$numAnnee ?></h2>
            <form method="POST"  action="<?php echo site_url("Visiteur/modifierFraisForfait") ;?>">
                <div class="corpsForm">
                    <fieldset>
                        <legend class="soustitrepage">Eléments forfaitisés</legend>
                            <?php
                                // Récupère la liste des frais forfaitisé
                                foreach ($lesFraisForfait as $unFrais)
                                {
                                    $idFrais = $unFrais['idfrais'];
                                    $libelle = $unFrais['libelle'];
                                    $quantite = $unFrais['quantite'];
                            ?>
                                    <p>
                                        <label for="idFrais"><?php echo $libelle ?></label>
                                        <input type="text" class="textbox" id="idFrais" 
                                               name="lesFrais[<?php echo $idFrais?>]" size="10" maxlength="5" 
                                               value="<?php echo $quantite?>" >
                                    </p>
                            <?php
                                }
                            ?>	
                            <input type ="hidden" name ="idVisiteur" value ="<?php echo $idVisiteur ?>" >	 
                            <input type ="hidden" name ="mois" value ="<?php echo $mois ?>" >	
                    </fieldset>
                </div>

                <div class="piedForm">
                  <p>
                    <input id="ok" type="submit" value="Valider" class="btn btn-primary" size="20" />
                    <input id="annuler" type="reset" value="Effacer" class="btn btn-warning" size="20" />
                  </p> 
                </div>
            </form>
  
            <!-- Affiche les éléments hors forfait dans un tableau -->
            <h2 class="h2titre">Descriptif des éléments hors forfait</h2>
		<table class="table table-striped table-hover table-bordered"> 
                    <div class="table-responsive"> 	
                        <thead class="panel-heading" style="background:#4f6185; color: #ffffff;">
                            <th>Date</th>
                            <th>Libellé</th>
                            <th>Montant</th>
                            <th>Supprimer</th>
                        </thead>
                                
                        <?php    
                            // Récupère la liste des frais hors forfait 
                            foreach( $lesFraisHorsForfait as $unFraisHorsForfait) 
                            {
                                $date = $unFraisHorsForfait['date'];
                                $libelle = $unFraisHorsForfait['libelle'];
                                $montant=$unFraisHorsForfait['montant'];
                                $id = $unFraisHorsForfait['id'];
                        ?>		
                                <tr>
                                    <td><?php echo $date ?></td>
                                    <td><?php echo $libelle ?></td>
                                    <td><?php echo $montant ?></td>
                                    <td>
                                        <a href="<?php echo site_url("Visiteur/supprimerFraisHorsForfait/".$id."/".$idVisiteur); ?>"onclick="return confirm('Voulez-vous vraiment supprimer ce frais?');">Supprimer ce frais</a>
                                    </td>
                                </tr>
                        <?php		   
                            }
                        ?>	                                     
                </table>
    
    <!-- Ajoute un nouveau élément hors forfait qui va charger la méthode creerFraisHorsForfait -->
    <form action="<?php echo site_url("Visiteur/creerFraisHorsForfait") ; ?>" method="post">
        <div class="corpsForm">
            <fieldset>
                <legend class="soustitrepage">Nouvel élément hors forfait</legend>
                <p>
                    <label for="txtDateHF">Date (jj/mm/aaaa): </label>
                    <input class="textbox" type="text" id="txtDateHF" name="dateFrais" size="10" maxlength="10" value=""  />
                </p>
                <p>
                    <label for="txtLibelleHF">Libellé</label>
                    <input type="text" class="textbox" id="txtLibelleHF" name="libelle" size="70" maxlength="256" value="" />
                </p>
                <p>
                    <label for="txtMontantHF">Montant : </label>
                    <input type="text" class="textbox" id="txtMontantHF" name="montant" size="10" maxlength="10" value="" />
                </p>
            </fieldset>
        </div>
        <div class="piedForm">
            <p>
                <input type ="hidden" name ="idVisiteur" value ="<?php echo $idVisiteur ?>" >	 
                <input type ="hidden" name ="mois" value ="<?php echo $mois ?>" >
                <input id="ajouter" type="submit" value="Ajouter" class="btn btn-success" size="20" />
                <input id="effacer" type="reset" value="Effacer" class="btn btn-warning" size="20" />
            </p> 
        </div>   
    </form>
 
    </div>
</div>
  

      