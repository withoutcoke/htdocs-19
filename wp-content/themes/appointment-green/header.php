<!DOCTYPE html>
<html <?php language_attributes(); ?> >
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
        <?php wp_head(); ?>
    </head>
    <body <?php body_class(); ?> >
        <?php wp_body_open(); ?>
        <a class="skip-link screen-reader-text" href="#wrap"><?php esc_html_e('Skip to content', 'appointment-green'); ?></a> 
        <!--/Logo & Menu Section-->	
        <?php
        $appointment_green_header_setting = wp_parse_args(get_option('appointment_options', array()), appointment_green_default_data());
        if ($appointment_green_header_setting['header_column_layout_setting'] == 'column') {

            appointment_green_header_column_layout();
        } else {

            appointment_green_header_default_layout();
        }
        ?>
        <div class="clearfix"></div>