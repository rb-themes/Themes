<?php
$sidebar = apply_filters('hitboox_theme_sidebar', '');
if (!$sidebar) {
    return;
}
?>

<div id="secondary" class="widget-area path-wrap-yes" role="complementary">
    <?php dynamic_sidebar($sidebar); ?>
</div><!-- #secondary -->
