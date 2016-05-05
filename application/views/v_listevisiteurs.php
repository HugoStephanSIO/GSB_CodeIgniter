<!-- Partie comptable: Affiche la liste des visiteurs -->
<div class="col-md-10">
    <legend class="h2titre"><center>Tableau des visiteurs</center></legend>
        <div class="table-responsive">    
            <table class="table table-striped table-hover table-bordered"> 
                <thead class="panel-heading" style="background:#4f6185; color: #ffffff;">
                    <th>Numéro</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Adresse</th>
                    <th>Code Postal</th>
                    <th>Ville</th>
                    <th>Détails des fiches frais</th>
                </thead>
                    <?php
                        // Un visiteur = une ligne du tableau
                        foreach ($visiteur as $ligne)
                        {
                    ?>
                            <tr>
                                <td><?php echo $ligne->id; ?></td>
                                <td><?php echo $ligne->nom; ?></td>
                                <td><?php echo $ligne->prenom; ?></td>
                                <td><?php echo $ligne->adresse; ?></td>
                                <td><?php echo $ligne->cp; ?></td>
                                <td><?php echo $ligne->ville; ?></td>
                                <td><a href="<?php echo site_url("Comptable/ficheNoVa/".$ligne->id."/".$idComptable);?>">Fiches à valider</a></td>
                            </tr>
                    <?php   
                        }
                    ?>
            </table>
        </div>            
</div> 
