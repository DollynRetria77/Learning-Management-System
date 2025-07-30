<?php get_header(); ?>

<div class="container">
<div class="row">
    <div class="col-12 mt-4 mb-4"><h2 class="title-h2">Tout les cours offert</h2></div>
</div>

<?php 
     $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
    // print_r($paged);
    $args = array(
        //'is_paged' => true,
        'post_type' => 'cours',
        'posts_per_page' => -1
        //'paged' => $paged
    );
    $loop = new WP_Query($args);
    // echo '<pre>';
    // print_r($loop);
    // echo '</pre>';
?>
    <?php if ($loop->have_posts()) : ?>
        <div class="row">
            <?php while($loop->have_posts()) : $loop->the_post();?>
                <div class="col-sm-4 mb-4">
                    <div class="card">
                        <div class="card-body card-cours">
                            <h4 class="card-title"><?php the_title(); ?></h4>
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

            <?php endwhile; wp_reset_postdata(); ?>
        </div>
        <!-- <div class="row">
            <div class="col-12">
                <div class="pagination">
                    <?php 
                        /*$big = 999999999; 
                        
                        echo paginate_links( array(
                            'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                            'format' => '?paged=%#%',
                            'current' => max( 1, get_query_var('paged') ),
                            'total' => $loop->max_num_pages
                        ) );*/

                    ?>
                </div>
            </div>
        </div> -->
<?php else: ?>
<div class="row">
    <div class="col-12 mb-4">
        <div>Pas de cours disponible</div>
    </div>
</div>

<?php endif; ?>
</div>


<?php get_footer(); ?>