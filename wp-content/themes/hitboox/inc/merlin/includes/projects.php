<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class Hitboox_Project
 */
class Hitboox_Project
{
    public $post_type = 'hitboox_project';
    public $taxonomy_platform = 'hitboox_project_platform';
    public $taxonomy_genre = 'hitboox_project_genre';
    static $instance;

    public static function getInstance()
    {
        if (!isset(self::$instance) && !(self::$instance instanceof Hitboox_Project)) {
            self::$instance = new Hitboox_Project();
        }

        return self::$instance;
    }

    public function __construct()
    {
        add_action('init', [$this, 'create_projects']);
        add_action('init', [$this, 'create_taxonomy']);
        if (hitboox_is_cmb2_activated()) {
            add_action('init', [$this, 'setup_meta']);
        }

        add_action('cmb2_admin_init', [$this, 'platforms_register_taxonomy_meta']);
        add_action('cmb2_admin_init', [$this, 'genre_register_taxonomy_meta']);
        add_action('cmb2_admin_init', [$this, 'hitboox_register_theme_options_meta']);
        add_action('cmb2_admin_init', [$this, 'hitboox_register_custom_post_meta']);
        add_action('wp_enqueue_scripts', array($this, 'scripts'), 10);

        add_action('template_redirect', [$this, 'project_taxonomy_redirect']);
    }


    public function setup_meta()
    {
        add_action('cmb2_admin_init', [$this, 'meta_project']);
    }

    public function meta_project()
    {
        $cmb2 = new_cmb2_box(array(
            'id' => 'hitboox_project_setting',
            'title' => esc_html__('Infomation', 'hitboox'),
            'object_types' => array('hitboox_project'),
        ));

        $cmb2->add_field(array(
            'name' => esc_html__('Project Image', 'hitboox'),
            'id' => 'project_image_class',
            'type' => 'file',
            'options' => array(
                'url' => true,
            ),
        ));
    }


    function project_taxonomy_redirect()
    {
        $check_platform = is_tax('hitboox_project_platform');
        $check_genre = is_tax('hitboox_project_genre');
        if ($check_platform || $check_genre) {
            $term = get_queried_object();
            $slug = $term->slug;
            $param = $check_platform ? 'platforms' : 'genres';
            $url = get_post_type_archive_link('hitboox_project') . '?' . $param . '=' . $slug;
            wp_redirect($url);
            exit;
        }
    }


    public function scripts()
    {
        $suffix = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';
        if (is_post_type_archive('hitboox_project')) {
            wp_enqueue_script('hitboox-archive-project', get_template_directory_uri() . '/assets/js/frontend/project' . $suffix . '.js', array('jquery'), HITBOOX_VERSION, true);
        }
    }

    /**
     * @return void
     */
    public function create_projects()
    {

        $labels = array(
            'name' => esc_html__('Projects', 'hitboox'),
            'singular_name' => esc_html__('Project', 'hitboox'),
            'add_new' => esc_html__('Add New Project', 'hitboox'),
            'add_new_item' => esc_html__('Add New Project', 'hitboox'),
            'edit_item' => esc_html__('Edit Project', 'hitboox'),
            'new_item' => esc_html__('New Project', 'hitboox'),
            'view_item' => esc_html__('View Project', 'hitboox'),
            'search_items' => esc_html__('Search Projects', 'hitboox'),
            'not_found' => esc_html__('No Projects found', 'hitboox'),
            'not_found_in_trash' => esc_html__('No Projects found in Trash', 'hitboox'),
            'parent_item_colon' => esc_html__('Parent Project:', 'hitboox'),
            'menu_name' => esc_html__('Projects', 'hitboox'),
        );

        $labels = apply_filters('hitboox_project_labels', $labels);
        $slug_field = apply_filters('hitboox_project_slug', 'projects');

        hitboox_function_to_call('post_type', [$this->post_type,
            array(
                'labels' => $labels,
                'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
                'public' => true,
                'has_archive' => true,
                'rewrite' => array('slug' => $slug_field),
                'menu_position' => 5,
                'categories' => array(),
            )
        ]);
    }

