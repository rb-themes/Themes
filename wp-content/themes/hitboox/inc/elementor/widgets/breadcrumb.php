<?php

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Hitboox_Elementor_Breadcrumb extends Elementor\Widget_Base {

    public function get_name() {
        return 'hitboox-breadcrumb';
    }

    public function get_title() {
        return esc_html__('Hitboox Breadcrumbs', 'hitboox');
    }

    public function get_icon() {
        return 'eicon-product-breadcrumbs';
    }

    public function get_keywords() {
        return ['breadcrumbs'];
    }

    public function get_categories() {
        return array('hitboox-addons');
    }

    protected function register_controls() {

        $this->start_controls_section(
            'section_product_rating_style',
            [
                'label' => esc_html__('Style Breadcrumbs', 'hitboox'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'wc_style_warning',
            [
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => esc_html__('The style of this widget is often affected by your theme and plugins. If you experience any such issue, try to switch to a basic theme and deactivate related plugins.', 'hitboox'),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label'     => esc_html__('Text Color', 'hitboox'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .breadcrumb-listItem' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'link_color',
            [
                'label'     => esc_html__('Link Color', 'hitboox'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .breadcrumb-listItem a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'link_color_hover',
            [
                'label'     => esc_html__('Link Color Hover', 'hitboox'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .breadcrumb-listItem a:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'text_typography',
                'selector' => '{{WRAPPER}} .breadcrumb span',
            ]
        );

        $this->add_responsive_control(
            'alignment',
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
                'selectors' => [
                    '{{WRAPPER}} .breadcrumb'     => 'text-align: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'listItem_padding',
            [
                'label'      => esc_html__('Padding', 'hitboox'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .breadcrumb-listItem' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'      => 'breadcrumb_listItem_border',
                'selector'  => '{{WRAPPER}} .breadcrumb-listItem',
            ]
        );

        $this->add_control(
            'display_list_item',
            [
                'label'        => esc_html__('Hidden List Item', 'hitboox'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'prefix_class' => 'hidden-hitboox-list-item-',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_product_rating_style_title',
            [
                'label' => esc_html__('Style Title', 'hitboox'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'alignment_title',
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
                'selectors' => [
                    '{{WRAPPER}} .hitboox-title' => 'text-align: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'text_color_title',
            [
                'label'     => esc_html__('Title Color', 'hitboox'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hitboox-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .hitboox-title',
            ]
        );

        $this->add_control(
            'display_title',
            [
                'label'        => esc_html__('Hidden Title', 'hitboox'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'prefix_class' => 'hidden-hitboox-title-',
            ]
        );

        $this->add_control(
            'display_title_single',
            [
                'label'        => esc_html__('Hidden Title Single', 'hitboox'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'prefix_class' => 'hidden-hitboox-title-single-'
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label'      => esc_html__('Margin', 'hitboox'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .hitboox-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->end_controls_section();
    }

    protected function render() {
        $setting              = $this->get_settings_for_display();
        $display_title_single = $setting['display_title_single'];
        $hide_list_item       = $setting['display_title_single'] == 'yes';
        $hide_allway          = $setting['display_title'] == 'yes';
        $hide_single          = (is_singular('post') || is_singular('product')) && $display_title_single == 'yes';
        ?>
        <div class="breadcrumb" typeof="BreadcrumbList" vocab="https://schema.org/">
            <?php
            if (!$hide_allway) {
                if (!$hide_single) {
                    ?>
                    <h1 class="hitboox-title"><?php
                        if (is_page() || is_single()) {
                            the_title();
                        } elseif (is_archive() && is_tax() && !is_category() && !is_tag()) {
                            $tax_object = get_queried_object();
                            echo esc_html($tax_object->name);
                        } elseif (is_category()) {
                            single_cat_title();
                        } elseif (is_home()) {
                            echo esc_html__('Our Blog', 'hitboox');
                        } elseif (is_404()) {
                            echo esc_html__('Error 404', 'hitboox');
                        } elseif (is_post_type_archive()) {
                            if (is_post_type_archive('hitboox_project')) {
                                echo esc_html__('Our Games', 'hitboox');
                            }
                            elseif (is_post_type_archive('product')) {
                                echo esc_html__('Our Shop', 'hitboox');
                            }else {
                                $tax_object = get_queried_object();
                                echo esc_html($tax_object->label);
                            }
                        } elseif (is_tag()) {
                            // Get tag information
                            $term_id  = get_query_var('tag_id');
                            $taxonomy = 'post_tag';
                            $args     = 'include=' . esc_attr($term_id);
                            $terms    = get_terms($taxonomy, $args);
                            // Display the tag name
                            if (isset($terms[0]->name)) {
                                echo esc_html($terms[0]->name);
                            }
                        } elseif (is_day()) {
                            echo esc_html__('Day Archives', 'hitboox');
                        } elseif (is_month()) {
                            echo get_the_time('F') . esc_html__(' Archives', 'hitboox');
                        } elseif (is_year()) {
                            echo get_the_time('Y') . esc_html__(' Archives', 'hitboox');
                        } elseif (is_search()) {
                            esc_html_e('Search Results', 'hitboox');
                        } elseif (is_author()) {
                            global $author;
                            if (!empty($author)) {
                                $usermetadata = get_userdata($author);
                                echo esc_html__('Author', 'hitboox') . ': ' . $usermetadata->display_name;
                            }
                        }
                        ?></h1>
                    <?php
                }
            }
            if (hitboox_is_bcn_nav_activated() && !$hide_list_item) {
                echo '<div class="breadcrumb-listItem">';
                bcn_display();
                echo '</div>';
            }
            ?>
        </div>
        <?php
    }
}

$widgets_manager->register(new Hitboox_Elementor_Breadcrumb());
