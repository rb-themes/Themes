<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;

class Hitboox_Elementor_Llist_Item extends \Elementor\Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve listitem widget name.
     *
     * @return string Widget name.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_name() {
        return 'hitboox-listitem';
    }

    /**
     * Get widget title.
     *
     * Retrieve listitem widget title.
     *
     * @return string Widget title.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_title() {
        return esc_html__('Hitboox Llist Item', 'hitboox');
    }

    /**
     * Get widget icon.
     *
     * Retrieve listitem widget icon.
     *
     * @return string Widget icon.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_icon() {
        return 'eicon-bullet-list';
    }

    public function get_categories() {
        return array('hitboox-addons');
    }

    /**
     * Register listitem widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since  1.0.0
     * @access protected
     */
    protected function register_controls() {
        $this->start_controls_section(
            'section_list_item',
            [
                'label' => esc_html__('List Item', 'hitboox'),
            ]
        );

        $this->add_control(
            'list_view',
            [
                'label' => esc_html__( 'Layout', 'hitboox' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'traditional',
                'options' => [
                    'traditional' => [
                        'title' => esc_html__( 'Default', 'hitboox' ),
                        'icon' => 'eicon-editor-list-ul',
                    ],
                    'inline' => [
                        'title' => esc_html__( 'Inline', 'hitboox' ),
                        'icon' => 'eicon-ellipsis-h',
                    ],
                ],
                'prefix_class' => 'elementor-list-item-layout-',
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'listitem_title',
            [
                'label' => esc_html__( 'Text', 'hitboox' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'placeholder' => esc_html__( 'List Item', 'hitboox' ),
                'default' => esc_html__( 'List Item', 'hitboox' ),
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $repeater->add_control(
            'listitem_icon',
            [
                'label' => esc_html__( 'Icon', 'hitboox' ),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-check',
                    'library' => 'fa-solid',
                ],
                'fa4compatibility' => 'icon',
            ]
        );

        $repeater->add_control(
            'listitem_link',
            [
                'label' => esc_html__('Link to', 'hitboox'),
                'placeholder' => esc_html__('https://your-link.com', 'hitboox'),
                'type' => Controls_Manager::URL,
                'default' => [
                    'url' => '#',
                ],
            ]
        );

        $this->add_control(
            'listitem',
            [
                'label'       => esc_html__('Items', 'hitboox'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'title_field' => '{{{ listitem_title }}}',
            ]
        );

        $this->end_controls_section();

        // Item.
        $this->start_controls_section(
            'section_style_title',
            [
                'label' => esc_html__('Title', 'hitboox'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'style_hover',
            [
                'label'        => esc_html__('Hover Style', 'hitboox'),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => '',
                'selectors' => [
                    '{{WRAPPER}} .hover-text:before' => '-webkit-text-fill-color: transparent; -webkit-text-stroke: 1px;',
                ],
                'description'  => 'Hover text stroke',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .elementor-listitem-item .hover-text',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Color', 'hitboox'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-listitem-item .hover-text span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_color_hover',
            [
                'label' => esc_html__('Color Hover', 'hitboox'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-listitem-item .hover-text:before' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_spacing',
            [
                'label' => esc_html__('Space Between', 'hitboox'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}:not(.elementor-list-item-layout-inline) .elementor-listitem-item:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.elementor-list-item-layout-inline .elementor-listitem-item:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        //Icon
        $this->start_controls_section(
            'section_style_icon',
            [
                'label' => esc_html__('Icon', 'hitboox'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'icon_colors' );

        $this->start_controls_tab(
            'icon_colors_normal',
            [
                'label' => esc_html__( 'Normal', 'hitboox' ),
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => esc_html__( 'Color', 'hitboox' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-list-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-list-icon svg' => 'fill: {{VALUE}};',
                ],
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'icon_colors_hover',
            [
                'label' => esc_html__( 'Hover', 'hitboox' ),
            ]
        );

        $this->add_control(
            'icon_color_hover',
            [
                'label' => esc_html__( 'Color', 'hitboox' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-listitem-item a:hover .elementor-list-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-listitem-item a:hover .elementor-list-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'icon_size',
            [
                'label' => esc_html__( 'Size', 'hitboox' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'default' => [
                    'size' => 14,
                ],
                'range' => [
                    'px' => [
                        'min' => 6,
                    ],
                    '%' => [
                        'min' => 6,
                    ],
                    'vw' => [
                        'min' => 6,
                    ],
                ],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .elementor-list-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-list-icon svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_indent',
            [
                'label' => esc_html__( 'Gap', 'hitboox' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'separator' => 'after',
                'selectors' => [
                    '{{WRAPPER}} .elementor-list-icon' => is_rtl() ? 'padding-left: {{SIZE}}{{UNIT}};' : 'padding-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
    }

    /**
     * Render listitem widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since  1.0.0
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        if (empty($settings['listitem'])) {
            return;
        }
        $this->add_render_attribute('wrapper', 'class', 'elementor-listitem-item-wrapper');
        $this->add_render_attribute('item', 'class', 'elementor-listitem-items');
        ?>
        <div <?php $this->print_render_attribute_string('item'); ?>>
        <?php foreach ($settings['listitem'] as $listitem): ?>
            <div class="elementor-listitem-item">
                <a href="<?php echo esc_html($listitem['listitem_link']['url']); ?>">
                    <?php if (!empty($listitem['listitem_icon']['value'])) : ?>
                        <span class="elementor-list-icon">
                            <?php \Elementor\Icons_Manager::render_icon($listitem['listitem_icon'], ['aria-hidden' => 'true']); ?>
                        </span>
                    <?php endif; ?>
                    <span class="hover-text" data-name="<?php echo esc_html($listitem['listitem_title']); ?>"><span><?php echo esc_html($listitem['listitem_title']); ?></span></span>
                </a>
            </div>
        <?php endforeach; ?>
        </div>
        <?php
    }


}

$widgets_manager->register(new Hitboox_Elementor_Llist_Item());
