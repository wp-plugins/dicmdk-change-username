<?php
/*
Plugin Name: Dicm.dk - Change username
Plugin URI: http://dicm.dk
Description: Allows site admin's to change username of any user wihtout using any other tools. Plugin requires Dicm.dk - Toolbox to work
Version: 1.2
Author: Kim Vinberg - dicm.dk
Author URI: http://dicm.dk
License: 
Free for personal use
*/

add_action('admin_menu', 'my_plugin_menu');
add_action( 'admin_init', 'dicmdktoolbox_change_username_settings_init' );


if(!function_exists('dicmdktoolbox_options_page')) {
function dicmdktoolbox_options_page(  ) { 

	?>
	<form action='options.php' method='post'>
		
		<h2>Dicm.dk - Toolbox</h2>
		
		<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
		?>
		
	</form>
	<?php

}
}

function my_plugin_menu() {
add_menu_page( 'Dicm.dk - Toolbox', 'Dicm.dk - Toolbox', 'manage_options', 'dicmdktoolbox', 'dicmdktoolbox_options_page', '', 0 );
//add_submenu_page( 'dicmdktoolbox', 'Change username', 'Change username', 'manage_options', 'my-secondary-slug', 'dicmdktoolbox_change_username_options_page');
}


function dicmdktoolbox_change_username_settings_init(  ) { 

  if ( @$_GET['settings-updated'] == 'true' && is_admin()) {
   	
	$options = get_option( 'dicmdktoolbox_settings' );

	$old_username = sanitize_text_field($options['dicmdktoolbox_change_username_text_field_0']);
	$new_username = sanitize_text_field($options['dicmdktoolbox_change_username_text_field_1']);
	
	 $old_user = get_user_by( 'login', $old_username );
	 $new_user = get_user_by( 'login', $new_username );
	 
	 if($old_user->ID != '' && $new_user->ID == '' && $new_username != '' && $old_username != '') { //If old user esists and no user with new name esists.

		global $wpdb;
		$wpdb->query( "UPDATE $wpdb->users SET user_login = '".$new_username."' WHERE user_login='".$old_username."';" );
	
	 }

	}
		
	register_setting( 'pluginPage', 'dicmdktoolbox_settings' );	

	add_settings_section(
		'dicmdktoolbox_change_username_pluginPage_section', 
		__( 'Change username', 'dicmdktoolbox' ), 
		'dicmdktoolbox_change_username_settings_section_callback', 
		'pluginPage'
	);

	add_settings_field( 
		'dicmdktoolbox_text_field_0', 
		__( 'Old username', 'dicmdktoolbox' ), 
		'dicmdktoolbox_change_username_text_field_0_render', 
		'pluginPage', 
		'dicmdktoolbox_change_username_pluginPage_section' 
	);	
	add_settings_field( 
		'dicmdktoolbox_text_field_1', 
		__( 'New username', 'dicmdktoolbox' ), 
		'dicmdktoolbox_change_username_text_field_1_render', 
		'pluginPage', 
		'dicmdktoolbox_change_username_pluginPage_section' 
	);

	add_settings_field( 
		'dicmdktoolbox_text_field_submit', 
		'', 
		'dicmdktoolbox_change_username_text_field_submit_render', 
		'pluginPage', 
		'dicmdktoolbox_change_username_pluginPage_section' 
	);


}


function dicmdktoolbox_change_username_text_field_0_render(  ) { 

	$options = get_option( 'dicmdktoolbox_change_username__settings' );
	?>
	<input type='text' name='dicmdktoolbox_settings[dicmdktoolbox_change_username_text_field_0]' value='<?php if($options['dicmdktoolbox_change_username_text_field_0'] == '' ) { echo "admin"; } else { echo $options['dicmdktoolbox_change_username_text_field_0']; } ?>'>
	<?php

}

function dicmdktoolbox_change_username_text_field_1_render(  ) { 

	$options = get_option( 'dicmdktoolbox_change_username__settings' );
	?>
	<input type='text' name='dicmdktoolbox_settings[dicmdktoolbox_change_username_text_field_1]' value='<?php echo $options['dicmdktoolbox_change_username_text_field_0']; ?>'>
	<?php

}

function dicmdktoolbox_change_username_text_field_submit_render(  ) { 

	submit_button();

}



function dicmdktoolbox_change_username_settings_section_callback(  ) { 

	echo __('Allows site administrators to change a username in 3 steps.<br>
		1: Enter Old username (example: admin)<br>
		2: Enter new username (Example: administrator)<br>
		3: Press "Save Changes".<br>
		The username is now changed.<br><br> <font color="red">IMPORTANT: If the change fail, you will no longer be able to login before manually changing the username (create a backup user before changing)</font>.', 'dicmdktoolbox' );

}


?>