<?php

if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Element_Base;

if (!class_exists('Hitboox_Elementor_Section_Path')) :
    class Hitboox_Elementor_Section_Path
    {
        public function __construct()
        {

            add_action('elementor/element/common/_section_responsive/after_section_end', [$this, 'register_controls']);
            add_action('elementor/element/section/_section_responsive/after_section_end', [$this, 'register_controls']);
            add_action('elementor/element/container/_section_responsive/after_section_end', [$this, 'register_controls']);
            add_action('elementor/frontend/after_enqueue_scripts', [$this, 'enqueue_scripts'], 1, 1);
        }

        public function enqueue_scripts()
        {
            wp_enqueue_script('hitboox-path-shape', get_theme_file_uri('inc/elementor/path-shape/path-radius.js'), ['jquery'], HITBOOX_VERSION);
        }

        /**
         * @param $element \Elementor\Controls_Stack
         * @param $section_id
         * @param $args
         */
        public function register_controls(Element_Base $element)
        {

            $element->start_controls_section(
                'section_hitboox_path',
                [
                    'label' => esc_html__('Clip Path Polygon Radius', 'hitboox'),
                    'tab' => Controls_Manager::TAB_ADVANCED,
                ]
            );

            $element->add_control('hitboox_path_radius',
                [
                    'label' => esc_html__('Enable', 'hitboox'),
                    'type' => Controls_Manager::SWITCHER,
                    'prefix_class' => 'path-wrap-',
                ]
            );

            $element->add_control('hitboox_path_radius_value',
                [
                    'label' => esc_html__('Path Radius', 'hitboox'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 5,
                    'frontend_available' => true,
                    'condition' => [
                        'hitboox_path_radius' => 'yes'
                    ]
                ]
            );

            $element->add_control('hitboox_path_text',
                [
                    'label' => esc_html__('Path Polygon Value', 'hitboox'),
                    'type' => Controls_Manager::TEXTAREA,
                    'placeholder' => '0 14px, 14px 0, 100% 0, 100% calc(100% - 14px), calc(100% - 14px) 100%, 0 100%',
                    'frontend_available' => true,
                    'condition' => [
                        'hitboox_path_radius' => 'yes'
                    ]
                ]
            );

            $element->add_control('hitboox_path_border',
                [
                    'label' => esc_html__('Enable Border', 'hitboox'),
                    'type' => Controls_Manager::SWITCHER,
                    'prefix_class' => 'path-border-',
                    'condition' => [
                        'hitboox_path_radius' => 'yes'
                    ]
                ]
            );

            $element->add_control('hitboox_border_color',
                [
                    'label' => esc_html__('Border Color', 'hitboox'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .path-border' => 'stroke:{{VALUE}};',
                    ],
                    'condition' => [
                        'hitboox_path_border' => 'yes'
                    ]
                ]
            );

            $element->add_control('hitboox_border_color_hover',
                [
                    'label' => esc_html__('Border Color Hover', 'hitboox'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}}:hover .path-border' => 'stroke:{{VALUE}};',
                    ],
                    'condition' => [
                        'hitboox_path_border' => 'yes'
                    ]
                ]
            );

            $element->add_responsive_control(
                'hitboox_border_size',
                [
                    'label' => esc_html__('Border Size', 'hitboox'),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 30,
                        ],
                    ],
                    'default' => [
                        'size' => 1
                    ],
                    'size_units' => ['px'],
                    'selectors' => [
                        '{{WRAPPER}} .path-border' => 'stroke-width: {{SIZE}}px',
                    ],
                    'condition' => [
                        'hitboox_path_border' => 'yes'
                    ]
                ]
            );
            $element->end_controls_section();
        }
    }
endif;

return new Hitboox_Elementor_Section_Path();
