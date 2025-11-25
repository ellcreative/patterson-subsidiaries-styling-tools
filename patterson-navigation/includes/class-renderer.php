<?php
/**
 * Frontend Renderer for Patterson Navigation
 */

if (!defined('ABSPATH')) {
    exit;
}

class Patterson_Nav_Renderer {
    
    /**
     * Get navigation configuration from JSON file
     */
    private static function get_config() {
        static $config = null;
        
        if ($config === null) {
            $config_file = PATTERSON_NAV_PLUGIN_DIR . 'config/navigation-config.json';
            
            if (file_exists($config_file)) {
                $config = json_decode(file_get_contents($config_file), true);
            }
            
            // Fallback to hardcoded config if file doesn't exist
            if (!$config) {
                $config = array(
                    'universal_nav' => array(
                        'logo' => array(
                            'url' => 'https://patenergy.com/',
                            'aria_label' => 'Patterson Companies',
                            'alt' => 'Patterson',
                            'width' => 130,
                            'height' => 22
                        ),
                        'links' => array(
                            array('title' => 'About', 'url' => 'https://patenergy.com/about-us/'),
                            array('title' => 'Investors', 'url' => 'https://investor.patenergy.com/overview/'),
                            array('title' => 'Sustainability', 'url' => 'https://esg.patenergy.com/'),
                            array('title' => 'Careers', 'url' => 'https://patenergy.com/careers/')
                        )
                    )
                );
            }
        }
        
        return $config;
    }
    
    /**
     * Get SVG icon by name
     * All icons use currentColor for fill/stroke so they inherit text color
     */
    private static function get_icon($name, $class = 'nav-icon') {
        $icons = array(
            'angle-right' => '<svg class="' . esc_attr($class) . '" aria-hidden="true" width="9" height="14" viewBox="0 0 9 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8.33398 6.80664L7.57031 7.57031L2.25781 12.8828L1.52734 13.6465L0 12.1191L0.763672 11.3887L5.3125 6.80664L0.763672 2.25781L0 1.49414L1.52734 0L2.25781 0.763672L7.57031 6.07617L8.33398 6.80664Z" fill="currentColor"/></svg>',
            
            'square-arrow-up-right' => '<svg class="' . esc_attr($class) . '" aria-hidden="true" width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.9375 1.3125H1.3125V10.9375H10.9375V1.3125ZM12.25 0V1.3125V10.9375V12.25H10.9375H1.3125H0V10.9375V1.3125V0H1.3125H10.9375H12.25ZM8.53125 3.0625H9.1875V3.71875V8.09375V8.75H7.875V8.09375V5.30469L4.18359 8.99609L3.71875 9.46094L2.78906 8.53125L3.25391 8.06641L6.94531 4.375H4.375H3.71875V3.0625H4.375H8.53125Z" fill="currentColor"/></svg>',
            
            'search' => '<svg class="' . esc_attr($class) . '" aria-hidden="true" width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M13.6562 7.71875C13.6562 5.60352 12.5059 3.67383 10.6875 2.59766C8.83203 1.52148 6.56836 1.52148 4.75 2.59766C2.89453 3.67383 1.78125 5.60352 1.78125 7.71875C1.78125 9.87109 2.89453 11.8008 4.75 12.877C6.56836 13.9531 8.83203 13.9531 10.6875 12.877C12.5059 11.8008 13.6562 9.87109 13.6562 7.71875ZM12.5059 13.8047C11.1699 14.8438 9.5 15.4375 7.71875 15.4375C3.45117 15.4375 0 11.9863 0 7.71875C0 3.48828 3.45117 0 7.71875 0C11.9492 0 15.4375 3.48828 15.4375 7.71875C15.4375 9.53711 14.8066 11.207 13.7676 12.543L18.3691 17.1445L19 17.7754L17.7383 19L17.1074 18.3691L12.5059 13.7676V13.8047Z" fill="currentColor"/></svg>',
            
            'angle-down' => '<svg class="' . esc_attr($class) . '" aria-hidden="true" width="16" height="9" viewBox="0 0 16 9" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.57812 8.90625L6.91406 8.24219L0.664062 1.99219L0 1.32812L1.32812 0L1.99219 0.664062L7.57812 6.28906L13.1641 0.703125L13.8281 0.0390625L15.1562 1.32812L14.4922 1.99219L8.24219 8.24219L7.57812 8.90625Z" fill="currentColor"/></svg>',
            
            'close' => '<svg class="' . esc_attr($class) . '" aria-hidden="true" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2 2L16 16M16 2L2 16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
        );
        
        return isset($icons[$name]) ? $icons[$name] : '';
    }
    
