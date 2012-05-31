<?php
/*
Plugin Name: Display Template Name
Description: Displays the name of the template used by the currently displayed page. Plugins very useful for developing your blog.
Version: 1.1
Author: Aurélien Chappard
Author URI: http://www.deefuse.fr/
License: GPL
Copyright: Aurélien Chappard
*/
if( !class_exists( 'Display_template_name' ) ) {
	class Display_template_name {
		public function __construct() {
			load_plugin_textdomain('display-template-name', false, basename(dirname(__FILE__)).'/lang' );
			
		}
		
		/**
		* Display the name on the top of the pages
		*/
		function displayTheTemplateName()
		{
			// Get the actual user
			$actualUser =  wp_get_current_user();
			if($actualUser->data != null)
			{
				// Css position according to the showing admin bar
				$top = ( is_admin_bar_showing() ? "29px" : "0px" );
				?>
					<style type="text/css">
						#debug-display-template-name{font-size: 14px; position: fixed; top: <?php echo $top;?>; left: 0px; background: #000; color: #FFF; padding: 5px; border: 1px solid #FFF; -moz-box-shadow: 5px 5px 5px 0px #CCCCCC;-webkit-box-shadow: 5px 5px 5px 0px #CCCCCC;-o-box-shadow: 5px 5px 5px 0px #CCCCCC;box-shadow: 5px 5px 5px 0px #CCCCCC;z-index: 99999}
					</style>
					 <div id="debug-display-template-name"><?php _e('Current template:','display-template-name'); ?> <?php echo $this->get_current_template();?></div>
				<?php
			}
			else
			{
				echo "prout";
			}
		}
		
		/**
		* Retrieve the current template name et save into a global variable current_theme_template
		*/

		function var_template_include( $t )
		{
		    $GLOBALS['current_theme_template'] = basename($t);
		    return $t;
		}
		
		/**
		* Grab the global variable et return it
		*/		
		function get_current_template( $echo = false )
		{
		    if( !isset( $GLOBALS['current_theme_template'] ) )
		        return false;
		    if( $echo )
		        echo $GLOBALS['current_theme_template'];
		    else
		        return $GLOBALS['current_theme_template'];
		}

	}
}


if (class_exists("Display_template_name")) {
	$display_template_name_plugin = new Display_template_name();
}

//Actions and Filters	
if (isset($display_template_name_plugin))
{
	//Actions
	add_action( 'wp_footer', array(&$display_template_name_plugin, 'displayTheTemplateName') );
	
	// Filter
	add_filter( 'template_include', array(&$display_template_name_plugin,'var_template_include'), 1000 );
}