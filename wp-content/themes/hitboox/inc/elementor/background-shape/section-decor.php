<?php

if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Element_Base;

if (!class_exists('Hitboox_Elementor_Section_Decor')) :
    class Hitboox_Elementor_Section_Decor
    {
        public function __construct()
        {

            add_action('elementor/element/common/_section_responsive/after_section_end', [$this, 'register_controls']);
            add_action('elementor/element/section/_section_responsive/after_section_end', [$this, 'register_controls']);
            add_action('elementor/element/container/_section_responsive/after_section_end', [$this, 'register_controls']);
            add_action('elementor/frontend/after_enqueue_scripts', [$this, 'enqueue_scripts']);
        }

        public function enqueue_scripts()
        {
            wp_enqueue_script('hitboox-background-shape', get_theme_file_uri('inc/elementor/background-shape/background-shape.js'), ['jquery'], HITBOOX_VERSION);
        }

        /**
         * @param $element \Elementor\Controls_Stack
         * @param $section_id
         * @param $args
         */
        public function register_controls(Element_Base $element)
        {

            $element->start_controls_section(
                'section_hitboox_decor',
                [
                    'label' => esc_html__('Hitboox Backgroud Shape', 'hitboox'),
                    'tab' => Controls_Manager::TAB_ADVANCED,
                ]
            );

            $element->add_control('hitboox_decor_top_left',
                [
                    'label' => esc_html__('Enable Top Left', 'hitboox'),
                    'type' => Controls_Manager::SWITCHER,
                    'frontend_available' => true,
                ]
            );

            $element->add_control('hitboox_decor_top_right',
                [
                    'label' => esc_html__('Enable Top Right', 'hitboox'),
                    'type' => Controls_Manager::SWITCHER,
                    'frontend_available' => true,
                ]
            );

            $element->add_control('hitboox_decor_bottom_right',
                [
                    'label' => esc_html__('Enable Bottom Right', 'hitboox'),
                    'type' => Controls_Manager::SWITCHER,
                    'frontend_available' => true,
                ]
            );

            $element->add_control('hitboox_decor_bottom_left',
                [
                    'label' => esc_html__('Enable Bottom Left', 'hitboox'),
                    'type' => Controls_Manager::SWITCHER,
                    'frontend_available' => true,
                ]
            );

            $element->add_control('hitboox_decor_color',
                [
                    'label' => esc_html__('Color', 'hitboox'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .hitboox-border-shape svg' => 'fill: {{VALUE}};',
                    ],
                ]
            );

            $element->add_responsive_control('hitboox_decor_width',
                [
                    'label' => esc_html__('Width', 'hitboox'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'unit' => 'px',
                    ],
                    'size_units' => ['%', 'px', 'vw'],
                    'selectors' => [
                        '{{WRAPPER}} .hitboox-border-shape svg' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
            $element->end_controls_section();
        }
    }
endif;

return new Hitboox_Elementor_Section_Decor();
