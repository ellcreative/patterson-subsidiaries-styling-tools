# Brand Logo Feature

## Overview

The Patterson navigation system now supports an optional brand logo that can be displayed before the main navigation menu items. This feature is completely optional and can be easily enabled or disabled per brand.

## Visual Design

Based on the Figma design (Frame 186:280), the brand logo includes:

- **Logo image**: Displays on the left side of the navigation
- **Vertical divider**: A subtle line separating the logo from menu items
- **Desktop sizing**: Logo max height of 24px, recommended width ~198px
- **Mobile sizing**: Logo max height of 20px, appears next to hamburger menu

## Implementation

### Static HTML / Prototypes

To add a brand logo to the static HTML navigation:

```html
<nav class="main-nav" aria-label="Main navigation">
  <div class="nav-container">
    
    <!-- Optional: Brand Logo -->
    <div class="main-nav__brand-logo">
      <a href="/" aria-label="Your Brand Home">
        <img src="path/to/logo.svg" alt="Your Brand" width="198" height="24">
      </a>
      <div class="main-nav__brand-divider" aria-hidden="true"></div>
    </div>

    <!-- Rest of navigation... -->
  </div>
</nav>
```

**To remove:** Simply delete the entire `.main-nav__brand-logo` section.

### WordPress Plugin

The WordPress plugin includes full support for the brand logo through the admin settings.

#### Configuring the Logo

1. Go to **WordPress Admin** → **Patterson Nav**
2. Under **Main Navigation** section, find the **Brand Logo** settings:
   - **Enable Brand Logo**: Check to enable the logo feature
   - **Brand Logo URL**: Click "Upload Logo" to select from media library
   - **Logo Width (px)**: Enter the logo width (default: 198px)
   - **Logo Height (px)**: Enter the logo height (default: 24px)
3. Click **Save Changes**

#### Logo Upload

The plugin includes a WordPress media uploader integration:
- Click the "Upload Logo" button
- Select an existing image or upload a new one
- SVG and PNG formats recommended
- The logo preview will display below the upload button

#### Settings Storage

The logo settings are stored in the WordPress options table:
- `brand_logo_enabled`: Boolean (1/0)
- `brand_logo_url`: Full URL to the logo image
- `brand_logo_width`: Integer (pixels)
- `brand_logo_height`: Integer (pixels)

#### PHP Rendering

The logo is automatically rendered when enabled. The renderer checks:

```php
<?php if (!empty($options['brand_logo_enabled']) && !empty($options['brand_logo_url'])) : ?>
    <!-- Brand Logo renders here -->
<?php endif; ?>
```

## Responsive Behavior

### Desktop (>1420px)
- Logo displays on the left side
- Vertical divider separates logo from menu items
- Logo maintains specified dimensions (max 24px height)
- 41px spacing between logo and divider, and divider and menu

### Mobile (≤1420px)
- Logo appears to the left of the hamburger menu
- Vertical divider is hidden
- Logo scales down (max 20px height)
- Maintains proportional width
- Mobile menu toggle remains on the right

## CSS Classes

### `.main-nav__brand-logo`
Container for the logo and divider
- `display: flex`
- `align-items: center`
- `gap: 41px`
- `margin-inline-end: 41px`

### `.main-nav__brand-divider`
Vertical divider line
- `inline-size: 1px`
- `block-size: 85px`
- `background: oklch(1 0 0 / 0.6)`
- `transform: rotate(90deg)`
- Hidden on mobile

### Mobile Adjustments
```css
@media (max-width: 1420px) {
  .main-nav__brand-divider {
    display: none;
  }
  
  .main-nav__brand-logo {
    margin-inline-end: auto;
    gap: var(--space-4);
  }
  
  .main-nav__brand-logo img {
    max-block-size: 20px;
  }
}
```

## Best Practices

### Logo Format
- **SVG**: Best choice for scalability and file size
- **PNG**: Use with transparent background
- **Size**: Optimize for web (< 50KB recommended)

### Logo Dimensions
- **Recommended**: 198px × 24px
- **Aspect ratio**: Maintain original to avoid distortion
- **Height**: Maximum 24px on desktop, 20px on mobile
- **Width**: Flexible, but ~200px works well

### Accessibility
- Always include `alt` text describing the brand
- Use descriptive `aria-label` on the link
- Ensure sufficient color contrast with background

### Logo Design
- Works best with horizontal/landscape logos
- Light colored logos (white/light) for dark navigation backgrounds
- Consider creating a navigation-specific logo variant if needed
- Test visibility on various background images

## Examples

### Example 1: Ulterra Brand
```html
<div class="main-nav__brand-logo">
  <a href="/" aria-label="Ulterra Home">
    <img src="/assets/ulterra-logo.svg" alt="Ulterra" width="198" height="24">
  </a>
  <div class="main-nav__brand-divider" aria-hidden="true"></div>
</div>
```

### Example 2: No Logo (Standard Layout)
Simply omit the `.main-nav__brand-logo` section entirely:

```html
<nav class="main-nav" aria-label="Main navigation">
  <div class="nav-container">
    
    <!-- Mobile Menu Toggle -->
    <button class="main-nav__mobile-toggle">...</button>
    
    <!-- Desktop Menu Items -->
    <ul class="main-nav__menu">...</ul>
  </div>
</nav>
```

## Browser Support

The brand logo feature uses CSS logical properties and modern layout techniques:
- Chrome 76+
- Edge 17+
- Firefox 103+
- Safari 9+

Legacy browser support is maintained through the existing navigation system.

## Troubleshooting

### Logo not displaying
- Check that `brand_logo_enabled` is true (WordPress)
- Verify the logo URL is correct and accessible
- Ensure the HTML structure is correct

### Logo too large/small
- Adjust the `width` and `height` attributes
- Check CSS max-height constraints (24px desktop, 20px mobile)
- Verify aspect ratio is maintained

### Divider not showing
- Ensure `.main-nav__brand-divider` element is present
- Check that you're viewing on desktop (hidden on mobile)
- Verify background color has sufficient contrast

### Mobile layout issues
- Logo should appear left of hamburger menu
- Check viewport width (breakpoint at 1420px)
- Ensure mobile styles are loading correctly

## Migration Guide

### Adding Logo to Existing Sites

1. **Update CSS**: Copy the latest `navigation.css` to your theme
2. **Update HTML**: Add the `.main-nav__brand-logo` section
3. **Add Logo**: Upload your brand logo
4. **Test**: Verify on desktop and mobile viewports

### WordPress Plugin Update

If you're using the WordPress plugin:

1. Update the plugin files:
   - `includes/class-admin.php`
   - `includes/class-renderer.php`
   - `assets/css/navigation.css`
2. Go to **Patterson Nav** settings
3. Enable and configure the brand logo
4. Save settings

## Future Enhancements

Potential future improvements:
- Support for different logos for light/dark themes
- Logo positioning options (left, center)
- Custom divider styles per brand
- Retina/responsive image support

