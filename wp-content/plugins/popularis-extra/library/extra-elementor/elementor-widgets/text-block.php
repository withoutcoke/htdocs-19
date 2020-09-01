<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Scheme_Typography;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

class Popularis_Text_Block extends Widget_Base {

    public function get_name() {
        return 'popularis-text-block';
    }

    public function get_title() {
        return __('Advanced Text Block', 'popularis-extra');
    }

    public function get_icon() {
        return 'eicon-text-area';
    }

    public function get_categories() {
        return array('basic');
    }

    public function get_script_depends() {
        return [
            'popularis-animate-scripts'
        ];
    }

    protected function _register_controls() {

        $this->start_controls_section(
                'content_section',
                [
                    'label' => __('Advanced Text Block', 'popularis-extra'),
                    'tab' => Controls_Manager::TAB_CONTENT,
                ]
        );
        $this->add_control(
                'content_description',
                [
                    'type' => Controls_Manager::WYSIWYG,
                    'default' => __('I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'popularis-extra'),
                    'placeholder' => __('Type your description here', 'popularis-extra'),
                ]
        );
        $this->add_control(
                'header_size',
                [
                    'label' => __('HTML Tag', 'popularis-extra'),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        'h1' => 'H1',
                        'h2' => 'H2',
                        'h3' => 'H3',
                        'h4' => 'H4',
                        'h5' => 'H5',
                        'h6' => 'H6',
                        'div' => 'div',
                        'span' => 'span',
                        'p' => 'p',
                    ],
                    'default' => 'div',
                ]
        );
        $this->add_responsive_control(
                'content_align',
                [
                    'label' => __('Alignment', 'popularis-extra'),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => __('Left', 'popularis-extra'),
                            'icon' => 'fa fa-align-left',
                        ],
                        'center' => [
                            'title' => __('Center', 'popularis-extra'),
                            'icon' => 'fa fa-align-center',
                        ],
                        'right' => [
                            'title' => __('Right', 'popularis-extra'),
                            'icon' => 'fa fa-align-right',
                        ],
                        'justify' => [
                            'title' => __('Justify', 'popularis-extra'),
                            'icon' => 'fa fa-align-justify',
                        ],
                    ],
                    'devices' => ['desktop', 'tablet', 'mobile'],
                    'prefix_class' => 'text-%s',
                ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
                'section_styling',
                [
                    'label' => __('Typography', 'popularis-extra'),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
        );
        $this->add_control(
                'content_color',
                [
                    'label' => __('Text Color', 'popularis-extra'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#888',
                    'selectors' => [
                        '{{WRAPPER}} .popularis_extra_adv_text_block .text-content-block p,{{WRAPPER}} .popularis_extra_adv_text_block .text-content-block' => 'color:{{VALUE}};',
                    ],
                ]
        );
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'content_typography',
                    'label' => __('Typography', 'popularis-extra'),
                    'scheme' => Scheme_Typography::TYPOGRAPHY_3,
                    'selector' => '{{WRAPPER}} .popularis_extra_adv_text_block .text-content-block,{{WRAPPER}} .popularis_extra_adv_text_block .text-content-block p',
                ]
        );

        $this->end_controls_section();

        /* Adv tab */
        $this->start_controls_section(
                'section_animation_styling',
                [
                    'label' => __('On Scroll View Animation', 'popularis-extra'),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
        );
        $this->add_control(
                'animation_effects',
                [
                    'label' => __('Choose Animation Effect', 'popularis-extra'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'no-animation',
                    'options' => $this->popularis_get_animation_options(),
                ]
        );
        $this->add_control(
                'animation_delay',
                [
                    'type' => Controls_Manager::SLIDER,
                    'label' => __('Animation Delay', 'popularis-extra'),
                    'default' => [
                        'unit' => '',
                        'size' => 50,
                    ],
                    'range' => [
                        '' => [
                            'min' => 0,
                            'max' => 4000,
                            'step' => 15,
                        ],
                    ],
                    'render_type' => 'ui',
                    'condition' => [
                        'animation_effects!' => 'no-animation',
                    ],
                ]
        );
        $this->add_control(
                'animation_duration_default',
                [
                    'label' => esc_html__('Animation Duration', 'popularis-extra'),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => 'no',
                    'condition' => [
                        'animation_effects!' => 'no-animation',
                    ],
                ]
        );
        $this->add_control(
                'animate_duration',
                [
                    'type' => Controls_Manager::SLIDER,
                    'label' => __('Duration Speed', 'popularis-extra'),
                    'default' => [
                        'unit' => 'px',
                        'size' => 50,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 100,
                            'max' => 10000,
                            'step' => 100,
                        ],
                    ],
                    'render_type' => 'ui',
                    'condition' => [
                        'animation_effects!' => 'no-animation',
                        'animation_duration_default' => 'yes',
                    ],
                ]
        );
        $this->end_controls_section();
    }

