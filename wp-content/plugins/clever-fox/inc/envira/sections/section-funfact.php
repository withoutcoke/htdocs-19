<?php
/**
 * Get default values for service section.
 *
 * @since 1.1.0
 * @access public
 */
 if ( ! function_exists( 'startkit_funfact_plu' ) ) :

	function startkit_funfact_plu() {
	 function startkit_get_funfact_default() {
			return apply_filters(
				'startkit_get_funfact_default', json_encode(
					 array(
						array(
							'title'           => esc_html__( '9.1', 'startkit-pro' ),
							'subtitle'		  => esc_html__( 'B', 'startkit-pro' ),
							'text'            => esc_html__( 'Users', 'startkit-pro' ),
							'icon_value'	=>  esc_html__( 'fa fa-users', 'startkit-pro' ),
							'id'              => 'customizer_repeater_funfact_001',
						),
						array(
							'title'           => esc_html__( '8.2', 'startkit-pro' ),
							'subtitle'		  => esc_html__( 'B', 'startkit-pro' ),
							'text'            => esc_html__( 'Downloads', 'startkit-pro' ),
							'icon_value'	=>  esc_html__( 'fal fa-download', 'startkit-pro' ),
							'id'              => 'customizer_repeater_funfact_001',
						
						),
						array(
							'title'           => esc_html__( '2.26', 'startkit-pro' ),
							'subtitle'		  => esc_html__( 'B', 'startkit-pro' ),
							'text'            => esc_html__( 'Reviews', 'startkit-pro' ),
							'icon_value'	=>  esc_html__( 'fa fa-star-half-o', 'startkit-pro' ),
							'id'              => 'customizer_repeater_funfact_001',
					
						),
						array(
							'title'           => esc_html__( '9.1', 'startkit-pro' ),
							'subtitle'		  => esc_html__( 'B', 'startkit-pro' ),
							'text'            => esc_html__( 'Users', 'startkit-pro' ),
							'icon_value'	=>  esc_html__( 'fa fa-trophy', 'startkit-pro' ),
							'id'              => 'customizer_repeater_funfact_001',
							
						),
					)
				)
			);
		}
		$default_content = startkit_get_funfact_default();
?>	
<?php
	$hide_show_funfact			= get_theme_mod('hide_show_funfact','1');
	$funfact_contents			= get_theme_mod('funfact_contents',$default_content);
	$funfact_background_setting	= get_theme_mod('funfact_background_setting',CLEVERFOX_PLUGIN_URL . 'inc/envira/images/factbg.jpg');
	$funfact_background_position= get_theme_mod('funfact_background_position','fixed');	
	if($hide_show_funfact == '1') { 
?>
<?php if ( ! empty( $funfact_background_setting ) ) { ?>
    <section id="fun-fact" style="background:url('<?php echo esc_url($funfact_background_setting); ?>') no-repeat center / cover <?php echo esc_attr($funfact_background_position); ?>;">
		<?php } else { ?>
		<section id="fun-fact" class="fun-background">
		<?php } ?>
			<div class="container">
				<div class="row text-center">
				<?php
		
					if ( ! empty( $funfact_contents ) ) {
					$allowed_html = array(
					'br'     => array(),
					'em'     => array(),
					'strong' => array(),
					'b'      => array(),
					'i'      => array(),
					);
					$funfact_contents = json_decode( $funfact_contents );
					foreach ( $funfact_contents as $funfact_item ) {
						$icon = ! empty( $funfact_item->icon_value ) ? apply_filters( 'startkit_translate_single_string', $funfact_item->icon_value, 'funfact section' ) : '';
						$title = ! empty( $funfact_item->title ) ? apply_filters( 'startkit_translate_single_string', $funfact_item->title, 'funfact section' ) : '';
						$subtitle = ! empty( $funfact_item->subtitle ) ? apply_filters( 'startkit_translate_single_string', $funfact_item->subtitle, 'funfact section' ) : '';
						$text = ! empty( $funfact_item->text ) ? apply_filters( 'startkit_translate_single_string', $funfact_item->text, 'funfact section' ) : '';
					?>
					<div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-lg-0 mb-4 home-fun">
						<div class="fact-item">	
							<?php if ( ! empty( $icon ) ) :?>
								<i class="fa <?php echo esc_attr( $icon ); ?>"></i>
							<?php endif; ?>	
							<?php if ( ! empty( $title ) ) :?>
							<div><strong><span class="counter"><?php echo wp_filter_post_kses( $title ); ?></span> <?php echo esc_html($subtitle); ?></strong></div>
							<?php endif; ?>
							<?php if ( ! empty( $text ) ) :?>
								<p><?php echo esc_html( $text ); ?></p>
							<?php endif; ?>
						</div>
					</div>
					<?php } } ?>
				</div>
			</div>
		</section>
    </section>	
<?php 
	}}
endif;
if ( function_exists( 'startkit_funfact_plu' ) ) {
$section_priority = apply_filters( 'startkit_section_priority', 13, 'startkit_funfact_plu' );
add_action( 'startkit_sections', 'startkit_funfact_plu', absint( $section_priority ) );
}
?>
