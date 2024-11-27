<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Repeater;

class Lebagol_Elementor_Team_Box extends Lebagol_Base_Widgets_Swiper {

    /**
     * Get widget name.
     *
     * Retrieve teambox widget name.
     *
     * @return string Widget name.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_name() {
        return 'lebagol-team-box';
    }

    /**
     * Get widget title.
     *
     * Retrieve teambox widget title.
     *
     * @return string Widget title.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_title() {
        return esc_html__('Lebagol Team Box', 'lebagol');
    }

    /**
     * Get widget icon.
     *
     * Retrieve teambox widget icon.
     *
     * @return string Widget icon.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_icon() {
        return 'eicon-person';
    }

    public function get_script_depends() {
        return ['lebagol-elementor-team-box', 'lebagol-elementor-swiper'];
    }

    public function get_categories() {
        return array('lebagol-addons');
    }

    /**
     * Register teambox widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since  1.0.0
     * @access protected
     */
    protected function register_controls() {
        $this->start_controls_section(
            'section_team',
            [
                'label' => esc_html__('Team', 'lebagol'),
            ]
        );
        $repeater = new Repeater();


        $repeater->add_control(
            'teambox_image',
            [
                'label'      => esc_html__('Choose Image', 'lebagol'),
                'default'    => [
                    'url' => Elementor\Utils::get_placeholder_image_src(),
                ],
                'type'       => Controls_Manager::MEDIA,
                'show_label' => false,
            ]
        );

        $repeater->add_control(
            'teambox_name',
            [
                'label'   => esc_html__('Name', 'lebagol'),
                'default' => 'John Doe',
                'type'    => Controls_Manager::TEXT,
            ]
        );

        $repeater->add_control(
            'teambox_job',
            [
                'label'   => esc_html__('Job', 'lebagol'),
                'default' => 'Designer',
                'type'    => Controls_Manager::TEXT,
            ]
        );

        $repeater->add_control(
            'teambox_description',
            [
                'label' => esc_html__( 'Description', 'lebagol' ),
                'type' => Controls_Manager::TEXTAREA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'lebagol' ),
                'placeholder' => esc_html__( 'Enter your description', 'lebagol' ),
                'separator' => 'none',
                'rows' => 5,
            ]
        );
        $repeater->add_control(
            'teambox_link',
            [
                'label'       => esc_html__('Link to', 'lebagol'),
                'placeholder' => esc_html__('https://your-link.com', 'lebagol'),
                'type'        => Controls_Manager::URL,
            ]
        );

        $repeater->add_control(
            'facebook',
            [
                'label'       => esc_html__('Facebook', 'lebagol'),
                'placeholder' => esc_html__('https://www.facebook.com/opalwordpress', 'lebagol'),
                'default'     => 'https://www.facebook.com/opalwordpress',
                'type'        => Controls_Manager::TEXT,
            ]
        );
        $repeater->add_control(
            'twitter',
            [
                'label'       => esc_html__('Twitter', 'lebagol'),
                'placeholder' => esc_html__('https://twitter.com/opalwordpress', 'lebagol'),
                'default'     => 'https://twitter.com/opalwordpress',
                'type'        => Controls_Manager::TEXT,
            ]
        );

        $repeater->add_control(
            'instagram',
            [
                'label'       => esc_html__('Instagram', 'lebagol'),
                'placeholder' => esc_html__('https://www.instagram.com/user/WPOpalTheme', 'lebagol'),
                'default'     => 'https://www.instagram.com/user/WPOpalTheme',
                'type'        => Controls_Manager::TEXT,
            ]
        );

