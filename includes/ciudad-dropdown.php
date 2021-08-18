<?php
/**
[ciudad] and [ciudad*]
**/

/* FORM_TAG HANDLER */
add_action( 'wpcf7_init', 'dycdc_add_form_tag_ciudad' );
function dycdc_add_form_tag_ciudad() {
	wpcf7_add_form_tag(
		array( 'ciudad', 'ciudad*'),
		'dycdc_ciudad_form_tag_handler', array( 'name-attr' => true ) );
	}
	function dycdc_ciudad_form_tag_handler( $tag ) {
		if ( empty( $tag->name ) ) {
			return '';
		}
		$options=$tag->options;
		$validation_error = wpcf7_get_validation_error( $tag->name );
		$class = wpcf7_form_controls_class( $tag->type, 'wpcf7-select ciudad' );
		$atts = array();
		$atts['class'] = $tag->get_class_option( $class );
		$atts['id'] = $tag->get_id_option();
		if ( $tag->is_required() ) {
			$atts['aria-required'] = 'true';
		}
		$atts['aria-invalid'] = $validation_error ? 'true' : 'false';
		$atts['name'] = $tag->name;
		$atts = wpcf7_format_atts( $atts );
		$st_tag = wpcf7_scan_form_tags(array('type' => array('departamento*','departamento')));
		if($st_tag)
		{
			$html='<span class="wpcf7-form-control-wrap ciudad '.$tag->name.'">';
			$html.='<select '.$atts.' disabled>';
			$html.='<option value="0">---</option>';
			$html.='</select></span>';
		}
		else
		{
			$html='Error: Country and State Field Must be available.';
		}
		return $html;
	}
	/* VALIDATION FILTER */
	add_filter( 'wpcf7_validate_ciudad', 'dycdc_ciudad_validation_filter', 10, 2 );
	add_filter( 'wpcf7_validate_ciudad*', 'dycdc_ciudad_validation_filter', 10, 2 );
	function dycdc_ciudad_validation_filter( $result, $tag ) {
		$type = $tag->type;
		$name = $tag->name;
		$value = sanitize_text_field($_POST[$name]);
		if ( $tag->is_required() && '0' == $value ) {
			$result->invalidate( $tag, 'Please Select City.' );
		}
		return $result;
	}
	/* TAG GENERATOR */
	add_action( 'wpcf7_admin_init', 'dycdc_add_tag_generator_ciudad', 20 );
	function dycdc_add_tag_generator_ciudad() {
		$tag_generator = WPCF7_TagGenerator::get_instance();
		$tag_generator->add( 'ciudad', __( 'ciudades', 'dycdc_' ),
		'dycdc_tag_generator_ciudad' );
	}
	function dycdc_tag_generator_ciudad( $contact_form, $args = '' ) {
		$args = wp_parse_args( $args, array() );
		$type = 'ciudad';
		$description = __( "Select con las ciudades de Chile", 'dycdc_' );
		$desc_link = '';
		?>
		<div class="control-box">
			<fieldset>
				<legend><?php echo sprintf( esc_html( $description ), $desc_link ); ?></legend>
				<table class="form-table">
					<tbody>
						<tr>
							<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-name' ); ?>"><?php echo esc_html( __( 'Name', 'dycdc_' ) ); ?></label></th>
							<td><input type="text" name="name" class="tg-name oneline" id="<?php echo esc_attr( $args['content'] . '-name' ); ?>" /></td>
						</tr>
						<tr>
							<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-id' ); ?>"><?php echo esc_html( __( 'Id attribute', 'dycdc_' ) ); ?></label></th>
							<td><input type="text" name="id" class="idvalue oneline option" id="<?php echo esc_attr( $args['content'] . '-id' ); ?>" /></td>
						</tr>
						<tr>
							<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-class' ); ?>"><?php echo esc_html( __( 'Class attribute', 'dycdc_' ) ); ?></label></th>
							<td><input type="text" name="class" class="classvalue oneline option" id="<?php echo esc_attr( $args['content'] . '-class' ); ?>" /></td>
						</tr>
					</tbody>
				</table>
			</fieldset>
		</div>
		<div class="insert-box">
			<input type="text" name="<?php echo $type; ?>" class="tag code" onfocus="this.select()" />
			<div class="submitbox">
				<input type="button" class="button button-primary insert-tag" value="<?php echo esc_attr( __( 'Insert Tag', 'dycdc_' ) ); ?>" />
			</div>
			<br class="clear" />
			<p class="description mail-tag"><label for="<?php echo esc_attr( $args['content'] . '-mailtag' ); ?>"><?php echo sprintf( esc_html( __( "To use the value input through this field in a mail field, you need to insert the corresponding mail-tag (%s) into the field on the Mail tab.", 'dycdc_' ) ), '<strong><span class="mail-tag"></span></strong>' ); ?><input type="text" class="mail-tag code hidden" readonly="readonly" id="<?php echo esc_attr( $args['content'] . '-mailtag' ); ?>" /></label></p>
		</div>
		<?php
	}