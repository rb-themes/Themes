<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Hitboox_Elementor_HotSpots extends Widget_Base {

    public function get_name() {
        return 'hitboox-hotspot';
    }

    public function get_title() {
        return esc_html__('Hitboox Hot Spots', 'hitboox');
    }

    public function get_icon() {
        return 'eicon-hotspot';
    }

    public function get_categories() {
        return array('hitboox-addons');
    }

    protected function register_controls() {


        $this->start_controls_section('hotspot_image_section',
            [
                'label' => esc_html__('Image', 'hitboox'),
            ]
        );

        $this->add_control('hotspot_image',
            [
                'label'       => __('Choose Image', 'hitboox'),
                'type'        => Controls_Manager::MEDIA,
                'default'     => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'label_block' => true
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'    => 'background_image', // Actually its `image_size`.
                'default' => 'full'
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section('image_hotspot_icons_settings',
            [
                'label' => esc_html__('Hotspots', 'hitboox'),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'hotspot_address',
            [
                'label'   => esc_html__('Address', 'hitboox'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'hotspot_city',
            [
                'label'   => esc_html__('City', 'hitboox'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $repeater->add_responsive_control(
                'hotspot_main_icons_horizontal_position',
            [
                'label'      => esc_html__('Horizontal Position', 'hitboox'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 20,
                    ],
                ],
                'default'    => [
                    'size' => 50,
                    'unit' => '%'
                ],
                'selectors'  => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'left: {{SIZE}}{{UNIT}}'
                ]
            ]
        );

        $repeater->add_responsive_control(
                'hotspot_main_icons_vertical_position',
            [
                'label'      => esc_html__('Vertical Position', 'hitboox'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 20,
                    ],
                ],
                'default'    => [
                    'size' => 50,
                    'unit' => '%'
                ],
                'selectors'  => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'top: {{SIZE}}{{UNIT}}'
                ]
            ]
        );

        $this->add_control('image_hotspot_icons',
            [
                'label'  => esc_html__('Hotspots', 'hitboox'),
                'type'   => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section('image_lookbook_image_style_settings',
            [
                'label' => esc_html__('Main Image', 'hitboox'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'height',
            [
                'label'      => esc_html__('Height', 'hitboox'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem', 'vh', 'custom'],
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
                'selectors'  => [
                    '{{WRAPPER}} .hitboox-addons-image-lookbook-ib-img' => 'min-height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_lookbook_image_padding',
            [
                'label'      => esc_html__('Padding', 'hitboox'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .hitboox-image-lookbook-container .hitboox-addons-image-lookbook-ib-img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'image_lookbook_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'hitboox'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .hitboox-image-lookbook-container .hitboox-addons-image-lookbook-ib-img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section('image_lookbook_hotspots_style_settings',
            [
                'label' => esc_html__('Hotspots', 'hitboox'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'hotspots_width',
            [
                'label'      => esc_html__('Width', 'hitboox'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'custom'],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                    ],
                    '%'  => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .hotspot-dots' => 'width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'hotspots_height',
            [
                'label'      => esc_html__('Height', 'hitboox'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'custom'],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                    ],
                    '%'  => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .hotspot-dots' => 'height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'hotspots_bg',
            [
                'label'     => esc_html__('Background Color', 'hitboox'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .hotspot-dots' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .hotspot-dots:after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section('image_lookbook_text_style_settings',
            [
                'label' => esc_html__('Text', 'hitboox'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'text_position',
            [
                'label'      => esc_html__('Position', 'hitboox'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'custom'],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                    ],
                    '%'  => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .hotspot-item .hotspot-text' => 'bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} .hotspot-item .hotspot-text',
            ]
        );

        $this->add_control(
            'address_color',
            [
                'label' => esc_html__('Color Address', 'hitboox'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .address' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'city_color',
            [
                'label' => esc_html__('Color City', 'hitboox'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .city' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }


    protected function render() {
        // get our input from the widget settings.
        $settings  = $this->get_settings_for_display();
        $image_src = $settings['hotspot_image'];

        $image_src_size = Group_Control_Image_Size::get_attachment_image_src($image_src['id'], 'background_image', $settings);
        if (empty($image_src_size)) {
            $image_src_size = $image_src['url'];
        }

        ?>
        <div id="hitboox-image-lookbook-<?php echo esc_attr($this->get_id()); ?>" class="hitboox-image-lookbook-container" >
            <img class="hitboox-addons-image-lookbook-ib-img" alt="Background" src="<?php echo esc_url($image_src_size); ?>">
            <?php foreach ($settings['image_hotspot_icons'] as $index => $item) {
                $list_item_key = 'img_hotspot_' . $index;
                $this->add_render_attribute($list_item_key, 'class',
                    [
                        'elementor-repeater-item-' . $item['_id'],
                        'hotspot-wrapper',
                    ]);
                ?>
                <div <?php $this->print_render_attribute_string($list_item_key); ?>>
                    <div class="hotspot-item">
                        <div class="hotspot-text">
                            <?php if ($item['hotspot_address']) { ?>
                                <div class="address"><?php echo esc_html($item["hotspot_address"]) ?></div>
                            <?php } ?>
                            <?php if ($item['hotspot_city']) { ?>
                                <div class="city"><?php echo esc_html($item["hotspot_city"]) ?></div>
                            <?php } ?>
                        </div>
                        <div class="hotspot-dots"></div>

                    </div>
                </div>
            <?php } ?>
        </div>

        <?php
    }
}

$widgets_manager->register(new Hitboox_Elementor_HotSpots());