<div class="col-md-10">
    <legend class="h2titre">
        <center>Liste des fiches
            <?php
                switch($action)
                {
                    case "CR":
                        echo " en cours (état CR) : " ;
                        break ;
                    case "CL":
                        echo " cloturées (état CL) :" ;
                        break ;
                    case "VA":
                        echo " validées (état VA) :";
                        break ;
                    case "RB":
                        echo " remboursées (état RB) :";
                        break ;
                }
        ?>
        </center>
    </legend>

    <table class="table table-striped table-hover table-bordered"> 
        <thead class="panel-heading" style="background:#4f6185; color: #ffffff;">
            <th>Clef</th>
            <th>Mois</th>
            <th>Année</th>
            <th>Concernant</th>
            <th>Nombre de justificatifs</th>
            <th>Montant validé</th>
            <th>Date dernière modif.</th>
            <th>Actions</th>
        </thead>
        <?php
            foreach($lesFiches as $uneFiche)
            {
                $clef = $uneFiche["mois"];
                $annee = substr($clef,0,4);
                $mois = moisChiffresVersLettres(substr($clef, 4, 2));
                $nb = $uneFiche["nbJustificatifs"];
                $montant = $uneFiche["montantValide"];
                $date = $uneFiche["dateModif"];
                $prenom = $uneFiche["prenom"];
                $nom = $uneFiche["nom"];
                echo "<tr>" ;
                echo    "<td>".$clef."</td>";
                echo    "<td>".$mois."</td>";
                echo    "<td>".$annee."</td>";
                echo    "<td>".$prenom." ".$nom."</td>";
                echo    "<td>".$nb."</td>";
                echo    "<td>".$montant."</td>";
                echo    "<td>".$date."</td>";
                echo    "<td>".
                        "<a href='".site_url("Comptable/consulterFiche/".$uneFiche["idVisiteur"]."/".$clef."/".$idComptable."/".$action)."'>Consulter</a>&nbsp;&nbsp;|&nbsp;&nbsp;" ;
                            switch($action)
                            {
                                case "CR":
                                    echo "<a href='".site_url("Comptable/cloturerFiche/".$uneFiche["idVisiteur"]."/".$clef."/".$idComptable)."' onclick=\"return confirm('Voulez vous vraiment cloturer cette fiche de frais ?');\">Cloturer</a>&nbsp;&nbsp;|&nbsp;&nbsp;" ;
                                    break ;
                                case "CL":
                                    echo "<a href='".site_url("Comptable/validerFiche/".$uneFiche["idVisiteur"]."/".$clef."/".$idComptable)."' onclick=\"return confirm('Voulez vous vraiment valider cette fiche de frais ?');\">Valider</a>&nbsp;&nbsp;|&nbsp;&nbsp;" ;
                                    break ;
                                case "VA":
                                    echo "<a href='".site_url("Comptable/rembourserFiche/".$uneFiche["idVisiteur"]."/".$clef."/".$idComptable)."' onclick=\"return confirm('Voulez vous vraiment mettre en remboursement cette fiche de frais ?');\">Rembourser</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
                                    break ;
                                case "RB":
                                    break ;
                            }
                echo    "<a href='".site_url("Comptable/supprimerFiche/".$uneFiche["idVisiteur"]."/".$clef."/".$idComptable."/".$action)."' onclick=\"return confirm('Voulez vous vraiment supprimer cette fiche de frais ?');\">Supprimer</a>".
                    "</td>";
            }
        ?>
    </table>
</div>