    protected function popularis_get_animation_options() {
        return array(
            'no-animation' => __('No-animation', 'popularis-extra'),
            'transition.fadeIn' => __('FadeIn', 'popularis-extra'),
            'transition.flipXIn' => __('FlipXIn', 'popularis-extra'),
            'transition.flipYIn' => __('FlipYIn', 'popularis-extra'),
            'transition.flipBounceXIn' => __('FlipBounceXIn', 'popularis-extra'),
            'transition.flipBounceYIn' => __('FlipBounceYIn', 'popularis-extra'),
            'transition.swoopIn' => __('SwoopIn', 'popularis-extra'),
            'transition.whirlIn' => __('WhirlIn', 'popularis-extra'),
            'transition.shrinkIn' => __('ShrinkIn', 'popularis-extra'),
            'transition.expandIn' => __('ExpandIn', 'popularis-extra'),
            'transition.bounceIn' => __('BounceIn', 'popularis-extra'),
            'transition.bounceUpIn' => __('BounceUpIn', 'popularis-extra'),
            'transition.bounceDownIn' => __('BounceDownIn', 'popularis-extra'),
            'transition.bounceLeftIn' => __('BounceLeftIn', 'popularis-extra'),
            'transition.bounceRightIn' => __('BounceRightIn', 'popularis-extra'),
            'transition.slideUpIn' => __('SlideUpIn', 'popularis-extra'),
            'transition.slideDownIn' => __('SlideDownIn', 'popularis-extra'),
            'transition.slideLeftIn' => __('SlideLeftIn', 'popularis-extra'),
            'transition.slideRightIn' => __('SlideRightIn', 'popularis-extra'),
            'transition.slideUpBigIn' => __('SlideUpBigIn', 'popularis-extra'),
            'transition.slideDownBigIn' => __('SlideDownBigIn', 'popularis-extra'),
            'transition.slideLeftBigIn' => __('SlideLeftBigIn', 'popularis-extra'),
            'transition.slideRightBigIn' => __('SlideRightBigIn', 'popularis-extra'),
            'transition.perspectiveUpIn' => __('PerspectiveUpIn', 'popularis-extra'),
            'transition.perspectiveDownIn' => __('PerspectiveDownIn', 'popularis-extra'),
            'transition.perspectiveLeftIn' => __('PerspectiveLeftIn', 'popularis-extra'),
            'transition.perspectiveRightIn' => __('PerspectiveRightIn', 'popularis-extra'),
        );
    }

    protected function render() {

        $settings = $this->get_settings_for_display();
        $content = $settings['content_description'];
        $block = $settings['header_size'];

        $animation_effects = $settings["animation_effects"];
        $animation_delay = $settings["animation_delay"]["size"];
        $animate_duration = '';
        if ($settings["animation_duration_default"] == 'yes') {
            $animate_duration = $settings["animate_duration"]["size"];
        }
        if ($animation_effects == 'no-animation') {
            $animated_class = '';
            $animation_attr = '';
        } else {
            $animated_class = 'animate-general';
            $animation_attr = ' data-animate-type="' . esc_attr($animation_effects) . '" data-animate-delay="' . esc_attr($animation_delay) . '"';
            if ($settings["animation_duration_default"] == 'yes') {
                $animation_attr .= ' data-animate-duration="' . esc_attr($animate_duration) . '"';
            }
        }

        $text_block = '<div class="popularis_extra_adv_text_block ' . $animated_class . '" ' . $animation_attr . '>';
        $text_block .= '<' . $block . ' class="text-content-block">';
        $text_block .= $content;
        $text_block .= '</div>';
        $text_block .= '</' . $block . '>';

        echo $text_block;
    }

    protected function content_template() {
        
    }

}
