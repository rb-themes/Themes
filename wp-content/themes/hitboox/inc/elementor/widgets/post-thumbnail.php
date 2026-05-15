<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;

class Hitboox_Elementor_Post_Thumbnail extends Elementor\Widget_Base {

    public function get_name() {
        return 'hitboox-post-thumbnails';
    }

    public function get_title() {
        return esc_html__('Hitboox Post Thumbnail', 'hitboox');
    }

    public function get_icon() {
        return 'eicon-image';
    }

    public function get_categories() {
        return array('hitboox-addons');
    }


    protected function register_controls() {
        $this->start_controls_section(
            'section_config',
            [
                'label' => esc_html__('Style', 'hitboox'),
                'tab'   => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'      => 'thumbnails',
                'separator' => 'none',
                'default'   => 'post-thumbnail'
            ]
        );

        $this->add_responsive_control(
            'imgage_width',
            [
                'label' => esc_html__( 'Width', 'hitboox' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-post-thumbnail img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'imgage_height',
            [
                'label' => esc_html__( 'Height', 'hitboox' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-post-thumbnail img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'imgage_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'hitboox' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-post-thumbnail img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'align',
            [
                'label'     => esc_html__('Alignment', 'hitboox'),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'flex-start'   => [
                        'title' => esc_html__('Left', 'hitboox'),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'hitboox'),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'flex-end'  => [
                        'title' => esc_html__('Right', 'hitboox'),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-post-thumbnail' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function render() {
        $settings = $this->get_settings_for_display();

        if (!is_single()) {
            return;
        }
        if (has_post_thumbnail()) {

            $settings['thumbnails']['id']  = get_post_thumbnail_id();
            $settings['thumbnails']['url'] = get_the_post_thumbnail_url();
            echo '<div class="elementor-post-thumbnail">';
            Group_Control_Image_Size::print_attachment_image_html($settings, 'thumbnails');
            echo '</div>';
        }
    }

}

$widgets_manager->register(new Hitboox_Elementor_Post_Thumbnail());