        $repeater->add_control(
            'pinterest',
            [
                'label'       => esc_html__('Pinterest', 'lebagol'),
                'placeholder' => esc_html__('https://plus.pinterest.com/u/0/+WPOpal', 'lebagol'),
                'default'     => 'https://plus.pinterest.com/u/0/+WPOpal',
                'type'        => Controls_Manager::TEXT,
            ]
        );
        $this->add_control(
            'teambox',
            [
                'label'       => esc_html__('Items', 'lebagol'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'title_field' => '{{{ name }}}',
            ]
        );

        $this->add_group_control(
            Elementor\Group_Control_Image_Size::get_type(),
            [
                'name'      => 'teambox_image',
                'default'   => 'full',
                'separator' => 'none',
            ]
        );

        $this->add_responsive_control(
            'column',
            [
                'label'     => esc_html__('Columns', 'lebagol'),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'default'   => 1,
                'options'   => [1 => 1, 2 => 2, 3 => 3, 4 => 4, 6 => 6],
                'selectors' => [
                    '{{WRAPPER}} .d-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr)',
                ],
                'condition' => ['enable_carousel!' => 'yes']
            ]
        );

        $this->add_responsive_control(
            'teambox_gutter',
            [
                'label'      => esc_html__('Gutter', 'lebagol'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .d-grid' => 'grid-gap:{{SIZE}}{{UNIT}}',
                ],
                'condition'  => ['enable_carousel!' => 'yes']
            ]
        );

        $this->add_control(
            'enable_carousel',
            [
                'label' => esc_html__('Enable Carousel', 'lebagol'),
                'type'  => Controls_Manager::SWITCHER,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'teambox_style_image',
            [
                'label' => esc_html__('Image', 'lebagol'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'image_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'lebagol'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .team-image img, .team-image:after ' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_margin',
            [
                'label'      => esc_html__('Margin', 'lebagol'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .team-image img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_padding',
            [
                'label'      => esc_html__('Padding', 'lebagol'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .team-image img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'teambox_style_content',
            [
                'label' => esc_html__('Content', 'lebagol'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'content_margin',
            [
                'label'      => esc_html__('Margin', 'lebagol'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .team-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'content_padding',
            [
                'label'      => esc_html__('Padding', 'lebagol'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .team-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'heading_name',
            [
                'label'     => esc_html__('Name', 'lebagol'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'name_color',
            [
                'label'     => esc_html__('Color', 'lebagol'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .team-name' => 'color: {{VALUE}};',
                ],

            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'name_typography',
                'selector' => '{{WRAPPER}} .team-name',

            ]
        );

        $this->add_control(
            'heading_job',
            [
                'label'     => esc_html__('Job', 'lebagol'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'job_color',
            [
                'label'     => esc_html__('Color', 'lebagol'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .team-job' => 'color: {{VALUE}};',
                ],

            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'job_typography',
                'selector' => '{{WRAPPER}} .team-job',

            ]
        );

        $this->add_control(
            'heading_des',
            [
                'label'     => esc_html__('Description', 'lebagol'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'des_color',
            [
                'label'     => esc_html__('Color', 'lebagol'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .team-description' => 'color: {{VALUE}};',
                ],

            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'des_typography',
                'selector' => '{{WRAPPER}} .team-description',

            ]
        );

        $this->add_control(
            'heading_social',
            [
                'label'     => esc_html__('Social', 'lebagol'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'social_icon_size',
            [
                'label'     => esc_html__('Size', 'lebagol'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .team-icon-socials ul a' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'social_color',
            [
                'label'     => esc_html__('Color', 'lebagol'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .team-icon-socials ul a' => 'color: {{VALUE}};',
                ],

            ]
        );
        $this->add_control(
            'social_color_hover',
            [
                'label'     => esc_html__('Color Hover', 'lebagol'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
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
    protected function render() {
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
                        <div <?php $this->print_render_attribute_string('details'); ?>>
                            <?php $this->render_image($settings, $teambox); ?>
                            <div class="team-content">
                                <?php if (!empty($teambox['team_content'])) { ?>
                                    <div class="content"><?php echo sprintf('%s', $teambox['team_content']); ?></div>
                                <?php } ?>
                                <?php if ($teambox['teambox_name']) { ?>
                                    <span class="team-name"><?php echo esc_html($teambox['teambox_name']); ?></span>
                                <?php } ?>
                                <?php if ($teambox['teambox_job']) { ?>
                                    <span class="team-job"><?php echo esc_html($teambox['teambox_job']); ?></span>
                                <?php } ?>
                                <?php if ($teambox['teambox_description']) { ?>
                                    <span class="team-description"><?php echo esc_html($teambox['teambox_description']); ?></span>
                                <?php } ?>
                            </div>
                            <div class="team-icon-socials">
                                <ul>
                                    <?php foreach ($this->get_socials() as $key => $social): ?>
                                        <?php if (!empty($teambox[$key])) : ?>
                                            <li class="social">
                                                <a href="<?php echo esc_url($teambox[$key]) ?>">
                                                    <i class="lebagol-icon-<?php echo esc_attr($social); ?>"></i>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php $this->render_swiper_pagination_navigation();?>
        <?php
    }

    private function render_image($settings, $teambox) {
        if (!empty($teambox['teambox_image']['url'])) :
            ?>
            <div class="team-image">
                <?php
                $teambox['teambox_image_size']             = $settings['teambox_image_size'];
                $teambox['teambox_image_custom_dimension'] = $settings['teambox_image_custom_dimension'];
                echo Group_Control_Image_Size::get_attachment_image_html($teambox, 'teambox_image');
                ?>
            </div>
        <?php
        endif;
    }

    private function get_socials()
    {
        return array(
            'facebook' => 'facebook-f',
            'twitter' => 'twitter',
            'instagram' => 'instagram',
            'pinterest' => 'pinterest-p',
        );
    }

}

$widgets_manager->register(new Lebagol_Elementor_Team_Box());
