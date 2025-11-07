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
                'section' => 'patterson_nav_main'
            ),
            array(
                'id' => 'brand_logo_url',
                'title' => __('Brand Logo URL', 'patterson-nav'),
                'callback' => 'render_media_field',
                'section' => 'patterson_nav_main',
                'class' => 'brand-logo-field'
            ),
            array(
                'id' => 'brand_logo_width',
                'title' => __('Logo Width (px)', 'patterson-nav'),
                'callback' => 'render_number_field',
                'section' => 'patterson_nav_main',
                'class' => 'brand-logo-field'
            ),
            array(
                'id' => 'brand_logo_height',
                'title' => __('Logo Height (px)', 'patterson-nav'),
                'callback' => 'render_number_field',
                'section' => 'patterson_nav_main',
                'class' => 'brand-logo-field'
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
                'id' => 'brand_color',
                'title' => __('Brand Primary Color', 'patterson-nav'),
                'callback' => 'render_color_field',
                'section' => 'patterson_nav_design'
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
    
    /**
     * Render field types
     */
    public function render_checkbox_field($args) {
        $options = get_option('patterson_nav_settings');
        $value = isset($options[$args['id']]) ? $options[$args['id']] : false;
        
        // Add data attribute for JS toggle
        $data_attr = '';
        if ($args['id'] === 'brand_logo_enabled') {
            $data_attr = 'id="brand_logo_enabled_checkbox"';
        }
        ?>
        <label>
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
        ?>
        <input type="text" 
               name="patterson_nav_settings[<?php echo esc_attr($args['id']); ?>]" 
               value="<?php echo esc_attr($value); ?>" 
               class="patterson-nav-color-picker">
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
     * Sanitize settings
     */
    public function sanitize_settings($input) {
        $sanitized = array();
        
        // Checkbox fields
        $checkbox_fields = array('search_enabled', 'cta_enabled', 'brand_logo_enabled');
        foreach ($checkbox_fields as $field) {
            $sanitized[$field] = isset($input[$field]) ? 1 : 0;
        }
        
        // Text fields
        $text_fields = array('cta_text', 'cta_url', 'brand_color');
        foreach ($text_fields as $field) {
            $sanitized[$field] = isset($input[$field]) ? sanitize_text_field($input[$field]) : '';
        }
        
        // URL fields
        $sanitized['brand_logo_url'] = isset($input['brand_logo_url']) ? esc_url_raw($input['brand_logo_url']) : '';
        
        // Number fields - only save if brand logo is enabled and values provided
        if (!empty($sanitized['brand_logo_enabled'])) {
            $sanitized['brand_logo_width'] = isset($input['brand_logo_width']) && $input['brand_logo_width'] ? absint($input['brand_logo_width']) : 198;
            $sanitized['brand_logo_height'] = isset($input['brand_logo_height']) && $input['brand_logo_height'] ? absint($input['brand_logo_height']) : 24;
        } else {
            // Don't set values when logo is disabled (leave them undefined)
            $sanitized['brand_logo_width'] = '';
            $sanitized['brand_logo_height'] = '';
        }
        $sanitized['mobile_breakpoint'] = isset($input['mobile_breakpoint']) && $input['mobile_breakpoint'] ? absint($input['mobile_breakpoint']) : 1420;
        
        // Textarea fields
        $sanitized['search_code'] = isset($input['search_code']) ? wp_kses_post($input['search_code']) : '';
        
        // Menu select fields
        $sanitized['main_nav_menu'] = isset($input['main_nav_menu']) ? absint($input['main_nav_menu']) : 0;
        
        return $sanitized;
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
                
                // Toggle brand logo fields
                function toggleBrandLogoFields() {
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
                toggleBrandLogoFields();
                
                // On change
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

