<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Typography;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Image_Size;

/**
 * Elementor custom slide scrolling widget.
 *
 * Elementor widget that displays vertical or horizontal tabs with different
 * pieces of content - Custom version with enhanced controls.
 *
 * @since 1.0.0
 */
if (!class_exists('Hitboox_Elementor_Slide_Scrolling_Custom') && class_exists('Elementor\Widget_Base')) {
    class Hitboox_Elementor_Slide_Scrolling_Custom extends Elementor\Widget_Base {

    public function get_categories() {
        // Use hitboox-addons if it exists, otherwise fall back to general
        $categories = array('hitboox-addons', 'general');
        return $categories;
    }

    /**
     * Get widget name.
     *
     * Retrieve custom slide scrolling widget name.
     *
     * @return string Widget name.
     * @since 1.0.0
     * @access public
     *
     */
    public function get_name() {
        return 'hitboox-slide-scrolling-custom';
    }

    /**
     * Get widget title.
     *
     * Retrieve custom slide scrolling widget title.
     *
     * @return string Widget title.
     * @since 1.0.0
     * @access public
     *
     */
    public function get_title() {
        return esc_html__('Hitboox Slide Scrolling Custom', 'hitboox');
    }

    /**
     * Get widget icon.
     *
     * Retrieve custom slide scrolling widget icon.
     *
     * @return string Widget icon.
     * @since 1.0.0
     * @access public
     *
     */
    public function get_icon() {
        return 'eicon-image';
    }

    /**
     * Register widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_controls() {

        $this->start_controls_section(
            'section_scrolling',
            [
                'label' => esc_html__('Items', 'hitboox'),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'graphic_element',
            [
                'label'   => esc_html__('Graphic Element', 'hitboox'),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [
                    'none'  => [
                        'title' => esc_html__('None', 'hitboox'),
                        'icon'  => 'eicon-ban',
                    ],
                    'image' => [
                        'title' => esc_html__('Image', 'hitboox'),
                        'icon'  => 'eicon-image-bold',
                    ],
                    'icon'  => [
                        'title' => esc_html__('Icon', 'hitboox'),
                        'icon'  => 'eicon-star',
                    ],
                ],
                'default' => 'none',
            ]
        );

        $repeater->add_control(
            'scrolling_image',
            [
                'label'      => esc_html__('Choose Image', 'hitboox'),
                'type'       => Controls_Manager::MEDIA,
                'dynamic'    => [
                    'active' => true,
                ],
                'default'    => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition'  => [
                    'graphic_element' => 'image',
                ],
                'show_label' => false,
            ]
        );
        $repeater->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'      => 'graphic_image', // Actually its `image_size`
                'default'   => 'thumbnail',
                'condition' => [
                    'graphic_element'    => 'image',
                    'graphic_image[id]!' => '',
                ],
            ]
        );
        $repeater->add_control(
            'selected_icon',
            [
                'label'            => esc_html__('Icon', 'hitboox'),
                'type'             => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'default'          => [
                    'value'   => 'fas fa-star',
                    'library' => 'fa-solid',
                ],
                'condition'        => [
                    'graphic_element' => 'icon',
                ],
            ]
        );
        $repeater->add_control(
            'link',
            [
                'label'       => esc_html__('Link to', 'hitboox'),
                'type'        => Controls_Manager::URL,
                'dynamic'     => [
                    'active' => true,
                ],
                'placeholder' => esc_html__('https://your-link.com', 'hitboox'),
            ]
        );

        $repeater->add_control(
            'text_stroke',
            [
                'label'     => esc_html__('Text Stroke', 'hitboox'),
                'type'      => Controls_Manager::SWITCHER,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.scrolling-title a' => '-webkit-text-fill-color: transparent;',
                ],

            ]
        );

        $repeater->add_responsive_control(
            'item_width',
            [
                'label'      => esc_html__('Width Stroke', 'hitboox'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min' => 1,
                        'max' => 50,
                    ],
                ],
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.scrolling-title a' => '-webkit-text-stroke-width: {{SIZE}}{{UNIT}}',
                ],
                'condition'  => [
                    'text_stroke' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'item_color_stroke',
            [
                'label'     => esc_html__('Color Stroke', 'hitboox'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.scrolling-title a' => '-webkit-text-stroke-color: {{VALUE}}',
                ],
                'condition' => [
                    'text_stroke' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'scrolling',
            [
                'label'       => esc_html__('Items', 'hitboox'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'title_field' => 'Item {{{ _id }}}',
            ]
        );


        $this->add_control(
            'heading_settings',
            [
                'label'     => esc_html__('Settings', 'hitboox'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'duration',
            [
                'label'     => esc_html__('Scrolling duration', 'hitboox'),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 30,
                'selectors' => [
                    '{{WRAPPER}} .elementor-scrolling-inner' => 'animation-duration: {{VALUE}}s',
                ],
            ]
        );

        $this->add_responsive_control(
            'scrolling_align',
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
                    '{{WRAPPER}} .elementor-scrolling-wrapper .elementor-scrolling-item-inner' => 'align-items: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'scrolling_vertical',
            [
                'label'        => esc_html__('Vertical Position', 'hitboox'),
                'type'         => Controls_Manager::CHOOSE,
                'options'      => [
                    'flex-start'=> [
                        'title' => esc_html__('Top', 'hitboox'),
                        'icon'  => 'eicon-v-align-top',
                    ],
                    'center'    => [
                        'title' => esc_html__('Middle', 'hitboox'),
                        'icon'  => 'eicon-v-align-middle',
                    ],
                    'flex-end'  => [
                        'title' => esc_html__('Bottom', 'hitboox'),
                        'icon'  => 'eicon-v-align-bottom',
                    ],
                ],
                'default'   => 'center',
                'selectors' => [
                    '{{WRAPPER}} .elementor-scrolling-inner' => 'align-items: {{VALUE}};',
                ],
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
                    '{{WRAPPER}} .elementor-scrolling-wrapper .elementor-scrolling-item' => 'margin-left: calc({{SIZE}}{{UNIT}}/2); margin-right: calc({{SIZE}}{{UNIT}}/2);',
                ],
            ]
        );

        $this->add_control(
            'rtl',
            [
                'label'        => esc_html__('Direction Right/Left', 'hitboox'),
                'type'         => Controls_Manager::SELECT,
                'default'      => 'ltr',
                'options'      => [
                    'ltr' => esc_html__('Left', 'hitboox'),
                    'rtl' => esc_html__('Right', 'hitboox'),
                ],
                'prefix_class' => 'hitboox-scrolling-custom-',
            ]
        );

        $this->add_control(
            'box_width',
            [
                'label'        => esc_html__('Box Width', 'hitboox'),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => '',
                'prefix_class' => 'box-width-custom-'
            ]
        );

        $this->add_control(
            'style_hover',
            [
                'label'        => esc_html__('Hover Style', 'hitboox'),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => '',
                'prefix_class' => 'style-hover-custom-'
            ]
        );

        $this->add_control(
            'hover_pause',
            [
                'label'        => esc_html__('Pause', 'hitboox'),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => '',
                'prefix_class' => 'hover-pause-custom-'
            ]
        );

        $this->end_controls_section();

        // Console Settings
        $this->start_controls_section(
            'section_console',
            [
                'label' => esc_html__('Console Settings', 'hitboox'),
            ]
        );

        $this->add_control(
            'console_svg',
            [
                'label'   => esc_html__('Console SVG', 'hitboox'),
                'type'    => Controls_Manager::MEDIA,
                'description' => esc_html__('Upload the handheld console SVG file', 'hitboox'),
            ]
        );

        $this->add_control(
            'console_color',
            [
                'label'     => esc_html__('Console Color', 'hitboox'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#87CEEB',
                'description' => esc_html__('Note: For exact color matching, use SVG with transparent fill or white fill. Color changes are applied via CSS filters.', 'hitboox'),
            ]
        );

        $this->add_control(
            'console_color_override',
            [
                'label'        => esc_html__('Use Inline SVG (Better Color Control)', 'hitboox'),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => '',
                'description'  => esc_html__('Enable this to load SVG inline for better color customization. Paste your SVG code below.', 'hitboox'),
            ]
        );

        $this->add_control(
            'console_svg_code',
            [
                'label'       => esc_html__('Console SVG Code', 'hitboox'),
                'type'        => Controls_Manager::CODE,
                'language'    => 'html',
                'rows'        => 10,
                'condition'   => [
                    'console_color_override' => 'yes',
                ],
                'description' => esc_html__('Paste your complete SVG code here (with or without <svg> tags). Color will be automatically applied.', 'hitboox'),
            ]
        );

        $this->add_control(
            'arrow_controls_svg',
            [
                'label'   => esc_html__('Arrow Controls SVG', 'hitboox'),
                'type'    => Controls_Manager::MEDIA,
                'description' => esc_html__('Upload the horizontal arrow controls SVG file', 'hitboox'),
            ]
        );

        $this->add_control(
            'content_area_heading',
            [
                'label'     => esc_html__('Content Area Position', 'hitboox'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'description' => esc_html__('Content area position is fixed relative to console. Use percentages to maintain proper scaling across resolutions.', 'hitboox'),
            ]
        );

        $this->add_responsive_control(
            'content_area_top',
            [
                'label'      => esc_html__('Top Position (%)', 'hitboox'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 0.1,
                    ],
                ],
                'size_units' => ['%'],
                'default'    => [
                    'size' => 15,
                    'unit' => '%',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .hitboox-console-content-area' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_area_left',
            [
                'label'      => esc_html__('Left Position (%)', 'hitboox'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 0.1,
                    ],
                ],
                'size_units' => ['%'],
                'default'    => [
                    'size' => 5,
                    'unit' => '%',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .hitboox-console-content-area' => 'left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_area_width',
            [
                'label'      => esc_html__('Width (%)', 'hitboox'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                        'step' => 0.1,
                    ],
                ],
                'size_units' => ['%'],
                'default'    => [
                    'size' => 90,
                    'unit' => '%',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .hitboox-console-content-area' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_area_height',
            [
                'label'      => esc_html__('Height (%)', 'hitboox'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                        'step' => 0.1,
                    ],
                ],
                'size_units' => ['%'],
                'default'    => [
                    'size' => 60,
                    'unit' => '%',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .hitboox-console-content-area' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_controls_heading',
            [
                'label'     => esc_html__('Arrow Controls Position', 'hitboox'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'description' => esc_html__('Arrow positions are fixed relative to console using percentages for proper scaling.', 'hitboox'),
            ]
        );

        $this->add_responsive_control(
            'arrow_left_top',
            [
                'label'      => esc_html__('Left Arrow - Top (%)', 'hitboox'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 0.1,
                    ],
                ],
                'size_units' => ['%'],
                'default'    => [
                    'size' => 70,
                    'unit' => '%',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .hitboox-arrow-controls .arrow-left' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrow_left_left',
            [
                'label'      => esc_html__('Left Arrow - Left (%)', 'hitboox'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 0.1,
                    ],
                ],
                'size_units' => ['%'],
                'default'    => [
                    'size' => 10,
                    'unit' => '%',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .hitboox-arrow-controls .arrow-left' => 'left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrow_right_top',
            [
                'label'      => esc_html__('Right Arrow - Top (%)', 'hitboox'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 0.1,
                    ],
                ],
                'size_units' => ['%'],
                'default'    => [
                    'size' => 70,
                    'unit' => '%',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .hitboox-arrow-controls .arrow-right' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrow_right_right',
            [
                'label'      => esc_html__('Right Arrow - Right (%)', 'hitboox'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 0.1,
                    ],
                ],
                'size_units' => ['%'],
                'default'    => [
                    'size' => 10,
                    'unit' => '%',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .hitboox-arrow-controls .arrow-right' => 'right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_scrolling_item',
            [
                'label' => esc_html__('Item', 'hitboox'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );


        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'scrolling_item',
                'selector' => '{{WRAPPER}} .elementor-scrolling-item-inner',
            ]
        );


        $this->add_control(
            'item_background_color',
            [
                'label'     => esc_html__('Background Color', 'hitboox'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-scrolling .elementor-scrolling-inner .elementor-scrolling-item-inner' => 'background-color: {{VALUE}}; ',
                ],
            ]
        );

        $this->add_responsive_control(
            'gap',
            [
                'label'      => esc_html__('Gap', 'hitboox'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .elementor-scrolling-item-inner' => 'gap:{{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_padding',
            [
                'label'      => esc_html__('Padding', 'hitboox'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .elementor-scrolling-item-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();

        //Image
        $this->start_controls_section(
            'section_style_scrolling_image',
            [
                'label' => esc_html__('Image', 'hitboox'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_height',
            [
                'label'      => esc_html__('Height', 'hitboox'),
                'type'       => Controls_Manager::SLIDER,
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
                'size_units' => ['px', 'vh'],
                'selectors'  => [
                    '{{WRAPPER}} img' => 'height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_width',
            [
                'label'      => esc_html__('Width', 'hitboox'),
                'type'       => Controls_Manager::SLIDER,
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
                'size_units' => ['px', 'vh'],
                'selectors'  => [
                    '{{WRAPPER}} img' => 'width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'image_radius',
            [
                'label'      => esc_html__('Border Radius', 'hitboox'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .elementor-scrolling-item-inner img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'image_opacity',
            [
                'label'     => esc_html__('Opacity', 'hitboox'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'max'  => 1,
                        'min'  => 0.1,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-scrolling-item-inner img' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_control(
            'image_opacity_hover',
            [
                'label'     => esc_html__('Opacity Hover', 'hitboox'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'max'  => 1,
                        'min'  => 0.1,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-scrolling-item-inner:hover img' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->end_controls_section();

        //Icon
        $this->start_controls_section(
            'section_style_scrolling_icon',
            [
                'label' => esc_html__('Icon', 'hitboox'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'icon_size',
            [
                'label'      => esc_html__('Size', 'hitboox'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem', 'custom'],
                'range'      => [
                    'px' => [
                        'min' => 6,
                        'max' => 100,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .elementor-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'description' => esc_html__( 'Apply to font', 'hitboox' ),
            ]
        );


        $this->add_responsive_control(
            'svg_height',
            [
                'label'      => esc_html__('Height', 'hitboox'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min' => 10,
                        'max' => 1000,
                    ],
                ],
                'size_units' => ['px',],
                'selectors'  => [
                    '{{WRAPPER}} .elementor-icon svg' => 'height: {{SIZE}}{{UNIT}}',
                ],
                'description' => esc_html__( 'Apply to svg', 'hitboox' ),
            ]
        );

        $this->add_responsive_control(
            'svg_width',
            [
                'label'      => esc_html__('Width', 'hitboox'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min' => 10,
                        'max' => 1000,
                    ],
                ],
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .elementor-icon svg' => 'width: {{SIZE}}{{UNIT}}',
                ],
                'description' => esc_html__( 'Apply to svg', 'hitboox' ),
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label'     => esc_html__('Color', 'hitboox'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon i'  => 'color: {{VALUE}}',
                    '{{WRAPPER}} .elementor-icon svg'  => 'fill: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'icon_color_hover',
            [
                'label'     => esc_html__('Color Hover', 'hitboox'),
                'type'      => Controls_Manager::COLOR,
                'global'    => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-scrolling-item:hover .elementor-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-scrolling-item:hover .elementor-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Title.
        $this->start_controls_section(
            'section_style_scrolling_title',
            [
                'label' => esc_html__('Title', 'hitboox'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'global'   => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .scrolling-title a',
            ]
        );

        $this->add_control(
            'title_text_color',
            [
                'label'     => esc_html__('Color', 'hitboox'),
                'type'      => Controls_Manager::COLOR,
                'global'    => [
                    'default' => Global_Colors::COLOR_ACCENT,
                ],
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .scrolling-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_text_color_hover',
            [
                'label'     => esc_html__('Color Hover', 'hitboox'),
                'type'      => Controls_Manager::COLOR,
                'global'    => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-scrolling-item:hover a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Console Styles
        $this->start_controls_section(
            'section_style_console',
            [
                'label' => esc_html__('Console', 'hitboox'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'console_max_width',
            [
                'label'      => esc_html__('Max Width', 'hitboox'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min' => 200,
                        'max' => 3000,
                    ],
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'size_units' => ['px', '%'],
                'default'    => [
                    'size' => 1400,
                    'unit' => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .hitboox-console-container' => 'max-width: {{SIZE}}{{UNIT}}; width: 100%;',
                ],
                'description' => esc_html__('Console maintains aspect ratio. Adjust max-width to scale proportionally.', 'hitboox'),
            ]
        );

        $this->add_responsive_control(
            'arrow_size',
            [
                'label'      => esc_html__('Arrow Controls Size (%)', 'hitboox'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    '%' => [
                        'min' => 1,
                        'max' => 20,
                        'step' => 0.1,
                    ],
                ],
                'size_units' => ['%'],
                'default'    => [
                    'size' => 12,
                    'unit' => '%',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .hitboox-arrow-controls .arrow-svg' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                ],
                'description' => esc_html__('Arrow size relative to console width for proportional scaling.', 'hitboox'),
            ]
        );

        $this->end_controls_section();

    }

    /**
     * Render widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        if (!empty($settings['scrolling']) && is_array($settings['scrolling'])) {

            $this->add_render_attribute('wrapper', 'class', 'elementor-scrolling-wrapper');
            $this->add_render_attribute('item', 'class', 'elementor-scrolling-item');
            $this->add_render_attribute('console-container', 'class', 'hitboox-console-container');

            ?>
            <style>
                .elementor-widget-hitboox-slide-scrolling-custom .hitboox-console-container {
                    position: relative;
                    margin: 0 auto;
                    width: 100%;
                    display: block;
                }
                .elementor-widget-hitboox-slide-scrolling-custom .hitboox-console-bg {
                    position: relative;
                    width: 100%;
                    z-index: 1;
                    display: block;
                    line-height: 0;
                }
                .elementor-widget-hitboox-slide-scrolling-custom .hitboox-console-bg .hitboox-console-svg {
                    display: block;
                    width: 100%;
                    height: auto;
                }
                .elementor-widget-hitboox-slide-scrolling-custom .hitboox-console-bg .hitboox-console-svg-inline {
                    display: block;
                    width: 100%;
                    height: auto;
                }
                .elementor-widget-hitboox-slide-scrolling-custom .hitboox-console-bg .hitboox-console-svg-inline svg {
                    display: block;
                    width: 100%;
                    height: auto;
                }
                .elementor-widget-hitboox-slide-scrolling-custom .hitboox-console-content-area {
                    position: absolute;
                    z-index: 2;
                    overflow: visible;
                    display: block;
                    box-sizing: border-box;
                }
                .elementor-widget-hitboox-slide-scrolling-custom .hitboox-arrow-controls {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    z-index: 10;
                    pointer-events: none;
                }
                .elementor-widget-hitboox-slide-scrolling-custom .hitboox-arrow-controls .arrow-left,
                .elementor-widget-hitboox-slide-scrolling-custom .hitboox-arrow-controls .arrow-right {
                    position: absolute;
                    pointer-events: auto;
                    display: block;
                }
                .elementor-widget-hitboox-slide-scrolling-custom .hitboox-arrow-controls .arrow-right {
                    transform: scaleX(-1);
                }
                .elementor-widget-hitboox-slide-scrolling-custom .hitboox-arrow-controls .arrow-svg {
                    display: block;
                    width: 100%;
                    height: auto;
                }
            </style>
            <div <?php $this->print_render_attribute_string('console-container'); ?>>
                <?php if (!empty($settings['console_svg']['url']) || !empty($settings['console_svg_code'])) : ?>
                    <div class="hitboox-console-bg">
                        <?php if (!empty($settings['console_color_override']) && 'yes' === $settings['console_color_override'] && !empty($settings['console_svg_code'])) : ?>
                            <?php 
                            $svg_code = $settings['console_svg_code'];
                            $console_color = !empty($settings['console_color']) ? esc_attr($settings['console_color']) : '#87CEEB';
                            // Replace fill colors in SVG
                            $svg_code = preg_replace('/fill=["\']([^"\']*)["\']/', 'fill="' . $console_color . '"', $svg_code);
                            $svg_code = preg_replace('/fill:\s*([^;]+);/', 'fill: ' . $console_color . ';', $svg_code);
                            // Ensure SVG has proper structure
                            if (strpos($svg_code, '<svg') === false) {
                                $svg_code = '<svg xmlns="http://www.w3.org/2000/svg">' . $svg_code . '</svg>';
                            }
                            // Allowed SVG tags and attributes
                            $allowed_svg_tags = array(
                                'svg' => array('xmlns' => array(), 'viewbox' => array(), 'width' => array(), 'height' => array(), 'fill' => array(), 'style' => array()),
                                'path' => array('d' => array(), 'fill' => array(), 'stroke' => array(), 'stroke-width' => array(), 'style' => array()),
                                'g' => array('fill' => array(), 'style' => array()),
                                'rect' => array('x' => array(), 'y' => array(), 'width' => array(), 'height' => array(), 'fill' => array(), 'rx' => array(), 'ry' => array()),
                                'circle' => array('cx' => array(), 'cy' => array(), 'r' => array(), 'fill' => array()),
                                'ellipse' => array('cx' => array(), 'cy' => array(), 'rx' => array(), 'ry' => array(), 'fill' => array())
                            );
                            echo '<div class="hitboox-console-svg-inline">' . wp_kses($svg_code, $allowed_svg_tags) . '</div>';
                            ?>
                        <?php else : ?>
                            <?php if (!empty($settings['console_svg']['url'])) : ?>
                                <img src="<?php echo esc_url($settings['console_svg']['url']); ?>" alt="Console" class="hitboox-console-svg" data-console-color="<?php echo esc_attr(!empty($settings['console_color']) ? $settings['console_color'] : '#87CEEB'); ?>" />
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($settings['arrow_controls_svg']['url'])) : ?>
                    <div class="hitboox-arrow-controls">
                        <div class="arrow-left">
                            <img src="<?php echo esc_url($settings['arrow_controls_svg']['url']); ?>" alt="Arrow Left" class="arrow-svg" />
                        </div>
                        <div class="arrow-right">
                            <img src="<?php echo esc_url($settings['arrow_controls_svg']['url']); ?>" alt="Arrow Right" class="arrow-svg" />
                        </div>
                    </div>
                <?php endif; ?>

                <div class="hitboox-console-content-area">
                    <!-- Content area for Elementor containers/widgets -->
                    <!-- Add your content here or via Elementor's nested container system -->
                </div>
            </div>
            <?php
        }
    }
} // End class
} // End if class_exists check

// Register the widget - make sure $widgets_manager is available in your plugin/theme
// $widgets_manager->register(new Hitboox_Elementor_Slide_Scrolling_Custom());
