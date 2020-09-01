<?php
// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * @review_dismiss()
 * @review_pending()
 * @popularis_extra_review_notice_message()
 * Make all the above functions working.
 */
function popularis_extra_review_notice() {

    popularis_extra_review_dismiss();
    popularis_extra_review_pending();

    $activation_time = get_site_option('popularis_extra_active_time');
    $review_dismissal = get_site_option('popularis_extra_review_dismiss');
    $maybe_later = get_site_option('popularis_extra_maybe_later');

    if ('yes' == $review_dismissal) {
        return;
    }

    if (!$activation_time) {
        add_site_option('popularis_extra_active_time', time());
    }

    $daysinseconds = 1209600; // 1209600 14 Days in seconds.
    if ('yes' == $maybe_later) {
        $daysinseconds = 2419200; // 28 Days in seconds.
    }

    if (time() - $activation_time > $daysinseconds) {
        add_action('admin_notices', 'popularis_extra_review_notice_message');
    }
}

//add_action('admin_init', 'popularis_extra_review_notice');

/**
 * For the notice preview.
 */
function popularis_extra_review_notice_message() {
    $scheme = (parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY)) ? '&' : '?';
    $url = $_SERVER['REQUEST_URI'] . $scheme . 'popularis_extra_review_dismiss=yes';
    $dismiss_url = wp_nonce_url($url, 'popularis-review-nonce');

    $_later_link = $_SERVER['REQUEST_URI'] . $scheme . 'popularis_extra_review_later=yes';
    $later_url = wp_nonce_url($_later_link, 'popularis-review-nonce');
    $theme = wp_get_theme();
    $themetemplate = $theme->template;
    $themename = $theme->name;
    ?>

    <div class="popularis-review-notice">
        <div class="popularis-review-thumbnail">
            <img src="<?php echo esc_url(POPULARIS_EXTRA_PLUGIN_URL) . 'img/et-logo.png'; ?>" alt="">
        </div>
        <div class="popularis-review-text">
            <h3><?php esc_html_e('Leave A Review?', 'popularis-extra') ?></h3>
            <p><?php echo sprintf(esc_html__('We hope you\'ve enjoyed using %1$s theme! Would you consider leaving us a review on WordPress.org?', 'popularis-extra'), esc_html($themename)) ?></p>
            <ul class="popularis-review-ul">
                <li>
                    <a href="https://wordpress.org/support/theme/<?php echo esc_html($themetemplate); ?>/reviews/?rate=5#new-post" target="_blank">
                        <span class="dashicons dashicons-external"></span>
                        <?php esc_html_e('Sure! I\'d love to!', 'popularis-extra') ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $dismiss_url ?>">
                        <span class="dashicons dashicons-smiley"></span>
                        <?php esc_html_e('I\'ve already left a review', 'popularis-extra') ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $later_url ?>">
                        <span class="dashicons dashicons-calendar-alt"></span>
                        <?php esc_html_e('Maybe Later', 'popularis-extra') ?>
                    </a>
                </li>
                <li>
                    <a href="https://populariswp.com/support/" target="_blank">
                        <span class="dashicons dashicons-sos"></span>
                        <?php esc_html_e('Found a bug!', 'popularis-extra') ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $dismiss_url ?>">
                        <span class="dashicons dashicons-dismiss"></span>
                        <?php esc_html_e('Never show again', 'popularis-extra') ?>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <?php
}

/**
 * For Dismiss! 
 */
function popularis_extra_review_dismiss() {

    if (!is_admin() ||
            !current_user_can('manage_options') ||
            !isset($_GET['_wpnonce']) ||
            !wp_verify_nonce(sanitize_key(wp_unslash($_GET['_wpnonce'])), 'popularis-review-nonce') ||
            !isset($_GET['popularis_extra_review_dismiss'])) {

        return;
    }

    add_site_option('popularis_extra_review_dismiss', 'yes');
}

/**
 * For Maybe Later Update.
 */
function popularis_extra_review_pending() {

    if (!is_admin() ||
            !current_user_can('manage_options') ||
            !isset($_GET['_wpnonce']) ||
            !wp_verify_nonce(sanitize_key(wp_unslash($_GET['_wpnonce'])), 'popularis-review-nonce') ||
            !isset($_GET['popularis_extra_review_later'])) {

        return;
    }
    // Reset Time to current time.
    update_site_option('popularis_extra_active_time', time());
    update_site_option('popularis_extra_maybe_later', 'yes');
}

function popularis_extra_pro_notice() {

    popularis_extra_pro_dismiss();

    $activation_time = get_site_option('popularis_extra_active_pro_time');

    if (!$activation_time) {
        add_site_option('popularis_extra_active_pro_time', time());
    }

    $daysinseconds = 432000; // 5 Days in seconds (432000).

    if (time() - $activation_time > $daysinseconds) {
        if (!popularis_extra_check_for_popularis_pro()) {
            add_action('admin_notices', 'popularis_extra_pro_notice_message');
        }
    }
}

add_action('admin_init', 'popularis_extra_pro_notice');

