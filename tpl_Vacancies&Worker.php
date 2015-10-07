<?php
/*
Template Name: Vacancies & Workers
*/
?>

<!-- Здесь html/php код шаблона -->
<?php get_header(); ?>

    <div id="content" class="site-content container">
    <div id="primary" class="content-area col-sm-12 col-md-8 <?php echo of_get_option( 'site_layout', 'no entry' ); ?>">
		<main id="main" class="site-main" role="main">

		<?php 
   $args = array('post_type' => array('areas_of_work', 'workers'), 'orderby' => 'name', 'order' => 'ASC');
   $query = new WP_Query ($args);

   while($query->have_posts()) { 

    $query->the_post(); ?>
   <div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
       <h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
       <?php the_content(); ?>
       <?php if (get_post_meta($post->ID, 'mytextinput', true)) {
           echo ('<p> Salary: '.(get_post_meta($post->ID, "mytextinput", true)).'</p>');
       } elseif (get_post_meta($post->ID, 'mytextinput2', true)){
           echo ('<p> Position: '.(get_post_meta($post->ID, 'mytextinput2', true)).'</p>');
       }
       ?>
   </div>  $custom_posts->post_count;

<?php } ?>
            <?php if ( $wp_query->max_num_pages > 1 ) : ?>
                <nav id="nav-below">
                    <div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">?</span> Previous page' ) ); ?></div>
                    <div class="nav-next"><?php previous_posts_link( __( 'Next page <span class="meta-nav">?</span>' ) ); ?></div>
                </nav><!-- #nav-below .navigation -->
            <?php endif; ?>


        </main><!-- #main -->
    </div><!-- #primary -->
	</div><!-- .content-area -->

<?php get_footer(); 

?>