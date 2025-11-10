<?php
/**
 * Plugin Name: Patterson Subsidiary Navigation
 * Plugin URI: https://github.com/patterson/navigation
 * Description: Universal Patterson nav + brand-specific main navigation system with mega-menu dropdowns, mobile menu, and accessibility features.
 * Version: 1.0.0
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Author: Patterson Companies
 * Author URI: https://patenergy.com
 * License: Proprietary
 * Text Domain: patterson-nav
 * Domain Path: /languages
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('PATTERSON_NAV_VERSION', '1.0.0');
define('PATTERSON_NAV_PLUGIN_FILE', __FILE__);
define('PATTERSON_NAV_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('PATTERSON_NAV_PLUGIN_URL', plugin_dir_url(__FILE__));
define('PATTERSON_NAV_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * Main Patterson Navigation Class
 */
class Patterson_Navigation {
    
    /**
     * Single instance of the class
     */
    private static $instance = null;
    
    /**
     * Get instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    private function __construct() {
        $this->includes();
        $this->init_hooks();
    }
    
    /**
     * Include required files
     */
    private function includes() {
        require_once PATTERSON_NAV_PLUGIN_DIR . 'includes/class-menu-walker.php';
        require_once PATTERSON_NAV_PLUGIN_DIR . 'includes/class-admin.php';
        require_once PATTERSON_NAV_PLUGIN_DIR . 'includes/class-renderer.php';
        require_once PATTERSON_NAV_PLUGIN_DIR . 'includes/class-meta-boxes.php';
    }
    
    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Register navigation menus
        add_action('after_setup_theme', array($this, 'register_nav_menus'));
        
        // Enqueue assets
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
        
        // Add filter to load custom menu item fields (needed on frontend)
        add_filter('wp_setup_nav_menu_item', array($this, 'add_menu_item_custom_fields'));
        
        // Initialize admin
        if (is_admin()) {
            new Patterson_Nav_Admin();
            new Patterson_Nav_Meta_Boxes();
        }
        
        // Add shortcode for rendering navigation
        add_shortcode('patterson_navigation', array('Patterson_Nav_Renderer', 'render_navigation'));
        
        // Prevent wpautop from adding <p> tags around shortcode output
        add_filter('the_content', array($this, 'remove_wpautop_for_shortcode'), 9);
        
        // Add theme support check
        add_action('after_setup_theme', array($this, 'check_theme_support'));
    }
    
    /**
     * Register navigation menu locations
     */
    public function register_nav_menus() {
        register_nav_menus(array(
            'patterson-main-nav' => __('Patterson Main Navigation', 'patterson-nav'),
        ));
    }
    
    /**
     * Enqueue frontend assets
     */
    public function enqueue_frontend_assets() {
        // Enqueue Adobe Typekit fonts (required for Patterson brand fonts)
        wp_enqueue_style(
            'patterson-typekit',
            'https://use.typekit.net/akz7boc.css',
            array(),
            null
        );
        
        // Enqueue design tokens (required)
        wp_enqueue_style(
            'patterson-nav-tokens',
            PATTERSON_NAV_PLUGIN_URL . 'assets/css/tokens.css',
            array('patterson-typekit'),
            PATTERSON_NAV_VERSION
        );
        
        // Enqueue navigation CSS
        wp_enqueue_style(
            'patterson-nav-styles',
            PATTERSON_NAV_PLUGIN_URL . 'assets/css/navigation.css',
            array('patterson-nav-tokens'),
            PATTERSON_NAV_VERSION
        );
        
        // Enqueue navigation JS
        wp_enqueue_script(
            'patterson-nav-script',
            PATTERSON_NAV_PLUGIN_URL . 'assets/js/navigation.js',
            array(),
            PATTERSON_NAV_VERSION,
            true
        );
    }
    
    /**
     * Check theme support
     */
    public function check_theme_support() {
        // Optionally add theme support features
        add_theme_support('patterson-navigation');
    }
    
    /**
     * Add custom fields to menu item objects
     * This runs on both frontend and admin
     */
    public function add_menu_item_custom_fields($menu_item) {
        $menu_item->description = get_post_meta($menu_item->ID, '_patterson_nav_description', true);
        $menu_item->enable_featured = get_post_meta($menu_item->ID, '_patterson_nav_enable_featured', true);
        $menu_item->featured_image = get_post_meta($menu_item->ID, '_patterson_nav_featured_image', true);
        $menu_item->featured_title = get_post_meta($menu_item->ID, '_patterson_nav_featured_title', true);
        $menu_item->featured_desc = get_post_meta($menu_item->ID, '_patterson_nav_featured_desc', true);
        $menu_item->featured_link_text = get_post_meta($menu_item->ID, '_patterson_nav_featured_link_text', true);
        $menu_item->featured_link_url = get_post_meta($menu_item->ID, '_patterson_nav_featured_link_url', true);
        
        return $menu_item;
    }
    
    /**
     * Remove wpautop from content containing our shortcode
     * This prevents WordPress from wrapping our navigation in <p> tags
     */
    public function remove_wpautop_for_shortcode($content) {
        if (has_shortcode($content, 'patterson_navigation')) {
            // Remove wpautop filter temporarily
            remove_filter('the_content', 'wpautop');
            
            // Re-add it after our shortcode runs
            add_filter('the_content', 'wpautop', 99);
            add_filter('the_content', array($this, 'restore_wpautop'), 100);
        }
        return $content;
    }
    
    /**
     * Restore wpautop for future content
     */
    public function restore_wpautop($content) {
        // Ensure wpautop is back on for subsequent content
        if (!has_filter('the_content', 'wpautop')) {
            add_filter('the_content', 'wpautop');
        }
        return $content;
    }
}

/**
 * Initialize the plugin
 */
function patterson_navigation_init() {
    return Patterson_Navigation::get_instance();
}

// Start the plugin
patterson_navigation_init();

/**
 * Activation hook
 */
register_activation_hook(__FILE__, function() {
    // Set default options
    $default_options = array(
        'main_nav_menu' => 0,
        'search_enabled' => true,
        'search_code' => '',
        'cta_enabled' => true,
        'cta_text' => 'Contact',
        'cta_url' => '#contact',
        'brand_color' => '#e51b24',
    );
    
    add_option('patterson_nav_settings', $default_options);
});

/**
 * Deactivation hook
 */
register_deactivation_hook(__FILE__, function() {
    // Cleanup if needed
});

/**
 * Helper function to render navigation
 */
function patterson_nav() {
    echo do_shortcode('[patterson_navigation]');
}

