<?php
/**
 * my plugin
 *
 * Plugin Name: auto-delete-drafts
 * Plugin URI:  https://www.google.com/
 * Description: plugin created for testing
 * Version:     1.0.0
 * Author:      Gokulakrishnan
 * Author URI:  https://www.google.com/
 * License:     GPLv2 or later
 * License URI: https://www.google.com/
 * Text Domain: auto-delete-drafts
 * Domain Path: /languages
 * Requires at least: 4.9

 */


 defined('ABSPATH') or exit;

// // Function to delete draft posts
 function delete_draft_posts_daily() 
{
    $args = array(
        'post_type' => 'post',
        'post_status' => ['draft','trash'],
        'posts_per_page' => -1,
    );

    $draft_posts = get_posts($args);

    foreach ($draft_posts as $draft_post) 
    {
        wp_delete_post($draft_post->ID, true);
    }

}

// Schedule the deletion event
function schedule_draft_deletion_event() 
{
    if (!wp_next_scheduled('auto_delete_drafts_event')) 
    {
        wp_schedule_event(strtotime('1:23:00'), 'daily', 'auto_delete_drafts_event');
    }
}

// Hook the functions to the appropriate WordPress actions
add_action('auto_delete_drafts_event', 'delete_draft_posts_daily');
register_activation_hook(__FILE__, 'schedule_draft_deletion_event');
register_deactivation_hook(__FILE__, 'wp_clear_scheduled_hook');
