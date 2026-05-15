<?php

/**
 * Producta_Control control.
 *
 */
class Hitboox_Query_Control extends \Elementor\Control_Select2 {

    public function get_type() {
        return 'hitboox_query';
    }

    public function enqueue() {

        wp_enqueue_script('elementor-hitboox-query-control', get_theme_file_uri('/inc/elementor/elementor-control/select2.js'), ['jquery'], HITBOOX_VERSION, true);
    }
}
