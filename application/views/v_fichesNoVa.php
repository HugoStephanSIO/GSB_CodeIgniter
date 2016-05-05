<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container">
    <center><h2 class="h2titre">Liste des fiches de frais à valider :</h2></center>
    <h4>
        <?php
            echo "<b>Visiteur numéro</b> : ".$visiteur->id."<br/>";
            echo "<b>Nom</b> : ".$visiteur->nom.""
                    . "<br/> "
               . "<b>Prénom</b> : ".$visiteur->prenom."";
        ?>
         <br/>
        <?php
            // On récupère les fiches de frais d'un visiteur qui sont en état CL ou CR
            foreach($ficheNoVa as $ligne)
            {
        ?>
                <br/>
                <p class="soustitre" style="text-align: center;">
                    <?php 
                        echo "<b>Mois de l'année concernée</b>: ".$ligne->mois."";
                    ?>
                </p>
        <?php
            } 
        ?>
    </h4>
</div>
