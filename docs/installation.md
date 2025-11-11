# Installation Guide

## Prerequisites

- WordPress 6.0+
- PHP 7.4+
- Modern browser with CSS custom properties support

## 1. Install Plugin

### Upload and Activate

```bash
# Copy plugin folder to your plugins directory
cp -r patterson-navigation /path/to/wp-content/plugins/
```

Or manually:
1. Upload `patterson-navigation` folder to `wp-content/plugins/`
2. Go to **WordPress Admin → Plugins**
3. Find "Patterson Subsidiary Navigation"
4. Click **Activate**

## 2. Create Menus

Go to **Appearance → Menus** and create two menus:

### Universal Navigation Menu
Top Patterson bar with links:
- About
- Our Brands
- Investors
- Sustainability
- Careers

### Main Navigation Menu
Your brand's menu:
- 5-7 parent items
- Add child items under parents (these become dropdown menus)
- Optional: Add descriptions to child items (see [Configuration Guide](wordpress-configuration.md))

## 3. Configure Plugin

Go to **Patterson Nav** in WordPress admin:

**Required:**
- Select your Universal Nav menu
- Select your Main Nav menu
- Set your brand primary color

**Optional:**
- Enable brand logo (upload logo, set dimensions)
- Enable search (add shortcode or custom HTML)
- Enable CTA button (set text and URL)
- Adjust mobile breakpoint (default: 1420px)

Click **Save Changes**

See [Configuration Guide](wordpress-configuration.md) for detailed settings reference.

## 4. Add to Your Theme

The navigation must be placed in a **full-width container** that sits **on top of your hero image/section**.

### For Classical Themes

Edit your header template (e.g., `header.php` or `template-parts/header.php`):

```php
<div class="hero-section" style="position: relative; min-height: 100vh; background: url('hero.jpg') center/cover;">
  
  <?php patterson_nav(); ?>
  
  <div class="hero-content" style="padding-top: 150px;">
    <h1>Your Hero Title</h1>
    <p>Your content...</p>
  </div>
  
</div>
```

### For Block Themes

**Important:** You must create a full-width container for the navigation.

#### Option 1: Using a Group Block

1. Edit your header template in **Site Editor**
2. Add a **Group** block at the top
3. Set Group to **Full Width** in block settings
4. Add background image to the Group
5. Inside the Group, add a **Shortcode** block
6. Enter: `[patterson_navigation]`
7. Below the shortcode, add your hero content with padding

#### Option 2: Using Custom HTML

1. In Site Editor, add a **Custom HTML** block at the top
2. Enter:

```html
<div style="position: relative; width: 100vw; margin-left: calc(50% - 50vw); min-height: 100vh; background: url('your-hero.jpg') center/cover;">
  [patterson_navigation]
  <div style="padding-top: 150px; max-width: var(--wp--style--global--wide-size); margin: 0 auto;">
    <!-- Your hero content here -->
  </div>
</div>
```

#### Option 3: Create a Custom Template Part

1. Create `parts/hero-with-nav.html` in your theme:

```html
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"0","bottom":"0"}}},"backgroundColor":"contrast","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-contrast-background-color" style="padding-top:0;padding-bottom:0;position:relative;min-height:100vh;">
  
  <!-- wp:shortcode -->
  [patterson_navigation]
  <!-- /wp:shortcode -->
  
  <!-- wp:group {"style":{"spacing":{"padding":{"top":"150px"}}}} -->
  <div class="wp-block-group" style="padding-top:150px">
    <!-- Your hero content blocks -->
  </div>
  <!-- /wp:group -->
  
</div>
<!-- /wp:group -->
```

2. Include in your header template

### Key Requirements

✅ **Full-width container** - Navigation must span entire viewport width  
✅ **Position relative** on parent - Navigation uses absolute positioning  
✅ **Hero content padding** - Add ~150px top padding to account for navigation height (134px + spacing)  
✅ **Dark background** - Navigation text is white, needs dark background for contrast

## 5. Test Your Navigation

Verify the following:

- [ ] Navigation appears on your site
- [ ] Both universal and main navigation bars visible
- [ ] Dropdown menus open when clicking parent items
- [ ] Mobile hamburger menu appears and works (resize browser to <1420px)
- [ ] Keyboard navigation works (Tab, Enter, Escape keys)
- [ ] Brand color appears correctly
- [ ] Search and CTA button work (if enabled)

## Troubleshooting

### Navigation not showing
- Verify plugin is activated
- Check you added navigation to your theme (shortcode or PHP function)
- Ensure menus are assigned in plugin settings
- Verify parent container has `position: relative`

### Navigation not full width (Block Themes)
- Ensure container block is set to "Full Width" alignment
- Check that container has `width: 100vw` or is using `alignfull` class
- Remove any max-width constraints on parent containers

### Dropdowns cut off or hidden
- Remove `overflow: hidden` from parent containers
- Check z-index values aren't interfering
- Verify navigation CSS is loading

### Mobile menu doesn't work
- Check browser console for JavaScript errors
- Ensure viewport is below breakpoint (default: 1420px)
- Test on actual mobile device, not just browser resize
- Verify JavaScript file is loading

### Text hard to read
- Ensure hero background is dark enough (navigation text is white)
- Add dark overlay to hero: `background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('hero.jpg')`
- Check background image contrast

## Additional Resources

- [Configuration Guide](wordpress-configuration.md) - Complete settings reference and menu setup
- [Design Tokens README](../design-tokens/README.md) - Customizing colors and spacing
