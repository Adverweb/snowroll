<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
if (!lebagol_is_contactform_activated()) {
    return;
}

use Elementor\Group_Control_Border;
use Elementor\Controls_Manager;

class Lebagol_Elementor_ContactForm extends Elementor\Widget_Base {

    public function get_name() {
        return 'lebagol-contactform';
    }

    public function get_title() {
        return esc_html__('Lebagol Contact Form', 'lebagol');
    }

    public function get_categories() {
        return array('lebagol-addons');
    }

    public function get_icon() {
        return 'eicon-form-horizontal';
    }

    protected function register_controls() {
        $this->start_controls_section(
            'contactform7',
            [
                'label' => esc_html__('General', 'lebagol'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );
        $cf7               = get_posts('post_type="wpcf7_contact_form"&numberposts=-1');
        $contact_forms[''] = esc_html__('Please select form', 'lebagol');
        if ($cf7) {
            foreach ($cf7 as $cform) {
                $contact_forms[$cform->ID] = $cform->post_title;
            }
        } else {
            $contact_forms[0] = esc_html__('No contact forms found', 'lebagol');
        }

        $this->add_control(
            'cf_id',
            [
                'label'   => esc_html__('Select contact form', 'lebagol'),
                'type'    => Controls_Manager::SELECT,
                'options' => $contact_forms,
                'default' => ''
            ]
        );

        $this->add_control(
            'form_name',
            [
                'label'   => esc_html__('Form name', 'lebagol'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('Contact form', 'lebagol'),
            ]
        );

        $this->end_controls_section();
        
        $this->start_controls_section(
            'ctform_style_input',
            [
                'label' => esc_html__('Input', 'lebagol'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->start_controls_tabs('tabs_input_style');

        $this->start_controls_tab(
            'tab_input_normal',
            [
                'label' => esc_html__('Normal', 'lebagol'),
            ]
        );

        $this->add_control(
            'input_color',
            [
                'label' => esc_html__('Color', 'lebagol'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-form input[type=text]' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form input[type=number]' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form input[type=email]' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form input[type=tel]' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form input[type=url]' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form input[type=date]' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form input[type=password]' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form input[type=search]' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form .input-text' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form select:not([size]):not([multiple])' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form textarea' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form input[type="date"]:before' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'input_color_placeholder',
            [
                'label' => esc_html__('Color Placeholder', 'lebagol'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-form input[type=text]::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form input[type=number]::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form input[type=email]::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form input[type=tel]::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form input[type=url]::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form input[type=date]::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form input[type=password]::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form input[type=search]::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form .input-text::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form select:not([size]):not([multiple])::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form textarea::placeholder' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'input_color_border',
            [
                'label' => esc_html__('Border Color', 'lebagol'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-form input[type=text]' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form input[type=number]' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form input[type=email]' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form input[type=tel]' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form input[type=url]' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form input[type=date]' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form input[type=password]' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form input[type=search]' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form .input-text' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form select:not([size]):not([multiple])' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form textarea' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_input_focus',
            [
                'label' => esc_html__('Focus', 'lebagol'),
            ]
        );

        $this->add_control(
            'input_border_color_focus',
            [
                'label' => esc_html__('Border Color', 'lebagol'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-form input[type=text]:focus' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form input[type=number]:focus' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form input[type=email]:focus' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form input[type=tel]:focus' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form input[type=url]:focus' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form input[type=date]:focus' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form input[type=password]:focus' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form input[type=search]:focus' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form .input-text:focus' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form select:not([size]):not([multiple]):focus' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form textarea:focus' => 'border-color: {{VALUE}}',
                ],
            ]
        );


        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        //Button
        $this->start_controls_section(
            'ctform_style_button',
            [
                'label' => esc_html__('Button', 'lebagol'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('tabs_button_style');

        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label' => esc_html__('Normal', 'lebagol'),
            ]
        );

        $this->add_control(
            'button_background',
            [
                'label' => esc_html__('Background Color', 'lebagol'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-form .ct-form .ct-button .elementor-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_color',
            [
                'label' => esc_html__('Text Color', 'lebagol'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-form .ct-form .ct-button .elementor-button' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .wpcf7-form .ct-form .ct-button .elementor-button .elementor-button-icon' => 'color: {{VALUE}};'
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label' => esc_html__('Hover', 'lebagol'),
            ]
        );

        $this->add_control(
            'button_background_hover',
            [
                'label' => esc_html__('Background Color', 'lebagol'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-form .ct-form .ct-button .elementor-button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_color_hover',
            [
                'label' => esc_html__('Color', 'lebagol'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-form .ct-form .ct-button .elementor-button:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .wpcf7-form .ct-form .ct-button .elementor-button:hover .elementor-button-icon' => 'color: {{VALUE}};'
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => esc_html__('Padding', 'lebagol'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        if (!$settings['cf_id']) {
            return;
        }
        $args['id']    = $settings['cf_id'];
        $args['title'] = $settings['form_name'];

        echo lebagol_do_shortcode('contact-form-7', $args);
    }
}

$widgets_manager->register(new Lebagol_Elementor_ContactForm());
