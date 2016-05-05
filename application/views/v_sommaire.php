<br/><br/>
<!-- Sommaire visiteur -->
   <!-- Le conteneur ouvert dans l'entête s'étale sur la vue centrale et le footer -->
    <div class="col-md-2"> <!-- Espacement latéral gauche -->
        <div class="panel-group">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <center>
                        Visiteur :<br>
                        <b><?php echo $infos->nom ; ?> <?php echo $infos->prenom; ?></b>
                    </center>
                </div>
                <div class="panel-body">
                    <ul class="nav nav-pills nav-stacked">
                        <li class="smenu">
                            <a href="<?php echo site_url("Visiteur/saisirFiche/".$infos->id) ; ?>" title="Saisie fiche de frais ">Saisie fiche de frais</a>
                        </li>
                        <li class="smenu">
                            <a href="<?php echo site_url("Visiteur/selectionnerMois/".$infos->id) ; ?>" title="Consultation de mes fiches de frais">Mes fiches de frais</a>
                        </li>
                        <li class="smenu">
                            <a href="<?php echo base_url ('index.php/Connect') ; ?>" title="Se déconnecter">Déconnexion</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>