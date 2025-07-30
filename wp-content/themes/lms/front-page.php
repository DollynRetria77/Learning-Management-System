<?php get_header(); ?>

<?php
$args = array(
		'is_paged' => false,
		'post_type' => 'gallerie',
		'posts_per_page' => -1,
);
$loop = new WP_Query($args);
?>
<section>
<?php if($loop->have_posts()): ?>
    <div class="slider-hp">
    <?php while($loop->have_posts()): $loop->the_post(); ?>
        <?php if ( has_post_thumbnail() ) : ?>
            <div class="slider-hp-item">
                <?php
                    //the_post_thumbnail();
                    $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'full');
                    echo '<img src="' . $large_image_url[0] . '" alt="' . get_the_title() . '"/>';
                ?>
            </div>
        <?php endif; ?>
    <?php endwhile; wp_reset_postdata();?>
    </div>
<?php endif; ?>
</section>
<section class="cours-en-offre">
    <div class="container">
        <div class="row">
            <div class="col-12 mb-4">
                <h2 class="title-h2">Cours en offre</h2>
            </div>
        </div>
        <?php
            $args = array(
                    'is_paged'          => false,
                    'post_type'         => 'cours',
                    'posts_per_page'    => 3,
                    'post_status'       => 'publish',
                    'order'             => 'DESC',
                    'suppress_filters'  => true
            );
            $loop = new WP_Query($args);
        ?>
        <?php if ($loop->have_posts()) : ?>
            <div class="row">
                <?php while($loop->have_posts()) : $loop->the_post();?>
                    <div class="col-sm-4 mb-2">
                        <div class="card">
                            <div class="card-body card-cours">
                                <div class="card-title"><?php the_title(); ?></div>
                                <div class="card-infos">
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
                                    <div><strong>Lieux : </strong><?php echo get_post_meta(get_the_ID(), '_lieux', true) ?></div>
                                    <div><strong>Duree : </strong><?php echo get_post_meta(get_the_ID(), '_duree', true) ?></div>
                                </div>
                                <div class="card-btn">
                                    <a href="<?php the_permalink(); ?>" class="btn-custom">En savoir plus</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; wp_reset_postdata();?>
            </div>
            <div class="row">
                <div class="col-12 text-center mt-4">
                    <a href="<?php echo site_url().'/cour' ?>" class="voir-plus">Voir plus de cours</a>
                </div>
            </div>
        <?php else: ?>
        <div class="row">
            <div class="col-12">
                <div>Pas de cours disponible</div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>
<section class="contactez-nous">
    <div class="container">
        <div class="row">
            <div class="col-12 mb-4">
                <h2 class="title-h2">Contactez nous</h2>
            </div>
        </div>
    </div>
    <?php echo do_shortcode('[contact-form-7 id="55" title="Formulaire de contact"]'); ?>
</section>



<?php get_footer(); ?>


