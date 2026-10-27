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

// One-time persistent closure of historical WordPress content (including
// public custom post types). Save old states so the DB operation is reversible.
add_action( 'init', function () {
    if ( get_option( 'bmd_all_comments_closed_20261027', false ) ) { return; }
    global $wpdb;
    $sql_where = "(comment_status = 'open' OR ping_status = 'open')";
    $previous = $wpdb->get_results(
        "SELECT ID, comment_status, ping_status FROM {$wpdb->posts} WHERE {$sql_where}",
        ARRAY_A
    );
    if ( ! is_array( $previous ) ) { return; }
    $backup_key = 'bmd_previous_open_comment_states_20261027';
    if ( get_option( $backup_key, null ) === null ) {
        if ( ! add_option( $backup_key, $previous, '', false ) ) { return; }
    }
    if ( ! update_option( 'default_comment_status', 'closed' ) &&
         get_option( 'default_comment_status' ) !== 'closed' ) { return; }
    if ( ! update_option( 'default_ping_status', 'closed' ) &&
         get_option( 'default_ping_status' ) !== 'closed' ) { return; }

    $affected = $wpdb->query(
        "UPDATE {$wpdb->posts} SET comment_status='closed', ping_status='closed'
         WHERE {$sql_where}"
    );
    if ( $affected === false ) { return; }
    update_option( 'bmd_all_comments_closed_20261027', array(
        'closed_count' => (int) $affected,
        'backed_up_count' => count( $previous ),
        'closed_at' => gmdate( 'Y-m-d H:i:s' ),
    ), false );
}, 25 );

// Temporary counts-only verification endpoint, removed after one deployment.
add_action( 'rest_api_init', function () {
    register_rest_route( 'bmd-performance/v1', '/closed-comments-audit', array(
        'methods' => 'GET',
        'permission_callback' => '__return_true',
        'callback' => function () {
            global $wpdb;
            return rest_ensure_response( array(
                'new_posts_default' => get_option( 'default_comment_status' ),
                'default_pingback_status' => get_option( 'default_ping_status' ),
                'open_posts_remaining' => (int) $wpdb->get_var(
                    "SELECT COUNT(*) FROM {$wpdb->posts} WHERE comment_status='open' OR ping_status='open'"
                ),
                'existing_comment_records' => (int) $wpdb->get_var(
                    "SELECT COUNT(*) FROM {$wpdb->comments}"
                ),
                'closed_result' => get_option( 'bmd_all_comments_closed_20261027', false ),
            ) );
        },
    ) );
} );
