
<?php 
    session_start(); 
    $user = wp_get_current_user();
    // echo '<pre>';
    // print_r($user);
    // echo '</pre>';
    // die()
    // if($user->ID == 0){
    //     header('location: login');
    // }

    $_SESSION['user_id'] = $user->ID;
    // //echo 'ici la session :'. $_SESSION['id'];
?>
<?php get_header(); ?>
<div class="container">
    <div class="row">
        <div class="col-12 mt-4 mb-4">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>  

                    <?php $_SESSION['post_id'] = get_the_ID(); ?>
                    <div class="result"></div>
                    <h2 class="title-h2"><?php the_title(); ?></h2>
                    <div class="single-cours-detail">
                        <div><strong>Catalogue : </strong>
                            <?php 
                                //Récuperer les termes associé à une publication
                                $term_list = wp_get_post_terms(get_the_ID(), 'catalogue');
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
                        </div>

                        <div><strong>Lieux :</strong> <?php echo get_post_meta(get_the_ID(), '_lieux', true); ?></div>
                        <div><strong>Duree :</strong> <?php echo get_post_meta(get_the_ID(), '_duree', true); ?></div>
                    </div>
                    <!-- <div>
                        <pre>
                            <?php print_r(get_post_meta(get_the_ID(), '_supports', true)); ?>
                        </pre>
                    </div> -->
                <?php endwhile; ?>
                <?php //echo '<pre>'; ?>
                <?php //print_r($_SESSION); ?>
                <?php //echo '</pre>'; ?>
                <div class="btn-je-participe text-center">
                    <?php if($user->ID !== 0 && in_array( 'subscriber', (array) $user->roles )): ?>
                        <a  id="je_participe" class="voir-plus" href="<?php echo site_url()."/sabonner" ?>">Je participe</a>

                    <?php else: ?>
                        <a class="voir-plus" href="<?php echo site_url()."/connexion" ?>">Je participe</a>
                    <?php endif; ?>
                </div>
            <?php else : ?>
                <h2 class="title-h2">Pas de cours</h2>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>