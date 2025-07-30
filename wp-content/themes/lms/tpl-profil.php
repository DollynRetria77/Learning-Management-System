<?php
    /**
     *  Template Name: profil
     */
?>

<?php
    //session_start();
?>

<?php 
    //Récupérer l'objet utilisateur actuel
    $user = wp_get_current_user();
    // print_r($user);
    // die()
    if($user->ID == 0){
        header('location:'.site_url().'/login');
    }
    //$_SESSION['id'] = $user->ID;

    $current_slug = add_query_arg( array(), $wp->request );
?>
<?php get_header(); ?>
<div class="container">
    <div class="row">
        <div class="col-12 mt-4 mb-4">
            <?php if($user->ID !== 0): ?>
            <ul class="dashboard-menu">
                <li><a href="<?php echo bloginfo('url') ?>/profil" class="btn <?php echo ($current_slug == 'profil') ? 'active': '' ?>">Mon profil</a></li>
                <li><a href="<?php echo bloginfo('url') ?>/edit-profil?id=<?php echo $user->ID ?>" class="btn <?php echo ($current_slug == 'edit-profil') ? 'active': '' ?>">Modifier mon profil</a></li>
                <li><a href="<?php echo bloginfo('url') ?>/edit-password?id=<?php echo $user->ID ?>" class="btn <?php echo ($current_slug == 'edit-password') ? 'active': '' ?>">Changer mot de passe</a></li>
                <li><a href="<?php echo bloginfo('url') ?>/mes-cours?id=<?php echo $user->ID ?>" class="btn <?php echo ($current_slug == 'mes-cours') ? 'active': '' ?>">Mes cours</a></li>
                <?php 
                    // Tester si la valeur 'editor' appartient au tableau 
                    if ( in_array( 'editor', (array) $user->roles ) ): 
                ?>
                <li><a href="<?php echo bloginfo('url') ?>/ajout-cours?id=<?php echo $user->ID ?>" class="btn <?php echo ($current_slug == 'ajout-cours') ? 'active': '' ?>">Ajouter un cours</a></li>
                <?php endif; ?>

            </ul>
            <h2 class="title-h2">Mes informations</h2>
            <!-- <pre>
            <?php //print_r($user); ?>
            </pre>  -->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th colspan="2"><strong>Statut :</strong> <span style="color:#007bff; font-weight:bold"><?php echo ( in_array( 'editor', (array) $user->roles ) ) ? 'FORMATEUR' : 'ETUDIANT' ?></span></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th><strong>Nom :</strong></th>
                        <td><?php echo $user->last_name;  ?></td>
                    </tr>
                    <tr>
                        <th><strong>Prénom :</strong></th>
                        <td><?php echo $user->first_name;  ?></td>
                    </tr>
                    <tr>
                        <th><strong>Login :</strong></th>
                        <td><?php echo $user->user_login;  ?></td>
                    </tr>
                    <tr>
                        <th><strong>Adresse mail :</strong></th>
                        <td><?php echo $user->user_email;  ?></td>
                    </tr>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>

