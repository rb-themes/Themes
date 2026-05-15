<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class Hitboox_Service
 */
class Hitboox_Services
{
    public $post_type = 'hitboox_services';
    public $taxonomy = 'hitboox_services_tags';
    static $instance;

    public static function getInstance()
    {
        if (!isset(self::$instance) && !(self::$instance instanceof Hitboox_Services)) {
            self::$instance = new Hitboox_Services();
        }

        return self::$instance;
    }

    public function __construct()
    {
        add_action('init', [$this, 'create_services']);
        add_action('init', [$this, 'create_taxonomy']);
        if (hitboox_is_cmb2_activated()) {
            add_action('init', [$this, 'setup_meta']);
        }
    }

    public function setup_meta()
    {
        add_action('cmb2_admin_init', [$this, 'meta_services']);
    }

    public function meta_services()
    {
        $cmb2 = new_cmb2_box(array(
            'id' => 'hitboox_services_setting',
            'title' => esc_html__('Infomation', 'hitboox'),
            'object_types' => array('hitboox_services'),
        ));

        $cmb2->add_field(array(
            'name' => esc_html__('Service Icon', 'hitboox'),
            'id' => 'services_icon_class',
            'type' => 'file',
            'options' => array(
                'url' => true,
            ),
        ));
        $group_field_id = $cmb2->add_field(array(
            'id' => 'services_features_repeat_group',
            'type' => 'group',
            'description' => __('Features', 'hitboox'),
            'options' => array(
                'group_title' => __('Feature {#}', 'hitboox'),
                'add_button' => __('Add Another Feature', 'hitboox'),
                'remove_button' => __('Remove Feature', 'hitboox'),
                'sortable' => true,
            ),
        ));
        $cmb2->add_group_field($group_field_id, array(
            'name' => __('Feature', 'hitboox'),
            'id' => 'title',
            'type' => 'text',
        ));
    }


    /**
     * @return void
     */
    public function create_services()
    {

        $labels = array(
            'name' => esc_html__('Services', 'hitboox'),
            'singular_name' => esc_html__('Service', 'hitboox'),
            'add_new' => esc_html__('Add New Service', 'hitboox'),
            'add_new_item' => esc_html__('Add New Service', 'hitboox'),
            'edit_item' => esc_html__('Edit Service', 'hitboox'),
            'new_item' => esc_html__('New Service', 'hitboox'),
            'view_item' => esc_html__('View Service', 'hitboox'),
            'search_items' => esc_html__('Search Services', 'hitboox'),
            'not_found' => esc_html__('No Services found', 'hitboox'),
            'not_found_in_trash' => esc_html__('No Services found in Trash', 'hitboox'),
            'parent_item_colon' => esc_html__('Parent Service:', 'hitboox'),
            'menu_name' => esc_html__('Services', 'hitboox'),
        );

        $labels = apply_filters('hitboox_services_labels', $labels);
        $slug_field = apply_filters('hitboox_services_slug', 'services');

        hitboox_function_to_call('post_type', [$this->post_type,
            array(
                'labels' => $labels,
                'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
                'public' => true,
                'has_archive' => false,
                'rewrite' => array('slug' => $slug_field),
                'menu_position' => 5,
            )
        ]);
    }

    public function create_taxonomy()
    {
        $labels = array(
            'name' => esc_html__('Tags', 'hitboox'),
            'singular_name' => esc_html__('Tag', 'hitboox'),
            'search_items' => esc_html__('Search Tags', 'hitboox'),
            'popular_items' => esc_html__('Popular Tags', 'hitboox'),
            'all_items' => esc_html__('All Tags', 'hitboox'),
            'parent_item' => null,
            'parent_item_colon' => null,
            'edit_item' => esc_html__('Edit Tag', 'hitboox'),
            'update_item' => esc_html__('Update Tag', 'hitboox'),
            'add_new_item' => esc_html__('Add New Tag', 'hitboox'),
            'new_item_name' => esc_html__('New Tag Name', 'hitboox'),
            'separate_items_with_commas' => esc_html__('Separate tags with commas', 'hitboox'),
            'add_or_remove_items' => esc_html__('Add or remove tags', 'hitboox'),
            'choose_from_most_used' => esc_html__('Choose from the most used tags', 'hitboox'),
            'menu_name' => esc_html__('Tags', 'hitboox'),
        );

        $labels = apply_filters('hitboox_services_tag_labels', $labels);
        $slug_tag_field = apply_filters('slug_tags_services', 'tags-services');
        $args = array(
            'hierarchical' => false,
            'labels' => $labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'public' => false,
            'query_var' => true,
            'show_in_nav_menus' => true,
            'rewrite' => array('slug' => $slug_tag_field)
        );

        hitboox_function_to_call('taxonomy', [$this->taxonomy, array($this->post_type), $args]);
    }

}

Hitboox_Services::getInstance();
