<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Plugin;

class Hitboox_Elementor_ImageCarousel extends Hitboox_Base_Widgets_Swiper {
    /**
     * Get widget name.
     *
     * Retrieve imagecarousel widget name.
     *
     * @return string Widget name.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_name() {
        return 'hitboox-image-carousel';
    }

    /**
     * Get widget title.
     *
     * Retrieve imagecarousel widget title.
     *
     * @return string Widget title.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_title() {
        return esc_html__('Hitboox Image Carousel', 'hitboox');
    }

    /**
     * Get widget icon.
     *
     * Retrieve imagecarousel widget icon.
     *
     * @return string Widget icon.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_icon() {
        return 'eicon-carousel';
    }

    public function get_script_depends() {
        return ['hitboox-elementor-image-carousel', 'hitboox-elementor-swiper', 'magnific-popup'];
    }

    public function get_style_depends() {
        return ['magnific-popup'];
    }

    public function get_categories() {
        return array('hitboox-addons');
    }

    /**
     * Register imagecarousel widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since  1.0.0
     * @access protected
     */
    protected function register_controls() {
        $this->start_controls_section(
            'section_imagecarousel',
            [
                'label' => esc_html__('Image Carousel', 'hitboox'),
            ]
        );

        $this->add_control(
            'carousel',
            [
                'label'      => __('Add Images', 'hitboox'),
                'type'       => Controls_Manager::GALLERY,
                'default'    => [],
                'show_label' => false,
                'dynamic'    => [
                    'active' => true,
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'      => 'thumbnail',
                'separator' => 'none',
                'default'   => 'maisonco-gallery-image'
            ]
        );


        $this->add_responsive_control(
            'column',
            [
                'label'     => esc_html__('Columns', 'hitboox'),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'default'   => 3,
                'options'   => [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6],
                'selectors' => [
                    '{{WRAPPER}} .d-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr)',
                ],
                'condition' => ['enable_carousel!' => 'yes']
            ]
        );

        $this->add_responsive_control(
            'gutter',
            [
                'label'      => esc_html__('Gutter', 'hitboox'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .d-grid' => 'grid-gap:{{SIZE}}{{UNIT}}',
                ],
                'condition'  => ['enable_carousel!' => 'yes']
            ]
        );

        $this->add_control(
            'link_to',
            [
                'label'   => esc_html__('Link', 'hitboox'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'file',
                'options' => [
                    'file'             => esc_html__('Media File', 'hitboox'),
                    'custom'           => esc_html__('Custom URL', 'hitboox'),
                    'custom-link-item' => esc_html__('Custom URL in Image', 'hitboox'),
                    'none'             => esc_html__('None', 'hitboox'),
                ],
            ]
        );

        $this->add_control(
            'link',
            [
                'label'       => esc_html__('Link', 'hitboox'),
                'type'        => Controls_Manager::URL,
                'placeholder' => esc_html__('https://your-link.com', 'hitboox'),
                'condition'   => [
                    'link_to' => 'custom',
                ],
                'show_label'  => false,
                'dynamic'     => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            'open_lightbox',
            [
                'label'     => esc_html__('Lightbox', 'hitboox'),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'default',
                'options'   => [
                    'default' => esc_html__('Default', 'hitboox'),
                    'yes'     => esc_html__('Yes', 'hitboox'),
                    'no'      => esc_html__('No', 'hitboox'),
                ],
                'condition' => [
                    'link_to' => 'file',
                ],
            ]
        );
        $this->add_control(
            'enable_title',
            [
                'label'        => esc_html__('No Title', 'hitboox'),
                'type'         => Controls_Manager::SWITCHER,
                'prefix_class' => 'enable-title-',
            ]
        );
        $this->add_control(
            'enable_carousel',
            [
                'label' => esc_html__('Enable Carousel', 'hitboox'),
                'type'  => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'layout_special',
            [
                'label'        => esc_html__('Layout Special', 'hitboox'),
                'type'         => Controls_Manager::SWITCHER,
                'prefix_class' => 'layout-special-',
                'condition' => [
                    'enable_carousel' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'special_gutter',
            [
                'label'      => esc_html__('Gutter', 'hitboox'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}}.layout-special-yes .image-carousel' => 'margin-bottom:{{SIZE}}{{UNIT}}',
                ],
                'condition'  => ['layout_special' => 'yes']
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_design_image',
            [
                'label' => esc_html__('Image', 'hitboox'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'image_radius',
            [
                'label'      => esc_html__('Border Radius', 'hitboox'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .grid-item a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
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
                    '{{WRAPPER}} .grid-item img' => 'height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();

        // Carousel options
        $this->add_control_carousel(['enable_carousel' => 'yes']);

    }

    private function get_link_url($attachment, $instance) {
        if ('none' == $instance['link_to']) {
            return false;
        }

        if ('custom' == $instance['link_to']) {
            if (empty($instance['link']['url'])) {
                return false;
            }

            return $instance['link'];
        }

        if ('custom-link-item' === $instance['link_to']) {
            $url = get_post_meta($attachment['id'], 'hitboox_custom_media_link', true);
            if ($url) {
                return [
                    'url' => get_post_meta($attachment['id'], 'hitboox_custom_media_link', true),
                    //                'is_external' => 'true'
                ];
            }
        }

        return [
            'url' => wp_get_attachment_url($attachment['id']),
        ];
    }

    /**
     * Render imagecarousel widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since  1.0.0
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        $this->add_render_attribute('item', 'class', 'image-carousel-item');

        // Carousel
        $this->get_data_elementor_columns();

        if ($settings['layout_special'] == 'yes') {

            $item_number = 0;
            $html = '';
            $item_count = count($settings['carousel']);

            foreach ($settings['carousel'] as $index => $attachment) {

                if ($item_number % 2 == 0) {
                    $html .= '<div class="elementor-imagecarousel-item swiper-slide">';
                    $html .= '<img class="image-carousel" src="' . esc_attr($attachment['url']) . '" alt="' . esc_attr($index) . '" />';
                } else {
                    $html .= '<img class="image-carousel" src="' . esc_attr($attachment['url']) . '" alt="' . esc_attr($index) . '" />';
                    $html .= '</div>';
                }

                $item_number++;
            }

            if ($item_count % 2 == 1) {
                $html .= '</div>';
            }
            }
        ?>
        <div <?php $this->print_render_attribute_string('wrapper'); // WPCS: XSS ok. ?>>

            <div <?php $this->print_render_attribute_string('row'); // WPCS: XSS ok. ?>>
             <?php if ($settings['layout_special'] != 'yes') {
                foreach ($settings['carousel'] as $items => $attachment) {
                    $link_key = 'link_' . $items;
                    $link     = $this->get_link_url($attachment, $settings);

                    $image_url = Group_Control_Image_Size::get_attachment_image_src($attachment['id'], 'thumbnail', $settings);
                    $this->add_render_attribute($link_key, ['class' => 'photo__bounce-wrap']);
                    ?>
                    <div <?php $this->print_render_attribute_string('item'); ?>>
                        <?php

                        if ($link) {

                            if ('custom' !== $settings['link_to'] && 'custom-link-item' !== $settings['link_to']) {
                                $this->add_lightbox_data_attributes($link_key, $attachment['id'], $settings['open_lightbox'], $this->get_id());
                            }
                            if ('custom-link-item' == $settings['link_to']) {
                                $this->add_render_attribute($link_key, ['class' => 'elementor-video elementor-clickable', 'data-elementor-open-lightbox' => 'no']);
                            }
                            if (Plugin::$instance->editor->is_edit_mode()) {
                                $this->add_render_attribute($link_key, [
                                    'class'                        => 'elementor-clickable',
                                    'data-elementor-open-lightbox' => 'no'
                                ]);
                            }

                            $this->add_link_attributes($link_key, $link);
                        } else {
                            $this->add_render_attribute($link_key, 'class', 'elementor-clickable');
                        }
                        ?>
                        <a <?php $this->print_render_attribute_string($link_key); ?>>
                            <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr(Elementor\Control_Media::get_image_alt($attachment)); ?>"/>
                            <div class="gallery-title"><?php echo esc_html(Elementor\Control_Media::get_image_title($attachment)); ?></div>
                        </a>
                    </div>
                <?php }
             } ?>

            <?php if ($settings['layout_special'] == 'yes') {
                printf('%s', $html);
            } ?>
            </div>
        </div>
        <?php $this->render_swiper_pagination_navigation(); ?>
        <?php
    }
}

$widgets_manager->register(new Hitboox_Elementor_ImageCarousel());

