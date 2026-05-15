
		</div><!-- .col-fluid -->

	</div><!-- #content -->

	<?php do_action( 'hitboox_before_footer' );
    if (hitboox_is_elementor_activated() && function_exists('hfe_init') && (hfe_footer_enabled() || hfe_is_before_footer_enabled())) {
        do_action('hfe_footer_before');
        do_action('hfe_footer');
    } else {
        ?>

        <footer id="colophon" class="site-footer" role="contentinfo">
            <?php
            /**
             * Functions hooked in to hitboox_footer action
             *
             * @see hitboox_footer_default - 20
             *
             *
             */
            do_action('hitboox_footer');

            ?>

        </footer><!-- #colophon -->

        <?php
    }

		/**
		 * Functions hooked in to hitboox_after_footer action
		 *
		 */
		do_action( 'hitboox_after_footer' );
	?>

</div><!-- #page -->

<?php

/**
 * Functions hooked in to wp_footer action
 * @see hitboox_template_account_dropdown 	- 1
 * @see hitboox_mobile_nav - 1
 * @see hitboox_render_woocommerce_shop_canvas - 1 - woo
 * @see render_html_back_to_top - 1
 *
 */

wp_footer();
?>
</body>
</html>