    /**
     * Enqueue frontend assets
     */
    private static function enqueue_assets() {
        // Enqueue Adobe Typekit fonts (required for Patterson brand fonts)
        if (!wp_style_is('patterson-typekit', 'enqueued')) {
            $options = get_option('patterson_nav_settings');
            $typekit_code = isset($options['typekit_code']) && !empty($options['typekit_code']) 
                ? $options['typekit_code'] 
                : 'akz7boc'; // Fallback to Patterson default
            
            wp_enqueue_style(
                'patterson-typekit',
                'https://use.typekit.net/' . esc_attr($typekit_code) . '.css',
                array(),
                null
            );
        }
        
        // Only enqueue if not already enqueued
        if (!wp_style_is('patterson-nav-tokens', 'enqueued')) {
            wp_enqueue_style(
                'patterson-nav-tokens',
                PATTERSON_NAV_PLUGIN_URL . 'assets/css/tokens.css',
                array('patterson-typekit'),
                PATTERSON_NAV_VERSION
            );
        }
        
        if (!wp_style_is('patterson-nav-styles', 'enqueued')) {
            wp_enqueue_style(
                'patterson-nav-styles',
                PATTERSON_NAV_PLUGIN_URL . 'assets/css/navigation.css',
                array('patterson-nav-tokens'),
                PATTERSON_NAV_VERSION
            );
        }
        
        if (!wp_script_is('patterson-nav-script', 'enqueued')) {
            wp_enqueue_script(
                'patterson-nav-script',
                PATTERSON_NAV_PLUGIN_URL . 'assets/js/navigation.js',
                array(),
                PATTERSON_NAV_VERSION,
                true
            );
        }
    }
    
