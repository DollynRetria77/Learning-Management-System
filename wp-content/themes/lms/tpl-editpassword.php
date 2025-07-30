<?php
    /**
     *  Template Name: editpassword
     */
?>
<?php if(isset($_GET['id'])): ?>
<?php
    $id = $_GET['id'];
    $user = get_user_by('ID', $id);
    $current_slug = add_query_arg( array(), $wp->request );
?>
<?php get_header(); ?>
<div class="container">
    <div class="row">

        <div class="col-12 mt-4 mb-4">
            <ul class="dashboard-menu">
            <li><a href="<?php echo bloginfo('url') ?>/profil" class="btn <?php echo ($current_slug == 'profil') ? 'active': '' ?>">Mon profl</a></li>
            <li><a href="<?php echo bloginfo('url') ?>/edit-profil?id=<?php echo $user->ID ?>" class="btn <?php echo ($current_slug == 'edit-profil') ? 'active': '' ?>">Modifier mon profil</a></li>
            <li><a href="<?php echo bloginfo('url') ?>/edit-password?id=<?php echo $user->ID ?>" class="btn <?php echo ($current_slug == 'edit-password') ? 'active': '' ?>">Changer mot de passe</a></li>
            <li><a href="<?php echo bloginfo('url') ?>/mes-cours?id=<?php echo $user->ID ?>" class="btn <?php echo ($current_slug == 'mes-cours') ? 'active': '' ?>">Mes cours</a></li>
                <?php 
                    if(isset($_GET['id'])): 
                        $id = $_GET['id']; 
                        $user = get_user_by('ID', $id);
                        if ( in_array( 'editor', (array) $user->roles ) ):
                ?>
                        <li><a href="<?php echo bloginfo('url') ?>/ajout-cours?id=<?php echo $user->ID ?>" class="btn <?php echo ($current_slug == 'ajout-cours') ? 'active': '' ?>">Ajouter un cours</a></li>
                  <?php endif; ?>
                <?php endif; ?>
            </ul>

            <h2 class="title-h2">Changer le mot de passe</h2>
            <?php 
                $error = false;
                $success = false;

                if(!empty($_POST)){
                    // echo "<strong>";
                    // echo $user->user_pass;
                    // echo "</br>";
                    // echo wp_hash_password($_POST['password']);
                    // echo "</strong>";

                    if(!empty($_POST['password']) && !empty($_POST['newpassword']) && !empty($_POST['newpassword2'])){
                        //if(md5($_POST['password']) !== $user->user_pass){
                        //Vérifie le mot de passe par rapport au mot de passe crypté.
                        if(!wp_check_password($_POST['password'], $user->user_pass, $user->ID )){
                            $error = "Le mot de passe actuelle n'est pas valide";
                        }elseif($_POST['newpassword'] !== $_POST['newpassword2']){
                            $error = "Les 2 nouveaux mots de passes ne correspondent pas";
                        }else{
                            //Met à jour le mot de passe de l'utilisateur avec un nouveau crypté.
                            wp_set_password($_POST['newpassword'], $id);
                            $success = "Modification reussie";
                            //@header('location: profil');                    
                            @header('location:'.site_url().'/profil');
                        }
                    }
                }
            ?>

            <?php if($error) : ?>
                <div class="alert alert-danger">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            <?php if($success): ?>
                <div class="alert alert-success">
                <a href="<?php echo bloginfo('url') ?>/profil" class="btn"><?php echo $success;  ?></a>
                </div>
            <?php endif; ?>

            <form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">
                <div class="form-group">
                    <label for="password">Mot de passe actuel :</label>
                    <input type="password" name="password" id="password" class="form-control" required/>
                </div>
                <div class="form-group">
                    <label for="newpassword">Nouveau mot de passe :</label>
                    <input type="password" name="newpassword" id="newpassword" class="form-control" required/>
                </div>
                <div class="form-group">
                    <label for="newpassword2">Confirmation nouveau mot de passe :</label>
                    <input type="password" name="newpassword2" id="newpassword2" class="form-control" required/>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary" type="submit">Modifier</button>
                </div>
            </form>

            <?php endif; ?>
        </div>
    </div>
</div>    
<?php get_footer(); ?>

