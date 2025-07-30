<?php
    /**
     *  Template Name: mes-cours
     */
?>
<?php
    //session_start();
?>
<?php 
    $user = wp_get_current_user();
    // print_r($user);
    // die()
    if($user->ID == 0){
        header('location:'.site_url().'/login');
    }
    $_GET['id'] = $user->ID;
    //echo 'ici la session :'. $_GET['id'];
    $current_slug = add_query_arg( array(), $wp->request );
?>
<?php get_header(); ?>
 <!-- <pre>
<?php //print_r($user); ?>
</pre>  -->
<div class="container">
    <div class="row">
        <div class="col-12 mt-4 mb-4">
            <ul class="dashboard-menu">
            <li><a href="<?php echo bloginfo('url') ?>/profil" class="btn <?php echo ($current_slug == 'profil') ? 'active': '' ?>">Mon profl</a></li>
            <li><a href="<?php echo bloginfo('url') ?>/edit-profil?id=<?php echo $user->ID ?>" class="btn <?php echo ($current_slug == 'edit-profil') ? 'active': '' ?>">Modifier mon profil</a></li>
            <li><a href="<?php echo bloginfo('url') ?>/edit-password?id=<?php echo $user->ID ?>" class="btn <?php echo ($current_slug == 'edit-password') ? 'active': '' ?>">Changer mot de passe</a></li>
            <li><a href="<?php echo bloginfo('url') ?>/mes-cours?id=<?php echo $user->ID ?>" class="btn <?php echo ($current_slug == 'mes-cours') ? 'active': '' ?>">Mes cours</a></li>
                <?php if ( in_array( 'editor', (array) $user->roles ) ): ?>
                <li><a href="<?php echo bloginfo('url') ?>/ajout-cours?id=<?php echo $user->ID ?>" class="btn <?php echo ($current_slug == 'ajout-cours') ? 'active': '' ?>">Ajouter un cours</a></li>
                <?php endif; ?>
            </ul>
            <h2 class="title-h2">Mes cours</h2>
            <?php if ( in_array( 'editor', (array) $user->roles ) ): ?>
                <?php 
                    $args = array(
                        'post_type' => 'cours',
                        'author' => $user->ID, 
                        'posts_per_page' => -1,
                    ) ;
                    $listCours =  get_posts( $args );
                    // echo "<pre>";
                    // print_r($listCours);
                    // echo "</pre>";

                    if($listCours):
                ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th><strong>Titre</strong></th>
                                <th><strong>Catalogue</strong></th>
                                <th><strong>Durée</strong></th>
                                <th><strong>Lieux</strong></th>
                                <th><strong>Les supports</strong></th>
                                <th><strong>Nb Etudiant associé</strong></th>
                                <!-- <th>Actions</th> -->
                            </tr>
                        </thead>
                        <tbody>
                        <?php  foreach($listCours as $cours): ?>
                        <tr>
                            <td><?php echo $cours->post_title ?></td>
                            <td>
                                <?php 
                                    //Récupèrer les termes d'une publication.
                                    $term_list = wp_get_post_terms($cours->ID, 'catalogue');
                                    // echo '<pre>';
                                    // print_r($term_list);
                                    // echo '</pre>';
                                    $nbterm = count($term_list);
                                    $i=0;
                                ?>
                                <?php foreach($term_list as $term): ?>
                                    <?php 
                                        echo $term->name; 
                                        $i++;
                                        echo ($i === $nbterm) ? '' : ', ';
                                    ?>
                                <?php endforeach; ?>
                            </td>
                            <td>
                                <?php 
                                    //print_r(get_post_meta($cours->ID, '_duree'));
                                    //recupere un champ meta par rapport à un ID
                                    $duree = get_post_meta($cours->ID, '_duree'); 
                                    echo $duree[0];
                                ?>
                            </td>
                            <td>
                                <?php 
                                    //print_r(get_post_meta($cours->ID, '_lieux'));
                                    $lieux = get_post_meta($cours->ID, '_lieux'); 
                                    echo $lieux[0];
                                ?>
                            </td>
                            <td>
                                <?php 
                                    //print_r(get_post_meta($cours->ID, '_supports')); 
                                    $supports = get_post_meta($cours->ID, '_supports');
                                    // echo '<pre>';
                                    // print_r($supports);
                                    // echo '</pre>';
                                    for($i=0; $i < count($supports[0]); $i++):
                                        $file = $supports[0][$i]['file'];
                                        $file = explode("/", $file);
                                ?>

                                        <div><a href="<?php echo $supports[0][$i]['url']; ?>" target="_blank"><?php echo $file[count($file) - 1]; ?></a></div>
                                <?php endfor; ?>
                            </td>
                            <td>
                                <?php   
                                    $participant = get_post_meta($cours->ID, '_participant'); 
                                    //print_r($participant);
                                ?>
                                <?php if(count($participant) !== 0): ?>
                                    <a class="nb_etudiant" data-post-id="<?php echo $cours->ID ?>"><?php echo count($participant); ?></a>
                                <?php else : ?>
                                    <div><?php echo count($participant); ?></div>
                                <?php endif; ?>    
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            <?php elseif( in_array( 'subscriber', (array) $user->roles )): ?>
                <?php 
                    $args = array(
                        'post_type' => 'cours',
                        'posts_per_page' => -1,
                    ) ;
                    $listCours =  get_posts( $args );
                    //tableau vide pour ajouter les cours ou ils se sont abonnée
                    $coursAbonnee = array();

                    if($listCours):
                        foreach($listCours as $cours):
                            //recupere un champ meta par rapport à un ID
                            $participant = get_post_meta($cours->ID, '_participant'); 
                            if(in_array($user->ID, $participant)):
                                array_push($coursAbonnee, $cours );
                            endif;
                        endforeach;
                    endif;
                ?>

                <?php if($coursAbonnee): ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th><strong>Titre</strong></th>
                                <th><strong>Catalogue</strong></th>
                                <th><strong>Durée</strong></th>
                                <th><strong>Lieux</strong></th>
                                <th><strong>Les supports</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php  foreach($coursAbonnee as $cours): ?>
                            <tr>
                                <td><?php echo $cours->post_title ?></td>
                                <td>
                                    <?php 
                                        $term_list = wp_get_post_terms($cours->ID, 'catalogue');
                                        // echo '<pre>';
                                        // print_r($term_list);
                                        // echo '</pre>';

                                        $nbterm = count($term_list);
                                        $i=0;
                                    ?>
                                    <?php foreach($term_list as $term): ?>
                                        <?php 
                                            echo $term->name; 
                                            $i++;
                                            echo ($i === $nbterm) ? '' : ', ';
                                        ?>
                                        
                                    <?php endforeach; ?>
                                </td>
                                <td>
                                    <?php 
                                        //print_r(get_post_meta($cours->ID, '_duree'));
                                        $duree = get_post_meta($cours->ID, '_duree'); 
                                        echo $duree[0];
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                        //print_r(get_post_meta($cours->ID, '_lieux'));
                                        $lieux = get_post_meta($cours->ID, '_lieux'); 
                                        echo $lieux[0];
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                        //print_r(get_post_meta($cours->ID, '_supports')); 
                                        $supports = get_post_meta($cours->ID, '_supports');
                                        // echo '<pre>';
                                        // print_r($supports);
                                        // echo '</pre>';
                                        for($i=0; $i < count($supports[0]); $i++):
                                            $file = $supports[0][$i]['file'];
                                            $file = explode("/", $file);
                                    ?>

                                            <div><a href="<?php echo $supports[0][$i]['url']; ?>" download="<?php echo $file[count($file) - 1]; ?>"><?php echo $file[count($file) - 1]; ?></a></div>
                                    <?php endfor; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>

            <?php else: ?>
            <div>je suis rien</div>
            <?php endif; ?>
        </div>
    </div>
</div>  
<?php get_footer(); ?>

