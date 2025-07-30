<?php
    /**
     *  Template Name: editprofil
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

            <h2 class="title-h2">Modifier mon profil</h2>

            <?php 
                $error = false;
                $success = false;

                if(!empty($_POST)){
                    //tester si c'est pas un adresse mail
                    if(!is_email($_POST['mail'])){
                        $error = "Veuillez entrer un email valide";
                    }else{
                        //Mettre à jour un utilisateur dans la bdd
                        $newuser = wp_update_user(array(
                            'ID'            => $id,
                            'last_name'     => $_POST['nom'],
                            'first_name'    => $_POST['prenom'],
                            'user_login'    => $_POST['login'], 
                            'user_email'    => $_POST['mail']
                        ));
                        //Vérifie si la variable $user est une erreur WordPress
                        if(is_wp_error($newuser)){
                            $error = $newuser->get_error_message();
                        }else{
                            $success = "Modification bien reussie";
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
                    <label for="nom">Nom</label>
                    <input type="text" name="nom" id="nom" class="form-control" value="<?php echo $user->last_name; ?>" required />
                </div>
                <div class="form-group">
                    <label for="prenom">Prénom</label>
                    <input type="text" name="prenom" id="prenom" class="form-control" value="<?php echo $user->first_name; ?>" required />
                </div>
                <div class="form-group">
                    <label for="login">Login</label>
                    <input type="text" name="login" id="login" class="form-control" value="<?php echo $user->user_login; ?>" required />
                </div>
                <div class="form-group">
                    <label for="mail">Mail :</label>
                    <input type="text" name="mail" id="mail" class="form-control" value="<?php echo $user->user_email; ?>" required />
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

