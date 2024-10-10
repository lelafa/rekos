<?php 
// Allow SVG file uploads
add_filter( 'upload_mimes', 'custom_allow_svg_upload' );
function custom_allow_svg_upload( $mimes ) {
    $mimes['svg'] = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';

    return $mimes;
}