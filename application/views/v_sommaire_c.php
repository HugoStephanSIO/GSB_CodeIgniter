<!-- Sommaire comptable -->
<br/><br/>
    <div class="col-md-2">
        <div class="panel-group">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <center>
                        Comptable :<br>
                        <b><?php echo $infos->nom ; ?> <?php echo $infos->prenom; ?></b>
                    </center>
                </div>
                <div class="panel-body">
                    <ul class="nav nav-pills nav-stacked">
                        <li class="smenu">
                            <a href="<?php echo site_url("Comptable/chargerListeVisiteur/".$infos->id) ; ?>" title="Liste des Visiteurs">Liste des Visiteurs</a>
                        </li>
                        <li class="smenu">
                            <a href="<?php echo site_url("Comptable/chargerFichesEnEtat/CR/".$infos->id) ; ?>" title="Fiches Créées  ">Fiches Créées</a>
                        </li>
                        <li class="smenu">
                            <a href="<?php echo site_url("Comptable/chargerFichesEnEtat/CL/".$infos->id) ; ?>" title="Fiches Cloturées">Fiches Cloturées</a>
                        </li> 
                        <li class="smenu">
                            <a href="<?php echo site_url("Comptable/chargerFichesEnEtat/VA/".$infos->id) ; ?>" title="Fiches à valider">Fiches Validées</a>
                        </li>
                        <li class="smenu">
                            <a href="<?php echo site_url("Comptable/chargerFichesEnEtat/RB/".$infos->id) ; ?>" title="Fiches Remboursées">Fiches Remboursées</a>
                        </li>
                        <hr style="width:104px; color:firebrick; background-color:firebrick; height:2px;" />
                        <li class="smenu">
                            <a href="<?php echo base_url ('index.php/Connect');?>" title="Se déconnecter">Déconnexion</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>



