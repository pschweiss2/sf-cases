<?php
/**
 * Plugin Name: SFDC | Cases
 * Description: Submit cases to Salesforce using a web-to-case form.
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: Your Website
 */

// Plugin code goes here...
// sfdc-cases.php
// ...

// Function to add the main plugin page

function sfdc_cases_add_main_page() {
    add_menu_page(
      'SFDC | Cases',
      'SFDC | Cases',
      'manage_options',
      'sfdc-cases',
      'sfdc_cases_render_main_page',
      'dashicons-external',
      20
    );
  }
  
  // Function to render the main plugin page
  function sfdc_cases_render_main_page() {
    include 'main-page.php';
  }
  
  // Function to add the settings page
  function sfdc_cases_add_settings_page() {
    add_submenu_page(
      'sfdc-cases',
      'SFDC | Cases Settings',
      'Settings',
      'manage_options',
      'sfdc-cases-settings',
      'sfdc_cases_render_settings_page'
    );
  }
  
// Function to render the settings page
function sfdc_cases_render_settings_page() {
    // Check if the current user is an administrator
    if (!current_user_can('manage_options')) {
      wp_die('You do not have sufficient permissions to access this page.');
    }
  
    include 'settings-page.php';
  }
  
  // Hook the menu pages
  add_action('admin_menu', 'sfdc_cases
  