/**
 * For PRO notice 
 */
function popularis_extra_pro_notice_message() {
    $scheme = (parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY)) ? '&' : '?';
    $url = $_SERVER['REQUEST_URI'] . $scheme . 'popularis_extra_pro_dismiss=yes';
    $dismiss_url = wp_nonce_url($url, 'popularis-pro-nonce');
    ?>

    <div class="popularis-review-notice">
        <div class="popularis-review-thumbnail">
            <img src="<?php echo esc_url(POPULARIS_EXTRA_PLUGIN_URL) . 'img/popularis-logo.png'; ?>" alt="">
        </div>
        <div class="popularis-review-text">
            <h3><?php esc_html_e('Go PRO for More Features', 'popularis-extra') ?></h3>
            <p>
                <?php echo sprintf(esc_html__('Get the %1$s for more stunning elements, demos and customization options.', 'popularis-extra'), '<a href="https://populariswp.com/product/popularis-pro/" target="_blank">PRO version</a>') ?>
            </p>
            <ul class="popularis-review-ul">
                <li class="show-mor-message">
                    <a href="https://populariswp.com/product/popularis-pro/" target="_blank">
                        <span class="dashicons dashicons-external"></span>
                        <?php esc_html_e('Show me more', 'popularis-extra') ?>
                    </a>
                </li>
                <li class="hide-message">
                    <a href="<?php echo $dismiss_url ?>">
                        <span class="dashicons dashicons-smiley"></span>
                        <?php esc_html_e('Hide this message', 'popularis-extra') ?>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <?php
}

/**
 * For PRO Dismiss! 
 */
function popularis_extra_pro_dismiss() {

    if (!is_admin() ||
            !current_user_can('manage_options') ||
            !isset($_GET['_wpnonce']) ||
            !wp_verify_nonce(sanitize_key(wp_unslash($_GET['_wpnonce'])), 'popularis-pro-nonce') ||
            !isset($_GET['popularis_extra_pro_dismiss'])) {

        return;
    }
    $daysinseconds = 1209600; // 14 Days in seconds (1209600).
    $newtime = time() + $daysinseconds;
    update_site_option('popularis_extra_active_pro_time', $newtime);
}

/**
 * Sale
 */

function popularis_extra_pro_sale() {

    popularis_extra_pro_sale_dismiss();

    $activation_time = get_site_option('popularis_extra_active_pro_time_sale');

    if (!$activation_time) {
        add_site_option('popularis_extra_active_pro_time_sale', time());
    }

    $daysinseconds = 86400; // 1 Day in seconds.

    if (time() - $activation_time > $daysinseconds) {
        if (!popularis_extra_check_for_popularis_pro()) {
            add_action('admin_notices', 'popularis_extra_pro_notice_sale');
        }
    }
}

add_action('admin_init', 'popularis_extra_pro_sale');

/**
 * For PRO sale notice 
 */
function popularis_extra_pro_notice_sale() {
    $scheme = (parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY)) ? '&' : '?';
    $url = $_SERVER['REQUEST_URI'] . $scheme . 'popularis_extra_pro_sale_dismiss=yes';
    $dismiss_url = wp_nonce_url($url, 'popularis-pro-nonce-sale');
    ?>

    <div class="popularis-review-notice">
        <div class="popularis-review-thumbnail">
            <img src="<?php echo esc_url(POPULARIS_EXTRA_PLUGIN_URL) . 'img/popularis-logo.png'; ?>" alt="">
        </div>
        <div class="popularis-review-text">
            <h3><?php esc_html_e('Limited time SALE! Up to 35% OFF.', 'popularis-extra') ?></h3>
            <p>
                <?php echo sprintf(esc_html__('Get the %1$s for more stunning elements, demos and customization options.', 'popularis-extra'), '<a href="https://populariswp.com/product/popularis-pro/" target="_blank">PRO version</a>') ?>
            </p>
            <ul class="popularis-review-ul">
                <li class="show-mor-message">
                    <a href="https://populariswp.com/plans-pricing/" target="_blank">
                        <span class="dashicons dashicons-external"></span>
                        <?php esc_html_e('Show me more', 'popularis-extra') ?>
                    </a>
                </li>
                <li class="hide-message">
                    <a href="<?php echo $dismiss_url ?>">
                        <span class="dashicons dashicons-smiley"></span>
                        <?php esc_html_e('Hide this message', 'popularis-extra') ?>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <?php
}

/**
 * For PRO sale Dismiss! 
 */
function popularis_extra_pro_sale_dismiss() {

    if (!is_admin() ||
            !current_user_can('manage_options') ||
            !isset($_GET['_wpnonce']) ||
            !wp_verify_nonce(sanitize_key(wp_unslash($_GET['_wpnonce'])), 'popularis-pro-nonce-sale') ||
            !isset($_GET['popularis_extra_pro_sale_dismiss'])) {

        return;
    }
    $daysinseconds = 604800; // 7 Days in seconds.
    $newtime = time() + $daysinseconds;
    update_site_option('popularis_extra_active_pro_time_sale', $newtime);
}
