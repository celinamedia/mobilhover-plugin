<?php
/**
 * Plugin Name: Phone Hover Animation
 * Plugin URI: https://rfo.mikkelsdesign.dk/
 * Description: Animated phone that rises on hover to reveal text below
 * Version: 1.1.4
 * Author: Celina Bækgaard
 * Author URI: https://github.com/celinamedia
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

/* Kun adgang fra wordpress og kan ikke tilgåes direkte */
if (!defined('ABSPATH')) {
    exit;
}

/* Definerer konstanter til pluginet */
define('PHONE_ANIMATION_VERSION', '1.1.4');
define('PHONE_ANIMATION_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('PHONE_ANIMATION_PLUGIN_URL', plugin_dir_url(__FILE__));

/* Tilføjer CSS filen til side med shortcode */
function phone_animation_enqueue_assets() {
    // // Loader kun CSS hvor shortcode bruges
    global $post;
    if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'phone_animation')) {
        wp_enqueue_style(
            'phone-animation-css',
            PHONE_ANIMATION_PLUGIN_URL . 'assets/css/phone-animation.css',
            array(),
            PHONE_ANIMATION_VERSION
        );
    }
} 
/* Indlæser pluginets CSS-fil på sider med [phone_animation]*/ 
add_action('wp_enqueue_scripts', 'phone_animation_enqueue_assets');

/* Shortcode til phone animation */
function phone_animation_shortcode($atts) {
    /* Sætter standardværdier for shortcode */
    $atts = shortcode_atts(array(
        'link' => '#',
        'title' => 'Capture Plastic Waste',
        'subtitle' => '',
        'phone_image' => '',
    ), $atts, 'phone_animation');
    
    /* vælger telefonbillede */
    if (!empty($atts['phone_image'])) {
        /* Bruger billedet fra linket */
        $phone_src = esc_url($atts['phone_image']);
    } else {
        /* Bruger standardbilledet */
        $phone_src = esc_url(PHONE_ANIMATION_PLUGIN_URL . 'assets/images/iphoneRFOT.png');
    }
    
    /* Gemmer indhold midlertidigt (output buffering) */
    ob_start();
    ?>
    
    <div class="phone-animation-wrapper">
        <div class="phone-container">
            <div class="text-wrapper">
                <div class="hidden-text">
                    <h3><?php echo esc_html($atts['title']); ?></h3>
                    <p><?php echo esc_html($atts['subtitle']); ?></p>
                </div>
            </div>
            <div class="phone-wrapper">
                <a href="<?php echo esc_url($atts['link']); ?>" class="phone-link">
                    <img src="<?php echo $phone_src; ?>" alt="Phone with the text Capture Plastic Waste" class="phone-image">
                </a>
            </div>
        </div>
    </div>
    
    <?php
    /* Viser det gemte indhold*/
    return ob_get_clean();
}
add_shortcode('phone_animation', 'phone_animation_shortcode');

/* Giver plugin et settings-link */
function phone_animation_settings_link($links) {
    $settings_link = '<a href="options-general.php?page=phone-animation-settings">Settings</a>';
    array_unshift($links, $settings_link);
    return $links;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'phone_animation_settings_link');
