<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

/**
 * Class Hitboox_Elementor_Blog
 */
class Hitboox_Elementor_Post_Grid extends Hitboox_Base_Widgets_Swiper {

    public function get_name() {
        return 'hitboox-post-grid';
    }

    public function get_title() {
        return esc_html__('Posts Grid', 'hitboox');
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
        return ['hitboox-elementor-posts-grid', 'hitboox-elementor-swiper'];
    }

    public function on_export($element) {
        unset($element['settings']['categories']);
        unset($element['settings']['tag']);

        return $element;
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_query',
            [
                'label' => esc_html__('Query', 'hitboox'),
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
            'advanced',
            [
                'label' => esc_html__('Advanced', 'hitboox'),
                'type'  => Controls_Manager::HEADING,
            ]
        );



        $this->add_control(
            'includes_ids',
            [
                'label'       => esc_html__('Includes', 'hitboox'),
                'type'        => Controls_Manager::SELECT2,
                'label_block' => true,
                'multiple'    => true,
                'options'     => $this->get_posts_options(), // Function to fetch posts
            ]
        );

        $this->add_control(
            'excludes_ids',
            [
                'label'       => esc_html__('Excludes', 'hitboox'),
                'type'        => Controls_Manager::SELECT2,
                'label_block' => true,
                'multiple'    => true,
                'options'     => $this->get_posts_options(), // Function to fetch posts
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

        $this->add_control(
            'categories',
            [
                'label'       => esc_html__('Categories', 'hitboox'),
                'type'        => Controls_Manager::SELECT2,
                'options'     => $this->get_post_categories(),
                'label_block' => true,
                'multiple'    => true,
            ]
        );

        $this->add_control(
            'cat_operator',
            [
                'label'     => esc_html__('Category Operator', 'hitboox'),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'IN',
                'options'   => [
                    'AND'    => esc_html__('AND', 'hitboox'),
                    'IN'     => esc_html__('IN', 'hitboox'),
                    'NOT IN' => esc_html__('NOT IN', 'hitboox'),
                ],
                'condition' => [
                    'categories!' => ''
                ],
            ]
        );

        $this->add_control(
            'tags',
            [
                'label'       => esc_html__('Tags', 'hitboox'),
                'type'        => Controls_Manager::SELECT2,
                'options'     => $this->get_post_tags(),
                'label_block' => true,
                'multiple'    => true,
            ]
        );

        $this->add_control(
            'tag_operator',
            [
                'label'     => esc_html__('Tags Operator', 'hitboox'),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'IN',
                'options'   => [
                    'AND'    => esc_html__('AND', 'hitboox'),
                    'IN'     => esc_html__('IN', 'hitboox'),
                    'NOT IN' => esc_html__('NOT IN', 'hitboox'),
                ],
                'condition' => [
                    'tags!' => ''
                ],
            ]
        );

        $this->add_control(
            'layout',
            [
                'label' => esc_html__('Layout', 'hitboox'),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'post_style',
            [
                'label'        => esc_html__('Style', 'hitboox'),
                'type'         => \Elementor\Controls_Manager::SELECT,
                'options'      => [
                    'post-style-1' => esc_html__('Style Grid', 'hitboox'),
                    'post-modern' => esc_html__('Style Modern', 'hitboox'),
                    'post-list' => esc_html__('Style List', 'hitboox'),
                    'post-list1' => esc_html__('Style List1', 'hitboox'),
                ],
                'default'      => 'post-style-1',
            ]
        );

        $this->add_control(
            'modern_special',
            [
                'label'     => esc_html__('Modern Special', 'hitboox'),
                'type'      => Controls_Manager::SWITCHER,
                'condition' => [
                        'post_style' => 'post-modern'
                ],
                'prefix_class' => 'modern-special-',
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
                'conditions'   => [
                    'relation' => 'and',
                    'terms'    => [
                        [
                            'name'     => 'post_style',
                            'operator' => '!==',
                            'value'    => 'post-list',
                        ],
                        [
                            'name'     => 'post_style',
                            'operator' => '!==',
                            'value'    => 'post-list1',
                        ],
                        [
                            'name'     => 'modern_special',
                            'operator' => '!==',
                            'value'    => 'yes',
                        ],
                        [
                            'name'     => 'enable_carousel',
                            'operator' => '!==',
                            'value'    => 'yes',
                        ],
                    ],
                ]
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
                ],
                'conditions'   => [
                    'relation' => 'and',
                    'terms'    => [
                        [
                            'name'     => 'post_style',
                            'operator' => '!==',
                            'value'    => 'post-list',
                        ],
                        [
                            'name'     => 'post_style',
                            'operator' => '!==',
                            'value'    => 'post-list1',
                        ],
                        [
                            'name'     => 'modern_special',
                            'operator' => '!==',
                            'value'    => 'yes',
                        ],
                        [
                            'name'     => 'enable_carousel',
                            'operator' => '!==',
                            'value'    => 'yes',
                        ],
                    ],
                ]
            ]
        );

        $this->add_control(
            'enable_carousel',
            [
                'label'     => esc_html__('Enable Carousel', 'hitboox'),
                'type'      => Controls_Manager::SWITCHER,
                'conditions'   => [
                    'relation' => 'and',
                    'terms'    => [
                        [
                            'name'     => 'post_style',
                            'operator' => '!==',
                            'value'    => 'post-list',
                        ],
                        [
                            'name'     => 'post_style',
                            'operator' => '!==',
                            'value'    => 'post-list1',
                        ],
                        [
                            'name'     => 'modern_special',
                            'operator' => '!==',
                            'value'    => 'yes',
                        ],

                    ],
                ]
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
            'pagination_numbers_shorten',
            [
                'label'     => esc_html__('Shorten', 'hitboox'),
                'type'      => Controls_Manager::SWITCHER,
                'default'   => '',
                'condition' => [
                    'pagination_type' => [
                        'numbers',
                        'numbers_and_prev_next',
                    ],
                ],
            ]
        );

        $this->add_control(
            'pagination_prev_label',
            [
                'label'     => esc_html__('Previous Label', 'hitboox'),
                'default'   => esc_html__('&laquo; Previous', 'hitboox'),
                'condition' => [
                    'pagination_type' => [
                        'prev_next',
                        'numbers_and_prev_next',
                    ],
                ],
            ]
        );

        $this->add_control(
            'pagination_next_label',
            [
                'label'     => esc_html__('Next Label', 'hitboox'),
                'default'   => esc_html__('Next &raquo;', 'hitboox'),
                'condition' => [
                    'pagination_type' => [
                        'prev_next',
                        'numbers_and_prev_next',
                    ],
                ],
            ]
        );

        $this->add_control(
            'pagination_align',
            [
                'label'     => esc_html__('Alignment', 'hitboox'),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'   => [
                        'title' => esc_html__('Left', 'hitboox'),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'hitboox'),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'  => [
                        'title' => esc_html__('Right', 'hitboox'),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'default'   => 'center',
                'selectors' => [
                    '{{WRAPPER}} .elementor-pagination' => 'justify-content: {{VALUE}};',
                ],
                'condition' => [
                    'pagination_type!' => '',
                ],
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'item_style',
            [
                'label' => esc_html__('Item', 'hitboox'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'item_padding',
            [
                'label' => esc_html__('Padding', 'hitboox'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .grid-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );


        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'item_border',
                'selector' => '{{WRAPPER}} .grid-item',
                'separator' => 'before',
            ]
        );


        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'item_shadow',
                'selector' => '{{WRAPPER}} .grid-item',
            ]
        );

        $this->add_control(
            'item_bg_color',
            [
                'label' => esc_html__( 'Background Color', 'hitboox' ),
                'type' => Controls_Manager::COLOR,

                'selectors' => [
                    '{{WRAPPER}} .grid-item' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'content_style',
            [
                'label' => esc_html__('Content', 'hitboox'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => esc_html__('Padding', 'hitboox'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .post-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'image_style',
            [
                'label' => esc_html__('Image', 'hitboox'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'height',
            [
                'label' => esc_html__( 'Height', 'hitboox' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem', 'vh', 'custom' ],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 1000,
                    ],
                    'vh' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .post-thumbnail img, {{WRAPPER}}.elementor-post-modern .post-content' => 'height: {{SIZE}}{{UNIT}}',
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
                    '{{WRAPPER}} .grid-item .post-thumbnail' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'category_style',
            [
                'label' => esc_html__('Category', 'hitboox'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'cat_color_tabs' );

        $this->start_controls_tab( 'cat_colors_normal',
            [
                'label' => esc_html__( 'Normal', 'hitboox' ),
            ]
        );
        $this->add_control(
            'cat_color',
            [
                'label'     => esc_html__('Color', 'hitboox'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .entry-meta .categories-link a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'cat_colors_hover',
            [
                'label' => esc_html__( 'Hover', 'hitboox' ),
            ]
        );
        $this->add_control(
            'cat_color_hover',
            [
                'label'     => esc_html__('Color', 'hitboox'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .entry-meta .categories-link a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'title_style',
            [
                'label' => esc_html__('Title', 'hitboox'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .grid-item .entry-title',
            ]
        );

        $this->start_controls_tabs( 'title_color_tabs' );

        $this->start_controls_tab( 'title_colors_normal',
            [
                'label' => esc_html__( 'Normal', 'hitboox' ),
            ]
        );
        $this->add_control(
            'title_color',
            [
                'label'     => esc_html__('Color', 'hitboox'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .entry-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'title_colors_hover',
            [
                'label' => esc_html__( 'Hover', 'hitboox' ),
            ]
        );
        $this->add_control(
            'title_color_hover',
            [
                'label'     => esc_html__('Color', 'hitboox'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .entry-title a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'meta_style',
            [
                'label' => esc_html__('Meta', 'hitboox'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'meta_typography',
                'selector' => '{{WRAPPER}} .grid-item .entry-meta',
            ]
        );


        $this->add_responsive_control(
            'meta_margin',
            [
                'label' => esc_html__('Margin', 'hitboox'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .grid-item .entry-meta' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );


        $this->start_controls_tabs( 'meta_color_tabs' );

        $this->start_controls_tab( 'meta_colors_normal',
            [
                'label' => esc_html__( 'Normal', 'hitboox' ),
            ]
        );
        $this->add_control(
            'meta_color',
            [
                'label'     => esc_html__('Color', 'hitboox'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .entry-meta, {{WRAPPER}} .entry-meta a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'meta_colors_hover',
            [
                'label' => esc_html__( 'Hover', 'hitboox' ),
            ]
        );
        $this->add_control(
            'meta_color_hover',
            [
                'label'     => esc_html__('Color', 'hitboox'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .entry-meta a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();



        $this->start_controls_section(
            'readmore_style',
            [
                'label' => esc_html__('Read More', 'hitboox'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'readmore_color_tabs' );

        $this->start_controls_tab( 'readmore_colors_normal',
            [
                'label' => esc_html__( 'Normal', 'hitboox' ),
            ]
        );
        $this->add_control(
            'readmore_color',
            [
                'label'     => esc_html__('Color', 'hitboox'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .more-link-wrap a.more-link' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'readmore_colors_hover',
            [
                'label' => esc_html__( 'Hover', 'hitboox' ),
            ]
        );
        $this->add_control(
            'readmore_color_hover',
            [
                'label'     => esc_html__('Color', 'hitboox'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .more-link-wrap a.more-link:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->add_control_carousel(['enable_carousel' => 'yes','post_style!' => 'post-list']);

    }

    protected function get_post_tags() {
        $tags    = get_terms(array(
                'taxonomy'   => 'post_tag',
                'hide_empty' => false,
            )
        );
        $results = array();
        if (!is_wp_error($tags)) {
            foreach ($tags as $tag) {
                $results[$tag->slug] = $tag->name;
            }
        }
        return $results;
    }

    protected function get_post_categories() {
        $categories = get_terms(array(
                'taxonomy'   => 'category',
                'hide_empty' => false,
            )
        );
        $results    = array();
        if (!is_wp_error($categories)) {
            foreach ($categories as $category) {
                $results[$category->slug] = $category->name;
            }
        }
        return $results;
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $query = $this->query_posts();

        if (!$query->found_posts) {
            return;
        }

        $this->add_render_attribute('wrapper', 'class', 'elementor-post-wrapper');

        $this->add_render_attribute('wrapper', 'class', 'elementor-' . esc_attr($settings['post_style']));
        $this->get_data_elementor_columns();
        $style1 = $settings['post_style'] === 'post-style-1';
        $style_modern = $settings['post_style'] === 'post-modern';
        ?>
        <div <?php $this->print_render_attribute_string('wrapper'); ?>>
        <?php if( $style1 || $style_modern){ ?>
            <div <?php $this->print_render_attribute_string('row'); ?>>
                <?php }
                $allowed_styles = [
                    'post-style-1',
                    'post-modern',
                    'post-list',
                    'post-list1'
                ];
                $style = $settings['post_style'] ?? 'post-style-1';

                if ( ! in_array( $style, $allowed_styles, true ) ) {
                    $style = 'post-style-1';
                }

                while ($query->have_posts()) {
                    $query->the_post();
                    ?>
                    <?php if($style1 || $style_modern){ ?>
                        <div <?php $this->print_render_attribute_string('item'); ?>>
                    <?php } ?>
                    <?php get_template_part('template-parts/posts-grid/item' , $style);?>
                    <?php if($style1 || $style_modern){ ?>
                        </div>
                        <?php } ?>
                    <?php }
                ?>
        <?php if( $style1 || $style_modern){ ?>
            </div>
        <?php } ?>
            <?php $this->render_swiper_pagination_navigation(); ?>
            <?php if ($settings['pagination_type'] && !empty($settings['pagination_type'])): ?>
                 <?php $this->render_loop_footer(); ?>
            <?php endif; ?>
        </div>
        <?php

        wp_reset_postdata();

    }

    public function query_posts() {
        $query_args = $this->get_query_args($this->get_settings());
        return new WP_Query($query_args);
    }

    public static function get_query_args($settings) {
        $query_args = [
            'post_type'           => 'post',
            'orderby'             => $settings['orderby'],
            'order'               => $settings['order'],
            'ignore_sticky_posts' => 1,
            'post__in'            => $settings['includes_ids'], // Includes selected posts
            'post__not_in'        => $settings['excludes_ids'], // Excludes selected posts
            'post_status'         => 'publish', // Hide drafts/private posts for admins
        ];

        if (!empty($settings['categories'])) {
            $categories = array();
            foreach ($settings['categories'] as $category) {
                $cat = get_term_by('slug', $category, 'category');
                if (!is_wp_error($cat) && is_object($cat)) {
                    $categories[] = $cat->term_id;
                }
            }

            if ($settings['cat_operator'] == 'AND') {
                $query_args['category__and'] = $categories;
            } elseif ($settings['cat_operator'] == 'IN') {
                $query_args['category__in'] = $categories;
            } else {
                $query_args['category__not_in'] = $categories;
            }
        }

        if (!empty($settings['tags'])) {
            $tags = array();
            foreach ($settings['tags'] as $tag) {
                $tag = get_term_by('slug', $tag, 'post_tag');
                if (!is_wp_error($tag) && is_object($tag)) {
                    $tags[] = $tag->term_id;
                }
            }

            if ($settings['tag_operator'] == 'AND') {
                $query_args['tag__and'] = $tags;
            } elseif ($settings['tag_operator'] == 'IN') {
                $query_args['tag__in'] = $tags;
            } else {
                $query_args['tag__not_in'] = $tags;
            }
        }

        $query_args['posts_per_page'] = $settings['posts_per_page'];
        if($settings['pagination_type'] != ''){
            if (is_front_page()) {
                $query_args['paged'] = (get_query_var('page')) ? get_query_var('page') : 1;
            } else {
                $query_args['paged'] = (get_query_var('paged')) ? get_query_var('paged') : 1;
            }
        }

        return $query_args;
    }

    public function get_posts_options() {
        $posts = get_posts([
            'post_type'      => 'post', // Only retrieve regular posts
            'posts_per_page' => -1,     // Get all posts
            'post_status'    => 'publish', // Only published posts
        ]);

        $options = [];
        foreach ($posts as $post) {
            $options[$post->ID] = $post->post_title; // Use the post ID as the key and title as the label
        }

        return $options;
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

        $has_numbers   = in_array($parent_settings['pagination_type'], ['numbers', 'numbers_and_prev_next']);
        $has_prev_next = in_array($parent_settings['pagination_type'], ['prev_next', 'numbers_and_prev_next']);

        $links = [];

        if ($has_numbers) {
            $links = paginate_links([
                'type'               => 'array',
                'current'            => $this->get_current_page(),
                'total'              => $page_limit,
                'prev_next'          => false,
                'show_all'           => 'yes' !== $parent_settings['pagination_numbers_shorten'],
                'before_page_number' => '<span class="elementor-screen-only">' . esc_html__('Page', 'hitboox') . '</span>',
            ]);
        }

        if ($has_prev_next) {
            $prev_next = $this->get_posts_nav_link($page_limit);
            array_unshift($links, $prev_next['prev']);
            $links[] = $prev_next['next'];
        }

        ?>
        <div class="pagination">
            <nav class="elementor-pagination" role="navigation"
                 aria-label="<?php esc_attr_e('Pagination', 'hitboox'); ?>">
                <?php echo implode(PHP_EOL, $links); ?>
            </nav>
        </div>
        <?php
    }

    public function get_current_page() {
        if ('' === $this->get_settings('pagination_type')) {
            return 1;
        }

        return max(1, get_query_var('paged'), get_query_var('page'));
    }

    public function get_posts_nav_link($page_limit = null) {
        if (!$page_limit) {
            $page_limit = $this->query_posts()->max_num_pages;
        }

        $return = [];

        $paged = $this->get_current_page();

        $link_template     = '<a class="page-numbers %s" href="%s">%s</a>';
        $disabled_template = '<span class="page-numbers %s">%s</span>';

        if ($paged > 1) {
            $next_page = intval($paged) - 1;
            if ($next_page < 1) {
                $next_page = 1;
            }

            $return['prev'] = sprintf($link_template, 'prev', get_pagenum_link($next_page), $this->get_settings('pagination_prev_label'));
        } else {
            $return['prev'] = sprintf($disabled_template, 'prev', $this->get_settings('pagination_prev_label'));
        }

        $next_page = intval($paged) + 1;

        if ($next_page <= $page_limit) {
            $return['next'] = sprintf($link_template, 'next', get_pagenum_link($next_page), $this->get_settings('pagination_next_label'));
        } else {
            $return['next'] = sprintf($disabled_template, 'next', $this->get_settings('pagination_next_label'));
        }

        return $return;
    }

}

$widgets_manager->register(new Hitboox_Elementor_Post_Grid());
