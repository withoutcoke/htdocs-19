<?php
/**
 * Get default values for testimonial section.
 *
 * @since 1.1.31
 * @access public
 */
  if ( ! function_exists( 'startkit_testimonial_plu' ) ) :

	function startkit_testimonial_plu() {
function startkit_get_testimonial_default() {
	return apply_filters(
		'startkit_get_testimonial_default', json_encode(
					 array(
				array(
					'title'           => esc_html__( 'Naiomi Watson', 'startkit' ),
					'subtitle'        => esc_html__( 'Lead Interaction Designer', 'startkit' ),
					'text'            => esc_html__( 'Established fact that a reader will be distracted by the readable content of a page when', 'startkit' ),
					'image_url'		  =>  CLEVERFOX_PLUGIN_URL .'inc/startkit/images/testimonial/testimonial01.png',
					'id'              => 'customizer_repeater_testimonial_001',
				),
				array(
					'title'           => esc_html__( 'Pins kumara', 'startkit' ),
					'subtitle'        => esc_html__( 'Lead Interaction Designer', 'startkit' ),
					'text'            => esc_html__( 'Established fact that a reader will be distracted by the readable content of a page when', 'startkit' ),
					'image_url'		  => CLEVERFOX_PLUGIN_URL .'inc/startkit/images/testimonial/testimonial02.png',
					'id'              => 'customizer_repeater_testimonial_001',
				
				),
				array(
					'title'           => esc_html__( 'Mairala Thare', 'startkit' ),
					'subtitle'        => esc_html__( 'Lead Interaction Designer', 'startkit' ),
					'text'            => esc_html__( 'Established fact that a reader will be distracted by the readable content of a page when', 'startkit' ),
					'image_url'		  => CLEVERFOX_PLUGIN_URL .'inc/startkit/images/testimonial/testimonial03.png',
					'id'              => 'customizer_repeater_testimonial_001',
			
				),
			)

		)
	);
}
?>
<?php 
		$default_content		= startkit_get_testimonial_default();
		$hide_show_testimonial	= get_theme_mod('hide_show_testimonial','1'); 
		$testimonial_title		= get_theme_mod('testimonial_title','Testimonial');
		$testimonial_description= get_theme_mod('testimonial_description','Publishing packages and web page editors now use Lorem Ipsum as their default model text');
		$testimonial_contents	= get_theme_mod('testimonial_contents',$default_content);
?>
<?php if($hide_show_testimonial) {?>
<section id="testimonial" class="section-padding">
	<div class="container">
		<?php if( ($testimonial_title) || ($testimonial_description)!='' ) { ?>
			<div class="row">
				<div class="col-md-6 offset-md-3 text-center">
					<div class="section-header">
						<?php if ( ! empty( $testimonial_title ) ) { ?>
							<h2><?php echo esc_html( $testimonial_title ); ?></h2>
						<?php } ?>
						<?php if ( ! empty( $testimonial_description ) ) { ?>
							<p class="wow fadeInUp" data-wow-delay="0.1s"><?php echo esc_html( $testimonial_description ); ?></p>
						<?php } ?>
					</div>
				</div>
			</div>
		<?php } ?>	
		<div class="row tst_contents">
			<div class="col-md-12">
				<div class="testimonial-carousel text-center">
					<?php
				
					if ( ! empty( $testimonial_contents ) ) {
					$allowed_html = array(
					'br'     => array(),
					'em'     => array(),
					'strong' => array(),
					'b'      => array(),
					'i'      => array(),
					);
					$testimonial_contents = json_decode( $testimonial_contents );
					foreach ( $testimonial_contents as $testimonial_item ) {
						
						$title = ! empty( $testimonial_item->title ) ? apply_filters( 'startkit_translate_single_string', $testimonial_item->title, 'service section' ) : '';
						$subtitle = ! empty( $testimonial_item->subtitle ) ? apply_filters( 'startkit_translate_single_string', $testimonial_item->subtitle, 'service section' ) : '';
						$text = ! empty( $testimonial_item->text ) ? apply_filters( 'startkit_translate_single_string', $testimonial_item->text, 'service section' ) : '';
						$link = ! empty( $testimonial_item->link ) ? apply_filters( 'startkit_translate_single_string', $testimonial_item->link, 'service section' ) : '';
						$image = ! empty( $testimonial_item->image_url ) ? apply_filters( 'startkit_translate_single_string', $testimonial_item->image_url, 'service section' ) : '';
						?>
					
				
					<div class="single-testimonial">
						<?php if ( ! empty( $image ) ) : ?>
							<div class="img-rounded"><img src="<?php echo esc_url( $image ); ?>" <?php if ( ! empty( $title ) ) : ?> alt="<?php echo esc_attr( $title ); ?>" title="<?php echo esc_attr( $title ); ?>" <?php endif; ?> /></div>
						<?php endif; ?>	
							<?php if ( ! empty( $title ) ) : ?>
								<h4><?php echo esc_html( $title ); ?></h4>
							<?php endif; ?>
							<?php if ( ! empty( $subtitle ) ) : ?>
								<p class="title"><?php echo esc_html( $subtitle ); ?></p>
							<?php endif; ?>
							<?php if ( ! empty( $text ) ) : ?>
								<p><?php echo esc_html( $text ); ?></p>
							<?php endif; ?>
			
					</div>
						<?php } } ?>
					
				</div>
			</div>
		</div>
	</div>
</section>
<?php 
	}}
	endif;
if ( function_exists( 'startkit_testimonial_plu' ) ) {
$section_priority = apply_filters( 'startkit_section_priority', 13, 'startkit_testimonial_plu' );
add_action( 'startkit_sections', 'startkit_testimonial_plu', absint( $section_priority ) );

}	
?>