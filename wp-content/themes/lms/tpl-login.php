<?php
    /**
     *  Template Name: Connexion
     */
?>
<?php 
    $error = false;
    if(!empty($_POST)){
        //Authentifie et connecte un utilisateur
        $user = wp_signon($_POST);
        ////Vérifie si la variable $user est une erreur WordPress
        if(is_wp_error($user)){
            $error = $user->get_error_message();
        }else{
            //Récupérer l'objet utilisateur actuel
            $user = wp_get_current_user();
            //echo 'ato ve';
            // echo site_url();
            // print_r($user);
            // die();
            //if($user->ID != 0){
                header('location:'.site_url().'/profil');
            //}
        }
    }

?>
<?php get_header(); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-12 mt-4 mb-4">
            <h2 class="title-h2">Se connecter</h2>
            <?php if($error) : ?>
                <div class="alert alert-danger">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
                <div class="form-group">
                    <label for="user_login" class="mb-1">Votre login</label>
                    <input type="text" name="user_login" id="user_login" class="form-control" />
                </div>
                <div class="form-group">
                    <label for="user_password" class="mb-1">Votre Mot de passe</label>
                    <input type="password" name="user_password" id="user_user_password" class="form-control" />
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Se connecter</button>
                </div>
            </form>
            <div>Vous n'avez pas de compte ? <a href="<?php echo site_url().'/inscription'; ?>">S'inscrire</a></div>
        </div>
    </div>
</div>
<?php get_footer(); ?>

