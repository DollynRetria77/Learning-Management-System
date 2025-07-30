<?php
	/**
	* The template for displaying all single posts and attachments
	*
	* This is the template that displays all pages by default.
	* Please note that this is the WordPress construct of pages and that
	* other "pages" on your WordPress site will use a different template.
	*
	* @package WordPress
	* @subpackage lms
	* @since lms 1.0
	*/
?>
<?php get_header(); ?>
<div class="container">
	<div class="row">
    	<div class="col-12 mt-3 mb-3">
		<?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
				<h2 class="title-h2"><?php the_title(); ?></h2>
				
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="post-img text-center mt-4 mb-4">
						<?php
							//the_post_thumbnail();
							$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'full');
							echo '<img src="' . $large_image_url[0] . '" alt="' . get_the_title() . '"/>';
						?>
					</div>
				<?php endif; ?>
				
                <div class="post-content">
                	<?php the_content(); ?>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>
