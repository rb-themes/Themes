<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Border;

class Hitboox_Elementor_IconBoxs extends Hitboox_Base_Widgets_Swiper
{
    /**
     * Get widget name.
     *
     * Retrieve iconbox widget name.
     *
     * @return string Widget name.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_name()
    {
        return 'hitboox-iconbox';
    }

    /**
     * Get widget title.
     *
     * Retrieve iconbox widget title.
     *
     * @return string Widget title.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_title()
    {
        return esc_html__('Hitboox Iconbox Carousel', 'hitboox');
    }

    /**
     * Get widget icon.
     *
     * Retrieve iconbox widget icon.
     *
     * @return string Widget icon.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_icon()
    {
        return 'eicon-carousel';
    }

    public function get_script_depends()
    {
        return ['hitboox-elementor-iconbox', 'hitboox-elementor-swiper'];
    }

    public function get_categories()
    {
        return array('hitboox-addons');
    }

    /**
     * Register iconbox widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since  1.0.0
     * @access protected
     */
    protected function register_controls()
    {
        $this->start_controls_section(
            'section_iconbox',
            [
                'label' => esc_html__('IconBoxs', 'hitboox'),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'iconbox_icon',
            [
                'label'            => esc_html__('Icon', 'hitboox'),
                'type'             => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'default'          => [
                    'value'   => 'fas fa-star',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $repeater->add_control(
            'iconbox_subtitle',
            [
                'label' => esc_html__('Sub Title', 'hitboox'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
            ]
        );

        $repeater->add_control(
            'iconbox_title',
            [
                'label' => esc_html__('Title', 'hitboox'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Title',
            ]
        );

        $repeater->add_control(
            'iconbox_desc',
            [
                'label' => esc_html__('Content', 'hitboox'),
                'type' => Controls_Manager::WYSIWYG,
                'default' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.',
            ]
        );

        $repeater->add_control(
            'iconbox_button',
            [
                'label' => esc_html__('Button Text', 'hitboox'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__('Click Here', 'hitboox'),
                'separator' => 'before',
            ]
        );

        $repeater->add_control(
            'iconbox_link',
            [
                'label' => esc_html__('Link to', 'hitboox'),
                'placeholder' => esc_html__('https://your-link.com', 'hitboox'),
                'type' => Controls_Manager::URL,
            ]
        );

        $this->add_control(
            'iconboxs',
            [
                'label' => esc_html__('Items', 'hitboox'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ iconbox_title }}}',
            ]
        );

        $this->add_responsive_control(
            'column',
            [
                'label' => esc_html__('Columns', 'hitboox'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 3,
                'options' => [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6],
                'selectors' => [
                    '{{WRAPPER}} .d-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr)',
                ],
                'condition' => ['enable_carousel!' => 'yes']
            ]
        );

        $this->add_responsive_control(
            'gutter',
            [
                'label' => esc_html__('Gutter', 'hitboox'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default'    => [
                                    'size' => 30
                                ],
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .d-grid' => 'grid-gap:{{SIZE}}{{UNIT}}',
                    '{{WRAPPER}}.enable-border-yes .grid-item' => '--gutter-width:{{SIZE}}{{UNIT}}',
                ],
                'condition' => ['enable_carousel!' => 'yes']
            ]
        );

        $this->add_control(
            'enable_border',
            [
                'label' => esc_html__('Border', 'hitboox'),
                'type' => Controls_Manager::SWITCHER,
                'prefix_class' => 'enable-border-',
            ]
        );

        $this->add_responsive_control(
            'border_height',
            [
                'label' => esc_html__('Height', 'hitboox'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}.enable-border-yes .grid-item:after' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'enable_border' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'border_color',
            [
                'label' => esc_html__('Border Color', 'hitboox'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}.enable-border-yes .grid-item:after' => 'background-color: {{VALUE}};',
                ],
                'condition' => ['enable_border' => 'yes']
            ]
        );

        $this->add_control(
            'enable_carousel',
            [
                'label' => esc_html__('Enable Carousel', 'hitboox'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'view',
            [
                'label' => esc_html__('View', 'hitboox'),
                'type' => Controls_Manager::HIDDEN,
                'default' => 'traditional',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'iconbox_wrapper',
            [
                'label' => esc_html__('Wrapper', 'hitboox'),
                'tab' => Controls_Manager::TAB_STYLE,

            ]
        );

        $this->add_control(
            'style_stretch',
            [
                'label' => esc_html__('Stretch', 'hitboox'),
                'type' => Controls_Manager::SWITCHER,
                'prefix_class' => 'style-stretch-',
            ]
        );

        $this->add_responsive_control(
            'iconbox_align',
            [
                'label'     => esc_html__('Wrap Alignment', 'hitboox'),
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
                    '{{WRAPPER}} .elementor-iconbox-item' => 'align-items: {{VALUE}};',
                    '{{WRAPPER}} .iconbox-wrapper' => 'align-items: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'iconbox_content_align',
            [
                'label'     => esc_html__('Content Alignment', 'hitboox'),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left' => [
                        'title' => esc_html__('Left', 'hitboox'),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center'     => [
                        'title' => esc_html__('Center', 'hitboox'),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'   => [
                        'title' => esc_html__('Right', 'hitboox'),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'default'   => 'center',
                'selectors' => [
                    '{{WRAPPER}} .iconbox-content' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'iconbox_wrapper_height',
            [
                'label'      => esc_html__('Min Height', 'hitboox'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem', 'vh', 'custom'],
                'range'      => [
                    'px' => [
                        'min' => 100,
                        'max' => 1000,
                    ],
                    'vh' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .elementor-iconbox-item' => 'min-height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'iconbox_padding_wrapper',
            [
                'label' => esc_html__('Padding', 'hitboox'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-iconbox-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'iconbox_wrapper_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'hitboox'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'custom'],
                'selectors'  => [
                    '{{WRAPPER}} .elementor-iconbox-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'iconbox_wrapper_image_border',
                'selector'  => '{{WRAPPER}} .elementor-iconbox-item',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'iconbox_wrapper_box_shadow',
                'selector' => '{{WRAPPER}} .elementor-iconbox-item',
            ]
        );

        $this->add_control(
            'wrapper_bg_color',
            [
                'label' => esc_html__('Background Color', 'hitboox'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-iconbox-item' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'path_wrap',
            [
                'label' => esc_html__('Clip Path', 'hitboox'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'path_border',
            [
                'label' => esc_html__('Path Border', 'hitboox'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'path_wrap' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // COUNT
        $this->start_controls_section(
            'section_style_count',
            [
                'label' => esc_html__('Count', 'hitboox'),
                'tab' => Controls_Manager::TAB_STYLE,

            ]
        );

        $this->add_control(
            'count_hidden',
            [
                'label' => esc_html__('Hidden', 'hitboox'),
                'type' => Controls_Manager::SWITCHER,
                'prefix_class' => 'count-hidden-',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'count_typography',
                'selector' => '{{WRAPPER}} .elementor-iconbox-item:before',
            ]
        );

        $this->add_responsive_control(
            'padding_count',
            [
                'label' => esc_html__('Padding', 'hitboox'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-iconbox-item:before' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'margin_count',
            [
                'label' => esc_html__('Margin', 'hitboox'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-iconbox-item:before' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'count_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'hitboox'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'custom'],
                'selectors'  => [
                    '{{WRAPPER}} .elementor-iconbox-item:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->start_controls_tabs('count_tabs');

        $this->start_controls_tab('count_normal',
            [
                'label' => esc_html__('Normal', 'hitboox'),
            ]
        );

        $this->add_control(
            'count_text_color',
            [
                'label' => esc_html__('Color', 'hitboox'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-iconbox-item:before' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'count_bg_color',
            [
                'label' => esc_html__('Background Color', 'hitboox'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-iconbox-item:before' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab('count_hover',
            [
                'label' => esc_html__('Hover', 'hitboox'),
            ]
        );

        $this->add_control(
            'count_text_color_hover',
            [
                'label' => esc_html__('Color', 'hitboox'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-iconbox-item:hover:before' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'count_bg_color_hover',
            [
                'label' => esc_html__('Background Color', 'hitboox'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-iconbox-item:hover:before' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        // Icon
        $this->start_controls_section(
            'section_style_icon',
            [
                'label' => esc_html__('Icon', 'hitboox'),
                'tab' => Controls_Manager::TAB_STYLE,

            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => esc_html__('Color', 'hitboox'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .iconbox-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .iconbox-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label'      => esc_html__('Size', 'hitboox'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'size_units' => ['px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .iconbox-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .iconbox-icon svg' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_spacing',
            [
                'label'      => esc_html__('Spacing', 'hitboox'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'size_units' => ['px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .iconbox-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Title.
        $this->start_controls_section(
            'section_style_iconbox_title',
            [
                'label' => esc_html__('Title', 'hitboox'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'padding_title',
            [
                'label' => esc_html__('Padding', 'hitboox'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .iconbox-imgwrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'iconbox_subtitle',
            [
                'type'      => Controls_Manager::HEADING,
                'label'     => esc_html__('Sub Title', 'hitboox'),
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'subtitle_typography',
                'selector' => '{{WRAPPER}} .iconbox-subtitle',
            ]
        );

        $this->add_control(
            'iconbox_subtitle_color',
            [
                'label' => esc_html__('Color', 'hitboox'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .iconbox-subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'subtitle_spacing',
            [
                'label'      => esc_html__('Spacing', 'hitboox'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'size_units' => ['px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .iconbox-subtitle' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'iconbox_title',
            [
                'type'      => Controls_Manager::HEADING,
                'label'     => esc_html__('Title', 'hitboox'),
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .iconbox-title',
            ]
        );

        $this->add_control(
            'iconbox_title_color',
            [
                'label' => esc_html__('Color', 'hitboox'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .iconbox-title a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .iconbox-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_spacing',
            [
                'label'      => esc_html__('Spacing', 'hitboox'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'size_units' => ['px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .iconbox-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        //Description
        $this->start_controls_section(
            'section_style_iconbox_desc',
            [
                'label' => esc_html__('Description', 'hitboox'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'desc_text_color',
            [
                'label' => esc_html__('Color', 'hitboox'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .iconbox-desc' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'desc_typography',
                'selector' => '{{WRAPPER}} .iconbox-desc',
            ]
        );

        $this->add_responsive_control(
            'padding_desc',
            [
                'label' => esc_html__('Padding', 'hitboox'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .iconbox-desc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_iconbox_button',
            [
                'label' => esc_html__('Button', 'hitboox'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'button_style_theme',
            [
                'label' => esc_html__('Style', 'hitboox'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'default' => 'Default',
                    'link' => 'Link',
                ],
                'default' => 'default',
                'prefix_class' => 'button-style-theme-',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .elementor-button',
            ]
        );

        $this->start_controls_tabs('button_tabs');

        $this->start_controls_tab('button_normal',
            [
                'label' => esc_html__('Normal', 'hitboox'),
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => esc_html__('Text Color', 'hitboox'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-button' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-button a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_icon_color',
            [
                'label' => esc_html__('Icon Color', 'hitboox'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-button i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-button svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_background_color',
            [
                'label' => esc_html__('Background Color', 'hitboox'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-button' => 'background-color: {{VALUE}};',

                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'button-hover',
            [
                'label' => esc_html__('Hover', 'hitboox'),
            ]
        );

        $this->add_control(
            'button_hover_text_color',
            [
                'label' => esc_html__('Text Color', 'hitboox'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-button:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-button:hover a' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_hover_icon_color',
            [
                'label' => esc_html__('Icon Color', 'hitboox'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-button:hover i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-button:hover svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_background_color',
            [
                'label' => esc_html__('Background Color', 'hitboox'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-button:before' => 'background-color: {{VALUE}};',

                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'margin_button',
            [
                'label' => esc_html__('Margin', 'hitboox'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'padding_button',
            [
                'label' => esc_html__('Padding', 'hitboox'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();

        // Carousel options
        $this->add_control_carousel(['enable_carousel' => 'yes']);

    }

    /**
     * Render iconbox widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since  1.0.0
     * @access protected
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $path_wrap_active = !empty($settings['path_wrap']) ? 'path-wrap-yes' : '';

        $path_border_active = !empty($settings['path_border']) ? 'path-border-yes path-border-gradient-yes' : '';

        if (!empty($settings['iconboxs']) && is_array($settings['iconboxs'])) {
            $this->add_render_attribute('wrapper', 'class', 'elementor-iconbox-item-wrapper');
            // Carousel
            $this->get_data_elementor_columns();
            // Item
            $this->add_render_attribute('item', 'class', 'elementor-iconbox-item' . ' ' . $path_wrap_active . ' ' . $path_border_active);
            $count = 0;
            ?>
            <div <?php $this->print_render_attribute_string('wrapper'); // WPCS: XSS ok. ?>>
                <div <?php $this->print_render_attribute_string('row'); // WPCS: XSS ok. ?>>
                    <?php foreach ($settings['iconboxs'] as $index => $iconbox): $count++; ?>
                        <?php
                        $class_item = 'elementor-repeater-item-' . $iconbox['_id'];
                        $tab_title_setting_key = $this->get_repeater_setting_key('item', 'items', $index);
                        $this->add_render_attribute($tab_title_setting_key, ['class' => ['iconbox-wrapper', $class_item],]); ?>

                        <div <?php $this->print_render_attribute_string('item'); // WPCS: XSS ok. ?>
                                data-count="<?php printf('%02d', $count); ?>">
                            <div <?php $this->print_render_attribute_string($tab_title_setting_key); ?>>
                                <div class="iconbox-icon">
                                    <?php Icons_Manager::render_icon($iconbox['iconbox_icon'], ['aria-hidden' => 'true']); ?>
                                </div>

                                <div class="iconbox-content">
                                    <?php if (!empty($iconbox['iconbox_subtitle'])) { ?>
                                        <div class="iconbox-subtitle"><?php echo sprintf('%s', $iconbox['iconbox_subtitle']); ?></div>
                                    <?php } ?>

                                    <?php $iconbox_title = $iconbox['iconbox_title'];
                                        if (!empty($iconbox['iconbox_link']['url'])) {
                                            $iconbox_title = '<a href="' . esc_url($iconbox['iconbox_link']['url']) . '">' . esc_html($iconbox_title) . '</a>';
                                        }
                                        printf('<h5 class="iconbox-title">%s</h5>', $iconbox_title);
                                    ?>

                                    <?php if (!empty($iconbox['iconbox_desc'])) { ?>
                                        <div class="iconbox-desc"><?php echo sprintf('%s', $iconbox['iconbox_desc']); ?></div>
                                    <?php } ?>
                                    <?php $iconbox_button = $iconbox['iconbox_button'];
                                    if (!empty($iconbox['iconbox_link']['url'])) {
                                        $iconbox_button = '<a class="elementor-button" href="' . esc_url($iconbox['iconbox_link']['url']) . '">' . '<span class="elementor-button-content-wrapper"><span class="elementor-button-text">' . esc_html($iconbox_button) . '</span>' . '<span class="elementor-button-icon"><i class="hitboox-icon-arrow-right"></i></span></span>' . ' </a>';
                                        printf('<div class="iconbox-button">%s</div>', $iconbox_button);
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php $this->render_swiper_pagination_navigation(); ?>
            <?php
        }
    }

}

$widgets_manager->register(new Hitboox_Elementor_IconBoxs());