    /**
     * Render brand logo (inline SVG)
     * 
     * @param array  $options        Plugin settings from database
     * @param string $override_url   Optional URL/path override from shortcode/function
     */
    private static function render_brand_logo($options, $override_url = '') {
        $svg_content = '';
        
        // Priority 1: Override from shortcode/function parameter
        if (!empty($override_url)) {
            $logo_path = $override_url;
            
            // Convert URL to file path if it starts with the site URL or plugin URL
            $site_url = site_url();
            $plugin_url = PATTERSON_NAV_PLUGIN_URL;
            
            if (strpos($logo_path, $site_url) === 0) {
                $logo_path = str_replace($site_url, ABSPATH, $logo_path);
            } elseif (strpos($logo_path, $plugin_url) === 0) {
                $logo_path = str_replace($plugin_url, PATTERSON_NAV_PLUGIN_DIR, $logo_path);
            }
            
            // Handle relative paths - check in theme directory first, then absolute
            if (!file_exists($logo_path) && strpos($logo_path, '/') === 0) {
                // Try as absolute path from WordPress root
                $logo_path = ABSPATH . ltrim($logo_path, '/');
            }
            
            if (file_exists($logo_path)) {
                $svg_content = file_get_contents($logo_path);
            }
        }
        // Priority 2: Custom SVG code from admin settings
        elseif (!empty($options['brand_logo_svg'])) {
            // Custom mode - use pasted SVG code
            $svg_content = $options['brand_logo_svg'];
        }
        // Priority 3: Preset mode - inline from file (admin settings)
        elseif (!empty($options['brand_logo_url'])) {
            $logo_path = str_replace(PATTERSON_NAV_PLUGIN_URL, PATTERSON_NAV_PLUGIN_DIR, $options['brand_logo_url']);
            
            if (file_exists($logo_path)) {
                $svg_content = file_get_contents($logo_path);
            }
        }
        
        if (empty($svg_content)) {
            return;
        }
        
        // Clean up SVG attributes that interfere with CSS sizing
        // Remove width, height, and style attributes from the <svg> tag
        // Keep viewBox which is essential for proper scaling
        $svg_content = preg_replace('/(<svg[^>]*)\s+width\s*=\s*["\'][^"\']*["\']/i', '$1', $svg_content);
        $svg_content = preg_replace('/(<svg[^>]*)\s+height\s*=\s*["\'][^"\']*["\']/i', '$1', $svg_content);
        $svg_content = preg_replace('/(<svg[^>]*)\s+style\s*=\s*["\'][^"\']*["\']/i', '$1', $svg_content);
        $svg_content = preg_replace('/(<svg[^>]*)\s+preserveAspectRatio\s*=\s*["\'][^"\']*["\']/i', '$1', $svg_content);
        
        ?>
        <div class="main-nav__brand-logo">
            <a href="<?php echo esc_url(home_url('/')); ?>" 
               aria-label="<?php echo esc_attr(get_bloginfo('name') . ' ' . __('Home', 'patterson-nav')); ?>">
                <?php echo $svg_content; // Already sanitized ?>
            </a>
        </div>
        <?php
    }
    
    /**
     * Get SVG icon (backward compatibility wrapper)
     */
    private static function get_icon_svg($type = 'angle-down') {
        // Map old icon names to new ones and set appropriate class
        $class = ($type === 'external') ? 'nav-icon nav-icon--external' : 'nav-icon';
        $icon_name = ($type === 'external') ? 'square-arrow-up-right' : $type;
        
        return self::get_icon($icon_name, $class);
    }
    
    /**
     * Check if a URL is external
     */
    private static function is_external_link($url) {
        // Get the home URL hostname
        $home_host = parse_url(home_url(), PHP_URL_HOST);
        
        // Parse the link URL
        $link_host = parse_url($url, PHP_URL_HOST);
        
        // Relative URLs (no host) are internal
        if (!$link_host) {
            return false;
        }
        
        // Compare hostnames (external if different)
        return $home_host !== $link_host;
    }
    
