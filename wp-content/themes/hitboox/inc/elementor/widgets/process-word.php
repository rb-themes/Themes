<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Repeater;

class Hitboox_Elementor_Process_Word extends Hitboox_Base_Widgets_Swiper
{


    public function get_name()
    {
        return 'hitboox-process-word';
    }


    public function get_title()
    {
        return esc_html__('Hiitboox Process Word', 'hitboox');
    }


    public function get_icon()
    {
        return 'eicon-carousel-loop';
    }

    public function get_script_depends()
    {
        return ['hitboox-elementor-process-word', 'hitboox-elementor-swiper'];
    }

    public function get_categories()
    {
        return array('hitboox-addons');
    }


    protected function register_controls()
    {
        $this->start_controls_section(
            'section_process',
            [
                'label' => esc_html__('Process', 'hitboox'),
            ]
        );
        $repeater = new Repeater();


        $repeater->add_control(
            'processword_image',
            [
                'label' => esc_html__('Choose Image', 'hitboox'),
                'default' => [
                    'url' => Elementor\Utils::get_placeholder_image_src(),
                ],
                'type' => Controls_Manager::MEDIA,
                'show_label' => false,
            ]
        );

        $repeater->add_control(
            'process_number',
            [
                'label' => esc_html__('Number', 'hitboox'),
                'default' => '01',
                'type' => Controls_Manager::TEXT,
            ]
        );

        $repeater->add_control(
            'process_title',
            [
                'label' => esc_html__('Title', 'hitboox'),
                'default' => 'Title',
                'type' => Controls_Manager::TEXT,
            ]
        );

        $repeater->add_control(
            'process_content',
            [
                'label' => esc_html__('Content', 'hitboox'),
                'type' => Controls_Manager::TEXTAREA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.',
                'rows' => '5',
            ]
        );

        $repeater->add_control(
            'processword_link',
            [
                'label' => esc_html__('Link to', 'hitboox'),
                'placeholder' => esc_html__('https://your-link.com', 'hitboox'),
                'type' => Controls_Manager::URL,
            ]
        );

        $this->add_control(
            'processword',
            [
                'label' => esc_html__('Items', 'hitboox'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ name }}}',
            ]
        );

        $this->add_group_control(
            Elementor\Group_Control_Image_Size::get_type(),
            [
                'name' => 'processword_image',
                'default' => 'full',
                'separator' => 'none',
            ]
        );

        $this->add_responsive_control(
            'column',
            [
                'label' => esc_html__('Columns', 'hitboox'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 1,
                'options' => [1 => 1, 2 => 2, 3 => 3, 4 => 4, 6 => 6],
                'selectors' => [
                    '{{WRAPPER}} .d-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr)',
                ],
                'condition' => ['enable_carousel!' => 'yes']
            ]
        );

        $this->add_responsive_control(
            'processword_gutter',
            [
                'label' => esc_html__('Gutter', 'hitboox'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .d-grid' => 'grid-gap:{{SIZE}}{{UNIT}}',
                ],
                'condition' => ['enable_carousel!' => 'yes']
            ]
        );

        $this->add_control(
            'scroll_sticky',
            [
                'label'     => esc_html__('Scroll Sticky', 'hitboox'),
                'type'      => Controls_Manager::SWITCHER,
                'prefix_class'    => 'process-scroll-sticky-',
                'condition' => ['enable_carousel!' => 'yes'],
            ]
        );

        $this->add_control(
            'enable_carousel',
            [
                'label' => esc_html__('Enable Carousel', 'hitboox'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['scroll_sticky!' => 'yes'],
            ]
        );

        $this->end_controls_section();

        // WRAPPER STYLE
        $this->start_controls_section(
            'section_style_process_wrapper',
            [
                'label' => esc_html__('Wrapper', 'hitboox'),
                'tab' => Controls_Manager::TAB_STYLE,

            ]
        );

        $this->add_responsive_control(
            'padding_process_wrapper',
            [
                'label' => esc_html__('Padding', 'hitboox'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .grid-item .process-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'margin_process_wrapper',
            [
                'label' => esc_html__('Margin', 'hitboox'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .grid-item .process-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'color_process_wrapper',
            [
                'label' => esc_html__('Background Color', 'hitboox'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .grid-item .process-content' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'wrapper_border',
                'placeholder' => '1px',
                'default' => '1px',
                'selector' => '{{WRAPPER}} .grid-item .process-content',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'wrapper_radius',
            [
                'label' => esc_html__('Border Radius', 'hitboox'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .grid-item .process-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Image style
        $this->start_controls_section(
            'section_style_process_image',
            [
                'label' => esc_html__('Image', 'hitboox'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );


        $this->add_responsive_control(
            'image_width',
            [
                'label' => esc_html__('Width', 'hitboox'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'size_units' => ['%', 'px', 'vw'],
                'range' => [
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-process-image img' => 'width: {{SIZE}}{{UNIT}};',
                ],
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
                        'max' => 500,
                    ],
                    'vh' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-process-image img' => 'height: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .elementor-process-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_padding',
            [
                'label' => esc_html__('Padding', 'hitboox'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-process-image img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .elementor-process-image img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'processword_style_content',
            [
                'label' => esc_html__('Content', 'hitboox'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'padding_content_wrapper',
            [
                'label' => esc_html__('Padding', 'hitboox'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-processword-item-wrapper .wrap_content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'heading_number',
            [
                'label' => esc_html__('Number', 'hitboox'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'number_color',
            [
                'label' => esc_html__('Color', 'hitboox'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .process-number' => 'color: {{VALUE}};',
                ],

            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'number_typography',
                'selector' => '{{WRAPPER}} .process-number',

            ]
        );

        $this->add_responsive_control(
            'number_spacing',
            [
                'label' => esc_html__('Number Spacing', 'hitboox'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .process-number' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'heading_title',
            [
                'label' => esc_html__('Title', 'hitboox'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Color', 'hitboox'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .process-title' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .process-title a' => 'color: {{VALUE}};',
                ],

            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .process-title',

            ]
        );

        $this->add_responsive_control(
            'title_spacing',
            [
                'label' => esc_html__('Title Spacing', 'hitboox'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .process-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'heading_content',
            [
                'label' => esc_html__('Content', 'hitboox'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label' => esc_html__('Color', 'hitboox'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .process-content' => 'color: {{VALUE}};',
                ],

            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'content_typography',
                'selector' => '{{WRAPPER}} .process-content',

            ]
        );

        $this->add_responsive_control(
            'content_spacing',
            [
                'label' => esc_html__('Content Spacing', 'hitboox'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .process-content' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->add_control_carousel(['enable_carousel' => 'yes']);

    }

    /**
     * Render processword widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since  1.0.0
     * @access protected
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        if (empty($settings['processword'])) {
            return;
        }

        $this->add_render_attribute('wrapper', 'class', 'elementor-processword-item-wrapper');
        $this->get_data_elementor_columns();
        // Item
        $this->add_render_attribute('item', 'class', 'elementor-processword-item');
        $this->add_render_attribute('details', 'class', 'processword-details');
        ?>

        <div <?php $this->print_render_attribute_string('wrapper'); ?>>
            <div <?php $this->print_render_attribute_string('row'); ?>>
                <?php foreach ($settings['processword'] as $processword): ?>
                    <div <?php $this->print_render_attribute_string('item'); ?>>
                        <div class="process-content">

                            <?php $this->render_image($settings, $processword); ?>

                            <div class="wrap_content">
                                <?php if ($processword['process_number']) { ?>
                                    <div class="process-number"><?php echo esc_html($processword['process_number']); ?></div>
                                <?php } ?>
                                <?php $processword_title_html = $processword['process_title'];
                                if (!empty($processword['processword_link']['url'])) {
                                    $processword_title_html = '<a href="' . esc_url($processword['processword_link']['url']) . '">' . esc_html($processword_title_html) . '</a>';
                                }
                                printf('<h3 class="process-title">%s</h3>', $processword_title_html);
                                ?>
                                <?php if ($processword['process_content']) { ?>
                                    <div class="content"><?php echo esc_html($processword['process_content']); ?></div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php $this->render_swiper_pagination_navigation(); ?>
        <?php
    }

    private function render_image($settings, $processword)
    {
        if (!empty($processword['processword_image']['url'])) :
            ?>
            <div class="elementor-process-image">
                <div class="decor-border"> </div>
                    <?php
                    $processword['processword_image_size'] = $settings['processword_image_size'];
                    $processword['processword_image_custom_dimension'] = $settings['processword_image_custom_dimension'];
                    echo Group_Control_Image_Size::get_attachment_image_html($processword, 'processword_image');
                    ?>
            </div>
        <?php
        endif;
    }

}

$widgets_manager->register(new Hitboox_Elementor_Process_Word());