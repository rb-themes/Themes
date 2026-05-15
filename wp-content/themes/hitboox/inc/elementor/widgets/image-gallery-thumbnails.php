<?php

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Repeater;
use Elementor\Utils;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Elementor image gallery widget.
 *
 * Elementor widget that displays a set of images in an aligned grid.
 *
 * @since 1.0.0
 */
class Hitboox_Elementor_Image_Gallery_Thumbnails extends Hitboox_Base_Widgets_Swiper
{

    /**
     * Get widget name.
     *
     * Retrieve image gallery widget name.
     *
     * @return string Widget name.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_name()
    {
        return 'hitboox-image-gallery-thumbnails';
    }

    /**
     * Get widget title.
     *
     * Retrieve image gallery widget title.
     *
     * @return string Widget title.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_title()
    {
        return esc_html__('Hitboox Carousel Videos', 'hitboox');
    }

    /**
     * Get widget icon.
     *
     * Retrieve image gallery widget icon.
     *
     * @return string Widget icon.
     * @since  1.0.0
     * @access public
     *
     */

    public function get_icon()
    {
        return 'eicon-carousel-loop';
    }

    public function get_script_depends()
    {
        return [
            'hitboox-elementor-image-gallery-thumbnails', 'hitboox-elementor-swiper', 'magnific-popup'
        ];
    }

    public function get_categories()
    {
        return ['hitboox-addons'];
    }


    /**
     * Get widget keywords.
     *
     * Retrieve the list of keywords the widget belongs to.
     *
     * @return array Widget keywords.
     * @since  2.1.0
     * @access public
     *
     */
    public function get_keywords()
    {
        return ['image', 'photo', 'visual', 'gallery', 'video'];
    }

    /**
     * Register image gallery widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since  1.0.0
     * @access protected
     */
    protected function register_controls()
    {
        $this->start_controls_section(
            'section_gallery',
            [
                'label' => esc_html__('Image Gallery', 'hitboox'),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'gallery_image',
            [
                'label' => esc_html__('Add Images', 'hitboox'),
                'type' => Controls_Manager::MEDIA,
                'label_block' => false,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'youtube_link',
            [
                'label' => esc_html__('Youtube Video (option)', 'hitboox'),
                'type' => Controls_Manager::URL,
                'description' => esc_html__('If you choose the image will be converted into a video', 'hitboox'),
            ]
        );

        $this->add_control(
            'images_items',
            [
                'label' => '',
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'gallery_image',
                'default' => 'full'
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
            'enable_carousel',
            [
                'label' => esc_html__('Enable Carousel', 'hitboox'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->end_controls_section();
        // Carousel options
        $this->add_control_carousel(['enable_carousel' => 'yes']);
    }

    /**
     * Render image gallery widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since  1.0.0
     * @access protected
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        // Carousel
        $this->get_data_elementor_columns();

        $main_html = '';

        foreach ($settings['images_items'] as $item) {
            ob_start();
            ?>
            <div <?php $this->print_render_attribute_string('item'); ?>>
                <div class="image-gallery">
                    <?php $this->render_image($settings, $item);
                    $has_youtube = !empty($item['youtube_link']['url']);
                    $url = $has_youtube ? $item['youtube_link']['url'] : $item['gallery_image']['url'];
                    $class = $has_youtube ? 'hitboox-video-item' : 'image-item';
                    ?>

                    <a href="<?php echo esc_attr($url); ?>" class="image-item-link image-gallery-icon path-wrap-yes <?php echo esc_attr($class)?>" data-effect="mfp-fade">
                        <?php if ($has_youtube) { ?>
                            <i class="hitboox-icon-play"></i>
                        <?php } else { ?>
                            <i class="hitboox-icon-search-plus"></i>
                        <?php } ?>
                    </a>
                </div>
            </div>
            <?php
            $main_html .= ob_get_clean();
            ob_start();
        }
        ?>

        <div <?php $this->print_render_attribute_string('wrapper'); // WPCS: XSS ok. ?>>
            <div <?php $this->print_render_attribute_string('row'); // WPCS: XSS ok. ?>>
                <?php printf('%s', $main_html); ?>
            </div>

        </div>
        <?php $this->render_swiper_pagination_navigation(); ?>
        <?php

    }

    private function render_image($settings, $item, $size = false)
    {
        if (!empty($item['gallery_image']['url'])) :
            if ($size) {
                $item['gallery_image_size'] = $size;
                $item['gallery_image_custom_dimension'] = null;
            } else {
                $item['gallery_image_size'] = $settings['gallery_image_size'];
                $item['gallery_image_custom_dimension'] = $settings['gallery_image_custom_dimension'];
            }
            echo Group_Control_Image_Size::get_attachment_image_html($item, 'gallery_image');
        endif;
    }


}

$widgets_manager->register(new Hitboox_Elementor_Image_Gallery_Thumbnails());





