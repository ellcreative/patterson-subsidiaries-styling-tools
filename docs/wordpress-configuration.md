# WordPress Plugin Configuration

This document covers all configuration options available in the Patterson Navigation WordPress plugin.

## Admin Settings Location

Navigate to **WordPress Admin** → **Patterson Nav**

## General Settings

### Enable Universal Nav
- **Type**: Checkbox
- **Default**: Unchecked
- **Description**: Enables the Patterson Companies universal navigation bar that appears above the main navigation

## Universal Navigation Settings

### Universal Nav Menu
- **Type**: Menu Selector
- **Default**: None
- **Description**: Select which WordPress menu to display in the universal navigation bar
- **Note**: Create a menu in Appearance → Menus first

## Main Navigation Settings

### Main Nav Menu
- **Type**: Menu Selector
- **Default**: None
- **Description**: Select which WordPress menu to use for the main navigation
- **Note**: Supports dropdowns and featured content

### Brand Logo Settings

#### Enable Brand Logo
- **Type**: Checkbox
- **Default**: Unchecked
- **Description**: Shows your brand logo before the navigation menu items

#### Brand Logo URL
- **Type**: Media Upload
- **Default**: Empty
- **Description**: Upload or select your brand logo from the media library
- **Recommended Format**: SVG or PNG with transparent background
- **Recommended Size**: 198px × 24px
- **Note**: Click "Upload Logo" to open the media library

#### Logo Width (px)
- **Type**: Number
- **Default**: 198
- **Range**: 1-500
- **Description**: The width of your logo in pixels

#### Logo Height (px)
- **Type**: Number
- **Default**: 24
- **Range**: 1-500
- **Description**: The height of your logo in pixels

### Search Settings

#### Enable Search
- **Type**: Checkbox
- **Default**: Unchecked
- **Description**: Shows a search button in the navigation

#### Search Code/Shortcode
- **Type**: Textarea
- **Default**: Empty
- **Description**: Enter a shortcode (e.g., `[search_form]`) or custom HTML/JavaScript code
- **Example Shortcodes**:
  - `[search_form]` - Default WordPress search
  - `[your_search_plugin_shortcode]` - Third-party search plugin
- **Example HTML**: Custom search modal trigger button

### CTA Button Settings

#### Enable CTA Button
- **Type**: Checkbox
- **Default**: Unchecked
- **Description**: Shows a call-to-action button in the navigation

#### CTA Button Text
- **Type**: Text
- **Default**: Empty
- **Description**: The text to display on the CTA button
- **Example**: "Contact", "Get Started", "Request Demo"

#### CTA Button URL
- **Type**: Text
- **Default**: Empty
- **Description**: The URL the CTA button links to
- **Example**: `/contact`, `https://yoursite.com/demo`

## Design & Branding Settings

### Mobile Breakpoint (px)
- **Type**: Number
- **Default**: 1420
- **Range**: 320-2000
- **Description**: The viewport width (in pixels) at which the navigation switches to the mobile hamburger menu
- **Note**: At this width and below, the desktop menu is hidden and the hamburger icon appears
- **Recommended**: 
  - 1420px - Default, works well for most sites
  - 1280px - If you have fewer menu items
  - 1600px - If you have many menu items or longer text
- **How it works**: When a custom breakpoint is set (different from 1420px), the plugin generates inline CSS to override the default breakpoint

### Brand Primary Color
- **Type**: Color Picker
- **Default**: #e51b24 (Patterson Red)
- **Description**: Your brand's primary color used for:
  - CTA button background
  - Focus states
  - Active navigation states
  - Hover effects
