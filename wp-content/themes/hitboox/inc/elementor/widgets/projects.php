<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;


/**
 * Class Hitboox_Elementor_Project
 */
class Hitboox_Elementor_Project extends Hitboox_Base_Widgets_Swiper {

    public function get_name() {
        return 'hitboox-projects';
    }

    public function get_title() {
        return esc_html__('Hitboox Project', 'hitboox');
    }

    /**
     * Get widget icon.
     *
     * Retrieve testimonial widget icon.
     *
     * @return string Widget icon.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_icon() {
        return 'eicon-posts-grid';
    }

    public function get_categories() {
        return array('hitboox-addons');
    }

    public function get_script_depends() {
        return ['hitboox-elementor-projects', 'hitboox-elementor-swiper'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_query',
            [
                'label' => esc_html__('Project', 'hitboox'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label'   => esc_html__('Posts Per Page', 'hitboox'),
                'type'    => Controls_Manager::NUMBER,
                'default' => 6,
            ]
        );


        $this->add_control(
            'platforms',
            [
                'label'    => __('Include Platforms', 'hitboox'),
                'type'     => Controls_Manager::SELECT2,
                'options'  => $this->get_project_platform(),
                'multiple' => true,
            ]
        );

        $this->add_control(
            'genres',
            [
                'label'    => __('Include Genres', 'hitboox'),
                'type'     => Controls_Manager::SELECT2,
                'options'  => $this->get_project_genre(),
                'multiple' => true,
            ]
        );

        $this->add_control(
            'includes_ids',
            [
                'label'       => esc_html__('Includes', 'hitboox'),
                'type'         => 'hitboox_query',
                'autocomplete' => [
                    'object' => 'hitboox_project',
                ],
                'label_block' => true,
                'multiple'    => true,
            ]
        );

        $this->add_control(
            'excludes_ids',
            [
                'label'       => esc_html__('Excludes', 'hitboox'),
                'type'         => 'hitboox_query',
                'autocomplete' => [
                    'object' => 'hitboox_project',
                ],
                'label_block' => true,
                'multiple'    => true,
            ]
        );

        $this->add_control(
            'project_style',
            [
                'label'        => esc_html__('Style', 'hitboox'),
                'type'         => \Elementor\Controls_Manager::SELECT,
                'options'      => [
                    'project-style-1' => esc_html__('Style 1', 'hitboox'),
                    'project-style-2' => esc_html__('Style 2', 'hitboox'),
                    'project-style-3' => esc_html__('Style 3', 'hitboox'),
                ],
                'render_type'  => 'template',
                'default'      => 'project-style-1',
                'prefix_class' => 'elementor-'
            ]
        );

        $this->add_control(
            'enable_style_effect',
            [
                'label'        => esc_html__('Enable Style Effect', 'hitboox'),
                'type'         => Controls_Manager::SWITCHER,
                'conditions'   => [
                    'relation' => 'or',
                    'terms'    => [
                        [
                            'name'     => 'project_style',
                            'operator' => '==',
                            'value'    => 'project-style-1',
                        ],
                        [
                            'name'     => 'project_style',
                            'operator' => '==',
                            'value'    => 'project-style-3',
                        ],
                    ],
                ],
                'prefix_class' => 'elementor-style-effect-'
            ]
        );

        $this->add_responsive_control(
            'effect_offset',
            [
                'label'     => esc_html__('Effect Offset', 'hitboox'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                    ],
                ],
                'conditions' => [
                    'relation' => 'and',
                    'terms'    => [
                        [
                            'name'     => 'enable_style_effect',
                            'operator' => '==',
                            'value'    => 'yes',
                        ],
                        [
                            'name'     => 'project_style',
                            'operator' => '==',
                            'value'    => 'project-style-1',
                        ],
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--offset: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label'   => esc_html__('Order By', 'hitboox'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'post_date',
                'options' => [
                    'post_date'  => esc_html__('Date', 'hitboox'),
                    'post_title' => esc_html__('Title', 'hitboox'),
                    'menu_order' => esc_html__('Menu Order', 'hitboox'),
                    'rand'       => esc_html__('Random', 'hitboox'),
                ],
            ]
        );

        $this->add_control(
            'order',
            [
                'label'   => esc_html__('Order', 'hitboox'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'desc',
                'options' => [
                    'asc'  => esc_html__('ASC', 'hitboox'),
                    'desc' => esc_html__('DESC', 'hitboox'),
                ],
            ]
        );

        $this->add_responsive_control(
            'column',
            [
                'label'      => esc_html__('Columns', 'hitboox'),
                'type'       => \Elementor\Controls_Manager::SELECT,
                'default'    => 3,
                'options'    => [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5],
                'selectors'  => [
                    '{{WRAPPER}} .d-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr)',
                ],
                'condition'  => ['enable_carousel!' => 'yes']
            ]
        );

        $this->add_responsive_control(
            'item_spacing',
            [
                'label'      => esc_html__('Spacing', 'hitboox'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default'    => [
                    'size' => 30
                ],
                'size_units' => ['px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .d-grid' => 'grid-gap:{{SIZE}}{{UNIT}}',
                    '{{WRAPPER}}.elementor-style-effect-yes.elementor-project-style-3  .d-grid' => 'margin-bottom:{{SIZE}}{{UNIT}}',
                ],
                'condition'  => ['enable_carousel!' => 'yes']
            ]
        );

        $this->add_control(
            'enable_carousel',
            [
                'label'     => esc_html__('Enable Carousel', 'hitboox'),
                'type'      => Controls_Manager::SWITCHER,
                'condition' => ['enable_style_effect!' => 'yes']
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_pagination',
            [
                'label'     => esc_html__('Pagination', 'hitboox'),
                'condition' => ['enable_carousel!' => 'yes']
            ]

        );

        $this->add_control(
            'pagination_type',
            [
                'label'   => esc_html__('Pagination', 'hitboox'),
                'type'    => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    ''                      => esc_html__('None', 'hitboox'),
                    'numbers'               => esc_html__('Numbers', 'hitboox'),
                    'prev_next'             => esc_html__('Previous/Next', 'hitboox'),
                    'numbers_and_prev_next' => esc_html__('Numbers', 'hitboox') . ' + ' . esc_html__('Previous/Next', 'hitboox'),
                ],
            ]
        );

        $this->add_control(
            'pagination_page_limit',
            [
                'label'     => esc_html__('Page Limit', 'hitboox'),
                'default'   => '5',
                'condition' => [
                    'pagination_type!' => '',
                ],
            ]
        );

        $this->add_control(
            'pagination_align',
            [
                'label'     => esc_html__('Alignment', 'hitboox'),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'flex-start' => [
                        'title' => esc_html__('Left', 'hitboox'),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center'     => [
                        'title' => esc_html__('Center', 'hitboox'),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'flex-end'   => [
                        'title' => esc_html__('Right', 'hitboox'),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'default'   => 'center',
                'selectors' => [
                    '{{WRAPPER}} ul.page-numbers' => 'justify-content: {{VALUE}};',
                ],
                'condition' => [
                    'pagination_type!' => '',
                ],
            ]
        );

        $this->end_controls_section();

        $this->add_control_carousel([
            'relation' => 'and',
            'terms'    => [
                [
                    'name'     => 'enable_carousel',
                    'operator' => '==',
                    'value'    => 'yes',
                ],
                [
                    'name'     => 'enable_style_effect',
                    'operator' => '!==',
                    'value'    => 'yes',
                ],
            ],
        ], 'conditions');

    }

    public static function get_query_args($settings) {
        $query_args = [
            'post_type'           => 'hitboox_project',
            'orderby'             => $settings['orderby'],
            'order'               => $settings['order'],
            'ignore_sticky_posts' => 1,
            'post__in'            => $settings['includes_ids'],
            'post__not_in'        => $settings['excludes_ids'],
            'post_status'         => 'publish',
        ];

        $query_args['posts_per_page'] = $settings['posts_per_page'];
        if (!empty($settings['platforms']) || !empty($settings['genres'])) {
            $query_args['tax_query'] = [];
        }
        if (!empty($settings['platforms']) && !empty($settings['genres'])) {
            $query_args['tax_query']['relation'] = 'AND';
        }
        if (!empty($settings['platforms'])) {
            $query_args['tax_query'][] = [
                'taxonomy' => 'hitboox_project_platform',
                'field'    => 'slug',
                'terms'    => $settings['platforms'],
                'operator' => 'IN'
            ];
        }

        if (!empty($settings['genres'])) {
            $query_args['tax_query'][] = [
                'taxonomy' => 'hitboox_project_genre',
                'field'    => 'slug',
                'terms'    => $settings['genres'],
                'operator' => 'IN'
            ];
        }

        if (is_front_page()) {
            $query_args['paged'] = (get_query_var('page')) ? get_query_var('page') : 1;
        } else {
            $query_args['paged'] = (get_query_var('paged')) ? get_query_var('paged') : 1;
        }

        return $query_args;
    }

    public function query_posts() {
        $query_args = $this->get_query_args($this->get_settings());
        return new WP_Query($query_args);
    }


    protected function render() {
        $settings = $this->get_settings_for_display();

        $this->add_render_attribute('wrapper', 'class', 'elementor-project-wrapper');

        $this->get_data_elementor_columns();
        $style = $settings['project_style'];
        $allowed_styles = [
            'project-style-1',
            'project-style-2',
            'project-style-3',
        ];

        $style = $settings['project_style'] ?? 'project-style-1';

        if ( ! in_array( $style, $allowed_styles, true ) ) {
            $style = 'project-style-1';
        }
        $query = $this->query_posts();
        if (!$query->found_posts) {
            return;
        }
        $columns = $this->get_settings_for_display('column');
        $enable_style_effect = $this->get_settings_for_display('enable_style_effect');
        $project_style = $this->get_settings_for_display('project_style');

        if ($enable_style_effect === 'yes' && $project_style === 'project-style-1') {
            $this->add_render_attribute(
                'row',
                'style',
                '--per_page: ' .$this->get_settings_for_display('posts_per_page') . ';'  // Set the index value dynamically based on $count
            );
        }

        ?>
        <div <?php $this->print_render_attribute_string('wrapper'); ?>>
            <?php
            $current_column = 0;
            ?>
            <div <?php $this->print_render_attribute_string('row'); ?>>
                <?php
                while ($query->have_posts()) {
                $query->the_post();

                ?>
                    <div <?php $this->print_render_attribute_string('item'); ?>>
                        <?php include get_theme_file_path('template-parts/project/item-' . $style . '.php'); ?>
                    </div>
                <?php

                $current_column++;

                if ($enable_style_effect === 'yes' && $project_style === 'project-style-3' && $current_column >= $columns) {
                $current_column = 0;
                ?>
            </div>
            <div <?php $this->print_render_attribute_string('row'); ?>>
                <?php
                }
                }
                ?>
            </div>
        </div>
        <?php $this->render_swiper_pagination_navigation();
        if ($settings['pagination_type'] && !empty($settings['pagination_type'])) {
            $this->render_loop_footer();
        }
        wp_reset_postdata();

    }

    protected function get_project_platform() {
        $platforms = get_terms(array(
                'taxonomy'   => 'hitboox_project_platform',
                'hide_empty' => false,
            )
        );
        $results   = array();
        if (!is_wp_error($platforms)) {
            foreach ($platforms as $platform) {
                $results[$platform->slug] = $platform->name;
            }
        }
        return $results;

    }

    protected function get_project_genre() {
        $genres  = get_terms(array(
                'taxonomy'   => 'hitboox_project_genre',
                'hide_empty' => false,
            )
        );
        $results = array();
        if (!is_wp_error($genres)) {
            foreach ($genres as $genre) {
                $results[$genre->slug] = $genre->name;
            }
        }
        return $results;

    }

    protected function render_loop_footer() {

        $parent_settings = $this->get_settings();
        if ('' === $parent_settings['pagination_type']) {
            return;
        }

        $page_limit = $this->query_posts()->max_num_pages;
        if ('' !== $parent_settings['pagination_page_limit']) {
            $page_limit = min($parent_settings['pagination_page_limit'], $page_limit);
        }

        if (2 > $page_limit) {
            return;
        }

        $this->add_render_attribute('pagination', 'class', 'elementor-pagination');

        $has_numbers = in_array($parent_settings['pagination_type'], ['numbers', 'numbers_and_prev_next']);

        $links = [];

        if ($has_numbers) {
            $links = paginate_links([
                'type'               => 'list',
                'current'            => $this->get_current_page(),
                'total'              => $page_limit,
                'prev_text'          => '<i class="hitboox-icon-chevron-left"></i>',
                'next_text'          => '<i class="hitboox-icon-chevron-right"></i>',
                'before_page_number' => '<span class="elementor-screen-only">' . esc_html__('Page', 'hitboox') . '</span>',
            ]);
        }

        ?>
        <nav class="pagination">
            <div class="nav-links">
                <?php printf('%s', $links); ?>
            </div>
        </nav>
        <?php
    }

    public function get_current_page() {
        if ('' === $this->get_settings('pagination_type')) {
            return 1;
        }

        return max(1, get_query_var('paged'), get_query_var('page'));
    }

}

$widgets_manager->register(new Hitboox_Elementor_Project());