<?php
class symbiostock_widgets extends WP_Widget {
	
	public $content = null;
	
	public function __construct() {
		//widget actual process
		$this->content = '';
		
		parent::__construct(
		
			'symbiostock_widget',
			'symbiostock_widgets',
			array(
				'description' => __( 'symbiostock Multi-Purpose Widget' ), 'symbiostock'
			)
		
		);
		
		}
	
	public function form( $instance ) {
		//outputs options form on admin
		}	
	public function update( $new_instance, $old_instance ) {
		//process widget options to be saved
		}
	public function widget( $args, $instance ) {
		//outputs the content of the widget
		}
	}
add_action( 'widgets_init', create_function( '', 'register_widget( "symbiostock_widget" );' ) );
?>