# Implementation Guide

## Navigation Structure

The navigation system consists of two parts:
1. **Universal Nav** - Patterson Companies top bar (dark background)
2. **Main Nav** - Your brand's navigation (transparent with border)

Both are contained in a `<header class="site-navigation">` element.

## Positioning Requirements

The navigation is designed to **overlay your hero/header section** using absolute positioning.

### Essential HTML Structure

```html
<div class="your-hero-wrapper" style="position: relative;">
  
  <?php patterson_nav(); ?>
  
  <div class="hero-content" style="padding-top: 150px;">
    <h1>Your Title</h1>
    <p>Your content...</p>
  </div>
  
</div>
```

### Key CSS Requirements

```css
/* Your hero section must have position: relative */
.your-hero-wrapper {
  position: relative;
  min-height: 100vh;
  background: url('hero.jpg') center/cover;
}

/* Your hero content needs top padding to account for nav */
.hero-content {
  padding-top: 150px; /* Accounts for 134px nav height + spacing */
}
```

## Navigation Height

- **Universal Nav:** 46px
- **Main Nav:** 88px (desktop) / 64px (mobile)
- **Total:** 134px (desktop) / 110px (mobile)

Account for this in your hero content positioning.

## Brand Customization

### Primary Color

Set your brand color in theme CSS or design tokens:

```css
:root {
  --primary-color: #YOUR_BRAND_COLOR;
}
```

This color is used for:
- CTA button background
- Focus indicators
- Hover states

### Brand Logo (Optional)

Enable in **Patterson Nav** settings:
- Upload your logo (SVG recommended)
- Set dimensions (default: 198px × 24px)
- Logo displays before menu items on desktop
- Scales down on mobile (max 20px height)

## Common Implementation Patterns

### Pattern 1: Simple Hero with Image

```php
<div class="hero" style="position: relative; background: url('bg.jpg') center/cover; min-height: 100vh;">
  <?php patterson_nav(); ?>
  
  <div style="padding: 200px 80px;">
    <h1 style="color: white; font-size: 96px;">Welcome</h1>
  </div>
</div>
```

### Pattern 2: Video Background

```php
<div class="video-hero" style="position: relative; overflow: hidden; height: 100vh;">
  <?php patterson_nav(); ?>
  
  <video autoplay muted loop playsinline 
         style="position: absolute; width: 100%; height: 100%; object-fit: cover; z-index: 0;">
    <source src="hero.mp4" type="video/mp4">
  </video>
  
  <div style="position: relative; z-index: 1; padding-top: 200px;">
    <h1 style="color: white;">Your Content</h1>
  </div>
</div>
```

### Pattern 3: Solid Color Background

```php
<div class="hero" style="position: relative; background: #333; min-height: 80vh;">
  <?php patterson_nav(); ?>
  
  <div style="padding: 180px 80px; color: white;">
    <h1>Your Title</h1>
    <p>Your description...</p>
  </div>
</div>
```

## Mobile Considerations

At ≤1420px (or your custom breakpoint):
- Desktop menu becomes hamburger icon
- Mobile slide-in panel activated
- Logo scales down (if enabled)
- Navigation height reduces slightly

## Z-Index Management

The navigation uses `z-index: 1030` (fixed).

If your hero has interactive elements (videos, carousels), ensure:
```css
.your-interactive-element {
  z-index: 1; /* Lower than navigation */
}
```

## Best Practices

### Do's
✅ Wrap navigation in `position: relative` container  
✅ Account for 134px nav height in hero content  
✅ Use white/light text on main nav items (designed for dark backgrounds)  
✅ Set your brand's primary color  
✅ Test on multiple viewport sizes

### Don'ts
❌ Place navigation in container with `overflow: hidden`  
❌ Forget to add `position: relative` to parent  
❌ Use dark text colors on main nav  
❌ Block navigation with high z-index elements

## Troubleshooting

**Navigation not visible**
- Add `position: relative` to parent container
- Check z-index isn't being overridden
- Verify CSS files are loading

**Text hard to read**
- Check background image brightness
- Consider adding dark overlay: `background: rgba(0,0,0,0.4);`
- Adjust transparency on the universal nav

**Dropdowns cut off**
- Remove `overflow: hidden` from parent containers
- Verify dropdown z-index is higher than other elements

**Mobile menu doesn't open**
- Check browser console for JavaScript errors
- Verify breakpoint setting
- Test on actual mobile device

## Advanced Customization

### Custom CSS Override

```css
/* Adjust CTA button style */
.main-nav__cta {
  border-radius: 4px;
  padding: 12px 32px;
}

/* Custom logo spacing */
.main-nav__brand-logo {
  margin-inline-end: 3rem;
}

/* Adjust mobile breakpoint via CSS (use admin setting instead) */
@media (max-width: 1500px) {
  .main-nav__menu {
    display: none !important;
  }
}
```

### PHP Customization

```php
// Programmatically modify settings
add_filter('patterson_nav_options', function($options) {
    $options['mobile_breakpoint'] = 1500;
    return $options;
});
```

## Need Help?

- Check [WordPress Configuration](wordpress-configuration.md) for all settings
- Review [Installation Guide](installation.md) for setup steps
- Contact development team for support
