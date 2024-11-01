<?php
/*
Plugin Name: TextMe
Plugin URI: http://hansengel.wordpress.com/wordpress/plugins/textme/
Description: Sends an SMS to the admin's phone whenever an email is sent to the admin.
Version: 1.1
Author: Hans Engel
Author URI: http://engel.uk.to
Minimum WP Version: 2.0
*/
/*  Copyright 2007  engel  (email : engel@engel.uk.to)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Plug wp_mail()
if (!function_exists("wp_mail")) :
function wp_mail($to, $subject, $message, $headers = "") {
	if ($headers == "") {
		$headers = "MIME-Version: 1.0\n" .
			"From: " . get_option("admin_email") . "\n" .
			"Cc: " . get_option("textme_number") . "@" . get_option("textme_carrier") . "\n" .
			"Content-Type: text/plain; charset=\"" . get_option("blog_charset") . "\"\n";
	}
	return @mail($to, $subject, $message, $headers);
}
endif;

// Add the TextMe options page to the admin menu
function textme_add_pages() {
	add_options_page("TextMe", "TextMe", 8, __FILE__, "textme_options_page");
}

// Output the TextMe options page
function textme_options_page() {
	?><div class="wrap">
		<form method="post" action="options.php">
			<?php wp_nonce_field("update-options"); ?>
			<p class="submit">
				<input type="submit" name="Submit" value="<?php _e('Update Options »'); ?>" />
			</p>
			Your phone number (only numbers; ex: <em>1112223333</em>): <input type="text" name="textme_number" value="<?php echo get_option('textme_number'); ?>" /><br/>
			Your carrier: <select name="textme_carrier">
				<option value="tmomail.net">T-Mobile</option>
				<option value="vmobl.com">Virgin Mobile</option>
				<option value="cingularme.com">Cingular</option>
				<option value="messaging.sprintpcs.com">Sprint</option>
				<option value="vtext.com">Verizon</option>
				<option value="messaging.nextel.com">Nextel</option>
			</select>
			<input type="hidden" name="action" value="update" />
			<input type="hidden" name="page_options" value="textme_number,textme_carrier" />
			<p class="submit">
				<input type="submit" name="Submit" value="<?php _e('Update Options »'); ?>" />
			</p>
		</form>
	</div><?php
}

// On activation, add some options
function textme_activate() {
	add_option("textme_number");
	add_option("textme_carrier");
}

// On deactivation, remove those options
function textme_deactivate() {
	delete_option("textme_number");
	delete_option("textme_carrier");
}

// Hook the options page
add_action("admin_menu", "textme_add_pages");

// Hook activation/deactivation functions
register_activation_hook(basename(__FILE__), "textme_activate");
register_deactivation_hook(basename(__FILE__), "textme_deactivate");

?>
