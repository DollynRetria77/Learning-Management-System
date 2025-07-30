<!DOCTYPE html>
<!-- <html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body> -->
    <?php get_header(); ?>
     <!-- Bonjour tout le monde : <?php wp_title(); ?> -->

    <!-- fonction pemet de lister les categories -->
    <?php wp_list_categories(['taxonomy' => 'sport', 'title_li' => '']) ?>
    <?php 
       // var_dump(get_terms(['taxonomy' => 'sport']));
       $sports = get_terms(['taxonomy' => 'sport']);
    ?>
       <ul class="nav nav-pills">
        <?php foreach($sports as $sport): ?>
            <li class="nav-item">
                <a href="<?php echo get_term_link($sport) ?>" class="nav-link <?php echo  is_tax('sport', $sport->term_id) ? 'active' : '' ?>"><?php echo $sport->name ?></a>
            </li>
        <?php endforeach; ?>
       </ul>


     <?php if(have_posts()): ?>
        <div class="row">
        <?php while(have_posts()): the_post(); ?>
            <div class="col-sm-4 mb-4">
                <?php
                    // global $post;
                    // global $wp_query;
                    // echo '<pre>';
                    //     var_dump($post);
                    // echo '</pre>';
                    // echo '</br></br>';
                    // echo '<b>Manasakaa azy</b>';
                    // echo '<pre>';
                    //     var_dump($wp_query);
                    // echo '</pre>';
                ?>
                    <?php get_template_part('parts/card', 'post') ?>
            </div>
        <?php endwhile; ?>



        </div>

        <!-- pagination -->
        <?php //the_posts_pagination(); 
            //echo paginate_links();

            // echo next_posts_link();
            // echo previous_posts_link();
        ?>
        <!-- <nav aria-label="Page navigation example"> -->
            <!-- <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">Next</a></li>
                </ul>
            </nav> -->
            <?php //echo paginate_links(array('type' => 'list')); ?>
        <!-- </nav> -->
        <?php montheme_pagination() ?>
     <?php else: ?>  
        <h1>Pas d'article</h1>
     <?php endif; ?>


    <?php get_footer(); ?>
<!-- </body>
</html> -->