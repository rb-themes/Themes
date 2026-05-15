<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;

/**
 * Elementor Hitboox_Elementor_Post_Filter
 * @since 1.0.0
 */
class Hitboox_Elementor_Post_Filter extends \Elementor\Widget_Base {

    public function get_categories() {
        return array('hitboox-addons');
    }

    /**
     * Get widget name.
     *
     * Retrieve tabs widget name.
     *
     *
     * @return string Widget name.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_name() {
        return 'hitboox-post-filter';
    }

    /**
     * Get widget title.
     *
     * Retrieve tabs widget title.
     *
     * @return string Widget title.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_title() {
        return esc_html__('Hitboox Post Filter', 'hitboox');
    }

    /**
     * Get widget icon.
     *
     * Retrieve tabs widget icon.
     *
     * @return string Widget icon.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_icon() {
        return 'eicon-tabs';
    }

    protected function register_controls() {

        //Section Query
        $this->start_controls_section(
            'section_setting',
            [
                'label' => esc_html__('Filter', 'hitboox'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control('hide_empty', [
            'label' => esc_html__('Hide empty', 'hitboox'),
            'type'  => Controls_Manager::SWITCHER,
        ]);

        $this->end_controls_section();

    }

    /**
     * Render tabs widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since  1.0.0
     * @access protected
     */
    protected function render() {
        $settings   = $this->get_settings_for_display();
        $hide_empty = $settings['hide_empty'] == 'yes';
        $link = get_post_type_archive_link('post');
        if (isset($_GET['blog_style'])) {
            $link = add_query_arg('blog_style', wc_clean($_GET['blog_style']), $link);
        }

        ?>
        <div class="navigation-post-filter">
            <?php
            $class = is_home() ? 'active' : '';
            ?>
            <a class="<?php echo esc_attr($class); ?>" href="<?php echo esc_url($link); ?>"><?php echo esc_html__('All Posts', 'hitboox'); ?></a>
            <?php
            $taxonomy = 'category';
            $terms    = $terms = get_terms([
                'taxonomy'   => 'category',
                'hide_empty' => $hide_empty,
            ]);
            foreach ($terms as $term) {
                $item_class = is_category($term->slug) ? 'active' : '';
                $link_cat = get_term_link($term->slug, $taxonomy);
                if (isset($_GET['blog_style'])) {
                    $link_cat = add_query_arg('blog_style', wc_clean($_GET['blog_style']), $link_cat);
                }
                ?>
                <a class="<?php echo esc_attr($item_class); ?>" href="<?php echo esc_url($link_cat); ?>"><?php echo esc_html($term->name); ?></a>
                <?php
            }
            ?>
        </div>
        <?php
    }

}

$widgets_manager->register(new Hitboox_Elementor_Post_Filter());

