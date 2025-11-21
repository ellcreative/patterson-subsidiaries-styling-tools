<?php
/**
 * Admin Settings for Patterson Navigation
 */

if (!defined('ABSPATH')) {
    exit;
}

class Patterson_Nav_Admin {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_menu_page(
            __('Patterson Navigation', 'patterson-nav'),
            __('Patterson Nav', 'patterson-nav'),
            'manage_options',
            'patterson-navigation',
            array($this, 'render_settings_page'),
            'dashicons-menu',
            30
        );
    }
    
    /**
     * Register settings
     */
    public function register_settings() {
        register_setting('patterson_nav_settings_group', 'patterson_nav_settings', array(
            'sanitize_callback' => array($this, 'sanitize_settings')
        ));
        
        // Subsidiary Configuration Section (at top - most important)
        add_settings_section(
            'patterson_nav_subsidiary',
            __('Subsidiary Configuration', 'patterson-nav'),
            array($this, 'render_subsidiary_section'),
            'patterson-navigation'
        );
        
        // Main Nav Section
        add_settings_section(
            'patterson_nav_main',
            __('Main Navigation', 'patterson-nav'),
            array($this, 'render_main_section'),
            'patterson-navigation'
        );
        
        // Design & Branding Section
        add_settings_section(
            'patterson_nav_design',
            __('Design & Branding', 'patterson-nav'),
            array($this, 'render_design_section'),
            'patterson-navigation'
        );
        
        // Add fields
        $this->add_settings_fields();
    }
    
    /**
     * Add settings fields
     */
    private function add_settings_fields() {
        $fields = array(
            // Subsidiary Configuration
            array(
                'id' => 'subsidiary_preset',
                'title' => __('Subsidiary', 'patterson-nav'),
                'callback' => 'render_subsidiary_select_field',
                'section' => 'patterson_nav_subsidiary'
            ),
            
            // Main Nav
            array(
                'id' => 'main_nav_menu',
                'title' => __('Main Nav Menu', 'patterson-nav'),
                'callback' => 'render_menu_select_field',
                'section' => 'patterson_nav_main'
            ),
            array(
                'id' => 'brand_logo_enabled',
                'title' => __('Enable Brand Logo', 'patterson-nav'),
                'callback' => 'render_checkbox_field',
                'section' => 'patterson_nav_main',
                'class' => 'custom-only-field'
            ),
            array(
                'id' => 'brand_logo_svg',
                'title' => __('Brand Logo SVG Code', 'patterson-nav'),
                'callback' => 'render_svg_textarea_field',
                'section' => 'patterson_nav_main',
                'class' => 'brand-logo-field custom-only-field'
            ),
            array(
                'id' => 'search_enabled',
                'title' => __('Enable Search', 'patterson-nav'),
                'callback' => 'render_checkbox_field',
                'section' => 'patterson_nav_main'
            ),
            array(
                'id' => 'search_code',
                'title' => __('Search Code/Shortcode', 'patterson-nav'),
                'callback' => 'render_textarea_field',
                'section' => 'patterson_nav_main'
            ),
            array(
                'id' => 'cta_enabled',
                'title' => __('Enable CTA Button', 'patterson-nav'),
                'callback' => 'render_checkbox_field',
                'section' => 'patterson_nav_main'
            ),
            array(
                'id' => 'cta_text',
                'title' => __('CTA Button Text', 'patterson-nav'),
                'callback' => 'render_text_field',
                'section' => 'patterson_nav_main'
            ),
            array(
                'id' => 'cta_url',
                'title' => __('CTA Button URL', 'patterson-nav'),
                'callback' => 'render_text_field',
                'section' => 'patterson_nav_main'
            ),
            
            // Design
            array(
                'id' => 'mobile_breakpoint',
                'title' => __('Mobile Breakpoint (px)', 'patterson-nav'),
                'callback' => 'render_number_field',
                'section' => 'patterson_nav_design'
            ),
            array(
                'id' => 'nav_mode',
                'title' => __('Navigation Mode', 'patterson-nav'),
                'callback' => 'render_nav_mode_field',
                'section' => 'patterson_nav_design'
            ),
            array(
                'id' => 'brand_color',
                'title' => __('Brand Primary Color', 'patterson-nav'),
                'callback' => 'render_color_field',
                'section' => 'patterson_nav_design',
                'class' => 'custom-only-field'
            ),
            array(
                'id' => 'typekit_code',
                'title' => __('Adobe Typekit Code', 'patterson-nav'),
                'callback' => 'render_typekit_field',
                'section' => 'patterson_nav_design',
                'class' => 'custom-only-field'
            ),
        );
        
        foreach ($fields as $field) {
            $args = array('id' => $field['id']);
            if (isset($field['class'])) {
                $args['class'] = $field['class'];
            }
            
            add_settings_field(
                'patterson_nav_' . $field['id'],
                $field['title'],
                array($this, $field['callback']),
                'patterson-navigation',
                $field['section'],
                $args
            );
        }
    }
    
    /**
     * Render section descriptions
     */
    public function render_main_section() {
        echo '<p>' . esc_html__('Configure the main navigation menu and actions. The universal Patterson navigation bar will always appear above the main navigation.', 'patterson-nav') . '</p>';
        echo '<p><strong>' . esc_html__('Brand Logo:', 'patterson-nav') . '</strong> ' . esc_html__('Optional. Some brands display their logo before the navigation menu items. On mobile, the logo appears next to the hamburger menu.', 'patterson-nav') . '</p>';
    }
    
    public function render_design_section() {
        echo '<p>' . esc_html__('Customize the appearance and branding. Design tokens are automatically loaded for consistent styling.', 'patterson-nav') . '</p>';
        echo '<p><strong>' . esc_html__('Mobile Breakpoint:', 'patterson-nav') . '</strong> ' . esc_html__('The viewport width (in pixels) at which the navigation switches to the mobile hamburger menu.', 'patterson-nav') . '</p>';
    }
    
    public function render_subsidiary_section() {
        echo '<p>' . esc_html__('Select which Patterson subsidiary this site represents. Preset configurations will automatically apply the correct branding, colors, and fonts. Choose "Custom" for complete control.', 'patterson-nav') . '</p>';
    }
    
    /**
     * Render field types
     */
    public function render_subsidiary_select_field($args) {
        $options = get_option('patterson_nav_settings');
        $value = isset($options[$args['id']]) ? $options[$args['id']] : 'custom';
        
        $subsidiaries = array(
            'ulterra' => 'Ulterra',
            'nextier' => 'NexTier',
            'superior-qc' => 'Superior QC',
            'custom' => 'Custom'
        );
        ?>
        <select name="patterson_nav_settings[<?php echo esc_attr($args['id']); ?>]" 
                id="subsidiary_preset_select">
            <?php foreach ($subsidiaries as $key => $label) : ?>
                <option value="<?php echo esc_attr($key); ?>" <?php selected($value, $key); ?>>
                    <?php echo esc_html($label); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <p class="description">
            <?php esc_html_e('Select a subsidiary for automatic branding configuration, or choose "Custom" to configure manually.', 'patterson-nav'); ?>
        </p>
        <?php
    }
    
    public function render_typekit_field($args) {
        $options = get_option('patterson_nav_settings');
        $value = isset($options[$args['id']]) ? $options[$args['id']] : '';
        ?>
        <input type="text" 
               name="patterson_nav_settings[<?php echo esc_attr($args['id']); ?>]" 
               value="<?php echo esc_attr($value); ?>" 
               class="regular-text"
               placeholder="akz7boc">
        <p class="description">
            <?php esc_html_e('Enter your Adobe Typekit project ID (e.g., akz7boc). Find this in your Typekit kit settings.', 'patterson-nav'); ?>
        </p>
        <?php
    }
    
    public function render_nav_mode_field($args) {
        $options = get_option('patterson_nav_settings');
        $value = isset($options[$args['id']]) ? $options[$args['id']] : 'light';
        ?>
        <fieldset>
            <label>
                <input type="radio" 
                       name="patterson_nav_settings[<?php echo esc_attr($args['id']); ?>]" 
                       value="light" 
                       <?php checked($value, 'light'); ?>>
                <span><?php esc_html_e('Light Mode', 'patterson-nav'); ?></span>
            </label>
            <br>
            <label style="margin-top: 8px; display: inline-block;">
                <input type="radio" 
                       name="patterson_nav_settings[<?php echo esc_attr($args['id']); ?>]" 
                       value="dark" 
                       <?php checked($value, 'dark'); ?>>
                <span><?php esc_html_e('Dark Mode', 'patterson-nav'); ?></span>
            </label>
        </fieldset>
        <p class="description">
            <?php esc_html_e('Light mode uses white text on dark overlay (default). Dark mode uses dark text on light overlay.', 'patterson-nav'); ?>
        </p>
        <?php
    }
    
    public function render_svg_textarea_field($args) {
        $options = get_option('patterson_nav_settings');
        $value = isset($options[$args['id']]) ? $options[$args['id']] : '';
        $row_class = isset($args['class']) ? ' class="' . esc_attr($args['class']) . '"' : '';
        ?>
        <div<?php echo $row_class; ?>>
            <div class="patterson-nav-svg-instructions" style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 12px; margin-bottom: 12px;">
                <p style="margin: 0 0 8px 0; font-weight: 600; color: #856404;">
                    ⚠️ <?php esc_html_e('Important: Preparing your SVG for dark mode', 'patterson-nav'); ?>
                </p>
                <p style="margin: 0; color: #856404;">
                    <?php esc_html_e('Replace the MAIN color (typically white) with "currentColor" in your SVG. This allows the logo to adapt to light and dark modes.', 'patterson-nav'); ?>
                </p>
                <p style="margin: 8px 0 0 0; color: #856404;">
                    <strong><?php esc_html_e('Replace main color only:', 'patterson-nav'); ?></strong><br>
                    <code style="background: #fff; padding: 2px 6px; border-radius: 3px;">fill="#fff"</code> → 
                    <code style="background: #fff; padding: 2px 6px; border-radius: 3px;">fill="currentColor"</code><br>
                    <code style="background: #fff; padding: 2px 6px; border-radius: 3px;">stroke="white"</code> → 
                    <code style="background: #fff; padding: 2px 6px; border-radius: 3px;">stroke="currentColor"</code>
                </p>
                <p style="margin: 8px 0 0 0; color: #856404;">
                    <strong><?php esc_html_e('Keep accent/support colors as-is:', 'patterson-nav'); ?></strong><br>
                    <code style="background: #fff; padding: 2px 6px; border-radius: 3px;">fill="#06929F"</code> ← <?php esc_html_e('Leave these unchanged', 'patterson-nav'); ?>
                </p>
            </div>
            
            <textarea 
                name="patterson_nav_settings[<?php echo esc_attr($args['id']); ?>]" 
                rows="10" 
                class="large-text code"
                style="font-family: monospace; font-size: 12px;"
                placeholder='<svg viewBox="0 0 200 24" fill="none" xmlns="http://www.w3.org/2000/svg">
  <path fill="currentColor" d="M10 5 L20 15..." />
  <path fill="#06929F" d="M30 10..." />
</svg>'><?php echo esc_textarea($value); ?></textarea>
            
            <p class="description">
                <?php esc_html_e('Paste your complete SVG code. Keep accent colors as defined values - only the main color should use currentColor.', 'patterson-nav'); ?>
            </p>
            
            <?php if (!empty($value)) : ?>
                <div style="margin-top: 12px; padding: 12px; background: #f0f0f1; border-radius: 4px;">
                    <p style="margin: 0 0 8px 0; font-weight: 600;">
                        <?php esc_html_e('Preview:', 'patterson-nav'); ?>
                    </p>
                    <div style="background: white; padding: 20px; border-radius: 4px; display: inline-block;">
                        <div style="width: 200px;">
                            <?php echo wp_kses_post($value); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
    
    public function render_checkbox_field($args) {
        $options = get_option('patterson_nav_settings');
        $value = isset($options[$args['id']]) ? $options[$args['id']] : false;
        
        $row_class = isset($args['class']) ? ' class="' . esc_attr($args['class']) . '"' : '';
        
        // Add data attribute for JS toggle
        $data_attr = '';
        if ($args['id'] === 'brand_logo_enabled') {
            $data_attr = 'id="brand_logo_enabled_checkbox"';
        }
        ?>
        <label<?php echo $row_class; ?>>
            <input type="checkbox" 
                   name="patterson_nav_settings[<?php echo esc_attr($args['id']); ?>]" 
                   value="1" 
                   <?php echo $data_attr; ?>
                   <?php checked($value, 1); ?>>
        </label>
        <?php
    }
    
    public function render_text_field($args) {
        $options = get_option('patterson_nav_settings');
        $value = isset($options[$args['id']]) ? $options[$args['id']] : '';
        ?>
        <input type="text" 
               name="patterson_nav_settings[<?php echo esc_attr($args['id']); ?>]" 
               value="<?php echo esc_attr($value); ?>" 
               class="regular-text">
        <?php
    }
    
    public function render_textarea_field($args) {
        $options = get_option('patterson_nav_settings');
        $value = isset($options[$args['id']]) ? $options[$args['id']] : '';
        ?>
        <textarea name="patterson_nav_settings[<?php echo esc_attr($args['id']); ?>]" 
                  rows="4" 
                  class="large-text"><?php echo esc_textarea($value); ?></textarea>
        <?php
        
        if ($args['id'] === 'search_code') {
            echo '<p class="description">' . esc_html__('Enter a shortcode like [search_form] or HTML/JS embed code', 'patterson-nav') . '</p>';
        }
    }
    
    public function render_color_field($args) {
        $options = get_option('patterson_nav_settings');
        $value = isset($options[$args['id']]) ? $options[$args['id']] : '#e51b24';
        $row_class = isset($args['class']) ? ' class="' . esc_attr($args['class']) . '"' : '';
        ?>
        <span<?php echo $row_class; ?>>
        <input type="text" 
               name="patterson_nav_settings[<?php echo esc_attr($args['id']); ?>]" 
               value="<?php echo esc_attr($value); ?>" 
               class="patterson-nav-color-picker">
        </span>
        <?php
    }
    
    public function render_menu_select_field($args) {
        $options = get_option('patterson_nav_settings');
        $value = isset($options[$args['id']]) ? $options[$args['id']] : 0;
        $menus = wp_get_nav_menus();
        ?>
        <select name="patterson_nav_settings[<?php echo esc_attr($args['id']); ?>]">
            <option value="0"><?php esc_html_e('— Select Menu —', 'patterson-nav'); ?></option>
            <?php foreach ($menus as $menu) : ?>
                <option value="<?php echo esc_attr($menu->term_id); ?>" 
                        <?php selected($value, $menu->term_id); ?>>
                    <?php echo esc_html($menu->name); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php
    }
    
    public function render_media_field($args) {
        $options = get_option('patterson_nav_settings');
        $value = isset($options[$args['id']]) ? $options[$args['id']] : '';
        $row_class = isset($args['class']) ? ' ' . esc_attr($args['class']) : '';
        ?>
        <div class="patterson-nav-media-upload<?php echo $row_class; ?>">
            <input type="text" 
                   id="patterson_nav_<?php echo esc_attr($args['id']); ?>" 
                   name="patterson_nav_settings[<?php echo esc_attr($args['id']); ?>]" 
                   value="<?php echo esc_url($value); ?>" 
                   class="regular-text">
            <button type="button" 
                    class="button patterson-nav-upload-button" 
                    data-target="patterson_nav_<?php echo esc_attr($args['id']); ?>">
                <?php esc_html_e('Upload Logo', 'patterson-nav'); ?>
            </button>
            <?php if ($value) : ?>
                <div class="patterson-nav-logo-preview" style="margin-top: 10px;">
                    <img src="<?php echo esc_url($value); ?>" alt="Logo preview" style="max-height: 60px; max-width: 300px;">
                </div>
            <?php endif; ?>
        </div>
        <?php
        if ($args['id'] === 'brand_logo_url') {
            echo '<p class="description">' . esc_html__('Upload your brand logo. Recommended size: 198x24px (SVG or PNG). The logo will appear before the navigation menu items with a divider.', 'patterson-nav') . '</p>';
        }
    }
    
    public function render_number_field($args) {
        $options = get_option('patterson_nav_settings');
        $value = isset($options[$args['id']]) && $options[$args['id']] ? $options[$args['id']] : '';
        $row_class = isset($args['class']) ? ' class="' . esc_attr($args['class']) . '"' : '';
        
        // Default values
        $defaults = array(
            'brand_logo_width' => '198',
            'brand_logo_height' => '24',
            'mobile_breakpoint' => '1420'
        );
        $placeholder = isset($defaults[$args['id']]) ? $defaults[$args['id']] : '';
        
        // Set min/max based on field
        $min = 1;
        $max = 500;
        if ($args['id'] === 'mobile_breakpoint') {
            $min = 320;
            $max = 2000;
        }
        ?>
        <span<?php echo $row_class; ?>>
        <input type="number" 
               name="patterson_nav_settings[<?php echo esc_attr($args['id']); ?>]" 
               value="<?php echo esc_attr($value); ?>" 
               placeholder="<?php echo esc_attr($placeholder); ?>"
               min="<?php echo esc_attr($min); ?>"
               max="<?php echo esc_attr($max); ?>"
               class="small-text brand-logo-number-field">
        <span class="description">px</span>
        <?php
        if ($args['id'] === 'brand_logo_width') {
            echo '<p class="description">' . esc_html__('Recommended: 198px', 'patterson-nav') . '</p>';
        }
        if ($args['id'] === 'brand_logo_height') {
            echo '<p class="description">' . esc_html__('Recommended: 24px', 'patterson-nav') . '</p>';
        }
        if ($args['id'] === 'mobile_breakpoint') {
            echo '<p class="description">' . esc_html__('Default: 1420px. The navigation switches to hamburger menu at this width and below.', 'patterson-nav') . '</p>';
        }
        ?>
        </span>
        <?php
    }
    
    /**
     * Sanitize SVG code (security only, preserve colors)
     */
    private function sanitize_svg($svg) {
        if (empty($svg)) {
            return '';
        }
        
        // Remove any script tags
        $svg = preg_replace('/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi', '', $svg);
        
        // Remove any event handlers (onclick, onload, etc.)
        $svg = preg_replace('/\s*on\w+\s*=\s*["\'][^"\']*["\']/gi', '', $svg);
        
        // Remove any javascript: protocols
        $svg = preg_replace('/javascript:/gi', '', $svg);
        
        // Remove any <style> tags with javascript
        $svg = preg_replace('/<style[^>]*>.*?<\/style>/gis', '', $svg);
        
        // Remove any <link> tags
        $svg = preg_replace('/<link[^>]*>/gi', '', $svg);
        
        // DO NOT auto-replace colors - let users control which colors use currentColor
        // This preserves brand accent colors
        
        // Ensure viewBox exists (required for proper scaling)
        if (!preg_match('/viewBox\s*=/', $svg)) {
            // Try to extract width/height to create viewBox
            preg_match('/width\s*=\s*["\']([^"\']+)["\']/', $svg, $width_match);
            preg_match('/height\s*=\s*["\']([^"\']+)["\']/', $svg, $height_match);
            
            if (!empty($width_match[1]) && !empty($height_match[1])) {
                $width = preg_replace('/[^0-9.]/', '', $width_match[1]);
                $height = preg_replace('/[^0-9.]/', '', $height_match[1]);
                $svg = str_replace('<svg ', '<svg viewBox="0 0 ' . $width . ' ' . $height . '" ', $svg);
            }
        }
        
        return $svg;
    }
    
    /**
     * Sanitize settings
     */
    public function sanitize_settings($input) {
        $sanitized = array();
        
        // Subsidiary preset
        $sanitized['subsidiary_preset'] = isset($input['subsidiary_preset']) ? sanitize_text_field($input['subsidiary_preset']) : 'custom';
        
        // Auto-populate values based on subsidiary preset
        $preset = $sanitized['subsidiary_preset'];
        if ($preset !== 'custom') {
            $presets = $this->get_subsidiary_presets();
            if (isset($presets[$preset])) {
                // Override with preset values
                $sanitized['brand_logo_enabled'] = 1;
                $sanitized['brand_logo_url'] = $presets[$preset]['logo_url']; // Keep for file path
                $sanitized['brand_logo_svg'] = ''; // Presets use files, not custom SVG
                $sanitized['brand_color'] = $presets[$preset]['color'];
                $sanitized['typekit_code'] = $presets[$preset]['typekit'];
            }
        } else {
            // Custom mode - use submitted values
            $sanitized['brand_logo_enabled'] = isset($input['brand_logo_enabled']) ? 1 : 0;
            $sanitized['brand_logo_svg'] = isset($input['brand_logo_svg']) ? $this->sanitize_svg($input['brand_logo_svg']) : '';
            $sanitized['brand_logo_url'] = ''; // Not used in custom mode
            $sanitized['brand_color'] = isset($input['brand_color']) ? sanitize_text_field($input['brand_color']) : '';
            $sanitized['typekit_code'] = isset($input['typekit_code']) ? sanitize_text_field($input['typekit_code']) : '';
        }
        
        // Other checkbox fields
        $checkbox_fields = array('search_enabled', 'cta_enabled');
        foreach ($checkbox_fields as $field) {
            $sanitized[$field] = isset($input[$field]) ? 1 : 0;
        }
        
        // Text fields
        $text_fields = array('cta_text', 'cta_url');
        foreach ($text_fields as $field) {
            $sanitized[$field] = isset($input[$field]) ? sanitize_text_field($input[$field]) : '';
        }
        
        // Navigation mode
        $sanitized['nav_mode'] = isset($input['nav_mode']) && in_array($input['nav_mode'], array('light', 'dark')) ? $input['nav_mode'] : 'light';
        
        // Mobile breakpoint
        $sanitized['mobile_breakpoint'] = isset($input['mobile_breakpoint']) && $input['mobile_breakpoint'] ? absint($input['mobile_breakpoint']) : 1420;
        
        // Textarea fields
        $sanitized['search_code'] = isset($input['search_code']) ? wp_kses_post($input['search_code']) : '';
        
        // Menu select fields
        $sanitized['main_nav_menu'] = isset($input['main_nav_menu']) ? absint($input['main_nav_menu']) : 0;
        
        return $sanitized;
    }
    
    /**
     * Get subsidiary preset configurations
     */
    private function get_subsidiary_presets() {
        return array(
            'ulterra' => array(
                'logo_url' => PATTERSON_NAV_PLUGIN_URL . 'assets/logos/ulterra.svg',
                'logo_width' => 198,
                'logo_height' => 24,
                'color' => '#06929F',
                'typekit' => 'eyo6evt'
            ),
            'nextier' => array(
                'logo_url' => PATTERSON_NAV_PLUGIN_URL . 'assets/logos/nextier.svg',
                'logo_width' => 198,
                'logo_height' => 24,
                'color' => '#037D3F',
                'typekit' => 'bqc1fxq'
            ),
            'superior-qc' => array(
                'logo_url' => PATTERSON_NAV_PLUGIN_URL . 'assets/logos/superiorQC.svg',
                'logo_width' => 198,
                'logo_height' => 24,
                'color' => '#DF181D',
                'typekit' => 'afd5ryn'
            )
        );
    }
    
    /**
     * Render settings page
     */
    public function render_settings_page() {
        if (!current_user_can('manage_options')) {
            return;
        }
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            
            <div class="patterson-nav-admin-header">
                <p><?php esc_html_e('Configure the Patterson unified navigation system. Use the shortcode [patterson_navigation] or the function patterson_nav() to display the navigation.', 'patterson-nav'); ?></p>
            </div>
            
            <form method="post" action="options.php">
                <?php
                settings_fields('patterson_nav_settings_group');
                do_settings_sections('patterson-navigation');
                submit_button();
                ?>
            </form>
            
            <div class="patterson-nav-admin-footer">
                <h2><?php esc_html_e('Usage', 'patterson-nav'); ?></h2>
                <p><?php esc_html_e('Add the navigation to your theme:', 'patterson-nav'); ?></p>
                <pre><code>&lt;?php patterson_nav(); ?&gt;</code></pre>
                <p><?php esc_html_e('Or use the shortcode:', 'patterson-nav'); ?></p>
                <pre><code>[patterson_navigation]</code></pre>
            </div>
        </div>
        <?php
    }
    
    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets($hook) {
        if ('toplevel_page_patterson-navigation' !== $hook) {
            return;
        }
        
        // Enqueue WordPress color picker
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
        
        // Enqueue WordPress media uploader
        wp_enqueue_media();
        
        // Enqueue custom admin JS
        wp_add_inline_script('wp-color-picker', '
            jQuery(document).ready(function($) {
                // Color picker
                $(".patterson-nav-color-picker").wpColorPicker();
                
                // Toggle fields based on subsidiary preset
                function toggleSubsidiaryFields() {
                    var preset = $("#subsidiary_preset_select").val();
                    var isCustom = preset === "custom";
                    
                    // Show/hide custom-only fields
                    $(".custom-only-field").closest("tr").toggle(isCustom);
                    
                    // Disable hidden fields to prevent HTML5 validation issues
                    if (isCustom) {
                        $(".custom-only-field input, .custom-only-field textarea, .custom-only-field select").prop("disabled", false);
                    } else {
                        $(".custom-only-field input, .custom-only-field textarea, .custom-only-field select").prop("disabled", true);
                    }
                    
                    // If custom mode, also toggle brand logo fields based on checkbox
                    if (isCustom) {
                        toggleBrandLogoFields();
                    }
                }
                
                // Toggle brand logo fields (only in custom mode)
                function toggleBrandLogoFields() {
                    var preset = $("#subsidiary_preset_select").val();
                    if (preset !== "custom") return; // Only applies in custom mode
                    
                    var isChecked = $("#brand_logo_enabled_checkbox").is(":checked");
                    $(".brand-logo-field").closest("tr").toggle(isChecked);
                    
                    // Disable hidden fields to prevent HTML5 validation issues
                    if (isChecked) {
                        $(".brand-logo-field input, .brand-logo-field textarea, .brand-logo-field select").prop("disabled", false);
                    } else {
                        $(".brand-logo-field input, .brand-logo-field textarea, .brand-logo-field select").prop("disabled", true);
                    }
                }
                
                // Initial state
                toggleSubsidiaryFields();
                
                // On subsidiary change
                $("#subsidiary_preset_select").on("change", function() {
                    toggleSubsidiaryFields();
                });
                
                // On brand logo checkbox change
                $("#brand_logo_enabled_checkbox").on("change", function() {
                    toggleBrandLogoFields();
                });
                
                // Media uploader
                var mediaUploader;
                $(".patterson-nav-upload-button").on("click", function(e) {
                    e.preventDefault();
                    var button = $(this);
                    var targetId = button.data("target");
                    var targetInput = $("#" + targetId);
                    
                    // If the media frame already exists, reopen it
                    if (mediaUploader) {
                        mediaUploader.open();
                        return;
                    }
                    
                    // Create a new media frame
                    mediaUploader = wp.media({
                        title: "Select or Upload Logo",
                        button: {
                            text: "Use this logo"
                        },
                        multiple: false
                    });
                    
                    // When an image is selected, run a callback
                    mediaUploader.on("select", function() {
                        var attachment = mediaUploader.state().get("selection").first().toJSON();
                        targetInput.val(attachment.url);
                        
                        // Update preview if it exists
                        var preview = button.parent().find(".patterson-nav-logo-preview");
                        if (preview.length) {
                            preview.find("img").attr("src", attachment.url);
                        } else {
                            button.parent().append(
                                \'<div class="patterson-nav-logo-preview" style="margin-top: 10px;">\' +
                                \'<img src="\' + attachment.url + \'" alt="Logo preview" style="max-height: 60px; max-width: 300px;">\' +
                                \'</div>\'
                            );
                        }
                    });
                    
                    // Open the media frame
                    mediaUploader.open();
                });
            });
        ');
        
        // Add admin CSS
        wp_add_inline_style('wp-color-picker', '
            .patterson-nav-admin-header {
                background: #fff;
                border: 1px solid #ccd0d4;
                border-radius: 4px;
                padding: 20px;
                margin: 20px 0;
            }
            .patterson-nav-admin-footer {
                background: #fff;
                border: 1px solid #ccd0d4;
                border-radius: 4px;
                padding: 20px;
                margin: 20px 0;
            }
            .patterson-nav-admin-footer pre {
                background: #f6f7f7;
                padding: 15px;
                border-radius: 4px;
                overflow-x: auto;
            }
        ');
    }
}

