<?php
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;

add_action( 'elementor/element/counter/section_number/before_section_end', function ($element, $args ) {
    $element->add_responsive_control(
        'position_number',
        [
            'label'        => __('Alignment', 'hitboox'),
            'type'         => Controls_Manager::CHOOSE,
            'options'      => [
                'left' => [
                    'title' => __('Start', 'hitboox'),
                    'icon'  => 'eicon-text-align-left',
                ],
                'center'     => [
                    'title' => __('Center', 'hitboox'),
                    'icon'  => 'eicon-text-align-center',
                ],
                'right'   => [
                    'title' => __('End', 'hitboox'),
                    'icon'  => 'eicon-text-align-right',
                ]
            ],
            'default'      => 'center',
            'selectors'    => [
                '{{WRAPPER}} .elementor-counter-number-wrapper' => 'justify-content: {{VALUE}}',
            ],
        ]
    );
}, 10, 2 );

add_action( 'elementor/element/counter/section_title/before_section_end', function ($element, $args ) {
    $element->add_responsive_control(
        'position_title',
        [
            'label'        => __('Alignment', 'hitboox'),
            'type'         => Controls_Manager::CHOOSE,
            'options'      => [
                'left' => [
                    'title' => __('Left', 'hitboox'),
                    'icon'  => 'eicon-text-align-left',
                ],
                'center'     => [
                    'title' => __('Center', 'hitboox'),
                    'icon'  => 'eicon-text-align-center',
                ],
                'right'   => [
                    'title' => __('Right', 'hitboox'),
                    'icon'  => 'eicon-text-align-right',
                ]
            ],
            'toggle'       => false,
            'default'      => 'center',
            'selectors'    => [
                '{{WRAPPER}} .elementor-counter-title' => 'text-align: {{VALUE}}',
            ],
        ]
    );
}, 10, 2 );