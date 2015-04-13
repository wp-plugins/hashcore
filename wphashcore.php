<?php
/*
Plugin Name: #Hashcore
Plugin URI: http://hashcore.com
Version: 1.4.0
Author: Hashcore.com
Author URI: http://www.hashcore.com
Description: This plugin allows WordPress users to automatically integrate their social media content from Twitter and Instagram.
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
// admin actions
if ( is_admin() ){ 

		// Hook for adding admin menu
		add_action( 'admin_menu', 'fc_op_page' );

		// Display the 'Settings' link in the plugin row on the installed plugins list page
		add_filter( 'plugin_action_links_'.plugin_basename(__FILE__), 'fc_admin_plugin_actions', -10);

} 
else 
{ // non-admin enqueues, actions, and filters

	 // hook for footer
	add_action('wp_footer', 'insert_js_tag');
	remove_filter( 'wp_footer', 'strip_tags' );
	 add_action('template_redirect', 'addDivToContent');
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
	
	$hidden_field_name = 'fc_submit_hidden';    
	$fch_text = 'fch_input_text';
	
	
	// Custom language title
	$custom_title_value = get_option('custom_title_value');
	
	// Custom recommended text
	$custom_recommend_value = get_option('custom_recommend_value');
	
	// Twitter user name variables
	$footer_field_name = 'fc_input_text';
	$fc_text = 'fc_input_text';
	$fc_val = get_option( $fc_text );
	
	// Instagram id variables
	$instagram_field_name = 'instagram_field_name';
	$instagram_input_text = 'instagram_input_text';
	$instagram_val = get_option( $instagram_input_text );
	
	//publisher ID variables
	$publisher_field_name = 'hc_input_text';	
	$hc_publisher = 'hc_publisher_text';
	$hc_val = get_option( $hc_publisher );
	
	// languages variables
	$publisher_languages  = 'hc_languages';
	$hc_languages = 'hc_language_options';
	$hc_lang = get_option($hc_languages);



// See if the user has posted us some information
// If they did, this hidden field will be set to 'Y'

if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' )
{

	$fc_val = $_POST[$footer_field_name]; // Read their posted value      
	update_option( $fc_text, $fc_val );  // Save the posted value in the database
	
	$instagram_val = $_POST[$instagram_field_name];
	update_option($instagram_input_text,$instagram_val);
	
	$hc_val = $_POST[$publisher_field_name]; 
	update_option( $hc_publisher, $hc_val );
	
	$hc_lang = $_POST[$publisher_languages]; 
	update_option( $hc_languages, $hc_lang );
	
	update_option('custom_title_value', $_POST['custom_title_value'] );
	
	update_option('custom_recommend_value', $_POST['custom_recommend_value'] );
	
	
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

<table> 
	<tr>
		<td><?php _e('Publisher ID', 'fc-menu' ); ?></td>
		<td>
			<input type="text" name="<?php echo $publisher_field_name; ?>" value="<?php echo $hc_val; ?>" />
			&nbsp;Please register <a href="http://hashcore.com/publishers-sign-up-form/" title="register with hashcore" target="_blank">here</a> to get your unique publisher ID
		</td>
	</tr>
	<tr>
		<td><?php _e('Twitter username', 'fc-menu' ); ?>&nbsp;@</td>
		<td>
			<input type="text" name="<?php echo $footer_field_name; ?>" value="<?php echo $fc_val; ?>" />	
			&nbsp;If left blank, defaults to HASHCORE_COM
		</td>
	</tr>
	<tr>
		<td><?php _e('Instagram ID', 'fc-menu' ); ?>&nbsp;</td>
		<td>
			<input type="text" name="<?php echo $instagram_field_name; ?>" value="<?php echo $instagram_val; ?>" />	
			You can find your id <a href="http://jelled.com/instagram/lookup-user-id" target="_blank">here</a>
		</td>
	</tr>
	
	<tr>
		<td><?php _e('Custom Title', 'fc-menu' ); ?></td>
		<td>
			<input type="text" name="custom_title_value" value="<?php echo stripslashes (get_option('custom_title_value')); ?>" />
			This text is displayed in the heading of the widget, defaults to What's Happening
		</td>
	</tr>
	<tr>
		<td><?php _e('Recommended by', 'fc-menu' ); ?></td>
		<td>
			<input type="text" name="custom_recommend_value" value="<?php echo stripslashes (get_option('custom_recommend_value')); ?>" />
			This text is displayed in the heading of the widget, defaults to Recommended by
		</td>
	</tr>
	
	<tr>
		<td><?php _e('Language', 'fc-menu' ); ?></td>
		<td>	
			<select name="<?php echo $publisher_languages; ?>">
				<?php echo language_options($hc_lang); ?>
			</select>
		</td>	
	</tr>
	
	<tr><td colspan="2"><?php submit_button(); ?></td></tr>

</table>


</form>

<h1><a target="_blank" href="http://dashboard.hashcore.com/">Go to reporting dashboard</a></h1>
<?php }


// Build array of links for rendering in installed plugins list
function fc_admin_plugin_actions($links) {

$fc_plugin_links = array(
		  '<a href="options-general.php?page=fcsettings">'.__('Settings').'</a>',
);

	return array_merge( $fc_plugin_links, $links );

}

// Display footer
function insert_js_tag() 
{
	$tag = '<script>';
	$tag .= '(function(w,d){';
	$tag .=  'w.pubcode = "'.get_option('hc_publisher_text').'";';
	$tag .=  'w.instagram = "'.get_option('instagram_input_text').'"; ';
	$tag .=  'w.twitter = "'.get_option('fc_input_text').'";';
	$tag .=  'var s=document.createElement("script"); s.async=true; s.src="http://c.hashcore.com/api/widget.js?r=" + Math.round(Math.random(1000,5000));';
	$tag .=  'var s2=document.getElementsByTagName("script")[0]; s2.parentNode.insertBefore(s,s2);';
	$tag .=  '})(window,document);';
	$tag .=  '</script>';
	
	print $tag;
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

function fc_instagram_id()
{
	if(get_option('instagram_id') == '')
	{
		return "xxxxxx";
	}
	else
	{
		return get_option('instagram_id');
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

function custom_language_title()
{
	if(get_option('custom_language_title') == '')
	{
		return "x";
	}
	else
	{
		return get_option('custom_language_title');
	}
}

function fc_languages()
{
	if(get_option('hc_language_options') == '')
	{
		return "en";
	}
	else
	{
		return get_option('hc_language_options');
	}
}

function language_options($set_lang)
{

	if(empty($set_lang) OR !isset($set_lang) OR $set_lang == "")
	{
		$default_lang = "en";
	}
	else
	{
		$default_lang = $set_lang;
	}
	
	$lang = array();

	$lang[0]=array('code'=>'aa', 'name'=>'Afar');
	$lang[1]=array('code'=>'ab', 'name'=>'Abkhazian');
	$lang[2]=array('code'=>'af', 'name'=>'Afrikaans');
	$lang[3]=array('code'=>'am', 'name'=>'Amharic');
	$lang[4]=array('code'=>'ar', 'name'=>'Arabic');
	$lang[5]=array('code'=>'as', 'name'=>'Assamese');
	$lang[6]=array('code'=>'ay', 'name'=>'Aymara');
	$lang[7]=array('code'=>'az', 'name'=>'Azerbaijani');
	$lang[8]=array('code'=>'ba', 'name'=>'Bashkir');
	$lang[9]=array('code'=>'be', 'name'=>'Byelorussian');
	$lang[10]=array('code'=>'bg', 'name'=>'Bulgarian');
	$lang[11]=array('code'=>'bh', 'name'=>'Bihari');
	$lang[12]=array('code'=>'bi', 'name'=>'Bislama');
	$lang[13]=array('code'=>'bn', 'name'=>'Bengali');
	$lang[14]=array('code'=>'bo', 'name'=>'Tibetan');
	$lang[15]=array('code'=>'br', 'name'=>'Breton');
	$lang[16]=array('code'=>'ca', 'name'=>'Catalan');
	$lang[17]=array('code'=>'co', 'name'=>'Corsican');
	$lang[18]=array('code'=>'cs', 'name'=>'Czech');
	$lang[19]=array('code'=>'cy', 'name'=>'Welch');
	$lang[20]=array('code'=>'da', 'name'=>'Danish');
	$lang[21]=array('code'=>'de', 'name'=>'German');
	$lang[22]=array('code'=>'dz', 'name'=>'Bhutani');
	$lang[23]=array('code'=>'el', 'name'=>'Greek');
	$lang[24]=array('code'=>'en', 'name'=>'English');
	$lang[25]=array('code'=>'eo', 'name'=>'Esperanto');
	$lang[26]=array('code'=>'es', 'name'=>'Spanish');
	$lang[27]=array('code'=>'et', 'name'=>'Estonian');
	$lang[28]=array('code'=>'eu', 'name'=>'Basque');
	$lang[29]=array('code'=>'fa', 'name'=>'Persian');
	$lang[30]=array('code'=>'fi', 'name'=>'Finnish');
	$lang[31]=array('code'=>'fj', 'name'=>'Fiji');
	$lang[32]=array('code'=>'fo', 'name'=>'Faeroese');
	$lang[33]=array('code'=>'fr', 'name'=>'French');
	$lang[34]=array('code'=>'fy', 'name'=>'Frisian');
	$lang[35]=array('code'=>'ga', 'name'=>'Irish');
	$lang[36]=array('code'=>'gd', 'name'=>'Scots Gaelic');
	$lang[37]=array('code'=>'gl', 'name'=>'Galician');
	$lang[38]=array('code'=>'gn', 'name'=>'Guarani');
	$lang[39]=array('code'=>'gu', 'name'=>'Gujarati');
	$lang[40]=array('code'=>'ha', 'name'=>'Hausa');
	$lang[41]=array('code'=>'hi', 'name'=>'Hindi');
	$lang[42]=array('code'=>'he', 'name'=>'Hebrew');
	$lang[43]=array('code'=>'hr', 'name'=>'Croatian');
	$lang[44]=array('code'=>'hu', 'name'=>'Hungarian');
	$lang[45]=array('code'=>'hy', 'name'=>'Armenian');
	$lang[46]=array('code'=>'ia', 'name'=>'Interlingua');
	$lang[47]=array('code'=>'id', 'name'=>'Indonesian');
	$lang[48]=array('code'=>'ie', 'name'=>'Interlingue');
	$lang[49]=array('code'=>'ik', 'name'=>'Inupiak');
	$lang[50]=array('code'=>'in', 'name'=>'former Indonesian');
	$lang[51]=array('code'=>'is', 'name'=>'Icelandic');
	$lang[52]=array('code'=>'it', 'name'=>'Italian');
	$lang[53]=array('code'=>'iu', 'name'=>'Inuktitut (Eskimo)');
	$lang[54]=array('code'=>'iw', 'name'=>'former Hebrew');
	$lang[55]=array('code'=>'ja', 'name'=>'Japanese');
	$lang[56]=array('code'=>'ji', 'name'=>'former Yiddish');
	$lang[57]=array('code'=>'jw', 'name'=>'Javanese');
	$lang[58]=array('code'=>'ka', 'name'=>'Georgian');
	$lang[59]=array('code'=>'kk', 'name'=>'Kazakh');
	$lang[60]=array('code'=>'kl', 'name'=>'Greenlandic');
	$lang[61]=array('code'=>'km', 'name'=>'Cambodian');
	$lang[62]=array('code'=>'kn', 'name'=>'Kannada');
	$lang[63]=array('code'=>'ko', 'name'=>'Korean');
	$lang[64]=array('code'=>'ks', 'name'=>'Kashmiri');
	$lang[65]=array('code'=>'ku', 'name'=>'Kurdish');
	$lang[66]=array('code'=>'ky', 'name'=>'Kirghiz');
	$lang[67]=array('code'=>'la', 'name'=>'Latin');
	$lang[68]=array('code'=>'ln', 'name'=>'Lingala');
	$lang[69]=array('code'=>'lo', 'name'=>'Laothian');
	$lang[70]=array('code'=>'lt', 'name'=>'Lithuanian');
	$lang[71]=array('code'=>'lv', 'name'=>'Latvian, Lettish');
	$lang[72]=array('code'=>'mg', 'name'=>'Malagasy');
	$lang[73]=array('code'=>'mi', 'name'=>'Maori');
	$lang[74]=array('code'=>'mk', 'name'=>'Macedonian');
	$lang[75]=array('code'=>'ml', 'name'=>'Malayalam');
	$lang[76]=array('code'=>'mn', 'name'=>'Mongolian');
	$lang[77]=array('code'=>'mo', 'name'=>'Moldavian');
	$lang[78]=array('code'=>'mr', 'name'=>'Marathi');
	$lang[79]=array('code'=>'ms', 'name'=>'Malay');
	$lang[80]=array('code'=>'mt', 'name'=>'Maltese');
	$lang[81]=array('code'=>'my', 'name'=>'Burmese');
	$lang[82]=array('code'=>'na', 'name'=>'Nauru');
	$lang[83]=array('code'=>'ne', 'name'=>'Nepali');
	$lang[84]=array('code'=>'nl', 'name'=>'Dutch');
	$lang[85]=array('code'=>'no', 'name'=>'Norwegian');
	$lang[86]=array('code'=>'oc', 'name'=>'Occitan');
	$lang[87]=array('code'=>'om', 'name'=>'(Afan) Oromo');
	$lang[88]=array('code'=>'or', 'name'=>'Oriya');
	$lang[89]=array('code'=>'pa', 'name'=>'Punjabi');
	$lang[90]=array('code'=>'pl', 'name'=>'Polish');
	$lang[91]=array('code'=>'ps', 'name'=>'Pashto, Pushto');
	$lang[92]=array('code'=>'pt', 'name'=>'Portuguese');
	$lang[93]=array('code'=>'qu', 'name'=>'Quechua');
	$lang[94]=array('code'=>'rm', 'name'=>'Rhaeto-Romance');
	$lang[95]=array('code'=>'rn', 'name'=>'Kirundi');
	$lang[96]=array('code'=>'ro', 'name'=>'Romanian');
	$lang[97]=array('code'=>'ru', 'name'=>'Russian');
	$lang[98]=array('code'=>'rw', 'name'=>'Kinyarwanda');
	$lang[99]=array('code'=>'sa', 'name'=>'Sanskrit');
	$lang[100]=array('code'=>'sd', 'name'=>'Sindhi');
	$lang[101]=array('code'=>'sg', 'name'=>'Sangro');
	$lang[102]=array('code'=>'sh', 'name'=>'Serbo-Croatian');
	$lang[103]=array('code'=>'si', 'name'=>'Singhalese');
	$lang[104]=array('code'=>'sk', 'name'=>'Slovak');
	$lang[105]=array('code'=>'sl', 'name'=>'Slovenian');
	$lang[106]=array('code'=>'sm', 'name'=>'Samoan');
	$lang[107]=array('code'=>'sn', 'name'=>'Shona');
	$lang[108]=array('code'=>'so', 'name'=>'Somali');
	$lang[109]=array('code'=>'sq', 'name'=>'Albanian');
	$lang[110]=array('code'=>'sr', 'name'=>'Serbian');
	$lang[111]=array('code'=>'ss', 'name'=>'Siswati');
	$lang[112]=array('code'=>'st', 'name'=>'Sesotho');
	$lang[113]=array('code'=>'su', 'name'=>'Sudanese');
	$lang[114]=array('code'=>'sv', 'name'=>'Swedish');
	$lang[115]=array('code'=>'sw', 'name'=>'Swahili');
	$lang[116]=array('code'=>'ta', 'name'=>'Tamil');
	$lang[117]=array('code'=>'te', 'name'=>'Tegulu');
	$lang[118]=array('code'=>'tg', 'name'=>'Tajik');
	$lang[119]=array('code'=>'th', 'name'=>'Thai');
	$lang[120]=array('code'=>'ti', 'name'=>'Tigrinya');
	$lang[121]=array('code'=>'tk', 'name'=>'Turkmen');
	$lang[122]=array('code'=>'tl', 'name'=>'Tagalog');
	$lang[123]=array('code'=>'tn', 'name'=>'Setswana');
	$lang[124]=array('code'=>'to', 'name'=>'Tonga');
	$lang[125]=array('code'=>'tr', 'name'=>'Turkish');
	$lang[126]=array('code'=>'ts', 'name'=>'Tsonga');
	$lang[127]=array('code'=>'tt', 'name'=>'Tatar');
	$lang[128]=array('code'=>'tw', 'name'=>'Twi');
	$lang[129]=array('code'=>'ug', 'name'=>'Uigur');
	$lang[130]=array('code'=>'uk', 'name'=>'Ukrainian');
	$lang[131]=array('code'=>'ur', 'name'=>'Urdu');
	$lang[132]=array('code'=>'uz', 'name'=>'Uzbek');
	$lang[133]=array('code'=>'vi', 'name'=>'Vietnamese');
	$lang[134]=array('code'=>'vo', 'name'=>'Volapuk');
	$lang[135]=array('code'=>'wo', 'name'=>'Wolof');
	$lang[136]=array('code'=>'xh', 'name'=>'Xhosa');
	$lang[137]=array('code'=>'yi', 'name'=>'Yiddish');
	$lang[138]=array('code'=>'yo', 'name'=>'Yoruba');
	$lang[139]=array('code'=>'za', 'name'=>'Zhuang');
	$lang[140]=array('code'=>'zh', 'name'=>'Chinese');
	$lang[141]=array('code'=>'zu', 'name'=>'Zulu');
	
	$lang_as_list = "";
	
	foreach($lang as $l)
	{
		$lang_as_list .= "<option value='".$l['code']."'";
		if($default_lang == $l['code'])
		{
			$lang_as_list .= " selected='selected'";
		}
		$lang_as_list .=">".$l['name']."</option>";
	}

	return $lang_as_list;	
}

function addDivToContent()
{	
   if(is_single() && is_main_query())   
   {
	  add_filter('the_content', 'filter_content');
	}	
}

function filter_content($content)
{		

	if(get_option('custom_title_value') == ""){
		$title = "What's Happening";
	}else{
		$title = stripslashes(get_option('custom_title_value'));
	}
	
	if(get_option('custom_recommend_value') == ""){
		$recommend = "Recommended by";
	}else{
		$recommend = stripslashes(get_option('custom_recommend_value'));
	}
	
	return $content . "<div id=\"hashcore-widget-container\"><h1><span  style=\"float:left;font-size:12px !important;\">".$title."</span> <span style=\"float:right;font-size:12px !important;\"><a class=\"hashcore-recommendby-link\" href=\"http://hashcore.com\" target=\"_blank\">".$recommend." Hashcore</a></span><br style=\"clear:both;\" /></h1></div>";
	
}	
?>