    /**
     * Render the navigation
     * 
     * @param array $atts Optional attributes.
     *                    'overlay_bg' - Custom background color for the main nav overlay.
     *                    'mode' - Navigation mode: 'light' or 'dark'.
     *                    'brand_logo_url' - URL or path to a custom brand logo SVG file.
     */
    public static function render_navigation($atts = array()) {
        $options = get_option('patterson_nav_settings');
        
        // Parse shortcode attributes
        $atts = shortcode_atts(array(
            'overlay_bg' => '',
            'mode' => '',
            'brand_logo_url' => ''
        ), $atts, 'patterson_navigation');
        
        // Determine mode (shortcode > admin setting > default)
        $nav_mode = !empty($atts['mode']) ? $atts['mode'] : 
                    (isset($options['nav_mode']) ? $options['nav_mode'] : 'light');
        
        // Ensure assets are enqueued (for block themes and shortcode usage)
        self::enqueue_assets();
        
        // Start output buffering
        ob_start();
        
        // Add inline styles for site customizations
        echo '<style>';
        
        // CSS custom properties
        $css_props = array();
        if (isset($options['brand_color']) && $options['brand_color']) {
            $css_props[] = '--primary-color: ' . esc_attr($options['brand_color']);
        }
        
        // Custom overlay background color from shortcode/function parameter
        if (!empty($atts['overlay_bg'])) {
            $css_props[] = '--color-background-overlay: ' . esc_attr($atts['overlay_bg']);
        } elseif ($nav_mode === 'dark') {
            // Use light overlay for dark mode (if no custom overlay specified)
            $css_props[] = '--color-background-overlay: oklch(0.95 0 0 / 0.85)';
        }
        
        if (!empty($css_props)) {
            echo ':root { ' . implode('; ', $css_props) . '; }';
        }
        
        // Mobile breakpoint override
        $breakpoint = isset($options['mobile_breakpoint']) && $options['mobile_breakpoint'] ? absint($options['mobile_breakpoint']) : 1420;
        if ($breakpoint !== 1420) {
            // Override default breakpoint with custom value
            echo '@media (max-width: ' . $breakpoint . 'px) {';
            echo '.universal-nav__menu, .main-nav__menu, .main-nav__actions { display: none; }';
            echo '.main-nav__brand-logo { margin-inline-end: auto; }';
            echo '.main-nav__brand-logo img { max-block-size: 20px; }';
            echo '.main-nav__mobile-toggle { display: flex; align-items: center; justify-content: center; background: transparent; border: none; cursor: pointer; padding: var(--space-2); inline-size: 44px; block-size: 44px; }';
            echo '}';
        }
        
        echo '</style>';
        
        ?><a href="#main-content" class="skip-link"><?php esc_html_e('Skip to main content', 'patterson-nav'); ?></a><div class="site-navigation" id="site-navigation"><nav class="universal-nav" aria-label="<?php esc_attr_e('Utility navigation', 'patterson-nav'); ?>">
                <div class="nav-container">
                    <?php self::render_universal_nav(); ?>
                </div>
            </nav><nav class="main-nav <?php echo $nav_mode === 'dark' ? 'main-nav--dark-mode' : ''; ?>" aria-label="<?php esc_attr_e('Main navigation', 'patterson-nav'); ?>"><div class="nav-container"><?php 
                    if (!empty($options['brand_logo_enabled']) || !empty($atts['brand_logo_url'])) : 
                        self::render_brand_logo($options, $atts['brand_logo_url']);
                    endif; 
                    ?><button 
                        class="main-nav__mobile-toggle" 
                        aria-expanded="false" 
                        aria-controls="mobile-menu"
                        aria-label="<?php esc_attr_e('Toggle navigation menu', 'patterson-nav'); ?>"
                    ><span class="main-nav__hamburger" aria-hidden="true"></span></button><?php 
                    if (!empty($options['main_nav_menu'])) : 
                        self::render_main_nav($options);
                    endif; 
                    ?><div class="main-nav__actions"><?php 
                        if (!empty($options['search_enabled'])) : 
                            self::render_search($options);
                        endif;
                        
                        if (!empty($options['cta_enabled']) && !empty($options['cta_text'])) : 
                            ?><a href="<?php echo esc_url($options['cta_url']); ?>" class="c-button c-button--primary">
                                <span><?php echo esc_html($options['cta_text']); ?></span>
                                <?php echo self::get_icon('angle-right'); ?>
                            </a><?php 
                        endif; 
                    ?></div></div>
            </nav><?php 
            if (!empty($options['main_nav_menu'])) : 
                self::render_dropdowns($options);
                self::render_mobile_menu($options);
            endif; 
            ?><div class="main-nav__backdrop" hidden></div></div><?php
        
        return ob_get_clean();
    }
    
