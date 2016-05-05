<!-- Partie visiteur: Consulter l'état des fiches frais selon le mois sélectionné  -->
<div class = "container">
    <div class="col-md-8"> <!-- Contenu principal -->
	<h2 class="h2titre"> Mes fiches de frais</h2>
            <legend class="soustitrepage"> Mois à sélectionner : </legend>
		<form action="<?php echo site_url("Visiteur/voirEtatFraisMois") ; ?>" method="post">
                    <div class="corpsForm">
			<p>
                            <label for="lstMois" accesskey="n">Mois : </label>
				<select id="lstMois" name="lstMois" class="textbox">
                                    <?php
                                        // Affiche tous les mois pour lesquels il existe une fiche de frais à consulter
					foreach ($lesMois as $unMois)
					{
                                            $mois = $unMois['mois'];
                                            $numAnnee =  $unMois['numAnnee'];
                                            $numMois =  $unMois['numMois'];
                                                if($mois == $moisASelectionner)
                                                {
                                    ?>
                                                    <option selected value="<?php echo $mois ?>"><?php echo  $numMois."/".$numAnnee ?> </option>
                                            <?php 
                                                }
                                                else
                                                { 
                                            ?>
                                                    <option value="<?php echo $mois ?>"><?php echo  $numMois."/".$numAnnee ?> </option>
                                            <?php 
                                                }
                                        }
                                            ?>     
                                </select>
                        </p>
                    </div>
		
                    <div class="piedForm">
                        <p>
                            <input id="ok" type="submit" value="Valider" class="btn btn-success" size="20" />
                            <input type ="hidden" name ="idVisiteur" value ="<?php echo $idVisiteur ?>" >
                        </p> 
                    </div>
                </form>
    </div>
</div>