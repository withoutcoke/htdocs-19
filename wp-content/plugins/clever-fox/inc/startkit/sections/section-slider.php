<?php
/**
 * Slider section for the homepage.
 */
if ( ! function_exists( 'start_startkit_slider' ) ) :

	function start_startkit_slider() {
		
	function startkit_get_slider_default() {
	return apply_filters(
		'startkit_get_slider_default', json_encode(
			 array(
					array("image_url" => CLEVERFOX_PLUGIN_URL .'inc/startkit/images/slider/slider02.jpg',
					"link" => "#", 
					"title" => "Strengths of Successful Businesses", 
					"text" => "Randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anembarrassing hidden in the middle of text.", 
					"text2" => "Explore More",
					"id" => "customizer_repeater_00071" ), 
			)
		)
	);
}
		$default_content 		= startkit_get_slider_default();
		$slider					= get_theme_mod('slider',$default_content); 
		$slider_opacity			= get_theme_mod('slider_opacity','0.3'); 
		$hide_show_slider		= get_theme_mod('hide_show_slider','1'); 
		
		if($hide_show_slider == '1') {
		?>
	  <div class="row" id="lite-slite">
            <div class="col-md-12">
                <div class="header-slider">
					<?php
						if ( ! empty( $slider ) ) {
						$allowed_html = array(
						'br'     => array(),
						'em'     => array(),
						'strong' => array(),
						'b'      => array(),
						'i'      => array(),
						);
						$slider = json_decode( $slider );
						foreach ( $slider as $slide_item ) {
							$title = ! empty( $slide_item->title ) ? apply_filters( 'startkit_translate_single_string', $slide_item->title, 'slider section' ) : '';
							$text = ! empty( $slide_item->text ) ? apply_filters( 'startkit_translate_single_string', $slide_item->text, 'slider section' ) : '';
							$button = ! empty( $slide_item->text2) ? apply_filters( 'startkit_translate_single_string', $slide_item->text2,'Learn More' ) : 'Read More';
							$link = ! empty( $slide_item->link ) ? apply_filters( 'startkit_translate_single_string', $slide_item->link, 'slider section' ) : '';
							$image = ! empty( $slide_item->image_url ) ? apply_filters( 'startkit_translate_single_string', $slide_item->image_url, 'slider section' ) : '';
					?>
                    <div class="header-single-slider">
                      <figure>
							<?php if ( ! empty( $image ) ) : ?>
                            <img src="<?php echo esc_url( $image ); ?>" <?php if ( ! empty( $title ) ) : ?> alt="<?php echo esc_attr( $title ); ?>" title="<?php echo esc_attr( $title ); ?>" <?php endif; ?> />
							<?php endif; ?>
							<div class="content" style="background: rgba(35, 48, 73,<?php echo $slider_opacity; ?>);">
								<div class="slide-table">
                                    <div class="slide-table-cell">                                        
                                        <div class="container">
											<div class="slide-content slide-left" >
											<?php if ( ! empty( $title ) ) : ?>
													<h1 class="fadeInLeft delay-1 animated"><?php echo esc_html( $title ); ?></h1>
												<?php endif; ?>
												<?php if ( ! empty( $text ) ) : ?>
													<p class="fadeInLeft delay-2 animated"><?php echo esc_html( $text ); ?></p>
												<?php endif; ?>
												<a href="<?php echo esc_url( $link ); ?>" class="boxed-btn fadeInLeft delay-3 animated"><?php echo esc_html( $button ); ?><i class="icofont icofont-long-arrow-right"></i></a>
											</div>
										</div>
									</div>
								</div>
							</div>
                        </figure>
                    </div>
					<?php }} ?>
                </div>
            </div>
        </div>
		<?php } ?>
		</header>
		<?php 
		}
	endif;
if ( function_exists( 'start_startkit_slider' ) ) {
$section_priority = apply_filters( 'startkit_section_priority', 11, 'start_startkit_slider' );
add_action( 'startkit_sections', 'start_startkit_slider', absint( $section_priority ) );

}