<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;

class Hitboox_Elementor__Menu_Canvas extends Elementor\Widget_Base {

    public function get_name() {
        return 'hitboox-menu-canvas';
    }

    public function get_title() {
        return esc_html__('Hitboox Menu Canvas', 'hitboox');
    }

    public function get_icon() {
        return 'eicon-nav-menu';
    }

    public function get_categories() {
        return ['hitboox-addons'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'icon-menu_style',
            [
                'label' => esc_html__('Menu Canvas', 'hitboox'),
                'tab'   => Controls_Manager::TAB_LAYOUT,
            ]
        );

        $this->add_control(
            'cavasmenu_icon',
            [
                'label'   => esc_html__('Menu Icon', 'hitboox'),
                'type'    => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'hitboox-icon-bars',
                    'library' => 'hitboox-icon',
                ],
            ]
        );

        $this->add_control(
            'menu_color',
            [
                'label'     => esc_html__('Color', 'hitboox'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .menu-mobile-nav-button .hitboox-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'menu_color_hover',
            [
                'label'     => esc_html__('Color Hover', 'hitboox'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .menu-mobile-nav-button:hover .hitboox-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'size',
            [
                'label' => esc_html__( 'Size', 'hitboox' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .menu-mobile-nav-button .hitboox-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .menu-mobile-nav-button .hitboox-icon svg' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'fit_to_size',
            [
                'label' => esc_html__( 'Fit to Size', 'hitboox' ),
                'type' => Controls_Manager::SWITCHER,
                'description' => 'Avoid gaps around icons when width and height aren\'t equal',
                'label_off' => esc_html__( 'Off', 'hitboox' ),
                'label_on' => esc_html__( 'On', 'hitboox' ),
                'condition' => [
                    'cavasmenu_icon[library]' => 'svg',
                ],
                'selectors' => [
                    '{{WRAPPER}} .menu-mobile-nav-button .hitboox-icon svg' => 'width: 100%;',
                ],
            ]
        );

        $this->add_control(
            'icon_padding',
            [
                'label' => esc_html__( 'Padding', 'hitboox' ),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .menu-mobile-nav-button .hitboox-icon' => 'padding: {{SIZE}}{{UNIT}};',
                ],
                'range' => [
                    'em' => [
                        'min' => 0,
                        'max' => 5,
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'rotate',
            [
                'label' => esc_html__( 'Rotate', 'hitboox' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'deg', 'grad', 'rad', 'turn', 'custom' ],
                'default' => [
                    'unit' => 'deg',
                ],
                'tablet_default' => [
                    'unit' => 'deg',
                ],
                'mobile_default' => [
                    'unit' => 'deg',
                ],
                'selectors' => [
                    '{{WRAPPER}} .menu-mobile-nav-button .hitboox-icon i, {{WRAPPER}} .menu-mobile-nav-button .hitboox-icon svg' => 'transform: rotate({{SIZE}}{{UNIT}});',
                ],
            ]
        );

        $this->end_controls_section();

    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $this->add_render_attribute('wrapper', 'class', 'elementor-canvas-menu-wrapper');
        ?>
        <div <?php $this->print_render_attribute_string('wrapper'); ?>>
            <a href="#" class="menu-mobile-nav-button">
                <div class="hitboox-icon">
                    <?php \Elementor\Icons_Manager::render_icon($settings['cavasmenu_icon']); ?>
                </div>
            </a>
        </div>
        <?php
    }

}

$widgets_manager->register(new Hitboox_Elementor__Menu_Canvas());
