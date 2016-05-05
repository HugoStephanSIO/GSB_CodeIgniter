<!-- Page d'identificaton de l'utilisateur -->
<div class="container">
    <div class="row">
        <div class="col-sm-6 col-md-4 col-md-offset-4">
            <h1 class="text-center login-title">Identification utilisateur</h1>
                <div class="account-wall">
                    <img class="profile-img" src="<?php echo base_url('assets/logo/logo-gsb.png');?>" />
                        <form method="POST" class="form-signin" action="<?php echo base_url('index.php/Connect/connecter');?>">
                            <p>
                                <input type='text' class="form-control" name='login' id="login" placeholder="Votre identifiant" required autofocus/>
                            <p/>
                            <p>
                                <input type='password' class="form-control" name='mdp' id="mdp" placeholder="Votre mot de passe" required/> 
                            <p/>
                            <input type="radio" name="type_co" value="Visiteur" checked> Visiteur<br>
                            <input type="radio" name="type_co" value="Comptable"> Comptable<br>
                            <center>
                                <button type="submit" class="btn btn-primary" name="valider" id="valider" value="valider" >Valider</button>
                                <button type="reset" class="btn btn-primary" name="annuler" id="annuler" value="annuler" >Annuler</button>
                            </center>
                        </form>
                </div>
        </div>
    </div>
</div>

<br/><br/><br/>