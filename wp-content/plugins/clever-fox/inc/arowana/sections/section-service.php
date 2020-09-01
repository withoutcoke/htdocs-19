<?php
/**
 * Get default values for service section.
 *
 * @since 1.1.0
 * @access public
 */
 if ( ! function_exists( 'startkit_service_plu' ) ) :

	function startkit_service_plu() {
  
  function startkit_get_service_default() {
	return apply_filters(
		'startkit_get_service_default', json_encode(
						 array(
				array(
					'title'           => esc_html__( 'Idea Provide', 'startkit' ),
					'text'            => esc_html__( 'Idea is the ipsum consecte tempor incididuntan andolore tumber tur adipisicing elit..', 'startkit' ),
					'icon_value'	  =>  esc_html__( 'fa-lightbulb-o', 'startkit' ),
					'id'              => 'customizer_repeater_service_001',
					
				),
				array(
					'title'           => esc_html__( 'People Research', 'startkit' ),
					'text'            => esc_html__( 'People is the ipsum consecte tempor incididuntan andolore tumber tur adipisicing elit.', 'startkit' ),
					'icon_value'	  =>  esc_html__( 'fa-users', 'startkit' ),
					'id'              => 'customizer_repeater_service_002',
				
				),
				array(
					'title'           => esc_html__( 'Business Develop', 'startkit' ),
					'text'            => esc_html__( 'People is the ipsum consecte tempor incididuntan andolore tumber tur adipisicing elit.', 'startkit' ),
					'icon_value'	  =>  esc_html__( 'fa-briefcase', 'startkit' ),
					'id'              => 'customizer_repeater_service_003',
			
				),
				array(
					'title'           => esc_html__( 'Marketing', 'startkit' ),
					'text'            => esc_html__( 'People is the ipsum consecte tempor incididuntan andolore tumber tur adipisicing elit.', 'startkit' ),
					'icon_value'	  =>  esc_html__( 'fa-user-md', 'startkit' ),
					'id'              => 'customizer_repeater_service_004',
					
				),
			)
		)
	);
}
 
?>
<?php 
	$default_content 	= startkit_get_service_default();
	$hide_show_service	= get_theme_mod('hide_show_service','1'); 
	$service_title		= get_theme_mod('service_title','Services');
	$service_description= get_theme_mod('service_description','Publishing packages and web page editors now use Lorem Ipsum as their default model text');
	$service_contents	= get_theme_mod('service_contents',$default_content);	
?>
<?php if($hide_show_service == '1') {?>
<section id="services" class="section-padding">
	<div class="container">
	<?php  
	if( ($service_title) || ($service_description)!='' ) { ?>
	    <!-- Section Title -->
	
		<div class="row">
			<div class="col-md-6 offset-md-3 text-center">
				<div class="section-header">
					<?php if ( ! empty( $service_title ) || is_customize_preview() ) : ?>
					<h2>
					<?php echo esc_html($service_title); ?>
					</h2>
					<?php endif; ?>
					
					<?php if($service_description) {?>
					
					<p class="wow fadeInUp" data-wow-delay="0.1s">
					<?php echo esc_html($service_description); ?>
					</p>
					<?php } ?>
				</div>
			</div>
		</div>
		<!-- /Section Title -->
		<?php } ?>
	<div class="row text-center servicesss">
		<?php
	
		if ( ! empty( $service_contents ) ) {
		$allowed_html = array(
		'br'     => array(),
		'em'     => array(),
		'strong' => array(),
		'b'      => array(),
		'i'      => array(),
		);
		$service_contents = json_decode( $service_contents );
		foreach ( $service_contents as $service_item ) {
			$icon = ! empty( $service_item->icon_value ) ? apply_filters( 'startkit_translate_single_string', $service_item->icon_value, 'service section' ) : '';
			$title = ! empty( $service_item->title ) ? apply_filters( 'startkit_translate_single_string', $service_item->title, 'service section' ) : '';
			$text = ! empty( $service_item->text ) ? apply_filters( 'startkit_translate_single_string', $service_item->text, 'service section' ) : '';
			?>
			<div class="col-lg-3 col-md-4 col-sm-6 col-12 text-center mb-5">
                    <div class="servicepage-item wow fadeInUp" data-wow-delay="0.4s">
                        <div class="service-icon">
                            <?php if ( ! empty( $icon ) ) :?>
								<i class="fa <?php echo esc_html( $icon ); ?> txt-pink"></i>
							<?php endif; ?>
                        </div>
                        <?php if ( ! empty( $title ) ) : ?>
						<h4><?php echo esc_html( $title ); ?></h4>
						<?php endif; ?>
						<?php if ( ! empty( $text ) ) : ?>
							<p><?php echo wp_kses( html_entity_decode( $text ), $allowed_html ); ?></p>
						<?php endif; ?>
                    </div>
                </div>
			<?php
			} }
			?>
			
		</div>
	</div>
	</section>		
<?php 
	}}
endif;
if ( function_exists( 'startkit_service_plu' ) ) {
$section_priority = apply_filters( 'startkit_section_priority', 13, 'startkit_service_plu' );
add_action( 'startkit_sections', 'startkit_service_plu', absint( $section_priority ) );
}
?>
