<?php
if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('Hitboox_Customize')) {

    class Hitboox_Customize {


        public function __construct() {
            add_action('customize_register', array($this, 'customize_register'));
        }

        /**
         * @param $wp_customize WP_Customize_Manager
         */
        public function customize_register($wp_customize) {

            /**
             * Theme options.
             */
            require_once get_theme_file_path('inc/customize-control/editor.php');
            $this->init_hitboox_blog($wp_customize);
            $this->init_hitboox_social($wp_customize);
            $this->init_hitboox_scroll($wp_customize);

            if (hitboox_is_woocommerce_activated()) {
                $this->init_woocommerce($wp_customize);
            }

            do_action('hitboox_customize_register', $wp_customize);
        }

        public function init_hitboox_scroll($wp_customize) {

            $wp_customize->add_section('hitboox_smooth_scroll', array(
                'title' => esc_html__('Smooth Scroll', 'hitboox'),
            ));

            $wp_customize->add_setting('hitboox_options_smooth_scroll', array(
                'type'              => 'option',
                'default'           => '',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('hitboox_options_smooth_scroll', array(
                'type'        => 'radio',
                'section'     => 'hitboox_smooth_scroll',
                'label'       => esc_html__('Enable', 'hitboox'),
                'description' => esc_html__('Smooth Scroll On Windows', 'hitboox'),
                'choices'     => array(
                    ''    => esc_html__('No', 'hitboox'),
                    'yes' => esc_html__('Yes', 'hitboox'),
                ),
            ));
        }


        /**
         * @param $wp_customize WP_Customize_Manager
         *
         * @return void
         */
        public function init_hitboox_blog($wp_customize) {

            $wp_customize->add_section('hitboox_blog_archive', array(
                'title' => esc_html__('Blog', 'hitboox'),
            ));

            // =========================================
            // Select Style
            // =========================================

            $wp_customize->add_setting('hitboox_options_blog_style', array(
                'type'              => 'option',
                'default'           => 'standard',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('hitboox_options_blog_style', array(
                'section' => 'hitboox_blog_archive',
                'label'   => esc_html__('Blog style', 'hitboox'),
                'type'    => 'select',
                'choices' => array(
                    'standard' => esc_html__('Blog Standard', 'hitboox'),
                    //====start_premium
                    'grid'     => esc_html__('Blog Grid', 'hitboox'),
                    'list'     => esc_html__('Blog List', 'hitboox'),
                    //====end_premium
                ),
            ));

            $wp_customize->add_setting('hitboox_options_blog_columns', array(
                'type'              => 'option',
                'default'           => 1,
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('hitboox_options_blog_columns', array(
                'section' => 'hitboox_blog_archive',
                'label'   => esc_html__('Colunms', 'hitboox'),
                'type'    => 'select',
                'choices' => array(
                    1 => esc_html__('1', 'hitboox'),
                    2 => esc_html__('2', 'hitboox'),
                    3 => esc_html__('3', 'hitboox'),
                    4 => esc_html__('4', 'hitboox'),
                ),
            ));

            $wp_customize->add_setting('hitboox_options_blog_archive_sidebar', array(
                'type'              => 'option',
                'default'           => 'right',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('hitboox_options_blog_archive_sidebar', array(
                'section' => 'hitboox_blog_archive',
                'label'   => esc_html__('Sidebar Position', 'hitboox'),
                'type'    => 'select',
                'choices' => array(
                    'left'  => esc_html__('Left', 'hitboox'),
                    'right' => esc_html__('Right', 'hitboox'),
                ),
            ));
        }

        /**
         * @param $wp_customize WP_Customize_Manager
         *
         * @return void
         */
        public function init_hitboox_social($wp_customize) {

            $wp_customize->add_section('hitboox_social', array(
                'title' => esc_html__('Socials', 'hitboox'),
            ));
            $wp_customize->add_setting('hitboox_options_social_share', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('hitboox_options_social_share', array(
                'type'    => 'checkbox',
                'section' => 'hitboox_social',
                'label'   => esc_html__('Show Social Share', 'hitboox'),
            ));
            $wp_customize->add_setting('hitboox_options_social_share_facebook', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('hitboox_options_social_share_facebook', array(
                'type'    => 'checkbox',
                'section' => 'hitboox_social',
                'label'   => esc_html__('Share on Facebook', 'hitboox'),
            ));
            $wp_customize->add_setting('hitboox_options_social_share_twitter', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('hitboox_options_social_share_twitter', array(
                'type'    => 'checkbox',
                'section' => 'hitboox_social',
                'label'   => esc_html__('Share on Twitter', 'hitboox'),
            ));
            $wp_customize->add_setting('hitboox_options_social_share_linkedin', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('hitboox_options_social_share_linkedin', array(
                'type'    => 'checkbox',
                'section' => 'hitboox_social',
                'label'   => esc_html__('Share on Linkedin', 'hitboox'),
            ));
            $wp_customize->add_setting('hitboox_options_social_share_google-plus', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('hitboox_options_social_share_google-plus', array(
                'type'    => 'checkbox',
                'section' => 'hitboox_social',
                'label'   => esc_html__('Share on Google+', 'hitboox'),
            ));

            $wp_customize->add_setting('hitboox_options_social_share_pinterest', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('hitboox_options_social_share_pinterest', array(
                'type'    => 'checkbox',
                'section' => 'hitboox_social',
                'label'   => esc_html__('Share on Pinterest', 'hitboox'),
            ));
            $wp_customize->add_setting('hitboox_options_social_share_email', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('hitboox_options_social_share_email', array(
                'type'    => 'checkbox',
                'section' => 'hitboox_social',
                'label'   => esc_html__('Share on Email', 'hitboox'),
            ));
        }

        public function init_woocommerce($wp_customize) {

            $wp_customize->add_panel('woocommerce', array(
                'title' => esc_html__('Woocommerce', 'hitboox'),
            ));

            $wp_customize->add_section('hitboox_woocommerce_archive', array(
                'title'      => esc_html__('Archive', 'hitboox'),
                'capability' => 'edit_theme_options',
                'panel'      => 'woocommerce',
                'priority'   => 1,
            ));

            $wp_customize->add_setting('hitboox_options_woocommerce_archive_sidebar', array(
                'type'              => 'option',
                'default'           => 'left',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('hitboox_options_woocommerce_archive_sidebar', array(
                'section' => 'hitboox_woocommerce_archive',
                'label'   => esc_html__('Sidebar Position', 'hitboox'),
                'type'    => 'select',
                'choices' => array(
                    'left'  => esc_html__('Left', 'hitboox'),
                    'right' => esc_html__('Right', 'hitboox'),

                ),
            ));

            // =========================================
            // Single Product
            // =========================================

            $wp_customize->add_section('hitboox_woocommerce_single', array(
                'title'      => esc_html__('Single Product', 'hitboox'),
                'capability' => 'edit_theme_options',
                'panel'      => 'woocommerce',
            ));

            $wp_customize->add_setting('hitboox_options_single_product_content_meta', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'hitboox_sanitize_editor',
            ));

            $wp_customize->add_control(new Hitboox_Customize_Control_Editor($wp_customize, 'hitboox_options_single_product_content_meta', array(
                'section' => 'hitboox_woocommerce_single',
                'label'   => esc_html__('Single extra description', 'hitboox'),
            )));


            // =========================================
            // Product
            // =========================================
            
            $wp_customize->add_section('hitboox_woocommerce_product', array(
                'title'      => esc_html__('Product Block', 'hitboox'),
                'capability' => 'edit_theme_options',
                'panel'      => 'woocommerce',
            ));

            $wp_customize->add_setting('hitboox_options_woocommerce_product_hover', array(
                'type'              => 'option',
                'default'           => 'none',
                'transport'         => 'refresh',
                'sanitize_callback' => 'sanitize_text_field',
            ));
            
            $wp_customize->add_control('hitboox_options_woocommerce_product_hover', array(
                'section' => 'hitboox_woocommerce_product',
                'label'   => esc_html__('Animation Image Hover', 'hitboox'),
                'type'    => 'select',
                'choices' => array(
                    'none'          => esc_html__('None', 'hitboox'),
                    'bottom-to-top' => esc_html__('Bottom to Top', 'hitboox'),
                    'top-to-bottom' => esc_html__('Top to Bottom', 'hitboox'),
                    'right-to-left' => esc_html__('Right to Left', 'hitboox'),
                    'left-to-right' => esc_html__('Left to Right', 'hitboox'),
                    'swap'          => esc_html__('Swap', 'hitboox'),
                    'fade'          => esc_html__('Fade', 'hitboox'),
                    'zoom-in'       => esc_html__('Zoom In', 'hitboox'),
                    'zoom-out'      => esc_html__('Zoom Out', 'hitboox'),
                ),
            ));
        }
    }
}
return new Hitboox_Customize();