    /**
     * Render universal navigation (Patterson Companies links from config)
     */
    private static function render_universal_nav() {
        // Load configuration from JSON file
        $config = self::get_config();
        
        // Patterson logo/brand
        $logo_url = PATTERSON_NAV_PLUGIN_URL . 'assets/patterson-logo.svg';
        $logo_config = $config['universal_nav']['logo'];
        
        echo '<div class="universal-nav__logo">';
        echo '<a href="' . esc_url($logo_config['url']) . '" aria-label="' . esc_attr($logo_config['aria_label']) . '">';
        echo '<img src="' . esc_url($logo_url) . '" alt="' . esc_attr($logo_config['alt']) . '" width="' . intval($logo_config['width']) . '" height="' . intval($logo_config['height']) . '">';
        echo '</a>';
        echo '</div>';
        
        // Universal navigation links from config
        $universal_links = $config['universal_nav']['links'];
        
        // Allow filtering of universal links
        $universal_links = apply_filters('patterson_nav_universal_links', $universal_links);
        
        if (!empty($universal_links)) {
            echo '<ul class="universal-nav__menu" role="list">';
            foreach ($universal_links as $link) {
                echo '<li>';
                echo '<a href="' . esc_url($link['url']) . '">' . esc_html($link['title']) . '</a>';
                echo '</li>';
            }
            echo '</ul>';
        }
    }
    
    /**
     * Render main navigation menu (desktop)
     */
    private static function render_main_nav($options) {
        $menu_id = $options['main_nav_menu'];
        $menu_items = wp_get_nav_menu_items($menu_id);
        
        if (!$menu_items) {
            return;
        }
        
        // Group items by parent
        $menu_tree = array();
        foreach ($menu_items as $item) {
            if ($item->menu_item_parent == 0) {
                $menu_tree[$item->ID] = array(
                    'item' => $item,
                    'children' => array()
                );
            }
        }
        
        // Add children
        foreach ($menu_items as $item) {
            if ($item->menu_item_parent != 0 && isset($menu_tree[$item->menu_item_parent])) {
                $menu_tree[$item->menu_item_parent]['children'][] = $item;
            }
        }
        
        echo '<ul class="main-nav__menu" role="list">';
        
        foreach ($menu_tree as $parent_id => $data) {
            $item = $data['item'];
            $has_children = !empty($data['children']);
            
            echo '<li class="main-nav__item">';
            
            if ($has_children) {
                $dropdown_id = 'dropdown-' . sanitize_title($item->title);
                $has_url = !empty($item->url) && $item->url !== '#';
                
                if ($has_url) {
                    // Nav item has a link AND children - render as link with dropdown
                    echo '<a class="main-nav__link" href="' . esc_url($item->url) . '" aria-expanded="false" aria-controls="' . esc_attr($dropdown_id) . '">';
                    echo esc_html($item->title);
                    echo self::get_icon_svg('angle-down');
                    echo '</a>';
                } else {
                    // Nav item has no link, just children - render as button
                    echo '<button class="main-nav__link" aria-expanded="false" aria-controls="' . esc_attr($dropdown_id) . '">';
                    echo esc_html($item->title);
                    echo self::get_icon_svg('angle-down');
                    echo '</button>';
                }
            } else {
                echo '<a class="main-nav__link" href="' . esc_url($item->url) . '">';
                echo esc_html($item->title);
                echo '</a>';
            }
            
            echo '</li>';
        }
        
        echo '</ul>';
    }
    
