<?php
/**
 * Meta Boxes for Menu Items
 * Adds featured content and description fields to WordPress menu items
 */

if (!defined('ABSPATH')) {
    exit;
}

class Patterson_Nav_Meta_Boxes {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('wp_nav_menu_item_custom_fields', array($this, 'add_menu_item_fields'), 10, 4);
        add_action('wp_update_nav_menu_item', array($this, 'save_menu_item_fields'), 10, 2);
        add_filter('wp_setup_nav_menu_item', array($this, 'add_custom_nav_fields'));
    }
    
    /**
     * Add custom fields to menu items
     */
    public function add_menu_item_fields($item_id, $item, $depth, $args) {
        // Get saved values
        $description = get_post_meta($item_id, '_patterson_nav_description', true);
        $enable_featured = get_post_meta($item_id, '_patterson_nav_enable_featured', true);
        $featured_image = get_post_meta($item_id, '_patterson_nav_featured_image', true);
        $featured_title = get_post_meta($item_id, '_patterson_nav_featured_title', true);
        $featured_desc = get_post_meta($item_id, '_patterson_nav_featured_desc', true);
        $featured_link_text = get_post_meta($item_id, '_patterson_nav_featured_link_text', true);
        $featured_link_url = get_post_meta($item_id, '_patterson_nav_featured_link_url', true);
        
        ?>
        <p class="field-description description description-wide">
            <label for="edit-menu-item-patterson-description-<?php echo esc_attr($item_id); ?>">
                <?php esc_html_e('Menu Item Description', 'patterson-nav'); ?><br>
                <textarea 
                    id="edit-menu-item-patterson-description-<?php echo esc_attr($item_id); ?>"
                    class="widefat edit-menu-item-patterson-description" 
                    rows="3"
                    name="menu-item-patterson-description[<?php echo esc_attr($item_id); ?>]"><?php echo esc_textarea($description); ?></textarea>
                <span class="description"><?php esc_html_e('Short description shown in dropdown menu', 'patterson-nav'); ?></span>
            </label>
        </p>
        
        <?php if ($depth === 0) : // Only show featured content for top-level items ?>
            
            <p class="field-enable-featured description description-wide">
                <label for="edit-menu-item-patterson-enable-featured-<?php echo esc_attr($item_id); ?>">
                    <input 
                        type="checkbox" 
                        id="edit-menu-item-patterson-enable-featured-<?php echo esc_attr($item_id); ?>"
                        value="1" 
                        name="menu-item-patterson-enable-featured[<?php echo esc_attr($item_id); ?>]"
                        <?php checked($enable_featured, '1'); ?>>
                    <?php esc_html_e('Enable Featured Content', 'patterson-nav'); ?>
                </label>
            </p>
            
            <div class="patterson-featured-content" style="<?php echo $enable_featured ? '' : 'display:none;'; ?>" data-item-id="<?php echo esc_attr($item_id); ?>">
                
                <p class="field-featured-image description description-wide">
                    <label for="edit-menu-item-patterson-featured-image-<?php echo esc_attr($item_id); ?>">
                        <?php esc_html_e('Featured Image URL', 'patterson-nav'); ?><br>
                        <input 
                            type="text" 
                            id="edit-menu-item-patterson-featured-image-<?php echo esc_attr($item_id); ?>"
                            class="widefat" 
                            name="menu-item-patterson-featured-image[<?php echo esc_attr($item_id); ?>]"
                            value="<?php echo esc_url($featured_image); ?>">
                        <button type="button" class="button patterson-upload-image" data-item-id="<?php echo esc_attr($item_id); ?>">
                            <?php esc_html_e('Select Image', 'patterson-nav'); ?>
                        </button>
                    </label>
                </p>
                
                <p class="field-featured-title description description-wide">
                    <label for="edit-menu-item-patterson-featured-title-<?php echo esc_attr($item_id); ?>">
                        <?php esc_html_e('Featured Title', 'patterson-nav'); ?><br>
                        <input 
                            type="text" 
                            id="edit-menu-item-patterson-featured-title-<?php echo esc_attr($item_id); ?>"
                            class="widefat" 
                            name="menu-item-patterson-featured-title[<?php echo esc_attr($item_id); ?>]"
                            value="<?php echo esc_attr($featured_title); ?>">
                    </label>
                </p>
                
                <p class="field-featured-desc description description-wide">
                    <label for="edit-menu-item-patterson-featured-desc-<?php echo esc_attr($item_id); ?>">
                        <?php esc_html_e('Featured Description', 'patterson-nav'); ?><br>
                        <textarea 
                            id="edit-menu-item-patterson-featured-desc-<?php echo esc_attr($item_id); ?>"
                            class="widefat" 
                            rows="3"
                            name="menu-item-patterson-featured-desc[<?php echo esc_attr($item_id); ?>]"><?php echo esc_textarea($featured_desc); ?></textarea>
                    </label>
                </p>
                
                <p class="field-featured-link-text description description-wide">
                    <label for="edit-menu-item-patterson-featured-link-text-<?php echo esc_attr($item_id); ?>">
                        <?php esc_html_e('Featured Link Text', 'patterson-nav'); ?><br>
                        <input 
                            type="text" 
                            id="edit-menu-item-patterson-featured-link-text-<?php echo esc_attr($item_id); ?>"
                            class="widefat" 
                            name="menu-item-patterson-featured-link-text[<?php echo esc_attr($item_id); ?>]"
                            value="<?php echo esc_attr($featured_link_text); ?>"
                            placeholder="<?php esc_attr_e('More', 'patterson-nav'); ?>">
                    </label>
                </p>
                
                <p class="field-featured-link-url description description-wide">
                    <label for="edit-menu-item-patterson-featured-link-url-<?php echo esc_attr($item_id); ?>">
                        <?php esc_html_e('Featured Link URL', 'patterson-nav'); ?><br>
                        <input 
                            type="text" 
                            id="edit-menu-item-patterson-featured-link-url-<?php echo esc_attr($item_id); ?>"
                            class="widefat" 
                            name="menu-item-patterson-featured-link-url[<?php echo esc_attr($item_id); ?>]"
                            value="<?php echo esc_url($featured_link_url); ?>">
                    </label>
                </p>
                
            </div>
            
            <script>
            jQuery(document).ready(function($) {
                // Toggle featured content visibility
                $('#edit-menu-item-patterson-enable-featured-<?php echo esc_js($item_id); ?>').on('change', function() {
                    $('.patterson-featured-content[data-item-id="<?php echo esc_js($item_id); ?>"]').toggle(this.checked);
                });
                
                // Media uploader
                $('.patterson-upload-image[data-item-id="<?php echo esc_js($item_id); ?>"]').on('click', function(e) {
                    e.preventDefault();
                    var button = $(this);
                    var itemId = button.data('item-id');
                    var imageField = $('#edit-menu-item-patterson-featured-image-' + itemId);
                    
                    var frame = wp.media({
                        title: '<?php echo esc_js(__('Select Featured Image', 'patterson-nav')); ?>',
                        button: {
                            text: '<?php echo esc_js(__('Use this image', 'patterson-nav')); ?>'
                        },
                        multiple: false
                    });
                    
                    frame.on('select', function() {
                        var attachment = frame.state().get('selection').first().toJSON();
                        imageField.val(attachment.url);
                    });
                    
                    frame.open();
                });
            });
            </script>
            
        <?php endif; ?>
        <?php
    }
    
    /**
     * Save menu item fields
     */
    public function save_menu_item_fields($menu_id, $menu_item_db_id) {
        // Save description
        if (isset($_POST['menu-item-patterson-description'][$menu_item_db_id])) {
            $value = sanitize_textarea_field($_POST['menu-item-patterson-description'][$menu_item_db_id]);
            update_post_meta($menu_item_db_id, '_patterson_nav_description', $value);
        } else {
            delete_post_meta($menu_item_db_id, '_patterson_nav_description');
        }
        
        // Save featured content checkbox
        if (isset($_POST['menu-item-patterson-enable-featured'][$menu_item_db_id])) {
            update_post_meta($menu_item_db_id, '_patterson_nav_enable_featured', '1');
        } else {
            delete_post_meta($menu_item_db_id, '_patterson_nav_enable_featured');
        }
        
        // Save featured content fields
        $featured_fields = array(
            'featured_image' => 'esc_url_raw',
            'featured_title' => 'sanitize_text_field',
            'featured_desc' => 'sanitize_textarea_field',
            'featured_link_text' => 'sanitize_text_field',
            'featured_link_url' => 'esc_url_raw',
        );
        
        foreach ($featured_fields as $field => $sanitize_callback) {
            $post_key = 'menu-item-patterson-' . str_replace('_', '-', $field);
            if (isset($_POST[$post_key][$menu_item_db_id])) {
                $value = call_user_func($sanitize_callback, $_POST[$post_key][$menu_item_db_id]);
                update_post_meta($menu_item_db_id, '_patterson_nav_' . $field, $value);
            } else {
                delete_post_meta($menu_item_db_id, '_patterson_nav_' . $field);
            }
        }
    }
    
    /**
     * Add custom nav fields to menu item object
     */
    public function add_custom_nav_fields($menu_item) {
        $menu_item->description = get_post_meta($menu_item->ID, '_patterson_nav_description', true);
        $menu_item->enable_featured = get_post_meta($menu_item->ID, '_patterson_nav_enable_featured', true);
        $menu_item->featured_image = get_post_meta($menu_item->ID, '_patterson_nav_featured_image', true);
        $menu_item->featured_title = get_post_meta($menu_item->ID, '_patterson_nav_featured_title', true);
        $menu_item->featured_desc = get_post_meta($menu_item->ID, '_patterson_nav_featured_desc', true);
        $menu_item->featured_link_text = get_post_meta($menu_item->ID, '_patterson_nav_featured_link_text', true);
        $menu_item->featured_link_url = get_post_meta($menu_item->ID, '_patterson_nav_featured_link_url', true);
        
        return $menu_item;
    }
}

