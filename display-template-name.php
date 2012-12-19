<?php
/*
Plugin Name: Display Template Name
Description: Displays the name of the template used by the currently displayed page. Plugins very useful for developing your blog.
Version: 1.3.1
Author: Aurélien Chappard
Author URI: http://www.deefuse.fr/
License: GPL
Copyright: Aurélien Chappard
*/
if( !class_exists( 'Display_template_name' ) ) {
	class Display_template_name {
		var $adminOptionsName = "DisplayTemplateNameAdminOptions";
		
		public function __construct() {
			load_plugin_textdomain('display-template-name', false, basename(dirname(__FILE__)).'/lang' );
			
		}
		
		
		//Returns an array of admin options
		function getAdminOptions() {
			$displayTemplateNameAdminOptions = array('position' => 'TL');
			$devOptions = get_option($this->adminOptionsName); 
			if (!empty($devOptions)) {
				foreach ($devOptions as $key => $option)
					$displayTemplateNameAdminOptions[$key] = $option;
			}
			update_option($this->adminOptionsName, $displayTemplateNameAdminOptions);
			return $displayTemplateNameAdminOptions;
		}

		function init() {
			$this->getAdminOptions();
		}

		
		function displayTplNameenqueue_my_styles()
		{
			wp_register_style( 'displayTPLNameStylesheet', plugins_url('stylesheet.css', __FILE__) );
			wp_enqueue_style( 'displayTPLNameStylesheet' );
		}
		
		
		//Prints out the admin page
		function printAdminPage()
		{
			$devOptions = $this->getAdminOptions();
			if (isset($_POST['update_displayTemplateNamePluginSettings']))
			{
				if (isset($_POST['news_position'])) {
					$devOptions['position'] = $_POST['news_position'];
				}
				
				update_option($this->adminOptionsName, $devOptions);
	
				?>
					<div class="updated"><p><strong><?php _e("The position have been saved.", "display-template-name");?></strong></p></div>
				<?php
			} ?>
			<div class=wrap>
				<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
					<h2>Display Template Name</h2>
					<?php
						$themes = get_themes();
						$current_theme = get_current_theme();
						$screenshotIMG = $themes[$current_theme]['Screenshot'];
																		
						$urlScreenShot = plugin_dir_url(__FILE__) . 'images/default.png';
						
						
						if( $screenshotIMG != '' )
						{
							$urlScreenShot = get_bloginfo('template_directory') . '/' . $screenshotIMG;
						}
					?>
					<p><?php _e("Select the position of the debug box.", "display-template-name") ?></p>
					<div id="tablePreviewDisplayTplName">
						
						<label for="displatTplName_TL">
							<div class="macPreviewDebug">
								<img src="<?php echo $urlScreenShot;?>" width="201" class="preview" height="150"/>
								<img src="<?php echo plugin_dir_url(__FILE__) . 'images/masque_tl.png';?>" class="maskDisplayTpl" />
							</div>
							<input type="radio" id="displatTplName_TL" name="news_position" value="TL" <?php if ($devOptions['position'] == "TL") { echo 'checked="checked"'; }?> />
							<?php _e("Top left", "display-template-name") ?>
						</label>
					
						<label for="displatTplName_TR">
							<div class="macPreviewDebug">
								<img src="<?php echo $urlScreenShot;?>" width="201" class="preview" height="150"/>
								<img src="<?php echo plugin_dir_url(__FILE__) . 'images/masque_tr.png';?>" class="maskDisplayTpl" />
							</div>
							<input type="radio" id="displatTplName_TR" name="news_position" value="TR" <?php if ($devOptions['position'] == "TR") {echo 'checked="checked"'; }?>/>
								<?php _e("Top right", "display-template-name") ?>
						</label>
					
						<label for="displatTplName_BL">
							<div class="macPreviewDebug">
								<img src="<?php echo $urlScreenShot;?>" width="201" class="preview" height="150"/>
								<img src="<?php echo plugin_dir_url(__FILE__) . 'images/masque_bl.png';?>" class="maskDisplayTpl" />
							</div>
							<input type="radio" id="displatTplName_BL" name="news_position" value="BL" <?php if ($devOptions['position'] == "BL") {echo 'checked="checked"'; }?>/>
								<?php _e("Bottom left", "display-template-name") ?>
						</label>
					
						<label for="displatTplName_BR">
							<div class="macPreviewDebug">
								<img src="<?php echo $urlScreenShot;?>" width="201" class="preview" height="150"/>
								<img src="<?php echo plugin_dir_url(__FILE__) . 'images/masque_br.png';?>" class="maskDisplayTpl" />
							</div>
							<input type="radio" id="displatTplName_BR" name="news_position" value="BR" <?php if ($devOptions['position'] == "BR") {echo 'checked="checked"'; }?>/>
							<?php _e("Bottom right", "display-template-name") ?>
						</label>
					</div>
					<div class="clearBoth"></div>
					<div class="submit">
						<input type="submit" class="button-primary" name="update_displayTemplateNamePluginSettings" value="<?php _e('Update Settings', 'display-template-name') ?>" />
					</div>
				</form>
			</div>
			<?php
		}//End function printAdminPage()

		
		/**
		* Display the name on the top of the pages
		*/
		function displayTheTemplateName()
		{
			// Get the actual user
			$actualUser =  wp_get_current_user();
			if($actualUser->data != null)
			{
				//
				$devOptions = $this->getAdminOptions();
				
				// Css position according to the showing admin bar
				$top = ( is_admin_bar_showing() ? "29px" : "0px" );
				switch ($devOptions['position'])
				{
					
					case 'BL' :
						$stringCss = 'bottom:0px; left:0px;';
						break;
					case 'BR' :
						$stringCss = 'bottom:0px; right:0px;';
						break;
					case 'TR' :
						$stringCss = 'top:' . $top . '; right:0px;';
						break;
					case 'TL' :
					default :
						$stringCss = 'top:' . $top . '; left:0px;';
						break;
				}
				
				?>
					<style type="text/css">
						#debug-display-template-name{font-size: 14px; position: fixed; <?php echo $stringCss;?> background: #000; color: #FFF; padding: 5px 7px; border: 1px solid #FFF;z-index: 99999}
						#debug-display-template-name a, #debug-display-template-name a:visited{
							color: #FFF;
							cursor: default;
							
						}
					</style>
					<?php 
						$templateInfos = $this->get_current_template();
					?>
					 <div id="debug-display-template-name"><?php _e('Current template:','display-template-name'); ?> <a href="#" title="<?php echo $templateInfos; ?>" target="_blank"><?php echo basename($templateInfos); ?></a></div>
				<?php
			}
		}
		
		
		/**
		* Retrieve the current template name et save into a global variable current_theme_template
		*/

		function var_template_include( $t )
		{
		 //   $GLOBALS['current_theme_template'] = basename($t);
		    $GLOBALS['current_theme_template'] = $t;
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
		
		function dispayTplName_settings_link($links)
		{
			$settings_link = '<a href="options-general.php?page=display-template-name.php">' . __('Settings', 'display-template-name') . '</a>'; 
			array_unshift($links, $settings_link); 
			return $links;
		}

	}
}


