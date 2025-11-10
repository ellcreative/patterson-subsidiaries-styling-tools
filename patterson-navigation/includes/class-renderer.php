<?php
/**
 * Frontend Renderer for Patterson Navigation
 */

if (!defined('ABSPATH')) {
    exit;
}

class Patterson_Nav_Renderer {
    
    /**
     * Enqueue frontend assets
     */
    private static function enqueue_assets() {
        // Enqueue Adobe Typekit fonts (required for Patterson brand fonts)
        if (!wp_style_is('patterson-typekit', 'enqueued')) {
            wp_enqueue_style(
                'patterson-typekit',
                'https://use.typekit.net/akz7boc.css',
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
     * Get SVG icon
     */
    private static function get_icon_svg($type = 'angle-down') {
        $icons = array(
            'angle-down' => '<svg class="nav-icon" aria-hidden="true" width="12" height="8" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1L6 6L11 1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            'angle-right' => '<svg class="nav-icon" aria-hidden="true" width="8" height="12" viewBox="0 0 8 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1L6 6L1 11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            'search' => '<svg class="nav-icon" aria-hidden="true" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="8" cy="8" r="6" stroke="currentColor" stroke-width="2"/><path d="M12.5 12.5L16 16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
            'close' => '<svg class="nav-icon" aria-hidden="true" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2 2L16 16M16 2L2 16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
        );
        
        return isset($icons[$type]) ? $icons[$type] : '';
    }
    
    /**
     * Render the navigation
     */
    public static function render_navigation($atts = array()) {
        $options = get_option('patterson_nav_settings');
        
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
            </nav><nav class="main-nav" aria-label="<?php esc_attr_e('Main navigation', 'patterson-nav'); ?>"><div class="nav-container"><?php 
                    if (!empty($options['brand_logo_enabled']) && !empty($options['brand_logo_url'])) : 
                ?><div class="main-nav__brand-logo">
                            <a href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php echo esc_attr(get_bloginfo('name') . ' ' . __('Home', 'patterson-nav')); ?>">
                                <img 
                                    src="<?php echo esc_url($options['brand_logo_url']); ?>" 
                                    alt="<?php echo esc_attr(get_bloginfo('name')); ?>" 
                                    width="<?php echo !empty($options['brand_logo_width']) ? esc_attr($options['brand_logo_width']) : '198'; ?>" 
                                    height="<?php echo !empty($options['brand_logo_height']) ? esc_attr($options['brand_logo_height']) : '24'; ?>"
                                >
                            </a>
                        </div><?php 
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
                            ?><a href="<?php echo esc_url($options['cta_url']); ?>" class="main-nav__cta"><?php echo esc_html($options['cta_text']); ?></a><?php 
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
     * Render universal navigation (hardcoded Patterson Companies links)
     */
    private static function render_universal_nav() {
        // Patterson logo/brand
        $logo_url = PATTERSON_NAV_PLUGIN_URL . 'assets/patterson-logo.svg';
        
        echo '<div class="universal-nav__logo">';
        echo '<a href="https://www.pattersoncompanies.com/" aria-label="Patterson Companies">';
        echo '<img src="' . esc_url($logo_url) . '" alt="Patterson" width="130" height="22">';
        echo '</a>';
        echo '</div>';
        
        // Static Patterson Companies navigation links
        $universal_links = array(
            array(
                'title' => 'About Patterson',
                'url' => 'https://www.pattersoncompanies.com/about/'
            ),
            array(
                'title' => 'Careers',
                'url' => 'https://www.pattersoncompanies.com/careers/'
            ),
            array(
                'title' => 'Investors',
                'url' => 'https://www.pattersoncompanies.com/investors/'
            ),
            array(
                'title' => 'News',
                'url' => 'https://www.pattersoncompanies.com/news/'
            ),
            array(
                'title' => 'Contact',
                'url' => 'https://www.pattersoncompanies.com/contact/'
            ),
        );
        
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
                echo '<button class="main-nav__link" aria-expanded="false" aria-controls="' . esc_attr($dropdown_id) . '">';
                echo esc_html($item->title);
                echo self::get_icon_svg('angle-down');
                echo '</button>';
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
            echo '<span class="main-nav__dropdown-title">' . esc_html($item->title) . '</span>';
            echo self::get_icon_svg('angle-right');
            echo '</div>';
            
            // Render children in columns (split into 2 columns)
            $chunk_size = ceil(count($children) / 2);
            $chunks = array_chunk($children, $chunk_size);
            
            echo '<div class="main-nav__dropdown-columns">';
            foreach ($chunks as $chunk) {
                echo '<div class="main-nav__dropdown-column">';
                foreach ($chunk as $child) {
                    echo '<a href="' . esc_url($child->url) . '" class="main-nav__dropdown-item">';
                    echo '<span class="main-nav__dropdown-item-title">' . esc_html($child->title) . '</span>';
                    if (!empty($child->description)) {
                        echo '<span class="main-nav__dropdown-item-description">' . esc_html($child->description) . '</span>';
                    }
                    echo '</a>';
                }
                echo '</div>';
            }
            echo '</div>'; // .main-nav__dropdown-columns
            
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
                echo '<button class="main-nav__mobile-link" aria-expanded="false" aria-controls="' . esc_attr($dropdown_id) . '">';
                echo esc_html($item->title);
                echo self::get_icon_svg('angle-down');
                echo '</button>';
                
                echo '<div class="main-nav__mobile-dropdown" id="' . esc_attr($dropdown_id) . '" hidden>';
                foreach ($children as $child) {
                    echo '<a href="' . esc_url($child->url) . '">' . esc_html($child->title) . '</a>';
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
            echo '<a href="' . esc_url($options['cta_url']) . '" class="main-nav__cta">';
            echo esc_html($options['cta_text']);
            echo '</a>';
            echo '</div>';
        }
        
        // Universal nav in mobile (Patterson Companies links)
        $universal_links = array(
            array(
                'title' => 'About Patterson',
                'url' => 'https://www.pattersoncompanies.com/about/'
            ),
            array(
                'title' => 'Careers',
                'url' => 'https://www.pattersoncompanies.com/careers/'
            ),
            array(
                'title' => 'Investors',
                'url' => 'https://www.pattersoncompanies.com/investors/'
            ),
            array(
                'title' => 'News',
                'url' => 'https://www.pattersoncompanies.com/news/'
            ),
            array(
                'title' => 'Contact',
                'url' => 'https://www.pattersoncompanies.com/contact/'
            ),
        );
        
        // Allow filtering of universal links
        $universal_links = apply_filters('patterson_nav_universal_links', $universal_links);
        
        if (!empty($universal_links)) {
            echo '<div class="main-nav__mobile-universal">';
            
            // Patterson logo
            echo '<div class="main-nav__mobile-universal-logo">';
            echo '<a href="https://pattersoncompanies.com" aria-label="Patterson Companies">';
            echo '<img src="' . plugins_url('assets/patterson-logo.svg', dirname(__FILE__)) . '" alt="Patterson" width="130" height="22">';
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

