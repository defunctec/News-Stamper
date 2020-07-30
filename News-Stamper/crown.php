<?php

/*plugin name: News-Stamper
Plugin URI: https://developer.wordpress.org/
Description: Generate Non Fungible Tokens on the Crown blockchain using shortcode [news-stamper]
Version: 2.0
Author: Defunctec*/

// CSS calls
function plugin_css3() {
    wp_enqueue_style( 'css-handle', plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css' );
    wp_enqueue_style( 'css-handle', plugin_dir_url( __FILE__ ) . 'css/jquery.dataTables.css' );
    wp_enqueue_style( 'custom', plugin_dir_url( __FILE__ ) . 'css/custom.css' );
}
add_action( 'wp_enqueue_scripts', 'plugin_css3' );
// JS and CSS calls
function plugin_javascript3() {
    // enqueue the script
    wp_enqueue_script( 'jquery.min', plugin_dir_url( __FILE__ ) . 'js/jquery.min.js' );
    wp_enqueue_script( 'bootstrap.min', plugin_dir_url( __FILE__ ) . 'js/bootstrap.min.js' );
    wp_enqueue_script( 'popper.min', plugin_dir_url( __FILE__ ) . 'js/popper.min.js' );
    wp_enqueue_script( 'jquery.dataTables', plugin_dir_url( __FILE__ ) . 'js/jquery.dataTables.min.js' );
    wp_enqueue_script( 'custom', plugin_dir_url( __FILE__ ) . 'js/custom.js' );
}
add_action( 'wp_enqueue_scripts', 'plugin_javascript3' );
// Database section
global $jal_db_version;
$jal_db_version = '1.0';
function jal_install3() {
	global $wpdb;
	global $jal_db_version;
	$table_name = 'wp_crownform3';
	$charset_collate = $wpdb->get_charset_collate();
	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		Username tinytext NOT NULL,
		Email varchar(512) NOT NULL,
		NFTName varchar(512) NOT NULL,
		NFTID varchar(512) NOT NULL,
		NFTOwneraddress varchar(512) NOT NULL,
		NFTMetadataAdminAddress varchar(512) NOT NULL,
		nftToken varchar(512) NOT NULL,
		NFTMetadata varchar(512) NOT NULL,
		created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
		PRIMARY KEY  (id)
	) $charset_collate;";
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
	add_option( 'jal_db_version', $jal_db_version );
}
register_activation_hook( __FILE__, 'jal_install3' );
register_activation_hook( __FILE__, 'registration_form3' );
// HTML For plugin (This is what the user sees). 
function registration_form3()
{
	// WP get logged in user details
	$getUser = wp_get_current_user();
	// Get username
	$currentUser = $getUser->user_login;
	// Get user ID
	$getID = $getUser->ID;
	// Get logged in user email
	$currentEmail = $getUser->user_email;
	// Custom get current users owner address
	$getUserMeta = get_user_meta($getID, $key = 'get_user_crown_address', $single = true);
	// Get the current time
	$getTime = current_time('timestamp', $gmt = true);
	// Create random hash
	$randomHash = hash('sha256', $getTime, false);
	// Strip any slashes from metadata
	$metaText = stripslashes("{\"Article\":[{\"Headline\":\"EDIT\",\"Link\":\"EDIT\"}]}");
	// We do not want users to change metadata, so please use admin address (Server owner)
	$servOwnerAddress = "ADMINOWNERADDRESS";


	echo'<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div id="contact-form" class="crown-form">
				<form id="form" action="../wp-content/plugins/News-Stamper/ajaxform.php" method="post" name="crown-form">
					<div class="form-group" style="display:none">
						<label for="username">Username<span style="color:red;font-size: 15px;">*</span></label>
						<input type="text" class="form-control" id="Username" value="'.$currentUser.'" name="Username" readonly required>
					</div>
					<div class="form-group" style="display:none">
						<label for="email">Email Address<span style="color:red;font-size: 15px;">*</span></label>
						<input type="email" class="form-control" name="Email" value="'.$currentEmail.'" id="Email" readonly required">
					</div>
					<div class="form-group" style="display:none">
						<label for="nftname">NFT Protocol<span style="color:red;font-size: 15px;">*</span></label>
						<input type="text" class="form-control" name="NFTName" value="nbp" id="NFTName" readonly required>
					</div>
					<div class="form-group" style="display:none">
						<label for="nftid">Token Hash<span style="color:red;font-size: 15px;">*</span></label>
						<input type="text" class="form-control" value="'.$randomHash.'" name="NFTID" placeholder="" id="NFTID" readonly required>
					</div>
					<div class="form-group" style="display:none">
						<label for="nftownaddress">Creator Address<span style="color:red;font-size: 15px;">*</span></label>
						<input type="text" class="form-control" name="NFTOwneraddress" value="'.$getUserMeta.'" placeholder="Placing winner address here"  id="NFTOwneraddress" readonly required>
					</div>
					<div class="form-group" style="display:none">
						<label for="adminaddress">Creator Address<span style="color:red;font-size: 15px;">*</span></label>
						<input type="text" class="form-control" name="NFTMetadataAdminAddress" value="'.$servOwnerAddress.'" placeholder="MetadataAdminAddr" id="NFTMetadataAdminAddress" readonly required>
					</div>
					<div class="form-group">
						<label for="NFTMetadata">Please Enter the News Headline and Link.<span style="color:red;font-size: 15px;">*</span></label>
						<textarea class="form-control" style="height: 200px; font-size: medium;" name="NFTMetadata" placeholder="" id="NFTMetadata" required>'.$metaText.'</textarea>
					</div>
					<div class="form-group submit_btnn">
						<input name="submitbtn" type="submit" class="btn btn-danger" value="Submit" id="submit_form"/>
						<a class="btn btn-danger" href="https://YOURURL/search/'.@$_GET['page_id'].'" id="submit_form">Get All list</a>
					</div>
				</form>
				<span class="hidden"></span>
				<img src="" id="loading-img">
				<div class="response_msg"></div>
				</div>
			</div>
		</div>
	</div>
	<div class="selected_from"></div>';
	return @$_GET['news-stamper'];
	//	echo @$_GET['news-stamper'];
}

	add_action( 'register_form', 'registration_form3' );
	add_shortcode('news-stamper','registration_form3');

?>