if (class_exists("Display_template_name")) {
	$display_template_name_plugin = new Display_template_name();
}

//Actions and Filters	
if (isset($display_template_name_plugin))
{
	register_activation_hook( __FILE__, array(&$display_template_name_plugin, 'init') );
	
	
	//Actions
	add_action( 'wp_footer', array(&$display_template_name_plugin, 'displayTheTemplateName') );
	add_action('admin_menu', 'DisplaYTemplateName_ap');
	add_action( 'admin_init', array(&$display_template_name_plugin, 'displayTplNameenqueue_my_styles') );
	// Filter
	add_filter( 'template_include', array(&$display_template_name_plugin,'var_template_include'), 1000 );
	
	$plugin = plugin_basename(__FILE__); 
	add_filter("plugin_action_links_$plugin", array(&$display_template_name_plugin,'dispayTplName_settings_link') );
}

//Initialize the admin panel
if (!function_exists("DisplaYTemplateName_ap")) {
	function DisplaYTemplateName_ap() {
		global $display_template_name_plugin;
		if (!isset($display_template_name_plugin)) {
			return;
		}
		if (function_exists('add_options_page')) {
			add_options_page('Display Template Name', 'Display Template Name', 9, basename(__FILE__), array(&$display_template_name_plugin, 'printAdminPage'));
		}
	}	
}