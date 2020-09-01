<?php
/**
 * cleverfox select control
 */
  if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return;
}
	class Cleverfox_Customizer_Select_Control extends WP_Customize_Control {

		public $type = 'hantus-select';

		public function enqueue() {
			wp_enqueue_script( 'selectize-js', CLEVERFOX_DIRECTORY_URI . 'controls/select/assets/js/selectize.min.js', array( 'jquery' ) );
			wp_enqueue_script( 'cleverfox-select-control', CLEVERFOX_DIRECTORY_URI . 'controls/select/assets/js/select-control.js', array( 'jquery', 'selectize-js' ), '', true );
			wp_enqueue_style( 'selectize-css', CLEVERFOX_DIRECTORY_URI . 'controls/select/selectize.default.css' );
		}

		public function render_content() {
		?>
			<label>
				<?php if ( ! empty( $this->label ) ) : ?>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<?php endif;
				if ( ! empty( $this->description ) ) : ?>
					<span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
				<?php endif; ?>
				<select <?php $this->link(); ?> class="hantus-select-control">
					<?php foreach ( $this->choices as $value => $label ) : ?>
						<option value="<?php echo esc_attr( $value ); ?>" <?php echo selected( $this->value(), $value, false ); ?> ><?php echo esc_html( $label ); ?></option>
					<?php endforeach; ?>
				</select>
			</label>
		<?php }

	}
