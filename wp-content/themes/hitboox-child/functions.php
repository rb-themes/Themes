<?php
/**
 * Theme functions and definitions.
 */
		 
// Register Hitboox Slide Scrolling Custom Widget
function hitboox_register_custom_scrolling_widget($widgets_manager) {
    // Use child theme directory (since widget file is in child theme)
    $widget_file = get_stylesheet_directory() . '/inc/elementor/widgets/hitboox-slide-scrolling-custom.php';
    
    if (file_exists($widget_file)) {
        require_once $widget_file;
        
        if (class_exists('Hitboox_Elementor_Slide_Scrolling_Custom')) {
            $widgets_manager->register(new Hitboox_Elementor_Slide_Scrolling_Custom());
        }
    }
}

// Register widget - use priority 20 to ensure it runs after theme setup
add_action('elementor/widgets/register', 'hitboox_register_custom_scrolling_widget', 20);