    public function create_taxonomy()
    {
        $labels_platform = array(
            'name' => __('Platform', 'hitboox'),
            'singular_name' => __('Platform', 'hitboox'),
            'search_items' => __('Search Platform', 'hitboox'),
            'all_items' => __('All Platforms', 'hitboox'),
            'parent_item' => __('Parent Platform', 'hitboox'),
            'parent_item_colon' => __('Parent Platform:', 'hitboox'),
            'edit_item' => __('Edit Platform', 'hitboox'),
            'update_item' => __('Update Platform', 'hitboox'),
            'add_new_item' => __('Add New Platform', 'hitboox'),
            'new_item_name' => __('New Platform Name', 'hitboox'),
            'menu_name' => __('Platforms', 'hitboox'),
        );
        $labels_platform = apply_filters('hitboox_project_platform_labels', $labels_platform);
        $slug_platform_field = apply_filters('slug_project_platform', 'project-platform');
        $args_platform = array(
            'hierarchical' => true,
            'public' => false,
            'labels' => $labels_platform,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'show_in_nav_menus' => true,
            'rewrite' => array('slug' => $slug_platform_field)
        );

        hitboox_function_to_call('taxonomy',[$this->taxonomy_platform, array($this->post_type), $args_platform]);


        $labels_genre = array(
            'name' => __('Genre', 'hitboox'),
            'singular_name' => __('Genre', 'hitboox'),
            'search_items' => __('Search Genre', 'hitboox'),
            'all_items' => __('All Genres', 'hitboox'),
            'parent_item' => __('Parent Genre', 'hitboox'),
            'parent_item_colon' => __('Parent Genre:', 'hitboox'),
            'edit_item' => __('Edit Genre', 'hitboox'),
            'update_item' => __('Update Genre', 'hitboox'),
            'add_new_item' => __('Add New Genre', 'hitboox'),
            'new_item_name' => __('New Genre Name', 'hitboox'),
            'menu_name' => __('Genres', 'hitboox'),
        );
        $labels_genre = apply_filters('hitboox_project_genre_labels', $labels_genre);
        $slug_genre_field = apply_filters('slug_project_genre', 'project-genre');
        $args_genre = array(
            'hierarchical' => true,
            'public' => false,
            'labels' => $labels_genre,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'show_in_nav_menus' => true,
            'rewrite' => array('slug' => $slug_genre_field)
        );

        hitboox_function_to_call('taxonomy',[$this->taxonomy_genre, array($this->post_type), $args_genre]);
    }

    function Genre_register_taxonomy_meta()
    {
        $prefix = 'genre_term_';

        /**
         * Metabox to add fields to categories and tags
         */
        $cmb_term = new_cmb2_box(array(
            'id' => $prefix . 'edit',
            'title' => esc_html__('Genre Metabox', 'hitboox'),
            'object_types' => array('term'),
            'taxonomies' => array('hitboox_project_genre'),
        ));

        $cmb_term->add_field(array(
            'name' => esc_html__('Genre Color', 'hitboox'),
            'id' => $prefix . 'bg_color',
            'type' => 'colorpicker',
            'default' => '#5C47DE'
        ));

    }

    public function get_term_genre($post_id)
    {
        $terms = get_the_terms($post_id, $this->taxonomy_genre);
        $archive_link = apply_filters('hitboox_project_filters_archive_link', get_post_type_archive_link('hitboox_project'));
        $output = '';
        if (!is_wp_error($terms) && is_array($terms)) {
            $output .= '<div class="project-genres">';
            foreach ($terms as $key => $term) {
                $term_bg_color = get_term_meta($term->term_id, 'genre_term_bg_color', true);
                $name = $term->name;
                $term_link = $archive_link . '?&genres=' . $term->slug;
                $background_color = !empty($term_bg_color) ? $term_bg_color : '#5C47DE';
                $output .= '<a class="path-wrap-yes" href="' . esc_url($term_link) . '" style="background-color: ' . esc_attr($background_color) . ' ">' . $name . '</a>';
            }
            $output .= '</div>';
        }
        return $output;
    }

    /**
     *
     */

    function platforms_register_taxonomy_meta()
    {
        $prefix = 'platforms_term_';

        /**
         * Metabox to add fields to categories and tags
         */
        $cmb_term = new_cmb2_box(array(
            'id' => $prefix . 'edit',
            'title' => esc_html__('Platforms Metabox', 'hitboox'),
            'object_types' => array('term'),
            'taxonomies' => array('hitboox_project_platform'),
        ));

        $cmb_term->add_field(array(
            'name' => esc_html__('Icon Class', 'hitboox'),
            'id' => $prefix . 'icon',
            'type' => 'text',
            'default' => 'hitboox-icon-android'
        ));

    }

