# Installation Guide

## Prerequisites

- WordPress 6.0+
- PHP 7.4+
- Modern browser (Chrome 76+, Edge 17+, Safari 9+, Firefox 103+)

## WordPress Installation

### 1. Upload Plugin

```bash
# Copy the wordpress folder to your plugins directory
cp -r wordpress /path/to/wp-content/plugins/patterson-navigation
```

Or manually:
- Upload `wordpress` folder to `wp-content/plugins/`
- Rename to `patterson-navigation` if needed

### 2. Activate Plugin

- Go to **WordPress Admin → Plugins**
- Find "Patterson Subsidiary Navigation"
- Click **Activate**

### 3. Create Menus

Go to **Appearance → Menus** and create:

**Universal Navigation** (Patterson top bar):
- About
- Our Brands
- Investors
- Sustainability
- Careers

**Main Navigation** (Your brand):
- 5-7 parent items
- Add sub-items under each parent
- Add descriptions to sub-items (optional)

### 4. Configure Plugin

Go to **Patterson Nav** in admin menu:

**Required Settings:**
- Select Universal Nav menu
- Select Main Nav menu
- Set brand primary color

**Optional Settings:**
- Enable brand logo (upload your logo)
- Enable search (add shortcode/HTML)
- Enable CTA button (set text and URL)
- Adjust mobile breakpoint if needed

Click **Save Changes**

### 5. Add to Theme

Add to your header template (e.g., `header.php`):

```php
<div class="your-hero-section" style="position: relative;">
  <?php patterson_nav(); ?>
  
  <div class="hero-content" style="padding-top: 150px;">
    <!-- Your hero content -->
  </div>
</div>
```

**Important:** The navigation needs a `position: relative` parent container to position correctly.

## Design Tokens (Optional)

The navigation uses design tokens from `design-tokens/tokens.css`. You have two options:

### Option 1: Load via Plugin (Recommended)
1. Upload `design-tokens/tokens.css` to your theme
2. In **Patterson Nav** settings:
   - Check "Load Design Tokens File"
   - Enter URL: `/wp-content/themes/yourtheme/tokens.css`

### Option 2: Load in Theme
```php
// In functions.php
wp_enqueue_style(
    'patterson-tokens', 
    get_template_directory_uri() . '/assets/css/tokens.css'
);
```

### Customize Brand Color

Override in your theme CSS or tokens file:
```css
:root {
  --primary-color: #YOUR_BRAND_COLOR;
}
```

## Verification

Test the following:

- [ ] Navigation displays on desktop
- [ ] Dropdowns open when clicking parent items
- [ ] Mobile menu works (resize browser to <1420px)
- [ ] Keyboard navigation works (Tab, Enter, Escape)
- [ ] Brand color appears on CTA button
- [ ] Search works (if enabled)

## Troubleshooting

**Navigation not showing**
- Verify plugin is activated
- Check you added `<?php patterson_nav(); ?>` to theme
- Ensure menus are assigned in settings

**Styles look wrong**
- Check design tokens file is loading
- Look for CSS conflicts with theme
- Clear browser cache

**Dropdowns don't work**
- Check browser console for JavaScript errors
- Verify JavaScript file is loading
- Test in different browser

**Mobile menu doesn't work**
- Ensure viewport is < 1420px (or your custom breakpoint)
- Check for JavaScript errors in console

## Next Steps

- [WordPress Configuration](wordpress-configuration.md) - Detailed settings reference
- [Implementation Guide](implementation-guide.md) - Integration tips and best practices