    /**
     * Render dropdown mega menus
     */
    private static function render_dropdowns($options) {
        $menu_id = $options['main_nav_menu'];
        $menu_items = wp_get_nav_menu_items($menu_id);
        
        if (!$menu_items) {
            return;
        }
        
        // Group items - build 3-level tree structure
        $menu_tree = array();
        $all_items_by_id = array();
        
        // First pass: index all items by ID
        foreach ($menu_items as $item) {
            $all_items_by_id[$item->ID] = $item;
        }
        
        // Second pass: build parent items
        foreach ($menu_items as $item) {
            if ($item->menu_item_parent == 0) {
                $menu_tree[$item->ID] = array(
                    'item' => $item,
                    'children' => array()
                );
            }
        }
        
        // Third pass: add 2nd level children to parents
        foreach ($menu_items as $item) {
            if ($item->menu_item_parent != 0 && isset($menu_tree[$item->menu_item_parent])) {
                $menu_tree[$item->menu_item_parent]['children'][$item->ID] = array(
                    'item' => $item,
                    'children' => array()
                );
            }
        }
        
        // Fourth pass: add 3rd level children to 2nd level items
        foreach ($menu_items as $item) {
            if ($item->menu_item_parent != 0) {
                // Check if this item's parent is a 2nd level item
                foreach ($menu_tree as $parent_id => $parent_data) {
                    if (isset($parent_data['children'][$item->menu_item_parent])) {
                        $menu_tree[$parent_id]['children'][$item->menu_item_parent]['children'][] = $item;
                    }
                }
            }
        }
        
        // Render dropdowns
        foreach ($menu_tree as $parent_id => $data) {
            $item = $data['item'];
            $children = $data['children'];
            
            if (empty($children)) {
                continue;
            }
            
            $dropdown_id = 'dropdown-' . sanitize_title($item->title);
            $has_featured = !empty($item->enable_featured);
            
            echo '<div class="main-nav__dropdown" id="' . esc_attr($dropdown_id) . '" hidden>';
            echo '<div class="main-nav__dropdown-container">';
            echo '<div class="main-nav__dropdown-content">';
            
            // Left section with menu items
            echo '<div class="main-nav__dropdown-section">';
            echo '<div class="main-nav__dropdown-header">';
            
            // Check if parent nav item has a URL
            $has_url = !empty($item->url) && $item->url !== '#';
            
            if ($has_url) {
                // Parent nav item is a link - make dropdown title a link with caret icon
                echo '<a href="' . esc_url($item->url) . '" class="main-nav__dropdown-title">' . esc_html($item->title) . '</a>';
                echo self::get_icon_svg('angle-right');
            } else {
                // Parent nav item is not a link - keep as span without caret icon
                echo '<span class="main-nav__dropdown-title">' . esc_html($item->title) . '</span>';
            }
            
            echo '</div>';
            
            // Render children in columns (split into 2 columns)
            $children_array = array_values($children);
            $chunk_size = ceil(count($children_array) / 2);
            $chunks = array_chunk($children_array, $chunk_size);
            
            // Build entire columns section as a single string to prevent wpautop
            $columns_html = '<div class="main-nav__dropdown-columns">';
            foreach ($chunks as $chunk) {
                $columns_html .= '<div class="main-nav__dropdown-column">';
                foreach ($chunk as $child_data) {
                    $child = $child_data['item'];
                    $grandchildren = $child_data['children'];
                    $is_external = self::is_external_link($child->url);
                    
                    // Build the dropdown item
                    $columns_html .= '<a href="' . esc_url($child->url) . '" class="main-nav__dropdown-item"';
                    if ($is_external) {
                        $columns_html .= ' data-external="true"';
                    }
                    $columns_html .= '>';
                    $columns_html .= '<span class="main-nav__dropdown-item-title">' . esc_html($child->title) . '</span>';
                    if (!empty($child->description)) {
                        $columns_html .= '<span class="main-nav__dropdown-item-description">' . esc_html($child->description) . '</span>';
                    }
                    if ($is_external) {
                        $columns_html .= self::get_icon_svg('external');
                    }
                    $columns_html .= '</a>';
                    
                    // Render 3rd level items if they exist
                    if (!empty($grandchildren)) {
                        $columns_html .= '<div class="main-nav__dropdown-subitems">';
                        foreach ($grandchildren as $grandchild) {
                            $is_external_sub = self::is_external_link($grandchild->url);
                            
                            $columns_html .= '<a href="' . esc_url($grandchild->url) . '" class="main-nav__dropdown-subitem"';
                            if ($is_external_sub) {
                                $columns_html .= ' data-external="true"';
                            }
                            $columns_html .= '>';
                            $columns_html .= '<span class="main-nav__dropdown-item-title">' . esc_html($grandchild->title) . '</span>';
                            if ($is_external_sub) {
                                $columns_html .= self::get_icon_svg('external');
                            }
                            $columns_html .= '</a>';
                        }
                        $columns_html .= '</div>';
                    }
                }
                $columns_html .= '</div>';
            }
            $columns_html .= '</div>'; // .main-nav__dropdown-columns
            
            // Echo the complete columns HTML in one go
            echo $columns_html;
            
            echo '</div>'; // .main-nav__dropdown-section
            
            // Featured content (if enabled)
            if ($has_featured && !empty($item->featured_title)) {
                echo '<div class="main-nav__dropdown-featured">';
                
                if (!empty($item->featured_image)) {
                    // Mark as decorative - the title provides the context
                    echo '<img src="' . esc_url($item->featured_image) . '" alt="" role="presentation" loading="lazy">';
                }
                
                echo '<div class="main-nav__dropdown-featured-content">';
                echo '<span class="main-nav__dropdown-featured-title">' . esc_html($item->featured_title) . '</span>';
                
                if (!empty($item->featured_desc)) {
                    echo '<p>' . esc_html($item->featured_desc) . '</p>';
                }
                
                if (!empty($item->featured_link_url)) {
                    // Create descriptive link text for screen readers
                    $link_text = !empty($item->featured_link_text) ? $item->featured_link_text : __('More', 'patterson-nav');
                    // If link text is generic, make it more descriptive
                    if ($link_text === __('More', 'patterson-nav')) {
                        /* translators: %s: Featured title */
                        $link_text = sprintf(__('Learn more about %s', 'patterson-nav'), strtolower($item->featured_title));
                    }
                    echo '<a href="' . esc_url($item->featured_link_url) . '">';
                    echo esc_html($link_text);
                    echo self::get_icon_svg('angle-right');
                    echo '</a>';
                }
                
                echo '</div>'; // .main-nav__dropdown-featured-content
                echo '</div>'; // .main-nav__dropdown-featured
            }
            
            echo '</div>'; // .main-nav__dropdown-content
            echo '</div>'; // .main-nav__dropdown-container
            echo '</div>'; // .main-nav__dropdown
        }
    }
    
