<?php
/**
 * Custom Menu Walker for Patterson Navigation
 * 
 * Extends Walker_Nav_Menu to add custom markup for mega-menu dropdowns
 */

if (!defined('ABSPATH')) {
    exit;
}

class Patterson_Nav_Walker extends Walker_Nav_Menu {
    
    /**
     * Start level (ul)
     */
    public function start_lvl(&$output, $depth = 0, $args = null) {
        if ($depth === 0) {
            // This is a top-level dropdown
            $output .= '<div class="main-nav__dropdown" hidden>';
            $output .= '<div class="main-nav__dropdown-container">';
            $output .= '<div class="main-nav__dropdown-content">';
            $output .= '<div class="main-nav__dropdown-section">';
        } else {
            // Nested level (shouldn't happen in mega menu, but just in case)
            $output .= '<div class="main-nav__dropdown-nested">';
        }
    }
    
    /**
     * End level (ul)
     */
    public function end_lvl(&$output, $depth = 0, $args = null) {
        if ($depth === 0) {
            $output .= '</div>'; // .main-nav__dropdown-section
            $output .= '</div>'; // .main-nav__dropdown-content
            $output .= '</div>'; // .main-nav__dropdown-container
            $output .= '</div>'; // .main-nav__dropdown
        } else {
            $output .= '</div>'; // .main-nav__dropdown-nested
        }
    }
    
    /**
     * Start element (li)
     */
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        
        // Get menu item meta
        $item_description = get_post_meta($item->ID, '_patterson_nav_description', true);
        $has_dropdown = in_array('menu-item-has-children', $classes);
        
        if ($depth === 0) {
            // Top-level item
            $output .= '<li class="main-nav__item ' . esc_attr($class_names) . '">';
            
            if ($has_dropdown) {
                // Button for dropdown trigger
                $dropdown_id = 'dropdown-' . sanitize_title($item->title);
                $output .= '<button class="main-nav__link" ';
                $output .= 'aria-expanded="false" ';
                $output .= 'aria-controls="' . esc_attr($dropdown_id) . '">';
                $output .= esc_html($item->title);
                $output .= '<svg class="nav-icon" aria-hidden="true" width="12" height="8" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1L6 6L11 1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
                $output .= '</button>';
            } else {
                // Regular link
                $output .= '<a class="main-nav__link" href="' . esc_url($item->url) . '">';
                $output .= esc_html($item->title);
                $output .= '</a>';
            }
        } else {
            // Dropdown item
            $output .= '<a href="' . esc_url($item->url) . '" class="main-nav__dropdown-item">';
            
            if ($item_description) {
                $output .= '<h3>' . esc_html($item->title) . '</h3>';
                $output .= '<p>' . esc_html($item_description) . '</p>';
            } else {
                $output .= '<h3>' . esc_html($item->title) . '</h3>';
            }
            
            $output .= '</a>';
        }
    }
    
    /**
     * End element (li)
     */
    public function end_el(&$output, $item, $depth = 0, $args = null) {
        if ($depth === 0) {
            $output .= '</li>';
        }
    }
}

/**
 * Mobile Menu Walker
 * Simplified walker for mobile accordion menu
 */
class Patterson_Nav_Mobile_Walker extends Walker_Nav_Menu {
    
    /**
     * Start level
     */
    public function start_lvl(&$output, $depth = 0, $args = null) {
        $output .= '<div class="main-nav__mobile-dropdown" hidden>';
    }
    
    /**
     * End level
     */
    public function end_lvl(&$output, $depth = 0, $args = null) {
        $output .= '</div>';
    }
    
    /**
     * Start element
     */
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $has_dropdown = in_array('menu-item-has-children', $classes);
        
        if ($depth === 0) {
            $output .= '<li>';
            
            if ($has_dropdown) {
                $dropdown_id = 'mobile-dropdown-' . sanitize_title($item->title);
                $output .= '<button class="main-nav__mobile-link" ';
                $output .= 'aria-expanded="false" ';
                $output .= 'aria-controls="' . esc_attr($dropdown_id) . '">';
                $output .= esc_html($item->title);
                $output .= '<svg class="nav-icon" aria-hidden="true" width="12" height="8" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1L6 6L11 1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
                $output .= '</button>';
            } else {
                $output .= '<a class="main-nav__mobile-link" href="' . esc_url($item->url) . '">';
                $output .= esc_html($item->title);
                $output .= '</a>';
            }
        } else {
            $output .= '<a href="' . esc_url($item->url) . '">';
            $output .= esc_html($item->title);
            $output .= '</a>';
        }
    }
    
    /**
     * End element
     */
    public function end_el(&$output, $item, $depth = 0, $args = null) {
        if ($depth === 0) {
            $output .= '</li>';
        }
    }
}

