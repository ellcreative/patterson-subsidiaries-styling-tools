<?php
/**
 * Frontend Renderer for Patterson Navigation
 */

if (!defined('ABSPATH')) {
    exit;
}

class Patterson_Nav_Renderer {
    
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
        
        // Start output buffering
        ob_start();
        
        // Add inline styles for site primary color
        if (isset($options['brand_color']) && $options['brand_color']) {
            echo '<style>';
            echo ':root { --primary-color: ' . esc_attr($options['brand_color']) . '; }';
            echo '</style>';
        }
        
        ?>
        <header class="site-navigation" id="site-navigation">
            
            <?php if (!empty($options['universal_nav_enabled']) && !empty($options['universal_nav_menu'])) : ?>
                <!-- Universal Navigation -->
                <nav class="universal-nav" aria-label="<?php esc_attr_e('Utility navigation', 'patterson-nav'); ?>">
                    <div class="nav-container">
                        <?php self::render_universal_nav($options); ?>
                    </div>
                </nav>
            <?php endif; ?>
            
            <!-- Main Navigation -->
            <nav class="main-nav" aria-label="<?php esc_attr_e('Main navigation', 'patterson-nav'); ?>">
                <div class="nav-container">
                    
                    <!-- Mobile Menu Toggle -->
                    <button 
                        class="main-nav__mobile-toggle" 
                        aria-expanded="false" 
                        aria-controls="mobile-menu"
                        aria-label="<?php esc_attr_e('Toggle navigation menu', 'patterson-nav'); ?>"
                    >
                        <span class="main-nav__hamburger" aria-hidden="true"></span>
                    </button>
                    
                    <!-- Desktop Menu -->
                    <?php if (!empty($options['main_nav_menu'])) : ?>
                        <?php self::render_main_nav($options); ?>
                    <?php endif; ?>
                    
                    <!-- Actions (Search + CTA) -->
                    <div class="main-nav__actions">
                        <?php if (!empty($options['search_enabled'])) : ?>
                            <?php self::render_search($options); ?>
                        <?php endif; ?>
                        
                        <?php if (!empty($options['cta_enabled']) && !empty($options['cta_text'])) : ?>
                            <a href="<?php echo esc_url($options['cta_url']); ?>" class="main-nav__cta">
                                <?php echo esc_html($options['cta_text']); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                    
                </div>
            </nav>
            
            <!-- Dropdown Mega Menus -->
            <?php if (!empty($options['main_nav_menu'])) : ?>
                <?php self::render_dropdowns($options); ?>
            <?php endif; ?>
            
            <!-- Mobile Menu Panel -->
            <?php if (!empty($options['main_nav_menu'])) : ?>
                <?php self::render_mobile_menu($options); ?>
            <?php endif; ?>
            
            <!-- Mobile Menu Backdrop -->
            <div class="main-nav__backdrop" hidden></div>
            
        </header>
        <?php
        
        return ob_get_clean();
    }
    
    /**
     * Render universal navigation
     */
    private static function render_universal_nav($options) {
        $menu_id = $options['universal_nav_menu'];
        $menu = wp_get_nav_menu_object($menu_id);
        
        if (!$menu) {
            return;
        }
        
        echo '<div class="universal-nav__logo">';
        echo '<a href="' . esc_url(home_url('/')) . '">';
        echo '<span class="universal-nav__logo-text">' . esc_html(get_bloginfo('name')) . '</span>';
        echo '</a>';
        echo '</div>';
        
        $menu_items = wp_get_nav_menu_items($menu_id);
        if ($menu_items) {
            echo '<ul class="universal-nav__menu" role="list">';
            foreach ($menu_items as $item) {
                if ($item->menu_item_parent == 0) { // Only top-level items
                    echo '<li>';
                    echo '<a href="' . esc_url($item->url) . '">' . esc_html($item->title) . '</a>';
                    echo '</li>';
                }
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
            echo '<h2>' . esc_html($item->title) . '</h2>';
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
                    echo '<h3>' . esc_html($child->title) . '</h3>';
                    if (!empty($child->description)) {
                        echo '<p>' . esc_html($child->description) . '</p>';
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
                    echo '<img src="' . esc_url($item->featured_image) . '" alt="' . esc_attr($item->featured_title) . '" loading="lazy">';
                }
                
                echo '<div class="main-nav__dropdown-featured-content">';
                echo '<h3>' . esc_html($item->featured_title) . '</h3>';
                
                if (!empty($item->featured_desc)) {
                    echo '<p>' . esc_html($item->featured_desc) . '</p>';
                }
                
                if (!empty($item->featured_link_url)) {
                    $link_text = !empty($item->featured_link_text) ? $item->featured_link_text : __('More', 'patterson-nav');
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
        
        // Universal nav in mobile
        if (!empty($options['universal_nav_enabled']) && !empty($options['universal_nav_menu'])) {
            $universal_items = wp_get_nav_menu_items($options['universal_nav_menu']);
            if ($universal_items) {
                echo '<ul class="main-nav__mobile-universal" role="list">';
                foreach ($universal_items as $item) {
                    if ($item->menu_item_parent == 0) {
                        echo '<li><a href="' . esc_url($item->url) . '">' . esc_html($item->title) . '</a></li>';
                    }
                }
                echo '</ul>';
            }
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

