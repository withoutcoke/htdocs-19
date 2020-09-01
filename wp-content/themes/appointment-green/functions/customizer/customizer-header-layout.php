<?php
function appointment_green_header_column_layout_customizer( $wp_customize ) {
    
    /**
     * Image Radio Button Custom Control
     *
     * @author Anthony Hortin <http://maddisondesigns.com>
     * @license http://www.gnu.org/licenses/gpl-2.0.html
     * @link https://github.com/maddisondesigns
     */
    class Appointment_green_Image_Radio_Button_Custom_Control extends WP_Customize_Control {

        /**
         * The type of control being rendered
         */
        public $type = 'image_radio_button';

        public function enqueue() {
            add_action('customize_controls_print_styles', array($this, 'print_styles'));
        }

        public function print_styles() {
            ?><style>
                /*blue child*/
                #customize-control-appointment_options-header_column_layout_setting .image_radio_button_control .radio-button-label > img {
                    margin-top: 5%;
                }
            </style>
            <?php
        }
        /**
         * Render the control in the customizer
         */
        public function render_content() {
            ?>
            <div class="image_radio_button_control">
                <?php if (!empty($this->label)) { ?>
                    <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
                <?php } ?>
                <?php if (!empty($this->description)) { ?>
                    <span class="customize-control-description"><?php echo esc_html($this->description); ?></span>
                <?php } ?>

                <?php foreach ($this->choices as $key => $value) { ?>
                    <label class="radio-button-label">
                        <input type="radio" name="<?php echo esc_attr($this->id); ?>" value="<?php echo esc_attr($key); ?>" <?php $this->link(); ?> <?php checked(esc_attr($key), $this->value()); ?>/>
                        <img src="<?php echo esc_attr($value['image']); ?>" alt="<?php echo esc_attr($value['name']); ?>" title="<?php echo esc_attr($value['name']); ?>" />
                    </label>
                <?php } ?>
            </div>
            <?php
        }

    }

        $appointment_blue_header_setting = wp_parse_args(get_option('appointment_options', array()), appointment_theme_setup_data());


	   /* Header Layout section */
        $wp_customize->add_section( 'header_column_layout_setting' , array(
            'title'      => esc_html__('Header Layout', 'appointment-green'),
            'panel'  => 'header_options'
        ) );
        
            // Header Layout settings
        if((!has_custom_logo() &&  $appointment_blue_header_setting['enable_header_logo_text'] == 'nomorenow') || $appointment_blue_header_setting['enable_header_logo_text'] == 1 || $appointment_blue_header_setting['upload_image_logo'] !='' ){

            $wp_customize->add_setting( 'appointment_options[header_column_layout_setting]' , array(
            'default' => 'default',
            'sanitize_callback' => 'appointment_green_sanitize_radio',
            'type'=>'option'
            ) );

        }
        else{

            $wp_customize->add_setting( 'appointment_options[header_column_layout_setting]' , array(
            'default' => 'column',
            'sanitize_callback' => 'appointment_green_sanitize_radio',
            'type'=>'option'
            ) );

        }
            $wp_customize->add_control(new Appointment_green_Image_Radio_Button_Custom_Control($wp_customize, 'appointment_options[header_column_layout_setting]',
                    array(
                'label' => esc_html__('Header Layout Setting', 'appointment-green'),
                'section' => 'header_column_layout_setting',
                'choices' => array(
                    'default' => array(
                        'image' => APPOINTMENT_GREEN_TEMPLATE_DIR_URI . '/images/green-default.png',
                        'name' => esc_html__('Standard Layout', 'appointment-green')
                    ),
                    'column' => array(
                        'image' => APPOINTMENT_GREEN_TEMPLATE_DIR_URI . '/images/green-column.png',
                        'name' => esc_html__('Classic Dark Layout', 'appointment-green')
                    )
                )
                    )
    ));
           
}
add_action( 'customize_register', 'appointment_green_header_column_layout_customizer' );
//radio box sanitization function
    function appointment_green_sanitize_radio($input, $setting) {

        $input = sanitize_key($input);

        $choices = $setting->manager->get_control($setting->id)->choices;

        //return if valid 
        return ( array_key_exists($input, $choices) ? $input : $setting->default );
    }