- **Format**: Hex color code (e.g., #06929f)

### Load Design Tokens File
- **Type**: Checkbox
- **Default**: Unchecked
- **Description**: Loads an external design tokens CSS file

### Design Tokens URL
- **Type**: Text
- **Default**: Empty
- **Description**: URL to your design tokens CSS file
- **Example**: `/wp-content/themes/yourtheme/assets/css/tokens.css`
- **Note**: Only loads if "Load Design Tokens File" is enabled

## Menu Configuration

### Setting Up Dropdown Menus with Descriptions and Featured Content

The navigation supports enhanced dropdown menus with item descriptions and featured content sections.

#### Enable Custom Fields in Menu Editor

**Important**: Before you can see the custom fields, you must enable them:

1. Go to **Appearance** → **Menus**
2. Click **Screen Options** at the top right of the page
3. Check the **"Description"** checkbox
4. The Patterson custom fields will now appear when you expand menu items

#### Menu Item Descriptions (Child Items Only)

**What**: Short descriptions that appear below menu item titles in dropdown menus

**Where**: Only available on **child items** (sub-menu items), not on top-level items

**How to add**:
1. In the menu editor, expand a **child menu item** (indented item)
2. Scroll down to find the blue box: **"📝 Patterson Nav: Menu Item Description"**
3. Enter a short description (1-2 sentences)
4. Save the menu

**Example**:
```
Menu Item: Dashboard
Description: Access real-time insights and analytics
```

#### Featured Content (Top-Level Items Only)

**What**: A featured content section that appears on the right side of mega-menu dropdowns

**Where**: Available on all **top-level menu items** (but only displays in dropdowns that have child items)

**How to add**:
1. In the menu editor, expand a **top-level menu item** that has children
2. Scroll down to find the yellow box: **"⭐ Enable Featured Content for Mega Menu"**
3. Check the checkbox to enable featured content
4. Fill in the featured content fields:
   - **Featured Image URL**: Click "Select Image" to choose an image
   - **Featured Title**: Main heading for the featured content
   - **Featured Description**: Text description
   - **Featured Link Text**: Call-to-action text (e.g., "Learn More")
   - **Featured Link URL**: Where the link goes
5. Save the menu

**Best Practices**:
- Use high-quality images (recommended: 455px wide × 255px tall)
- Keep titles short and compelling
- Descriptions should be 2-3 sentences max
- Use descriptive link text (not just "More" or "Learn More")

**Example**:
```
Featured Title: New Product Launch
Featured Description: Discover our latest innovation in drilling technology with enhanced performance and reliability.
Featured Link Text: View Product Details
Featured Link URL: /products/new-drill-system
```

## Usage

After configuring the settings, add the navigation to your theme:

### PHP Function
```php
<?php patterson_nav(); ?>
```

### Shortcode
```
[patterson_navigation]
```

## Typical Configuration Examples

### Example 1: Basic Setup (No Logo)
```
✓ Enable Universal Nav
  Universal Nav Menu: Patterson Companies Menu
  Main Nav Menu: Main Navigation
✓ Enable Search
  Search Code: [search_form]
✓ Enable CTA Button
  CTA Text: Contact
  CTA URL: /contact
  Brand Primary Color: #06929f
  Mobile Breakpoint: 1420
```

### Example 2: With Brand Logo
```
✓ Enable Universal Nav
  Universal Nav Menu: Patterson Companies Menu
  Main Nav Menu: Main Navigation
✓ Enable Brand Logo
  Brand Logo URL: [uploaded-logo.svg]
  Logo Width: 198
  Logo Height: 24
✓ Enable Search
  Search Code: [custom_search]
✓ Enable CTA Button
  CTA Text: Get Started
  CTA URL: /get-started
  Brand Primary Color: #e51b24
  Mobile Breakpoint: 1420
```

### Example 3: Narrow Viewport (Fewer Items)
```
✓ Enable Universal Nav
  Universal Nav Menu: Patterson Companies Menu
  Main Nav Menu: Simple Navigation (3 items)
  [No Logo]
✓ Enable CTA Button
  CTA Text: Login
  CTA URL: /login
  Brand Primary Color: #333333
  Mobile Breakpoint: 1280
```

### Example 4: Wide Viewport (Many Items)
```
✓ Enable Universal Nav
  Universal Nav Menu: Patterson Companies Menu
  Main Nav Menu: Full Navigation (7 items)
✓ Enable Brand Logo
  Brand Logo URL: [long-logo.svg]
  Logo Width: 250
  Logo Height: 30
✓ Enable Search
✓ Enable CTA Button
  CTA Text: Contact Sales
  CTA URL: /contact-sales
  Brand Primary Color: #0066cc
  Mobile Breakpoint: 1600
```

## Best Practices

### Menu Structure
- **Universal Nav**: 4-6 items max (About, Brands, Investors, Careers, etc.)
- **Main Nav**: 5-7 top-level items with dropdowns
- **Dropdown Items**: 4-10 items per dropdown, split into 2 columns

### Brand Logo
- Use SVG format for best quality and performance
- Optimize file size (aim for <50KB)
- Ensure logo works on dark backgrounds (navigation has dark overlay)
- Test logo visibility on various hero backgrounds

### Mobile Breakpoint
- Test with actual content and menu items
- Consider logo width + menu items + search + CTA
- Check on real devices, not just browser resize
- If items wrap or overlap, increase breakpoint

### Colors
- Ensure sufficient contrast with white text (WCAG AA minimum)
- Test CTA button visibility
- Consider your brand guidelines

### Search Integration
- Test search functionality after setup
- Ensure search modal/overlay has proper z-index
- Consider using a dedicated search plugin for better UX

## Troubleshooting

### Navigation Not Showing
- Verify you've added `<?php patterson_nav(); ?>` to your theme
- Check that menus are assigned in settings
- Ensure navigation CSS is loading

### Mobile Menu Not Switching
- Check your mobile breakpoint setting
- Clear browser cache
- Test on actual device, not just browser resize
- Ensure JavaScript is loading without errors

### Logo Not Displaying
- Verify "Enable Brand Logo" is checked
- Ensure logo URL is set and valid
- Check file permissions on the logo file
- Try re-uploading the logo

### CTA Button Wrong Color
- Check "Brand Primary Color" setting
- Clear cache
- Inspect element to verify CSS is loading

### Search Not Working
- Verify search code/shortcode is correct
- Test shortcode in a post/page first
- Check for JavaScript errors in console
- Ensure search plugin (if used) is active

## Advanced Customization

### Custom CSS
You can add custom CSS to override default styles:

```css
/* In your theme's CSS file or Customizer */

/* Adjust mobile breakpoint via CSS (not recommended, use admin setting) */
@media (max-width: 1500px) {
  .main-nav__menu { display: none !important; }
  .main-nav__mobile-toggle { display: flex !important; }
}

/* Custom logo spacing */
.main-nav__brand-logo {
  margin-inline-end: 2rem;
}

/* Custom CTA button style */
.main-nav__cta {
  border-radius: 4px;
  text-transform: none;
}
```

### PHP Filters
Developers can use filters to modify navigation behavior:

```php
// Change default mobile breakpoint programmatically
add_filter('patterson_nav_mobile_breakpoint', function($breakpoint) {
    return 1500; // px
});

// Modify navigation options
add_filter('patterson_nav_options', function($options) {
    $options['brand_color'] = '#custom';
    return $options;
});
```

## Performance Tips

1. **Optimize Logo**: Use SVG when possible, or optimized PNG
2. **Cache**: Ensure page caching is enabled
3. **Minimize Menus**: Don't create overly complex menu structures
4. **CDN**: Serve assets through a CDN if possible
5. **Lazy Load**: Hero background images should use lazy loading

## Accessibility

The navigation is built to WCAG 2.2 AA standards:
- Keyboard navigation supported
- Proper ARIA labels and states
- Focus indicators on all interactive elements
- Reduced motion support
- Screen reader friendly

Test with:
- Keyboard only (Tab, Enter, Escape)
- Screen reader (NVDA, JAWS, VoiceOver)
- Browser zoom (200%+)
- Various viewport sizes

