<?php
include( 'includes/scripts.php' );
include( 'includes/additional.php' );
include( 'includes/disable_comments.php' );

// ACF
if ( function_exists( 'acf_add_options_sub_page' ) ) {
	acf_add_options_sub_page( 'Theme settings' );
}

// Menus
add_action( 'init', 'register_theme_menus' );
function register_theme_menus()
{
	register_nav_menus( [
		'main-nav' => __( 'Main menu' ),
		'footer-nav' => __( 'Footer menu' ),
	] );
}

function get_menu_items_by_registered_slug( $menu_slug )
{
	$menu_items = [];
	
	if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[ $menu_slug ] ) ) {
		$menu = get_term( $locations[ $menu_slug ] );
		$menu_items = wp_get_nav_menu_items( $menu -> term_id );
	}
	
	// Filtering
	
	$filtered_menu_items = [];
	
	$parent_menu_items = [];
	
	foreach ( $menu_items as $menu_item ) {
		if ( $menu_item -> menu_item_parent == '0' ) {
			$filtered_menu_items[] = $menu_item;
		} else {
			$parent_menu_item = $parent_menu_items[ $menu_item -> menu_item_parent ];
			if ( !isset( $parent_menu_item -> children ) ) {
				$parent_menu_item -> children = [];
			}
			$parent_menu_item -> children[] = $menu_item;
		}
		$parent_menu_items[ $menu_item -> ID ] = $menu_item;
	}
	
	return $filtered_menu_items;
}

// Contact Form Submission
add_action( 'wp_ajax_contact_form_submission', 'contact_form_submission' );
add_action( 'wp_ajax_nopriv_contact_form_submission', 'contact_form_submission' );
function contact_form_submission()
{
	$name = sanitize_text_field( $_POST[ 'name' ] );
	$email = sanitize_email( $_POST[ 'email' ] );
	$project = ( isset( $_POST[ 'project' ] ) && $_POST[ 'project' ] ) ? json_decode( str_replace( '\"', '"', $_POST[ 'project' ] ), true ) : '';
	$message = ( isset( $_POST[ 'message' ] ) && $_POST[ 'message' ] ) ? sanitize_text_field( $_POST[ 'message' ] ) : '';
	$url = $_POST[ 'url' ];
	
	$to = get_field( 'contact_form_email_to_send_submissions_to', 'options' );
	
	if ( !$to ) {
		$to = 'MAIL@MAIL.COM';
	}
	
	$subject = 'Contact form submission | ' . $email;
	$message_to_send = 'Name: ' . $name . '<br /><br />Email: ' . $email . '<br /><br />Message: ' . $message . '<br /><br />Project(s): ' . ( ( is_array( $project ) ? implode( ', ', $project ) : $project ) . '<br /><br /><br />URL: ' . $url );
	$headers = [ 'Content-Type: text/html; charset=UTF-8' ];
	
	$success = wp_mail( $to, $subject, $message_to_send, $headers );
	
	die();
}

