<?php
/**
 * Plugin Name: Patterson Subsidiary Navigation
 * Plugin URI: https://github.com/patterson/navigation
 * Description: Universal Patterson nav + brand-specific main navigation system with mega-menu dropdowns, mobile menu, and accessibility features.
 * Version: 1.0.0
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Author: Patterson Companies
 * Author URI: https://pattersoncompanies.com
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
        // Enqueue assets
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
        
        // Initialize admin
        if (is_admin()) {
            new Patterson_Nav_Admin();
            new Patterson_Nav_Meta_Boxes();
        }
        
        // Add shortcode for rendering navigation
        add_shortcode('patterson_navigation', array('Patterson_Nav_Renderer', 'render_navigation'));
        
        // Add theme support check
        add_action('after_setup_theme', array($this, 'check_theme_support'));
    }
    
    /**
     * Enqueue frontend assets
     */
    public function enqueue_frontend_assets() {
        // Get plugin settings
        $options = get_option('patterson_nav_settings', array());
        
        // Enqueue design tokens if enabled
        if (isset($options['enable_design_tokens']) && $options['enable_design_tokens']) {
            $tokens_url = isset($options['design_tokens_url']) ? $options['design_tokens_url'] : '';
            if ($tokens_url) {
                wp_enqueue_style(
                    'patterson-nav-tokens',
                    esc_url($tokens_url),
                    array(),
                    PATTERSON_NAV_VERSION
                );
            }
        }
        
        // Enqueue navigation CSS
        wp_enqueue_style(
            'patterson-nav-styles',
            PATTERSON_NAV_PLUGIN_URL . 'assets/css/navigation.css',
            array(),
            PATTERSON_NAV_VERSION
        );
        
        // No longer using Font Awesome - using inline SVG icons for better accessibility and performance
        
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
        'universal_nav_enabled' => true,
        'main_nav_menu' => 0,
        'universal_nav_menu' => 0,
        'search_enabled' => true,
        'search_code' => '',
        'cta_enabled' => true,
        'cta_text' => 'Contact',
        'cta_url' => '#contact',
        'brand_color' => '#e51b24',
        'enable_design_tokens' => false,
        'design_tokens_url' => '',
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