    public function get_term_platform($post_id)
    {
        $terms = get_the_terms($post_id, $this->taxonomy_platform);
        $output = '';
        if (!is_wp_error($terms) && is_array($terms)) {
            $output .= '<div class="project-platforms">';
            foreach ($terms as $key => $term) {
                $term_link = get_post_meta(get_the_ID(), 'hitboox_platform_' . $term->slug, true) ? get_post_meta(get_the_ID(), 'hitboox_platform_' . $term->slug, true) : '#';

                $term_icon = get_term_meta($term->term_id, 'platforms_term_icon', true);

                if (is_wp_error($term_link)) {
                    continue;
                }

                $name = !empty($term_icon) ? '<i class="' . esc_attr($term_icon) . '"></i>' : $term->name;
                $output .= '<a href="' . esc_url($term_link) . '" class="path-wrap-yes">' . $name . '</a>';
            }
            $output .= '</div>';
        }
        return $output;
    }


    public function hitboox_register_custom_post_meta()
    {
        $prefix = 'hitboox_platform_';

        $cmb = new_cmb2_box(array(
            'id' => $prefix . 'metabox',
            'title' => __('Platform Link', 'hitboox'),
            'object_types' => array('hitboox_project'),
            'context' => 'normal',
            'priority' => 'high',
            'show_names' => true,
        ));

        $args = array(
            'hide_empty' => false,
            'order' => 'ASC',
            'number' => 0,
        );
        $terms_platform = get_terms('hitboox_project_platform', $args);
        if (!is_wp_error($terms_platform) && !empty($terms_platform)) {
            foreach ($terms_platform as $term) {
                $cmb->add_field(array(
                    'name' => $term->name,
                    'id' => $prefix . $term->slug,
                    'type' => 'text_url',

                ));
            }
        }
    }

    public function hitboox_register_theme_options_meta()
    {

        /**
         * Registers options page menu item and form.
         */
        $cmb2 = new_cmb2_box(array(
            'id' => 'hitboox_project_archive',
            'title' => esc_html__('Projects Setting', 'hitboox'),
            'object_types' => array('options-page'),
            'option_key' => 'hitboox_project_archive',
            'position' => 8,
        ));

        $cmb2->add_field(array(
            'name' => esc_html__('Archive description', 'hitboox'),
            'id' => 'hitboox_project-content',
            'type' => 'wysiwyg',
        ));

        $cmb2->add_field(array(
            'name' => esc_html__('Archive width', 'hitboox'),
            'id' => 'archive_width',
            'type' => 'radio_inline',
            'options' => array(
                '' => esc_html__('Default width', 'hitboox'),
                'full' => esc_html__('Full width', 'hitboox'),
            ),
            'default' => '',
        ));

        $cmb2->add_field(array(
            'name' => esc_html__('Archive Style', 'hitboox'),
            'id' => 'archive_style',
            'type' => 'select',
            'show_option_none' => true,
            'default' => 'style-1',
            'options' => array(
                'style-1' => esc_html__('Style 1', 'hitboox'),
                'style-2' => esc_html__('Style 2', 'hitboox'),
                'style-3' => esc_html__('Style 3', 'hitboox'),
            ),
        ));

        $cmb2->add_field(array(
            'name' => esc_html__('Filter', 'hitboox'),
            'id' => 'filter_style',
            'type' => 'select',
            'show_option_none' => true,
            'default' => '',
            'options' => array(
                '' => esc_html__('None', 'hitboox'),
                'style-1' => esc_html__('Style 1', 'hitboox'),
                'style-2' => esc_html__('Style 2', 'hitboox'),
            ),
        ));

        $cmb2->add_field(array(
            'name' => esc_html__('Columns Desktop', 'hitboox'),
            'id' => 'columns_desktop',
            'type' => 'select',
            'show_option_none' => true,
            'default' => '3',
            'options' => array(
                '1' => '1',
                '2' => '2',
                '3' => '3',
            ),
        ));
        $cmb2->add_field(array(
            'name' => esc_html__('Columns Tablet', 'hitboox'),
            'id' => 'columns_tablet',
            'type' => 'select',
            'show_option_none' => true,
            'default' => '2',
            'options' => array(
                '1' => '1',
                '2' => '2',
                '3' => '3',
            ),
        ));

        $cmb2->add_field(array(
            'name' => esc_html__('Columns Mobile', 'hitboox'),
            'id' => 'columns_mobile',
            'type' => 'select',
            'show_option_none' => true,
            'default' => '1',
            'options' => array(
                '1' => '1',
                '2' => '2',
                '3' => '3',
            ),
        ));

        $cmb2->add_field(array(
            'name' => __('Gutter (px)', 'hitboox'),
            'id' => 'gutter',
            'type' => 'text',
            'default' => 30,
        ));

        $cmb2->add_field(array(
            'name' => __('Posts Per Page', 'hitboox'),
            'id' => 'posts_per_page',
            'type' => 'text',
            'default' => 10,
        ));
    }

}

Hitboox_Project::getInstance();
