<?php

if ( has_nav_menu( 'primary' ) || has_nav_menu( 'social' ) || is_active_sidebar( 'sidebar-1' )  ) : ?>
	<div id="secondary" class="secondary">

		

<?php if ( is_active_sidebar( 'sidebar' ) ) : ?>
    <div id="primary" role="complementary">
        <ul>
            <?php dynamic_sidebar( 'sidebar' ); ?>
        </ul>
    </div>
<?php endif; ?>


	</div><!-- .secondary -->

<?php endif; ?>
