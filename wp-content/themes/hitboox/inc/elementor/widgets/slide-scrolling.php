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
 * Elementor tabs widget.
 *
 * Elementor widget that displays vertical or horizontal tabs with different
 * pieces of content.
 *
 * @since 1.0.0
 */
class Hitboox_Elementor_Slide_Scrolling extends Elementor\Widget_Base {

    public function get_categories() {
        return array('hitboox-addons');
    }

    /**
     * Get widget name.
     *
     * Retrieve tabs widget name.
     *
     * @return string Widget name.
     * @since 1.0.0
     * @access public
     *
     */
    public function get_name() {
        return 'hitboox-slide-scrolling';
    }

    /**
     * Get widget title.
     *
     * Retrieve tabs widget title.
     *
     * @return string Widget title.
     * @since 1.0.0
     * @access public
     *
     */
    public function get_title() {
        return esc_html__('Hitboox Slide Scrolling', 'hitboox');
    }

    /**
     * Get widget icon.
     *
     * Retrieve tabs widget icon.
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
     * Register tabs widget controls.
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
            'scrolling_title',
            [
                'label'       => esc_html__('Scrolling name', 'hitboox'),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => esc_html__('Scrolling Name', 'hitboox'),
                'label_block' => true,
            ]
        );

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
                'title_field' => '{{{ scrolling_title }}}',
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
                'prefix_class' => 'hitboox-scrolling-',
            ]
        );

        $this->add_control(
            'box_width',
            [
                'label'        => esc_html__('Box Width', 'hitboox'),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => '',
                'prefix_class' => 'box-width-'
            ]
        );

        $this->add_control(
            'style_hover',
            [
                'label'        => esc_html__('Hover Style', 'hitboox'),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => '',
                'prefix_class' => 'style-hover-'
            ]
        );

        $this->add_control(
            'hover_pause',
            [
                'label'        => esc_html__('Pause', 'hitboox'),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => '',
                'prefix_class' => 'hover-pause-'
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

        //Image
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

    }

    /**
     * Render tabs widget output on the frontend.
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

            ?>
            <div class="elementor-scrolling">
                <div <?php $this->print_render_attribute_string('wrapper'); ?>>
                    <?php
                    for ($i = 1; $i <= 3; $i++) {
                        ?>
                        <div class="elementor-scrolling-inner">
                            <?php foreach ($settings['scrolling'] as $item) : ?>
                                <div <?php $this->print_render_attribute_string('item'); ?>>
                                    <div class="elementor-scrolling-item-inner">

                                        <?php if ('image' === $item['graphic_element'] && !empty($item['scrolling_image']['url'])) : ?>
                                            <div class="elementor-image">
                                                <?php Group_Control_Image_Size::print_attachment_image_html($item, 'scrolling_image'); ?>
                                            </div>
                                        <?php elseif ('icon' === $item['graphic_element'] && (!empty($item['icon']) || !empty($item['selected_icon']))) : ?>
                                            <div class="elementor-icon">
                                                <?php Icons_Manager::render_icon($item['selected_icon'], ['aria-hidden' => 'true']); ?>
                                            </div>
                                        <?php endif; ?>

                                        <div class="scrolling-title elementor-repeater-item-<?php echo esc_attr($item['_id']); ?>">
                                            <?php if ($item['scrolling_title']) {
                                                if (!empty($item['link'])) {
                                                    if (!empty($item['link']['is_external'])) {
                                                        $this->add_render_attribute('scrolling-title', 'target', '_blank');
                                                    }

                                                    if (!empty($item['link']['nofollow'])) {
                                                        $this->add_render_attribute('scrolling-title', 'rel', 'nofollow');
                                                    }

                                                    echo '<a href="' . esc_url($item['link']['url'] ? $item['link']['url'] : '#') . '" ' . $this->get_render_attribute_string('scrolling-title') . ' title="' . esc_attr($item['scrolling_title']) . '">';
                                                }
                                                echo '<span>' . sprintf('%s', $item['scrolling_title']) . '</span>';
                                                if (!empty($item['link'])) {
                                                    echo '</a>';
                                                }
                                            }
                                            ?>
                                        </div>

                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <?php
        }
    }
}

$widgets_manager->register(new Hitboox_Elementor_Slide_Scrolling());