    /**
     * Render mobile menu
     */
    private static function render_mobile_menu($options) {
        $menu_id = $options['main_nav_menu'];
        $menu_items = wp_get_nav_menu_items($menu_id);
        
        if (!$menu_items) {
            return;
        }
        
        // Group items
        $menu_tree = array();
        foreach ($menu_items as $item) {
            if ($item->menu_item_parent == 0) {
                $menu_tree[$item->ID] = array(
                    'item' => $item,
                    'children' => array()
                );
            }
        }
        
        foreach ($menu_items as $item) {
            if ($item->menu_item_parent != 0 && isset($menu_tree[$item->menu_item_parent])) {
                $menu_tree[$item->menu_item_parent]['children'][] = $item;
            }
        }
        
        echo '<div class="main-nav__mobile-menu" id="mobile-menu" hidden>';
        
        echo '<div class="main-nav__mobile-header">';
        echo '<button class="main-nav__mobile-close" aria-label="' . esc_attr__('Close navigation menu', 'patterson-nav') . '">';
        echo self::get_icon_svg('close');
        echo '</button>';
        echo '</div>';
        
        // Search in mobile
        if (!empty($options['search_enabled'])) {
            echo '<div class="main-nav__mobile-search">';
            self::render_search($options);
            echo '</div>';
        }
        
        // Menu items
        echo '<ul class="main-nav__mobile-items" role="list">';
        
        foreach ($menu_tree as $parent_id => $data) {
            $item = $data['item'];
            $children = $data['children'];
            $has_children = !empty($children);
            
            echo '<li>';
            
            if ($has_children) {
                $dropdown_id = 'mobile-dropdown-' . sanitize_title($item->title);
                $has_url = !empty($item->url) && $item->url !== '#';
                
                echo '<button class="main-nav__mobile-link" aria-expanded="false" aria-controls="' . esc_attr($dropdown_id) . '">';
                echo esc_html($item->title);
                echo self::get_icon_svg('angle-down');
                echo '</button>';
                
                echo '<div class="main-nav__mobile-dropdown" id="' . esc_attr($dropdown_id) . '" hidden>';
                
                // If parent nav item has a URL, add it as the first link in the mobile dropdown
                if ($has_url) {
                    echo '<a href="' . esc_url($item->url) . '" class="main-nav__mobile-dropdown-title">';
                    echo esc_html($item->title);
                    echo '</a>';
                }
                
                // Render children
                foreach ($children as $child) {
                    $is_external = self::is_external_link($child->url);
                    
                    echo '<a href="' . esc_url($child->url) . '"';
                    if ($is_external) {
                        echo ' data-external="true"';
                    }
                    echo '>';
                    echo esc_html($child->title);
                    if ($is_external) {
                        echo self::get_icon_svg('external');
                    }
                    echo '</a>';
                }
                echo '</div>';
            } else {
                echo '<a class="main-nav__mobile-link" href="' . esc_url($item->url) . '">';
                echo esc_html($item->title);
                echo '</a>';
            }
            
            echo '</li>';
        }
        
        echo '</ul>';
        
        // CTA in mobile
        if (!empty($options['cta_enabled']) && !empty($options['cta_text'])) {
            echo '<div class="main-nav__mobile-cta">';
            echo '<a href="' . esc_url($options['cta_url']) . '" class="c-button c-button--primary">';
            echo '<span>' . esc_html($options['cta_text']) . '</span>';
            echo self::get_icon('angle-right');
            echo '</a>';
            echo '</div>';
        }
        
        // Universal nav in mobile (Patterson Companies links) - use same config as desktop
        $config = self::get_config();
        $universal_links = $config['universal_nav']['links'];
        
        // Allow filtering of universal links
        $universal_links = apply_filters('patterson_nav_universal_links', $universal_links);
        
        if (!empty($universal_links)) {
            echo '<div class="main-nav__mobile-universal">';
            
            // Patterson logo
            $logo_config = $config['universal_nav']['logo'];
            echo '<div class="main-nav__mobile-universal-logo">';
            echo '<a href="' . esc_url($logo_config['url']) . '" aria-label="' . esc_attr($logo_config['aria_label']) . '">';
            echo '<img src="' . plugins_url('assets/patterson-logo.svg', dirname(__FILE__)) . '" alt="' . esc_attr($logo_config['alt']) . '" width="' . intval($logo_config['width']) . '" height="' . intval($logo_config['height']) . '">';
            echo '</a>';
            echo '</div>';
            
            // Universal links
            echo '<ul class="main-nav__mobile-universal-menu" role="list">';
            foreach ($universal_links as $link) {
                echo '<li><a href="' . esc_url($link['url']) . '">' . esc_html($link['title']) . '</a></li>';
            }
            echo '</ul>';
            
            echo '</div>';
        }
        
        echo '</div>'; // .main-nav__mobile-menu
    }
    
    /**
     * Render search
     */
    private static function render_search($options) {
        if (!empty($options['search_code'])) {
            // Check if it's a shortcode
            if (preg_match('/\[.*\]/', $options['search_code'])) {
                echo do_shortcode($options['search_code']);
            } else {
                // Output as HTML
                echo wp_kses_post($options['search_code']);
            }
        } else {
            // Default search button
            echo '<button class="main-nav__search" aria-label="' . esc_attr__('Open search', 'patterson-nav') . '">';
            echo self::get_icon_svg('search');
            echo '<span>' . esc_html__('Search', 'patterson-nav') . '</span>';
            echo '</button>';
        }
    }
}

