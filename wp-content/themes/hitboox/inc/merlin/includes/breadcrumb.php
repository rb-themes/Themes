<?php

use HFE\Lib\Astra_Target_Rules_Fields;

class Hitboox_breadcrumb {

    private static $_instance = null;

    public static function instance() {
        if (!isset(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        add_action('init', [$this, 'breadcrumb_register']);
        add_action('admin_menu', [$this, 'register_admin_menu'], 50);
        hitboox_add_meta('boxes', [$this, 'ehf_register_meta']);
        add_action('save_post', [$this, 'ehf_save_meta']);
        add_action('save_post', [$this, 'ehf_save_meta']);
        add_action('template_redirect', [$this, 'block_template_frontend']);
        add_filter('single_template', [$this, 'load_canvas_template']);
        add_action('hfe_header', [$this, 'render_breadcrumb'], 99);
        add_filter('hitboox_page_title', [$this, 'hide_title']);
        add_action('wp_enqueue_scripts', [$this, 'scripts']);

    }

    public function breadcrumb_register() {
        $labels = [
            'name'               => esc_html__('Breadcrumbs', 'hitboox'),
            'singular_name'      => esc_html__('Breadcrumb', 'hitboox'),
            'menu_name'          => esc_html__('Breadcrumbs', 'hitboox'),
            'name_admin_bar'     => esc_html__('Breadcrumb', 'hitboox'),
            'add_new'            => esc_html__('Add New', 'hitboox'),
            'add_new_item'       => esc_html__('Add New Breadcrumb', 'hitboox'),
            'new_item'           => esc_html__('New Header Footer & Blocks Template', 'hitboox'),
            'edit_item'          => esc_html__('Edit Header Footer & Blocks Template', 'hitboox'),
            'view_item'          => esc_html__('View Header Footer & Blocks Template', 'hitboox'),
            'all_items'          => esc_html__('All Breadcrumb', 'hitboox'),
            'search_items'       => esc_html__('Search Header Footer & Blocks Templates', 'hitboox'),
            'parent_item_colon'  => esc_html__('Parent Header Footer & Blocks Templates:', 'hitboox'),
            'not_found'          => esc_html__('No Header Footer & Blocks Templates found.', 'hitboox'),
            'not_found_in_trash' => esc_html__('No Header Footer & Blocks Templates found in Trash.', 'hitboox'),
        ];

        $args = [
            'labels'              => $labels,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => false,
            'show_in_nav_menus'   => false,
            'exclude_from_search' => true,
            'capability_type'     => 'post',
            'hierarchical'        => false,
            'supports'            => ['title', 'thumbnail', 'elementor'],
        ];

        hitboox_function_to_call('post_type',['hitboox-breadcrumb', $args]);
    }


    /**
     * Register the admin menu for Header Footer & Blocks builder.
     *
     * @since  1.0.0
     * @since  1.0.1
     *         Moved the menu under Appearance -> Header Footer & Blocks Builder
     */
    public function register_admin_menu() {
        add_submenu_page(
            'themes.php',
            esc_html__('Breadcrumbs', 'hitboox'),
            esc_html__('Breadcrumbs', 'hitboox'),
            'edit_pages',
            'edit.php?post_type=hitboox-breadcrumb'
        );
    }


    /**
     * Register meta box(es).
     */
    function ehf_register_meta() {
        hitboox_add_meta_fn('box',[
                'ehf-meta-box',
                esc_html__('Breadcrumb Options', 'hitboox'),
                [
                    $this,
                    'efh_meta_render',
                ],
                'hitboox-breadcrumb',
                'normal',
                'high']
        );
    }

    function efh_meta_render($post) {
        // We'll use this nonce field later on when saving.
        wp_nonce_field('ehf_meta_nounce', 'ehf_meta_nounce');
        ?>
        <table class="hfe-options-table widefat">
            <tbody>
            <?php
            // Load Target Rule assets.
            HFE\Lib\Astra_Target_Rules_Fields::get_instance()->admin_styles();

            $include_locations = get_post_meta(get_the_id(), 'ehf_target_include_locations', true);
            $exclude_locations = get_post_meta(get_the_id(), 'ehf_target_exclude_locations', true);
            $users             = get_post_meta(get_the_id(), 'ehf_target_user_roles', true);

            ?>
            <tr class="bsf-target-rules-row hfe-options-row">
                <td class="bsf-target-rules-row-heading hfe-options-row-heading">
                    <label><?php esc_html_e('Display On', 'hitboox'); ?></label>
                    <i class="bsf-target-rules-heading-help dashicons dashicons-editor-help"
                       title="<?php echo esc_attr__('Add locations for where this template should appear.', 'hitboox'); ?>"></i>
                </td>
                <td class="bsf-target-rules-row-content hfe-options-row-content">
                    <input type="hidden" id="ehf_template_type" value="breadcrumb">
                    <?php
                    HFE\Lib\Astra_Target_Rules_Fields::target_rule_settings_field(
                        'bsf-target-rules-location',
                        [
                            'title'          => esc_html__('Display Rules', 'hitboox'),
                            'value'          => '[{"type":"basic-global","specific":null}]',
                            'tags'           => 'site,enable,target,pages',
                            'rule_type'      => 'display',
                            'add_rule_label' => esc_html__('Add Display Rule', 'hitboox'),
                        ],
                        $include_locations
                    );
                    ?>
                </td>
            </tr>
            <tr class="bsf-target-rules-row hfe-options-row">
                <td class="bsf-target-rules-row-heading hfe-options-row-heading">
                    <label><?php esc_html_e('Do Not Display On', 'hitboox'); ?></label>
                    <i class="bsf-target-rules-heading-help dashicons dashicons-editor-help"
                       title="<?php echo esc_attr__('Add locations for where this template should not appear.', 'hitboox'); ?>"></i>
                </td>
                <td class="bsf-target-rules-row-content hfe-options-row-content">
                    <?php
                    HFE\Lib\Astra_Target_Rules_Fields::target_rule_settings_field(
                        'bsf-target-rules-exclusion',
                        [
                            'title'          => esc_html__('Exclude On', 'hitboox'),
                            'value'          => '[]',
                            'tags'           => 'site,enable,target,pages',
                            'add_rule_label' => esc_html__('Add Exclusion Rule', 'hitboox'),
                            'rule_type'      => 'exclude',
                        ],
                        $exclude_locations
                    );
                    ?>
                </td>
            </tr>
            <tr class="bsf-target-rules-row hfe-options-row">
                <td class="bsf-target-rules-row-heading hfe-options-row-heading">
                    <label><?php esc_html_e('User Roles', 'hitboox'); ?></label>
                    <i class="bsf-target-rules-heading-help dashicons dashicons-editor-help" title="<?php echo esc_attr__('Display custom template based on user role.', 'hitboox'); ?>"></i>
                </td>
                <td class="bsf-target-rules-row-content hfe-options-row-content">
                    <?php
                    HFE\Lib\Astra_Target_Rules_Fields::target_user_role_settings_field(
                        'bsf-target-rules-users',
                        [
                            'title'          => esc_html__('Users', 'hitboox'),
                            'value'          => '[]',
                            'tags'           => 'site,enable,target,pages',
                            'add_rule_label' => esc_html__('Add User Rule', 'hitboox'),
                        ],
                        $users
                    );
                    ?>
                </td>
            </tr>
            </tbody>
        </table>
        <?php
    }

    public function ehf_save_meta($post_id) {

        // Bail if we're doing an auto save.
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // if our nonce isn't there, or we can't verify it, bail.
        if (!isset($_POST['ehf_meta_nounce']) || !wp_verify_nonce($_POST['ehf_meta_nounce'], 'ehf_meta_nounce')) {
            return;
        }

        // if our current user can't edit this post, bail.
        if (!current_user_can('edit_posts')) {
            return;
        }

        $target_locations = Astra_Target_Rules_Fields::get_format_rule_value($_POST, 'bsf-target-rules-location');
        $target_exclusion = Astra_Target_Rules_Fields::get_format_rule_value($_POST, 'bsf-target-rules-exclusion');
        $target_users     = [];

        if (isset($_POST['bsf-target-rules-users'])) {
            $target_users = array_map('sanitize_text_field', $_POST['bsf-target-rules-users']);
        }

        update_post_meta($post_id, 'ehf_target_include_locations', $target_locations);
        update_post_meta($post_id, 'ehf_target_exclude_locations', $target_exclusion);
        update_post_meta($post_id, 'ehf_target_user_roles', $target_users);
    }

    public function hide_title($val) {

        if ($this->get_template_id()) {
            $val = false;
        }

        return $val;
    }

    public static function get_template_id() {
        $option = [
            'location'  => 'ehf_target_include_locations',
            'exclusion' => 'ehf_target_exclude_locations',
            'users'     => 'ehf_target_user_roles',
        ];

        $hfe_templates = Astra_Target_Rules_Fields::get_instance()->get_posts_by_conditions('hitboox-breadcrumb', $option);

        foreach ($hfe_templates as $template) {
            return apply_filters('hitboox_breadcrumb_id', $template['id']);
        }

        return '';
    }

    public function render_breadcrumb() {
        $template_id = $this->get_template_id();
        if($template_id && !is_singular('hitboox_project') && !is_singular('hitboox_services')) {
            echo '<div class="breadcrumb-wrap"><div class="breadcrumb-overlay"></div>' . Elementor\Plugin::instance()->frontend->get_builder_content_for_display($template_id) . '</div>';
        }

    }

    public function scripts() {
        if ($this->get_template_id()) {

            if (class_exists('\Elementor\Core\Files\CSS\Post')) {
                $css_file = new \Elementor\Core\Files\CSS\Post($this->get_template_id());
            } elseif (class_exists('\Elementor\Post_CSS_File')) {
                $css_file = new \Elementor\Post_CSS_File($this->get_template_id());
            }

            $css_file->enqueue();
        }
    }

    /**
     * Don't display the elementor header footer & blocks templates on the frontend for non edit_posts capable users.
     *
     * @since  1.0.0
     */
    public function block_template_frontend() {
        if (is_singular('hitboox-breadcrumb') && !current_user_can('edit_posts')) {
            wp_redirect(site_url(), 301);
            die;
        }
    }

    function load_canvas_template($single_template) {
        global $post;

        if ('hitboox-breadcrumb' == $post->post_type) {
            $elementor_2_0_canvas = ELEMENTOR_PATH . '/modules/page-templates/templates/canvas.php';

            if (file_exists($elementor_2_0_canvas)) {
                return $elementor_2_0_canvas;
            } else {
                return ELEMENTOR_PATH . '/includes/page-templates/canvas.php';
            }
        }

        return $single_template;
    }

}

Hitboox_breadcrumb::instance();
