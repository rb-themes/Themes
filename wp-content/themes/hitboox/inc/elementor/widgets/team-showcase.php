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
class Hitboox_Elementor_Team_Showcase extends Widget_Base {

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
        return 'hitboox-team-showcase';
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
        return esc_html__('Hitboox Team Showcase', 'hitboox');
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
        return ['hitboox-elementor-team-showcase'];
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
            'teamshowcase_image',
            [
                'label'      => esc_html__('Choose Image', 'hitboox'),
                'default'    => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'type'       => Controls_Manager::MEDIA,
                'show_label' => false,
            ]
        );

        $repeater->add_control(
            'teamshowcase_name',
            [
                'label'   => esc_html__('Name', 'hitboox'),
                'default' => 'John Doe',
                'type'    => Controls_Manager::TEXT,
            ]
        );

        $repeater->add_control(
            'teamshowcase_job',
            [
                'label'   => esc_html__('Job', 'hitboox'),
                'default' => 'Designer',
                'type'    => Controls_Manager::TEXT,
            ]
        );


        $repeater->add_control(
            'facebook',
            [
                'label'       => esc_html__('Facebook', 'hitboox'),
                'type'        => Controls_Manager::URL,
                'label_block' => false,
            ]
        );

        $repeater->add_control(
            'instagram',
            [
                'label'       => esc_html__('Instagram', 'hitboox'),
                'type'        => Controls_Manager::URL,
                'label_block' => false,
            ]
        );

        $repeater->add_control(
            'twitter',
            [
                'label'       => esc_html__('Twitter', 'hitboox'),
                'type'        => Controls_Manager::URL,
                'label_block' => false,
            ]
        );

        $repeater->add_control(
            'youtube',
            [
                'label'       => esc_html__('Youtube', 'hitboox'),
                'type'        => Controls_Manager::URL,
                'label_block' => false,
            ]
        );

        $repeater->add_control(
            'pinterest',
            [
                'label'       => esc_html__('Pinterest', 'hitboox'),
                'type'        => Controls_Manager::URL,
                'label_block' => false,
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
                'name'      => 'teamshowcase_image',
                'default'   => 'full',
                'separator' => 'none',
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            'section_wrap_style',
            [
                'label' => esc_html__('Wrapper', 'hitboox'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'min-height',
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
                    '{{WRAPPER}} .team-showcase-item' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            'section_content_style',
            [
                'label' => esc_html__('Content', 'hitboox'),
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
                        'title' => esc_html__('start', 'hitboox'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => esc_html__('center', 'hitboox'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('end', 'hitboox'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-team-showcase-inner' => 'align-items: {{VALUE}}',
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
                '{{WRAPPER}} .team-showcase-title-wrapper' => 'flex-basis: {{SIZE}}{{UNIT}}',
            ],
        ] );


        $this->add_responsive_control( 'title_space_between', [
            'label' => esc_html__( 'Gap between title', 'hitboox' ),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 400,
                ],
            ],
            'size_units' => [ 'px' ],
            'selectors' => [
                '{{WRAPPER}} .team-showcase-title-inner' => 'display: flex; flex-direction: column; gap: {{SIZE}}{{UNIT}}',
            ],
        ] );

        $this->add_responsive_control(
            'title_spacing', [
            'label' => esc_html__( 'Distance from content', 'hitboox' ),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 400,
                ],
            ],
            'size_units' => [ 'px' ],
            'selectors' => [
                '{{WRAPPER}} .elementor-team-showcase-inner' => 'gap: {{SIZE}}{{UNIT}}',
            ],
        ] );

        $this->add_control(
            'border_type',
            [
                'label' => esc_html__( 'Border Type',  'hitboox' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__( 'Default', 'hitboox' ),
                    'none' => esc_html__( 'None', 'hitboox' ),
                    'solid' => esc_html__( 'Solid', 'hitboox' ),
                    'double' => esc_html__( 'Double', 'hitboox' ),
                    'dotted' => esc_html__( 'Dotted',  'hitboox' ),
                    'dashed' => esc_html__( 'Dashed',  'hitboox' ),
                    'groove' => esc_html__( 'Groove',  'hitboox' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-team-showcase-title' => 'border-style: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'border_width',
            [
                'label' => esc_html__( 'Width', 'hitboox' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', 'rem', 'vw', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-team-showcase-title' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'border_type!' => [ '', 'none' ],
                ],
            ]
        );

        $this->add_control(
            'border_color',
            [
                'label' => esc_html__( 'Color',  'hitboox' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-team-showcase-title, {{WRAPPER}} .elementor-team-showcase-title .link-title' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'border_type!' => [ '', 'none' ],
                ],
            ]
        );

        $this->add_responsive_control(
            'text_padding',
            [
                'label'      => esc_html__('Padding', 'hitboox'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .elementor-team-showcase-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator'  => 'before',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_name',
            [
                'label' => esc_html__('Name', 'hitboox'),
                'tab' => Controls_Manager::TAB_STYLE,

            ]
        );
        $this->add_control(
            'name_color',
            [
                'label'     => esc_html__('Color', 'hitboox'),
                'type'      => Controls_Manager::COLOR,

                'selectors' => [
                    '{{WRAPPER}} .elementor-team-showcase-title .team-name' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'typography_name',
                'global'   => [
                    'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
                ],
                'selector' => '{{WRAPPER}} .elementor-team-showcase-title .team-name',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_job',
            [
                'label' => esc_html__('Job', 'hitboox'),
                'tab' => Controls_Manager::TAB_STYLE,

            ]
        );
        $this->add_control(
            'job_color',
            [
                'label'     => esc_html__('Color', 'hitboox'),
                'type'      => Controls_Manager::COLOR,

                'selectors' => [
                    '{{WRAPPER}} .elementor-team-showcase-title .team-job' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'typography_job',
                'global'   => [
                    'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
                ],
                'selector' => '{{WRAPPER}} .elementor-team-showcase-title .team-job',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_image_style',
            [
                'label' => esc_html__('Image', 'hitboox'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image-height',
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
                    '{{WRAPPER}} .team-showcase-thumbnail' => 'height: {{SIZE}}{{UNIT}};padding-top:0',
                ],
            ]
        );

        $this->add_control(
            'image_background',
            [
                'label'     => esc_html__('Background', 'hitboox'),
                'type'      => Controls_Manager::COLOR,

                'selectors' => [
                    '{{WRAPPER}} .team-showcase-thumbnail:before' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => esc_html__('Border Radius', 'hitboox'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .team-showcase-thumbnail' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_social',
            [
                'label' => esc_html__('Social', 'hitboox'),
                'tab' => Controls_Manager::TAB_STYLE,

            ]
        );

        $this->add_responsive_control(
            'social_size',
            [
                'label' => esc_html__('Font Size', 'hitboox'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .team-icon-socials a' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'social_width',
            [
                'label' => esc_html__('Width', 'hitboox'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .team-icon-socials a' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'social_tabs' );

        $this->start_controls_tab( 'social_normal',
            [
                'label' => esc_html__( 'Normal', 'hitboox' ),
            ]
        );

        $this->add_control(
            'social_bg',
            [
                'label' => esc_html__( 'Background', 'hitboox' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .team-icon-socials a' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'social_color',
            [
                'label' => esc_html__( 'Color', 'hitboox' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .team-icon-socials a' => 'color: {{VALUE}}',
                ]
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab('social_hover',
            [
                'label' => esc_html__( 'Hover', 'hitboox' ),
            ]
        );

        $this->add_control(
            'social_bg_hover',
            [
                'label' => esc_html__( 'Background', 'hitboox' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .team-icon-socials a:hover' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'social_color_hover',
            [
                'label' => esc_html__( 'Color', 'hitboox' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .team-icon-socials a:hover' => 'color: {{VALUE}}',
                ]
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

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
            $this->add_render_attribute('wrapper', 'class', 'elementor-team-showcase-wrapper');
            $this->add_render_attribute('row', 'class', 'elementor-team-showcase-inner');
            $this->add_render_attribute('row', 'role', 'tablist');
            $id_int = substr($this->get_id_int(), 0, 3);
            ?>
            <div <?php $this->print_render_attribute_string('wrapper'); ?>>
                <div <?php $this->print_render_attribute_string('row'); ?>>
                    <div class="team-showcase-item team-showcase-title-wrapper">
                        <div class="team-showcase-title-inner">
                            <?php foreach ($items as $index => $item) :
                                $count = $index + 1;
                                $item_title_setting_key = $this->get_repeater_setting_key('item_title', 'items', $index);
                                $this->add_render_attribute($item_title_setting_key, [
                                    'id'            => 'elementor-team-showcase-title-' . $id_int . $count,
                                    'class'         => [
                                        'elementor-team-showcase-title',
                                        ($index == 0) ? 'elementor-active' : '',
                                        'elementor-repeater-item-' . $item['_id']
                                    ],
                                    'data-tab'      => $count,
                                    'role'          => 'tab',
                                    'aria-controls' => 'elementor-team-showcase-content-' . $id_int . $count,
                                ]);

                                ?>
                                <div <?php $this->print_render_attribute_string($item_title_setting_key); ?>>
                                    <?php $this->render_image($settings, $item); ?>
                                    <div class="team-content">
                                        <div class="team-content-header">
                                            <?php if ($item['teamshowcase_job']) { ?>
                                                <div class="team-job"><?php echo esc_html($item['teamshowcase_job']); ?></div>
                                            <?php } ?>
                                            <?php
                                            echo '<h4 class="team-name">' . esc_html($item['teamshowcase_name']) . '</h4>';
                                            ?>
                                        </div>
                                        <div class="team-icon-socials">
                                            <?php foreach ($this->get_socials() as $key => $social): ?>
                                                <?php if (!empty($item[$key]['url'])) : ?>
                                                    <a href="<?php echo esc_url($item[$key]['url']) ?>" class="<?php echo esc_attr($social); ?>">
                                                        <i class="hitboox-icon-<?php echo esc_attr($social); ?>"></i>
                                                    </a>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="team-showcase-item team-showcase-content-wrapper">
                        <div class="team-showcase-content-inner">
                            <?php foreach ($items as $index => $item) :
                                $count = $index + 1;
                                $item_content_setting_key = $this->get_repeater_setting_key('item_content', 'items', $index);
                                $this->add_render_attribute($item_content_setting_key, [
                                    'id'            => 'elementor-team-showcase-content-' . $id_int . $count,
                                    'class'         => [
                                        'elementor-team-showcase-content',
                                        'elementor-repeater-item-' . $item['_id'],
                                        ($index == 0) ? 'elementor-active' : '',
                                    ],
                                    'data-tab'      => $count,
                                    'role'          => 'tab',
                                    'aria-controls' => 'elementor-team-showcase-title-' . $id_int . $count,
                                ]);
                                ?>
                                <div <?php $this->print_render_attribute_string($item_content_setting_key); ?>>
                                    <div class="team-showcase-thumbnail">
                                        <?php $this->render_image($settings, $item); ?>
                                    </div>
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
        if (!empty($item['teamshowcase_image']['url'])) :
            ?>
            <?php
            $item['teamshowcase_image_size']             = $settings['teamshowcase_image_size'];
            $item['teamshowcase_image_custom_dimension'] = $settings['teamshowcase_image_custom_dimension'];
            echo Group_Control_Image_Size::get_attachment_image_html($item, 'teamshowcase_image');
            ?>
        <?php
        endif;
    }
    private function get_socials() {
        return array(
            'facebook' => 'facebook-f',
            'instagram' => 'instagram',
            'twitter' => 'twitter-x',
            'youtube'  => 'youtube',
            'pinterest' => 'pinterest-p',
        );
    }

}

$widgets_manager->register(new Hitboox_Elementor_Team_Showcase());
