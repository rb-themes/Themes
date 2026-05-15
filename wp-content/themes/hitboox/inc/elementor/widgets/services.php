<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;


/**
 * Class Hitboox_Elementor_Service
 */
class Hitboox_Elementor_Service extends Hitboox_Base_Widgets_Swiper {

    public function get_name() {
        return 'hitboox-services';
    }

    public function get_title() {
        return esc_html__('Hitboox Service', 'hitboox');
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

    public function get_script_depends() {
        return ['hitboox-elementor-services', 'hitboox-elementor-swiper'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_query',
            [
                'label' => esc_html__('Service', 'hitboox'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label'   => esc_html__('Posts Per Page', 'hitboox'),
                'type'    => Controls_Manager::NUMBER,
                'default' => 6,
            ]
        );

        $this->add_control(
            'service_style',
            [
                'label'        => esc_html__('Style', 'hitboox'),
                'type'         => \Elementor\Controls_Manager::SELECT,
                'options'      => [
                    'service-style-1' => esc_html__('Style 1', 'hitboox'),
                    'service-style-2' => esc_html__('Style 2', 'hitboox'),
                    'service-style-3' => esc_html__('Style 3', 'hitboox'),
                    'service-style-4' => esc_html__('Style 4', 'hitboox'),
                ],
                'render_type'  => 'template',
                'default'      => 'service-style-1',
                'prefix_class' => 'elementor-'
            ]
        );

        $this->add_control(
            'includes_ids',
            [
                'label'       => esc_html__('Includes', 'hitboox'),
                'type'         => 'hitboox_query',
                'autocomplete' => [
                    'object' => 'hitboox_services',
                ],
                'label_block' => true,
                'multiple'    => true,
            ]
        );

        $this->add_control(
            'excludes_ids',

            [
                'label'       => esc_html__('Excludes', 'hitboox'),
                'type'         => 'hitboox_query',
                'autocomplete' => [
                    'object' => 'hitboox_services',
                ],
                'label_block' => true,
                'multiple'    => true,
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label'   => esc_html__('Order By', 'hitboox'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'post_date',
                'options' => [
                    'post_date'  => esc_html__('Date', 'hitboox'),
                    'post_title' => esc_html__('Title', 'hitboox'),
                    'menu_order' => esc_html__('Menu Order', 'hitboox'),
                    'rand'       => esc_html__('Random', 'hitboox'),
                ],
            ]
        );

        $this->add_control(
            'order',
            [
                'label'   => esc_html__('Order', 'hitboox'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'desc',
                'options' => [
                    'asc'  => esc_html__('ASC', 'hitboox'),
                    'desc' => esc_html__('DESC', 'hitboox'),
                ],
            ]
        );

        $this->add_responsive_control(
            'column',
            [
                'label'     => esc_html__('Columns', 'hitboox'),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'default'   => 3,
                'options'   => [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5],
                'selectors' => [
                    '{{WRAPPER}} .d-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr)',
                ],

                'conditions'   => [
                    'relation' => 'and',
                    'terms'    => [
                        [
                            'name'     => 'service_style',
                            'operator' => '!==',
                            'value'    => 'service-style-2',
                        ],
                        [
                            'name'     => 'service_style',
                            'operator' => '!==',
                            'value'    => 'service-style-3',
                        ],
                        [
                            'name'     => 'enable_carousel',
                            'operator' => '!==',
                            'value'    => 'yes',
                        ],
                    ],
                ]
            ]
        );

        $this->add_responsive_control(
            'item_spacing',
            [
                'label'      => esc_html__('Spacing', 'hitboox'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default'    => [
                    'size' => 30
                ],
                'size_units' => ['px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .d-grid' => 'grid-gap:{{SIZE}}{{UNIT}}',
                ],
                'condition'  => ['enable_carousel!' => 'yes']
            ]
        );

        $this->add_control(
            'enable_carousel',
            [
                'label' => esc_html__('Enable Carousel', 'hitboox'),
                'type'  => Controls_Manager::SWITCHER,

                'conditions'   => [
                    'relation' => 'and',
                    'terms'    => [
                        [
                            'name'     => 'service_style',
                            'operator' => '!==',
                            'value'    => 'service-style-2',
                        ],
                        [
                            'name'     => 'service_style',
                            'operator' => '!==',
                            'value'    => 'service-style-3',
                        ],
                    ],
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_pagination',
            [
                'label'     => esc_html__('Pagination', 'hitboox'),
                'conditions'   => [
                    'relation' => 'and',
                    'terms'    => [
                        [
                            'name'     => 'service_style',
                            'operator' => '!==',
                            'value'    => 'service-style-2',
                        ],
                        [
                            'name'     => 'service_style',
                            'operator' => '!==',
                            'value'    => 'service-style-3',
                        ],
                        [
                            'name'     => 'enable_carousel',
                            'operator' => '!==',
                            'value'    => 'yes',
                        ],
                    ],
                ]
            ]

        );

        $this->add_control(
            'pagination_type',
            [
                'label'   => esc_html__('Pagination', 'hitboox'),
                'type'    => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    ''                      => esc_html__('None', 'hitboox'),
                    'numbers'               => esc_html__('Yes', 'hitboox'),
                ],
            ]
        );

        $this->add_control(
            'pagination_page_limit',
            [
                'label'     => esc_html__('Page Limit', 'hitboox'),
                'default'   => '5',
                'condition' => [
                    'pagination_type!' => '',
                ],
            ]
        );

        $this->add_control(
            'pagination_align',
            [
                'label'     => esc_html__('Alignment', 'hitboox'),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'flex-start' => [
                        'title' => esc_html__('Left', 'hitboox'),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center'     => [
                        'title' => esc_html__('Center', 'hitboox'),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'flex-end'   => [
                        'title' => esc_html__('Right', 'hitboox'),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'default'   => 'center',
                'selectors' => [
                    '{{WRAPPER}} ul.page-numbers' => 'justify-content: {{VALUE}};',
                ],
                'condition' => [
                    'pagination_type!' => '',
                ],
            ]
        );

        $this->end_controls_section();

        // WRAPPER STYLE
        $this->start_controls_section(
            'section_style_wrapper',
            [
                'label' => esc_html__('Wrapper', 'hitboox'),
                'tab' => Controls_Manager::TAB_STYLE,

            ]
        );

        $this->add_control(
            'service_wrapper_bg',
            [
                'label' => esc_html__('Background Color', 'hitboox'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .service-inner' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .service-inner:before' => 'background: {{VALUE}};',
                ],
                'conditions'   => [
                    'relation' => 'or',
                    'terms'    => [
                        [
                            'name'     => 'service_style',
                            'operator' => '==',
                            'value'    => 'service-style-2',
                        ],
                        [
                            'name'     => 'service_style',
                            'operator' => '==',
                            'value'    => 'service-style-4',
                        ],
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'service_height',
            [
                'label' => esc_html__('Height', 'hitboox'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'px',
                ],
                'size_units' => ['px', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 1000,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .service-post-thumbnail' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'service_style' => 'service-style-3',
                ],
            ]
        );

        $this->add_responsive_control(
            'padding_wrapper',
            [
                'label' => esc_html__('Padding', 'hitboox'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%','custom'],
                'selectors' => [
                    '{{WRAPPER}} .service-item:not(.service-style-2) .service-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}}.elementor-service-style-2 .service-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}}.elementor-service-style-4 .service-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

                ],
            ]
        );

        $this->add_responsive_control(
            'item_gap',
            [
                'label'      => esc_html__('Column Gap', 'hitboox'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                    ],
                ],
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}}.elementor-service-style-2 .service-inner' => 'column-gap:{{SIZE}}{{UNIT}}',
                ],
                'condition'  => ['service_style' => 'service-style-2']
            ]
        );

        $this->end_controls_section();

        // Icon style
        $this->start_controls_section(
            'section_style_icon',
            [
                'label' => esc_html__('Icon', 'hitboox'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'service_style' => 'service-style-4',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label' => esc_html__('Icon Size', 'hitboox'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .icon-wrap img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_spacing',
            [
                'label' => esc_html__('Icon Spacing', 'hitboox'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .icon-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_title_style',
            [
                'label' => esc_html__('Title', 'hitboox'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'typography',
                'global'   => [
                    'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
                ],
                'selector' => '{{WRAPPER}} .service-title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => esc_html__('Color', 'hitboox'),
                'type'      => Controls_Manager::COLOR,

                'selectors' => [
                    '{{WRAPPER}} .service-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_spacing',
            [
                'label' => esc_html__('Spacing', 'hitboox'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .service-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_discription_style',
            [
                'label' => esc_html__('Discription', 'hitboox'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'typography_discription',
                'global'   => [
                    'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
                ],
                'selector' => '{{WRAPPER}} .description',
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label'     => esc_html__('Color', 'hitboox'),
                'type'      => Controls_Manager::COLOR,

                'selectors' => [
                    '{{WRAPPER}} .description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'description_spacing',
            [
                'label' => esc_html__('Spacing', 'hitboox'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_feature_style',
            [
                'label' => esc_html__('Feature', 'hitboox'),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'service_style' => 'service-style-4',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'typography_feature',
                'global'   => [
                    'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
                ],
                'selector' => '{{WRAPPER}} .service-features',
            ]
        );

        $this->add_control(
            'feature_color',
            [
                'label'     => esc_html__('Color', 'hitboox'),
                'type'      => Controls_Manager::COLOR,

                'selectors' => [
                    '{{WRAPPER}} .service-features' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'feature_spacing',
            [
                'label' => esc_html__('Spacing', 'hitboox'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .service-features' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_number_style',
            [
                'label' => esc_html__('Number', 'hitboox'),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'service_style!' => 'service-style-1',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'typography_number',
                'global'   => [
                    'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
                ],
                'selector' => '{{WRAPPER}}.elementor-service-style-2 .service-inner:before,{{WRAPPER}}.elementor-service-style-3 .service-post-thumbnail:before',
            ]
        );

        $this->add_control(
            'number_color_normal',
            [
                'label'     => esc_html__('Color Normal', 'hitboox'),
                'type'      => Controls_Manager::COLOR,

                'selectors' => [
                    '{{WRAPPER}}.elementor-service-style-2 .service-inner:before' => 'color: {{VALUE}};',
                    '{{WRAPPER}}.elementor-service-style-3 .service-post-thumbnail:before' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'number_color_active',
            [
                'label'     => esc_html__('Color Active', 'hitboox'),
                'type'      => Controls_Manager::COLOR,

                'selectors' => [
                    '{{WRAPPER}}.elementor-service-style-2 .grid-item.active .service-inner:before' => 'color: {{VALUE}};',
                    '{{WRAPPER}}.elementor-service-style-3 .grid-item.active .service-post-thumbnail:before' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'number_spacing',
            [
                'label' => esc_html__('Spacing Bottom', 'hitboox'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}.elementor-service-style-2 .service-inner:before' => 'bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'service_style' => 'service-style-2',
                ],
            ]
        );

        $this->add_responsive_control(
            'number_position_left',
            [
                'label' => esc_html__('Position Left', 'hitboox'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}.elementor-service-style-2 .service-inner:before' => 'left: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'service_style' => 'service-style-2',
                ],
            ]
        );

        $this->add_responsive_control(
            'number_position',
            [
                'label' => esc_html__('Position', 'hitboox'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}.elementor-service-style-3 .service-post-thumbnail:before' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'service_style' => 'service-style-3',
                ],
            ]
        );

        $this->end_controls_section();

        // Image style
        $this->start_controls_section(
            'section_style_image',
            [
                'label' => esc_html__('Image', 'hitboox'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_height',
            [
                'label' => esc_html__('Height', 'hitboox'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'unit' => 'px',
                ],
                'size_units' => ['px', 'vh'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 450,
                    ],
                    'vh' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .service-post-thumbnail' => 'height: {{SIZE}}{{UNIT}};padding-top:0;',
                ],
            ]
        );

        $this->add_control(
            'image_border_radius',
            [
                'label' => esc_html__('Border Radius', 'hitboox'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .service-post-thumbnail img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_margin',
            [
                'label' => esc_html__('Margin', 'hitboox'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .service-post-thumbnail' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_button',
            [
                'label' => esc_html__('Button', 'hitboox'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography_button',
                'selector' => '{{WRAPPER}} .elementor-button',
            ]
        );

        $this->start_controls_tabs('tabs_button_style');

        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label' => esc_html__('Normal', 'hitboox'),
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => esc_html__('Text Color', 'hitboox'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .service-button span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'border_color',
            [
                'label' => esc_html__('Border Color', 'hitboox'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .service-button.path-border-yes .path-border' => 'stroke: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'background_color',
            [
                'label' => esc_html__('Background Color', 'hitboox'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .service-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label' => esc_html__('Hover', 'hitboox'),
            ]
        );

        $this->add_control(
            'hover_button_color',
            [
                'label' => esc_html__('Text Color', 'hitboox'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .service-button:hover span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'border_color_hover',
            [
                'label' => esc_html__('Border Color', 'hitboox'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .service-button.path-border-yes:hover .path-border' => 'stroke: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'background_color_hover',
            [
                'label' => esc_html__('Background Color', 'hitboox'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .service-button:hover' => '--bg-hover: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => esc_html__('Padding', 'hitboox'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .service-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->add_control_carousel(['enable_carousel' => 'yes']);

    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $this->add_render_attribute('wrapper', 'class', 'elementor-service-wrapper');

        $this->get_data_elementor_columns();

        $this->add_render_attribute('item', 'class', 'service-item ' . esc_attr($settings['service_style']));

        $allowed_styles = [
            'service-style-1',
            'service-style-2',
            'service-style-3',
            'service-style-4',
        ];

        $style = $settings['service_style'] ?? 'service-style-1';

        if ( ! in_array( $style, $allowed_styles, true ) ) {
            $style = 'service-style-1';
        }

        $query = $this->query_posts();

        if (!$query->found_posts) {
            return;
        }


        ?>
        <div <?php $this->print_render_attribute_string('wrapper'); ?>>
            <div <?php $this->print_render_attribute_string('row'); ?>>
                <?php
                while ($query->have_posts()) {
                    $query->the_post();
                    ?>
                    <div <?php $this->print_render_attribute_string('item'); ?>>
                        <?php get_template_part('template-parts/services/item', $style); ?>
                    </div>
                    <?php
                }
                ?>
            </div>

        </div>
        <?php $this->render_swiper_pagination_navigation();
        if ($settings['pagination_type'] && !empty($settings['pagination_type'])) {
            $this->render_loop_footer();
        }
        wp_reset_postdata();

    }

    public static function get_query_args($settings) {
        $query_args = [
            'posts_per_page'      => $settings['posts_per_page'],
            'post_type'           => 'hitboox_services',
            'orderby'             => $settings['orderby'],
            'order'               => $settings['order'],
            'ignore_sticky_posts' => 1,
            'post__in'            => $settings['includes_ids'],
            'post__not_in'        => $settings['excludes_ids'],
            'post_status'         => 'publish',
        ];

        if (is_front_page()) {
            $query_args['paged'] = (get_query_var('page')) ? get_query_var('page') : 1;
        } else {
            $query_args['paged'] = (get_query_var('paged')) ? get_query_var('paged') : 1;
        }

        return $query_args;
    }

    public function query_posts() {
        $query_args = $this->get_query_args($this->get_settings());
        return new WP_Query($query_args);
    }

    protected function render_loop_footer() {

        $parent_settings = $this->get_settings();
        if ('' === $parent_settings['pagination_type']) {
            return;
        }

        $page_limit = $this->query_posts()->max_num_pages;
        if ('' !== $parent_settings['pagination_page_limit']) {
            $page_limit = min($parent_settings['pagination_page_limit'], $page_limit);
        }

        if (2 > $page_limit) {
            return;
        }

        $this->add_render_attribute('pagination', 'class', 'elementor-pagination');

        $has_numbers = in_array($parent_settings['pagination_type'], ['numbers']);

        $links = [];

        if ($has_numbers) {
            $links = paginate_links([
                'type'               => 'list',
                'current'            => $this->get_current_page(),
                'total'              => $page_limit,
                'prev_text'          => '<i class="hitboox-icon-chevron-left"></i>',
                'next_text'          => '<i class="hitboox-icon-chevron-right"></i>',
                'before_page_number' => '<span class="elementor-screen-only">' . esc_html__('Page', 'hitboox') . '</span>',
            ]);
        }

        ?>
        <nav class="pagination">
            <div class="nav-links">
                <?php printf('%s', $links); ?>
            </div>
        </nav>
        <?php
    }

    public function get_current_page() {
        if ('' === $this->get_settings('pagination_type')) {
            return 1;
        }

        return max(1, get_query_var('paged'), get_query_var('page'));
    }

}

$widgets_manager->register(new Hitboox_Elementor_Service());