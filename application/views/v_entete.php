<!DOCTYPE html>
<!--
En tête
-->
<html>
    <head>
        <meta charset="UTF-8">
        <!-- Une utilisation pour les appareils mobiles -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Permet de récupérer les deux fichiers css: style et celui de bootstrap -->
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/bootstrap/css/bootstrap.css');?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/style.css');?>">
        <!--<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/styles.css');?>">-->
        <!-- Permet de récupérer les icons bootstrap font-awesome et par lien aussi -->
        <link rel="stylesheet" href="<?php echo base_url('assets/font-awesome/css/font-awesome.min.css');?>">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
        <!-- Utilisation des composants javascript -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <link rel="icon" type="image/png" href="<?php echo base_url('assets/logo/logo-page.png');?>">
        <title><?php if(isset($titre)) { echo $titre ;} else { echo "Intranet du Laboratoire Galaxy-Swiss Bourdin" ; } ?></title>
    </head>
    
    <body>
  
<!-- Titre du projet -->        
    <div class="container">
        <div class="row">
            <div class="span12">
                <div class="well">
                    <center>
                        <h3>
                            Suivi du remboursement des frais
                        </h3>
                    </center>
                </div>
            </div>
        </div>
    </div>

<!-- 
Envoie un message d'erreur ou success en dessous du titre si il est question d'une connexion réussi, 
échoué ou erreur de saisie dans un formulaire
-->        
<?php 
    if ($this->session->flashdata("erreur"))
    {
?>
    
        <div class="container">
            <div class="failure">
                <?php 
                     echo "<b>".$this->session->flashdata("erreur")."</b>" ; 
                ?>
            </div>
        </div>
 

<?php
    }
        
    if (isset($success))
    {
?>
            
        <div class="container">
            <div class="success">
                <?php 
                        echo "<b>$success</b>" ; 
                ?>
            </div>
        </div>
            
<?php
    }
?>
       