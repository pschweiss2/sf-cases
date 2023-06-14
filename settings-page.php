<!-- settings-page.php -->
<div>
  <h2>SFDC | Cases Settings</h2>
  <form method="post" action="options.php">
    <?php settings_fields('sfdc_cases_options'); ?>
    <?php do_settings_sections('sfdc_cases_options'); ?>
    <label for="salesforce_url">Salesforce Web-to-Case URL:</label>
    <input type="text" id="salesforce_url" name="sfdc_cases_options[salesforce_url]" value="<?php echo esc_attr(get_option('sfdc_cases_options')['salesforce_url']); ?>" required>
    <?php submit_button(); ?>
  </form>
</div>
