<?php
if (!defined('ABSPATH')) {
    exit;
}

Class WizardAjax {

    public function __construct() {
        add_action('wp_ajax_popularis_wizard_ajax_get_demo_data', array($this, 'ajax_demo_data'));
    }

    public function ajax_demo_data() {


        if (!wp_verify_nonce($_GET['demo_data_nonce'], 'get-demo-data')) {
            die('This action was stopped for security purposes.');
        }

        // Database reset url
        if (is_plugin_active('wordpress-database-reset/wp-reset.php')) {
            $plugin_link = admin_url('tools.php?page=database-reset');
        } else {
            $plugin_link = admin_url('plugin-install.php?s=Wordpress+Database+Reset&tab=search');
        }

        // Get all demos
        $demos = Popularis_Extra_Demos::get_demos_data();

        // Get selected demo
        $demo = $_GET['demo_name'];

        // Get required plugins
        $plugins = $demos[$demo]['required_plugins'];

        // Get free plugins
        $free = $plugins['free'];

        // Get premium plugins
        $premium = $plugins['premium'];
        ?>

        <div id="popularis-demo-plugins">

            <h2 class="title"><?php echo sprintf(esc_html__('Import the %1$s demo', 'popularis-extra'), esc_attr($demos[$demo]['demo_name'])); ?></h2>

            <div class="popularis-popup-text">

                <p><?php
                    echo
                    sprintf(
                            esc_html__('Importing demo data allow you to quickly edit everything instead of creating content from scratch. It is recommended uploading sample data on a fresh WordPress install to prevent conflicts with your current content. You can use this plugin to reset your site if needed: %1$sWordpress Database Reset%2$s.', 'popularis-extra'), '<a href="' . $plugin_link . '" target="_blank">', '</a>'
                    );
                    ?></p>

                <div class="popularis-required-plugins-wrap">
                    <h3><?php esc_html_e('Required Plugins', 'popularis-extra'); ?></h3>
                    <p><?php esc_html_e('For your site to look exactly like this demo, the plugins below need to be activated.', 'popularis-extra'); ?></p>
                    <div class="popularis-required-plugins oe-plugin-installer">
                        <?php
                        Popularis_Extra_Demos::required_plugins($free, 'free');
                        Popularis_Extra_Demos::required_plugins($premium, 'premium');
                        ?>
                    </div>
                </div>

            </div>


        </div>

        <form method="post" id="popularis-demo-import-form">

            <input id="popularis_import_demo" type="hidden" name="popularis_import_demo" value="<?php echo esc_attr($demo); ?>" />

            <div class="popularis-demo-import-form-types">

                <h2 class="title"><?php esc_html_e('Select what you want to import:', 'popularis-extra'); ?></h2>

                <ul class="popularis-popup-text">
                    <li>
                        <label for="popularis_import_xml">
                            <input id="popularis_import_xml" type="checkbox" name="popularis_import_xml" checked="checked" />
                            <strong><?php esc_html_e('Import XML Data', 'popularis-extra'); ?></strong> (<?php esc_html_e('pages, posts, images, menus, etc...', 'popularis-extra'); ?>)
                        </label>
                    </li>

                    <li>
                        <label for="popularis_theme_settings">
                            <input id="popularis_theme_settings" type="checkbox" name="popularis_theme_settings" checked="checked" />
                            <strong><?php esc_html_e('Import Customizer Settings', 'popularis-extra'); ?></strong>
                        </label>
                    </li>

                    <li>
                        <label for="popularis_import_widgets">
                            <input id="popularis_import_widgets" type="checkbox" name="popularis_import_widgets" checked="checked" />
                            <strong><?php esc_html_e('Import Widgets', 'popularis-extra'); ?></strong>
                        </label>
                    </li>
                </ul>

            </div>

            <?php wp_nonce_field('popularis_import_demo_data_nonce', 'popularis_import_demo_data_nonce'); ?>
                        
            <?php if (!defined('TWP_PRO_CURRENT_VERSION') && $premium['0']['slug'] == 'popularis-pro') { ?>
                    <div class="popularis-button popularis-plugins-pro">
                        <a href="<?php echo esc_url('https://populariswp.com/product/popularis-pro/'); ?>" target="_blank" >
                            <?php esc_html_e('Install and activate Popularis PRO', 'popularis-extra'); ?>
                        </a>
                    </div>
                <?php } elseif (defined('TWP_PRO_CURRENT_VERSION') && !defined('TWP_SLT_PRO') && $premium['0']['slug'] == 'popularis-pro') { ?>
                    <div class="popularis-button popularis-plugins-pro">
                        <a href="<?php echo esc_url(network_admin_url('options-general.php?page=popularis-license-options')) ?>" >
                            <?php esc_html_e('Activate Popularis PRO license', 'popularis-extra'); ?>
                        </a>
                    </div>
                <?php } else { ?>
                    <input type="submit" name="submit" class="popularis-button popularis-import" value="<?php esc_html_e('Import', 'popularis-extra'); ?>"  />
                <?php } ?>

        </form>

        <div class="popularis-loader">
            <h2 class="title"><?php esc_html_e('The import process could take some time, please be patient', 'popularis-extra'); ?></h2>
            <div class="popularis-import-status popularis-popup-text"></div>
        </div>

        <div class="popularis-last">
            <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52"><circle class="checkmark-circle" cx="26" cy="26" r="25" fill="none"></circle><path class="checkmark-check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"></path></svg>
            <h3><?php esc_html_e('Demo Imported!', 'popularis-extra'); ?></h3>
        </div>
        <div class="popularis-error" style="display: none;">
                <p ><?php esc_html_e("The import didn't import well please contact the support.", 'popularis-extra'); ?></p>
            </div>
        </div>


        <?php
        die();
    }

}

new WizardAjax();
