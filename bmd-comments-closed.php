<?php
/**
 * Global comment and pingback shutdown.
 * Existing approved comments remain in DB; no content is deleted.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Close submissions even when legacy posts or a plugin mark them as "open".
add_filter( 'comments_open', '__return_false', 999, 2 );
add_filter( 'pings_open', '__return_false', 999, 2 );
add_filter( 'pre_option_default_comment_status', function () { return 'closed'; }, 999 );
add_filter( 'pre_option_default_ping_status', function () { return 'closed'; }, 999 );

// Return an intentionally blank comments template (hides past discussion on
// public pages but keeps original records and admin moderation untouched).
add_filter( 'comments_template', function ( $template ) {
    if ( is_admin() || ! is_singular() ) { return $template; }
    return __DIR__ . '/bmd-empty-comments.php';
}, 999 );

// Avoid public "4 comments" links leading to a now-hidden discussion block.
add_filter( 'get_comments_number', function ( $count ) {
    if ( is_admin() ) { return $count; }
    return 0;
}, 999, 2 );
