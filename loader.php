<?php
/*
Plugin Name: Wplms Unattemp Question Evaluation
Plugin URI: http://www.VibeThemes.com
Description: This is the Wplms Unattemp Question Evaluation for WPLMS WordPress Theme by VibeThemes
Version: 1.0.0
Requires at least: WP 3.8, BuddyPress 1.9 
Tested up to: 4.8
License: (Themeforest License : http://themeforest.net/licenses)
Author: Mr.Vibe 
Author URI: http://www.VibeThemes.com
Network: false
Text Domain: vibe
Domain Path: /languages/
*/

add_action('plugins_loaded','load_files_for_unattemp_question');
function load_files_for_unattemp_question(){
	include_once 'includes/filters.php';  // should be on first to run
	include_once 'includes/actions.php';
	include_once 'includes/ajax.php';
}
