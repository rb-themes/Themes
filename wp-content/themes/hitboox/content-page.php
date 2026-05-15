<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	/**
	 * Functions hooked in to hitboox_page action
	 *
	 * @see hitboox_page_header          - 10
	 * @see hitboox_page_content         - 20
	 *
	 */
	do_action( 'hitboox_page' );
	?>
</article><!-- #post-## -->
