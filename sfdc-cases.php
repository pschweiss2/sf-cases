<?php
/*
Plugin Name: Web-To-Case Embed
Description: Embeds the web-to-case function from Salesforce to the admin dashboard in Wordpress. Enabling a user to generate a case from WordPress.
Version: 1.0
Author: Peter Schweiss
Author URI: pete.schweiss.net
License: GPL-2.0-or-later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// Create top-level menu item
function sfdc_cases_menu() {
    add_menu_page(
        'SFDC Cases',             // Page title
        'SFDC Cases',             // Menu title
        'manage_options',         // Capability required to access the menu
        'sfdc-cases',             // Menu slug
        'sfdc_cases_home_page',   // Callback function to display the settings page
        'dashicons-share-alt',    // Menu icon
        80                        // Menu position
    );

    // Create sub-menu items
    add_submenu_page(
        'sfdc-cases',                 // Parent menu slug
        'SFDC Cases Settings',        // Page title
        'Settings',                   // Menu title
        'manage_options',             // Capability required to access the page
        'sfdc-cases-settings',        // Menu slug
        'sfdc_cases_settings_page'    // Callback function to display the settings page
    );

    add_submenu_page(
        'sfdc-cases',             // Parent menu slug
        'SFDC Cases',             // Page title
        'Create A Case',          // Menu title
        'read',                   // Capability required to access the page
        'sfdc-cases-case',        // Menu slug
        'sfdc_cases_case_page'    // Callback function to display the case page
    );
}
add_action('admin_menu', 'sfdc_cases_menu');
// Home Page callback function
function sfdc_cases_home_page(){
    $url = plugin_dir_url(__FILE__);
?>
<div class="wrap">
<h1><img style="display: block; margin-left: auto; margin-right: auto;" src="http://pete-playground.flywheelsites.com/wp-content/uploads/2023/06/sfdc-logo.png" alt="" /></h1>
<h2>Thank you for downloading SFDC Web-To-Case!</h2>
<p>If you haven't done so, head to the settings page and input your Salesforce Org ID. Need help to find your org id? <a href="https://help.salesforce.com/s/articleView?id=000385215&amp;language=en_US&amp;type=1" target="_blank">Click Here to access the Salesforce help article on the topic.</a></p>
<p>Once you have input your Org ID, you can open cases directly from WordPress.</p>
<p>Version 1.0.0 only supports a basic case; future iterations may support fields such as Case Category, etc.</p>
<p>&nbsp;</p>
</div>
<?php
}
// Settings page callback function
function sfdc_cases_settings_page() {
    if (!current_user_can('manage_options')) {
        wp_die('Insufficient permissions to access this page.');
    }

    $orgid = get_option('sfdc_cases_orgid');

    if (isset($_POST['sfdc_cases_orgid'])) {
        $orgid = sanitize_text_field($_POST['sfdc_cases_orgid']);
        update_option('sfdc_cases_orgid', $orgid);
        echo '<div class="notice notice-success"><p>Settings saved. Organization ID: ' . $orgid . '</p></div>';
    }
    ?>

    <div class="wrap">
        <h1>SFDC Cases Settings</h1>

        <form method="post" action="">
            <label for="sfdc_cases_orgid">Salesforce Organization ID:</label>
            <input type="text" name="sfdc_cases_orgid" id="sfdc_cases_orgid" value="<?php echo esc_attr($orgid); ?>" />

            <?php submit_button('Save Settings'); ?>
        </form>
    </div>
    <?php
}

// Case page callback function
function sfdc_cases_case_page() {
    if (isset($_GET['success']) && $_GET['success'] == 1) {
        echo '<div class="notice notice-success"><p>Case submitted successfully!</p></div>';
    }

    $current_user = wp_get_current_user();
    $current_user_email = $current_user->user_email;
    ?>
    <div class="wrap">
        <h1>Case</h1>
        <p>Use the form below to submit a case:</p>

        <form action="https://webto.salesforce.com/servlet/servlet.WebToCase?encoding=UTF-8" method="POST">
            <input type="hidden" name="orgid" value="<?php echo esc_attr(get_option('sfdc_cases_orgid')); ?>">
            <input type="hidden" name="retURL" value="<?php echo esc_attr(admin_url('admin.php?page=sfdc-cases-case&success=1')); ?>">

            <label for="name">Contact Name</label>
            <input id="name" maxlength="80" name="name" size="20" type="text" /><br>

            <label for="email">Email</label>
            <input id="email" maxlength="80" name="email" size="20" type="text" value="<?php echo esc_attr($current_user_email); ?>" /><br>

            <label for="phone">Phone</label>
            <input id="phone" maxlength="40" name="phone" size="20" type="text" /><br>

            <label for="subject">Subject</label>
            <input id="subject" maxlength="80" name="subject" size="20" type="text" /><br>

            <label for="description">Description</label>
            <textarea name="description"></textarea><br>

            <input type="hidden" id="external" name="external" value="1" /><br>

            <input type="submit" name="submit">
        </form>
    </div>
    <?php
}

// Add shortcut to admin dashboard
function sfdc_cases_admin_dashboard_shortcut() {
    global $submenu;

    // Check if the SFDC Cases menu exists
    if (isset($submenu['sfdc-cases'])) {
        $submenu['index.php'][] = array(
            'SFDC Cases',
            'manage_options',
            'admin.php?page=sfdc-cases-case'
        );
    }
}
add_action('admin_init', 'sfdc_cases_admin_dashboard_shortcut');
