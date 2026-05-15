<?php
// Button
use Elementor\Controls_Manager;

add_action('elementor/element/button/section_style/after_section_end', function ($element, $args) {
    /** @var \Elementor\Element_Base $element */
    $element->update_control(
        'background_color',
        [
            'global' => [
                'default' => '',
            ],
            'selectors' => [
                '{{WRAPPER}}.elementor-widget-button .elementor-button' => 'background-color: {{VALUE}};',
            ],

        ]
    );

    $element->update_control(
        'button_background_hover_color',
        [
            'global' => [
                'default' => '',
            ],
            'selectors' => [
                '{{WRAPPER}}.elementor-widget-button .elementor-button:hover:before' => 'background-color: {{VALUE}};',
            ],

        ]
    );

    $element->update_control(
        'button_text_color',
        [
            'global' => [
                'default' => '',
            ],
            'selectors' => [
                '{{WRAPPER}} .elementor-button' => 'color: {{VALUE}};',
                '{{WRAPPER}} .elementor-button svg' => 'fill: {{VALUE}};',
            ],
        ]
    );

    $element->update_control(
        'hover_color',
        [
            'global' => [
                'default' => '',
            ],
            'selectors' => [
                '{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:focus' => 'color: {{VALUE}};',
                '{{WRAPPER}} .elementor-button:hover svg, {{WRAPPER}} .elementor-button:focus svg' => 'fill: {{VALUE}};',
            ],
        ]
    );

}, 10, 2);

add_action('elementor/element/button/section_button/before_section_end', function ($element, $args) {
    $element->add_control(
        'icon_size',
        [
            'label' => esc_html__('Icon Size', 'hitboox'),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'max' => 50,
                ],
            ],
            'condition' => [
                'selected_icon[value]!' => '',
            ],
            'selectors' => [
                '{{WRAPPER}} .elementor-button .elementor-button-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .elementor-button .elementor-button-icon svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
            ],
        ]
    );
}, 10, 2);
