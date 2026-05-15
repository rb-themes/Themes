<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;


/**
 * Class Autoros_Elementor_Project
 */
class Autoros_Elementor_Project_Detail_Logo extends \Elementor\Widget_Base {

    public function get_name() {
        return 'hitboox-projects-detail_logo';
    }

    public function get_title() {
        return esc_html__('Autoros Project Detail_Logo', 'hitboox');
    }

    /**
     * Get widget icon.
     *
     * Retrieve testimonial widget icon.
     *
     * @return string Widget icon.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_icon() {
        return 'eicon-posts-grid';
    }

    public function get_categories() {
        return array('hitboox-addons');
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_query',
            [
                'label' => esc_html__('Project Infomation', 'hitboox'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->end_controls_section();
    }


    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <?php
        $logo_url = get_post_meta(get_the_ID(), 'project_image_class', true);
        if ($logo_url && !empty($logo_url)) {
            echo '<div class="project-logo-wrap"><img class="project-logo" src="' . esc_url($logo_url) . '" alt="project logo"></div>';
        }
        ?>
        <?php
    }
}

$widgets_manager->register(new Autoros_Elementor_Project_Detail_Logo());