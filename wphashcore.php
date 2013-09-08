<?php
/*

Plugin Name: #Hashcore
Plugin URI: http://hashcore.com/add-a-social-media-plugin-to-your-wordpress-website/
Description: Inserts the hashcore code in the footer
Version: 1.0
Author: Hashcore.com
Author URI: http://www.hashcore.com
License: GPL2 or later
*/

/*

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

*/

if ( ! defined( 'ABSPATH' ) ) die();



// Hook for adding admin menus
if ( is_admin() ){ // admin actions

	// Hook for adding admin menu
	add_action( 'admin_menu', 'fc_op_page' );

	// Display the 'Settings' link in the plugin row on the installed plugins list page
	add_filter( 'plugin_action_links_'.plugin_basename(__FILE__), 'fc_admin_plugin_actions', -10);

} 
else 
{ // non-admin enqueues, actions, and filters

     // hook for footer
     add_action('wp_footer', 'fc_text_inputreal');
     remove_filter( 'wp_footer', 'strip_tags' );
}


// action function for above hook
function fc_op_page()
{
    // Add a new submenu under Settings:
    add_options_page(__('Hashcore Settings','fc-menu'), __('Hashcore Settings','fc-menu'), 'manage_options', 'fcsettings', 'fc_settings_page');

}
// fc_settings_page() displays the page content for the Header and Footer Commander submenu
function fc_settings_page() {

    //must check that the user has the required capability 
    if (!current_user_can('manage_options'))
    {
      wp_die( __('You do not have sufficient permissions to access this page.') );
    }

    // variables for the field and option names 
    $fc_text = 'fc_input_text';
    $hidden_field_name = 'fc_submit_hidden';
    $footer_field_name = 'fc_input_text';
    $publisher_field_name = 'hc_input_text';
    $fch_text = 'fch_input_text';
    $hc_publisher = 'hc_publisher_text';

	// Read in existing option value from database
    $fc_val = get_option( $fc_text );
    $hc_val = get_option( $hc_publisher );


// See if the user has posted us some information
// If they did, this hidden field will be set to 'Y'

if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' )
{

	$fc_val = $_POST[$footer_field_name]; // Read their posted value      
	update_option( $fc_text, $fc_val );  // Save the posted value in the database
	
	$hc_val = $_POST[$publisher_field_name]; 
	update_option( $hc_publisher, $hc_val );
	
	
	echo '<div class="updated"><p><strong>';
		 _e('settings saved.', 'fc-menu' );
	echo '</strong></p></div>';

}

    // Now display the settings editing screen
    echo '<div class="wrap">';    
    // icon for settings
     echo '<div id="icon-plugins" class="icon32"></div>';
    // header
    echo "<h2>" . __( 'Hashcore Settings', 'fc-menu' ) . "</h2>";    
 ?>


<form name="form1" method="post" action="">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<div>
	<?php _e('Twitter username', 'fc-menu' ); ?>
	<input type="text" name="<?php echo $footer_field_name; ?>" value="<?php echo $fc_val; ?>" />
</div>	
<div>Please enter your twitter username. If left blank, defaults to HASHCORE_COM</div>
<div>
	<?php _e('Publisher ID', 'fc-menu' ); ?>
	<input type="text" name="<?php echo $publisher_field_name; ?>" value="<?php echo $hc_val; ?>" />
</div>
<div>Please register <a href="http://hashcore.com/publishers-sign-up-form/" title="register with hashcore">here</a> to get your unique publisher ID</div>
<div><?php submit_button(); ?></div>
</form>
<?php }


// Build array of links for rendering in installed plugins list
function fc_admin_plugin_actions($links) {

$fc_plugin_links = array(
          '<a href="options-general.php?page=fcsettings">'.__('Settings').'</a>',
);

	return array_merge( $fc_plugin_links, $links );

}

// Display footer
      function fc_text_inputreal() 
      {
		echo '<script type="text/javascript">';
		echo 'var TWITTER_USERNAME="'.fc_twitter_username().'";';
		echo 'var publisher="' .fc_publisher_code().'";';
		echo '</script>';
		echo '<script src="http://hashcore.com/hashcorev1.1/hashcore.js" type="text/javascript"></script>';
	  }
function fc_twitter_username()
{
	if(get_option('fc_input_text') == '')
	{
		return "HASHCORE_COM";
	}
	else
	{
		return get_option('fc_input_text');
	}
}

function fc_publisher_code()
{
	if(get_option('hc_publisher_text') == '')
	{
		return "x";
	}
	else
	{
		return get_option('hc_publisher_text');
	}
}

	  

