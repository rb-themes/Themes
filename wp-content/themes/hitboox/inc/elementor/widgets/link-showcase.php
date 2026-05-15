<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

/**
 * Elementor tabs widget.
 *
 * Elementor widget that displays vertical or horizontal tabs with different
 * pieces of content.
 *
 * @since 1.0.0
 */
class Hitboox_Elementor_Link_Showcase extends Widget_Base {

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
        return 'hitboox-link-showcase';
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
        return esc_html__('Hitboox Link Showcase', 'hitboox');
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
        return 'eicon-tabs';
    }

    /**
     * Get widget keywords.
     *
     * Retrieve the list of keywords the widget belongs to.
     *
     * @return array Widget keywords.
     * @since 2.1.0
     * @access public
     *
     */
    public function get_keywords() {
        return ['tabs', 'accordion', 'toggle', 'link', 'showcase'];
    }

    public function get_script_depends() {
        return ['hitboox-elementor-link-showcase'];
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
            'section_items',
            [
                'label' => esc_html__('Items', 'hitboox'),
            ]
        );

        $repeater = new Repeater();
        $repeater->add_control(
            'sub_title',
            [
                'label'       => esc_html__('Sub Title', 'hitboox'),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => esc_html__('Sub title', 'hitboox'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'title',
            [
                'label'       => esc_html__('Title', 'hitboox'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Title', 'hitboox'),
                'placeholder' => esc_html__('Title', 'hitboox'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'link_content',
            [
                'label'       => esc_html__('Content', 'hitboox'),
                'placeholder' => esc_html__('Content', 'hitboox'),
                'type'        => Controls_Manager::TEXTAREA,
                'show_label'  => false,
            ]
        );

        $repeater->add_control(
            'link_image',
            [
                'label'   => esc_html__('Choose Image', 'hitboox'),
                'type'    => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
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

        $this->add_control(
            'items',
            [
                'label'       => esc_html__('Items', 'hitboox'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'title'    => esc_html__('Title #1', 'hitboox'),
                        'subtitle' => esc_html__('Subtitle #1', 'hitboox'),
                        'link'     => esc_html__('#', 'hitboox'),
                    ],
                    [
                        'title'    => esc_html__('Title #2', 'hitboox'),
                        'subtitle' => esc_html__('Subtitle #2', 'hitboox'),
                        'link'     => esc_html__('#', 'hitboox'),
                    ],
                    [
                        'title'    => esc_html__('Title #3', 'hitboox'),
                        'subtitle' => esc_html__('Subtitle #3', 'hitboox'),
                        'link'     => esc_html__('#', 'hitboox'),
                    ],
                ],
                'title_field' => '{{{ title }}}',
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'      => 'link_image',
                'default'   => 'full',
                'separator' => 'none',
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


        $this->add_control(
            'title_position',
            [
                'label' => esc_html__('Position', 'hitboox'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Top', 'hitboox'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => esc_html__('Middle', 'hitboox'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Bottom', 'hitboox'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-link-showcase-title' => 'justify-content: {{VALUE}}',
                ],
                'separator' => 'none',
            ]
        );


        $this->add_responsive_control(
            'title_width', [
            'label' => esc_html__( 'Width', 'hitboox' ),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                '%' => [
                    'min' => 10,
                    'max' => 90,
                ],
                'px' => [
                    'min' => 20,
                    'max' => 800,
                ],
            ],
            'default' => [
                'unit' => '%',
            ],
            'size_units' => [ '%', 'px' ],
            'selectors' => [
                '{{WRAPPER}} .link-showcase-title-wrapper' => 'flex-basis: {{SIZE}}{{UNIT}}',
            ],
        ] );

        $this->add_responsive_control(
            'title_spacing', [
            'label' => esc_html__( 'Distance from image', 'hitboox' ),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 400,
                ],
            ],
            'size_units' => [ 'px' ],
            'selectors' => [
                '{{WRAPPER}} .elementor-link-showcase-inner' => 'gap: {{SIZE}}{{UNIT}}',
            ],
        ] );

        $this->add_control(
            'style_text_subtitle',
            [
                'type' => Controls_Manager::HEADING,
                'label' => esc_html__( 'Subtitle', 'hitboox' ),
                'separator'  => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'subtitle_typography',
                'global'   => [
                    'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
                ],
                'selector' => '{{WRAPPER}} .elementor-link-showcase-title .link-subtitle',
            ]
        );

        $this->add_control(
            'style_text_title',
            [
                'type' => Controls_Manager::HEADING,
                'label' => esc_html__( 'Title', 'hitboox' ),
                'separator'  => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'typography',
                'global'   => [
                    'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
                ],
                'selector' => '{{WRAPPER}} .elementor-link-showcase-title .link-title',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Stroke::get_type(),
            [
                'name'     => 'text_stroke',
                'selector' => '{{WRAPPER}} .elementor-link-showcase-title .link-title',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'     => 'text_shadow',
                'selector' => '{{WRAPPER}} .elementor-link-showcase-title .link-title',
            ]
        );

        $this->add_control(
            'style_text_content',
            [
                'type' => Controls_Manager::HEADING,
                'label' => esc_html__( 'Content', 'hitboox' ),
                'separator'  => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'content_typography',
                'global'   => [
                    'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
                ],
                'selector' => '{{WRAPPER}} .elementor-link-showcase-title .link-content',
            ]
        );

        $this->add_responsive_control(
            'text_padding',
            [
                'label'      => esc_html__('Padding', 'hitboox'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .link-showcase-title-wrapper .elementor-link-showcase-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator'  => 'before',
            ]
        );

        $this->start_controls_tabs( 'color_tabs' );

        $this->start_controls_tab( 'colors_normal',
            [
                'label' => esc_html__( 'Normal', 'hitboox' ),
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label'     => esc_html__('Sub Title', 'hitboox'),
                'type'      => Controls_Manager::COLOR,

                'selectors' => [
                    '{{WRAPPER}} .elementor-link-showcase-title .link-subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => esc_html__('Title', 'hitboox'),
                'type'      => Controls_Manager::COLOR,

                'selectors' => [
                    '{{WRAPPER}} .elementor-link-showcase-title .link-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label'     => esc_html__('Content', 'hitboox'),
                'type'      => Controls_Manager::COLOR,

                'selectors' => [
                    '{{WRAPPER}} .elementor-link-showcase-title .link-content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'border_color',
            [
                'label' => esc_html__( 'Border',  'hitboox' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-link-showcase-title' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'colors_active',
            [
                'label' => esc_html__( 'Active', 'hitboox' ),
            ]
        );

        $this->add_control(
            'subtitle_color_active',
            [
                'label'     => esc_html__('Sub Title', 'hitboox'),
                'type'      => Controls_Manager::COLOR,

                'selectors' => [
                    '{{WRAPPER}} .elementor-link-showcase-title.elementor-active .link-subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_color_active',
            [
                'label'     => esc_html__('Title', 'hitboox'),
                'type'      => Controls_Manager::COLOR,

                'selectors' => [
                    '{{WRAPPER}} .elementor-link-showcase-title.elementor-active .link-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'content_color_active',
            [
                'label'     => esc_html__('Content', 'hitboox'),
                'type'      => Controls_Manager::COLOR,

                'selectors' => [
                    '{{WRAPPER}} .elementor-link-showcase-title.elementor-active .link-content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'border_color_active',
            [
                'label' => esc_html__( 'Border',  'hitboox' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-link-showcase-title:after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_image_style',
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
                    '{{WRAPPER}} .link-showcase-contnet-wrapper img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_padding',
            [
                'label'      => esc_html__('Padding', 'hitboox'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .link-showcase-contnet-wrapper img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_border-radius',
            [
                'label'      => esc_html__('Border radius', 'hitboox'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .link-showcase-contnet-wrapper img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'image_background',
            [
                'label'     => esc_html__('Background', 'hitboox'),
                'type'      => Controls_Manager::COLOR,

                'selectors' => [
                    '{{WRAPPER}} .link-showcase-contnet-inner img' => 'background-color: {{VALUE}};',
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
        if (!empty($settings['items']) && is_array($settings['items'])) {
            $items = $settings['items'];
            // Row
            $this->add_render_attribute('wrapper', 'class', 'elementor-link-showcase-wrapper');
            $this->add_render_attribute('row', 'class', 'elementor-link-showcase-inner');
            $this->add_render_attribute('row', 'role', 'tablist');
            $id_int = substr($this->get_id_int(), 0, 3);
            ?>
            <div <?php $this->print_render_attribute_string('wrapper'); ?>>
                <div <?php $this->print_render_attribute_string('row'); ?>>
                    <div class="link-showcase-item link-showcase-title-wrapper">
                        <div class="link-showcase-title-inner">
                            <?php foreach ($items as $index => $item) :
                                $count = $index + 1;
                                $item_title_setting_key = $this->get_repeater_setting_key('item_title', 'items', $index);
                                $this->add_render_attribute($item_title_setting_key, [
                                    'id'            => 'elementor-link-showcase-title-' . $id_int . $count,
                                    'class'         => [
                                        'elementor-link-showcase-title',
                                        ($index == 0) ? 'elementor-active' : '',
                                        'elementor-repeater-item-' . $item['_id']
                                    ],
                                    'data-tab'      => $count,
                                    'role'          => 'tab',
                                    'aria-controls' => 'elementor-link-showcase-content-' . $id_int . $count,
                                ]);

                                $title = $item['title'];
                                if (!empty($item['link']['url'])) {
                                    $title = '<a href="' . esc_url($item['link']['url']) . '">' . $title . '</a>';
                                }
                                ?>
                                <div <?php $this->print_render_attribute_string($item_title_setting_key); ?>>
                                    <?php if (!empty($item['sub_title'])) { ?>
                                        <div class="link-subtitle"><?php echo sprintf('%s', $item['sub_title']); ?></div>
                                    <?php } ?>

                                    <h6 class="link-title"><?php echo wp_kses_post($title); ?></h6>

                                    <?php if (!empty($item['link_content'])) { ?>
                                        <div class="link-content"><?php echo sprintf('%s', $item['link_content']); ?></div>
                                    <?php } ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="link-showcase-item link-showcase-contnet-wrapper">
                        <div class="link-showcase-contnet-inner">
                            <?php foreach ($items as $index => $item) :
                                $count = $index + 1;
                                $item_content_setting_key = $this->get_repeater_setting_key('item_content', 'items', $index);
                                $this->add_render_attribute($item_content_setting_key, [
                                    'id'            => 'elementor-link-showcase-content-' . $id_int . $count,
                                    'class'         => [
                                        'elementor-link-showcase-content',
                                        'elementor-repeater-item-' . $item['_id'],
                                        ($index == 0) ? 'elementor-active' : '',
                                    ],
                                    'data-tab'      => $count,
                                    'role'          => 'tab',
                                    'aria-controls' => 'elementor-link-showcase-title-' . $id_int . $count,
                                ]);
                                ?>
                                <div <?php $this->print_render_attribute_string($item_content_setting_key); ?>>
                                        <?php $this->render_image($settings, $item); ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    }

    private function render_image($settings, $item) {
        if (!empty($item['link_image']['url'])) :
            ?>
            <?php
            $item['link_image_size']             = $settings['link_image_size'];
            $item['link_image_custom_dimension'] = $settings['link_image_custom_dimension'];
            echo Group_Control_Image_Size::get_attachment_image_html($item, 'link_image');
            ?>
        <?php
        endif;
    }

}

$widgets_manager->register(new Hitboox_Elementor_Link_Showcase());
