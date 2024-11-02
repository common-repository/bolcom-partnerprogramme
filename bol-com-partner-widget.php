<?php
/**
 * Plugin Name
 *
 * @package           BolPartnerWidget
 * @author            IMEGO
 * @license           GPL-2.0+
 * @link              http://imego.nl
 * @copyright         2014 Imego
 */

class BolPartnerWidget extends WP_Widget {
	protected $defaults;

	function __construct() {
		global $bolPPSearch;

		$this->defaults = array(
			'title'                   => 'Bol.com',
		);

		$widget_ops = array(
			'classname'   => 'js--bol-pp-results widget bol-com-product-widget',
			'description' => __( 'Bol.com Partner Programme', 'imego' ),
		);

		$control_ops = array(
			'id_base' => 'bol-com-programme',
		);

		parent::__construct( 'bol-com-programme', __( 'Bol.com partner programme', 'imego' ), $widget_ops, $control_ops );
	}

	function widget ($args, $instance)  {
		global $bolPPSearch;
		global $wp_query, $_genesis_displayed_ids;

		extract( $args );

		//* Merge with defaults
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		?><section class="widget bol-com-programme">
			<div class="widget-wrap"><?php
				if ( ! empty( $instance['title'] ) )
					echo $before_title . apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) . $after_title;

				$bolPPSearch->setTmpLayoutSettings($bolPPSearch->getOptions($bolPPSearch->getLayoutSettings()));
				print $bolPPSearch->printResult();
			?></div>
		</section><?php
	}
	function update( $new_instance, $old_instance ) {

		$new_instance['title']     = strip_tags( $new_instance['title'] );
		return $new_instance;

	}
	function form( $instance ) {

		//* Merge with defaults
		$instance = wp_parse_args( (array) $instance, $this->defaults );
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'genesis' ); ?>:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" class="widefat" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'title_color' ); ?>"><?php _e( 'Title Colour', 'genesis' ); ?>:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'title_color' ); ?>" name="<?php echo $this->get_field_name( 'title_color' ); ?>" value="<?php echo esc_attr( $instance['title_color'] ); ?>" class="widefat" />
		</p>
		<?php
	}
}
