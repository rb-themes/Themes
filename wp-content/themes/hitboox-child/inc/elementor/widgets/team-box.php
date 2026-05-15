<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Repeater;

class Hitboox_Elementor_Team_Box extends Hitboox_Base_Widgets_Swiper
{


    public function get_name()
    {
        return 'hitboox-team-box';
    }


    public function get_title()
    {
        return esc_html__('Hiitboox Team Box', 'hitboox');
    }


    public function get_icon()
    {
        return 'eicon-person';
    }

    public function get_script_depends()
    {
        return ['hitboox-elementor-team-box', 'hitboox-elementor-swiper'];
    }

    public function get_categories()
    {
        return array('hitboox-addons');
    }


    protected function register_controls()
    {
        $this->start_controls_section(
            'section_team',
            [
                'label' => esc_html__('Team', 'hitboox'),
            ]
        );
        $repeater = new Repeater();


        $repeater->add_control(
            'teambox_image',
            [
                'label' => esc_html__('Choose Image', 'hitboox'),
                'default' => [
                    'url' => Elementor\Utils::get_placeholder_image_src(),
                ],
                'type' => Controls_Manager::MEDIA,
                'show_label' => false,
            ]
        );

        $repeater->add_control(
            'teambox_name',
            [
                'label' => esc_html__('Name', 'hitboox'),
                'default' => 'John Doe',
                'type' => Controls_Manager::TEXT,
            ]
        );

        $repeater->add_control(
            'teambox_job',
            [
                'label' => esc_html__('Job', 'hitboox'),
                'default' => 'Designer',
                'type' => Controls_Manager::TEXT,
            ]
        );

        $repeater->add_control(
            'teambox_link',
            [
                'label' => esc_html__('Link to', 'hitboox'),
                'placeholder' => esc_html__('https://your-link.com', 'hitboox'),
                'type' => Controls_Manager::URL,
            ]
        );

        $repeater->add_control(
    'linkedin',
    [
        'label' => esc_html__('LinkedIn', 'hitboox'),
        'placeholder' => esc_html__('https://www.linkedin.com/in/username', 'hitboox'),
        'default' => '',
        'type' => Controls_Manager::TEXT,
    ]
);

        $this->add_control(
            'teambox',
            [
                'label' => esc_html__('Items', 'hitboox'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ name }}}',
            ]
        );

        $this->add_group_control(
            Elementor\Group_Control_Image_Size::get_type(),
            [
                'name' => 'teambox_image',
                'default' => 'full',
                'separator' => 'none',
            ]
        );

        $this->add_responsive_control(
            'column',
            [
                'label' => esc_html__('Columns', 'hitboox'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 1,
                'options' => [1 => 1, 2 => 2, 3 => 3, 4 => 4, 6 => 6],
                'selectors' => [
                    '{{WRAPPER}} .d-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr)',
                ],
                'condition' => ['enable_carousel!' => 'yes']
            ]
        );

        $this->add_responsive_control(
            'teambox_gutter',
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

        $this->start_controls_section(
            'teambox_style_image',
            [
                'label' => esc_html__('Image', 'hitboox'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'image_border_radius',
            [
                'label' => esc_html__('Border Radius', 'hitboox'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .team-image-inner:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .team-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'teambox_style_content',
            [
                'label' => esc_html__('Content', 'hitboox'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'heading_name',
            [
                'label' => esc_html__('Name', 'hitboox'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'name_color',
            [
                'label' => esc_html__('Color', 'hitboox'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .team-name' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .team-name a' => 'color: {{VALUE}};',
                ],

            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'name_typography',
                'selector' => '{{WRAPPER}} .team-name',

            ]
        );

        $this->add_control(
            'heading_job',
            [
                'label' => esc_html__('Job', 'hitboox'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'job_color',
            [
                'label' => esc_html__('Color', 'hitboox'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .team-job' => 'color: {{VALUE}};',
                ],

            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'job_typography',
                'selector' => '{{WRAPPER}} .team-job',

            ]
        );

        $this->add_control(
            'heading_social',
            [
                'label' => esc_html__('Social', 'hitboox'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'social_color',
            [
                'label' => esc_html__('Color', 'hitboox'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .team-icon-socials ul a' => 'color: {{VALUE}};',
                ],

            ]
        );
        $this->add_control(
            'social_color_hover',
            [
                'label' => esc_html__('Color Hover', 'hitboox'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .team-icon-socials ul a:hover' => 'color: {{VALUE}};',
                ],

            ]
        );

        $this->end_controls_section();

        $this->add_control_carousel(['enable_carousel' => 'yes']);

    }

    /**
     * Render teambox widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since  1.0.0
     * @access protected
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        if (empty($settings['teambox'])) {
            return;
        }

        $this->add_render_attribute('wrapper', 'class', 'elementor-teambox-item-wrapper');
        $this->get_data_elementor_columns();
        // Item
        $this->add_render_attribute('item', 'class', 'elementor-teambox-item');
        $this->add_render_attribute('details', 'class', 'teambox-details');
        ?>

        <div <?php $this->print_render_attribute_string('wrapper'); ?>>
            <div <?php $this->print_render_attribute_string('row'); ?>>
                <?php foreach ($settings['teambox'] as $teambox): ?>
                    <div <?php $this->print_render_attribute_string('item'); ?>>
                        <div class="team-item">

                            <?php $this->render_image($settings, $teambox); ?>

                            <div class="team-infor">
                                <div class="team-content">
                                    <?php $teambox_name_html = $teambox['teambox_name'];
                                    if (!empty($teambox['teambox_link']['url'])) {
                                        $teambox_name_html = '<a href="' . esc_url($teambox['teambox_link']['url']) . '">' . esc_html($teambox_name_html) . '</a>';
                                    }
                                    printf('<h5 class="team-name">%s</h5>', $teambox_name_html);
                                    ?>
                                    <?php if ($teambox['teambox_job']) { ?>
                                        <div class="team-job"><?php echo esc_html($teambox['teambox_job']); ?></div>
                                    <?php } ?>
                                </div>

                                <div class="team-box-socials-wrapper">
                                    <div class="team-icon-socials">
                                        <ul>
                                            <?php foreach ($this->get_socials() as $key => $social): ?>
                                                <?php if (!empty($teambox[$key])) : ?>
                                                    <li class="social">
                                                        <a href="<?php echo esc_url($teambox[$key]) ?>">
                                                            <i class="hitboox-icon-<?php echo esc_attr($social); ?>"></i>
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php $this->render_swiper_pagination_navigation(); ?>
        <?php
    }

    private function render_image($settings, $teambox)
    {
        if (!empty($teambox['teambox_image']['url'])) :
            ?>
            <div class="team-image">
                <div class="decor-border"> </div>
                <div class="team-image-inner">
                    <?php
                    $teambox['teambox_image_size'] = $settings['teambox_image_size'];
                    $teambox['teambox_image_custom_dimension'] = $settings['teambox_image_custom_dimension'];
                    echo Group_Control_Image_Size::get_attachment_image_html($teambox, 'teambox_image');
                    ?>
                </div>
            </div>
        <?php
        endif;
    }

    private function get_socials()
    {
        return array(
             'linkedin' => 'linkedin',
        );
    }

}

$widgets_manager->register(new Hitboox_Elementor_Team_Box());