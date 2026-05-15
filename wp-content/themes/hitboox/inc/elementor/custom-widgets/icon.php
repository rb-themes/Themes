<?php

use Elementor\Controls_Manager;

add_action('elementor/element/icon/section_style_icon/before_section_end', function ($element, $args) {
    $element->add_control(
        'show_icon_effect',
        [
            'label'        => esc_html__('Show Icon Effect', 'hitboox'),
            'type'         => Controls_Manager::SWITCHER,
            'condition' => [
                'selected_icon[value]!' => '',
            ],
            'prefix_class' => 'effect-icon-',
        ]
    );

}, 10, 2);
