# Installation Guide

## Prerequisites

- WordPress 6.0+ or Craft CMS 4+
- PHP 7.4 or higher
- Modern browser (Chrome 76+, Edge 17+, Safari 9+, Firefox 103+)

## Installation Steps

### For WordPress Sites

1. **Upload Plugin**
   - Copy the `wordpress` folder to `wp-content/plugins/`
   - Rename it to `patterson-navigation` if needed

2. **Activate Plugin**
   - Go to WordPress Admin → Plugins
   - Find "Patterson Navigation"
   - Click "Activate"

3. **Configure Settings**
   - Go to Patterson Nav in the admin menu
   - Configure your settings (see [Configuration Guide](configuration.md))

4. **Set Up Menus**
   - Go to Appearance → Menus
   - Create or edit your menus
   - Add custom fields to menu items (descriptions, featured content)

5. **Add to Theme**
   - Add to your theme template:
   ```php
   <?php patterson_nav(); ?>
   ```
   
   OR use the shortcode:
   ```php
   <?php echo do_shortcode('[patterson_navigation]'); ?>
   ```

### For Craft CMS Sites

_Note: Craft plugin development pending. Contact development team for implementation._

## Design Tokens Setup

1. **Copy Tokens File**
   - Copy `design-tokens/tokens.css` to your theme/site

2. **Include in Your Site**
   
   **For WordPress:**
   ```php
   // In functions.php
   wp_enqueue_style('patterson-tokens', get_template_directory_uri() . '/tokens.css');
   ```
   
   **For Craft:**
   ```twig
   {# In your layout template #}
   <link rel="stylesheet" href="{{ alias('@web/assets/tokens.css') }}">
   ```
   
   **OR configure in plugin settings:**
   - Enable "Load Design Tokens File"
   - Enter the URL to your tokens.css file

3. **Customize Brand Color**
   - Edit tokens.css and change:
   ```css
   :root {
     --brand-primary: #YOUR_BRAND_COLOR;
   }
   ```

## Verification

After installation, verify:

- [ ] Navigation displays correctly on desktop
- [ ] Mobile menu works (test at <1024px viewport)
- [ ] Dropdowns open/close properly
- [ ] Keyboard navigation works (Tab, Enter, Escape)
- [ ] Brand color is correct
- [ ] Search functionality works (if enabled)
- [ ] CTA button displays (if enabled)

## Troubleshooting

### Navigation not displaying
- Check that the plugin is activated
- Verify you've added the template tag or shortcode
- Check browser console for JavaScript errors

### Styles look wrong
- Ensure design tokens file is loaded
- Check for theme CSS conflicts
- Verify Font Awesome is loading

### Dropdowns not working
- Check browser console for JavaScript errors
- Ensure JavaScript file is loading
- Test in different browser

### Mobile menu not working
- Verify viewport is less than 1024px
- Check that backdrop and mobile menu elements exist
- Test JavaScript console for errors

## Next Steps

- [Configuration Guide](configuration.md)
- [WordPress Setup](wordpress-setup.md)
- [Customization Guide](customization.md)

