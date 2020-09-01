<?php
/**
 * Install demos page
 *
 * @package Popularis_Extra_Demo_Import
 * @category Core
 * @author PopularisWP
 */
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Start Class
class popularis_Install_Demos {

    /**
     * Start things up
     */
    public function __construct() {
        add_action('admin_menu', array($this, 'add_page'), 999);
    }

    /**
     * Add sub menu page for the custom CSS input
     *
     * @since 1.0.0
     */
    public function add_page() {


        $title = esc_html__('Install Demos', 'popularis-extra');


        add_theme_page(
                esc_html__('Install Demos', 'popularis-extra'),
                $title,
                'manage_options',
                'popularis-extra-panel-install-demos',
                array($this, 'create_admin_page')
        );
    }

    /**
     * Settings page output
     *
     * @since 1.0.0
     */
    public function create_admin_page() {

        // Theme branding
        $brand = 'PopularisWP'
        ?>

        <div class="popularis-demo-wrap wrap">

            <h2><?php echo esc_html($brand); ?> - <?php esc_html_e('Install Demos', 'popularis-extra'); ?></h2>
            <div class="updated notice-success popularis-extra-notice">
                <div class="notice-inner">
                    <div class="notice-content">
                        <p><?php esc_html_e('Are you ready to create an amazing website? ', 'popularis-extra'); ?> <a href="<?php echo esc_url(admin_url('admin.php?page=popularis_setup')); ?>" class="btn button-primary"><?php esc_html_e('Run the Setup Wizard', 'popularis-extra'); ?></a></p>

                    </div>
                </div>
            </div>
            <div class="theme-browser rendered">

                <?php
                // Vars
                $demos = Popularis_Extra_Demos::get_demos_data();
                $categories = Popularis_Extra_Demos::get_demo_all_categories($demos);

                ?>

                <?php if (!empty($categories)) : ?>
                    <div class="popularis-header-bar">
                        <nav class="popularis-navigation">
                            <ul>
                                <li class="active"><a href="#all" class="popularis-navigation-link"><?php esc_html_e('All', 'popularis-extra'); ?></a></li>
                                <?php foreach ($categories as $key => $name) : ?>
                                    <li><a href="#<?php echo esc_attr($key); ?>" class="popularis-navigation-link"><?php echo esc_html($name); ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </nav>
                        <div clas="popularis-search">
                            <input type="text" class="popularis-search-input" name="popularis-search" value="" placeholder="<?php esc_html_e('Search demos...', 'popularis-extra'); ?>">
                        </div>
                    </div>
                <?php endif; ?>

                <div class="themes wp-clearfix">

                    <?php
                    // Loop through all demos
                    foreach ($demos as $demo => $key) {

                        // Vars
                        $item_categories = Popularis_Extra_Demos::get_demo_item_categories($key);
                        ?>

                        <div class="theme-wrap" data-categories="<?php echo esc_attr($item_categories); ?>" data-name="<?php echo esc_attr(strtolower($key['demo_template'])); ?>">

                            <div class="theme popularis-open-popup" data-demo-id="<?php echo esc_attr(str_replace('-demo', '', $key['demo_template'])); ?>">

                                <div class="theme-screenshot">
                                    <img src="<?php echo esc_url($key['screenshot']); ?>" />

                                    <div class="demo-import-loader preview-all preview-all-<?php echo esc_attr(str_replace('-demo', '', $key['demo_template'])); ?>"></div>

                                    <div class="demo-import-loader preview-icon preview-<?php echo esc_attr(str_replace('-demo', '', $key['demo_template'])); ?>"><i class="custom-loader"></i></div>
                                    <?php if ( isset ($key['required_plugins']['premium']['0']['slug']) && $key['required_plugins']['premium']['0']['slug'] == 'popularis-pro') { ?>
                                        <div class="demo-import-pro"><?php esc_html_e('PRO', 'popularis-extra'); ?></div>
                                    <?php } ?>
                                </div>

                                <div class="theme-id-container">

                                    <h2 class="theme-name" id="<?php echo esc_attr(str_replace('-demo', '', $key['demo_template'])); ?>"><span><?php echo esc_html($key['demo_name']); ?></span></h2>

                                    <div class="theme-actions">
                                        <a class="button button-primary" href="https://populariswp.com/<?php echo esc_attr($key['demo_template']); ?>/" target="_blank"><?php esc_html_e('Live Preview', 'popularis-extra'); ?></a>
                                    </div>

                                </div>

                            </div>

                        </div>

                    <?php } ?>

                </div>

            </div>

        </div>

        <?php
    }

}

new popularis_Install_Demos();
